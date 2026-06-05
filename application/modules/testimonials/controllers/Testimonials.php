<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testimonials extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->database();
        $this->load->model('Testimonials_model');
        
      
    }

    public function index() {
        $page = (int)$this->input->get('page') ?: 1;
        $perPage = 15;
        $offset = ($page - 1) * $perPage;
        
        $filters = [
            'search' => $this->input->get('search'),
            'est_approuve' => $this->input->get('est_approuve'),
            'note' => $this->input->get('note')
        ];
        
        $data['testimonials'] = $this->Testimonials_model->getAll($perPage, $offset, $filters);
        $data['total_testimonials'] = $this->Testimonials_model->countAll($filters);
        $data['total_pages'] = ceil($data['total_testimonials'] / $perPage);
        $data['current_page'] = $page;
        $data['filters'] = $filters;
        $data['stats'] = $this->Testimonials_model->getStats();
        $data['title'] = 'Gestion des témoignages';
    
        $this->load->view('testimonials/list', $data);
       
    }

    public function add() {
        $data['title'] = 'Ajouter un témoignage';
        
        $this->form_validation->set_rules('nom', 'Nom', 'required|min_length[2]');
        $this->form_validation->set_rules('prenom', 'Prénom', 'required|min_length[2]');
        $this->form_validation->set_rules('message', 'Message', 'required|min_length[10]');
        $this->form_validation->set_rules('note', 'Note', 'required|integer|between[1,5]');
        
        if ($this->form_validation->run() == FALSE) {
            $data['errors'] = validation_errors();
            $this->load->view('testimonials/form', $data);
           
            return;
        }
        
        $testimonial_data = [
            'nom' => $this->input->post('nom', TRUE),
            'prenom' => $this->input->post('prenom', TRUE),
            'poste' => $this->input->post('poste', TRUE),
            'message' => $this->input->post('message', TRUE),
            'photo_url' => $this->input->post('photo_url', TRUE),
            'note' => (int)$this->input->post('note', TRUE),
            'est_approuve' => (int)$this->input->post('est_approuve', TRUE),
            'ordre_affichage' => (int)$this->input->post('ordre_affichage', TRUE)
        ];
        
        $result = $this->Testimonials_model->add($testimonial_data);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Témoignage ajouté avec succès !');
            redirect('admin/testimonials');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'ajout.');
            redirect('testimonials/add');
        }
    }

    public function edit($id) {
        $data['testimonial'] = $this->Testimonials_model->getById($id);
        
        if (!$data['testimonial']) {
            show_404();
            return;
        }
        
        $data['title'] = 'Modifier le témoignage';
        
        $this->form_validation->set_rules('nom', 'Nom', 'required|min_length[2]');
        $this->form_validation->set_rules('prenom', 'Prénom', 'required|min_length[2]');
        $this->form_validation->set_rules('message', 'Message', 'required|min_length[10]');
        $this->form_validation->set_rules('note', 'Note', 'required|integer|between[1,5]');
        
        if ($this->form_validation->run() == FALSE) {
            $data['errors'] = validation_errors();
            $this->load->view('testimonials/form', $data);
           
            return;
        }
        
        $testimonial_data = [
            'nom' => $this->input->post('nom', TRUE),
            'prenom' => $this->input->post('prenom', TRUE),
            'poste' => $this->input->post('poste', TRUE),
            'message' => $this->input->post('message', TRUE),
            'photo_url' => $this->input->post('photo_url', TRUE),
            'note' => (int)$this->input->post('note', TRUE),
            'est_approuve' => (int)$this->input->post('est_approuve', TRUE),
            'ordre_affichage' => (int)$this->input->post('ordre_affichage', TRUE)
        ];
        
        $result = $this->Testimonials_model->update($id, $testimonial_data);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Témoignage modifié avec succès !');
            redirect('testimonials');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la modification.');
            redirect('testimonials/edit' . $id);
        }
    }

    public function delete($id) {
        $result = $this->Testimonials_model->delete($id);
        
        $this->output->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => $result,
                'message' => $result ? 'Supprimé avec succès' : 'Erreur lors de la suppression'
            ]));
    }

    public function approve($id) {
        $testimonial = $this->Testimonials_model->getById($id);
        
        if (!$testimonial) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Témoignage non trouvé']));
            return;
        }
        
        $new_status = $testimonial->est_approuve == 1 ? 0 : 1;
        $result = $this->Testimonials_model->approve($id, $new_status);
        
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
            $result = $this->Testimonials_model->updateOrder($order['id'], $order['ordre']);
            if (!$result) $success = false;
        }
        
        $this->output->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => $success,
                'message' => $success ? 'Ordre mis à jour' : 'Erreur'
            ]));
    }
}