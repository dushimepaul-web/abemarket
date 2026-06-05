<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coupons extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Coupons_model');
        $this->load->library('form_validation');
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
     * Liste des coupons
     */
    public function index() {
        $data['title'] = 'Gestion des coupons promotionnels';
        
        // Filtres
        $filters = [
            'est_actif' => $this->input->get('est_actif'),
            'type_reduction' => $this->input->get('type_reduction'),
            'search' => $this->input->get('search')
        ];
        
        // Configuration de la pagination
        $config['base_url'] = base_url('coupons/index');
        $config['total_rows'] = $this->Coupons_model->count_all($filters);
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
        
        $data['coupons'] = $this->Coupons_model->get_all(
            $config['per_page'],
            $page,
            $filters
        );
        
        $data['stats'] = $this->Coupons_model->get_stats();
        $data['filters'] = $filters;
        
        $this->load->view('coupons_list', $data);
    }

    /**
     * Ajouter un coupon
     */
    public function add() {
        $data['title'] = 'Ajouter un coupon';
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->form_validation->set_rules('code', 'Code', 'required|trim|is_unique[coupons.code]');
            $this->form_validation->set_rules('type_reduction', 'Type de réduction', 'required');
            $this->form_validation->set_rules('valeur_reduction', 'Valeur de réduction', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('date_debut', 'Date de début', 'required');
            $this->form_validation->set_rules('date_fin', 'Date de fin', 'required');
            
            if ($this->form_validation->run() == true) {
                // Gérer les IDs applicables
                $ids_applicables = $this->input->post('ids_applicables');
                if ($ids_applicables && is_array($ids_applicables)) {
                    $ids_applicables = json_encode($ids_applicables);
                } else {
                    $ids_applicables = null;
                }
                
                $insert_data = [
                    'code' => strtoupper(trim($this->input->post('code'))),
                    'description' => $this->input->post('description'),
                    'type_reduction' => $this->input->post('type_reduction'),
                    'valeur_reduction' => $this->input->post('valeur_reduction'),
                    'montant_min_achat' => $this->input->post('montant_min_achat') ?: null,
                    'montant_max_reduction' => $this->input->post('montant_max_reduction') ?: null,
                    'limite_utilisation' => $this->input->post('limite_utilisation') ?: null,
                    'limite_par_utilisateur' => $this->input->post('limite_par_utilisateur') ?: 1,
                    'date_debut' => date('Y-m-d H:i:s', strtotime($this->input->post('date_debut'))),
                    'date_fin' => date('Y-m-d H:i:s', strtotime($this->input->post('date_fin'))),
                    'applicable_a' => $this->input->post('applicable_a'),
                    'ids_applicables' => $ids_applicables,
                    'est_actif' => $this->input->post('est_actif') ? 1 : 0,
                    'date_creation' => date('Y-m-d H:i:s')
                ];
                
                $id = $this->Coupons_model->add($insert_data);
                
                if ($id) {
                    $this->session->set_flashdata('success', 'Coupon ajouté avec succès');
                    redirect('coupons');
                } else {
                    $this->session->set_flashdata('error', 'Erreur lors de l\'ajout');
                }
            }
        }
        
        $data['categories'] = $this->db->get('categories')->result();
        $data['produits'] = $this->db->get('produits')->result();
        $data['vendeurs'] = $this->db->get('vendeurs')->result();
        
        $this->load->view('coupons_add_edit', $data);
    }

    /**
     * Modifier un coupon
     */
    public function edit($id) {
        $data['coupon'] = $this->Coupons_model->get_by_id($id);
        
        if (!$data['coupon']) {
            show_404();
        }
        
        $data['title'] = 'Modifier le coupon - ' . $data['coupon']->code;
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Vérifier l'unicité du code (sauf pour ce coupon)
            $this->form_validation->set_rules('code', 'Code', 'required|trim|callback_check_unique_code[' . $id . ']');
            $this->form_validation->set_rules('type_reduction', 'Type de réduction', 'required');
            $this->form_validation->set_rules('valeur_reduction', 'Valeur de réduction', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('date_debut', 'Date de début', 'required');
            $this->form_validation->set_rules('date_fin', 'Date de fin', 'required');
            
            if ($this->form_validation->run() == true) {
                // Gérer les IDs applicables
                $ids_applicables = $this->input->post('ids_applicables');
                if ($ids_applicables && is_array($ids_applicables)) {
                    $ids_applicables = json_encode($ids_applicables);
                } else {
                    $ids_applicables = null;
                }
                
                $update_data = [
                    'code' => strtoupper(trim($this->input->post('code'))),
                    'description' => $this->input->post('description'),
                    'type_reduction' => $this->input->post('type_reduction'),
                    'valeur_reduction' => $this->input->post('valeur_reduction'),
                    'montant_min_achat' => $this->input->post('montant_min_achat') ?: null,
                    'montant_max_reduction' => $this->input->post('montant_max_reduction') ?: null,
                    'limite_utilisation' => $this->input->post('limite_utilisation') ?: null,
                    'limite_par_utilisateur' => $this->input->post('limite_par_utilisateur') ?: 1,
                    'date_debut' => date('Y-m-d H:i:s', strtotime($this->input->post('date_debut'))),
                    'date_fin' => date('Y-m-d H:i:s', strtotime($this->input->post('date_fin'))),
                    'applicable_a' => $this->input->post('applicable_a'),
                    'ids_applicables' => $ids_applicables,
                    'est_actif' => $this->input->post('est_actif') ? 1 : 0
                ];
                
                $updated = $this->Coupons_model->update($id, $update_data);
                
                if ($updated) {
                    $this->session->set_flashdata('success', 'Coupon modifié avec succès');
                    redirect('coupons');
                } else {
                    $this->session->set_flashdata('error', 'Erreur lors de la modification');
                }
            }
        }
        
        // Décoder les IDs applicables pour l'affichage
        if ($data['coupon']->ids_applicables) {
            $data['coupon']->ids_applicables_array = json_decode($data['coupon']->ids_applicables, true);
        } else {
            $data['coupon']->ids_applicables_array = [];
        }
        
        $data['categories'] = $this->db->get('categories')->result();
        $data['produits'] = $this->db->get('produits')->result();
        $data['vendeurs'] = $this->db->get('vendeurs')->result();
        
        $this->load->view('coupons_add_edit', $data);
    }

    /**
     * Callback pour vérifier l'unicité du code (hors coupon courant)
     */
    public function check_unique_code($code, $id) {
        $this->db->where('code', strtoupper(trim($code)));
        $this->db->where('id_coupon !=', $id);
        $exists = $this->db->get('coupons')->num_rows() > 0;
        
        if ($exists) {
            $this->form_validation->set_message('check_unique_code', 'Le code {field} existe déjà');
            return false;
        }
        return true;
    }

    /**
     * Supprimer un coupon
     */
    public function delete($id) {
        $this->output->set_content_type('application/json');
        
        $deleted = $this->Coupons_model->delete($id);
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Coupon supprimé avec succès' : 'Erreur lors de la suppression'
        ]);
    }

    /**
     * Activer/Désactiver un coupon
     */
    public function toggle_status($id) {
        $this->output->set_content_type('application/json');
        
        $coupon = $this->Coupons_model->get_by_id($id);
        
        if (!$coupon) {
            echo json_encode(['success' => false, 'message' => 'Coupon non trouvé']);
            return;
        }
        
        $new_status = $coupon->est_actif == 1 ? 0 : 1;
        $updated = $this->Coupons_model->update($id, ['est_actif' => $new_status]);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Statut modifié avec succès' : 'Erreur'
        ]);
    }

    /**
     * Valider un coupon (AJAX)
     */
    public function valider() {
        $this->output->set_content_type('application/json');
        
        $code = $this->input->post('code');
        $montant = $this->input->post('montant');
        
        $coupon = $this->Coupons_model->valider_coupon($code, $montant);
        
        if ($coupon) {
            echo json_encode([
                'success' => true,
                'coupon' => $coupon,
                'reduction' => $coupon->reduction_calculee,
                'message' => 'Coupon valide'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Coupon invalide ou expiré'
            ]);
        }
    }

    /**
     * Récupérer les catégories pour le formulaire
     */
    public function get_categories() {
        $this->output->set_content_type('application/json');
        $categories = $this->db->select('id_categorie as id, nom_categorie as nom')
                               ->get('categories')
                               ->result();
        echo json_encode($categories);
    }

    /**
     * Récupérer les produits pour le formulaire
     */
    public function get_produits() {
        $this->output->set_content_type('application/json');
        $produits = $this->db->select('id_produit as id, nom_produit as nom')
                              ->get('produits')
                              ->result();
        echo json_encode($produits);
    }

    /**
     * Récupérer les vendeurs pour le formulaire
     */
    public function get_vendeurs() {
        $this->output->set_content_type('application/json');
        $vendeurs = $this->db->select('id_vendeur as id, nom_boutique as nom')
                              ->get('vendeurs')
                              ->result();
        echo json_encode($vendeurs);
    }

    /**
     * Détail d'un coupon
     */
    public function detail($id) {
        $data['coupon'] = $this->Coupons_model->get_by_id($id);
        
        if (!$data['coupon']) {
            show_404();
        }
        
        // Décoder les IDs applicables
        if ($data['coupon']->ids_applicables) {
            $data['coupon']->ids_applicables_array = json_decode($data['coupon']->ids_applicables, true);
        }
        
        $data['title'] = 'Détails du coupon - ' . $data['coupon']->code;
        
        $this->load->view('coupons_detail', $data);
    }
}
?>