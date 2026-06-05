<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @author:    dushime paul
 * Email:     dushimeyesupaulin@gmail.com
 * Date :     Le 20/01/2026
 */

class Roles extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') !== TRUE) {
            redirect('Admin');
        }
        // Vérifier les permissions (seul super_admin peut gérer les rôles)
        $role = $this->session->userdata('role');
        if ($role !== 'super_admin') {
            show_error('Vous n\'avez pas les permissions nécessaires pour accéder à cette page.', 403);
        }
    }

    public function index()
    {
        // Récupérer tous les profils disponibles
        $data['profils'] = $this->Model->read('profils', [], 'id_profil', 'ASC');
        
        // Récupérer tous les utilisateurs
        $data['utilisateurs'] = $this->db->select('u.*')
            ->from('utilisateurs u')
            ->order_by('u.id_utilisateur', 'ASC')
            ->get()
            ->result_array();
        
        // Récupérer les rôles assignés à chaque utilisateur
        $user_roles = $this->db->select('id_utilisateur, id_profil')
            ->from('utilisateur_profils')
            ->get()
            ->result_array();
        
        $data['user_roles'] = [];
        foreach ($user_roles as $ur) {
            if (!isset($data['user_roles'][$ur['id_utilisateur']])) {
                $data['user_roles'][$ur['id_utilisateur']] = [];
            }
            $data['user_roles'][$ur['id_utilisateur']][] = $ur['id_profil'];
        }
        
        $data['total_utilisateurs'] = count($data['utilisateurs']);
        $data['total_profils'] = count($data['profils']);
        
        $this->load->view('Roles_View', $data);
    }

    public function assigner()
    {
        $user_id = $this->input->post('user_id');
        $profil_ids = $this->input->post('profil_ids');
        
        if (!$user_id) {
            $this->session->set_flashdata('error', 'Utilisateur non spécifié.');
            redirect(base_url('Roles'));
            return;
        }
        
        // Vérifier si l'utilisateur existe
        $user = $this->Model->readOne('utilisateurs', ['id_utilisateur' => $user_id]);
        if (!$user) {
            $this->session->set_flashdata('error', 'Utilisateur introuvable.');
            redirect(base_url('Roles'));
            return;
        }
        
        // Supprimer les anciens rôles
        $this->db->delete('utilisateur_profils', ['id_utilisateur' => $user_id]);
        
        // Ajouter les nouveaux rôles
        if (!empty($profil_ids) && is_array($profil_ids)) {
            foreach ($profil_ids as $profil_id) {
                // Vérifier si le profil existe
                $profil = $this->Model->readOne('profils', ['id_profil' => $profil_id]);
                if ($profil) {
                    $this->db->insert('utilisateur_profils', [
                        'id_utilisateur' => $user_id,
                        'id_profil' => $profil_id,
                        'attribue_par' => $this->session->userdata('id_utilisateur'),
                        'date_attribution' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }
        
        $this->session->set_flashdata('success', 'Rôles assignés avec succès à ' . $user['prenom'] . ' ' . $user['nom']);
        redirect(base_url('Roles'));
    }

    public function get_user_roles($user_id)
    {
        $roles = $this->db->select('id_profil')
            ->from('utilisateur_profils')
            ->where('id_utilisateur', $user_id)
            ->get()
            ->result_array();
        
        $result = [];
        foreach ($roles as $r) {
            $result[] = $r['id_profil'];
        }
        
        echo json_encode($result);
    }

    public function ajouter_profil()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $description = $this->input->post('description');
            $permissions = $this->input->post('permissions');
            
            if (empty($description)) {
                $this->session->set_flashdata('error', 'La description du profil est requise.');
                redirect(base_url('Roles/ajouter_profil'));
                return;
            }
            
            // Vérifier si le profil existe déjà
            $existing = $this->Model->readOne('profils', ['description' => $description]);
            if ($existing) {
                $this->session->set_flashdata('error', 'Ce profil existe déjà.');
                redirect(base_url('Roles/ajouter_profil'));
                return;
            }
            
            $data = [
                'description' => $description,
                'permissions' => json_encode($permissions ?? []),
                'est_actif' => $this->input->post('est_actif') ? 1 : 0,
                'date_creation' => date('Y-m-d H:i:s')
            ];
            
            $result = $this->Model->create('profils', $data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Profil créé avec succès.');
                redirect(base_url('Roles'));
            } else {
                $this->session->set_flashdata('error', 'Erreur lors de la création du profil.');
                redirect(base_url('Roles/ajouter_profil'));
            }
        }
        
        $data['profil'] = null;
        $this->load->view('Roles_View', $data);
    }

    public function modifier_profil($id)
    {
        $profil = $this->Model->readOne('profils', ['id_profil' => $id]);
        
        if (!$profil) {
            $this->session->set_flashdata('error', 'Profil non trouvé.');
            redirect(base_url('Roles'));
        }
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $description = $this->input->post('description');
            $permissions = $this->input->post('permissions');
            
            if (empty($description)) {
                $this->session->set_flashdata('error', 'La description du profil est requise.');
                redirect(base_url('Roles/modifier_profil/' . $id));
                return;
            }
            
            $data = [
                'description' => $description,
                'permissions' => json_encode($permissions ?? []),
                'est_actif' => $this->input->post('est_actif') ? 1 : 0,
                'date_modification' => date('Y-m-d H:i:s')
            ];
            
            $result = $this->Model->update('profils', ['id_profil' => $id], $data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Profil mis à jour avec succès.');
                redirect(base_url('Roles'));
            } else {
                $this->session->set_flashdata('error', 'Erreur lors de la mise à jour du profil.');
                redirect(base_url('Roles/modifier_profil/' . $id));
            }
        }
        
        $data['profil'] = $profil;
        $data['permissions_list'] = json_decode($profil['permissions'], true) ?? [];
        $this->load->view('Roles_View', $data);
    }

    public function supprimer_profil($id)
    {
        // Vérifier si des utilisateurs sont associés à ce profil
        $users_count = $this->Model->count('utilisateur_profils', ['id_profil' => $id]);
        
        if ($users_count > 0) {
            $this->session->set_flashdata('error', 'Impossible de supprimer ce profil car ' . $users_count . ' utilisateur(s) y sont associés.');
            redirect(base_url('Roles'));
        }
        
        // Empêcher la suppression des profils système
        $profil = $this->Model->readOne('profils', ['id_profil' => $id]);
        $system_profiles = ['super_admin', 'admin', 'vendeur', 'client', 'livreur', 'finance', 'support', 'moderateur'];
        
        if (in_array($profil['description'], $system_profiles)) {
            $this->session->set_flashdata('error', 'Impossible de supprimer un profil système.');
            redirect(base_url('Roles'));
        }
        
        $result = $this->Model->delete('profils', ['id_profil' => $id]);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Profil supprimé avec succès.');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la suppression du profil.');
        }
        
        redirect(base_url('Roles'));
    }
    public function get_user_details($user_id)
{
    $user = $this->Model->readOne('utilisateurs', ['id_utilisateur' => $user_id]);
    if (!$user) {
        echo json_encode(['error' => 'Utilisateur non trouvé']);
        return;
    }
    
    // Récupérer les rôles de l'utilisateur
    $roles = $this->db->select('p.description, p.id_profil')
        ->from('utilisateur_profils up')
        ->join('profils p', 'p.id_profil = up.id_profil')
        ->where('up.id_utilisateur', $user_id)
        ->get()
        ->result_array();
    
    echo json_encode([
        'user' => $user,
        'roles' => $roles
    ]);
}

// Modifier un utilisateur
public function modifier_utilisateur()
{
    $user_id = $this->input->post('user_id');
    $data = [
        'prenom' => $this->input->post('prenom'),
        'nom' => $this->input->post('nom'),
        'email' => $this->input->post('email'),
        'telephone' => $this->input->post('telephone'),
        'est_actif' => $this->input->post('est_actif')
    ];
    
    // Changer le mot de passe si fourni
    $new_password = $this->input->post('new_password');
    if (!empty($new_password)) {
        if (strlen($new_password) >= 6) {
            $data['mot_de_passe'] = md5($new_password);
        } else {
            $this->session->set_flashdata('error', 'Le mot de passe doit contenir au moins 6 caractères.');
            redirect(base_url('Roles'));
            return;
        }
    }
    
    $this->Model->update('utilisateurs', ['id_utilisateur' => $user_id], $data);
    $this->session->set_flashdata('success', 'Utilisateur modifié avec succès.');
    redirect(base_url('Roles'));
}

// Supprimer un utilisateur
public function supprimer_utilisateur($user_id)
{
    // Vérifier si l'utilisateur existe
    $user = $this->Model->readOne('utilisateurs', ['id_utilisateur' => $user_id]);
    if (!$user) {
        $this->session->set_flashdata('error', 'Utilisateur non trouvé.');
        redirect(base_url('Roles'));
        return;
    }
    
    // Empêcher la suppression de son propre compte
    if ($user_id == $this->session->userdata('id_utilisateur')) {
        $this->session->set_flashdata('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        redirect(base_url('Roles'));
        return;
    }
    
    // Supprimer les relations utilisateur_profils
    $this->db->delete('utilisateur_profils', ['id_utilisateur' => $user_id]);
    
    // Supprimer l'utilisateur
    $this->Model->delete('utilisateurs', ['id_utilisateur' => $user_id]);
    
    $this->session->set_flashdata('success', 'Utilisateur supprimé avec succès.');
    redirect(base_url('Roles'));
}
}
?>