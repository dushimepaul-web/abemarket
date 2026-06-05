<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('file');
        $this->load->database();
        $this->load->model('Team_model');
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
            'est_actif' => $this->input->get('est_actif')
        ];
        
        // Récupérer les données
        $data['members'] = $this->Team_model->getAll($perPage, $offset, $filters);
        $data['total_members'] = $this->Team_model->countAll($filters);
        $data['total_pages'] = ceil($data['total_members'] / $perPage);
        $data['current_page'] = $page;
        $data['filters'] = $filters;
        
        // Statistiques
        $data['stats'] = $this->Team_model->getStats();
        
        $data['title'] = 'Gestion de l\'équipe';
        
      
        $this->load->view('team/list', $data);
    
    }

    public function add() {
        $data['title'] = 'Ajouter un membre';
        
        $this->form_validation->set_rules('nom', 'Nom', 'required|min_length[2]');
        $this->form_validation->set_rules('prenom', 'Prénom', 'required|min_length[2]');
        $this->form_validation->set_rules('poste', 'Poste', 'required|min_length[2]');
        
        if ($this->form_validation->run() == FALSE) {
          
            $this->load->view('team/form', $data);

            return;
        }
        
        $member_data = [
            'nom' => $this->input->post('nom'),
            'prenom' => $this->input->post('prenom'),
            'poste' => $this->input->post('poste'),
            'bio' => $this->input->post('bio'),
            'photo_url' => $this->input->post('photo_url'),
            'facebook_url' => $this->input->post('facebook_url'),
            'instagram_url' => $this->input->post('instagram_url'),
            'linkedin_url' => $this->input->post('linkedin_url'),
            'twitter_url' => $this->input->post('twitter_url'),
            'ordre_affichage' => (int)$this->input->post('ordre_affichage'),
            'est_actif' => (int)$this->input->post('est_actif')
        ];
        
        $result = $this->Team_model->add($member_data);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Membre ajouté avec succès !');
            redirect('admin/team');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'ajout.');
            redirect('admin/team/add');
        }
    }

    public function edit($id) {
        $data['member'] = $this->Team_model->getById($id);
        
        if (!$data['member']) {
            show_404();
            return;
        }
        
        $data['title'] = 'Modifier : ' . $data['member']->prenom . ' ' . $data['member']->nom;
        
        $this->form_validation->set_rules('nom', 'Nom', 'required|min_length[2]');
        $this->form_validation->set_rules('prenom', 'Prénom', 'required|min_length[2]');
        $this->form_validation->set_rules('poste', 'Poste', 'required|min_length[2]');
        
        if ($this->form_validation->run() == FALSE) {
          
            $this->load->view('team/form', $data);

            return;
        }
        
        $member_data = [
            'nom' => $this->input->post('nom'),
            'prenom' => $this->input->post('prenom'),
            'poste' => $this->input->post('poste'),
            'bio' => $this->input->post('bio'),
            'photo_url' => $this->input->post('photo_url'),
            'facebook_url' => $this->input->post('facebook_url'),
            'instagram_url' => $this->input->post('instagram_url'),
            'linkedin_url' => $this->input->post('linkedin_url'),
            'twitter_url' => $this->input->post('twitter_url'),
            'ordre_affichage' => (int)$this->input->post('ordre_affichage'),
            'est_actif' => (int)$this->input->post('est_actif')
        ];
        
        $result = $this->Team_model->update($id, $member_data);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Membre modifié avec succès !');
            redirect('admin/team');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la modification.');
            redirect('admin/team/edit/' . $id);
        }
    }

    public function delete($id) {
        $member = $this->Team_model->getById($id);
        
        if (!$member) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Membre non trouvé']));
            return;
        }
        
        $result = $this->Team_model->delete($id);
        
        $this->output->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => $result,
                'message' => $result ? 'Membre supprimé avec succès' : 'Erreur lors de la suppression'
            ]));
    }

    public function toggle($id) {
        $member = $this->Team_model->getById($id);
        
        if (!$member) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Membre non trouvé']));
            return;
        }
        
        $new_status = $member->est_actif == 1 ? 0 : 1;
        $result = $this->Team_model->toggle($id, $new_status);
        
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
            $result = $this->Team_model->updateOrder($order['id'], $order['ordre']);
            if (!$result) $success = false;
        }
        
        $this->output->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => $success,
                'message' => $success ? 'Ordre mis à jour' : 'Erreur lors de la mise à jour'
            ]));
    }
}