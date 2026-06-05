<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @author:    dushime paul
 * Email:     dushimeyesupaulin@gmail.com
 * Date :     Le 20/01/2026
 */

class Sellers extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') !== TRUE) {
            redirect('Admin');
        }
        // Vérifier les permissions (seul super_admin et admin peuvent gérer les vendeurs)
        $role = $this->session->userdata('role');
        if ($role !== 'super_admin' && $role !== 'admin') {
            show_error('Vous n\'avez pas les permissions nécessaires pour accéder à cette page.', 403);
        }
    }

    // Liste des vendeurs
    public function index()
    {
        // Récupérer tous les vendeurs avec leurs informations
        $data['sellers'] = $this->db->select('v.*, u.email, u.prenom, u.nom, u.telephone, u.avatar_url, u.est_actif as user_actif, u.date_creation as user_date,
                                              p.province_name, c.commune_name, q.quartier_name')
            ->from('vendeurs v')
            ->join('utilisateurs u', 'u.id_utilisateur = v.id_utilisateur')
            ->join('provinces p', 'p.id_province = v.id_province', 'left')
            ->join('communes c', 'c.id_commune = v.id_commune', 'left')
            ->join('quartiers q', 'q.id_quartier = v.id_quartier', 'left')
            ->order_by('v.id_vendeur', 'DESC')
            ->get()
            ->result_array();
        
        // Ajouter les statistiques pour chaque vendeur
        foreach ($data['sellers'] as &$seller) {
            $seller['total_products'] = $this->Model->count('produits', ['id_vendeur' => $seller['id_vendeur']]);
            $seller['total_orders'] = $this->db->select('COUNT(DISTINCT a.id_commande) as total')
                ->from('articles_commande a')
                ->where('a.id_vendeur', $seller['id_vendeur'])
                ->get()
                ->row()->total ?? 0;
            $seller['total_revenue'] = $this->db->select_sum('a.prix_total')
                ->from('articles_commande a')
                ->where('a.id_vendeur', $seller['id_vendeur'])
                ->where('a.statut_article', 'livre')
                ->get()
                ->row()->prix_total ?? 0;
            $seller['note_moyenne'] = $this->db->select_avg('note_globale')
                ->from('evaluations_vendeurs')
                ->where('id_vendeur', $seller['id_vendeur'])
                ->get()
                ->row()->note_globale ?? 0;
            $seller['nombre_avis'] = $this->Model->count('evaluations_vendeurs', ['id_vendeur' => $seller['id_vendeur']]);
        }
        
        // Statistiques
        $data['total_sellers'] = count($data['sellers']);
        $data['pending_sellers'] = $this->Model->count('vendeurs', ['est_approuve' => 0]);
        $data['approved_sellers'] = $this->Model->count('vendeurs', ['est_approuve' => 1]);
        $data['active_sellers'] = $this->db->select('COUNT(*) as total')
            ->from('vendeurs v')
            ->join('utilisateurs u', 'u.id_utilisateur = v.id_utilisateur')
            ->where('u.est_actif', 1)
            ->where('v.est_approuve', 1)
            ->get()
            ->row()->total ?? 0;
        
        // Total des ventes
        $data['total_sales'] = $this->db->select('SUM(a.prix_total) as total')
            ->from('articles_commande a')
            ->join('vendeurs v', 'v.id_vendeur = a.id_vendeur')
            ->where('a.statut_article', 'livre')
            ->get()
            ->row()->total ?? 0;
        
        $this->load->view('seller_list', $data);
    }

    // Ajouter un vendeur (à partir d'un utilisateur existant)
    public function add()
    {
        // Charger les données pour les select (provinces, communes, quartiers)
        $data['provinces'] = $this->db->select('id_province, province_name')
            ->where('est_actif', 1)
            ->order_by('province_name')
            ->get('provinces')
            ->result_array();
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Récupérer l'ID de l'utilisateur sélectionné
            $id_utilisateur = $this->input->post('id_utilisateur');
            
            if (empty($id_utilisateur)) {
                $this->session->set_flashdata('error', 'Veuillez sélectionner un utilisateur.');
                redirect(base_url('Sellers/add'));
                return;
            }
            
            // Vérifier si l'utilisateur existe
            $user = $this->Model->readOne('utilisateurs', ['id_utilisateur' => $id_utilisateur]);
            if (!$user) {
                $this->session->set_flashdata('error', 'Utilisateur non trouvé.');
                redirect(base_url('Sellers/add'));
                return;
            }
            
            // Vérifier si l'utilisateur est déjà vendeur
            $existing_seller = $this->Model->readOne('vendeurs', ['id_utilisateur' => $id_utilisateur]);
            if ($existing_seller) {
                $this->session->set_flashdata('error', 'Cet utilisateur est déjà un vendeur.');
                redirect(base_url('Sellers/add'));
                return;
            }
            
            // Récupérer les données du formulaire
            $nom_boutique = trim($this->input->post('nom_boutique'));
            $description = trim($this->input->post('description'));
            $type_vendeur = $this->input->post('type_vendeur');
            $nom_entreprise = trim($this->input->post('nom_entreprise'));
            $numero_nif = trim($this->input->post('numero_nif'));
            $numero_rc = trim($this->input->post('numero_rc'));
            $taux_commission = $this->input->post('taux_commission');
            $whatsapp = trim($this->input->post('whatsapp'));
            
            // Informations de localisation
            $id_province = $this->input->post('id_province') ?: null;
            $id_commune = $this->input->post('id_commune') ?: null;
            $id_quartier = $this->input->post('id_quartier') ?: null;
            $latitude = $this->input->post('latitude') ?: null;
            $longitude = $this->input->post('longitude') ?: null;
            
            // Configuration de paiement
            $methode_paiement = $this->input->post('methode_paiement');
            $operateur_mobile = $this->input->post('operateur_mobile');
            $numero_mobile_money = $this->input->post('numero_mobile_money');
            $nom_abonne_mobile = $this->input->post('nom_abonne_mobile');
            $nom_banque = $this->input->post('nom_banque');
            $numero_compte = $this->input->post('numero_compte');
            $nom_titulaire = $this->input->post('nom_titulaire');
            
            // Validation
            if (empty($nom_boutique)) {
                $this->session->set_flashdata('error', 'Le nom de la boutique est obligatoire.');
                redirect(base_url('Sellers/add'));
                return;
            }
            
            // Vérifier si le nom de boutique existe déjà
            $slug = $this->createSlug($nom_boutique);
            $existing_shop = $this->Model->readOne('vendeurs', ['slug_boutique' => $slug]);
            if ($existing_shop) {
                $this->session->set_flashdata('error', 'Ce nom de boutique existe déjà.');
                redirect(base_url('Sellers/add'));
                return;
            }
            
            // Gestion du logo de la boutique
            $logo_boutique = null;
            if (!empty($_FILES['logo_boutique']['name'])) {
                $upload_logo = $this->upload_image($_FILES['logo_boutique']['tmp_name'], $_FILES['logo_boutique']['name'], 'logo');
                if ($upload_logo) {
                    $logo_boutique = 'attachments/Users/' . $upload_logo;
                }
            }
            
            // Créer le vendeur
            $vendeur_data = [
                'id_utilisateur' => $id_utilisateur,
                'nom_boutique' => $nom_boutique,
                'slug_boutique' => $slug,
                'logo_boutique' => $logo_boutique,
                'description' => $description,
                'type_vendeur' => $type_vendeur,
                'nom_entreprise' => !empty($nom_entreprise) ? $nom_entreprise : null,
                'numero_nif' => !empty($numero_nif) ? $numero_nif : null,
                'numero_rc' => !empty($numero_rc) ? $numero_rc : null,
                'id_province' => $id_province,
                'id_commune' => $id_commune,
                'id_quartier' => $id_quartier,
                'latitude' => !empty($latitude) ? $latitude : null,
                'longitude' => !empty($longitude) ? $longitude : null,
                'telephone' => $user['telephone'],
                'whatsapp' => !empty($whatsapp) ? $whatsapp : $user['telephone'],
                'taux_commission' => !empty($taux_commission) ? $taux_commission : 10,
                'delai_paiement_jours' => $this->input->post('delai_paiement_jours') ?: 7,
                'est_approuve' => $this->input->post('est_approuve') ? 1 : 0,
                'statut' => $this->input->post('statut') ?: ($this->input->post('est_approuve') ? 'actif' : 'en_attente'),
                'date_creation' => date('Y-m-d H:i:s')
            ];
            
            // Si approuvé directement
            if ($vendeur_data['est_approuve'] == 1) {
                $vendeur_data['date_approbation'] = date('Y-m-d H:i:s');
                $vendeur_data['approuve_par'] = $this->session->userdata('id_utilisateur');
            }
            
            $vendeur_id = $this->Model->createLastId('vendeurs', $vendeur_data);
            
            if ($vendeur_id) {
                // Assigner le profil vendeur (id_profil = 4) à l'utilisateur
                $existing_profile = $this->db->get_where('utilisateur_profils', [
                    'id_utilisateur' => $id_utilisateur,
                    'id_profil' => 4
                ])->row();
                
                if (!$existing_profile) {
                    $this->db->insert('utilisateur_profils', [
                        'id_utilisateur' => $id_utilisateur,
                        'id_profil' => 4,
                        'attribue_par' => $this->session->userdata('id_utilisateur'),
                        'date_attribution' => date('Y-m-d H:i:s')
                    ]);
                }
                
                // Créer le solde initial
                $this->db->insert('soldes_vendeurs', [
                    'id_vendeur' => $vendeur_id,
                    'solde_disponible' => 0,
                    'solde_en_attente' => 0,
                    'total_gagne' => 0,
                    'total_retire' => 0
                ]);
                
                // Créer la configuration de paiement
                $config_paiement = [
                    'id_vendeur' => $vendeur_id,
                    'methode_principale' => $methode_paiement,
                    'est_verifie' => 0,
                    'est_actif' => 1,
                    'date_creation' => date('Y-m-d H:i:s')
                ];
                
                if ($methode_paiement == 'mobile_money') {
                    $config_paiement['operateur_mobile'] = $operateur_mobile;
                    $config_paiement['numero_mobile_money'] = $numero_mobile_money;
                    $config_paiement['nom_abonne_mobile'] = $nom_abonne_mobile;
                } else {
                    $config_paiement['nom_banque'] = $nom_banque;
                    $config_paiement['numero_compte'] = $numero_compte;
                    $config_paiement['nom_titulaire'] = $nom_titulaire;
                }
                
                $this->db->insert('config_paiement_vendeur', $config_paiement);
                
                // Gestion des documents
                $doc_types = $this->input->post('doc_type');
                if (!empty($doc_types) && is_array($doc_types)) {
                    foreach ($doc_types as $index => $doc_type) {
                        if (!empty($_FILES['doc_file']['name'][$index])) {
                            $upload_doc = $this->upload_document($_FILES['doc_file']['tmp_name'][$index], $_FILES['doc_file']['name'][$index]);
                            if ($upload_doc) {
                                $this->db->insert('documents_vendeur', [
                                    'id_vendeur' => $vendeur_id,
                                    'type_document' => $doc_type,
                                    'fichier_document' => 'uploads/documents_vendeurs/' . $upload_doc,
                                    'statut_verification' => 'en_attente',
                                    'date_upload' => date('Y-m-d H:i:s')
                                ]);
                            }
                        }
                    }
                }
                
                $this->session->set_flashdata('success', 'Vendeur créé avec succès. L\'utilisateur ' . $user['prenom'] . ' ' . $user['nom'] . ' est maintenant vendeur.');
                redirect(base_url('Sellers'));
            } else {
                $this->session->set_flashdata('error', 'Erreur lors de la création du vendeur.');
                redirect(base_url('Sellers/add'));
            }
        }
        
        $this->load->view('seller_add', $data);
    }
    


    // Version avec exclusion des vendeurs existants
public function search_users()
{
    header('Content-Type: application/json');
    
    $search = $this->input->get('search');
    
    // Récupérer les IDs des utilisateurs déjà vendeurs
    $seller_ids = $this->db->select('id_utilisateur')->from('vendeurs')->get()->result_array();
    $exclude = array_column($seller_ids, 'id_utilisateur');
    
    // Requête
    $this->db->select('id_utilisateur, prenom, nom, email, telephone')
        ->from('utilisateurs')
        ->where('est_actif', 1);
    
    if (!empty($exclude)) {
        $this->db->where_not_in('id_utilisateur', $exclude);
    }
    
    if (!empty($search)) {
        $this->db->group_start();
        $this->db->like('prenom', $search);
        $this->db->or_like('nom', $search);
        $this->db->or_like('email', $search);
        $this->db->group_end();
    }
    
    $this->db->limit(20);
    $query = $this->db->get();
    $users = $query->result_array();
    
    $items = [];
    foreach ($users as $user) {
        $items[] = [
            'id' => $user['id_utilisateur'],
            'text' => $user['prenom'] . ' ' . $user['nom'] . ' (' . $user['email'] . ')',
            'prenom' => $user['prenom'],
            'nom' => $user['nom'],
            'email' => $user['email'],
            'telephone' => $user['telephone']
        ];
    }
    
    echo json_encode(['items' => $items]);
}


    
    // Récupérer les détails d'un utilisateur (AJAX)
    public function get_user_details()
    {
        $id_utilisateur = $this->input->post('id_utilisateur');
        
        $user = $this->db->select('id_utilisateur, prenom, nom, email, telephone, avatar_url, date_creation')
            ->from('utilisateurs')
            ->where('id_utilisateur', $id_utilisateur)
            ->get()
            ->row_array();
        
        if ($user) {
            echo json_encode([
                'success' => true,
                'user' => [
                    'prenom' => $user['prenom'],
                    'nom' => $user['nom'],
                    'email' => $user['email'],
                    'telephone' => $user['telephone'],
                    'avatar_url' => $user['avatar_url'],
                    'date_creation' => date('d/m/Y H:i', strtotime($user['date_creation']))
                ]
            ]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    // Détails d'un vendeur
    public function view($id)
    {
        $data['seller'] = $this->db->select('v.*, u.email, u.prenom, u.nom, u.telephone, u.avatar_url, u.est_actif as user_actif, u.date_creation as user_date, u.derniere_connexion,
                                              p.province_name, c.commune_name, q.quartier_name')
            ->from('vendeurs v')
            ->join('utilisateurs u', 'u.id_utilisateur = v.id_utilisateur')
            ->join('provinces p', 'p.id_province = v.id_province', 'left')
            ->join('communes c', 'c.id_commune = v.id_commune', 'left')
            ->join('quartiers q', 'q.id_quartier = v.id_quartier', 'left')
            ->where('v.id_vendeur', $id)
            ->get()
            ->row_array();
        
        if (!$data['seller']) {
            $this->session->set_flashdata('error', 'Vendeur non trouvé.');
            redirect(base_url('Sellers'));
        }
        
        // Récupérer la configuration de paiement
        $data['payment_config'] = $this->db->select('*')
            ->from('config_paiement_vendeur')
            ->where('id_vendeur', $id)
            ->get()
            ->row_array();
        
        // Récupérer les documents du vendeur
        $data['documents'] = $this->db->select('*')
            ->from('documents_vendeur')
            ->where('id_vendeur', $id)
            ->order_by('date_upload', 'DESC')
            ->get()
            ->result_array();
        
        // Récupérer le solde du vendeur
        $data['solde'] = $this->db->select('*')
            ->from('soldes_vendeurs')
            ->where('id_vendeur', $id)
            ->get()
            ->row_array();
        
        // Récupérer les produits du vendeur
        $data['products'] = $this->db->select('p.*, c.nom_categorie')
            ->from('produits p')
            ->join('categories c', 'c.id_categorie = p.id_categorie', 'left')
            ->where('p.id_vendeur', $id)
            ->order_by('p.date_creation', 'DESC')
            ->limit(10)
            ->get()
            ->result_array();
        
        $data['total_products'] = $this->Model->count('produits', ['id_vendeur' => $id]);
        
        // Récupérer les commandes du vendeur
        $data['orders'] = $this->db->select('a.*, c.numero_commande, c.date_creation as order_date, c.statut_commande, u.prenom, u.nom, u.email')
            ->from('articles_commande a')
            ->join('commandes c', 'c.id_commande = a.id_commande')
            ->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur')
            ->where('a.id_vendeur', $id)
            ->order_by('c.date_creation', 'DESC')
            ->limit(10)
            ->get()
            ->result_array();
        
        $data['total_orders'] = $this->Model->count('articles_commande', ['id_vendeur' => $id]);
        
        // Commandes par statut
        $data['order_stats'] = $this->db->select('a.statut_article, COUNT(*) as total')
            ->from('articles_commande a')
            ->where('a.id_vendeur', $id)
            ->group_by('a.statut_article')
            ->get()
            ->result_array();
        
        // Nombre de clients uniques
        $data['total_customers'] = $this->db->select('COUNT(DISTINCT c.id_utilisateur) as total')
            ->from('articles_commande a')
            ->join('commandes c', 'c.id_commande = a.id_commande')
            ->where('a.id_vendeur', $id)
            ->get()
            ->row()->total ?? 0;
        
        // Chiffre d'affaires
        $data['total_revenue'] = $this->db->select_sum('prix_total')
            ->from('articles_commande')
            ->where('id_vendeur', $id)
            ->where('statut_article', 'livre')
            ->get()
            ->row()->prix_total ?? 0;
        
        // Commission totale
        $data['total_commission'] = $this->db->select_sum('montant_commission')
            ->from('articles_commande')
            ->where('id_vendeur', $id)
            ->where('statut_article', 'livre')
            ->get()
            ->row()->montant_commission ?? 0;
        
        // Revenu net
        $data['net_revenue'] = $data['total_revenue'] - $data['total_commission'];
        
        // Évaluations
        $data['reviews'] = $this->db->select('ev.*, u.prenom, u.nom, u.avatar_url')
            ->from('evaluations_vendeurs ev')
            ->join('utilisateurs u', 'u.id_utilisateur = ev.id_utilisateur')
            ->where('ev.id_vendeur', $id)
            ->order_by('ev.date_creation', 'DESC')
            ->limit(5)
            ->get()
            ->result_array();
        
        // Distribution des notes
        $rating_data = $this->db->select('note_globale, COUNT(*) as total')
            ->from('evaluations_vendeurs')
            ->where('id_vendeur', $id)
            ->group_by('note_globale')
            ->get()
            ->result_array();

        $data['rating_distribution'] = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        foreach($rating_data as $rd) {
            $data['rating_distribution'][$rd['note_globale']] = $rd['total'];
        }
        $data['total_reviews'] = array_sum($data['rating_distribution']);
        
        // Note moyenne
        $data['avg_rating'] = $this->db->select_avg('note_globale')
            ->from('evaluations_vendeurs')
            ->where('id_vendeur', $id)
            ->get()
            ->row()->note_globale ?? 0;

        // Graphique des ventes par mois
        $data['months_labels'] = [];
        $data['sales_chart_data'] = [];
        for($i = 5; $i >= 0; $i--) {
            $month = date('m', strtotime("-$i month"));
            $year = date('Y', strtotime("-$i month"));
            $data['months_labels'][] = date('M Y', strtotime("-$i month"));
            
            $monthly_sales = $this->db->select_sum('a.prix_total')
                ->from('articles_commande a')
                ->join('commandes c', 'c.id_commande = a.id_commande')
                ->where('a.id_vendeur', $id)
                ->where('MONTH(c.date_creation)', $month)
                ->where('YEAR(c.date_creation)', $year)
                ->where('a.statut_article', 'livre')
                ->get()
                ->row()->prix_total ?? 0;
            
            $data['sales_chart_data'][] = (float)$monthly_sales;
        }

        // Profit par catégorie
        $data['category_profits'] = $this->db->select('c.nom_categorie, SUM(a.prix_total) as total')
            ->from('articles_commande a')
            ->join('produits p', 'p.id_produit = a.id_produit')
            ->join('categories c', 'c.id_categorie = p.id_categorie')
            ->where('a.id_vendeur', $id)
            ->where('a.statut_article', 'livre')
            ->group_by('c.id_categorie')
            ->order_by('total', 'DESC')
            ->limit(4)
            ->get()
            ->result_array();

        $max_total = !empty($data['category_profits']) ? max(array_column($data['category_profits'], 'total')) : 1;
        foreach($data['category_profits'] as &$cat) {
            $cat['percentage'] = ($cat['total'] / $max_total) * 100;
            $colors = ['primary', 'success', 'warning', 'info'];
            $cat['color'] = $colors[array_rand($colors)];
        }

        // Calcul de la croissance
        $current_month = $this->db->select_sum('a.prix_total')
            ->from('articles_commande a')
            ->join('commandes c', 'c.id_commande = a.id_commande')
            ->where('a.id_vendeur', $id)
            ->where('MONTH(c.date_creation)', date('m'))
            ->where('YEAR(c.date_creation)', date('Y'))
            ->where('a.statut_article', 'livre')
            ->get()
            ->row()->prix_total ?? 0;

        $last_month = $this->db->select_sum('a.prix_total')
            ->from('articles_commande a')
            ->join('commandes c', 'c.id_commande = a.id_commande')
            ->where('a.id_vendeur', $id)
            ->where('MONTH(c.date_creation)', date('m', strtotime('-1 month')))
            ->where('YEAR(c.date_creation)', date('Y'))
            ->where('a.statut_article', 'livre')
            ->get()
            ->row()->prix_total ?? 0;

        $data['revenue_growth'] = $last_month > 0 ? (($current_month - $last_month) / $last_month) * 100 : 0;
        $data['monthly_gain'] = $current_month;

        // Objectifs
        $data['order_target'] = 500;
        $data['order_percentage'] = min(100, ($data['total_orders'] / $data['order_target']) * 100);
        $data['customer_target'] = 1000;
        $data['customer_percentage'] = min(100, (($data['total_customers'] ?? 0) / $data['customer_target']) * 100);
        
        // Récupérer les historiques des paiements vendeur
        $data['payments_history'] = $this->db->select('*')
            ->from('paiements_vendeurs')
            ->where('id_vendeur', $id)
            ->order_by('date_creation', 'DESC')
            ->limit(10)
            ->get()
            ->result_array();
        
        $this->load->view('seller_detail', $data);
    }

    // Modifier un vendeur
    public function edit($id)
    {
         $data['seller'] = $this->db->select('v.*, u.email, u.prenom, u.nom, u.telephone, u.avatar_url, u.est_actif as user_actif, u.date_creation as user_date')
        ->from('vendeurs v')
        ->join('utilisateurs u', 'u.id_utilisateur = v.id_utilisateur')
        ->where('v.id_vendeur', $id)
        ->get()
        ->row_array();
        
        if (!$data['seller']) {
            $this->session->set_flashdata('error', 'Vendeur non trouvé.');
            redirect(base_url('Sellers'));
        }
        
        // Charger les données pour les select
        $data['provinces'] = $this->db->select('id_province, province_name')
            ->where('est_actif', 1)
            ->order_by('province_name')
            ->get('provinces')
            ->result_array();
        
        $data['communes'] = [];
        if ($data['seller']['id_province']) {
            $data['communes'] = $this->db->select('id_commune, commune_name')
                ->where('id_province', $data['seller']['id_province'])
                ->where('est_actif', 1)
                ->order_by('commune_name')
                ->get('communes')
                ->result_array();
        }
        
        $data['quartiers'] = [];
        if ($data['seller']['id_commune']) {
            $data['quartiers'] = $this->db->select('id_quartier, quartier_name')
                ->where('id_commune', $data['seller']['id_commune'])
                ->where('est_actif', 1)
                ->order_by('quartier_name')
                ->get('quartiers')
                ->result_array();
        }
        
        // Récupérer la configuration de paiement
        $data['payment_config'] = $this->db->select('*')
            ->from('config_paiement_vendeur')
            ->where('id_vendeur', $id)
            ->get()
            ->row_array();
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Mise à jour de l'utilisateur (seulement si nécessaire)
            $user_data = [];
            
            // Changer le mot de passe si fourni
            $new_password = $this->input->post('new_password');
            if (!empty($new_password)) {
                if (strlen($new_password) >= 6) {
                    $user_data['mot_de_passe'] = md5($new_password);
                } else {
                    $this->session->set_flashdata('error', 'Le mot de passe doit contenir au moins 6 caractères.');
                    redirect(base_url('Sellers/edit/' . $id));
                    return;
                }
            }
            
            if (!empty($user_data)) {
                $user_data['date_modification'] = date('Y-m-d H:i:s');
                $this->Model->update('utilisateurs', ['id_utilisateur' => $data['seller']['id_utilisateur']], $user_data);
            }
            
            // Gestion du logo de la boutique
            $logo_boutique = $data['seller']['logo_boutique'];
            if (!empty($_FILES['logo_boutique']['name'])) {
                $upload_logo = $this->upload_image($_FILES['logo_boutique']['tmp_name'], $_FILES['logo_boutique']['name'], 'logo');
                if ($upload_logo) {
                    if ($data['seller']['logo_boutique'] && file_exists(FCPATH . $data['seller']['logo_boutique'])) {
                        unlink(FCPATH . $data['seller']['logo_boutique']);
                    }
                    $logo_boutique = 'attachments/Users/' . $upload_logo;
                }
            }
            
            // Mise à jour du vendeur
            $slug = $this->createSlug($this->input->post('nom_boutique'));
            $vendeur_data = [
                'nom_boutique' => trim($this->input->post('nom_boutique')),
                'slug_boutique' => $slug,
                'logo_boutique' => $logo_boutique,
                'description' => trim($this->input->post('description')),
                'type_vendeur' => $this->input->post('type_vendeur'),
                'nom_entreprise' => !empty($this->input->post('nom_entreprise')) ? trim($this->input->post('nom_entreprise')) : null,
                'numero_nif' => !empty($this->input->post('numero_nif')) ? trim($this->input->post('numero_nif')) : null,
                'numero_rc' => !empty($this->input->post('numero_rc')) ? trim($this->input->post('numero_rc')) : null,
                'id_province' => $this->input->post('id_province') ?: null,
                'id_commune' => $this->input->post('id_commune') ?: null,
                'id_quartier' => $this->input->post('id_quartier') ?: null,
                'latitude' => !empty($this->input->post('latitude')) ? $this->input->post('latitude') : null,
                'longitude' => !empty($this->input->post('longitude')) ? $this->input->post('longitude') : null,
                'whatsapp' => !empty($this->input->post('whatsapp')) ? trim($this->input->post('whatsapp')) : $data['seller']['telephone'],
                'taux_commission' => $this->input->post('taux_commission') ?: 10,
                'est_approuve' => $this->input->post('est_approuve') ? 1 : 0,
                'statut' => $this->input->post('statut'),
                'date_modification' => date('Y-m-d H:i:s')
            ];
            
            // Si approuvé, ajouter la date d'approbation
            if ($this->input->post('est_approuve') && !$data['seller']['est_approuve']) {
                $vendeur_data['date_approbation'] = date('Y-m-d H:i:s');
                $vendeur_data['approuve_par'] = $this->session->userdata('id_utilisateur');
            }
            
            $this->Model->update('vendeurs', ['id_vendeur' => $id], $vendeur_data);
            
            // Mise à jour de la configuration de paiement
            $methode_paiement = $this->input->post('methode_paiement');
            $config_data = [
                'methode_principale' => $methode_paiement
            ];
            
            if ($methode_paiement == 'mobile_money') {
                $config_data['operateur_mobile'] = $this->input->post('operateur_mobile');
                $config_data['numero_mobile_money'] = $this->input->post('numero_mobile_money');
                $config_data['nom_abonne_mobile'] = $this->input->post('nom_abonne_mobile');
                $config_data['nom_banque'] = null;
                $config_data['numero_compte'] = null;
                $config_data['nom_titulaire'] = null;
            } else {
                $config_data['operateur_mobile'] = null;
                $config_data['numero_mobile_money'] = null;
                $config_data['nom_abonne_mobile'] = null;
                $config_data['nom_banque'] = $this->input->post('nom_banque');
                $config_data['numero_compte'] = $this->input->post('numero_compte');
                $config_data['nom_titulaire'] = $this->input->post('nom_titulaire');
            }
            
            if ($data['payment_config']) {
                $this->Model->update('config_paiement_vendeur', ['id_config' => $data['payment_config']['id_config']], $config_data);
            } else {
                $config_data['id_vendeur'] = $id;
                $config_data['est_verifie'] = 0;
                $config_data['est_actif'] = 1;
                $config_data['date_creation'] = date('Y-m-d H:i:s');
                $this->db->insert('config_paiement_vendeur', $config_data);
            }
            
            $this->session->set_flashdata('success', 'Vendeur modifié avec succès.');
            redirect(base_url('Sellers/view/' . $id));
        }
        
        $this->load->view('seller_edit', $data);
    }

    // Approuver un vendeur
    public function approve($id)
    {
        $seller = $this->Model->readOne('vendeurs', ['id_vendeur' => $id]);
        
        if (!$seller) {
            $this->session->set_flashdata('error', 'Vendeur non trouvé.');
            redirect(base_url('Sellers'));
        }
        
        $this->Model->update('vendeurs', ['id_vendeur' => $id], [
            'est_approuve' => 1,
            'statut' => 'actif',
            'date_approbation' => date('Y-m-d H:i:s'),
            'approuve_par' => $this->session->userdata('id_utilisateur')
        ]);
        
        $this->session->set_flashdata('success', 'Vendeur approuvé avec succès.');
        redirect(base_url('Sellers'));
    }

    // Suspendre un vendeur
    public function suspend($id)
    {
        $seller = $this->Model->readOne('vendeurs', ['id_vendeur' => $id]);
        
        if (!$seller) {
            $this->session->set_flashdata('error', 'Vendeur non trouvé.');
            redirect(base_url('Sellers'));
        }
        
        $new_status = $seller['statut'] == 'actif' ? 'suspendu' : 'actif';
        $this->Model->update('vendeurs', ['id_vendeur' => $id], ['statut' => $new_status]);
        
        // Mettre à jour l'utilisateur associé (optionnel)
        // $this->Model->update('utilisateurs', ['id_utilisateur' => $seller['id_utilisateur']], 
        //     ['est_actif' => $new_status == 'actif' ? 1 : 0]);
        
        $this->session->set_flashdata('success', 'Statut du vendeur modifié avec succès.');
        redirect(base_url('Sellers'));
    }

    // Bannir un vendeur
    public function ban($id)
    {
        $seller = $this->Model->readOne('vendeurs', ['id_vendeur' => $id]);
        
        if (!$seller) {
            $this->session->set_flashdata('error', 'Vendeur non trouvé.');
            redirect(base_url('Sellers'));
        }
        
        $motif = $this->input->post('motif_ban');
        
        $this->Model->update('vendeurs', ['id_vendeur' => $id], [
            'statut' => 'banni'
        ]);
        
        $this->Model->update('utilisateurs', ['id_utilisateur' => $seller['id_utilisateur']], [
            'est_banni' => 1,
            'motif_bannissement' => $motif
        ]);
        
        $this->session->set_flashdata('success', 'Vendeur banni avec succès.');
        redirect(base_url('Sellers'));
    }

    // Supprimer un vendeur (ne supprime pas l'utilisateur, seulement le statut vendeur)
    public function delete($id)
    {
        $seller = $this->db->select('v.*, u.id_utilisateur')
            ->from('vendeurs v')
            ->join('utilisateurs u', 'u.id_utilisateur = v.id_utilisateur')
            ->where('v.id_vendeur', $id)
            ->get()
            ->row_array();
        
        if (!$seller) {
            $this->session->set_flashdata('error', 'Vendeur non trouvé.');
            redirect(base_url('Sellers'));
        }
        
        // Supprimer le logo
        if ($seller['logo_boutique'] && file_exists(FCPATH . $seller['logo_boutique'])) {
            unlink(FCPATH . $seller['logo_boutique']);
        }
        
        // Supprimer la configuration de paiement
        $this->db->delete('config_paiement_vendeur', ['id_vendeur' => $id]);
        
        // Supprimer les documents
        $documents = $this->db->select('fichier_document')
            ->from('documents_vendeur')
            ->where('id_vendeur', $id)
            ->get()
            ->result_array();
        foreach ($documents as $doc) {
            if (file_exists(FCPATH . $doc['fichier_document'])) {
                unlink(FCPATH . $doc['fichier_document']);
            }
        }
        $this->db->delete('documents_vendeur', ['id_vendeur' => $id]);
        
        // Supprimer le solde
        $this->db->delete('soldes_vendeurs', ['id_vendeur' => $id]);
        
        // Supprimer les paiements vendeurs
        $this->db->delete('paiements_vendeurs', ['id_vendeur' => $id]);
        
        // Retirer le profil vendeur (id_profil = 4) de l'utilisateur
        $this->db->delete('utilisateur_profils', [
            'id_utilisateur' => $seller['id_utilisateur'],
            'id_profil' => 4
        ]);
        
        // Supprimer le vendeur
        $this->Model->delete('vendeurs', ['id_vendeur' => $id]);
        
        $this->session->set_flashdata('success', 'Vendeur supprimé avec succès. L\'utilisateur reste actif sur la plateforme.');
        redirect(base_url('Sellers'));
    }

    // Obtenir les communes par province (AJAX)
    public function get_communes()
    {
        $id_province = $this->input->post('id_province');
        if ($id_province) {
            $communes = $this->db->select('id_commune, commune_name')
                ->where('id_province', $id_province)
                ->where('est_actif', 1)
                ->order_by('commune_name')
                ->get('communes')
                ->result_array();
            echo json_encode($communes);
        } else {
            echo json_encode([]);
        }
    }

    // Obtenir les quartiers par commune (AJAX)
    public function get_quartiers()
    {
        $id_commune = $this->input->post('id_commune');
        if ($id_commune) {
            $quartiers = $this->db->select('id_quartier, quartier_name')
                ->where('id_commune', $id_commune)
                ->where('est_actif', 1)
                ->order_by('quartier_name')
                ->get('quartiers')
                ->result_array();
            echo json_encode($quartiers);
        } else {
            echo json_encode([]);
        }
    }

    // Upload d'image
    public function upload_image($nom_file, $nom_champ, $type = 'avatar')
    {
        $ref_folder = FCPATH . 'attachments/Users/';
        $code = date("YmdHis") . uniqid();
        $fichier = basename($code);
        $file_extension = pathinfo($nom_champ, PATHINFO_EXTENSION);
        $file_extension = strtolower($file_extension);
        $valid_ext = array('gif', 'jpg', 'png', 'jpeg', 'webp', 'svg');

        if (!in_array($file_extension, $valid_ext)) {
            return NULL;
        }

        if (!is_dir($ref_folder)) {
            mkdir($ref_folder, 0777, TRUE);
        }

        move_uploaded_file($nom_file, $ref_folder . $fichier . "." . $file_extension);
        return $fichier . "." . $file_extension;
    }
    
    // Upload de document
    public function upload_document($nom_file, $nom_champ)
    {
        $ref_folder = FCPATH . 'uploads/documents_vendeurs/';
        $code = date("YmdHis") . uniqid();
        $fichier = basename($code);
        $file_extension = pathinfo($nom_champ, PATHINFO_EXTENSION);
        $file_extension = strtolower($file_extension);
        $valid_ext = array('gif', 'jpg', 'png', 'jpeg', 'pdf', 'webp');

        if (!in_array($file_extension, $valid_ext)) {
            return NULL;
        }

        if (!is_dir($ref_folder)) {
            mkdir($ref_folder, 0777, TRUE);
        }

        move_uploaded_file($nom_file, $ref_folder . $fichier . "." . $file_extension);
        return $fichier . "." . $file_extension;
    }

    // Créer un slug
    private function createSlug($string)
    {
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9-]/', '-', $string);
        $string = preg_replace('/-+/', '-', $string);
        return trim($string, '-');
    }
}
?>