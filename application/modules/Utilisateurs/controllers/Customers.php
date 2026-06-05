<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @author:    dushime paul
 * Email:     dushimeyesupaulin@gmail.com
 * Date :     Le 20/01/2026
 */

class Customers extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') !== TRUE) {
            redirect('Admin');
        }
    }

    // Liste des clients
    public function index()
    {
        // Récupérer l'ID du profil client (id_profil = 5)
        $client_profil_id = 5;
        
        // Récupérer tous les utilisateurs qui ont le profil client
        $data['customers'] = $this->db->select('u.*')
            ->from('utilisateurs u')
            ->join('utilisateur_profils up', 'up.id_utilisateur = u.id_utilisateur')
            ->where('up.id_profil', $client_profil_id)
            ->group_by('u.id_utilisateur')
            ->order_by('u.id_utilisateur', 'DESC')
            ->get()
            ->result_array();
        
        // Statistiques
        $data['total_customers'] = count($data['customers']);
        $data['total_orders'] = $this->db->select('COUNT(*) as total')
            ->from('commandes c')
            ->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur')
            ->join('utilisateur_profils up', 'up.id_utilisateur = u.id_utilisateur')
            ->where('up.id_profil', $client_profil_id)
            ->get()
            ->row()->total ?? 0;
        
        $data['total_revenue'] = $this->db->select('SUM(c.montant_total) as total')
            ->from('commandes c')
            ->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur')
            ->join('utilisateur_profils up', 'up.id_utilisateur = u.id_utilisateur')
            ->where('up.id_profil', $client_profil_id)
            ->where('c.statut_commande', 'livre')
            ->get()
            ->row()->total ?? 0;
        
        $data['active_customers'] = $this->db->select('COUNT(*) as total')
            ->from('utilisateurs u')
            ->join('utilisateur_profils up', 'up.id_utilisateur = u.id_utilisateur')
            ->where('up.id_profil', $client_profil_id)
            ->where('u.est_actif', 1)
            ->get()
            ->row()->total ?? 0;
        
        $this->load->view('customer_list', $data);
    }

    // Détails d'un client
    public function view($id)
    {
        // Récupérer les informations du client
        $data['customer'] = $this->db->select('u.*')
            ->from('utilisateurs u')
            ->join('utilisateur_profils up', 'up.id_utilisateur = u.id_utilisateur')
            ->where('up.id_profil', 5)
            ->where('u.id_utilisateur', $id)
            ->get()
            ->row_array();
        
        if (!$data['customer']) {
            $this->session->set_flashdata('error', 'Client non trouvé.');
            redirect(base_url('Customers'));
        }
        
        // Récupérer les commandes du client
        $data['orders'] = $this->db->select('*')
            ->from('commandes')
            ->where('id_utilisateur', $id)
            ->order_by('date_creation', 'DESC')
            ->get()
            ->result_array();
        
        // Statistiques des commandes
        $data['total_orders'] = count($data['orders']);
        $data['total_spent'] = $this->db->select_sum('montant_total')
            ->from('commandes')
            ->where('id_utilisateur', $id)
            ->where('statut_commande', 'livre')
            ->get()
            ->row()->montant_total ?? 0;
        
        // Récupérer les adresses du client
        $data['addresses'] = $this->db->select('*')
            ->from('adresses')
            ->where('id_utilisateur', $id)
            ->get()
            ->result_array();
        
        // Récupérer les avis du client
        $data['reviews'] = $this->db->select('ap.*, p.nom_produit')
            ->from('avis_produits ap')
            ->join('produits p', 'p.id_produit = ap.id_produit')
            ->where('ap.id_utilisateur', $id)
            ->order_by('ap.date_creation', 'DESC')
            ->limit(5)
            ->get()
            ->result_array();
        
        // Dernière commande
        $data['last_order'] = !empty($data['orders']) ? $data['orders'][0] : null;
        
        $this->load->view('customer_detail', $data);
    }

    // Modifier un client
    public function edit($id)
    {
        $data['customer'] = $this->db->select('u.*')
            ->from('utilisateurs u')
            ->join('utilisateur_profils up', 'up.id_utilisateur = u.id_utilisateur')
            ->where('up.id_profil', 5)
            ->where('u.id_utilisateur', $id)
            ->get()
            ->row_array();
        
        if (!$data['customer']) {
            $this->session->set_flashdata('error', 'Client non trouvé.');
            redirect(base_url('Customers'));
        }
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $update_data = [
                'prenom' => $this->input->post('prenom'),
                'nom' => $this->input->post('nom'),
                'email' => $this->input->post('email'),
                'telephone' => $this->input->post('telephone'),
                'est_actif' => $this->input->post('est_actif') ? 1 : 0,
                'date_modification' => date('Y-m-d H:i:s')
            ];
            
            // Gestion de l'avatar
            if (!empty($_FILES['avatar']['name'])) {
                $upload = $this->upload_image($_FILES['avatar']['tmp_name'], $_FILES['avatar']['name']);
                if ($upload) {
                    if ($data['customer']['avatar_url'] && file_exists(FCPATH . $data['customer']['avatar_url'])) {
                        unlink(FCPATH . $data['customer']['avatar_url']);
                    }
                    $update_data['avatar_url'] = 'attachments/Users/' . $upload;
                }
            }
            
            // Changer le mot de passe si fourni
            $new_password = $this->input->post('new_password');
            if (!empty($new_password)) {
                if (strlen($new_password) >= 6) {
                    $update_data['mot_de_passe'] = password_hash($new_password, PASSWORD_BCRYPT);
                } else {
                    $this->session->set_flashdata('error', 'Le mot de passe doit contenir au moins 6 caractères.');
                    redirect(base_url('Customers/edit/' . $id));
                    return;
                }
            }
            
            $this->Model->update('utilisateurs', ['id_utilisateur' => $id], $update_data);
            $this->session->set_flashdata('success', 'Client modifié avec succès.');
            redirect(base_url('Customers/view/' . $id));
        }
        
        $this->load->view('customer_edit', $data);
    }

    // Supprimer un client
    public function delete($id)
    {
        $customer = $this->db->select('u.*')
            ->from('utilisateurs u')
            ->join('utilisateur_profils up', 'up.id_utilisateur = u.id_utilisateur')
            ->where('up.id_profil', 5)
            ->where('u.id_utilisateur', $id)
            ->get()
            ->row_array();
        
        if (!$customer) {
            $this->session->set_flashdata('error', 'Client non trouvé.');
            redirect(base_url('Customers'));
        }
        
        // Supprimer l'avatar
        if ($customer['avatar_url'] && file_exists(FCPATH . $customer['avatar_url'])) {
            unlink(FCPATH . $customer['avatar_url']);
        }
        
        // Supprimer les relations
        $this->db->delete('utilisateur_profils', ['id_utilisateur' => $id]);
        $this->Model->delete('utilisateurs', ['id_utilisateur' => $id]);
        
        $this->session->set_flashdata('success', 'Client supprimé avec succès.');
        redirect(base_url('Customers'));
    }

    // Activer/Désactiver un client
    public function toggle_status($id)
    {
        $customer = $this->db->select('u.*')
            ->from('utilisateurs u')
            ->join('utilisateur_profils up', 'up.id_utilisateur = u.id_utilisateur')
            ->where('up.id_profil', 5)
            ->where('u.id_utilisateur', $id)
            ->get()
            ->row_array();
        
        if (!$customer) {
            $this->session->set_flashdata('error', 'Client non trouvé.');
            redirect(base_url('Customers'));
        }
        
        $new_status = $customer['est_actif'] == 1 ? 0 : 1;
        $this->Model->update('utilisateurs', ['id_utilisateur' => $id], ['est_actif' => $new_status]);
        
        $this->session->set_flashdata('success', 'Statut du client modifié avec succès.');
        redirect(base_url('Customers'));
    }

    // Upload d'image
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
}
?>