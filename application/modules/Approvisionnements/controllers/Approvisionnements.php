<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approvisionnements extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') === 'vendeur') {
            redirect('Home/User_dashboard');
        }
        $this->load->model('Approvisionnement_model');
        $this->load->model('Produit_model');
        $this->load->model('VarianteProduit_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $upload_path = FCPATH . 'uploads/approvisionnements/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
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

    private function get_vendeur_id() {
        $user_id = $this->session->userdata('id_utilisateur');
        $vendeur = $this->db->where('id_utilisateur', $user_id)->get('vendeurs')->row();
        return $vendeur ? $vendeur->id_vendeur : null;
    }

    public function index() {
        $data['title'] = 'Gestion des stocks & approvisionnements';
        $id_vendeur = $this->get_vendeur_id();
        $is_admin = $this->is_admin();
        
        // Configuration de la pagination
        $config['base_url'] = base_url('approvisionnements/index');
        $config['total_rows'] = $id_vendeur && !$is_admin
            ? $this->Produit_model->count_produits_by_vendeur($id_vendeur)
            : $this->Produit_model->count_all_produits();
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
        
        $filters = [
            'id_categorie' => $this->input->get('id_categorie'),
            'statut_stock' => $this->input->get('statut_stock'),
            'search' => $this->input->get('search')
        ];
        
        if ($id_vendeur && !$is_admin) {
            $data['produits'] = $this->Produit_model->get_produits_by_vendeur_paginated(
                $id_vendeur, $config['per_page'], $page, $filters
            );
            $stats = $this->Produit_model->get_produits_stats_by_vendeur($id_vendeur);
        } else {
            $data['produits'] = $this->Produit_model->get_all_produits_paginated(
                $config['per_page'], $page, $filters
            );
            $stats = $this->Produit_model->get_produits_stats();
        }
        $data['total_produits'] = $stats['total'] ?? 0;
        $data['produits_actifs'] = $stats['actifs'] ?? 0;
        $data['produits_inactifs'] = $stats['inactifs'] ?? 0;
        $data['produits_stock_bas'] = $stats['stock_bas'] ?? 0;
        $data['rupture_stock'] = $stats['rupture'] ?? 0;
        
        $data['categories'] = $this->db->where('est_actif', 1)
                                      ->order_by('nom_categorie', 'ASC')
                                      ->get('categories')
                                      ->result();
        
        foreach($data['produits'] as &$prod){
            $prod['image_url'] = $this->Produit_model->get_main_image($prod['id_produit']);
        }
        
        $data['stats_appro'] = $this->Approvisionnement_model->get_stats($id_vendeur);
        
        $this->load->view('approvisionnements_list', $data);
    }

    // ============================================
    // AJAX - Récupération des données
    // ============================================

    public function get_variantes() {
        $this->output->set_content_type('application/json');
        
        $id_produit = $this->input->post('id_produit');
        
        if (!$id_produit) {
            echo json_encode([]);
            return;
        }
        
        $variantes = $this->VarianteProduit_model->get_variantes_by_produit($id_produit);
        
        foreach ($variantes as $v) {
            if (is_string($v->attributs_variante)) {
                $v->attributs = json_decode($v->attributs_variante, true);
            }
        }
        
        echo json_encode($variantes);
    }

    public function get_stock_actuel() {
        $this->output->set_content_type('application/json');
        
        $id_produit = $this->input->post('id_produit');
        $id_variante = $this->input->post('id_variante');
        
        if ($id_variante) {
            $variante = $this->VarianteProduit_model->get_variante_by_id($id_variante);
            $stock = $variante ? $variante->quantite_actuelle : 0;
        } else {
            $produit = $this->Produit_model->get_produit_by_id($id_produit);
            $stock = $produit ? $produit['quantite_actuelle'] : 0;
        }
        
        echo json_encode(['stock' => $stock]);
    }

    public function get_variantes_by_slug() {
        $this->output->set_content_type('application/json');
        
        $slug = $this->input->post('slug');
        
        if (empty($slug)) {
            echo json_encode([]);
            return;
        }
        
        $produit = $this->Produit_model->get_produit_by_slug($slug);
        
        if (!$produit) {
            echo json_encode([]);
            return;
        }
        
        $variantes = $this->VarianteProduit_model->get_variantes_by_produit($produit['id_produit']);
        
        $result = [];
        foreach ($variantes as $v) {
            $attributs = [];
            if (isset($v->attributs_variante) && !empty($v->attributs_variante)) {
                $attributs = json_decode($v->attributs_variante, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $attributs = [];
                }
            }
            
            $result[] = [
                'id_variante' => $v->id_variante,
                'sku' => $v->sku,
                'attributs' => $attributs,
                'quantite_actuelle' => $v->quantite_actuelle
            ];
        }
        
        echo json_encode($result);
    }

    public function get_variantes_with_stock() {
        $this->output->set_content_type('application/json');
        
        $slug = $this->input->post('slug');
        
        if (empty($slug)) {
            echo json_encode(['success' => false, 'message' => 'Slug manquant', 'variantes' => []]);
            return;
        }
        
        $produit = $this->Produit_model->get_produit_by_slug($slug);
        
        if (!$produit) {
            echo json_encode(['success' => false, 'message' => 'Produit non trouvé', 'variantes' => []]);
            return;
        }
        
        $variantes = $this->VarianteProduit_model->get_variantes_by_produit($produit['id_produit']);
        
        if (empty($variantes)) {
            echo json_encode(['success' => true, 'message' => 'Aucune variante', 'variantes' => []]);
            return;
        }
        
        $result = [];
        foreach ($variantes as $v) {
            $attributs = [];
            if (isset($v->attributs_variante) && !empty($v->attributs_variante)) {
                $attributs = json_decode($v->attributs_variante, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $attributs = [];
                }
            }
            
            $result[] = [
                'id_variante' => $v->id_variante,
                'sku' => $v->sku,
                'attributs' => $attributs,
                'quantite_actuelle' => $v->quantite_actuelle,
                'prix' => $v->prix,
                'est_actif' => $v->est_actif,
                'seuil_stock_bas' => $produit['seuil_stock_bas']
            ];
        }
        
        echo json_encode(['success' => true, 'variantes' => $result]);
    }

    // ============================================
    // AJAX - Ajout de stock
    // ============================================

    public function ajouter_stock_produit() {
        $this->output->set_content_type('application/json');
        
        $slug = $this->input->post('slug_produit');
        $quantite = (int)$this->input->post('quantite');
        $prix_achat = (float)$this->input->post('prix_achat_unitaire');
        $fournisseur = $this->input->post('fournisseur');
        $reference_bon = $this->input->post('reference_bon');
        $note = $this->input->post('note');
        $id_variante = $this->input->post('id_variante');
        
        if (empty($slug) || $quantite <= 0 || $prix_achat <= 0) {
            echo json_encode(['success' => false, 'message' => 'Données invalides']);
            return;
        }
        
        $produit = $this->Produit_model->get_produit_by_slug($slug);
        
        if (!$produit) {
            echo json_encode(['success' => false, 'message' => 'Produit non trouvé']);
            return;
        }
        
        $id_vendeur = $this->get_vendeur_id();
        
        if (!$id_vendeur) {
            echo json_encode(['success' => false, 'message' => 'Aucun vendeur trouvé']);
            return;
        }
        
        // Récupérer le stock actuel
        if ($id_variante) {
            $variante = $this->VarianteProduit_model->get_variante_by_id($id_variante);
            $stock_actuel = $variante ? $variante->quantite_actuelle : 0;
        } else {
            $stock_actuel = $produit['quantite_actuelle'];
        }
        
        $stock_apres = $stock_actuel + $quantite;
        
        // Enregistrer l'approvisionnement
        $insert_data = [
            'id_produit' => $produit['id_produit'],
            'id_variante' => $id_variante ?: null,
            'id_vendeur' => $id_vendeur,
            'quantite_initiale' => $quantite,
            'quantite_recue' => $quantite,
            'quantite_apres' => $stock_apres,
            'prix_achat_unitaire' => $prix_achat,
            'cout_total' => $quantite * $prix_achat,
            'fournisseur' => $fournisseur,
            'reference_bon' => $reference_bon,
            'note' => $note,
            'enregistre_par' => $this->session->userdata('id_utilisateur'),
            'date_appro' => date('Y-m-d'),
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        $id_appro = $this->Approvisionnement_model->add($insert_data);
        
        if ($id_appro) {
            // Mettre à jour le stock
            if ($id_variante) {
                $this->db->set('quantite_actuelle', 'quantite_actuelle + ' . $quantite, FALSE);
                $this->db->where('id_variante', $id_variante);
                $this->db->update('variantes_produit');
                
                // Mettre à jour le stock du produit parent
                $this->mettre_a_jour_stock_produit($produit['id_produit']);
            } else {
                $this->db->set('quantite_actuelle', 'quantite_actuelle + ' . $quantite, FALSE);
                $this->db->where('id_produit', $produit['id_produit']);
                $this->db->update('produits');
            }
            
            // Mettre à jour le statut du stock
            $this->mettre_a_jour_statut_stock($produit['id_produit']);
            
            echo json_encode(['success' => true, 'message' => $quantite . ' unité(s) ajoutée(s) au stock']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement']);
        }
    }

    public function ajouter_stock_variante() {
        $this->output->set_content_type('application/json');
        
        $id_variante = $this->input->post('id_variante');
        $quantite = (int)$this->input->post('quantite');
        $prix_achat = (float)$this->input->post('prix_achat_unitaire');
        $fournisseur = $this->input->post('fournisseur');
        
        if (empty($id_variante) || $quantite <= 0 || $prix_achat <= 0) {
            echo json_encode(['success' => false, 'message' => 'Données invalides']);
            return;
        }
        
        $variante = $this->VarianteProduit_model->get_variante_by_id($id_variante);
        
        if (!$variante) {
            echo json_encode(['success' => false, 'message' => 'Variante non trouvée']);
            return;
        }
        
        $produit = $this->Produit_model->get_produit_by_id($variante->id_produit);
        $id_vendeur = $this->get_vendeur_id();
        
        if (!$id_vendeur) {
            echo json_encode(['success' => false, 'message' => 'Aucun vendeur trouvé']);
            return;
        }
        
        $stock_actuel = $variante->quantite_actuelle;
        $stock_apres = $stock_actuel + $quantite;
        
        $insert_data = [
            'id_produit' => $variante->id_produit,
            'id_variante' => $id_variante,
            'id_vendeur' => $id_vendeur,
            'quantite_initiale' => $quantite,
            'quantite_recue' => $quantite,
            'quantite_apres' => $stock_apres,
            'prix_achat_unitaire' => $prix_achat,
            'cout_total' => $quantite * $prix_achat,
            'fournisseur' => $fournisseur,
            'enregistre_par' => $this->session->userdata('id_utilisateur'),
            'date_appro' => date('Y-m-d'),
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        $id_appro = $this->Approvisionnement_model->add($insert_data);
        
        if ($id_appro) {
            // Mettre à jour le stock de la variante
            $this->db->set('quantite_actuelle', 'quantite_actuelle + ' . $quantite, FALSE);
            $this->db->where('id_variante', $id_variante);
            $this->db->update('variantes_produit');
            
            // Mettre à jour le stock du produit parent
            $this->mettre_a_jour_stock_produit($variante->id_produit);
            
            // Mettre à jour le statut du stock
            $this->mettre_a_jour_statut_stock($variante->id_produit);
            
            echo json_encode(['success' => true, 'message' => $quantite . ' unité(s) ajoutée(s) au stock de la variante']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement']);
        }
    }

    // ============================================
    // Mise à jour du stock
    // ============================================

    private function mettre_a_jour_stock_produit($id_produit) {
        $this->db->select_sum('quantite_actuelle');
        $this->db->where('id_produit', $id_produit);
        $result = $this->db->get('variantes_produit')->row();
        
        $stock_total = (int)($result->quantite_actuelle ?? 0);
        
        $this->db->where('id_produit', $id_produit);
        $this->db->update('produits', ['quantite_actuelle' => $stock_total]);
    }

    private function mettre_a_jour_statut_stock($id_produit) {
        $this->db->where('id_produit', $id_produit);
        $produit = $this->db->get('produits')->row();
        
        if ($produit) {
            if ($produit->quantite_actuelle <= 0) {
                $statut = 'rupture_stock';
            } elseif ($produit->quantite_actuelle <= $produit->seuil_stock_bas) {
                $statut = 'stock_bas';
            } else {
                $statut = 'en_stock';
            }
            
            $this->db->where('id_produit', $id_produit);
            $this->db->update('produits', ['statut_stock' => $statut]);
        }
    }

    // ============================================
    // Historique
    // ============================================

    public function historique() {
        $data['title'] = 'Historique des approvisionnements';
        $id_vendeur = $this->get_vendeur_id();
        
        // Récupérer les produits pour le filtre
        if ($id_vendeur) {
            $data['produits'] = $this->Produit_model->get_produits_by_vendeur($id_vendeur);
        } else {
            $data['produits'] = $this->Produit_model->get_all_produits();
        }
        
        $config['base_url'] = base_url('approvisionnements/historique');
        $config['total_rows'] = $this->Approvisionnement_model->count_all($id_vendeur);
        $config['per_page'] = 50;
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
        
        $data['approvisionnements'] = $this->Approvisionnement_model->get_all(
            $config['per_page'],
            $page,
            $id_vendeur
        );
        
        $data['stats'] = $this->Approvisionnement_model->get_stats($id_vendeur);
        
        $this->load->view('approvisionnements_historique', $data);
    }

    // ============================================
    // Modification d'un approvisionnement
    // ============================================

    public function update() {
        $this->output->set_content_type('application/json');
        
        $id_appro = $this->input->post('id_appro');
        $nouvelle_quantite = (int)$this->input->post('quantite_recue');
        $nouveau_prix = (float)$this->input->post('prix_achat_unitaire');
        $fournisseur = $this->input->post('fournisseur');
        $reference_bon = $this->input->post('reference_bon');
        $note = $this->input->post('note');
        
        if (empty($id_appro) || $nouvelle_quantite <= 0 || $nouveau_prix <= 0) {
            echo json_encode(['success' => false, 'message' => 'Données invalides']);
            return;
        }
        
        // Récupérer l'approvisionnement original
        $appro = $this->Approvisionnement_model->get_by_id($id_appro);
        
        if (!$appro) {
            echo json_encode(['success' => false, 'message' => 'Approvisionnement non trouvé']);
            return;
        }
        
        $id_vendeur = $this->get_vendeur_id();
        if ($id_vendeur && $appro->id_vendeur != $id_vendeur && !$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        // Calculer la différence de quantité
        $difference = $nouvelle_quantite - $appro->quantite_recue;
        $nouveau_cout_total = $nouvelle_quantite * $nouveau_prix;
        
        // Mettre à jour l'approvisionnement
        $update_data = [
            'quantite_recue' => $nouvelle_quantite,
            'quantite_apres' => $appro->quantite_apres + $difference,
            'prix_achat_unitaire' => $nouveau_prix,
            'cout_total' => $nouveau_cout_total,
            'fournisseur' => $fournisseur,
            'reference_bon' => $reference_bon,
            'note' => $note
        ];
        
        $this->db->where('id_appro', $id_appro);
        $updated = $this->db->update('approvisionnements', $update_data);
        
        if ($updated) {
            // Mettre à jour le stock
            if ($appro->id_variante) {
                // Mettre à jour la variante
                $this->db->set('quantite_actuelle', 'quantite_actuelle + ' . $difference, FALSE);
                $this->db->where('id_variante', $appro->id_variante);
                $this->db->update('variantes_produit');
                
                // Mettre à jour le produit parent
                $this->mettre_a_jour_stock_produit($appro->id_produit);
            } else {
                // Mettre à jour le produit
                $this->db->set('quantite_actuelle', 'quantite_actuelle + ' . $difference, FALSE);
                $this->db->where('id_produit', $appro->id_produit);
                $this->db->update('produits');
            }
            
            // Mettre à jour le statut du stock
            $this->mettre_a_jour_statut_stock($appro->id_produit);
            
            echo json_encode(['success' => true, 'message' => 'Approvisionnement modifié avec succès']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la modification']);
        }
    }

    // ============================================
    // Suppression d'un approvisionnement
    // ============================================

    public function delete($id) {
        $this->output->set_content_type('application/json');
        
        $appro = $this->Approvisionnement_model->get_by_id($id);
        
        if (!$appro) {
            echo json_encode(['success' => false, 'message' => 'Approvisionnement non trouvé']);
            return;
        }
        
        $id_vendeur = $this->get_vendeur_id();
        if ($id_vendeur && $appro->id_vendeur != $id_vendeur && !$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $quantite_a_retirer = (int)$appro->quantite_recue;
        
        // 1. Retirer le stock
        if ($appro->id_variante) {
            // Retirer le stock de la variante
            $this->db->set('quantite_actuelle', 'quantite_actuelle - ' . $quantite_a_retirer, FALSE);
            $this->db->where('id_variante', $appro->id_variante);
            $this->db->update('variantes_produit');
            
            // Mettre à jour le produit parent
            $this->mettre_a_jour_stock_produit($appro->id_produit);
        } else {
            // Retirer le stock du produit
            $this->db->set('quantite_actuelle', 'quantite_actuelle - ' . $quantite_a_retirer, FALSE);
            $this->db->where('id_produit', $appro->id_produit);
            $this->db->update('produits');
        }
        
        // 2. Mettre à jour le statut du stock
        $this->mettre_a_jour_statut_stock($appro->id_produit);
        
        // 3. Supprimer l'approvisionnement
        $deleted = $this->Approvisionnement_model->delete($id);
        
        if ($deleted) {
            echo json_encode([
                'success' => true,
                'message' => 'Approvisionnement supprimé. Stock retiré (-' . $quantite_a_retirer . ' unités)'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors de la suppression'
            ]);
        }
    }

    // ============================================
    // Détail d'un approvisionnement
    // ============================================

    public function view($id) {
        $data['appro'] = $this->Approvisionnement_model->get_by_id($id);
        
        if (!$data['appro']) {
            show_404();
        }
        
        $id_vendeur = $this->get_vendeur_id();
        if ($id_vendeur && $data['appro']->id_vendeur != $id_vendeur && !$this->is_admin()) {
            show_error('Accès non autorisé', 403);
        }
        
        $data['title'] = 'Détails de l\'approvisionnement #' . $id;
        
        $this->load->view('approvisionnements_view', $data);
    }
}
?>