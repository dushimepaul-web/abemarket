<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TentativesConnexion extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('TentativesConnexion_model');
        $this->load->library('pagination');
        
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
     * Liste des tentatives de connexion
     */
    public function index() {
        $data['title'] = 'Historique des tentatives de connexion';
        
        // Filtres
        $filters = [
            'reussie' => $this->input->get('reussie'),
            'ip' => $this->input->get('ip'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin'),
            'search' => $this->input->get('search')
        ];
        
        // Configuration de la pagination
        $config['base_url'] = base_url('tentatives-connexion/index');
        $config['total_rows'] = $this->TentativesConnexion_model->count_all($filters);
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
        
        $data['tentatives'] = $this->TentativesConnexion_model->get_all(
            $config['per_page'],
            $page,
            $filters
        );
        
        $data['stats'] = $this->TentativesConnexion_model->get_stats($filters);
        $data['filters'] = $filters;
        $data['ips_blacklist'] = $this->get_ips_blacklist();
        $data['top_ips'] = $this->TentativesConnexion_model->get_top_ips(10);
        
        $this->load->view('tentatives_connexion_list', $data);
    }

    /**
     * Récupérer les IPs blacklistées
     */
    private function get_ips_blacklist() {
        $this->db->select('b.*, u.prenom, u.nom, u.email')
                 ->from('blacklist_ips b')
                 ->join('utilisateurs u', 'u.id_utilisateur = b.cree_par', 'left')
                 ->order_by('b.date_creation', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Blacklister une adresse IP
     */
    public function blacklister_ip() {
        $this->output->set_content_type('application/json');
        
        $ip = $this->input->post('ip');
        $raison = $this->input->post('raison');
        $duree = $this->input->post('duree');
        
        if (empty($ip)) {
            echo json_encode(['success' => false, 'message' => 'Adresse IP invalide']);
            return;
        }
        
        // Vérifier si l'IP est déjà blacklistée
        $exists = $this->db->where('adresse_ip', $ip)->get('blacklist_ips')->num_rows() > 0;
        
        if ($exists) {
            echo json_encode(['success' => false, 'message' => 'Cette IP est déjà blacklistée']);
            return;
        }
        
        // Calculer la date d'expiration
        $date_fin = null;
        switch ($duree) {
            case '1day':
                $date_fin = date('Y-m-d H:i:s', strtotime('+1 day'));
                break;
            case '1week':
                $date_fin = date('Y-m-d H:i:s', strtotime('+1 week'));
                break;
            case '1month':
                $date_fin = date('Y-m-d H:i:s', strtotime('+1 month'));
                break;
            default:
                $date_fin = null;
        }
        
        $data = [
            'adresse_ip' => $ip,
            'raison' => !empty($raison) ? $raison : 'Tentatives de connexion suspectes',
            'date_fin' => $date_fin,
            'cree_par' => $this->session->userdata('id_utilisateur'),
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        $inserted = $this->db->insert('blacklist_ips', $data);
        
        if ($inserted) {
            echo json_encode([
                'success' => true, 
                'message' => 'IP blacklistée avec succès'
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Erreur lors du blacklistage'
            ]);
        }
    }

    /**
     * Retirer une IP de la blacklist
     */
    public function retirer_blacklist() {
        $this->output->set_content_type('application/json');
        
        $ip = $this->input->post('ip');
        
        if (empty($ip)) {
            echo json_encode(['success' => false, 'message' => 'IP invalide']);
            return;
        }
        
        $deleted = $this->db->where('adresse_ip', $ip)->delete('blacklist_ips');
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'IP retirée de la blacklist' : 'Erreur'
        ]);
    }

    /**
     * Supprimer une tentative
     */
    public function delete($id) {
        $this->output->set_content_type('application/json');
        
        $deleted = $this->TentativesConnexion_model->delete($id);
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Tentative supprimée' : 'Erreur'
        ]);
    }

    /**
     * Supprimer plusieurs tentatives
     */
    public function delete_multiple() {
        $this->output->set_content_type('application/json');
        
        $ids = $this->input->post('ids');
        
        if (empty($ids)) {
            echo json_encode(['success' => false, 'message' => 'Aucune tentative sélectionnée']);
            return;
        }
        
        $deleted = $this->TentativesConnexion_model->delete_multiple($ids);
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Tentatives supprimées' : 'Erreur'
        ]);
    }

    /**
     * Vider l'historique
     */
    public function vider() {
        $this->output->set_content_type('application/json');
        
        $date_limite = $this->input->post('date_limite');
        
        $deleted = $this->TentativesConnexion_model->vider($date_limite);
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Historique vidé' : 'Erreur'
        ]);
    }

    /**
     * Nettoyer les IPs expirées
     */
    public function nettoyer_expirees() {
        $this->output->set_content_type('application/json');
        
        $deleted = $this->db
            ->where('date_fin IS NOT NULL')
            ->where('date_fin <=', date('Y-m-d H:i:s'))
            ->delete('blacklist_ips');
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'IPs expirées supprimées' : 'Aucune IP expirée'
        ]);
    }

    /**
     * Exporter les tentatives
     */
    public function exporter() {
        $filters = [
            'reussie' => $this->input->get('reussie'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin')
        ];
        
        $tentatives = $this->TentativesConnexion_model->get_all(null, null, $filters);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=tentatives_connexion_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Utilisateur', 'Email', 'IP', 'Statut', 'Motif', 'Date']);
        
        foreach ($tentatives as $t) {
            fputcsv($output, [
                $t->id_tentative,
                $t->prenom && $t->nom ? $t->prenom . ' ' . $t->nom : ($t->utilisateur_email ?? 'Visiteur'),
                $t->email_tente ?? '-',
                $t->adresse_ip,
                $t->reussie ? 'Succès' : 'Échec',
                $t->motif_echec ?? '-',
                date('d/m/Y H:i:s', strtotime($t->date_tentative))
            ]);
        }
        
        fclose($output);
    }
}
?>