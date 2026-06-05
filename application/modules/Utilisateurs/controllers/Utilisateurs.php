<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @author:    dushime paul
 * Email:     dushimeyesupaulin@gmail.com
 * Date :     Le 20/01/2026
 */

class Utilisateurs extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') !== TRUE) {
            redirect('Admin');
        }
        // Vérifier les permissions (seul super_admin et admin peuvent gérer les utilisateurs)
        $role = $this->session->userdata('role');
        if ($role !== 'super_admin' && $role !== 'admin') {
            show_error('Vous n\'avez pas les permissions nécessaires pour accéder à cette page.', 403);
        }
    }

    // Afficher la liste des utilisateurs
    public function index()
    {
        // Récupérer tous les utilisateurs
        $data['utilisateurs'] = $this->db->select('u.*')
            ->from('utilisateurs u')
            ->order_by('u.id_utilisateur', 'DESC')
            ->get()
            ->result_array();
        
        // Récupérer tous les profils disponibles
        $data['profils'] = $this->Model->read('profils', ['est_actif' => 1], 'id_profil', 'ASC');
        
        // Récupérer les profils assignés à chaque utilisateur
        $user_profiles = $this->db->select('id_utilisateur, id_profil')
            ->from('utilisateur_profils')
            ->get()
            ->result_array();
        
        $data['user_roles'] = [];
        foreach ($user_profiles as $up) {
            if (!isset($data['user_roles'][$up['id_utilisateur']])) {
                $data['user_roles'][$up['id_utilisateur']] = [];
            }
            $data['user_roles'][$up['id_utilisateur']][] = $up['id_profil'];
        }
        
        $data['total_utilisateurs'] = count($data['utilisateurs']);
        $data['total_actifs'] = $this->Model->count('utilisateurs', ['est_actif' => 1]);
        $data['total_inactifs'] = $this->Model->count('utilisateurs', ['est_actif' => 0]);
        
        $this->load->view('Utilisateurs_View', $data);
    }

    // Ajouter un utilisateur
    public function add()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $prenom = $this->input->post('prenom');
            $nom = $this->input->post('nom');
            $telephone = $this->input->post('telephone');
            $profil_ids = $this->input->post('profil_ids');
            
            // Validation
            if (empty($email) || empty($password) || empty($prenom) || empty($nom)) {
                $this->session->set_flashdata('error', 'Tous les champs obligatoires doivent être remplis.');
                redirect(base_url('Utilisateurs/add'));
                return;
            }
            
            // Vérifier si l'email existe déjà
            $existing = $this->Model->readOne('utilisateurs', ['email' => $email]);
            if ($existing) {
                $this->session->set_flashdata('error', 'Cet email est déjà utilisé.');
                redirect(base_url('Utilisateurs/add'));
                return;
            }
            
            // Gestion de l'avatar
            $avatar_url = null;
            if (!empty($_FILES['avatar']['name'])) {
                $upload = $this->upload_image($_FILES['avatar']['tmp_name'], $_FILES['avatar']['name']);
                if ($upload) {
                    $avatar_url = 'attachments/Users/' . $upload;
                }
            }
            
            // Créer l'utilisateur
            $user_data = [
                'email' => $email,
                'mot_de_passe' => password_hash($password, PASSWORD_BCRYPT),
                'prenom' => $prenom,
                'nom' => $nom,
                'telephone' => $telephone,
                'avatar_url' => $avatar_url,
                'email_verifie' => $this->input->post('email_verifie') ? 1 : 0,
                'telephone_verifie' => $this->input->post('telephone_verifie') ? 1 : 0,
                'est_actif' => $this->input->post('est_actif') ? 1 : 0,
                'date_creation' => date('Y-m-d H:i:s')
            ];
            
            $user_id = $this->Model->createLastId('utilisateurs', $user_data);
            
            if ($user_id && !empty($profil_ids)) {
                foreach ($profil_ids as $profil_id) {
                    $this->db->insert('utilisateur_profils', [
                        'id_utilisateur' => $user_id,
                        'id_profil' => $profil_id,
                        'attribue_par' => $this->session->userdata('id_utilisateur'),
                        'date_attribution' => date('Y-m-d H:i:s')
                    ]);
                }
            }
            
            if ($user_id) {
                $this->session->set_flashdata('success', 'Utilisateur créé avec succès.');
                redirect(base_url('Utilisateurs'));
            } else {
                $this->session->set_flashdata('error', 'Erreur lors de la création de l\'utilisateur.');
                redirect(base_url('Utilisateurs/add'));
            }
        }
        
        $data['profils'] = $this->Model->read('profils', ['est_actif' => 1], 'id_profil', 'ASC');
        $this->load->view('Utilisateurs_View', $data);
    }

    // Modifier un utilisateur
    public function edit($id)
    {
        $data['utilisateur'] = $this->Model->readOne('utilisateurs', ['id_utilisateur' => $id]);
        
        if (!$data['utilisateur']) {
            $this->session->set_flashdata('error', 'Utilisateur non trouvé.');
            redirect(base_url('Utilisateurs'));
        }
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $update_data = [
                'prenom' => $this->input->post('prenom'),
                'nom' => $this->input->post('nom'),
                'email' => $this->input->post('email'),
                'telephone' => $this->input->post('telephone'),
                'email_verifie' => $this->input->post('email_verifie') ? 1 : 0,
                'telephone_verifie' => $this->input->post('telephone_verifie') ? 1 : 0,
                'est_actif' => $this->input->post('est_actif') ? 1 : 0,
                'date_modification' => date('Y-m-d H:i:s')
            ];
            
            // Changer le mot de passe si fourni
            $new_password = $this->input->post('new_password');
            if (!empty($new_password)) {
                if (strlen($new_password) >= 6) {
                    $update_data['mot_de_passe'] = password_hash($new_password, PASSWORD_BCRYPT);
                } else {
                    $this->session->set_flashdata('error', 'Le mot de passe doit contenir au moins 6 caractères.');
                    redirect(base_url('Utilisateurs/edit/' . $id));
                    return;
                }
            }
            
            // Gestion de l'avatar
            if (!empty($_FILES['avatar']['name'])) {
                $upload = $this->upload_image($_FILES['avatar']['tmp_name'], $_FILES['avatar']['name']);
                if ($upload) {
                    // Supprimer l'ancien avatar s'il existe
                    if ($data['utilisateur']['avatar_url'] && file_exists(FCPATH . $data['utilisateur']['avatar_url'])) {
                        unlink(FCPATH . $data['utilisateur']['avatar_url']);
                    }
                    $update_data['avatar_url'] = 'attachments/Users/' . $upload;
                }
            }
            
            $this->Model->update('utilisateurs', ['id_utilisateur' => $id], $update_data);
            
            // Mettre à jour les rôles
            $profil_ids = $this->input->post('profil_ids');
            if (!empty($profil_ids)) {
                $this->db->delete('utilisateur_profils', ['id_utilisateur' => $id]);
                foreach ($profil_ids as $profil_id) {
                    $this->db->insert('utilisateur_profils', [
                        'id_utilisateur' => $id,
                        'id_profil' => $profil_id,
                        'attribue_par' => $this->session->userdata('id_utilisateur'),
                        'date_attribution' => date('Y-m-d H:i:s')
                    ]);
                }
            }
            
            $this->session->set_flashdata('success', 'Utilisateur modifié avec succès.');
            redirect(base_url('Utilisateurs'));
        }
        
        // Récupérer les rôles actuels de l'utilisateur
        $user_roles = $this->db->select('id_profil')
            ->from('utilisateur_profils')
            ->where('id_utilisateur', $id)
            ->get()
            ->result_array();
        
        $data['user_roles'] = array_column($user_roles, 'id_profil');
        $data['profils'] = $this->Model->read('profils', ['est_actif' => 1], 'id_profil', 'ASC');
        
        $this->load->view('Utilisateurs_View', $data);
    }

    // Voir les détails d'un utilisateur
    public function view($id)
    {
        $data['utilisateur'] = $this->Model->readOne('utilisateurs', ['id_utilisateur' => $id]);
        
        if (!$data['utilisateur']) {
            $this->session->set_flashdata('error', 'Utilisateur non trouvé.');
            redirect(base_url('Utilisateurs'));
        }
        
        // Récupérer les rôles de l'utilisateur
        $data['roles'] = $this->db->select('p.*')
            ->from('utilisateur_profils up')
            ->join('profils p', 'p.id_profil = up.id_profil')
            ->where('up.id_utilisateur', $id)
            ->get()
            ->result_array();
        
        // Récupérer les adresses de l'utilisateur
        $data['adresses'] = $this->db->select('*')
            ->from('adresses')
            ->where('id_utilisateur', $id)
            ->get()
            ->result_array();
        
        // Récupérer les commandes de l'utilisateur
        $data['commandes'] = $this->db->select('*')
            ->from('commandes')
            ->where('id_utilisateur', $id)
            ->order_by('date_creation', 'DESC')
            ->limit(10)
            ->get()
            ->result_array();
        
        $this->load->view('Utilisateurs_View', $data);
    }

    // Supprimer un utilisateur
    public function delete($id)
    {
        $user = $this->Model->readOne('utilisateurs', ['id_utilisateur' => $id]);
        
        if (!$user) {
            $this->session->set_flashdata('error', 'Utilisateur non trouvé.');
            redirect(base_url('Utilisateurs'));
        }
        
        // Empêcher la suppression de son propre compte
        if ($id == $this->session->userdata('id_utilisateur')) {
            $this->session->set_flashdata('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            redirect(base_url('Utilisateurs'));
        }
        
        // Supprimer l'avatar s'il existe
        if ($user['avatar_url'] && file_exists(FCPATH . $user['avatar_url'])) {
            unlink(FCPATH . $user['avatar_url']);
        }
        
        // Supprimer les relations (cascade devrait le faire, mais on le fait manuellement)
        $this->db->delete('utilisateur_profils', ['id_utilisateur' => $id]);
        
        // Supprimer l'utilisateur
        $this->Model->delete('utilisateurs', ['id_utilisateur' => $id]);
        
        $this->session->set_flashdata('success', 'Utilisateur supprimé avec succès.');
        redirect(base_url('Utilisateurs'));
    }

    // Upload images
    public function upload_image($nom_file, $nom_champ)
    {
        $ref_folder = FCPATH . 'attachments/Users/';
        $code = date("YmdHis") . uniqid();
        $fichier = basename($code);
        $file_extension = pathinfo($nom_champ, PATHINFO_EXTENSION);
        $file_extension = strtolower($file_extension);
        $valid_ext = array('gif', 'jpg', 'png', 'jpeg', 'webp', 'svg');

        if (!in_array($file_extension, $valid_ext)) {
            return NULL;
        }

        if (!is_dir($ref_folder)) {
            mkdir($ref_folder, 0777, TRUE);
        }

        move_uploaded_file($nom_file, $ref_folder . $fichier . "." . $file_extension);
        return $fichier . "." . $file_extension;
    }

    // Activer/Désactiver un utilisateur
    public function toggle_status($id)
    {
        $user = $this->Model->readOne('utilisateurs', ['id_utilisateur' => $id]);
        
        if (!$user) {
            $this->session->set_flashdata('error', 'Utilisateur non trouvé.');
            redirect(base_url('Utilisateurs'));
        }
        
        $new_status = $user['est_actif'] == 1 ? 0 : 1;
        $this->Model->update('utilisateurs', ['id_utilisateur' => $id], ['est_actif' => $new_status]);
        
        $this->session->set_flashdata('success', 'Statut de l\'utilisateur modifié avec succès.');
        redirect(base_url('Utilisateurs'));
    }
}
?>