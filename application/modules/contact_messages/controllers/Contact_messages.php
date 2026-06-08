<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contact_messages extends MX_Controller {
    public function __construct() { parent::__construct(); $this->load->model('Model'); }
    public function index() { $data['messages'] = $this->Model->read('contact_messages', [], 'date_creation', 'DESC'); $this->load->view('contact_messages/list', $data); }
    public function view($id) { $data['message'] = $this->Model->readOne('contact_messages', ['id' => $id]); $this->load->view('contact_messages/detail', $data); }
    public function reply($id) { $this->load->view('contact_messages/reply', ['id' => $id]); }
    public function delete($id) { $this->Model->delete('contact_messages', ['id' => $id]); redirect('admin/contact/messages'); }
    public function mark_read($id) { $this->Model->update('contact_messages', ['id' => $id], ['est_lu' => 1]); redirect('admin/contact/messages'); }
}
