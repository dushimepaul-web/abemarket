<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ArticleCommande extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('ArticleCommande_model');
        $this->load->model('RetourRemboursement_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }
    
    /**
     * Vérifier si l'utilisateur est admin
     */
    private function is_admin() {
        $user_id = $this->session->userdata('id_utilisateur');
        $this->db->select('up.id_profil')
                 ->from('utilisateur_profils up')
                 ->where('up.id_utilisateur', $user_id)
                 ->where_in('up.id_profil', [1, 2]);
        return $this->db->get()->num_rows() > 0;
    }
    
    /**
     * Récupérer l'ID du vendeur connecté
     */
    private function get_vendeur_id() {
        $user_id = $this->session->userdata('id_utilisateur');
        $vendeur = $this->db->where('id_utilisateur', $user_id)->get('vendeurs')->row();
        return $vendeur ? $vendeur->id_vendeur : null;
    }
    
    /**
     * Liste des articles commandés
     */
    public function index() {
        $is_admin = $this->is_admin();
        $id_vendeur = $this->get_vendeur_id();
        
        $data['title'] = 'Gestion des articles commandés';
        $data['is_admin'] = $is_admin;
        
        // Filtres
        $filters = [
            'statut_article' => $this->input->get('statut'),
            'search' => $this->input->get('search')
        ];
        
        if (!$is_admin && $id_vendeur) {
            $filters['id_vendeur'] = $id_vendeur;
        }
        
        // Configuration de la pagination
        if ($is_admin) {
            $total_rows = count($this->ArticleCommande_model->get_all_articles(null, null, $filters));
        } else {
            $total_rows = count($this->ArticleCommande_model->get_articles_by_vendeur($id_vendeur, null, null, $filters));
        }
        
        $config['base_url'] = base_url('ArticleCommande/index');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 20;
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
        
        // Récupérer les articles
        if ($is_admin) {
            $data['articles'] = $this->ArticleCommande_model->get_all_articles(
                $config['per_page'],
                $this->uri->segment(3),
                $filters
            );
        } else {
            $data['articles'] = $this->ArticleCommande_model->get_articles_by_vendeur(
                $id_vendeur,
                $config['per_page'],
                $this->uri->segment(3),
                $filters
            );
        }
        
        // Statistiques
        $data['stats'] = $this->ArticleCommande_model->get_ventes_stats($id_vendeur);
        $data['statuts'] = $this->ArticleCommande_model->get_statuts_options();
        $data['filters'] = $filters;
        
        $this->load->view('article_commande_list', $data);
    }
    
    /**
     * Détails d'un article
     */
    public function detail($id) {
        $data['article'] = $this->ArticleCommande_model->get_article_by_id($id);
        
        if (!$data['article']) {
            show_404();
        }
        
        // Vérifier les droits d'accès
        $is_admin = $this->is_admin();
        $id_vendeur = $this->get_vendeur_id();
        
        if (!$is_admin && $id_vendeur && $data['article']->id_vendeur != $id_vendeur) {
            show_error('Accès non autorisé', 403);
        }
        
        $data['title'] = 'Détails de l\'article';
        $data['is_admin'] = $is_admin;
        $data['statuts'] = $this->ArticleCommande_model->get_statuts_options();
        $data['motifs_retour'] = $this->RetourRemboursement_model->get_motifs_options();
        
        // Vérifier si une demande de retour existe déjà
        $data['retour_existant'] = $this->db->where('id_article', $id)
                                            ->where_in('statut', ['demande', 'en_cours', 'approuve'])
                                            ->get('retours_remboursements')
                                            ->row();
        
        $this->load->view('article_commande/detail', $data);
    }
    
    /**
     * Changer le statut d'un article
     */
    public function change_statut() {
        $id_article = $this->input->post('id_article');
        $nouveau_statut = $this->input->post('statut');
        $commentaire = $this->input->post('commentaire');
        
        $article = $this->ArticleCommande_model->get_article_by_id($id_article);
        
        if (!$article) {
            echo json_encode(['success' => false, 'message' => 'Article non trouvé']);
            return;
        }
        
        // Vérifier les droits
        $is_admin = $this->is_admin();
        $id_vendeur = $this->get_vendeur_id();
        
        if (!$is_admin && $id_vendeur && $article->id_vendeur != $id_vendeur) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $updated = $this->ArticleCommande_model->update_statut_article($id_article, $nouveau_statut, $commentaire);
        
        if ($updated) {
            echo json_encode(['success' => true, 'message' => 'Statut mis à jour avec succès']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
        }
    }
    
    /**
     * Demander un retour d'article
     */
    public function demander_retour() {
        $id_article = $this->input->post('id_article');
        $motif = $this->input->post('motif');
        $description = $this->input->post('description');
        $montant_demande = $this->input->post('montant_demande');
        
        $article = $this->ArticleCommande_model->get_article_by_id($id_article);
        
        if (!$article) {
            echo json_encode(['success' => false, 'message' => 'Article non trouvé']);
            return;
        }
        
        // Vérifier que l'utilisateur est le client
        $user_id = $this->session->userdata('id_utilisateur');
        if ($article->id_utilisateur != $user_id) {
            echo json_encode(['success' => false, 'message' => 'Vous n\'êtes pas autorisé à demander un retour pour cet article']);
            return;
        }
        
        // Vérifier que l'article est livré
        if ($article->statut_article != 'livre') {
            echo json_encode(['success' => false, 'message' => 'Cet article n\'a pas encore été livré']);
            return;
        }
        
        // Vérifier qu'un retour n'est pas déjà demandé
        $existant = $this->db->where('id_article', $id_article)
                             ->where_in('statut', ['demande', 'en_cours', 'approuve'])
                             ->get('retours_remboursements')
                             ->row();
        
        if ($existant) {
            echo json_encode(['success' => false, 'message' => 'Une demande de retour existe déjà pour cet article']);
            return;
        }
        
        $data = [
            'id_commande' => $article->id_commande,
            'id_article' => $id_article,
            'id_utilisateur' => $user_id,
            'type' => 'retour_produit',
            'motif' => $motif,
            'description' => $description,
            'montant_demande' => $montant_demande ?: $article->prix_unitaire,
            'statut' => 'demande',
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        $id_retour = $this->RetourRemboursement_model->creer_retour($data);
        
        if ($id_retour) {
            echo json_encode(['success' => true, 'message' => 'Demande de retour envoyée avec succès']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la création de la demande']);
        }
    }
    
    /**
     * Exporter les articles en CSV
     */
    public function exporter() {
        $is_admin = $this->is_admin();
        $id_vendeur = $this->get_vendeur_id();
        
        $filters = [
            'statut_article' => $this->input->get('statut'),
            'search' => $this->input->get('search')
        ];
        
        $data = $this->ArticleCommande_model->exporter_articles($is_admin ? null : $id_vendeur, $filters);
        
        $this->load->helper('download');
        
        $csv = '';
        foreach ($data as $row) {
            $csv .= '"' . implode('","', array_map('addslashes', $row)) . '"' . "\n";
        }
        
        force_download('articles_commandes_' . date('Y-m-d') . '.csv', $csv);
    }

    /**
 * Page d'ajout/modification d'un article
 */
public function add_edit($id = null) {
    $is_admin = $this->is_admin();
    $id_vendeur = $this->get_vendeur_id();
    
    if (!$is_admin && !$id_vendeur) {
        show_error('Accès non autorisé', 403);
    }
    
    $data['title'] = $id ? 'Modifier l\'article' : 'Nouvel article';
    
    // Récupérer les commandes
    $this->load->model('Commande_model');
    $data['commandes'] = $this->Commande_model->get_all_commandes();
    
    // Récupérer les produits
    $this->db->select('p.*')->from('produits p');
    if (!$is_admin && $id_vendeur) {
        $this->db->where('p.id_vendeur', $id_vendeur);
    }
    $this->db->where('p.statut', 'actif');
    $data['produits'] = $this->db->get()->result();
    
    // Récupérer les vendeurs
    $this->db->select('v.*, u.prenom, u.nom')
             ->from('vendeurs v')
             ->join('utilisateurs u', 'v.id_utilisateur = u.id_utilisateur');
    $data['vendeurs'] = $this->db->get()->result();
    
    $data['statuts'] = $this->ArticleCommande_model->get_statuts_options();
    $data['motifs_retour'] = $this->RetourRemboursement_model->get_motifs_options();
    
    if ($id) {
        $data['article'] = $this->ArticleCommande_model->get_article_by_id($id);
        if (!$data['article']) {
            show_404();
        }
        
        // Récupérer les variantes du produit
        if ($data['article']->id_produit) {
            $data['variantes'] = $this->db->where('id_produit', $data['article']->id_produit)
                                          ->get('variantes_produit')
                                          ->result();
        }
    }
    
    $this->load->view('article_commande_add_edit', $data);
}

/**
 * Sauvegarder un article (AJAX)
 */
public function save() {
    $is_admin = $this->is_admin();
    $id_vendeur = $this->get_vendeur_id();
    
    $this->form_validation->set_rules('id_commande', 'Commande', 'required');
    $this->form_validation->set_rules('id_produit', 'Produit', 'required');
    $this->form_validation->set_rules('id_vendeur', 'Vendeur', 'required');
    $this->form_validation->set_rules('prix_unitaire', 'Prix unitaire', 'required|numeric|greater_than[0]');
    $this->form_validation->set_rules('quantite', 'Quantité', 'required|integer|greater_than[0]');
    
    if ($this->form_validation->run() == false) {
        echo json_encode(['success' => false, 'message' => validation_errors()]);
        return;
    }
    
    $prix_unitaire = $this->input->post('prix_unitaire');
    $quantite = $this->input->post('quantite');
    $prix_total = $prix_unitaire * $quantite;
    $taux_commission = $this->input->post('taux_commission') ?: 10;
    $montant_commission = ($prix_total * $taux_commission) / 100;
    $revenus_vendeur = $prix_total - $montant_commission;
    
    // Récupérer les informations du produit
    $produit = $this->db->where('id_produit', $this->input->post('id_produit'))->get('produits')->row();
    
    $data = [
        'id_commande' => $this->input->post('id_commande'),
        'id_produit' => $this->input->post('id_produit'),
        'id_variante' => $this->input->post('id_variante') ?: null,
        'id_vendeur' => $this->input->post('id_vendeur'),
        'nom_produit' => $produit ? $produit->nom_produit : '',
        'sku_produit' => $produit ? $produit->sku : '',
        'prix_unitaire' => $prix_unitaire,
        'quantite' => $quantite,
        'prix_total' => $prix_total,
        'taux_commission' => $taux_commission,
        'montant_commission' => $montant_commission,
        'revenus_vendeur' => $revenus_vendeur,
        'statut_article' => $this->input->post('statut_article') ?: 'en_attente',
        'est_retourne' => $this->input->post('est_retourne') ? 1 : 0,
        'motif_retour' => $this->input->post('motif_retour'),
        'date_demande_retour' => $this->input->post('date_demande_retour'),
        'avis_laisse' => $this->input->post('avis_laisse') ? 1 : 0
    ];
    
    $id_article = $this->ArticleCommande_model->ajouter_article($data);
    
    if ($id_article) {
        echo json_encode(['success' => true, 'message' => 'Article ajouté avec succès', 'id' => $id_article]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout']);
    }
}

/**
 * Mettre à jour un article
 */
public function update($id) {
    $is_admin = $this->is_admin();
    $id_vendeur = $this->get_vendeur_id();
    
    $article = $this->ArticleCommande_model->get_article_by_id($id);
    
    if (!$article) {
        echo json_encode(['success' => false, 'message' => 'Article non trouvé']);
        return;
    }
    
    if (!$is_admin && $article->id_vendeur != $id_vendeur) {
        echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
        return;
    }
    
    $prix_unitaire = $this->input->post('prix_unitaire');
    $quantite = $this->input->post('quantite');
    $prix_total = $prix_unitaire * $quantite;
    $taux_commission = $this->input->post('taux_commission') ?: $article->taux_commission;
    $montant_commission = ($prix_total * $taux_commission) / 100;
    $revenus_vendeur = $prix_total - $montant_commission;
    
    $data = [
        'prix_unitaire' => $prix_unitaire,
        'quantite' => $quantite,
        'prix_total' => $prix_total,
        'taux_commission' => $taux_commission,
        'montant_commission' => $montant_commission,
        'revenus_vendeur' => $revenus_vendeur,
        'statut_article' => $this->input->post('statut_article'),
        'est_retourne' => $this->input->post('est_retourne') ? 1 : 0,
        'motif_retour' => $this->input->post('motif_retour'),
        'date_demande_retour' => $this->input->post('date_demande_retour'),
        'avis_laisse' => $this->input->post('avis_laisse') ? 1 : 0
    ];
    
    $this->db->where('id_article', $id);
    $updated = $this->db->update('articles_commande', $data);
    
    if ($updated) {
        echo json_encode(['success' => true, 'message' => 'Article mis à jour avec succès', 'id' => $id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Aucune modification effectuée']);
    }
}

/**
 * Récupérer les variantes d'un produit (AJAX)
 */
public function get_variantes() {
    $id_produit = $this->input->post('id_produit');
    
    if ($id_produit) {
        $variantes = $this->db->where('id_produit', $id_produit)
                              ->where('est_actif', 1)
                              ->get('variantes_produit')
                              ->result();
        echo json_encode($variantes);
    } else {
        echo json_encode([]);
    }
}

/**
 * Supprimer un article
 */
public function delete($id) {
    $is_admin = $this->is_admin();
    $id_vendeur = $this->get_vendeur_id();
    
    $article = $this->ArticleCommande_model->get_article_by_id($id);
    
    if (!$article) {
        echo json_encode(['success' => false, 'message' => 'Article non trouvé']);
        return;
    }
    
    // Vérifier les droits
    if (!$is_admin && $article->id_vendeur != $id_vendeur) {
        echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
        return;
    }
    
    $deleted = $this->db->where('id_article', $id)->delete('articles_commande');
    
    if ($deleted) {
        echo json_encode(['success' => true, 'message' => 'Article supprimé avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
    }
}
}
?>