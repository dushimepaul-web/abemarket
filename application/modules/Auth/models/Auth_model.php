<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Vérifier si l'email existe
    public function getUserByEmail($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('utilisateurs');
        return $query->row_array();
    }
    
    // Vérifier si le téléphone existe
    public function getUserByPhone($telephone) {
        $this->db->where('telephone', $telephone);
        $query = $this->db->get('utilisateurs');
        return $query->row_array();
    }
    
    // Créer un nouvel utilisateur
    public function createUser($data) {
        $this->db->insert('utilisateurs', $data);
        return $this->db->insert_id();
    }
    
    // Attribuer un profil à l'utilisateur
    public function assignUserProfile($id_utilisateur, $id_profil, $attribue_par = null) {
        $data = array(
            'id_utilisateur' => $id_utilisateur,
            'id_profil' => $id_profil,
            'attribue_par' => $attribue_par ?: $id_utilisateur,
            'date_attribution' => date('Y-m-d H:i:s')
        );
        $this->db->insert('utilisateur_profils', $data);
        return $this->db->affected_rows();
    }
    
    // Récupérer les profils d'un utilisateur
    public function getUserProfiles($id_utilisateur) {
        $this->db->select('p.*');
        $this->db->from('utilisateur_profils up');
        $this->db->join('profils p', 'p.id_profil = up.id_profil');
        $this->db->where('up.id_utilisateur', $id_utilisateur);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    // Mettre à jour la dernière connexion
    public function updateLastLogin($id_utilisateur) {
        $this->db->where('id_utilisateur', $id_utilisateur);
        $this->db->update('utilisateurs', array('derniere_connexion' => date('Y-m-d H:i:s')));
        return $this->db->affected_rows();
    }
    
    // Mettre à jour le mot de passe (MD5)
    public function updatePassword($id_utilisateur, $new_password) {
        $this->db->where('id_utilisateur', $id_utilisateur);
        $this->db->update('utilisateurs', array('mot_de_passe' => md5($new_password)));
        return $this->db->affected_rows();
    }
    
    // Vérifier si le token de réinitialisation est valide
public function checkResetToken($token) {
    $this->db->where('token_reset', $token);
    $this->db->where('date_reset_expiration >', date('Y-m-d H:i:s'));
    $query = $this->db->get('utilisateurs');
    return $query->row_array();
}
    
    // Sauvegarder le token de réinitialisation
    public function saveResetToken($email, $token) {
        $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $this->db->where('email', $email);
        $this->db->update('utilisateurs', array(
            'token_reset' => $token,
            'date_reset_expiration' => $expiration
        ));
        return $this->db->affected_rows();
    }
    
    // Journaliser les tentatives de connexion
    public function logConnexionAttempt($id_utilisateur, $email_tente, $adresse_ip, $reussie, $motif_echec = null) {
        $valid_user_id = null;
        if ($id_utilisateur) {
            $this->db->where('id_utilisateur', $id_utilisateur);
            $check = $this->db->get('utilisateurs');
            if ($check->num_rows() > 0) {
                $valid_user_id = $id_utilisateur;
            }
        }
        $data = array(
            'id_utilisateur' => $valid_user_id,
            'email_tente' => $email_tente,
            'adresse_ip' => $adresse_ip,
            'reussie' => $reussie ? 1 : 0,
            'motif_echec' => $motif_echec,
            'date_tentative' => date('Y-m-d H:i:s')
        );
        $this->db->insert('tentatives_connexion', $data);
        return $this->db->insert_id();
    }
    
    // Vérifier les tentatives de connexion récentes par IP
    public function checkRecentAttempts($adresse_ip, $minutes = 15) {
        $this->db->where('adresse_ip', $adresse_ip);
        $this->db->where('date_tentative >', date('Y-m-d H:i:s', strtotime("-$minutes minutes")));
        $this->db->where('reussie', 0);
        $query = $this->db->get('tentatives_connexion');
        return $query->num_rows();
    }
    
    // Vérifier les tentatives de connexion récentes par email
    public function checkRecentEmailAttempts($email, $minutes = 5) {
        $this->db->where('email_tente', $email);
        $this->db->where('date_tentative >', date('Y-m-d H:i:s', strtotime("-$minutes minutes")));
        $this->db->where('reussie', 0);
        $query = $this->db->get('tentatives_connexion');
        return $query->num_rows();
    }
}