<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produit_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // ==================== LECTURE (ADMIN) ====================

    public function get_all_produits($limit = null, $offset = null, $search = null, $categorie = null, $status = null) {
        $this->db->select('p.*, c.nom_categorie, v.nom_boutique')
                 ->from('produits p')
                 ->join('categories c', 'p.id_categorie = c.id_categorie', 'left')
                 ->join('vendeurs v', 'p.id_vendeur = v.id_vendeur', 'left')
                 ->where('p.statut !=', 'supprime');

        if ($search) {
            $this->db->group_start()
                     ->like('p.nom_produit', $search)
                     ->or_like('p.sku', $search)
                     ->or_like('p.code_produit', $search)
                     ->group_end();
        }
        if ($categorie) $this->db->where('p.id_categorie', $categorie);
        if ($status !== null) $this->db->where('p.est_actif', $status);

        $this->db->order_by('p.id_produit', 'DESC');
        if ($limit !== null) $this->db->limit($limit, $offset);

        return $this->db->get()->result_array();
    }

    public function count_all_produits($search = null, $categorie = null, $status = null) {
        $this->db->from('produits p')->where('p.statut !=', 'supprime');

        if ($search) {
            $this->db->group_start()
                     ->like('p.nom_produit', $search)
                     ->or_like('p.sku', $search)
                     ->or_like('p.code_produit', $search)
                     ->group_end();
        }
        if ($categorie) $this->db->where('p.id_categorie', $categorie);
        if ($status !== null) $this->db->where('p.est_actif', $status);

        return $this->db->count_all_results();
    }

    public function get_produits_stats() {
        return [
            'total'     => $this->db->where('statut !=', 'supprime')->from('produits')->count_all_results(),
            'actifs'    => $this->db->where('est_actif', 1)->where('statut !=', 'supprime')->from('produits')->count_all_results(),
            'inactifs'  => $this->db->where('est_actif', 0)->where('statut !=', 'supprime')->from('produits')->count_all_results(),
            'stock_bas' => $this->db->where('statut_stock', 'stock_bas')->where('statut !=', 'supprime')->from('produits')->count_all_results(),
            'rupture'   => $this->db->where('statut_stock', 'rupture_stock')->where('statut !=', 'supprime')->from('produits')->count_all_results(),
        ];
    }

    // ==================== LECTURE PAR VENDEUR ====================

    /**
     * FIX : méthode unique (ancienne version dupliquée supprimée)
     * Retourne les produits d'un vendeur avec jointure catégorie
     */
    public function get_produits_by_vendeur($id_vendeur, $limit = null, $offset = null) {
        $this->db->select('p.*, c.nom_categorie')
                 ->from('produits p')
                 ->join('categories c', 'p.id_categorie = c.id_categorie', 'left')
                 ->where('p.id_vendeur', $id_vendeur)
                 ->where('p.statut !=', 'supprime')
                 ->order_by('p.date_creation', 'DESC');

        if ($limit !== null) $this->db->limit($limit, $offset);

        return $this->db->get()->result_array();
    }

    public function count_produits_by_vendeur($id_vendeur) {
        return $this->db->where('id_vendeur', $id_vendeur)
                        ->where('statut !=', 'supprime')
                        ->from('produits')
                        ->count_all_results();
    }

    /**
     * FIX : syntaxe compact() corrigée
     */
    public function get_produits_stats_by_vendeur($id_vendeur) {
        $total     = $this->db->where('id_vendeur', $id_vendeur)->where('statut !=', 'supprime')->from('produits')->count_all_results();
        $actifs    = $this->db->where('id_vendeur', $id_vendeur)->where('est_actif', 1)->where('statut !=', 'supprime')->from('produits')->count_all_results();
        $inactifs  = $this->db->where('id_vendeur', $id_vendeur)->where('est_actif', 0)->where('statut !=', 'supprime')->from('produits')->count_all_results();
        $stock_bas = $this->db->where('id_vendeur', $id_vendeur)->where('statut_stock', 'stock_bas')->where('statut !=', 'supprime')->from('produits')->count_all_results();
        $rupture   = $this->db->where('id_vendeur', $id_vendeur)->where('statut_stock', 'rupture_stock')->where('statut !=', 'supprime')->from('produits')->count_all_results();

        return compact('total', 'actifs', 'inactifs', 'stock_bas', 'rupture');
    }

    // ==================== LECTURE PAR SLUG / ID ====================

    public function get_produit_by_slug($slug) {
        return $this->db->select('p.*, c.nom_categorie, v.nom_boutique')
                        ->from('produits p')
                        ->join('categories c', 'p.id_categorie = c.id_categorie', 'left')
                        ->join('vendeurs v', 'p.id_vendeur = v.id_vendeur', 'left')
                        ->where('p.slug_produit', $slug)
                        ->where('p.statut !=', 'supprime')
                        ->get()->row_array();
    }

    public function get_produit_by_id($id_produit) {
        return $this->db->select('p.*, c.nom_categorie')
                        ->from('produits p')
                        ->join('categories c', 'p.id_categorie = c.id_categorie', 'left')
                        ->where('p.id_produit', $id_produit)
                        ->where('p.statut !=', 'supprime')
                        ->get()->row_array();
    }

    // ==================== ÉCRITURE ====================

    public function add_produit($data) {
        $this->db->insert('produits', $data);
        return $this->db->insert_id();
    }

    public function update_produit_by_slug($slug, $data) {
        return $this->db->where('slug_produit', $slug)->update('produits', $data);
    }

    public function update_produit_by_id($id_produit, $data) {
        return $this->db->where('id_produit', $id_produit)->update('produits', $data);
    }

    public function delete_produit_by_slug($slug) {
        return $this->db->where('slug_produit', $slug)->update('produits', ['statut' => 'supprime']);
    }

    public function delete_produit_by_id($id_produit) {
        return $this->db->where('id_produit', $id_produit)->update('produits', ['statut' => 'supprime']);
    }

    public function hard_delete_produit($id_produit) {
        return $this->db->where('id_produit', $id_produit)->delete('produits');
    }

    public function toggle_status_by_slug($slug, $status) {
        return $this->db->where('slug_produit', $slug)->update('produits', ['est_actif' => $status]);
    }

    // ==================== IMAGES ====================

    public function get_images_by_produit_id($id_produit) {
        return $this->db->select('*')
                        ->from('images_produit')
                        ->where('id_produit', $id_produit)
                        ->order_by('est_principale', 'DESC')
                        ->order_by('ordre_affichage', 'ASC')
                        ->get()->result_array();
    }

    public function get_images_by_produit_slug($slug) {
        $produit = $this->get_produit_by_slug($slug);
        return $produit ? $this->get_images_by_produit_id($produit['id_produit']) : [];
    }

    /**
     * Récupère l'URL de l'image principale d'un produit par son ID
     */
    public function get_main_image($produit_id) {
        $query = $this->db->select('url_image')
                          ->from('images_produit')
                          ->where('id_produit', $produit_id)
                          ->where('est_principale', 1)
                          ->limit(1)->get();

        if ($query->num_rows() > 0) return $query->row()->url_image;

        $query = $this->db->select('url_image')
                          ->from('images_produit')
                          ->where('id_produit', $produit_id)
                          ->limit(1)->get();

        return $query->num_rows() > 0 ? $query->row()->url_image : null;
    }

    public function get_main_image_by_slug($slug) {
        $produit = $this->get_produit_by_slug($slug);
        return $produit ? $this->get_main_image($produit['id_produit']) : null;
    }

    public function add_image($data) {
        $this->db->insert('images_produit', $data);
        return $this->db->insert_id();
    }

    public function delete_image($id_image) {
        return $this->db->where('id_image', $id_image)->delete('images_produit');
    }

    public function set_main_image($id_produit, $id_image) {
        $this->db->where('id_produit', $id_produit)->update('images_produit', ['est_principale' => 0]);
        $this->db->where('id_image', $id_image)->update('images_produit', ['est_principale' => 1]);
        return $this->db->affected_rows() > 0;
    }

    // ==================== VARIANTES ====================

    public function get_variantes_by_produit_id($id_produit) {
        return $this->db->where('id_produit', $id_produit)
                        ->where('est_actif', 1)
                        ->order_by('id_variante', 'ASC')
                        ->get('variantes_produit')->result_array();
    }

    public function get_variantes_by_produit_slug($slug) {
        $produit = $this->get_produit_by_slug($slug);
        return $produit ? $this->get_variantes_by_produit_id($produit['id_produit']) : [];
    }

    public function get_variante_by_id($id_variante) {
        return $this->db->where('id_variante', $id_variante)->get('variantes_produit')->row_array();
    }

    // ==================== STOCK ====================

    public function update_stock_by_slug($slug, $quantite, $id_variante = null) {
        $produit = $this->get_produit_by_slug($slug);
        return $produit ? $this->update_stock_by_id($produit['id_produit'], $quantite, $id_variante) : false;
    }

    public function update_stock_by_id($id_produit, $quantite, $id_variante = null) {
        if ($id_variante) {
            $this->db->set('quantite_actuelle', 'quantite_actuelle + ' . (int)$quantite, FALSE)
                     ->where('id_variante', $id_variante)
                     ->update('variantes_produit');
            $this->update_produit_stock_from_variantes($id_produit);
        } else {
            $this->db->set('quantite_actuelle', 'quantite_actuelle + ' . (int)$quantite, FALSE)
                     ->where('id_produit', $id_produit)
                     ->update('produits');
            $this->update_produit_stock_status($id_produit);
        }
        return $this->db->affected_rows() > 0;
    }

    private function update_produit_stock_status($id_produit) {
        $produit = $this->db->select('quantite_actuelle, seuil_stock_bas')
                            ->where('id_produit', $id_produit)
                            ->get('produits')->row();
        if (!$produit) return;

        if ($produit->quantite_actuelle <= 0) $statut = 'rupture_stock';
        elseif ($produit->quantite_actuelle <= $produit->seuil_stock_bas) $statut = 'stock_bas';
        else $statut = 'en_stock';

        $this->db->where('id_produit', $id_produit)->update('produits', ['statut_stock' => $statut]);
    }

    private function update_produit_stock_from_variantes($id_produit) {
        $total = $this->db->select_sum('quantite_actuelle')
                          ->where('id_produit', $id_produit)
                          ->get('variantes_produit')->row();
        if (!$total) return;

        $produit = $this->db->select('seuil_stock_bas')->where('id_produit', $id_produit)->get('produits')->row();
        if (!$produit) return;

        $qty = (int)$total->quantite_actuelle;
        if ($qty <= 0) $statut = 'rupture_stock';
        elseif ($qty <= $produit->seuil_stock_bas) $statut = 'stock_bas';
        else $statut = 'en_stock';

        $this->db->where('id_produit', $id_produit)->update('produits', [
            'quantite_actuelle' => $qty,
            'statut_stock'      => $statut,
        ]);
    }

    // ==================== UTILITAIRES ====================

    public function increment_vues_by_slug($slug) {
        $produit = $this->get_produit_by_slug($slug);
        if (!$produit) return false;
        $this->db->set('nombre_vues', 'nombre_vues + 1', FALSE)->where('id_produit', $produit['id_produit'])->update('produits');
        return true;
    }

    public function slug_exists($slug, $exclude_id = null) {
        $this->db->where('slug_produit', $slug);
        if ($exclude_id) $this->db->where('id_produit !=', $exclude_id);
        return $this->db->get('produits')->num_rows() > 0;
    }

    public function generate_unique_slug($nom, $exclude_id = null) {
        $slug = $original = $this->createSlug($nom);
        $counter = 1;
        while ($this->slug_exists($slug, $exclude_id)) {
            $slug = $original . '-' . $counter++;
        }
        return $slug;
    }

    private function createSlug($string) {
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9-]/', '-', $string);
        $string = preg_replace('/-+/', '-', $string);
        return trim($string, '-');
    }

    public function check_produit_owner_by_slug($slug, $id_vendeur) {
        $produit = $this->get_produit_by_slug($slug);
        return $produit && $produit['id_vendeur'] == $id_vendeur;
    }

    public function get_produits_en_promo($limit = null) {
        $now = date('Y-m-d H:i:s');
        $this->db->select('p.*, c.nom_categorie')
                 ->from('produits p')
                 ->join('categories c', 'p.id_categorie = c.id_categorie', 'left')
                 ->where('p.prix_promo IS NOT NULL')
                 ->where('p.date_debut_promo <=', $now)
                 ->where('p.date_fin_promo >=', $now)
                 ->where('p.est_actif', 1)
                 ->where('p.statut', 'actif')
                 ->order_by('p.date_creation', 'DESC');
        if ($limit) $this->db->limit($limit);
        return $this->db->get()->result_array();
    }
}