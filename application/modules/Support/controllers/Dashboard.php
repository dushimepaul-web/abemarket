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
        $data['open_disputes'] = $this->Model->count('litiges_commandes', ['statut' => 'ouvert']);
        $data['unread_messages'] = 0;
        $data['pending_returns'] = $this->Model->count('retours_remboursements', ['statut' => 'demande']);

        $this->load->view('Dashboard_view', $data);
    }
}
