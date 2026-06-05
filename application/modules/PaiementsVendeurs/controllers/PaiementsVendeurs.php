<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PaiementsVendeurs extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('PaiementsVendeurs_model');
        $this->load->library('pagination');
        $this->load->library('form_validation');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Vérifier que l'utilisateur est admin
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
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

    /**
     * Liste des paiements vendeurs
     */
    public function index() {
        $data['title'] = 'Gestion des paiements vendeurs';
        
        // Filtres
        $filters = [
            'id_vendeur' => $this->input->get('id_vendeur'),
            'statut' => $this->input->get('statut'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin')
        ];
        
        // Configuration de la pagination
        $config['base_url'] = base_url('paiements-vendeurs/index');
        $config['total_rows'] = $this->PaiementsVendeurs_model->count_all($filters);
        $config['per_page'] = 20;
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
        
        $data['paiements'] = $this->PaiementsVendeurs_model->get_all(
            $config['per_page'],
            $page,
            $filters
        );
        
        $data['stats'] = $this->PaiementsVendeurs_model->get_stats($filters);
        $data['vendeurs'] = $this->db->get('vendeurs')->result();
        $data['filters'] = $filters;
        
        $this->load->view('paiements_vendeurs_list', $data);
    }

    /**
     * Détail d'un paiement
     */
    public function detail($id) {
        $data['paiement'] = $this->PaiementsVendeurs_model->get_by_id($id);
        
        if (!$data['paiement']) {
            show_404();
        }
        
        $data['title'] = 'Détails du paiement - ' . $data['paiement']->vendeur_nom;
        $data['commandes'] = $this->PaiementsVendeurs_model->get_commandes_by_periode(
            $data['paiement']->id_vendeur,
            $data['paiement']->date_debut_periode,
            $data['paiement']->date_fin_periode
        );
        
        $this->load->view('paiements_vendeurs_detail', $data);
    }

    /**
     * Payer un vendeur
     */
    public function payer($id) {
        $this->output->set_content_type('application/json');
        
        $paiement = $this->PaiementsVendeurs_model->get_by_id($id);
        
        if (!$paiement) {
            echo json_encode(['success' => false, 'message' => 'Paiement non trouvé']);
            return;
        }
        
        if ($paiement->statut == 'paye') {
            echo json_encode(['success' => false, 'message' => 'Ce paiement a déjà été effectué']);
            return;
        }
        
        $reference = $this->input->post('reference_transaction');
        $date_paiement = date('Y-m-d H:i:s');
        
        $updated = $this->PaiementsVendeurs_model->update($id, [
            'statut' => 'paye',
            'date_paiement' => $date_paiement,
            'reference_transaction' => $reference
        ]);
        
        if ($updated) {
            echo json_encode([
                'success' => true,
                'message' => 'Paiement marqué comme payé avec succès'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors du paiement'
            ]);
        }
    }

    /**
     * Marquer comme en cours
     */
    public function en_cours($id) {
        $this->output->set_content_type('application/json');
        
        $updated = $this->PaiementsVendeurs_model->update($id, ['statut' => 'en_cours']);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Paiement marqué comme en cours' : 'Erreur'
        ]);
    }

    /**
     * Marquer comme échoué
     */
    public function echoue($id) {
        $this->output->set_content_type('application/json');
        
        $updated = $this->PaiementsVendeurs_model->update($id, ['statut' => 'echoue']);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Paiement marqué comme échoué' : 'Erreur'
        ]);
    }

    /**
     * Générer les paiements pour une période
     */
    public function generer() {
        $data['title'] = 'Générer les paiements vendeurs';
        $data['vendeurs'] = $this->db->get('vendeurs')->result();
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $date_debut = $this->input->post('date_debut');
            $date_fin = $this->input->post('date_fin');
            $id_vendeur = $this->input->post('id_vendeur');
            
            $result = $this->PaiementsVendeurs_model->generer_paiements($date_debut, $date_fin, $id_vendeur);
            
            if ($result['success']) {
                $this->session->set_flashdata('success', $result['message']);
                redirect('paiements-vendeurs');
            } else {
                $this->session->set_flashdata('error', $result['message']);
            }
        }
        
        $this->load->view('paiements_vendeurs_generer', $data);
    }

    /**
     * Exporter les paiements
     */
    public function exporter() {
        $filters = [
            'id_vendeur' => $this->input->get('id_vendeur'),
            'statut' => $this->input->get('statut'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin')
        ];
        
        $paiements = $this->PaiementsVendeurs_model->get_all(null, null, $filters);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=paiements_vendeurs_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Vendeur', 'Période', 'Commandes', 'Revenus', 'Commissions', 'Net', 'Statut', 'Date paiement']);
        
        foreach ($paiements as $p) {
            fputcsv($output, [
                $p->id_paiement,
                $p->vendeur_nom,
                date('d/m/Y', strtotime($p->date_debut_periode)) . ' - ' . date('d/m/Y', strtotime($p->date_fin_periode)),
                $p->nombre_commandes,
                number_format($p->total_revenus, 0, ',', ' ') . ' FBu',
                number_format($p->total_commissions, 0, ',', ' ') . ' FBu',
                number_format($p->montant_net, 0, ',', ' ') . ' FBu',
                $p->statut,
                $p->date_paiement ? date('d/m/Y', strtotime($p->date_paiement)) : '-'
            ]);
        }
        
        fclose($output);
    }

    /**
     * Supprimer un paiement
     */
    public function delete($id) {
        $this->output->set_content_type('application/json');
        
        $deleted = $this->PaiementsVendeurs_model->delete($id);
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Paiement supprimé' : 'Erreur'
        ]);
    }
}
?>