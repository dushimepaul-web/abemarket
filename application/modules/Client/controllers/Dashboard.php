<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
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
        $user = $this->session->userdata();
        $data['total_orders'] = $this->Model->count('commandes', ['id_utilisateur' => $user['id_utilisateur']]);
        $data['cart_count'] = $this->Model->count('paniers', ['id_utilisateur' => $user['id_utilisateur']]);
        $data['wishlist_count'] = $this->Model->count('liste_souhaits', ['id_utilisateur' => $user['id_utilisateur']]);
        $data['address_count'] = $this->Model->count('adresses', ['id_utilisateur' => $user['id_utilisateur']]);

        $this->load->view('Dashboard_view', $data);
    }
}
