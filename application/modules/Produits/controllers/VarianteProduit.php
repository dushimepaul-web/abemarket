<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VarianteProduit extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('VarianteProduit_model');
        $this->load->model('Produit_model');
        $this->load->library('form_validation');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    private function is_admin() {
        $user_id = $this->session->userdata('id_utilisateur');
        $this->db->select('up.id_profil')
                 ->from('utilisateur_profils up')
                 ->where('up.id_utilisateur', $user_id)
                 ->where_in('up.id_profil', [1, 2]);
        return $this->db->get()->num_rows() > 0;
    }

    private function get_vendeur_id() {
        $user_id = $this->session->userdata('id_utilisateur');
        $vendeur = $this->db->where('id_utilisateur', $user_id)->get('vendeurs')->row();
        return $vendeur ? $vendeur->id_vendeur : null;
    }

    public function index($slug) {
        $produit = $this->Produit_model->get_produit_by_slug($slug);
        
        if (!$produit) {
            show_404();
        }
        
        $produit = (object)$produit;
        
        $id_vendeur = $this->get_vendeur_id();
        if (!$this->is_admin() && $produit->id_vendeur != $id_vendeur) {
            show_error('Accès non autorisé', 403);
        }
        
        $data['produit'] = $produit;
        $data['title'] = 'Gestion des variantes - ' . $produit->nom_produit;
        $data['variantes'] = $this->VarianteProduit_model->get_variantes_by_produit($produit->id_produit);
        $data['types_attributs'] = $this->VarianteProduit_model->get_types_attributs();
        
        $this->load->view('variantes_list', $data);
    }

    public function add($slug) {
        $produit = $this->Produit_model->get_produit_by_slug($slug);
        
        if (!$produit) {
            show_404();
        }
        
        $produit = (object)$produit;
        
        $id_vendeur = $this->get_vendeur_id();
        if (!$this->is_admin() && $produit->id_vendeur != $id_vendeur) {
            show_error('Accès non autorisé', 403);
        }
        
        $data['produit'] = $produit;
        $data['title'] = 'Ajouter une variante - ' . $produit->nom_produit;
        $data['types_attributs'] = $this->VarianteProduit_model->get_types_attributs();
        
        $this->load->view('variante_add_edit', $data);
    }

    public function edit($slug, $id_variante) {
        $produit = $this->Produit_model->get_produit_by_slug($slug);
        
        if (!$produit) {
            show_404();
        }
        
        $produit = (object)$produit;
        
        $id_vendeur = $this->get_vendeur_id();
        if (!$this->is_admin() && $produit->id_vendeur != $id_vendeur) {
            show_error('Accès non autorisé', 403);
        }
        
        $data['variante'] = $this->VarianteProduit_model->get_variante_by_id($id_variante);
        
        if (!$data['variante']) {
            show_404();
        }
        
        if ($data['variante']->id_produit != $produit->id_produit) {
            show_error('Variante non associée à ce produit', 403);
        }
        
        $data['produit'] = $produit;
        $data['title'] = 'Modifier la variante - ' . $produit->nom_produit;
        $data['types_attributs'] = $this->VarianteProduit_model->get_types_attributs();
        
        $this->load->view('variante_add_edit', $data);
    }

    public function delete($slug, $id_variante) {
        $this->output->set_content_type('application/json');
        
        $produit = $this->Produit_model->get_produit_by_slug($slug);
        
        if (!$produit) {
            echo json_encode(['success' => false, 'message' => 'Produit non trouvé']);
            return;
        }
        
        $produit = (object)$produit;
        
        $id_vendeur = $this->get_vendeur_id();
        if (!$this->is_admin() && $produit->id_vendeur != $id_vendeur) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $variante = $this->VarianteProduit_model->get_variante_by_id($id_variante);
        
        if (!$variante) {
            echo json_encode(['success' => false, 'message' => 'Variante non trouvée']);
            return;
        }
        
        if ($variante->id_produit != $produit->id_produit) {
            echo json_encode(['success' => false, 'message' => 'Variante non associée à ce produit']);
            return;
        }
        
        $deleted = $this->VarianteProduit_model->supprimer_variante($id_variante);
        
        if ($deleted) {
            $this->mettre_a_jour_stock_produit($produit->id_produit);
            
            $nb_variantes = $this->db->where('id_produit', $variante->id_produit)->get('variantes_produit')->num_rows();
            if ($nb_variantes == 0) {
                $this->db->where('id_produit', $variante->id_produit);
                $this->db->update('produits', ['type_produit' => 'simple']);
            }
        }
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Variante supprimée avec succès' : 'Erreur lors de la suppression'
        ]);
    }

    public function save() {
        $this->output->set_content_type('application/json');
        
        $slug = $this->input->post('slug_produit');
        $produit = $this->Produit_model->get_produit_by_slug($slug);
        
        if (!$produit) {
            echo json_encode(['success' => false, 'message' => 'Produit non trouvé']);
            return;
        }
        
        $produit = (object)$produit;
        
        $id_vendeur = $this->get_vendeur_id();
        if (!$this->is_admin() && $produit->id_vendeur != $id_vendeur) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $this->form_validation->set_rules('sku', 'SKU', 'required|trim');
        $this->form_validation->set_rules('prix', 'Prix', 'required|numeric|greater_than[0]');
        
        if ($this->form_validation->run() == false) {
            echo json_encode(['success' => false, 'message' => strip_tags(validation_errors())]);
            return;
        }
        
        $attributs = [];
        $types = $this->input->post('attribut_type');
        $valeurs = $this->input->post('attribut_valeur');
        
        if ($types && $valeurs) {
            for ($i = 0; $i < count($types); $i++) {
                if (!empty($types[$i]) && !empty($valeurs[$i])) {
                    $attributs[$types[$i]] = $valeurs[$i];
                }
            }
        }
        
        $data = [
            'id_produit' => $produit->id_produit,
            'sku' => trim($this->input->post('sku')),
            'attributs' => $attributs,
            'prix' => $this->input->post('prix'),
            'quantite_actuelle' => $this->input->post('quantite_actuelle') ?: 0,
            'est_actif' => $this->input->post('est_actif') ? 1 : 0
        ];
        
        $id_variante = $this->input->post('id_variante');
        
        if ($id_variante) {
            $result = $this->VarianteProduit_model->modifier_variante($id_variante, $data);
            $message = $result ? 'Variante modifiée avec succès' : 'Erreur lors de la modification ou SKU déjà existant';
            if ($result) {
                $this->mettre_a_jour_stock_produit($produit->id_produit);
            }
        } else {
            $id_variante = $this->VarianteProduit_model->ajouter_variante($data);
            $result = $id_variante ? true : false;
            $message = $result ? 'Variante ajoutée avec succès' : 'Erreur lors de l\'ajout ou SKU déjà existant';
            
            if ($result) {
                $this->db->where('id_produit', $produit->id_produit);
                $this->db->update('produits', ['type_produit' => 'variable']);
                $this->mettre_a_jour_stock_produit($produit->id_produit);
            }
        }
        
        echo json_encode([
            'success' => $result,
            'message' => $message,
            'id' => $id_variante
        ]);
    }

    public function update_stock() {
        $this->output->set_content_type('application/json');
        
        $slug = $this->input->post('slug_produit');
        $id_variante = $this->input->post('id_variante');
        $quantite = (int)$this->input->post('quantite');
        
        if (empty($slug) || empty($id_variante)) {
            echo json_encode(['success' => false, 'message' => 'Données manquantes']);
            return;
        }
        
        $produit = $this->Produit_model->get_produit_by_slug($slug);
        
        if (!$produit) {
            echo json_encode(['success' => false, 'message' => 'Produit non trouvé']);
            return;
        }
        
        $produit = (object)$produit;
        
        $id_vendeur = $this->get_vendeur_id();
        if (!$this->is_admin() && $produit->id_vendeur != $id_vendeur) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $variante = $this->VarianteProduit_model->get_variante_by_id($id_variante);
        
        if (!$variante) {
            echo json_encode(['success' => false, 'message' => 'Variante non trouvée']);
            return;
        }
        
        if ($variante->id_produit != $produit->id_produit) {
            echo json_encode(['success' => false, 'message' => 'Variante non associée à ce produit']);
            return;
        }
        
        $nouvelle_quantite = $variante->quantite_actuelle + $quantite;
        if ($nouvelle_quantite < 0) $nouvelle_quantite = 0;
        
        // Mise à jour directe sans passer par le modèle
        $this->db->where('id_variante', $id_variante);
        $updated = $this->db->update('variantes_produit', [
            'quantite_actuelle' => $nouvelle_quantite,
            'est_actif' => $nouvelle_quantite > 0 ? 1 : 0
        ]);
        
        if ($updated) {
            $this->mettre_a_jour_stock_produit($produit->id_produit);
        }
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Stock mis à jour avec succès' : 'Erreur lors de la mise à jour'
        ]);
    }

    /**
     * Mettre à jour le stock du produit parent en additionnant toutes ses variantes
     */
    private function mettre_a_jour_stock_produit($id_produit) {
        // Calculer la somme des quantités de toutes les variantes
        $this->db->select_sum('quantite_actuelle');
        $this->db->where('id_produit', $id_produit);
        $result = $this->db->get('variantes_produit')->row();
        
        $stock_total = (int)($result->quantite_actuelle ?? 0);
        
        // Mettre à jour le stock du produit
        $this->db->where('id_produit', $id_produit);
        $this->db->update('produits', ['quantite_actuelle' => $stock_total]);
        
        // Mettre à jour le statut du stock
        $this->db->where('id_produit', $id_produit);
        $produit = $this->db->get('produits')->row();
        
        if ($produit) {
            if ($stock_total <= 0) {
                $statut = 'rupture_stock';
            } elseif ($stock_total <= $produit->seuil_stock_bas) {
                $statut = 'stock_bas';
            } else {
                $statut = 'en_stock';
            }
            
            $this->db->where('id_produit', $id_produit);
            $this->db->update('produits', ['statut_stock' => $statut]);
        }
    }

    public function check_sku() {
        $this->output->set_content_type('application/json');
        
        $sku = $this->input->post('sku');
        $exclude_id = $this->input->post('exclude_id');
        
        $exists = $this->VarianteProduit_model->sku_exists($sku, $exclude_id);
        
        echo json_encode(['exists' => $exists]);
    }
}
?>