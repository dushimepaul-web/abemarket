<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SuiviGps extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SuiviGps_model');
        $this->load->model('Commande_model');
        $this->load->model('Transporteurs_model');
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
                 ->where_in('up.id_profil', [1, 2, 3]);
        return $this->db->get()->num_rows() > 0;
    }

    private function get_transporteur_id() {
        $user_id = $this->session->userdata('id_utilisateur');
        $transporteur = $this->db->where('id_utilisateur', $user_id)->get('transporteurs')->row();
        return $transporteur ? $transporteur->id_transporteur : null;
    }

    /**
     * Liste des suivis GPS (Admin)
     */
    public function index() {
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
        
        $data['title'] = 'Suivi GPS des livraisons';
        
        $filters = [
            'id_transporteur' => $this->input->get('id_transporteur'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin')
        ];
        
        $config['base_url'] = base_url('suivi-gps/index');
        $config['total_rows'] = $this->SuiviGps_model->count_all($filters);
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
        
        $data['suivis'] = $this->SuiviGps_model->get_all(
            $config['per_page'],
            $page,
            $filters
        );
        
        $data['stats'] = $this->SuiviGps_model->get_stats($filters);
        $data['transporteurs'] = $this->db->get('transporteurs')->result();
        $data['filters'] = $filters;
        
        $this->load->view('suivi_gps_list', $data);
    }

    /**
     * Carte de suivi en direct
     */
    public function carte() {
        $data['title'] = 'Carte de suivi des livraisons';
        $data['transporteurs'] = $this->Transporteurs_model->get_disponibles();
        $data['commandes'] = $this->db->where_in('statut_commande', ['expedie', 'en_livraison'])
                                   ->get('commandes')
                                   ->result();
        
        $this->load->view('suivi_gps_carte', $data);
    }

    /**
     * API - Dernière position d'un transporteur
     */
    public function derniere_position($id_transporteur) {
        $this->output->set_content_type('application/json');
        
        $position = $this->SuiviGps_model->get_derniere_position($id_transporteur);
        
        if ($position) {
            echo json_encode(['success' => true, 'data' => $position]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Position non trouvée']);
        }
    }

    /**
     * API - Historique des positions d'un transporteur
     */
    public function historique_positions($id_transporteur) {
        $this->output->set_content_type('application/json');
        
        $date_debut = $this->input->get('date_debut');
        $date_fin = $this->input->get('date_fin');
        
        $positions = $this->SuiviGps_model->get_historique($id_transporteur, $date_debut, $date_fin);
        
        echo json_encode(['success' => true, 'data' => $positions]);
    }

    /**
     * API - Suivi d'une commande
     */
    public function suivi_commande($id_commande) {
        $this->output->set_content_type('application/json');
        
        $suivi = $this->SuiviGps_model->get_suivi_by_commande($id_commande);
        
        if ($suivi) {
            echo json_encode(['success' => true, 'data' => $suivi]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Aucun suivi trouvé']);
        }
    }




/**
 * Formulaire d'ajout/modification
 */
public function add_edit($id_suivi = null) {
    if (!$this->is_admin()) {
        show_error('Accès réservé aux administrateurs', 403);
    }
    
    $data['title'] = $id_suivi ? 'Modifier un point GPS' : 'Ajouter un point GPS';
    
    if ($id_suivi) {
        $data['suivi'] = $this->SuiviGps_model->get_by_id($id_suivi);
        if (!$data['suivi']) {
            show_404();
        }
    }
    
    // Charger les commandes et transporteurs pour les selects
    $data['commandes'] = $this->db->select('c.*, CONCAT(u.prenom, " ", u.nom) as nom_client')
                                   ->from('commandes c')
                                   ->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur')
                                   ->get()->result();
    $data['transporteurs'] = $this->db->get('transporteurs')->result();
    
    $this->load->view('suivi_gps_add_edit', $data);
}

/**
 * Sauvegarder (ajouter ou modifier)
 */
public function save() {
    if (!$this->is_admin()) {
        show_error('Accès réservé aux administrateurs', 403);
    }
    
    $this->load->library('form_validation');
    $this->form_validation->set_rules('id_commande', 'Commande', 'required|integer');
    $this->form_validation->set_rules('id_transporteur', 'Transporteur', 'required|integer');
    $this->form_validation->set_rules('latitude', 'Latitude', 'required|decimal');
    $this->form_validation->set_rules('longitude', 'Longitude', 'required|decimal');
    
    if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata('error', validation_errors());
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    $id_suivi = $this->input->post('id_suivi');
    $timestamp_gps = $this->input->post('timestamp_gps');
    
    $data = [
        'id_commande' => $this->input->post('id_commande'),
        'id_transporteur' => $this->input->post('id_transporteur'),
        'latitude' => $this->input->post('latitude'),
        'longitude' => $this->input->post('longitude'),
        'vitesse_kmh' => $this->input->post('vitesse_kmh') ?: null
    ];
    
    if (!empty($timestamp_gps)) {
        $data['timestamp_gps'] = date('Y-m-d H:i:s', strtotime($timestamp_gps));
    }
    
    if ($id_suivi) {
        // Modification
        $this->db->where('id_suivi', $id_suivi)->update('suivi_gps', $data);
        $this->session->set_flashdata('success', 'Point GPS modifié avec succès');
    } else {
        // Ajout
        $this->db->insert('suivi_gps', $data);
        $id_suivi = $this->db->insert_id();
        $this->session->set_flashdata('success', 'Point GPS ajouté avec succès');
    }
    
    // Mettre à jour la position actuelle du transporteur
    $this->db->where('id_transporteur', $data['id_transporteur'])
             ->update('transporteurs', [
                 'latitude_actuelle' => $data['latitude'],
                 'longitude_actuelle' => $data['longitude'],
                 'derniere_position' => date('Y-m-d H:i:s')
             ]);
    
    redirect('suivi-gps/detail/' . $id_suivi);
}

/**
 * Supprimer un point GPS
 */
public function delete($id_suivi) {
    if (!$this->is_admin()) {
        show_error('Accès réservé aux administrateurs', 403);
    }
    
    $this->db->where('id_suivi', $id_suivi)->delete('suivi_gps');
    $this->session->set_flashdata('success', 'Point GPS supprimé avec succès');
    redirect('suivi-gps');
}

    /**
 * API - Positions actives (dernières 5 minutes)
 */
public function positions_actives() {
    $this->output->set_content_type('application/json');
    
    $positions = $this->SuiviGps_model->get_positions_actives();
    
    // Ajouter les informations de statut pour chaque transporteur
    foreach ($positions as $pos) {
        $transporteur = $this->db->get_where('transporteurs', ['id_transporteur' => $pos->id_transporteur])->row();
        $pos->est_disponible = $transporteur->est_disponible ?? false;
        $pos->statut = $transporteur->statut ?? 'inactif';
        $pos->nom = $pos->transporteur_nom;
    }
    
    echo json_encode(['success' => true, 'data' => $positions]);
}

    /**
     * API - Enregistrer une position (pour l'application livreur)
     */
    public function enregistrer_position() {
        $this->output->set_content_type('application/json');
        
        $token = $this->input->post('token');
        $id_transporteur = $this->input->post('id_transporteur');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $vitesse = $this->input->post('vitesse');
        $id_commande = $this->input->post('id_commande');
        
        // Vérifier l'authentification du livreur
        if (!$this->is_admin() && !$id_transporteur) {
            echo json_encode(['success' => false, 'message' => 'Non autorisé']);
            return;
        }
        
        if (empty($latitude) || empty($longitude)) {
            echo json_encode(['success' => false, 'message' => 'Coordonnées manquantes']);
            return;
        }
        
        $data = [
            'id_transporteur' => $id_transporteur,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'vitesse_kmh' => $vitesse,
            'timestamp_gps' => date('Y-m-d H:i:s')
        ];
        
        if ($id_commande) {
            $data['id_commande'] = $id_commande;
        }
        
        $inserted = $this->SuiviGps_model->ajouter_position($data);
        
        if ($inserted) {
            // Mettre à jour la position du transporteur
            $this->db->where('id_transporteur', $id_transporteur);
            $this->db->update('transporteurs', [
                'latitude_actuelle' => $latitude,
                'longitude_actuelle' => $longitude,
                'derniere_position' => date('Y-m-d H:i:s')
            ]);
            
            echo json_encode(['success' => true, 'message' => 'Position enregistrée']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur']);
        }
    }

    /**
     * Détail du suivi d'une commande
     */
    public function detail($id_commande) {
        $data['commande'] = $this->Commande_model->get_by_id($id_commande);
        
        if (!$data['commande']) {
            show_404();
        }
        
        $data['positions'] = $this->SuiviGps_model->get_suivi_by_commande($id_commande);
        $data['title'] = 'Suivi de la commande #' . $data['commande']->numero_commande;
        
        $this->load->view('suivi_gps_detail', $data);
    }

    /**
     * Exporter les données de suivi
     */
    public function exporter() {
        if (!$this->is_admin()) {
            show_error('Accès non autorisé', 403);
        }
        
        $filters = [
            'id_transporteur' => $this->input->get('id_transporteur'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin')
        ];
        
        $suivis = $this->SuiviGps_model->get_all(null, null, $filters);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=suivi_gps_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Commande', 'Transporteur', 'Latitude', 'Longitude', 'Vitesse', 'Date heure']);
        
        foreach ($suivis as $s) {
            fputcsv($output, [
                $s->id_suivi,
                $s->numero_commande ?? '-',
                $s->transporteur_nom,
                $s->latitude,
                $s->longitude,
                $s->vitesse_kmh ?? '-',
                date('d/m/Y H:i:s', strtotime($s->timestamp_gps))
            ]);
        }
        
        fclose($output);
    }
}
?>