<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commande extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') === 'vendeur') {
            redirect('Home/User_dashboard');
        }
        $this->load->model('Commande_model');
        $this->load->model('Produit_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }

    private function is_admin() {
        $user_id = $this->session->userdata('id_utilisateur');
        $this->db->select('up.id_profil')
                 ->from('utilisateur_profils up')
                 ->where('up.id_utilisateur', $user_id)
                 ->where_in('up.id_profil', [1, 2, 3]);
        return $this->db->get()->num_rows() > 0;
    }

    private function get_vendeur_id() {
        $user_id = $this->session->userdata('id_utilisateur');
        $vendeur = $this->db->where('id_utilisateur', $user_id)->get('vendeurs')->row();
        return $vendeur ? $vendeur->id_vendeur : null;
    }

    /**
     * Liste des commandes
     */
    public function index() {
        $data['title'] = 'Gestion des commandes';
        $id_vendeur = $this->get_vendeur_id();
        $is_admin = $this->is_admin();
        
        // Filtres
        $filters = [
            'statut_commande' => $this->input->get('statut_commande'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin'),
            'search' => $this->input->get('search')
        ];
        
        // Configuration de la pagination
        $config['base_url'] = base_url('Commande/index');
        $config['total_rows'] = $this->Commande_model->count_all($filters, $id_vendeur, $is_admin);
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
        
        $data['commandes'] = $this->Commande_model->get_all(
            $config['per_page'],
            $page,
            $filters,
            $id_vendeur,
            $is_admin
        );
        
        $data['stats'] = $this->Commande_model->get_stats($id_vendeur, $is_admin);
        $data['filters'] = $filters;
        $data['is_admin'] = $is_admin;
        $data['is_vendeur'] = !$is_admin && $id_vendeur ? true : false;
        
        // Statuts disponibles
        $data['statuts'] = [
            'en_attente' => ['label' => 'En attente', 'color' => 'warning', 'icon' => 'clock-circle'],
            'confirme' => ['label' => 'Confirmée', 'color' => 'info', 'icon' => 'check-circle'],
            'en_preparation' => ['label' => 'En préparation', 'color' => 'primary', 'icon' => 'box'],
            'expedie' => ['label' => 'Expédiée', 'color' => 'secondary', 'icon' => 'delivery'],
            'livre' => ['label' => 'Livrée', 'color' => 'success', 'icon' => 'home'],
            'annule' => ['label' => 'Annulée', 'color' => 'danger', 'icon' => 'close-circle']
        ];
        
        $this->load->view('commandes_list', $data);
    }

    /**
     * Détail d'une commande
     */
    public function detail($id) {
        $data['commande'] = $this->Commande_model->get_by_id($id);
        
        if (!$data['commande']) {
            show_404();
        }
        
        $id_vendeur = $this->get_vendeur_id();
        $is_admin = $this->is_admin();
        
        // Vérifier les droits
        if (!$is_admin && $id_vendeur) {
            // Vendeur - vérifier que la commande contient ses produits
            $article = $this->db->where('id_commande', $id)
                                ->where('id_vendeur', $id_vendeur)
                                ->get('articles_commande')
                                ->row();
            if (!$article) {
                show_error('Accès non autorisé', 403);
            }
        } elseif (!$is_admin && !$id_vendeur) {
            // Client - vérifier que c'est sa commande
            $user_id = $this->session->userdata('id_utilisateur');
            if ($data['commande']->id_utilisateur != $user_id) {
                show_error('Accès non autorisé', 403);
            }
        }
        
        $data['articles'] = $this->Commande_model->get_articles($id, $id_vendeur);
        $data['historique'] = $this->Commande_model->get_historique_statuts($id);
        $data['qr'] = $this->Commande_model->get_qr_by_commande($id);
        $data['is_admin'] = $is_admin;
        $data['is_vendeur'] = !$is_admin && $id_vendeur ? true : false;
        $data['title'] = 'Détails commande #' . $data['commande']->numero_commande;
        
        // Statuts disponibles
        $data['statuts'] = [
            'en_attente' => ['label' => 'En attente', 'color' => 'warning', 'icon' => 'clock-circle'],
            'confirme' => ['label' => 'Confirmée', 'color' => 'info', 'icon' => 'check-circle'],
            'en_preparation' => ['label' => 'En préparation', 'color' => 'primary', 'icon' => 'box'],
            'expedie' => ['label' => 'Expédiée', 'color' => 'secondary', 'icon' => 'delivery'],
            'livre' => ['label' => 'Livrée', 'color' => 'success', 'icon' => 'home'],
            'annule' => ['label' => 'Annulée', 'color' => 'danger', 'icon' => 'close-circle']
        ];
        
        $this->load->view('commandes_detail', $data);
    }

    /**
     * Changer le statut d'une commande (AJAX)
     */
    public function change_statut() {
        $this->output->set_content_type('application/json');
        
        $id_commande = $this->input->post('id_commande');
        $statut = $this->input->post('statut');
        $commentaire = $this->input->post('commentaire');
        
        if (empty($id_commande) || empty($statut)) {
            echo json_encode(['success' => false, 'message' => 'Données manquantes']);
            return;
        }
        
        // Vérifier les droits
        $commande = $this->Commande_model->get_by_id($id_commande);
        if (!$commande) {
            echo json_encode(['success' => false, 'message' => 'Commande non trouvée']);
            return;
        }

        if ($commande->statut_paiement !== 'paye' && $statut !== 'annule') {
            echo json_encode(['success' => false, 'message' => 'La commande ne peut pas être préparée avant confirmation du paiement.']);
            return;
        }
        
        $id_vendeur = $this->get_vendeur_id();
        $is_admin = $this->is_admin();
        
        if (!$is_admin && $id_vendeur) {
            // Vendeur - vérifier que la commande contient ses produits
            $article = $this->db->where('id_commande', $id_commande)
                                ->where('id_vendeur', $id_vendeur)
                                ->get('articles_commande')
                                ->row();
            if (!$article) {
                echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
                return;
            }
        } elseif (!$is_admin && !$id_vendeur) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        // Mettre à jour le statut
        $updated = $this->Commande_model->update_statut($id_commande, $statut);
        
        if ($updated) {
            // Ajouter à l'historique
            $historique_data = [
                'id_commande' => $id_commande,
                'statut' => $statut,
                'commentaire' => $commentaire,
                'modifie_par' => $this->session->userdata('id_utilisateur'),
                'date_creation' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('historique_statut_commande', $historique_data);
            
            echo json_encode(['success' => true, 'message' => 'Statut mis à jour avec succès']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
        }
    }


    /**
 * Modifier une commande (Admin)
 */
public function edit($id) {
    if (!$this->is_admin()) {
        show_error('Accès réservé aux administrateurs', 403);
    }
    
    $data['commande'] = $this->Commande_model->get_by_id($id);
    
    if (!$data['commande']) {
        show_404();
    }
    
    if ($this->input->server('REQUEST_METHOD') === 'POST') {
        $update_data = [
            'statut_commande' => $this->input->post('statut_commande'),
            'statut_paiement' => $this->input->post('statut_paiement'),
            'numero_suivi' => $this->input->post('numero_suivi'),
            'note_interne' => $this->input->post('note_interne')
        ];
        
        if ($this->input->post('date_expedition')) {
            $update_data['date_expedition'] = date('Y-m-d H:i:s', strtotime($this->input->post('date_expedition')));
        }
        
        $updated = $this->Commande_model->update($id, $update_data);
        
        if ($updated) {
            // Ajouter à l'historique
            $this->db->insert('historique_statut_commande', [
                'id_commande' => $id,
                'statut' => $this->input->post('statut_commande'),
                'commentaire' => 'Modification manuelle par administrateur',
                'modifie_par' => $this->session->userdata('id_utilisateur'),
                'date_creation' => date('Y-m-d H:i:s')
            ]);
            
            $this->session->set_flashdata('success', 'Commande modifiée avec succès');
            redirect('Commande/detail/' . $id);
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la modification');
        }
    }
    
    $data['title'] = 'Modifier commande #' . $data['commande']->numero_commande;
    
    $this->load->view('commandes_edit', $data);
}



   

    /**
     * Exporter les commandes
     */
    public function exporter() {
        if (!$this->is_admin()) {
            show_error('Accès non autorisé', 403);
        }
        
        $filters = [
            'statut_commande' => $this->input->get('statut_commande'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin')
        ];
        
        $commandes = $this->Commande_model->get_all(null, null, $filters);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=commandes_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'N° Commande', 'Client', 'Date', 'Montant', 'Statut', 'Paiement']);
        
        foreach ($commandes as $c) {
            fputcsv($output, [
                $c->id_commande,
                $c->numero_commande,
                $c->client_nom,
                date('d/m/Y', strtotime($c->date_creation)),
                number_format($c->montant_total, 0, ',', ' ') . ' FBu',
                $c->statut_commande,
                $c->statut_paiement
            ]);
        }
        
        fclose($output);
    }
}
?>
