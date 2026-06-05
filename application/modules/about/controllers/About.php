<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('About_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        

        }
   

    /**
     * Liste des contenus "À propos"
     */
    public function index() {
        $data['contents'] = $this->About_model->getAll();
        $data['title'] = 'Gestion de la page "À propos"';
        
        $this->load->view('list', $data);
    }

    /**
     * Formulaire d'édition d'un contenu
     */
    public function edit($id) {
        $data['content'] = $this->About_model->getById($id);
        
        if (!$data['content']) {
            show_404();
            return;
        }
        
        $data['title'] = 'Modifier : ' . ($data['content']['title'] ?? $data['content']['section_key']);
    
        $this->load->view('about/form', $data);
        
    }

    /**
     * Mise à jour d'un contenu
     */
    public function update($id) {
        $content = $this->About_model->getById($id);
        
        if (!$content) {
            show_404();
            return;
        }
        
        // Règles de validation
        $this->form_validation->set_rules('title', 'Titre', 'trim');
        $this->form_validation->set_rules('content', 'Contenu', 'trim');
        $this->form_validation->set_rules('image_url', 'Image URL', 'trim');
        $this->form_validation->set_rules('ordre_affichage', 'Ordre d\'affichage', 'integer');
        $this->form_validation->set_rules('est_actif', 'Actif', 'integer');
        
        if ($this->form_validation->run() == FALSE) {
            $data['content'] = $content;
            $data['title'] = 'Modifier : ' . ($content['title'] ?? $content['section_key']);
            
            $this->load->view('admin/layouts/header', $data);
            $this->load->view('admin/about/form', $data);
            $this->load->view('admin/layouts/footer');
            return;
        }
        
        // Préparer les données de mise à jour
        $update_data = [];
        
        if ($this->input->post('title') !== null) {
            $update_data['title'] = $this->input->post('title');
        }
        
        if ($this->input->post('content') !== null) {
            $update_data['content'] = $this->input->post('content');
        }
        
        if ($this->input->post('image_url') !== null) {
            $update_data['image_url'] = $this->input->post('image_url');
        }
        
        if ($this->input->post('ordre_affichage') !== null) {
            $update_data['ordre_affichage'] = (int)$this->input->post('ordre_affichage');
        }
        
        if ($this->input->post('est_actif') !== null) {
            $update_data['est_actif'] = (int)$this->input->post('est_actif');
        }
        
        $result = $this->About_model->update($id, $update_data);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Contenu mis à jour avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la mise à jour.');
        }
        
        redirect('admin/about');
    }

    /**
     * Activer/Désactiver un contenu (AJAX)
     */
    public function toggle($id) {
        $content = $this->About_model->getById($id);
        
        if (!$content) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Contenu non trouvé']));
            return;
        }
        
        $new_status = $content['est_actif'] == 1 ? 0 : 1;
        $result = $this->About_model->toggleStatus($id, $new_status);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => $result,
                'new_status' => $new_status,
                'message' => $result ? 'Statut modifié' : 'Erreur'
            ]));
    }

    /**
     * Mettre à jour l'ordre d'affichage (AJAX)
     */
    public function updateOrder() {
        $orders = $this->input->post('orders');
        
        if (empty($orders)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Aucune donnée']));
            return;
        }
        
        $success = true;
        foreach ($orders as $order) {
            $result = $this->About_model->updateOrder($order['id'], $order['ordre']);
            if (!$result) $success = false;
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => $success,
                'message' => $success ? 'Ordre mis à jour' : 'Erreur lors de la mise à jour'
            ]));
    }
}