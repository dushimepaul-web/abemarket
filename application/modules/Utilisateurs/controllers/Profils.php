<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @author:    dushime paul
 * Email:     dushimeyesupaulin@gmail.com
 * Date :     Le 20/01/2026
 * https://github.com/Dushimepaul
 */

class Profils extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Vérifier si l'utilisateur est connecté
        if ($this->session->userdata('logged_in') !== TRUE) {
            redirect('Admin');
        }
        // Vérifier les permissions (seul super_admin et admin peuvent gérer les profils)
        $role = $this->session->userdata('role');
        if ($role !== 'super_admin' && $role !== 'admin') {
            show_error('Vous n\'avez pas les permissions nécessaires pour accéder à cette page.', 403);
        }
    }

    public function index()
    {
        // Récupérer tous les profils
        $data['profils'] = $this->Model->read('profils', [], 'id_profil', 'ASC');
        
        // Récupérer les statistiques
        $data['total_profils'] = $this->Model->count('profils');
        $data['total_utilisateurs'] = $this->Model->count('utilisateurs');
        
        // Compter les utilisateurs par profil
        $user_profiles_count = $this->db->select('id_profil, COUNT(*) as total')
            ->from('utilisateur_profils')
            ->group_by('id_profil')
            ->get()
            ->result_array();
        
        $data['user_count_by_profil'] = [];
        foreach ($user_profiles_count as $uc) {
            $data['user_count_by_profil'][$uc['id_profil']] = $uc['total'];
        }
        
        $this->load->view('Profils_View', $data);
    }

    public function add()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $description = $this->input->post('description');
            $permissions = $this->input->post('permissions');
            
            // Validation
            if (empty($description)) {
                $this->session->set_flashdata('error', 'La description du profil est requise.');
                redirect(base_url('Profils/add'));
                return;
            }
            
            // Vérifier si le profil existe déjà
            $existing = $this->Model->readOne('profils', ['description' => $description]);
            if ($existing) {
                $this->session->set_flashdata('error', 'Ce profil existe déjà.');
                redirect(base_url('Profils/add'));
                return;
            }
            
            // Préparer les données
            $data = [
                'description' => $description,
                'permissions' => json_encode($permissions ?? []),
                'est_actif' => $this->input->post('est_actif') ? 1 : 0,
                'date_creation' => date('Y-m-d H:i:s')
            ];
            
            $result = $this->Model->create('profils', $data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Profil créé avec succès.');
                redirect(base_url('Profils'));
            } else {
                $this->session->set_flashdata('error', 'Erreur lors de la création du profil.');
                redirect(base_url('Profils/add'));
            }
        }
        
        // Afficher le formulaire d'ajout
        $data['profil'] = null;
        $this->load->view('Profils_View', $data);
    }

    public function edit($id)
    {
        $profil = $this->Model->readOne('profils', ['id_profil' => $id]);
        
        if (!$profil) {
            $this->session->set_flashdata('error', 'Profil non trouvé.');
            redirect(base_url('Profils'));
        }
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $description = $this->input->post('description');
            $permissions = $this->input->post('permissions');
            
            if (empty($description)) {
                $this->session->set_flashdata('error', 'La description du profil est requise.');
                redirect(base_url('Profils/edit/' . $id));
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
                redirect(base_url('Profils'));
            } else {
                $this->session->set_flashdata('error', 'Erreur lors de la mise à jour du profil.');
                redirect(base_url('Profils/edit/' . $id));
            }
        }
        
        $data['profil'] = $profil;
        $data['permissions_list'] = json_decode($profil['permissions'], true) ?? [];
        $this->load->view('Profils_View', $data);
    }

    public function delete($id)
    {
        // Vérifier si des utilisateurs sont associés à ce profil
        $users_count = $this->Model->count('utilisateur_profils', ['id_profil' => $id]);
        
        if ($users_count > 0) {
            $this->session->set_flashdata('error', 'Impossible de supprimer ce profil car ' . $users_count . ' utilisateur(s) y sont associés.');
            redirect(base_url('Profils'));
        }
        
        // Empêcher la suppression des profils système
        $profil = $this->Model->readOne('profils', ['id_profil' => $id]);
        $system_profiles = ['super_admin', 'admin', 'vendeur', 'client', 'livreur'];
        
        if (in_array($profil['description'], $system_profiles)) {
            $this->session->set_flashdata('error', 'Impossible de supprimer un profil système.');
            redirect(base_url('Profils'));
        }
        
        $result = $this->Model->delete('profils', ['id_profil' => $id]);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Profil supprimé avec succès.');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la suppression du profil.');
        }
        
        redirect(base_url('Profils'));
    }

    public function view($id)
    {
        $data['profil'] = $this->Model->readOne('profils', ['id_profil' => $id]);
        
        if (!$data['profil']) {
            $this->session->set_flashdata('error', 'Profil non trouvé.');
            redirect(base_url('Profils'));
        }
        
        // Récupérer les utilisateurs ayant ce profil
        $data['users'] = $this->db->select('u.*')
            ->from('utilisateurs u')
            ->join('utilisateur_profils up', 'up.id_utilisateur = u.id_utilisateur')
            ->where('up.id_profil', $id)
            ->get()
            ->result_array();
        
        $data['permissions'] = json_decode($data['profil']['permissions'], true) ?? [];
        $data['total_users'] = count($data['users']);
        
        $this->load->view('Profils_View', $data);
    }
}
?>