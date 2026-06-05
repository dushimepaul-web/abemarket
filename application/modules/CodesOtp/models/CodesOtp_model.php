<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CodesOtp_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->helper('string');
    }

    /**
     * Compter tous les codes OTP
     */
    public function count_all($filters = []) {
        $this->db->from('codes_otp c');
        $this->db->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur');
        
        if (!empty($filters['type_otp'])) {
            $this->db->where('c.type_otp', $filters['type_otp']);
        }
        if (isset($filters['utilise']) && $filters['utilise'] !== '') {
            $this->db->where('c.utilise', $filters['utilise']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(c.date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(c.date_creation) <=', $filters['date_fin']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('u.email', $filters['search']);
            $this->db->or_like('u.prenom', $filters['search']);
            $this->db->or_like('u.nom', $filters['search']);
            $this->db->or_like('c.code', $filters['search']);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupérer tous les codes OTP
     */
    public function get_all($limit = null, $offset = null, $filters = []) {
        $this->db->select('c.*, u.prenom, u.nom, u.email as user_email, CONCAT(u.prenom, " ", u.nom) as utilisateur_nom')
                 ->from('codes_otp c')
                 ->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur')
                 ->order_by('c.date_creation', 'DESC');
        
        if (!empty($filters['type_otp'])) {
            $this->db->where('c.type_otp', $filters['type_otp']);
        }
        if (isset($filters['utilise']) && $filters['utilise'] !== '') {
            $this->db->where('c.utilise', $filters['utilise']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(c.date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(c.date_creation) <=', $filters['date_fin']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('u.email', $filters['search']);
            $this->db->or_like('u.prenom', $filters['search']);
            $this->db->or_like('u.nom', $filters['search']);
            $this->db->or_like('c.code', $filters['search']);
            $this->db->group_end();
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Récupérer un code par ID
     */
    public function get_by_id($id) {
        $this->db->select('c.*, u.prenom, u.nom, u.email as user_email')
                 ->from('codes_otp c')
                 ->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur')
                 ->where('c.id_otp', $id);
        
        return $this->db->get()->row();
    }

    /**
     * Générer un code OTP
     */
    public function generer_code($id_utilisateur, $type_otp, $telephone = null, $email = null) {
        // Générer un code aléatoire à 6 chiffres
        $code = random_string('numeric', 6);
        
        // Date d'expiration (15 minutes)
        $date_expiration = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        
        $data = [
            'id_utilisateur' => $id_utilisateur,
            'code' => $code,
            'type_otp' => $type_otp,
            'telephone' => $telephone,
            'email' => $email,
            'tentatives' => 0,
            'date_expiration' => $date_expiration,
            'utilise' => 0,
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        $this->db->insert('codes_otp', $data);
        
        if ($this->db->affected_rows() > 0) {
            return ['code' => $code, 'id' => $this->db->insert_id()];
        }
        
        return false;
    }

    /**
     * Vérifier un code OTP
     */
    public function verifier_code($code, $type_otp, $id_utilisateur = null, $email = null, $telephone = null) {
        $this->db->where('code', $code);
        $this->db->where('type_otp', $type_otp);
        $this->db->where('utilise', 0);
        $this->db->where('date_expiration >', date('Y-m-d H:i:s'));
        
        if ($id_utilisateur) {
            $this->db->where('id_utilisateur', $id_utilisateur);
        }
        if ($email) {
            $this->db->where('email', $email);
        }
        if ($telephone) {
            $this->db->where('telephone', $telephone);
        }
        
        $code_data = $this->db->get('codes_otp')->row();
        
        if (!$code_data) {
            return false;
        }
        
        // Marquer comme utilisé
        $this->db->where('id_otp', $code_data->id_otp);
        $this->db->update('codes_otp', ['utilise' => 1]);
        
        return $code_data;
    }

    /**
     * Incrémenter les tentatives
     */
    public function incrementer_tentatives($id) {
        $this->db->set('tentatives', 'tentatives + 1', FALSE);
        $this->db->where('id_otp', $id);
        $this->db->update('codes_otp');
    }

    /**
     * Supprimer un code
     */
    public function delete($id) {
        $this->db->where('id_otp', $id);
        $this->db->delete('codes_otp');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Nettoyer les codes expirés
     */
    public function nettoyer_expires() {
        $this->db->where('date_expiration <', date('Y-m-d H:i:s'));
        $this->db->or_where('utilise', 1);
        $this->db->delete('codes_otp');
        return $this->db->affected_rows();
    }

    /**
     * Statistiques
     */
    public function get_stats($filters = []) {
        $this->db->select('
            COUNT(*) as total,
            SUM(CASE WHEN type_otp = "connexion" THEN 1 ELSE 0 END) as connexion,
            SUM(CASE WHEN type_otp = "verification_telephone" THEN 1 ELSE 0 END) as verification_telephone,
            SUM(CASE WHEN type_otp = "verification_email" THEN 1 ELSE 0 END) as verification_email,
            SUM(CASE WHEN type_otp = "reinitialisation_mdp" THEN 1 ELSE 0 END) as reinitialisation_mdp,
            SUM(CASE WHEN utilise = 1 THEN 1 ELSE 0 END) as utilises,
            SUM(CASE WHEN utilise = 0 AND date_expiration > NOW() THEN 1 ELSE 0 END) as actifs,
            SUM(CASE WHEN utilise = 0 AND date_expiration <= NOW() THEN 1 ELSE 0 END) as expires
        ');
        
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(date_creation) <=', $filters['date_fin']);
        }
        
        $result = $this->db->get('codes_otp')->row();
        
        if (!$result->total) $result->total = 0;
        if (!$result->connexion) $result->connexion = 0;
        if (!$result->verification_telephone) $result->verification_telephone = 0;
        if (!$result->verification_email) $result->verification_email = 0;
        if (!$result->reinitialisation_mdp) $result->reinitialisation_mdp = 0;
        if (!$result->utilises) $result->utilises = 0;
        if (!$result->actifs) $result->actifs = 0;
        if (!$result->expires) $result->expires = 0;
        
        return $result;
    }
}
?>