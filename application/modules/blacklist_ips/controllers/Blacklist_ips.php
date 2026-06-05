<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blacklist_ips extends MY_Controller {

    public function __construct() {

        parent::__construct();

        $this->load->model('Blacklist_ips_model');
        $this->load->library('form_validation');

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    /**
     * Liste des IP blacklistées
     */
    public function index() {

        $data['title'] = "Blacklist des adresses IP";

        $data['ips'] =
            $this->Blacklist_ips_model->get_all();

        $this->load->view(
            'blacklist_ips_list',
            $data
        );
    }

    /**
     * Ajouter IP
     */
    public function ajouter() {

        $data['title'] = "Ajouter IP blacklist";

        $this->load->view(
            'blacklist_ips_add',
            $data
        );
    }

    /**
     * Enregistrer
     */
    public function save()
{

$this->form_validation->set_rules(
    'adresse_ip',
    'Adresse IP',
    'required|valid_ip|is_unique[blacklist_ips.adresse_ip]'
);

if ($this->form_validation->run() == FALSE) {

    $this->ajouter();

} else {

$data = [

'adresse_ip' => $this->input->post('adresse_ip'),

'raison' => $this->input->post('raison'),

'date_fin' => $this->input->post('date_fin'),

'cree_par' => $this->session->userdata('id_utilisateur')

];

$this->Blacklist_ips_model->insert($data);

$this->session->set_flashdata(
    'success',
    'IP ajoutée avec succès'
);

redirect('Blacklist_ips');

}

}

    /**
     * Détail
     */
    public function detail($id = null) {

        if (!$id) show_404();

        $data['ip'] =
            $this->Blacklist_ips_model
                 ->get_by_id($id);

        if (!$data['ip'])
            show_404();

        $this->load->view(
            'blacklist_ips_detail',
            $data
        );
    }

    /**
     * Modifier
     */
    public function edit($id) {

        $data['ip'] =
            $this->Blacklist_ips_model
                 ->get_by_id($id);

        if (!$data['ip'])
            show_404();

        $data['title'] =
            "Modifier IP blacklist";

        $this->load->view(
            'blacklist_ips_edit',
            $data
        );
    }

    /**
     * Update
     */
    public function update($id) {

        $this->form_validation
             ->set_rules(
                 'adresse_ip',
                 'Adresse IP',
                 'required|valid_ip'
             );

        if ($this->form_validation->run() == FALSE) {

            $this->edit($id);
            return;
        }

        $data = [

            'adresse_ip' =>
                $this->input->post('adresse_ip'),

            'raison' =>
                $this->input->post('raison'),

            'date_fin' =>
                $this->input->post('date_fin')

        ];

        $this->Blacklist_ips_model
             ->update($id,$data);

        redirect('Blacklist_ips');
    }

    /**
     * Supprimer
     */
    public function delete($id) {

        $this->Blacklist_ips_model
             ->delete($id);

        redirect('Blacklist_ips');
    }

    /**
     * Vérifier si IP bloquée
     */
    public function check_ip($ip) {

        return $this->Blacklist_ips_model
                    ->is_blocked($ip);
    }

}