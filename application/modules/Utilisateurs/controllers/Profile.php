<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') !== TRUE) {
            redirect('Admin');
        }
        if ($this->session->userdata('role') === 'vendeur') {
            redirect('Home/User_dashboard');
        }
    }

    public function index()
    {
        $user_id = $this->session->userdata('id_utilisateur');
        $user = $this->Model->readOne('utilisateurs', ['id_utilisateur' => $user_id]);
        
        $avatar_url = $user['avatar_url'];
        if ($avatar_url && !file_exists(FCPATH . $avatar_url)) {
            $avatar_url = null;
        }
        $this->session->set_userdata('avatar_url', $avatar_url);
        
        $data = [
            'prenom' => $user['prenom'],
            'nom' => $user['nom'],
            'email' => $user['email'],
            'telephone' => $user['telephone'],
            'avatar_url' => $avatar_url,
            'role' => $this->session->userdata('role'),
            'est_actif' => $user['est_actif'],
            'email_verifie' => $user['email_verifie'],
            'telephone_verifie' => $user['telephone_verifie'],
            'date_creation' => $user['date_creation'],
            'derniere_connexion' => $user['derniere_connexion']
        ];
        
        $this->load->view('Profile_View', $data);
    }

    public function update()
    {
        $user_id = $this->session->userdata('id_utilisateur');
        $data = [
            'prenom' => $this->input->post('prenom'),
            'nom' => $this->input->post('nom'),
            'email' => $this->input->post('email'),
            'telephone' => $this->input->post('telephone') ?: null
        ];

        if (!empty($_FILES['avatar']['name'])) {
            $config['upload_path'] = './attachments/Users/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
            $config['max_size'] = 2048;
            $config['encrypt_name'] = true;
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }
            $this->load->library('upload');
            $this->upload->initialize($config);
            if ($this->upload->do_upload('avatar')) {
                $data['avatar_url'] = 'attachments/Users/' . $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', 'Erreur upload: ' . $this->upload->display_errors('', ''));
                redirect('Profile');
            }
        }
        
        $this->Model->update('utilisateurs', ['id_utilisateur' => $user_id], $data);
        
        // Mettre à jour la session
        $this->session->set_userdata('prenom', $data['prenom']);
        $this->session->set_userdata('nom', $data['nom']);
        $this->session->set_userdata('email', $data['email']);
        if (isset($data['avatar_url'])) {
            $this->session->set_userdata('avatar_url', $data['avatar_url']);
        }
        
        $this->session->set_flashdata('success', 'Profil mis à jour avec succès.');
        redirect('Profile');
    }

    public function changepassword()
    {
        $user_id = $this->session->userdata('id_utilisateur');
        $current = $this->input->post('current_password');
        $new = $this->input->post('new_password');
        $confirm = $this->input->post('confirm_password');
        
        $user = $this->Model->readOne('utilisateurs', ['id_utilisateur' => $user_id]);
        
        $password_correct = false;
        if (password_verify($current, $user['mot_de_passe'])) {
            $password_correct = true;
        } elseif (strlen($user['mot_de_passe']) === 32 && md5($current) === $user['mot_de_passe']) {
            $password_correct = true;
        }

        if (!$password_correct) {
            $this->session->set_flashdata('error', 'Mot de passe actuel incorrect.');
            redirect('Profile');
        }
        
        if ($new != $confirm) {
            $this->session->set_flashdata('error', 'Les mots de passe ne correspondent pas.');
            redirect('Profile');
        }
        
        if (strlen($new) < 6) {
            $this->session->set_flashdata('error', 'Le mot de passe doit contenir au moins 6 caractères.');
            redirect('Profile');
        }
        
        $this->Model->update('utilisateurs', ['id_utilisateur' => $user_id], ['mot_de_passe' => password_hash($new, PASSWORD_BCRYPT)]);
        
        $this->session->set_flashdata('success', 'Mot de passe changé avec succès.');
        redirect('Profile');
    }
}