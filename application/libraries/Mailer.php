<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mailer Library for CodeIgniter 3
 * Gère l'envoi d'emails avec SMTP pour ABEMARKET
 */
class Mailer {
    
    private $CI;
    private $config;
    private $from_email = 'expressmarket44@gmail.com';
    private $from_name = 'ABEMARKET';
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library('email');
        $this->loadConfig();
    }
    
    /**
     * Charger la configuration email
     */
    private function loadConfig() {
        // Configuration SMTP par défaut pour Gmail
        $this->config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 587,
            'smtp_user' => $this->from_email,
            'smtp_pass' => 'biahgnvbkuikemik', // À remplacer par votre mot de passe d'application
            'smtp_crypto' => 'tls',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n",
            'crlf' => "\r\n",
            'wordwrap' => TRUE,
            'validate' => TRUE
        );
        
        // Essayer de charger la configuration depuis la base de données
        try {
            // Charger l'email de l'expéditeur et le mot de passe SMTP depuis les paramètres généraux
            $site_email = $this->CI->db->where('KeyValue', 'site_email')->get('settings')->row();
            $smtp_pass = $this->CI->db->where('KeyValue', 'password_email16caractere')->get('settings')->row();
            
            if ($site_email && !empty($site_email->Value)) {
                $this->from_email = $site_email->Value;
                $this->config['smtp_user'] = $this->from_email;
            }
            if ($smtp_pass && !empty($smtp_pass->Value)) {
                $this->config['smtp_pass'] = $smtp_pass->Value;
            }

            // Charger les configurations SMTP spécifiques de surcharge s'il y en a
            $settings = $this->CI->db->where('KeyValue', 'smtp_config')->get('settings')->row();
            if ($settings && !empty($settings->Value)) {
                $smtp_config = json_decode($settings->Value, true);
                if (is_array($smtp_config) && !empty($smtp_config)) {
                    $this->config = array_merge($this->config, $smtp_config);
                    if (isset($smtp_config['smtp_user'])) {
                        $this->from_email = $smtp_config['smtp_user'];
                    }
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Erreur chargement config SMTP depuis la BD: ' . $e->getMessage());
        }
    }
    
    /**
     * Envoyer un email générique
     * @param string $to Email destinataire
     * @param string $subject Sujet
     * @param string $message Message HTML
     * @param string $alt_message Message texte alternatif
     * @return bool
     */
    public function send($to, $subject, $message, $alt_message = '') {
        // Initialiser la bibliothèque email avec la configuration
        $this->CI->email->clear();
        $this->CI->email->initialize($this->config);
        
        // Configurer l'email
        $this->CI->email->from($this->config['smtp_user'], $this->from_name);
        $this->CI->email->to($to);
        $this->CI->email->subject($subject);
        $this->CI->email->message($message);
        
        if (!empty($alt_message)) {
            $this->CI->email->set_alt_message($alt_message);
        }
        
        // Envoyer et journaliser le résultat
        if ($this->CI->email->send()) {
            log_message('info', "Email envoyé à : $to - Sujet : $subject");
            return true;
        } else {
            log_message('error', "Erreur d'envoi email à : $to - Erreur : " . $this->CI->email->print_debugger());
            return false;
        }
    }
    
    /**
     * Envoyer un email à plusieurs destinataires
     * @param array $to_list Liste des emails
     * @param string $subject Sujet
     * @param string $message Message HTML
     * @return bool
     */
    public function sendToMultiple($to_list, $subject, $message) {
        $this->CI->email->clear();
        $this->CI->email->initialize($this->config);
        
        $this->CI->email->from($this->config['smtp_user'], $this->from_name);
        $this->CI->email->to(implode(',', $to_list));
        $this->CI->email->subject($subject);
        $this->CI->email->message($message);
        
        if ($this->CI->email->send()) {
            log_message('info', "Email envoyé à plusieurs destinataires");
            return true;
        }
        return false;
    }
    
    /**
     * Envoyer un code OTP pour vérification email
     * @param string $email Email du destinataire
     * @param string $prenom Prénom
     * @param string $nom Nom
     * @param string $code Code OTP à 6 chiffres
     * @return bool
     */
    public function sendVerificationCode($email, $prenom, $nom, $code) {
        $subject = 'Vérification de votre compte - ABEMARKET';
        
        $body = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Vérification compte ABEMARKET</title>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
                .container { max-width: 550px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
                .header { background: linear-gradient(135deg, #ff6600, #ff8533); padding: 25px; text-align: center; }
                .header h1 { color: white; margin: 0; font-size: 28px; }
                .content { padding: 30px; text-align: center; }
                .code { font-size: 42px; font-weight: bold; color: #ff6600; letter-spacing: 10px; padding: 15px; background: #fff3e6; border-radius: 10px; display: inline-block; margin: 20px 0; font-family: monospace; }
                .info { background: #e8f4fd; border-left: 4px solid #ff6600; padding: 15px; margin: 20px 0; text-align: left; font-size: 13px; }
                .footer { background: #f9f9f9; padding: 20px; text-align: center; color: #999; font-size: 12px; border-top: 1px solid #eee; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>ABEMARKET</h1>
                    <p style="color: white; margin: 5px 0 0;">Votre marketplace au Burundi</p>
                </div>
                <div class="content">
                    <h2>Bienvenue ' . htmlspecialchars($prenom) . ' ' . htmlspecialchars($nom) . ' !</h2>
                    <p>Merci de vous être inscrit sur ABEMARKET.</p>
                    <p>Pour activer votre compte, veuillez utiliser le code de vérification ci-dessous :</p>
                    
                    <div class="code">' . $code . '</div>
                    
                    <div class="info">
                        <strong>📌 Information importante :</strong><br>
                        • Ce code est valable pendant <strong>10 minutes</strong><br>
                        • Ne partagez ce code avec personne<br>
                        • Si vous n\'avez pas créé de compte, ignorez cet email
                    </div>
                    
                    <p>Une fois votre compte activé, vous pourrez :</p>
                    <ul style="text-align: left;">
                        <li>🛍️ Acheter des produits en ligne</li>
                        <li>📦 Suivre vos commandes</li>
                        <li>⭐ Laisser des avis</li>
                        <li>🏪 Créer votre boutique (option vendeur)</li>
                    </ul>
                </div>
                <div class="footer">
                    <p>&copy; ' . date('Y') . ' ABEMARKET - Tous droits réservés</p>
                    <p><a href="' . base_url() . '" style="color: #ff6600;">www.abemarket.com</a></p>
                </div>
            </div>
        </body>
        </html>';
        
        $alt_body = "Bienvenue $prenom $nom sur ABEMARKET !\n\n";
        $alt_body .= "Votre code de vérification est : $code\n";
        $alt_body .= "Ce code est valable pendant 10 minutes.\n\n";
        $alt_body .= "Ne partagez ce code avec personne.\n\n";
        $alt_body .= "Cordialement,\nL'équipe ABEMARKET";
        
        return $this->send($email, $subject, $body, $alt_body);
    }
    
    /**
     * Envoyer un code de réinitialisation de mot de passe
     * @param string $email Email du destinataire
     * @param string $nom Nom du destinataire
     * @param string $code Code OTP à 6 chiffres
     * @return bool
     */
    public function sendResetCode($email, $nom, $code) {
        $subject = 'Code de réinitialisation - ABEMARKET';
        
        $body = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Code de réinitialisation ABEMARKET</title>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
                .container { max-width: 550px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
                .header { background: linear-gradient(135deg, #ff6600, #ff8533); padding: 25px; text-align: center; }
                .header h1 { color: white; margin: 0; font-size: 28px; }
                .content { padding: 30px; text-align: center; }
                .code { font-size: 42px; font-weight: bold; color: #ff6600; letter-spacing: 10px; padding: 15px; background: #fff3e6; border-radius: 10px; display: inline-block; margin: 20px 0; font-family: monospace; }
                .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; text-align: left; font-size: 13px; }
                .footer { background: #f9f9f9; padding: 20px; text-align: center; color: #999; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>ABEMARKET</h1>
                </div>
                <div class="content">
                    <h2>Réinitialisation du mot de passe</h2>
                    <p>Bonjour <strong>' . htmlspecialchars($nom) . '</strong>,</p>
                    <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>
                    <p>Voici votre code de vérification :</p>
                    
                    <div class="code">' . $code . '</div>
                    
                    <div class="warning">
                        <strong>⚠️ Attention :</strong><br>
                        • Ce code est valable pendant <strong>15 minutes</strong><br>
                        • Ne partagez ce code avec personne<br>
                        • Si vous n\'avez pas demandé cette réinitialisation, ignorez cet email
                    </div>
                    
                    <p>Rendez-vous sur la page de connexion pour réinitialiser votre mot de passe avec ce code.</p>
                </div>
                <div class="footer">
                    <p>&copy; ' . date('Y') . ' ABEMARKET - Tous droits réservés</p>
                </div>
            </div>
        </body>
        </html>';
        
        $alt_body = "Bonjour $nom,\n\n";
        $alt_body .= "Vous avez demandé la réinitialisation de votre mot de passe.\n";
        $alt_body .= "Votre code de vérification est : $code\n\n";
        $alt_body .= "Ce code est valable pendant 15 minutes.\n\n";
        $alt_body .= "Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.\n\n";
        $alt_body .= "Cordialement,\nL'équipe ABEMARKET";
        
        return $this->send($email, $subject, $body, $alt_body);
    }
    
    /**
     * Envoyer un email de bienvenue
     * @param string $email Email du destinataire
     * @param string $prenom Prénom du destinataire
     * @param string $nom Nom du destinataire
     * @return bool
     */
    public function sendWelcomeEmail($email, $prenom, $nom) {
        $subject = 'Bienvenue sur ABEMARKET !';
        
        $body = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Bienvenue sur ABEMARKET</title>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
                .container { max-width: 550px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
                .header { background: linear-gradient(135deg, #ff6600, #ff8533); padding: 25px; text-align: center; }
                .header h1 { color: white; margin: 0; font-size: 28px; }
                .content { padding: 30px; }
                .button { display: inline-block; background: #ff6600; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin-top: 20px; font-weight: bold; }
                .features { display: flex; justify-content: space-around; margin: 30px 0; text-align: center; }
                .feature { flex: 1; padding: 10px; }
                .footer { background: #f9f9f9; padding: 20px; text-align: center; color: #999; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>ABEMARKET</h1>
                    <p style="color: white; margin: 5px 0 0;">La marketplace du Burundi</p>
                </div>
                <div class="content">
                    <h2>Bienvenue ' . htmlspecialchars($prenom) . ' !</h2>
                    <p>Nous sommes ravis de vous compter parmi nos membres.</p>
                    <p>Votre compte a été créé avec succès. Vous pouvez maintenant profiter de tous nos services.</p>
                    
                    <div class="features">
                        <div class="feature">
                            <div style="font-size: 30px;">🛍️</div>
                            <p>Achetez en ligne</p>
                        </div>
                        <div class="feature">
                            <div style="font-size: 30px;">🚚</div>
                            <p>Livraison rapide</p>
                        </div>
                        <div class="feature">
                            <div style="font-size: 30px;">💳</div>
                            <p>Paiement sécurisé</p>
                        </div>
                    </div>
                    
                    <div style="text-align: center;">
                        <a href="' . base_url() . '" class="button">Découvrir les offres</a>
                    </div>
                </div>
                <div class="footer">
                    <p>&copy; ' . date('Y') . ' ABEMARKET - Tous droits réservés</p>
                    <p><a href="' . base_url() . '" style="color: #ff6600;">www.abemarket.com</a></p>
                </div>
            </div>
        </body>
        </html>';
        
        $alt_body = "Bienvenue $prenom $nom sur ABEMARKET !\n\n";
        $alt_body .= "Votre compte a été créé avec succès.\n\n";
        $alt_body .= "Vous pouvez maintenant vous connecter et découvrir nos produits.\n\n";
        $alt_body .= "Cordialement,\nL'équipe ABEMARKET";
        
        return $this->send($email, $subject, $body, $alt_body);
    }
    
    /**
     * Envoyer un email de confirmation de commande
     * @param string $email Email du client
     * @param string $nom Nom du client
     * @param array $commande Données de la commande
     * @return bool
     */
    public function sendOrderConfirmation($email, $nom, $commande) {
        $subject = 'Confirmation de votre commande #' . $commande['numero_commande'];
        
        $items_html = '';
        if (isset($commande['items']) && !empty($commande['items'])) {
            foreach ($commande['items'] as $item) {
                $items_html .= '
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">' . htmlspecialchars($item['nom_produit']) . '</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: center;">' . $item['quantite'] . '</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: right;">' . number_format($item['prix_unitaire'], 0, ',', ' ') . ' BIF</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: right;">' . number_format($item['prix_total'], 0, ',', ' ') . ' BIF</td>
                </tr>';
            }
        }
        
        $body = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Confirmation commande ABEMARKET</title>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
                .container { max-width: 650px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
                .header { background: linear-gradient(135deg, #ff6600, #ff8533); padding: 25px; text-align: center; }
                .header h1 { color: white; margin: 0; font-size: 28px; }
                .content { padding: 30px; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th { background: #f5f5f5; padding: 12px; text-align: left; font-weight: bold; }
                .total { font-size: 18px; font-weight: bold; text-align: right; margin-top: 20px; padding-top: 20px; border-top: 2px solid #eee; }
                .footer { background: #f9f9f9; padding: 20px; text-align: center; color: #999; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>ABEMARKET</h1>
                </div>
                <div class="content">
                    <h2>Merci pour votre commande !</h2>
                    <p>Bonjour <strong>' . htmlspecialchars($nom) . '</strong>,</p>
                    <p>Nous avons bien reçu votre commande et nous la traitons actuellement.</p>
                    
                    <h3>Détails de la commande #' . $commande['numero_commande'] . '</h3>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Qté</th>
                                <th>Prix unitaire</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ' . $items_html . '
                        </tbody>
                    </table>
                    
                    <div class="total">
                        <p>Sous-total : ' . number_format($commande['sous_total'], 0, ',', ' ') . ' BIF</p>
                        <p>Frais de livraison : ' . number_format($commande['frais_livraison'], 0, ',', ' ') . ' BIF</p>
                        <p><strong>Total : ' . number_format($commande['montant_total'], 0, ',', ' ') . ' BIF</strong></p>
                    </div>
                    
                    <p>Vous serez notifié dès que votre commande sera expédiée.</p>
                    <p>Pour suivre l\'évolution de votre commande, connectez-vous à votre espace client.</p>
                </div>
                <div class="footer">
                    <p>&copy; ' . date('Y') . ' ABEMARKET - Tous droits réservés</p>
                    <p>Besoin d\'aide ? Contactez-nous au <strong>+257 68 86 39 45</strong></p>
                </div>
            </div>
        </body>
        </html>';
        
        $alt_body = "Merci pour votre commande $nom !\n\n";
        $alt_body .= "Votre commande #" . $commande['numero_commande'] . " a été confirmée.\n";
        $alt_body .= "Montant total : " . number_format($commande['montant_total'], 0, ',', ' ') . " BIF\n\n";
        $alt_body .= "Cordialement,\nL'équipe ABEMARKET";
        
        return $this->send($email, $subject, $body, $alt_body);
    }
    
    /**
     * Tester la configuration SMTP
     * @param string $to Email de test
     * @return array
     */
    public function testConnection($to = null) {
        $test_email = $to ?: $this->config['smtp_user'];
        
        $subject = 'Test de configuration SMTP - ABEMARKET';
        $body = '
        <!DOCTYPE html>
        <html>
        <head><title>Test SMTP</title></head>
        <body>
            <h1 style="color: #ff6600;">✅ Test réussi !</h1>
            <p>Votre configuration SMTP fonctionne correctement.</p>
            <p>Ce message a été envoyé depuis ABEMARKET.</p>
        </body>
        </html>';
        
        if ($this->send($test_email, $subject, $body)) {
            return ['success' => true, 'message' => 'Email de test envoyé avec succès'];
        } else {
            return ['success' => false, 'message' => $this->CI->email->print_debugger()];
        }
    }
    
    /**
     * Mettre à jour la configuration SMTP
     * @param array $new_config Nouvelle configuration
     * @return bool
     */
    public function updateConfig($new_config) {
        $this->config = array_merge($this->config, $new_config);
        
        // Sauvegarder en base de données
        try {
            if (!$this->CI->db->table_exists('settings')) {
                return false;
            }
            
            $config_json = json_encode($this->config);
            $exists = $this->CI->db->where('KeyValue', 'smtp_config')->get('settings')->row();
            
            if ($exists) {
                $this->CI->db->where('KeyValue', 'smtp_config')->update('settings', ['Value' => $config_json]);
            } else {
                $this->CI->db->insert('settings', [
                    'KeyValue' => 'smtp_config',
                    'TitlePage' => 'Configuration SMTP',
                    'Value' => $config_json,
                    'IsFile' => 0
                ]);
            }
            return true;
        } catch (Exception $e) {
            log_message('error', 'Erreur sauvegarde config SMTP: ' . $e->getMessage());
            return false;
        }
    }
}