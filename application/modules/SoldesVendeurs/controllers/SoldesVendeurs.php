<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SoldesVendeurs extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SoldesVendeurs_model');
        $this->load->model('Vendeurs_model');
        $this->load->model('PaiementsVendeurs_model');
        $this->load->library('pagination');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    private function is_admin() {
        $user_id = $this->session->userdata('id_utilisateur');
        $this->db->select('up.id_profil')
                 ->from('utilisateur_profils up')
                 ->where('up.id_utilisateur', $user_id)
                 ->where_in('up.id_profil', [1, 2, 3, 7]); // admin, super_admin, moderateur, finance
        return $this->db->get()->num_rows() > 0;
    }

    private function is_vendeur() {
        $user_id = $this->session->userdata('id_utilisateur');
        $this->db->select('up.id_profil')
                 ->from('utilisateur_profils up')
                 ->where('up.id_utilisateur', $user_id)
                 ->where('up.id_profil', 4);
        return $this->db->get()->num_rows() > 0;
    }

    private function get_user_id() {
        return $this->session->userdata('id_utilisateur');
    }

    private function get_vendeur_id_by_user() {
        $user_id = $this->get_user_id();
        $vendeur = $this->db->where('id_utilisateur', $user_id)->get('vendeurs')->row();
        return $vendeur ? $vendeur->id_vendeur : null;
    }

    /**
     * Liste des soldes (Admin)
     */
    public function index() {
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
        
        $data['title'] = 'Gestion des soldes vendeurs';
        
        $filters = [
            'id_vendeur' => $this->input->get('id_vendeur'),
            'statut' => $this->input->get('statut'),
            'solde_min' => $this->input->get('solde_min'),
            'solde_max' => $this->input->get('solde_max')
        ];
        
        $config['base_url'] = base_url('soldes-vendeurs/index');
        $config['total_rows'] = $this->SoldesVendeurs_model->count_all($filters);
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
        
        $data['soldes'] = $this->SoldesVendeurs_model->get_all(
            $config['per_page'],
            $page,
            $filters
        );
        
        $data['stats'] = $this->SoldesVendeurs_model->get_stats($filters);
        $data['vendeurs'] = $this->db->select('id_vendeur, nom_boutique')->get('vendeurs')->result();
        $data['filters'] = $filters;
        
        $this->load->view('soldes_vendeurs_list', $data);
    }

    /**
     * Mon solde (Vendeur)
     */
    public function mon_solde() {
        if (!$this->is_vendeur()) {
            show_error('Accès réservé aux vendeurs', 403);
        }
        
        $id_vendeur = $this->get_vendeur_id_by_user();
        
        if (!$id_vendeur) {
            show_error('Vous n\'êtes pas associé à une boutique', 403);
        }
        
        $data['solde'] = $this->SoldesVendeurs_model->get_by_vendeur($id_vendeur);
        $data['vendeur'] = $this->Vendeurs_model->get_by_id($id_vendeur);
        $data['historique_paiements'] = $this->PaiementsVendeurs_model->get_by_vendeur($id_vendeur, 10);
        $data['title'] = 'Mon solde - ' . $data['vendeur']->nom_boutique;
        
        $this->load->view('soldes_vendeurs_mon_solde', $data);
    }

    /**
     * Détail d'un solde
     */
    public function detail($id_solde) {
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
        
        $data['solde'] = $this->SoldesVendeurs_model->get_by_id_with_details($id_solde);
        
        if (!$data['solde']) {
            show_404();
        }
        
        $data['vendeur'] = $this->Vendeurs_model->get_by_id($data['solde']->id_vendeur);
        $data['historique_paiements'] = $this->PaiementsVendeurs_model->get_by_vendeur($data['solde']->id_vendeur);
        $data['title'] = 'Détail du solde - ' . $data['vendeur']->nom_boutique;
        
        $this->load->view('soldes_vendeurs_detail', $data);
    }

    /**
     * Mettre à jour le solde (Admin)
     */
    public function update_solde() {
        if (!$this->is_admin()) {
            show_error('Accès non autorisé', 403);
        }
        
        $id_vendeur = $this->input->post('id_vendeur');
        $solde_disponible = $this->input->post('solde_disponible');
        $solde_en_attente = $this->input->post('solde_en_attente');
        
        $data = [];
        if ($solde_disponible !== null) $data['solde_disponible'] = $solde_disponible;
        if ($solde_en_attente !== null) $data['solde_en_attente'] = $solde_en_attente;
        
        if ($this->SoldesVendeurs_model->update($id_vendeur, $data)) {
            $this->session->set_flashdata('success', 'Solde mis à jour avec succès');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la mise à jour');
        }
        
        redirect('soldes-vendeurs');
    }

    /**
     * Recalculer le solde d'un vendeur
     */
    public function recalculer($id_vendeur) {
        if (!$this->is_admin()) {
            show_error('Accès non autorisé', 403);
        }
        
        $result = $this->SoldesVendeurs_model->recalculer_solde($id_vendeur);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Solde recalculé avec succès');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors du calcul');
        }
        
        redirect('soldes-vendeurs');
    }

    /**
     * Recalculer tous les soldes
     */
    public function recalculer_tous() {
        if (!$this->is_admin()) {
            show_error('Accès non autorisé', 403);
        }
        
        $vendeurs = $this->db->get('vendeurs')->result();
        $count = 0;
        
        foreach ($vendeurs as $v) {
            if ($this->SoldesVendeurs_model->recalculer_solde($v->id_vendeur)) {
                $count++;
            }
        }
        
        $this->session->set_flashdata('success', $count . ' soldes recalculés avec succès');
        redirect('soldes-vendeurs');
    }

    /**
     * Exporter les soldes
     */
    public function exporter() {
        if (!$this->is_admin()) {
            show_error('Accès non autorisé', 403);
        }
        
        $filters = [
            'id_vendeur' => $this->input->get('id_vendeur'),
            'solde_min' => $this->input->get('solde_min')
        ];
        
        $soldes = $this->SoldesVendeurs_model->get_all(null, null, $filters);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=soldes_vendeurs_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID Vendeur', 'Boutique', 'Solde disponible', 'Solde en attente', 'Total gagné', 'Total retiré', 'Dernier paiement']);
        
        foreach ($soldes as $s) {
            fputcsv($output, [
                $s->id_vendeur,
                $s->nom_boutique,
                number_format($s->solde_disponible, 0, ',', ' ') . ' FBu',
                number_format($s->solde_en_attente, 0, ',', ' ') . ' FBu',
                number_format($s->total_gagne, 0, ',', ' ') . ' FBu',
                number_format($s->total_retire, 0, ',', ' ') . ' FBu',
                $s->dernier_paiement ? date('d/m/Y', strtotime($s->dernier_paiement)) : '-'
            ]);
        }
        
        fclose($output);
    }
}
?>