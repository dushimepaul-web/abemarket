<?php defined('BASEPATH') or exit('No direct script access allowed');

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
        $vendeur_id = $this->session->userdata('id_vendeur');
        if (!$vendeur_id) {
            show_error('Aucune boutique trouvée', 404);
        }

        $data['user'] = $this->session->userdata();
        $data['total_products'] = $this->Model->count('produits', ['id_vendeur' => $vendeur_id]);
        $data['total_orders'] = $this->db->from('commandes c')
            ->join('articles_commande ac', 'ac.id_commande = c.id_commande')
            ->join('produits p', 'p.id_produit = ac.id_produit')
            ->where('p.id_vendeur', $vendeur_id)
            ->count_all_results();
        $data['total_revenue'] = $this->db->select_sum('c.montant_total')
            ->from('commandes c')
            ->join('articles_commande ac', 'ac.id_commande = c.id_commande')
            ->join('produits p', 'p.id_produit = ac.id_produit')
            ->where('p.id_vendeur', $vendeur_id)
            ->where('c.statut_commande', 'livre')
            ->get()
            ->row()->montant_total ?? 0;

        $this->load->view('Dashboard_view', $data);
    }
}
