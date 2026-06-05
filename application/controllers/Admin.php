<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author:    dushime paul
 * Email:     dushimeyesupaulin@gmail.com
 * Date :     Le 20/01/2026
 * https://github.com/Dushimepaul
*/

class Admin extends MY_Controller {

    public function index()
    {
        $this->load->view('Login_View');
    }

    public function Login(){
        $this->load->view('Dashboard/Dashboard_View');
    }

    public function do_login()
    {
        // Récupération des données du formulaire
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        
        // Validation des champs
        if (empty($email) || empty($password)) {
            $this->session->set_flashdata('sms', 
                '<div class="alert alert-danger mt-1 message">
                    <strong>Oups!</strong> Veuillez remplir tous les champs.
                </div>'
            );
            redirect(base_url('Admin'));
            return;
        }
        
        // Vérifier si l'email existe
        $user = $this->Model->getUserByEmail($email);
        
        if ($user) {
            // Vérifier si le compte est actif
            if ($user['est_actif'] == 0) {
                $this->session->set_flashdata('sms', 
                    '<div class="alert alert-danger mt-1 message">
                        <strong>Oups!</strong> Votre compte est désactivé. Veuillez contacter l\'administrateur.
                    </div>'
                );
                redirect(base_url('Admin'));
                return;
            }
            
            // Vérifier si l'utilisateur est banni
            if ($user['est_banni'] == 1) {
                $this->session->set_flashdata('sms', 
                    '<div class="alert alert-danger mt-1 message">
                        <strong>Oups!</strong> Votre compte a été banni. Motif: ' . $user['motif_bannissement'] . '
                    </div>'
                );
                redirect(base_url('Admin'));
                return;
            }
            
            // Vérifier le mot de passe (BCRYPT avec transition transparente depuis MD5)
            $password_correct = false;
            if (password_verify($password, $user['mot_de_passe'])) {
                $password_correct = true;
            } elseif (strlen($user['mot_de_passe']) === 32 && md5($password) === $user['mot_de_passe']) {
                $password_correct = true;
                // Transition transparente vers BCRYPT
                $this->db->where('id_utilisateur', $user['id_utilisateur'])
                    ->update('utilisateurs', array('mot_de_passe' => password_hash($password, PASSWORD_BCRYPT)));
            }
            
            if ($password_correct) {
                // Récupérer les profils de l'utilisateur
                $user_profiles = $this->Model->getUserProfiles($user['id_utilisateur']);
                
                // Récupérer les informations du vendeur si l'utilisateur est un vendeur
                $vendeur_info = $this->Model->getVendeurByUserId($user['id_utilisateur']);
                
                // Récupérer les informations du transporteur si l'utilisateur est un livreur
                $transporteur_info = $this->Model->getTransporteurByUserId($user['id_utilisateur']);
                
                // Déterminer le rôle principal
                $role = 'client'; // rôle par défaut
                $role_id = null;
                $permissions = [];
                
                if (!empty($user_profiles)) {
                    // Prendre le premier profil comme rôle principal
                    $role_id = $user_profiles[0]['id_profil'];
                    $role = $user_profiles[0]['description'];
                    $permissions = json_decode($user_profiles[0]['permissions'], true);
                }
                
                // Création de la session
                $session_data = array(
                    'id_utilisateur' => $user['id_utilisateur'],
                    'email' => $user['email'],
                    'prenom' => $user['prenom'],
                    'nom' => $user['nom'],
                    'nom_complet' => $user['prenom'] . ' ' . $user['nom'],
                    'telephone' => $user['telephone'],
                    'avatar_url' => $user['avatar_url'],
                    'role' => $role,
                    'role_id' => $role_id,
                    'permissions' => $permissions,
                    'est_vendeur' => !empty($vendeur_info),
                    'est_transporteur' => !empty($transporteur_info),
                    'logged_in' => TRUE
                );
                
                // Ajouter les infos vendeur si disponibles
                if (!empty($vendeur_info)) {
                    $session_data['id_vendeur'] = $vendeur_info['id_vendeur'];
                    $session_data['nom_boutique'] = $vendeur_info['nom_boutique'];
                    $session_data['slug_boutique'] = $vendeur_info['slug_boutique'];
                    $session_data['statut_vendeur'] = $vendeur_info['statut'];
                    $session_data['est_vendeur_approuve'] = $vendeur_info['est_approuve'];
                }
                
                // Ajouter les infos transporteur si disponibles
                if (!empty($transporteur_info)) {
                    $session_data['id_transporteur'] = $transporteur_info['id_transporteur'];
                    $session_data['type_transporteur'] = $transporteur_info['type'];
                    $session_data['statut_transporteur'] = $transporteur_info['statut'];
                }
                
                $this->session->set_userdata($session_data);
                
                // Mettre à jour la dernière connexion
                $this->Model->updateLastLogin($user['id_utilisateur']);
                
                // Journaliser la connexion réussie
                $this->Model->logConnexionAttempt($user['id_utilisateur'], $email, $this->input->ip_address(), true);
                
                // Message de succès
                $this->session->set_flashdata('sms', 
                    '<div class="alert alert-success mt-1 message">
                        <strong>Succès!</strong> Bienvenue ' . $user['prenom'] . ' ' . $user['nom'] . '
                    </div>'
                );
                
                // Redirection selon le rôle
                if ($role === 'super_admin' || $role === 'admin') {
                    redirect(base_url('Dashboard'));
                } elseif ($role === 'vendeur' && !empty($vendeur_info) && $vendeur_info['est_approuve'] == 1) {
                    redirect(base_url('Vendeur/Dashboard'));
                } elseif ($role === 'livreur' && !empty($transporteur_info)) {
                    redirect(base_url('Livreur/Dashboard'));
                } elseif ($role === 'finance') {
                    redirect(base_url('Finance/Dashboard'));
                } elseif ($role === 'support') {
                    redirect(base_url('Support/Dashboard'));
                } else {
                    redirect(base_url('Client/Dashboard'));
                }
                
            } else {
                // Mot de passe incorrect
                $this->Model->logConnexionAttempt($user['id_utilisateur'], $email, $this->input->ip_address(), false, 'Mot de passe incorrect');
                
                $this->session->set_flashdata('sms', 
                    '<div class="alert alert-danger mt-1 message">
                        <strong>Oups!</strong> Mot de passe incorrect.
                    </div>'
                );
                redirect(base_url('Admin'));
            }
        } else {
            // Email non trouvé
            $this->Model->logConnexionAttempt(null, $email, $this->input->ip_address(), false, 'Email non trouvé');
            
            $this->session->set_flashdata('sms', 
                '<div class="alert alert-danger mt-1 message">
                    <strong>Oups!</strong> Aucun compte trouvé avec cet email.
                </div>'
            );
            redirect(base_url('Admin'));
        }
    }

    public function logout()
    {
        // Journaliser la déconnexion
        if ($this->session->userdata('logged_in')) {
            $this->Model->logConnexionAttempt(
                $this->session->userdata('id_utilisateur'), 
                $this->session->userdata('email'), 
                $this->input->ip_address(), 
                false, 
                'Déconnexion volontaire'
            );
        }
        
        // Détruire toutes les données de session
        $session_data = array(
            'id_utilisateur',
            'email',
            'prenom',
            'nom',
            'nom_complet',
            'telephone',
            'avatar_url',
            'role',
            'role_id',
            'permissions',
            'est_vendeur',
            'est_transporteur',
            'id_vendeur',
            'nom_boutique',
            'slug_boutique',
            'statut_vendeur',
            'est_vendeur_approuve',
            'id_transporteur',
            'type_transporteur',
            'statut_transporteur',
            'logged_in'
        );
        
        $this->session->unset_userdata($session_data);
        
        // Détruire complètement la session
        $this->session->sess_destroy();
        
        // Message de déconnexion
        $this->session->set_flashdata('sms', 
            '<div class="alert alert-success text-center">
                <strong>Déconnexion réussie !</strong> À bientôt.
            </div>'
        );
        
        redirect(base_url('Admin'));
    }
}