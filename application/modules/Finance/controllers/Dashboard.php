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
        $data['total_transactions'] = $this->Model->count('transactions_paiement');
        $data['pending_payments'] = $this->Model->count('paiements_vendeurs', ['statut' => 'en_attente']);
        $data['total_transfers'] = $this->Model->count('paiements_vendeurs', ['statut' => 'effectue']);

        $this->load->view('Dashboard_view', $data);
    }
}
