<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('is_super_admin')) {
    function is_super_admin() {
        $CI =& get_instance();
        $user_id = $CI->session->userdata('id_utilisateur');
        
        if (!$user_id) return false;
        
        $CI->db->select('up.id_profil')
               ->from('utilisateur_profils up')
               ->where('up.id_utilisateur', $user_id)
               ->where('up.id_profil', 1);
        
        return $CI->db->get()->num_rows() > 0;
    }
}

if (!function_exists('get_action_color')) {
    function get_action_color($action) {
        $colors = [
            'connexion' => 'success',
            'deconnexion' => 'secondary',
            'creation' => 'primary',
            'modification' => 'warning',
            'suppression' => 'danger',
            'export' => 'info'
        ];
        
        foreach ($colors as $key => $color) {
            if (strpos(strtolower($action), $key) !== false) {
                return $color;
            }
        }
        return 'secondary';
    }
}
?>