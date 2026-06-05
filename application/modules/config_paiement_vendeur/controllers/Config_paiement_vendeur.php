<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config_paiement_vendeur extends MY_Controller {

    public function __construct() {

        parent::__construct();

        $this->load->model('Config_paiement_vendeur_model');
        $this->load->model('Vendeur_model');

        $this->load->library('form_validation');

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    /**
     * LISTE
     */
    public function index() {

        $data['title'] = "Configuration paiement vendeur";

        $data['configs'] =
            $this->Config_paiement_vendeur_model->get_all();

        $this->load->view(
            'config_paiement_vendeur_list',
            $data
        );
    }

    /**
     * FORM ADD
     */
    public function ajouter() {

        $data['title'] =
            "Ajouter configuration paiement";

        $data['vendeurs'] =
            $this->Vendeur_model->get_all();

        $this->load->view(
            'config_paiement_vendeur_add',
            $data
        );
    }

    /**
     * SAVE
     */
    public function save() {

        $this->form_validation->set_rules(
            'id_vendeur',
            'Vendeur',
            'required'
        );

        $this->form_validation->set_rules(
            'methode_principale',
            'Méthode paiement',
            'required'
        );

        if ($this->form_validation->run() == FALSE) {

            $this->ajouter();

        } else {

            $data = [

                'id_vendeur' =>
                    $this->input->post('id_vendeur'),

                'methode_principale' =>
                    $this->input->post('methode_principale'),

                'operateur_mobile' =>
                    $this->input->post('operateur_mobile'),

                'numero_mobile_money' =>
                    $this->input->post('numero_mobile_money'),

                'nom_abonne_mobile' =>
                    $this->input->post('nom_abonne_mobile'),

                'nom_titulaire' =>
                    $this->input->post('nom_titulaire'),

                'numero_compte' =>
                    $this->input->post('numero_compte'),

                'nom_banque' =>
                    $this->input->post('nom_banque'),

                'est_verifie' => 0,

                'est_actif' => 1

            ];

            $this->Config_paiement_vendeur_model
                 ->insert($data);

            redirect('Config_paiement_vendeur');
        }
    }

    /**
     * DETAIL
     */
    public function detail($id) {

        $data['config'] =
            $this->Config_paiement_vendeur_model
                 ->get_by_id($id);

        if (!$data['config']) {
            show_404();
        }

        $this->load->view(
            'config_paiement_vendeur_detail',
            $data
        );
    }

    /**
     * EDIT
     */
    public function edit($id) {

        $data['config'] =
            $this->Config_paiement_vendeur_model
                 ->get_by_id($id);

        $data['vendeurs'] =
            $this->Vendeur_model->get_all();

        if (!$data['config']) {
            show_404();
        }

        $this->load->view(
            'config_paiement_vendeur_edit',
            $data
        );
    }

    /**
     * UPDATE
     */
    public function update($id) {

        $data = [

            'id_vendeur' =>
                $this->input->post('id_vendeur'),

            'methode_principale' =>
                $this->input->post('methode_principale'),

            'operateur_mobile' =>
                $this->input->post('operateur_mobile'),

            'numero_mobile_money' =>
                $this->input->post('numero_mobile_money'),

            'nom_abonne_mobile' =>
                $this->input->post('nom_abonne_mobile'),

            'nom_titulaire' =>
                $this->input->post('nom_titulaire'),

            'numero_compte' =>
                $this->input->post('numero_compte'),

            'nom_banque' =>
                $this->input->post('nom_banque')

        ];

        $this->Config_paiement_vendeur_model
             ->update($id,$data);

        redirect('Config_paiement_vendeur');
    }

    /**
     * DELETE
     */
    public function delete($id) {

        $this->Config_paiement_vendeur_model
             ->delete($id);

        redirect('Config_paiement_vendeur');
    }

    /**
     * ACTIVER / DESACTIVER
     */
    public function toggle($id) {

        $this->Config_paiement_vendeur_model
             ->toggle_status($id);

        redirect('Config_paiement_vendeur');
    }

    /**
     * VERIFIER CONFIG
     */
    public function verifier($id) {

        $this->Config_paiement_vendeur_model
             ->verifier($id);

        redirect('Config_paiement_vendeur');
    }

}