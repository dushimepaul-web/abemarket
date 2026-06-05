<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->database();
        $this->load->model('Faq_model');
        $this->load->library('pagination');
        
      
    }

    public function index() {
        // Paramètres de pagination
        $page = (int)$this->input->get('page') ?: 1;
        $perPage = 15;
        $offset = ($page - 1) * $perPage;
        
        // Filtres
        $filters = [
            'search' => $this->input->get('search'),
            'est_actif' => $this->input->get('est_actif'),
            'categorie' => $this->input->get('categorie')
        ];
        
        // Récupérer les données
        $data['faqs'] = $this->Faq_model->getAll($perPage, $offset, $filters);
        $data['total_faqs'] = $this->Faq_model->countAll($filters);
        $data['total_pages'] = ceil($data['total_faqs'] / $perPage);
        $data['current_page'] = $page;
        $data['filters'] = $filters;
        
        // Statistiques
        $data['stats'] = $this->Faq_model->getStats();
        
        
        $data['title'] = 'Gestion des FAQ';
        ;
        $this->load->view('faq/list', $data);
    }

    public function add() {
        $data['title'] = 'Ajouter une FAQ';
        
        $this->form_validation->set_rules('question', 'Question', 'required|min_length[5]');
        $this->form_validation->set_rules('reponse', 'Réponse', 'required|min_length[10]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('faq/form', $data);
            
            return;
        }
        
        $faq_data = [
            'id_categorie' => $this->input->post('id_categorie') ?: null,
            'question' => $this->input->post('question'),
            'reponse' => $this->input->post('reponse'),
            'ordre_affichage' => (int)$this->input->post('ordre_affichage'),
            'est_actif' => (int)$this->input->post('est_actif'),
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        $result = $this->Faq_model->add($faq_data);
        
        if ($result) {
            $this->session->set_flashdata('success', 'FAQ ajoutée avec succès !');
            redirect('admin/faq');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'ajout.');
            redirect('admin/faq/add');
        }
    }

    public function edit($id) {
        $data['faq'] = $this->Faq_model->getById($id);
        
        if (!$data['faq']) {
            show_404();
            return;
        }
        
        $data['title'] = 'Modifier la FAQ';

        
        $this->form_validation->set_rules('question', 'Question', 'required|min_length[5]');
        $this->form_validation->set_rules('reponse', 'Réponse', 'required|min_length[10]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('faq/form', $data);
            
            return;
        }
        
        $faq_data = [
            'id_categorie' => $this->input->post('id_categorie') ?: null,
            'question' => $this->input->post('question'),
            'reponse' => $this->input->post('reponse'),
            'ordre_affichage' => (int)$this->input->post('ordre_affichage'),
            'est_actif' => (int)$this->input->post('est_actif')
        ];
        
        $result = $this->Faq_model->update($id, $faq_data);
        
        if ($result) {
            $this->session->set_flashdata('success', 'FAQ modifiée avec succès !');
            redirect('admin/faq');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la modification.');
            redirect('faq/edit/' . $id);
        }
    }

    public function delete($id) {
        $faq = $this->Faq_model->getById($id);
        
        if (!$faq) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'FAQ non trouvée']));
            return;
        }
        
        $result = $this->Faq_model->delete($id);
        
        $this->output->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => $result,
                'message' => $result ? 'FAQ supprimée avec succès' : 'Erreur lors de la suppression'
            ]));
    }

    public function toggle($id) {
        $faq = $this->Faq_model->getById($id);
        
        if (!$faq) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'FAQ non trouvée']));
            return;
        }
        
        $new_status = $faq->est_actif == 1 ? 0 : 1;
        $result = $this->Faq_model->toggle($id, $new_status);
        
        $this->output->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => $result,
                'new_status' => $new_status,
                'message' => $result ? 'Statut modifié' : 'Erreur'
            ]));
    }

    public function updateOrder() {
        $orders = $this->input->post('orders');
        
        if (empty($orders)) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Aucune donnée']));
            return;
        }
        
        $success = true;
        foreach ($orders as $order) {
            $result = $this->Faq_model->updateOrder($order['id'], $order['ordre']);
            if (!$result) $success = false;
        }
        
        $this->output->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => $success,
                'message' => $success ? 'Ordre mis à jour' : 'Erreur lors de la mise à jour'
            ]));
    }


}