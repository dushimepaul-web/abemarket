<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Model');
    }

    public function get_transporteur_id() {
        $user_id = $this->input->get('user_id');
        if (!$user_id) {
            echo json_encode(['success' => false, 'message' => 'user_id required']);
            return;
        }
        $transporteur = $this->Model->getTransporteurByUserId($user_id);
        echo json_encode(['success' => !empty($transporteur), 'data' => $transporteur]);
    }
}
