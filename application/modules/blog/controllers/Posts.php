<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->database();
        $this->load->model('Blog_model');
        
      
    }

    public function index() {
        $page = (int)$this->input->get('page') ?: 1;
        $perPage = 15;
        $offset = ($page - 1) * $perPage;
        $status = $this->input->get('status');
        
        $data['posts'] = $this->Blog_model->getAllPosts($perPage, $offset, $status);
        $data['total_posts'] = $this->Blog_model->countAllPosts($status);
        $data['total_pages'] = ceil($data['total_posts'] / $perPage);
        $data['current_page'] = $page;
        $data['current_status'] = $status;
        $data['title'] = 'Gestion des articles de blog';
        
        
        $this->load->view('blog/list', $data);
  
    }

    public function add() {
        $data['title'] = 'Ajouter un article';
        $data['categories'] = $this->Blog_model->getAllCategories();
        
        $this->form_validation->set_rules('title', 'Titre', 'required|min_length[5]');
        $this->form_validation->set_rules('content', 'Contenu', 'required');
        $this->form_validation->set_rules('id_categorie', 'Catégorie', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('blog/form', $data);
          
            return;
        }
        
        $slug = $this->Blog_model->generateSlug($this->input->post('title'));
        
        $post_data = [
            'id_utilisateur' => $this->session->userdata('id_utilisateur'),
            'id_categorie' => $this->input->post('id_categorie'),
            'title' => $this->input->post('title'),
            'slug' => $slug,
            'content' => $this->input->post('content'),
            'excerpt' => $this->input->post('excerpt'),
            'featured_image' => $this->input->post('featured_image'),
            'tags' => $this->input->post('tags'),
            'allow_comments' => (int)$this->input->post('allow_comments'),
            'status' => $this->input->post('status'),
            'date_publication' => $this->input->post('date_publication') ?: date('Y-m-d H:i:s'),
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        $result = $this->Blog_model->addPost($post_data);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Article ajouté avec succès !');
            redirect('admin/blog/posts');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'ajout.');
            redirect('admin/blog/posts/add');
        }
    }

    public function edit($id) {
        $data['post'] = $this->Blog_model->getPostById($id);
        
        if (!$data['post']) {
            show_404();
            return;
        }
        
        $data['title'] = 'Modifier : ' . $data['post']['title'];
        $data['categories'] = $this->Blog_model->getAllCategories();
        
        $this->form_validation->set_rules('title', 'Titre', 'required|min_length[5]');
        $this->form_validation->set_rules('content', 'Contenu', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('blog/form', $data);
      
            return;
        }
        
        $post_data = [
            'id_categorie' => $this->input->post('id_categorie'),
            'title' => $this->input->post('title'),
            'content' => $this->input->post('content'),
            'excerpt' => $this->input->post('excerpt'),
            'featured_image' => $this->input->post('featured_image'),
            'tags' => $this->input->post('tags'),
            'allow_comments' => (int)$this->input->post('allow_comments'),
            'status' => $this->input->post('status'),
            'date_modification' => date('Y-m-d H:i:s')
        ];
        
        if ($this->input->post('date_publication')) {
            $post_data['date_publication'] = $this->input->post('date_publication');
        }
        
        $result = $this->Blog_model->updatePost($id, $post_data);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Article modifié avec succès !');
            redirect('admin/blog/posts');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la modification.');
            redirect('admin/blog/posts/edit/' . $id);
        }
    }

    public function delete($id) {
        $post = $this->Blog_model->getPostById($id);
        
        if (!$post) {
            show_404();
            return;
        }
        
        $result = $this->Blog_model->deletePost($id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Article supprimé avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la suppression.');
        }
        
        redirect('admin/blog/posts');
    }

    public function toggle($id) {
        $post = $this->Blog_model->getPostById($id);
        
        if (!$post) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Article non trouvé']));
            return;
        }
        
        $new_status = $post['status'] == 'publie' ? 'brouillon' : 'publie';
        $result = $this->Blog_model->togglePostStatus($id, $new_status);
        
        $this->output->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => $result,
                'new_status' => $new_status,
                'message' => $result ? 'Statut modifié' : 'Erreur'
            ]));
    }
}