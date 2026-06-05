<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CodesOtp extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('CodesOtp_model');
        $this->load->library('pagination');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Vérifier que l'utilisateur est admin
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
    }

    private function is_admin() {
        $user_id = $this->session->userdata('id_utilisateur');
        $this->db->select('up.id_profil')
                 ->from('utilisateur_profils up')
                 ->where('up.id_utilisateur', $user_id)
                 ->where_in('up.id_profil', [1, 2, 3]);
        return $this->db->get()->num_rows() > 0;
    }

    /**
     * Liste des codes OTP
     */
    public function index() {
        $data['title'] = 'Gestion des codes OTP';
        
        $filters = [
            'type_otp' => $this->input->get('type_otp'),
            'utilise' => $this->input->get('utilise'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin'),
            'search' => $this->input->get('search')
        ];
        
        $config['base_url'] = base_url('codes-otp/index');
        $config['total_rows'] = $this->CodesOtp_model->count_all($filters);
        $config['per_page'] = 30;
        $config['uri_segment'] = 3;
        $config['full_tag_open'] = '<ul class="pagination justify-content-end mb-0">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['attributes'] = ['class' => 'page-link'];
        $config['reuse_query_string'] = true;
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['codes'] = $this->CodesOtp_model->get_all(
            $config['per_page'],
            $page,
            $filters
        );
        
        $data['stats'] = $this->CodesOtp_model->get_stats($filters);
        $data['filters'] = $filters;
        
        $this->load->view('codes_otp_list', $data);
    }

    /**
     * Détail d'un code OTP
     */
    public function detail($id) {
        $data['code'] = $this->CodesOtp_model->get_by_id($id);
        
        if (!$data['code']) {
            show_404();
        }
        
        $data['title'] = 'Détails du code OTP #' . $id;
        
        $this->load->view('codes_otp_detail', $data);
    }

    /**
     * Générer un code OTP (Admin)
     */
    public function generate() {
        $data['title'] = 'Générer un code OTP';
        $data['utilisateurs'] = $this->db->get('utilisateurs')->result();
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $id_utilisateur = $this->input->post('id_utilisateur');
            $type_otp = $this->input->post('type_otp');
            $telephone = $this->input->post('telephone');
            $email = $this->input->post('email');
            
            if (empty($id_utilisateur)) {
                $this->session->set_flashdata('error', 'Veuillez sélectionner un utilisateur');
                redirect('codes-otp/generate');
                return;
            }
            
            $code = $this->CodesOtp_model->generer_code($id_utilisateur, $type_otp, $telephone, $email);
            
            if ($code) {
                $this->session->set_flashdata('success', 'Code OTP généré: ' . $code['code']);
                redirect('codes-otp');
            } else {
                $this->session->set_flashdata('error', 'Erreur lors de la génération');
            }
        }
        
        $this->load->view('codes_otp_generate', $data);
    }

    /**
     * Supprimer un code OTP
     */
    public function delete($id) {
        $this->output->set_content_type('application/json');
        
        $deleted = $this->CodesOtp_model->delete($id);
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Code OTP supprimé' : 'Erreur'
        ]);
    }

    /**
     * Supprimer les codes expirés
     */
    public function nettoyer() {
        $this->output->set_content_type('application/json');
        
        $deleted = $this->CodesOtp_model->nettoyer_expires();
        
        echo json_encode([
            'success' => true,
            'message' => $deleted . ' code(s) expiré(s) supprimé(s)'
        ]);
    }

    /**
     * Exporter les codes OTP
     */
    public function exporter() {
        $filters = [
            'type_otp' => $this->input->get('type_otp'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin')
        ];
        
        $codes = $this->CodesOtp_model->get_all(null, null, $filters);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=codes_otp_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Utilisateur', 'Code', 'Type', 'Téléphone', 'Email', 'Tentatives', 'Statut', 'Expiration', 'Date création']);
        
        foreach ($codes as $c) {
            fputcsv($output, [
                $c->id_otp,
                $c->utilisateur_nom,
                $c->code,
                $c->type_otp,
                $c->telephone ?? '-',
                $c->email ?? '-',
                $c->tentatives,
                $c->utilise ? 'Utilisé' : 'Non utilisé',
                date('d/m/Y H:i', strtotime($c->date_expiration)),
                date('d/m/Y H:i', strtotime($c->date_creation))
            ]);
        }
        
        fclose($output);
    }
}
?>