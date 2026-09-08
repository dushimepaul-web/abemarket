<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('logged_in') !== TRUE) {
            redirect('Admin');
        }
    }

    public function index() {
        $data['permissions'] = $this->Model->read('permissions', [], 'name', 'ASC');
        $this->render_page('Utilisateurs/permissions/list', $data);
    }

    public function add() {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'module' => $this->input->post('module'),
                'est_actif' => 1
            ];
            $this->Model->create('permissions', $data);
            $this->session->set_flashdata('success', 'Permission ajoutée avec succès');
            redirect('Permissions');
        }
        $this->render_page('Utilisateurs/permissions/add', []);
    }

    public function edit($id) {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'module' => $this->input->post('module')
            ];
            $this->Model->update('permissions', $id, $data);
            $this->session->set_flashdata('success', 'Permission modifiée avec succès');
            redirect('Permissions');
        }
        $data['permission'] = $this->Model->readOne('permissions', ['id' => $id]);
        $this->render_page('Utilisateurs/permissions/edit', $data);
    }

    public function delete($id) {
        $this->Model->delete('permissions', ['id' => $id]);
        $this->session->set_flashdata('success', 'Permission supprimée avec succès');
        redirect('Permissions');
    }
}
