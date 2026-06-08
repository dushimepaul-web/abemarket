<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Categories extends MX_Controller {
    public function __construct() { parent::__construct(); $this->load->model('blog/Blog_model'); }
    public function index() { $data['categories'] = $this->Blog_model->getCategories(); $this->load->view('blog/categories_list', $data); }
    public function add() { $this->load->view('blog/categories_form'); }
    public function edit($id) { $data['category'] = $this->Blog_model->getCategory($id); $this->load->view('blog/categories_form', $data); }
    public function delete($id) { $this->Blog_model->deleteCategory($id); redirect('admin/blog/categories'); }
    public function toggle($id) { $this->Blog_model->toggleCategory($id); redirect('admin/blog/categories'); }
}
