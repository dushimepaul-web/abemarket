<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @author:    dushime paul
 * Email:     dushimeyesupaulin@gmail.com
 * Date :     Le 20/01/2026
 * https://github.com/Dushimepaul
*/

class Dashboard extends My_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') !== TRUE) {
            redirect('Admin');
        }
    }

    public function index()
{
    $role = $this->session->userdata('role');
    $role_dashboards = [
        'vendeur' => 'Vendeur/Dashboard',
        'client' => 'Client/Dashboard',
        'livreur' => 'Livreur/Dashboard',
        'finance' => 'Finance/Dashboard',
        'support' => 'Support/Dashboard',
    ];
    if (isset($role_dashboards[$role])) {
        redirect($role_dashboards[$role]);
    }
    
    // Statistiques des commandes
    $data['total_orders'] = $this->Model->count('commandes');
    $data['pending_orders'] = $this->Model->count('commandes', ['statut_commande' => 'en_attente']);
    $data['processing_orders'] = $this->Model->count('commandes', ['statut_commande' => 'confirme']);
    $data['delivered_orders'] = $this->Model->count('commandes', ['statut_commande' => 'livre']);
    
    // Calcul de la croissance (mois dernier vs mois actuel)
    $current_month_orders = $this->Model->count('commandes', ['MONTH(date_creation)' => date('m'), 'YEAR(date_creation)' => date('Y')]);
    $last_month_orders = $this->Model->count('commandes', ['MONTH(date_creation)' => date('m', strtotime('-1 month')), 'YEAR(date_creation)' => date('Y')]);
    $data['orders_growth'] = $last_month_orders > 0 ? (($current_month_orders - $last_month_orders) / $last_month_orders) * 100 : 0;
    
    // Statistiques utilisateurs
    $data['total_users'] = $this->Model->count('utilisateurs');
    $data['recent_users'] = $this->Model->read('utilisateurs', [], 'date_creation', 'DESC', 5);
    
    // Statistiques produits
    $data['total_products'] = $this->Model->count('produits', ['statut' => 'actif']);
    $data['products_stock_bas'] = $this->Model->count('produits', ['statut_stock' => 'stock_bas']);
    
    // Chiffre d'affaires
    $data['total_revenue'] = $this->db->select_sum('montant_total')->get('commandes')->row()->montant_total ?? 0;
    
    // Top catégories
    $data['top_categories'] = $this->db->select('c.nom_categorie, COUNT(p.id_produit) as nombre_produits')
        ->from('categories c')
        ->join('produits p', 'p.id_categorie = c.id_categorie', 'left')
        ->where('c.est_actif', 1)
        ->group_by('c.id_categorie')
        ->order_by('nombre_produits', 'DESC')
        ->limit(5)
        ->get()
        ->result_array();
    
    // Données pour les graphiques
    $data['months_labels'] = [];
    $data['sales_chart_data'] = [];
    
    for($i = 5; $i >= 0; $i--) {
        $month = date('m', strtotime("-$i month"));
        $year = date('Y', strtotime("-$i month"));
        $data['months_labels'][] = date('M Y', strtotime("-$i month"));
        
        $monthly_sales = $this->db->select_sum('montant_total')
            ->from('commandes')
            ->where('MONTH(date_creation)', $month)
            ->where('YEAR(date_creation)', $year)
            ->where('statut_commande', 'livre')
            ->get()
            ->row()->montant_total ?? 0;
        
        $data['sales_chart_data'][] = (float)$monthly_sales;
    }
    
    // Données pour le graphique des catégories
    $data['categories_chart_data'] = array_column($data['top_categories'], 'nombre_produits');
    $data['categories_chart_names'] = array_column($data['top_categories'], 'nom_categorie');
    
    // Dernières commandes
    $data['recent_orders'] = $this->db->select('c.*, u.prenom, u.nom, u.email')
        ->from('commandes c')
        ->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur')
        ->order_by('c.date_creation', 'DESC')
        ->limit(10)
        ->get()
        ->result_array();
    
    $this->load->view('Dashboard_View', $data);
}
}