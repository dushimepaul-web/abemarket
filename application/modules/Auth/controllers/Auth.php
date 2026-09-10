<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->library('form_validation');
        $this->load->helper('email');
        $this->load->library('Cpanel_email_lib');
    }
    
    // ============================================
    // PAGE DE CONNEXION UTILISATEUR
    // ============================================
    
    public function login_page() {
        $data['settings'] = [];
        $this->load->view('auth/login_user_view', $data);
    }

    // ============================================
    // CONNEXION (AJAX)
    // ============================================
    
    public function login() {
        // Si accès GET (navigateur), rediriger vers l'accueil
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect(base_url());
            return;
        }

        $this->output->set_content_type('application/json');
        $ip_address = $this->input->ip_address();

        // Rate limiting: max 5 tentatives échouées en 15 minutes
        $recent_attempts = $this->Auth_model->checkRecentAttempts($ip_address, 15);
        if ($recent_attempts >= 5) {
            echo json_encode(['success' => false, 'message' => 'Trop de tentatives. Veuillez réessayer dans 15 minutes.']);
            return;
        }

        // IP Blacklist
        $blacklisted = $this->db->where('adresse_ip', $ip_address)
            ->where('(date_fin IS NULL OR date_fin > NOW())')
            ->get('blacklist_ips')
            ->row();

        $is_development = defined('ENVIRONMENT') && ENVIRONMENT === 'development';
        if ($blacklisted && !$is_development) {
            echo json_encode(['success' => false, 'message' => 'Accès refusé.']);
            return;
        }

        if ($this->session->userdata('logged_in')) {
            $redirect_url = $this->getDashboardRedirect($this->session->userdata('role'));
            echo json_encode(['success' => true, 'message' => 'Vous êtes déjà connecté', 'redirect' => $redirect_url]);
            return;
        }

        $email = trim($this->input->post('email'));
        $password = $this->input->post('password');

        if (empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs']);
            return;
        }

        if (!valid_email($email)) {
            echo json_encode(['success' => false, 'message' => 'Email invalide']);
            return;
        }

        $user = $this->Auth_model->getUserByEmail($email);

        // Message générique pour éviter la user enumeration
        if (!$user) {
            $this->Auth_model->logConnexionAttempt(null, $email, $ip_address, false, 'Email non trouvé');
            echo json_encode(['success' => false, 'message' => 'Email ou mot de passe incorrect']);
            return;
        }

        if ($user['est_actif'] == 0) {
            echo json_encode(['success' => false, 'message' => 'Compte désactivé. Contactez l\'administrateur.']);
            return;
        }

        if ($user['est_banni'] == 1) {
            echo json_encode(['success' => false, 'message' => 'Votre compte a été suspendu.']);
            $this->Auth_model->logConnexionAttempt($user['id_utilisateur'], $email, $ip_address, false, 'Compte banni');
            return;
        }

        // Vérifier le profil - rejeter admin/super_admin (login admin séparé)
        $profiles = $this->Auth_model->getUserProfiles($user['id_utilisateur']);
        if (!empty($profiles)) {
            $role = $profiles[0]['description'];
            if (in_array($role, ['super_admin', 'admin'])) {
                echo json_encode(['success' => false, 'message' => 'Utilisez le portail administrateur pour vous connecter.']);
                return;
            }
        }

        // Vérifier le mot de passe (MD5)
        if (md5($password) !== $user['mot_de_passe']) {
            $this->Auth_model->logConnexionAttempt($user['id_utilisateur'], $email, $ip_address, false, 'Mot de passe incorrect');
            echo json_encode(['success' => false, 'message' => 'Email ou mot de passe incorrect']);
            return;
        }

        // Régénérer la session pour éviter la session fixation
        $this->session->sess_regenerate(TRUE);

        $role = !empty($profiles) ? $profiles[0]['description'] : 'client';
        $permissions = !empty($profiles) ? json_decode($profiles[0]['permissions'], true) : [];

        $session_data = array(
            'user_id' => $user['id_utilisateur'],
            'id_utilisateur' => $user['id_utilisateur'],
            'email' => $user['email'],
            'prenom' => $user['prenom'],
            'nom' => $user['nom'],
            'nom_complet' => $user['prenom'] . ' ' . $user['nom'],
            'telephone' => $user['telephone'],
            'avatar_url' => $user['avatar_url'],
            'role' => $role,
            'permissions' => $permissions,
            'logged_in' => true
        );

        $this->session->set_userdata($session_data);
        $this->Auth_model->updateLastLogin($user['id_utilisateur']);
        $this->Auth_model->logConnexionAttempt($user['id_utilisateur'], $email, $ip_address, true);

        $redirect_url = $this->getDashboardRedirect($role);

        echo json_encode([
            'success' => true,
            'message' => 'Connexion réussie !',
            'redirect' => $redirect_url
        ]);
    }
    

    
    // ============================================
    // INSCRIPTION AVEC ENVOI DE CODE OTP (utilisant Mailer)
    // ============================================
    
    // ============================================
    // PAGE D'INSCRIPTION UTILISATEUR
    // ============================================
    
    public function register_page() {
        $data['settings'] = [];
        $this->load->view('auth/register_user_view', $data);
    }

    // ============================================
    // INSCRIPTION (AJAX)
    // ============================================
    
    public function register() {
    // Si accès GET (navigateur), rediriger vers l'accueil
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
        redirect(base_url());
        return;
    }

    $this->output->set_content_type('application/json');
    $prenom = trim($this->input->post('prenom'));
    $nom = trim($this->input->post('nom'));
    $email = trim($this->input->post('email'));
    $telephone = trim($this->input->post('telephone'));
    $password = $this->input->post('password');
    $confirm_password = $this->input->post('confirm_password');
    $agree_terms = $this->input->post('agree_terms');

    if (empty($prenom) || empty($nom) || empty($email) || empty($telephone) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Tous les champs sont obligatoires']);
        return;
    }

    if (!valid_email($email)) {
        echo json_encode(['success' => false, 'message' => 'Email invalide']);
        return;
    }

    if (strlen($password) < 8) {
        echo json_encode(['success' => false, 'message' => 'Le mot de passe doit contenir au moins 8 caractères']);
        return;
    }

    if ($password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'Les mots de passe ne correspondent pas']);
        return;
    }

    if (!$agree_terms) {
        echo json_encode(['success' => false, 'message' => 'Vous devez accepter les conditions d\'utilisation']);
        return;
    }

    // Messages génériques pour éviter la user enumeration
    if ($this->Auth_model->getUserByEmail($email)) {
        echo json_encode(['success' => false, 'message' => 'Email ou téléphone déjà utilisé']);
        return;
    }

    if ($this->Auth_model->getUserByPhone($telephone)) {
        echo json_encode(['success' => false, 'message' => 'Email ou téléphone déjà utilisé']);
        return;
    }

    $user_data = array(
        'email' => $email,
        'mot_de_passe' => md5($password),
        'prenom' => $prenom,
        'nom' => $nom,
        'telephone' => $telephone,
        'email_verifie' => 0,
        'telephone_verifie' => 0,
        'est_actif' => 1,
        'est_banni' => 0,
        'date_creation' => date('Y-m-d H:i:s')
    );

    $id_utilisateur = $this->Auth_model->createUser($user_data);

    if ($id_utilisateur) {
        $this->Auth_model->assignUserProfile($id_utilisateur, 5);

        $session_data = array(
            'logged_in' => true,
            'id_utilisateur' => $id_utilisateur,
            'email' => $email,
            'prenom' => $prenom,
            'nom' => $nom,
            'telephone' => $telephone,
            'role' => 'client',
            'id_profil' => 5,
            'email_verifie' => 0
        );
        $this->session->set_userdata($session_data);

        // Générer un code OTP cryptographiquement sûr
        $otp_code = sprintf("%06d", random_int(100000, 999999));
        $expiration = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        // Invalider les anciens OTPs non utilisés de cet utilisateur
        $this->db->where('id_utilisateur', $id_utilisateur)
            ->where('utilise', 0)
            ->update('codes_otp', ['utilise' => 1]);

        $otp_data = array(
            'id_utilisateur' => $id_utilisateur,
            'code' => $otp_code,
            'type_otp' => 'verification_email',
            'email' => $email,
            'tentatives' => 0,
            'date_expiration' => $expiration,
            'utilise' => 0,
            'date_creation' => date('Y-m-d H:i:s')
        );

        $this->db->insert('codes_otp', $otp_data);

        $logo_setting = $this->db->where('KeyValue', 'site_logo')->get('settings')->row();
        $logo_filename = ($logo_setting && !empty($logo_setting->Value)) ? $logo_setting->Value : 'logo.png';
        $logo_url = base_url('attachments/Settings/' . $logo_filename);

        $this->load->library('Cpanel_email_lib');
        $subject = "Code de vérification - ABEMARKET";
        $safeprenom = htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8');
        $safenom = htmlspecialchars($nom, ENT_QUOTES, 'UTF-8');
        $message = "<div style='font-family:Arial,sans-serif;padding:25px;max-width:600px;margin:0 auto;background:#fff;border-radius:12px;border:1px solid #eaeaea;'>
            <div style='text-align:center;margin-bottom:20px;'>
                <img src='" . htmlspecialchars($logo_url, ENT_QUOTES, 'UTF-8') . "' alt='ABEMARKET' style='max-height:50px;object-fit:contain;'>
            </div>
            <h2>Bonjour {$safeprenom} {$safenom},</h2>
            <p>Voici votre code de vérification pour votre compte ABEMARKET :</p>
            <h1 style='color:#ff6600;background:#f8f9fa;padding:12px;text-align:center;letter-spacing:5px;border-radius:8px;'>{$otp_code}</h1>
            <p>Ce code expirera dans 10 minutes.</p>
            <p>Cordialement,<br><strong>L'équipe ABEMARKET</strong></p>
        </div>";
        @$this->cpanel_email_lib->send_email($email, $subject, $message);

        echo json_encode([
            'success' => true,
            'message' => 'Un code de vérification a été envoyé à votre adresse email.',
            'need_verification' => true,
            'user_id' => $id_utilisateur,
            'email' => $email,
            'redirect_url' => base_url('auth/otp_verification_page?email=' . urlencode($email) . '&user_id=' . $id_utilisateur)
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'inscription. Veuillez réessayer.']);
    }
}






    // ============================================
// PAGE DE VÉRIFICATION OTP
// ============================================
public function otp_verification_page() {
    $email = $this->input->get('email');
    $user_id = $this->input->get('user_id');
    
    if (empty($email) || empty($user_id)) {
        redirect(base_url());
    }
    
    $data['email'] = $email;
    $data['user_id'] = $user_id;
    $this->load->view('otp_verification_email', $data);
}






// ============================================
// PAGE DE CHOIX DU PROFIL APRÈS VÉRIFICATION OTP
// ============================================

public function choose_profile_page() {
    $user_id = $this->session->userdata('id_utilisateur');
    
    if (!$user_id) {
        redirect(base_url('auth/login_page'));
    }
    
    // Récupérer les informations de l'utilisateur
    $user = $this->db->get_where('utilisateurs', ['id_utilisateur' => $user_id])->row_array();
    
    // Vérifier quels profils l'utilisateur a déjà
    $existing_profiles = $this->db->select('id_profil')
        ->from('utilisateur_profils')
        ->where('id_utilisateur', $user_id)
        ->get()
        ->result_array();
    
    $has_client = false;
    $has_vendeur = false;
    
    foreach ($existing_profiles as $profile) {
        if ($profile['id_profil'] == 5) $has_client = true;
        if ($profile['id_profil'] == 4) $has_vendeur = true;
    }
    
    $data['user'] = $user;
    $data['has_client'] = $has_client;
    $data['has_vendeur'] = $has_vendeur;
    
    $this->load->view('choose_profile_view', $data);
}





public function save_profile_choice() {
    $this->output->set_content_type('application/json');
    
    $user_id = $this->session->userdata('id_utilisateur');
    $profile_type = $this->input->post('profile_type');
    
    if (!$user_id) {
        echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
        return;
    }
    
    // Déterminer l'ID du profil
    $profil_id = ($profile_type == 'vendeur') ? 4 : 5;
    
    // Vérifier si le profil existe déjà
    $exists = $this->db->get_where('utilisateur_profils', [
        'id_utilisateur' => $user_id,
        'id_profil' => $profil_id
    ])->row();
    
    if (!$exists) {
        $this->db->insert('utilisateur_profils', [
            'id_utilisateur' => $user_id,
            'id_profil' => $profil_id,
            'attribue_par' => $user_id,
            'date_attribution' => date('Y-m-d H:i:s')
        ]);
    }
    
    // Mettre à jour la session
    $this->session->set_userdata('role', $profile_type);
    $this->session->set_userdata('id_profil', $profil_id);
    
    // Redirection en fonction du choix
    $redirect_url = base_url('User_dashboard');
    
    // Si c'est un vendeur, rediriger vers la page de complétion des infos vendeur
    if ($profile_type == 'vendeur') {
        // Vérifier si les infos vendeur sont déjà complétées
        $seller_info = $this->db->get_where('vendeurs', ['id_utilisateur' => $user_id])->row();
        if (!$seller_info) {
            $redirect_url = base_url('User_dashboard/complete_profile');
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Profil enregistré avec succès',
        'redirect_url' => $redirect_url
    ]);
}





// ============================================
// REDIRECTION APRÈS VÉRIFICATION OTP
// ============================================

public function after_verification() {
    $user_id = $this->session->userdata('id_utilisateur');
    
    if (!$user_id) {
        redirect(base_url('auth/login_page'));
    }
    
    // Vérifier les profils existants
    $profiles = $this->db->select('id_profil')
        ->from('utilisateur_profils')
        ->where('id_utilisateur', $user_id)
        ->get()
        ->result_array();
    
    $profile_ids = array_column($profiles, 'id_profil');
    
    // Si déjà vendeur, rediriger vers la page de complétion
    if (in_array(4, $profile_ids)) {
        $seller_info = $this->db->get_where('vendeurs', ['id_utilisateur' => $user_id])->row();
        if (!$seller_info) {
            redirect(base_url('auth/complete_profile'));
        } else {
            redirect(base_url('User_dashboard'));
        }
    }
    // Si déjà client, aller au dashboard
    elseif (in_array(5, $profile_ids)) {
        redirect(base_url('User_dashboard'));
    }
    // Sinon, proposer le choix du profil
    else {
        redirect(base_url('auth/choose_profile_page'));
    }
}










    
    public function verify_otp() {
    $this->output->set_content_type('application/json');
    $user_id = $this->input->post('user_id');
    $code = trim($this->input->post('code'));

    if (empty($user_id) || empty($code)) {
        echo json_encode(['success' => false, 'message' => 'Code invalide']);
        return;
    }

    $otp = $this->db->select('*')
        ->from('codes_otp')
        ->where('id_utilisateur', $user_id)
        ->where('type_otp', 'verification_email')
        ->where('utilise', 0)
        ->order_by('id_otp', 'DESC')
        ->limit(1)
        ->get()
        ->row();

    if (!$otp) {
        echo json_encode(['success' => false, 'message' => 'Aucun code actif. Veuillez en demander un nouveau.']);
        return;
    }

    if (strtotime($otp->date_expiration) < time()) {
        echo json_encode(['success' => false, 'message' => 'Code expiré. Veuillez demander un nouveau code.']);
        return;
    }

    if ($otp->tentatives >= 5) {
        $this->db->where('id_otp', $otp->id_otp)->update('codes_otp', ['utilise' => 1]);
        echo json_encode(['success' => false, 'message' => 'Trop de tentatives. Veuillez en demander un nouveau.']);
        return;
    }

    if ($otp->code !== $code) {
        $this->db->set('tentatives', 'tentatives+1', FALSE)
            ->where('id_otp', $otp->id_otp)
            ->update('codes_otp');

        $new_tentatives = $otp->tentatives + 1;
        if ($new_tentatives >= 5) {
            $this->db->where('id_otp', $otp->id_otp)->update('codes_otp', ['utilise' => 1]);
            echo json_encode(['success' => false, 'message' => 'Trop de tentatives. Code annulé. Veuillez en demander un nouveau.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Code invalide. Veuillez réessayer.']);
        }
        return;
    }

    // Code correct - marquer comme utilisé
    $this->db->where('id_otp', $otp->id_otp)
        ->update('codes_otp', ['utilise' => 1]);

    $this->db->where('id_utilisateur', $user_id)
        ->update('utilisateurs', [
            'email_verifie' => 1,
            'est_actif' => 1
        ]);

    $this->session->set_userdata('email_verifie', 1);

    $user = $this->db->get_where('utilisateurs', ['id_utilisateur' => $user_id])->row();

    $logo_setting = $this->db->where('KeyValue', 'site_logo')->get('settings')->row();
    $logo_filename = ($logo_setting && !empty($logo_setting->Value)) ? $logo_setting->Value : 'logo.png';
    $logo_url = base_url('attachments/Settings/' . $logo_filename);

    $this->load->library('Cpanel_email_lib');
    $subject = "Bienvenue sur ABEMARKET";
    $safeprenom = htmlspecialchars($user->prenom, ENT_QUOTES, 'UTF-8');
    $safenom = htmlspecialchars($user->nom, ENT_QUOTES, 'UTF-8');
    $message = "<div style='font-family:Arial,sans-serif;padding:25px;max-width:600px;margin:0 auto;background:#fff;border-radius:12px;border:1px solid #eaeaea;'>
        <div style='text-align:center;margin-bottom:20px;'>
            <img src='" . htmlspecialchars($logo_url, ENT_QUOTES, 'UTF-8') . "' alt='ABEMARKET' style='max-height:50px;object-fit:contain;'>
        </div>
        <h2>Bienvenue {$safeprenom} {$safenom} !</h2>
        <p>Votre compte a été vérifié et activé avec succès sur ABEMARKET.</p>
        <p>Vous pouvez dès à présent profiter de notre plateforme.</p>
        <p>Cordialement,<br><strong>L'équipe ABEMARKET</strong></p>
    </div>";
    @$this->cpanel_email_lib->send_email($user->email, $subject, $message);

    echo json_encode([
        'success' => true,
        'message' => 'Compte vérifié avec succès !',
        'redirect_url' => base_url('auth/choose_profile_page')
    ]);
}





    
    // ============================================
    // RENVOYER LE CODE OTP (Vérification email)
    // ============================================
    
    public function resend_otp() {
        $this->output->set_content_type('application/json');
        $user_id = $this->input->post('user_id');
        $email = $this->input->post('email');

        if (empty($user_id) && empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Informations manquantes']);
            return;
        }

        if ($user_id) {
            $user = $this->db->get_where('utilisateurs', ['id_utilisateur' => $user_id])->row();
        } else {
            $user = $this->db->get_where('utilisateurs', ['email' => $email])->row();
        }

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé']);
            return;
        }

        // Invalider les anciens OTPs non utilisés
        $this->db->where('id_utilisateur', $user->id_utilisateur)
            ->where('type_otp', 'verification_email')
            ->where('utilise', 0)
            ->update('codes_otp', ['utilise' => 1]);

        $otp_code = sprintf("%06d", random_int(100000, 999999));
        $expiration = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        $otp_data = array(
            'id_utilisateur' => $user->id_utilisateur,
            'code' => $otp_code,
            'type_otp' => 'verification_email',
            'email' => $user->email,
            'tentatives' => 0,
            'date_expiration' => $expiration,
            'utilise' => 0,
            'date_creation' => date('Y-m-d H:i:s')
        );

        $this->db->insert('codes_otp', $otp_data);

        $logo_setting = $this->db->where('KeyValue', 'site_logo')->get('settings')->row();
        $logo_filename = ($logo_setting && !empty($logo_setting->Value)) ? $logo_setting->Value : 'logo.png';
        $logo_url = base_url('attachments/Settings/' . $logo_filename);

        $this->load->library('Cpanel_email_lib');
        $subject = "Nouveau code de vérification - ABEMARKET";
        $safeprenom = htmlspecialchars($user->prenom, ENT_QUOTES, 'UTF-8');
        $safenom = htmlspecialchars($user->nom, ENT_QUOTES, 'UTF-8');
        $message = "<div style='font-family:Arial,sans-serif;padding:25px;max-width:600px;margin:0 auto;background:#fff;border-radius:12px;border:1px solid #eaeaea;'>
            <div style='text-align:center;margin-bottom:20px;'>
                <img src='" . htmlspecialchars($logo_url, ENT_QUOTES, 'UTF-8') . "' alt='ABEMARKET' style='max-height:50px;object-fit:contain;'>
            </div>
            <h2>Bonjour {$safeprenom} {$safenom},</h2>
            <p>Voici votre nouveau code de vérification :</p>
            <h1 style='color:#ff6600;background:#f8f9fa;padding:12px;text-align:center;letter-spacing:5px;border-radius:8px;'>{$otp_code}</h1>
            <p>Ce code expirera dans 10 minutes.</p>
            <p>Cordialement,<br><strong>L'équipe ABEMARKET</strong></p>
        </div>";
        $result = @$this->cpanel_email_lib->send_email($user->email, $subject, $message);
        $email_sent = isset($result['success']) && $result['success'];

        if ($email_sent) {
            echo json_encode(['success' => true, 'message' => 'Un nouveau code a été envoyé à votre email']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'envoi du code. Veuillez réessayer.']);
        }
    }
    
    // ============================================
// MOT DE PASSE OUBLIÉ - ÉTAPE 1 (Envoi du code via Mailer)
// ============================================

public function forgot_password() {
    // Si accès GET (navigateur), rediriger vers l'accueil
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
        redirect(base_url());
        return;
    }

    $this->output->set_content_type('application/json');
    $ip_address = $this->input->ip_address();

    $email = trim($this->input->post('email'));

    if (empty($email) || !valid_email($email)) {
        echo json_encode(['success' => false, 'message' => 'Email invalide']);
        return;
    }

    // Rate limiting: max 3 OTP requests per 5 minutes (per-email)
    $recent_otp = $this->db->where('email', $email)
        ->where('type_otp', 'reinitialisation_mdp')
        ->where('date_creation >', date('Y-m-d H:i:s', strtotime('-5 minutes')))
        ->count_all_results('codes_otp');
    if ($recent_otp >= 3) {
        echo json_encode(['success' => false, 'message' => 'Trop de demandes. Veuillez réessayer dans 5 minutes.']);
        return;
    }

    $user = $this->Auth_model->getUserByEmail($email);

    // Message générique pour éviter la user enumeration
    if (!$user) {
        echo json_encode(['success' => true, 'message' => 'Si cet email existe, un code a été envoyé.']);
        return;
    }

    $otp_code = sprintf("%06d", random_int(100000, 999999));
    $expiration = date('Y-m-d H:i:s', strtotime('+15 minutes'));

    // Invalider les anciens codes de réinitialisation
    $this->db->where('id_utilisateur', $user['id_utilisateur'])
        ->where('type_otp', 'reinitialisation_mdp')
        ->where('utilise', 0)
        ->update('codes_otp', ['utilise' => 1]);

    $otp_data = array(
        'id_utilisateur' => $user['id_utilisateur'],
        'code' => $otp_code,
        'type_otp' => 'reinitialisation_mdp',
        'email' => $email,
        'tentatives' => 0,
        'date_expiration' => $expiration,
        'utilise' => 0,
        'date_creation' => date('Y-m-d H:i:s')
    );

    $this->db->insert('codes_otp', $otp_data);

    $logo_setting = $this->db->where('KeyValue', 'site_logo')->get('settings')->row();
    $logo_filename = ($logo_setting && !empty($logo_setting->Value)) ? $logo_setting->Value : 'logo.png';
    $logo_url = base_url('attachments/Settings/' . $logo_filename);

    $this->load->library('Cpanel_email_lib');
    $subject = "Réinitialisation de mot de passe - ABEMARKET";
    $safeprenom = htmlspecialchars($user['prenom'], ENT_QUOTES, 'UTF-8');
    $safenom = htmlspecialchars($user['nom'], ENT_QUOTES, 'UTF-8');
    $message = "<div style='font-family:Arial,sans-serif;padding:25px;max-width:600px;margin:0 auto;background:#fff;border-radius:12px;border:1px solid #eaeaea;'>
        <div style='text-align:center;margin-bottom:20px;'>
            <img src='" . htmlspecialchars($logo_url, ENT_QUOTES, 'UTF-8') . "' alt='ABEMARKET' style='max-height:50px;object-fit:contain;'>
        </div>
        <h2>Bonjour {$safeprenom} {$safenom},</h2>
        <p>Vous avez demandé la réinitialisation de votre mot de passe sur ABEMARKET.</p>
        <p>Voici votre code de réinitialisation :</p>
        <h1 style='color:#ff6600;background:#f8f9fa;padding:12px;text-align:center;letter-spacing:5px;border-radius:8px;'>{$otp_code}</h1>
        <p>Ce code expirera dans 15 minutes.</p>
        <p>Si vous n'avez pas fait cette demande, ignorez cet email.</p>
        <p>Cordialement,<br><strong>L'équipe ABEMARKET</strong></p>
    </div>";
    @$this->cpanel_email_lib->send_email($email, $subject, $message);

    $this->session->set_userdata('reset_email', $email);

    echo json_encode([
        'success' => true,
        'message' => 'Si cet email existe, un code de réinitialisation a été envoyé.',
        'redirect_url' => base_url('auth/verify_code_page')
    ]);
}



    // ============================================
    // PAGE DE VÉRIFICATION DU CODE OTP
    // ============================================
    
    public function verify_code_page() {
        $email = $this->session->userdata('reset_email');
        
        if (!$email) {
            redirect(base_url());
        }
        
        $data['email'] = $email;
        $this->load->view('Auth/verify_code_view', $data);
    }
    
    // ============================================
    // VÉRIFICATION DU CODE OTP (AJAX)
    // ============================================
    
    public function verify_code() {
    $this->output->set_content_type('application/json');
    $email = $this->session->userdata('reset_email');
    $code = trim($this->input->post('code'));
    $password = $this->input->post('password');
    $confirm_password = $this->input->post('confirm_password');

    if (!$email) {
        echo json_encode(['success' => false, 'message' => 'Session expirée. Veuillez recommencer.']);
        return;
    }

    // Étape 1: Vérification du code seulement
    if ($password === null) {
        if (empty($code)) {
            echo json_encode(['success' => false, 'message' => 'Veuillez entrer le code reçu par email']);
            return;
        }

        $user = $this->Auth_model->getUserByEmail($email);

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Session invalide. Veuillez recommencer.']);
            return;
        }

        $otp = $this->db->select('*')
            ->from('codes_otp')
            ->where('id_utilisateur', $user['id_utilisateur'])
            ->where('type_otp', 'reinitialisation_mdp')
            ->where('utilise', 0)
            ->order_by('id_otp', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        if (!$otp) {
            echo json_encode(['success' => false, 'message' => 'Aucun code actif. Veuillez recommencer la demande.']);
            return;
        }

        if (strtotime($otp->date_expiration) < time()) {
            echo json_encode(['success' => false, 'message' => 'Code expiré. Veuillez demander un nouveau code.']);
            return;
        }

        if ($otp->tentatives >= 5) {
            $this->db->where('id_otp', $otp->id_otp)->update('codes_otp', ['utilise' => 1]);
            echo json_encode(['success' => false, 'message' => 'Trop de tentatives. Veuillez en demander un nouveau.']);
            return;
        }

        if ($otp->code !== $code) {
            $this->db->set('tentatives', 'tentatives+1', FALSE)
                ->where('id_otp', $otp->id_otp)
                ->update('codes_otp');

            $new_tentatives = $otp->tentatives + 1;
            if ($new_tentatives >= 5) {
                $this->db->where('id_otp', $otp->id_otp)->update('codes_otp', ['utilise' => 1]);
                echo json_encode(['success' => false, 'message' => 'Trop de tentatives. Code annulé. Veuillez en demander un nouveau.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Code invalide ou expiré']);
            }
            return;
        }

        // Code correct - marquer comme utilisé
        $this->db->where('id_otp', $otp->id_otp);
        $this->db->update('codes_otp', ['utilise' => 1]);

        $this->session->set_userdata('code_validated', true);
        $this->session->set_userdata('validated_user_id', $user['id_utilisateur']);

        echo json_encode(['success' => true, 'message' => 'Code valide', 'action' => 'set_password']);
        return;
    }
        
        // Étape 2: Réinitialisation du mot de passe
        if (!$this->session->userdata('code_validated')) {
            echo json_encode(['success' => false, 'message' => 'Veuillez d\'abord valider votre code']);
            return;
        }
        
        if (strlen($password) < 8) {
            echo json_encode(['success' => false, 'message' => 'Le mot de passe doit contenir au moins 8 caractères']);
            return;
        }
        
        if ($password !== $confirm_password) {
            echo json_encode(['success' => false, 'message' => 'Les mots de passe ne correspondent pas']);
            return;
        }
        
        $user_id = $this->session->userdata('validated_user_id');
        $user = null;
        if ($user_id) {
            $user = $this->db->get_where('utilisateurs', ['id_utilisateur' => $user_id])->row_array();
        } else {
            $user = $this->Auth_model->getUserByEmail($email);
        }
        
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé']);
            return;
        }
        
        $this->Auth_model->updatePassword($user['id_utilisateur'], $password);
        
        // Supprimer tous les OTP de réinitialisation restants pour cet utilisateur
        $this->db->where('id_utilisateur', $user['id_utilisateur']);
        $this->db->where('type_otp', 'reinitialisation_mdp');
        $this->db->delete('codes_otp');
        
        $this->session->unset_userdata('reset_email');
        $this->session->unset_userdata('code_validated');
        $this->session->unset_userdata('validated_user_id');
        
        echo json_encode(['success' => true, 'message' => 'Mot de passe modifié avec succès. Veuillez vous connecter.']);
    }
    
    // ============================================
    // RENVOYER UN NOUVEAU CODE OTP (Réinitialisation)
    // ============================================
    
    public function resend_reset_code() {
        $this->output->set_content_type('application/json');
        $email = trim($this->input->post('email'));

        if (empty($email) || !valid_email($email)) {
            echo json_encode(['success' => false, 'message' => 'Email invalide']);
            return;
        }

        $user = $this->Auth_model->getUserByEmail($email);

        if (!$user) {
            echo json_encode(['success' => true, 'message' => 'Si cet email existe, un code a été envoyé.']);
            return;
        }

        // Invalider les anciens codes
        $this->db->where('id_utilisateur', $user['id_utilisateur'])
            ->where('type_otp', 'reinitialisation_mdp')
            ->where('utilise', 0)
            ->update('codes_otp', ['utilise' => 1]);

        $otp_code = sprintf("%06d", random_int(100000, 999999));
        $expiration = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $otp_data = array(
            'id_utilisateur' => $user['id_utilisateur'],
            'code' => $otp_code,
            'type_otp' => 'reinitialisation_mdp',
            'email' => $email,
            'tentatives' => 0,
            'date_expiration' => $expiration,
            'utilise' => 0,
            'date_creation' => date('Y-m-d H:i:s')
        );

        $this->db->insert('codes_otp', $otp_data);

        $this->load->library('Cpanel_email_lib');
        $subject = "Nouveau code de réinitialisation - ABEMARKET";
        $safeprenom = htmlspecialchars($user['prenom'], ENT_QUOTES, 'UTF-8');
        $safenom = htmlspecialchars($user['nom'], ENT_QUOTES, 'UTF-8');
        $message = "<div style='font-family:Arial,sans-serif;padding:20px;'>
            <h2>Bonjour {$safeprenom} {$safenom},</h2>
            <p>Voici votre nouveau code de réinitialisation de mot de passe :</p>
            <h1 style='color:#ff6600;background:#f8f9fa;padding:10px;text-align:center;letter-spacing:5px;'>{$otp_code}</h1>
            <p>Ce code expirera dans 15 minutes.</p>
            <p>Si vous n'avez pas fait cette demande, ignorez cet email.</p>
            <p>Cordialement,<br>L'équipe ABEMARKET</p>
        </div>";
        @$this->cpanel_email_lib->send_email($email, $subject, $message);

        echo json_encode(['success' => true, 'message' => 'Si cet email existe, un nouveau code a été envoyé.']);
    }
    
    // ============================================
    // DÉCONNEXION
    // ============================================
    
    public function logout() {
        if ($this->session->userdata('logged_in')) {
            $this->Auth_model->logConnexionAttempt(
                $this->session->userdata('id_utilisateur'),
                $this->session->userdata('email'),
                $this->input->ip_address(),
                false,
                'Déconnexion volontaire'
            );
        }
        
        $this->session->sess_destroy();
        redirect(base_url());
    }
    
    public function logout_ajax() {
        $this->output->set_content_type('application/json');
        if ($this->session->userdata('logged_in')) {
            $this->session->sess_destroy();
            echo json_encode(['success' => true, 'message' => 'Déconnecté']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Non connecté']);
        }
    }
    
    // ============================================
    // REDIRECTION SELON LE RÔLE
    // ============================================
    
    private function getDashboardRedirect($role = null) {
        if ($role === null) {
            $role = $this->session->userdata('role');
        }
        
        switch ($role) {
            case 'super_admin':
            case 'admin':
                return base_url('Dashboard');
            case 'vendeur':
                return base_url('Home/User_dashboard');
            case 'livreur':
                return base_url('Livreur/Dashboard');
            case 'finance':
                return base_url('Finance/Dashboard');
            case 'support':
                return base_url('Support/Dashboard');
            default:
                return base_url('Home/User_dashboard');
        }
    }
}
