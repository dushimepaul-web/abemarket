<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LogsAudit extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('LogsAudit_model');
        $this->load->library('pagination');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Seul l'admin et le super_admin peuvent accéder aux logs
        if (!$this->is_admin() && !$this->is_super_admin()) {
            show_error('Accès non autorisé', 403);
        }
    }

    private function is_admin() {
        $user_id = $this->session->userdata('id_utilisateur');
        $this->db->select('up.id_profil')
                 ->from('utilisateur_profils up')
                 ->where('up.id_utilisateur', $user_id)
                 ->where_in('up.id_profil', [1, 2]);
        return $this->db->get()->num_rows() > 0;
    }

    private function is_super_admin() {
        $user_id = $this->session->userdata('id_utilisateur');
        $this->db->select('up.id_profil')
                 ->from('utilisateur_profils up')
                 ->where('up.id_utilisateur', $user_id)
                 ->where('up.id_profil', 1);
        return $this->db->get()->num_rows() > 0;
    }

    private function get_user_id() {
        return $this->session->userdata('id_utilisateur');
    }

    /**
     * Liste des logs d'audit
     */
    public function index() {
    $data['title'] = 'Journal d\'audit système';
    
    $filters = [
        'id_utilisateur' => $this->input->get('id_utilisateur'),
        'type_action' => $this->input->get('type_action'),
        'table_cible' => $this->input->get('table_cible'),
        'date_debut' => $this->input->get('date_debut'),
        'date_fin' => $this->input->get('date_fin')
    ];
    
    $config['base_url'] = base_url('logs-audit/index');
    $config['total_rows'] = $this->LogsAudit_model->count_all($filters);
    $config['per_page'] = 50;
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
    
    $data['logs'] = $this->LogsAudit_model->get_all(
        $config['per_page'],
        $page,
        $filters
    );
    
    $data['stats'] = $this->LogsAudit_model->get_stats($filters);
    $data['utilisateurs'] = $this->db->select('id_utilisateur, prenom, nom, email')->get('utilisateurs')->result();
    $data['types_action'] = $this->LogsAudit_model->get_distinct_types();
    $data['tables_cibles'] = $this->LogsAudit_model->get_distinct_tables();
    $data['filters'] = $filters;
    
    // AJOUTER CETTE LIGNE
    $data['is_super_admin'] = $this->is_super_admin();
    
    $this->load->view('logs_audit_list', $data);
}

    /**
     * Détail d'un log
     */
    public function detail($id_log) {
        $data['log'] = $this->LogsAudit_model->get_by_id_with_details($id_log);
        
        if (!$data['log']) {
            show_404();
        }
        
        $data['title'] = 'Détail du log #' . $id_log;
        
        $this->load->view('logs_audit_detail', $data);
    }

    /**
     * Nettoyer les anciens logs
     */
    public function nettoyer() {
        if (!$this->is_super_admin()) {
            show_error('Accès réservé au super administrateur', 403);
        }
        
        $jours = $this->input->post('jours') ?: 90;
        $supprime = $this->LogsAudit_model->clean_old_logs($jours);
        
        $this->session->set_flashdata('success', $supprime . ' logs supprimés (plus de ' . $jours . ' jours)');
        redirect('logs-audit');
    }

    /**
     * Exporter les logs
     */
    public function exporter() {
        if (!$this->is_admin()) {
            show_error('Accès non autorisé', 403);
        }
        
        $filters = [
            'id_utilisateur' => $this->input->get('id_utilisateur'),
            'type_action' => $this->input->get('type_action'),
            'table_cible' => $this->input->get('table_cible'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin')
        ];
        
        $format = $this->input->get('format') ?: 'csv';
        $logs = $this->LogsAudit_model->get_all(null, null, $filters);
        
        if ($format == 'csv') {
            $this->export_csv($logs);
        } elseif ($format == 'json') {
            $this->export_json($logs);
        }
    }

    private function export_csv($logs) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=logs_audit_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Utilisateur', 'Action', 'Table cible', 'ID cible', 'Adresse IP', 'Date']);
        
        foreach ($logs as $log) {
            fputcsv($output, [
                $log->id_log,
                $log->utilisateur_nom,
                $log->type_action,
                $log->table_cible ?: '-',
                $log->id_cible ?: '-',
                $log->adresse_ip ?: '-',
                date('d/m/Y H:i:s', strtotime($log->date_creation))
            ]);
        }
        
        fclose($output);
    }

    private function export_json($logs) {
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename=logs_audit_' . date('Y-m-d') . '.json');
        
        $data = [];
        foreach ($logs as $log) {
            $data[] = [
                'id' => $log->id_log,
                'utilisateur' => $log->utilisateur_nom,
                'action' => $log->type_action,
                'table' => $log->table_cible,
                'id_cible' => $log->id_cible,
                'ip' => $log->adresse_ip,
                'date' => $log->date_creation
            ];
        }
        
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    /**
     * Statistiques détaillées
     */
    public function statistiques() {
        if (!$this->is_admin()) {
            show_error('Accès non autorisé', 403);
        }
        
        $data['title'] = 'Statistiques des logs';
        $data['stats_par_action'] = $this->LogsAudit_model->get_stats_by_action();
        $data['stats_par_table'] = $this->LogsAudit_model->get_stats_by_table();
        $data['stats_par_jour'] = $this->LogsAudit_model->get_stats_by_day(30);
        $data['stats_par_utilisateur'] = $this->LogsAudit_model->get_stats_by_user(10);
        $data['total_logs'] = $this->LogsAudit_model->count_all();
        
        $this->load->view('logs_audit_statistiques', $data);
    }
}
?>