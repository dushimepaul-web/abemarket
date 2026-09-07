<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Model extends CI_Model{


    function create($table, $data) {
        $query = $this->db->insert($table, $data);
        return ($query) ? true : false;
    }

    function read($table, $criteres = array(), $order_by_column = null, $order = 'DESC', $limit = null) {
    // WHERE
    if ($criteres !== null) {
        $this->db->where($criteres);
    }
    // ORDER BY
    if ($order_by_column !== null) {
        $this->db->order_by($order_by_column, $order);
    }
    // LIMIT
    if ($limit !== null) {
        $this->db->limit($limit);
    }

    $query = $this->db->get($table);
    return $query->result_array();
}


    function readWhereIdIn($table, $ids = array()) {
        $this->db->where('isDeleted !=', 1);
        $this->db->where('isApproved !=', 0);
        $this->db->where_in('idAccount', $ids);
        $query = $this->db->get($table);
        return $query->result_array();
    }



    function update($table, $criteres, $data) {
        $this->db->where($criteres);
        $query = $this->db->update($table, $data);
        return ($query) ? true : false;
    }


    function updateWhereIdIn($table, $ids = array()) {
    // $this->db->where('isDeleted !=', 1);
    date_default_timezone_set('Africa/Bujumbura');
    $date=date('Y-m-d:H:i:s');
    $this->db->where_in('idAccount', $ids);
    $query = $this->db->get($table);
    $result = $query->result_array();

    // Update isApproved to 0 for matching IDs
    $matchedIds = array_column($result, 'idAccount');
    $updateIds = array_intersect($ids, $matchedIds);

    if (!empty($updateIds)) {
        $this->db->where_in('idAccount', $updateIds);
        $this->db->update($table, array('isTreated' => 1,'dateTreated'=>$date));
    }

    return $result;
}



    function updateReturnAffectedRow($table, $criteres, $data) {
        $this->db->where($criteres);
        $this->db->update($table, $data);
        $affected_rows = $this->db->affected_rows();

        if ($affected_rows > 0) {
            $query = $this->db->get_where($table, $criteres);
            return $query->row_array();
        } else {
            return null;
        }
    }

    // loging with the specific permission
    public function getUserGroupByUserId($idUser){
      $sql = "SELECT * FROM user_group 
      INNER JOIN groups ON groups.idGroup = user_group.idGroup 
      WHERE user_group.idUser = ?";
      $query = $this->db->query($sql, array($idUser));
      $result = $query->row_array();

      return $result;
     }

    public function login($username, $password) {
        if($username && $password) {
          $sql = "SELECT * FROM utilisateurs WHERE email = ?";
          $query = $this->db->query($sql, array($username));

          if($query->num_rows() == 1) {
            $result = $query->row_array();

            $hash_password = password_verify($password, $result['mot_de_passe']);
            if($hash_password === true) {
              return $result; 
            }
            else {
              return false;
            }

            
          }
          else {
            return false;
          }
        }
    }

    public function check_email($email){ // it checks if a specific username exist 
      if($email) {
        $sql = 'SELECT * FROM utilisateurs WHERE email = ?';
        $query = $this->db->query($sql, array($email));
        $result = $query->num_rows();
        return ($result == 1) ? true : false;
      }

      return false;
    }
    
    function delete($table,$criteres){
        $this->db->where($criteres);
        $query = $this->db->delete($table);
        return ($query) ? true : false;
    }

    function readOne($table, $criteres) {
        $this->db->where($criteres);
        $query = $this->db->get($table);
        return $query->row_array();
    }

    function readQuery($query,$bindings = null){
      if (!is_null($bindings) && !empty($bindings)) {
            $query=$this->db->query($query, $bindings);
        } else {
            $query=$this->db->query($query);
        }

      if ($query) {
         return $query->result_array();
      }
    }

    function readQueryOne($query,$bindings = null){
      
      if (!is_null($bindings) && !empty($bindings)) {
            $query=$this->db->query($query, $bindings);
        } else {
            $query=$this->db->query($query);
        }
      if ($query) {
        return $query->row_array();
      }
    }


    function createLastId($table, $data) {
        $query = $this->db->insert($table, $data);
       if ($query) {
            return $this->db->insert_id();
        }
    }


    public function Set_History($idUser,$action,$description){
        $this->db->insert('logHistory', array(
                    'idUser' => $idUser,
                    'action' => $action,
                    'actionDescription' =>$description ));
    }

    
    
    function createBatch($table,$data){   
      $query=$this->db->insert_batch($table, $data);
      return ($query) ? true : false;
    }

    function readLimit($table,$limit)
    {
     $this->db->limit($limit);
     $query= $this->db->get($table);
     
      if($query)
       {
           return $query->result_array();
       }   
    }
 

    function updateBatch($table, $criteres, $data) {
        $this->db->where($criteres);
        $query = $this->db->update_batch($table, $data);
        return ($query) ? true : false;
    }


    function checkValue($table, $criteres) {
        $this->db->where($criteres);
        $query = $this->db->get($table);
        if($query->num_rows() > 0)
        {
           return true ;
        }
        else{
           return false;
       }
    }

    
    public function getValueSettings($key)
    {
        $query = $this->db->query("SELECT value FROM settings WHERE thekey = ? LIMIT 1", [$key]);
        $value = $query->row_array();
        if(!$value) {
            return null;
        }
        return $value['value'];
    }

     public function setValueStore($key, $value)
    {
        $this->db->where('thekey', $key);
        $query = $this->db->get('settings');
        if ($query->num_rows() > 0) {
            $this->db->where('thekey', $key);
            if (!$this->db->update('settings', array('value' => $value))) {
                log_message('error', print_r($this->db->error(), true));
                show_error(lang('database_error'));
            }
        } else {
            if (!$this->db->insert('settings', array('value' => $value, 'thekey' => $key))) {
                log_message('error', print_r($this->db->error(), true));
                show_error(lang('database_error'));
            }
        }

    }
    

   public function get_setting($key, $default = null)
     {
      $this->db->select('Value');
      $this->db->from('settings');
      $this->db->where('KeyValue', $key);
      $query = $this->db->get();

      if ($query->num_rows() > 0) {
        return $query->row()->Value;
     }

    return $default; 
    }



     //global query
     public function execute_query($sql){
      // Exécute la requête
        $query = $this->db->query($sql);

        // Vérifie si la requête retourne des résultats
        if ($query->num_rows() > 0) {
            return $query->result(); // Retourne les résultats sous forme d'objet
        } else {
            return []; // Retourne un tableau vide si aucun résultat
        }
    }

    
    // Compter toutes les entrées d'une table
    public function count($table,$where=null)
    {  
        if ($where) {
            return $this->db->where($where)->count_all_results($table);
        }else{
            return $this->db->count_all($table);
        }
        
    }


     
function getYoutubeId($url)
{
    preg_match(
        '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
        $url,
        $match
    );
    return $match[1] ?? null;
}


/*
 public function log_visit()
{
    $ip = $this->input->ip_address(); 
    $today = date('Y-m-d');
    $current_page = current_url();

    // Si on est en local, on met une IP de test pour voir si ça marche
    if ($ip == '::1' || $ip == '127.0.0.1') {
        $ip = '104.28.147.22'; // IP de test (Bujumbura par exemple)
    }

    // 1. On vérifie l'existence pour l'IP, la PAGE et la DATE précise
    $this->db->where('ip_address', $ip);
    $this->db->where('visit_date', $today);
    $this->db->where('page', $current_page);
    $query = $this->db->get('visitors_logs');

    // 2. Si le résultat est vide, on insère
    if ($query->num_rows() == 0) {
        
        // On n'appelle l'API que si on va vraiment insérer (gain de performance)
        $geo = @json_decode(file_get_contents("https://ipapi.co/{$ip}/json/"));

        $data = [
            'ip_address' => $ip,
            'user_agent' => $this->input->user_agent(),
            'device'     => $this->agent->is_mobile() ? 'Mobile' : 'Desktop',
            'page'       => $current_page,
            'referer'    => $this->agent->referrer(),
            'visit_date' => $today,
            'visit_time' => date('H:i:s'),
            'country'    => $geo->country_name ?? 'Unknown',
            'city'       => $geo->city ?? 'Unknown',
            'latitude'   => $geo->latitude ?? null,
            'longitude'  => $geo->longitude ?? null
        ];

        $this->db->insert('visitors_logs', $data);
    }
}









/**
 * Récupérer un utilisateur par son email
 */
public function getUserByEmail($email)
{
    $this->db->where('email', $email);
    $query = $this->db->get('utilisateurs');
    return $query->row_array();
}

/**
 * Récupérer les profils d'un utilisateur avec leurs permissions
 */
public function getUserProfiles($user_id)
{
    $this->db->select('p.*, up.*');
    $this->db->from('utilisateur_profils up');
    $this->db->join('profils p', 'p.id_profil = up.id_profil');
    $this->db->where('up.id_utilisateur', $user_id);
    $query = $this->db->get();
    return $query->result_array();
}

/**
 * Récupérer les informations vendeur par ID utilisateur
 */
public function getVendeurByUserId($user_id)
{
    $this->db->where('id_utilisateur', $user_id);
    $query = $this->db->get('vendeurs');
    return $query->row_array();
}

/**
 * Récupérer les informations transporteur par ID utilisateur
 */
public function getTransporteurByUserId($user_id)
{
    $this->db->where('id_utilisateur', $user_id);
    $query = $this->db->get('transporteurs');
    return $query->row_array();
}

/**
 * Mettre à jour la dernière connexion de l'utilisateur
 */
public function updateLastLogin($user_id)
{
    $data = array(
        'derniere_connexion' => date('Y-m-d H:i:s')
    );
    $this->db->where('id_utilisateur', $user_id);
    return $this->db->update('utilisateurs', $data);
}

/**
 * Journaliser les tentatives de connexion
 */
public function logConnexionAttempt($user_id, $email_tente, $ip_address, $reussie, $motif_echec = null)
{
    $valid_user_id = null;
    if ($user_id) {
        $this->db->where('id_utilisateur', $user_id);
        $check = $this->db->get('utilisateurs');
        if ($check->num_rows() > 0) {
            $valid_user_id = $user_id;
        }
    }
    $data = array(
        'id_utilisateur' => $valid_user_id,
        'email_tente' => $email_tente,
        'adresse_ip' => $ip_address,
        'reussie' => $reussie ? 1 : 0,
        'motif_echec' => $motif_echec,
        'date_tentative' => date('Y-m-d H:i:s')
    );
    return $this->db->insert('tentatives_connexion', $data);
}




public function log_visit() {
    $ip = $this->input->ip_address();
    
    // Forcer une IP publique si on est en local
    if ($ip == '::1' || $ip == '127.0.0.1') {
        $ip = '197.255.128.0'; 
    }

    $today = date('Y-m-d');
    $current_page = current_url();

    // Vérification anti-doublon
    $exists = $this->db->get_where('visitors_logs', [
        'ip_address' => $ip, 
        'visit_date' => $today,
        'page'       => $current_page
    ])->row();

    if (!$exists) {
        // Utilisation de ip-api.com (plus stable pour le local)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/{$ip}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        
        $response = curl_exec($ch);
        $geo = json_decode($response, true); // true pour avoir un tableau associatif
        curl_close($ch);

        $data = [
            'ip_address' => $ip,
            'user_agent' => $this->input->user_agent(),
            'device'     => $this->agent->is_mobile() ? 'Mobile' : 'Desktop',
            'page'       => $current_page,
            'referer'    => $this->agent->referrer(),
            'visit_date' => $today,
            'visit_time' => date('H:i:s'),
            // Adaptation aux clés de ip-api.com
            'country'    => $geo['country'] ?? 'Unknown',
            'city'       => $geo['city'] ?? 'Unknown',
            'latitude'   => $geo['lat'] ?? null,
            'longitude'  => $geo['lon'] ?? null
        ];

        $this->db->insert('visitors_logs', $data);
    }
}



    }
 







   