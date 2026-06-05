<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransactionsPaiement extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('TransactionsPaiement_model');
        $this->load->library('pagination');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Vérifier que l'utilisateur est admin ou finance
        if (!$this->is_admin() && !$this->is_finance()) {
            show_error('Accès réservé aux administrateurs et finance', 403);
        }
    }

    private function is_admin() {
        $user_id = $this->session->userdata('id_utilisateur');
        $this->db->select('up.id_profil')
                 ->from('utilisateur_profils up')
                 ->where('up.id_utilisateur', $user_id)
                 ->where_in('up.id_profil', [1, 2, 3]);
        return $this->db->get()->num_rows() > 0;
    }

    private function is_finance() {
        $user_id = $this->session->userdata('id_utilisateur');
        $this->db->select('up.id_profil')
                 ->from('utilisateur_profils up')
                 ->where('up.id_utilisateur', $user_id)
                 ->where('up.id_profil', 7);
        return $this->db->get()->num_rows() > 0;
    }

    /**
     * Liste des transactions
     */
    public function index() {
        $data['title'] = 'Gestion des transactions de paiement';
        
        $filters = [
            'statut' => $this->input->get('statut'),
            'type_transaction' => $this->input->get('type_transaction'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin'),
            'search' => $this->input->get('search')
        ];
        
        $config['base_url'] = base_url('transactions-paiement/index');
        $config['total_rows'] = $this->TransactionsPaiement_model->count_all($filters);
        $config['per_page'] = 30;
        $config['uri_segment'] = 3;
        $config['full_tag_open'] = '<ul class="pagination justify-content-end mb-0">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['attributes'] = ['class' => 'page-link'];
        $config['reuse_query_string'] = true;
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['transactions'] = $this->TransactionsPaiement_model->get_all(
            $config['per_page'],
            $page,
            $filters
        );
        
        $data['stats'] = $this->TransactionsPaiement_model->get_stats($filters);
        $data['filters'] = $filters;
        $data['is_admin'] = $this->is_admin();
        
        $this->load->view('transactions_list', $data);
    }

    /**
     * Détail d'une transaction
     */
    public function detail($id) {
        $data['transaction'] = $this->TransactionsPaiement_model->get_by_id($id);
        
        if (!$data['transaction']) {
            show_404();
        }
        
        // Récupérer la commande directement
        if ($data['transaction']->id_commande) {
            $data['commande'] = $this->db->where('id_commande', $data['transaction']->id_commande)->get('commandes')->row();
        } else {
            $data['commande'] = null;
        }
        
        $data['title'] = 'Détails de la transaction #' . $data['transaction']->reference_interne;
        $data['is_admin'] = $this->is_admin();
        
        $this->load->view('transactions_detail', $data);
    }

    /**
     * Mettre à jour le statut d'une transaction
     */
    public function update_statut($id) {
        $this->output->set_content_type('application/json');
        
        if (!$this->is_admin() && !$this->is_finance()) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $statut = $this->input->post('statut');
        $message_statut = $this->input->post('message_statut');
        $reference_operateur = $this->input->post('reference_operateur');
        
        $update_data = ['statut' => $statut];
        
        if ($message_statut) {
            $update_data['message_statut'] = $message_statut;
        }
        if ($reference_operateur) {
            $update_data['reference_operateur'] = $reference_operateur;
        }
        if ($statut == 'confirme') {
            $update_data['date_confirmation'] = date('Y-m-d H:i:s');
        }
        
        $updated = $this->TransactionsPaiement_model->update($id, $update_data);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Statut mis à jour' : 'Erreur'
        ]);
    }

    /**
     * Supprimer une transaction
     */
    public function delete($id) {
        $this->output->set_content_type('application/json');
        
        if (!$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $deleted = $this->TransactionsPaiement_model->delete($id);
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Transaction supprimée' : 'Erreur'
        ]);
    }

    /**
     * Exporter les transactions
     */
    public function exporter() {
        $filters = [
            'statut' => $this->input->get('statut'),
            'type_transaction' => $this->input->get('type_transaction'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin')
        ];
        
        $transactions = $this->TransactionsPaiement_model->get_all(null, null, $filters);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=transactions_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Référence', 'Commande', 'Client', 'Type', 'Montant', 'Frais', 'Net', 'Statut', 'Mode', 'Date création', 'Date confirmation']);
        
        foreach ($transactions as $t) {
            fputcsv($output, [
                $t->reference_interne,
                $t->numero_commande ?? '-',
                $t->client_nom,
                $t->type_transaction,
                number_format($t->montant, 0, ',', ' ') . ' FBu',
                number_format($t->frais, 0, ',', ' ') . ' FBu',
                number_format($t->montant_net, 0, ',', ' ') . ' FBu',
                $t->statut,
                $t->mode_paiement,
                date('d/m/Y H:i', strtotime($t->date_creation)),
                $t->date_confirmation ? date('d/m/Y H:i', strtotime($t->date_confirmation)) : '-'
            ]);
        }
        
        fclose($output);
    }

    /**
     * Statistiques avancées
     */
    public function statistiques() {
        $data['title'] = 'Statistiques des paiements';
        $data['stats_mensuelles'] = $this->TransactionsPaiement_model->get_stats_mensuelles();
        $data['stats_par_mode'] = $this->TransactionsPaiement_model->get_stats_par_mode();
        $data['stats_par_type'] = $this->TransactionsPaiement_model->get_stats_par_type();
        
        $this->load->view('transactions_stats', $data);
    }
}
?>