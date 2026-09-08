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
        $data['ongoing_deliveries'] = $this->Model->count('commandes', ['id_livreur' => $user['id_utilisateur'], 'statut_commande' => 'expedie']);
        $data['completed_deliveries'] = $this->Model->count('commandes', ['id_livreur' => $user['id_utilisateur'], 'statut_commande' => 'livre']);
        $data['notifications'] = $this->Model->count('notifications', ['id_utilisateur' => $user['id_utilisateur'], 'lu' => 0]);

        $this->load->view('Dashboard_view', $data);
    }
}
