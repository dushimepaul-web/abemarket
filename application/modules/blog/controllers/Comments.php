<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Comments extends MX_Controller {
    public function __construct() { parent::__construct(); $this->load->model('blog/Blog_model'); }
    public function index() { $data['comments'] = $this->Blog_model->getComments(); $this->load->view('blog/comments_list', $data); }
    public function approve($id) { $this->Blog_model->approveComment($id); redirect('admin/blog/comments'); }
    public function delete($id) { $this->Blog_model->deleteComment($id); redirect('admin/blog/comments'); }
}
