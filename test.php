<?php
// Charger l'environnement CodeIgniter pour tester l'envoi d'email
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');

// Chemin vers index.php ou bootstrap minimal
require_once 'index.php';

// Récupérer l'instance de CodeIgniter
$CI =& get_instance();

// Charger la bibliothèque cpanel_email_lib
$CI->load->library('Cpanel_email_lib');

$to = 'dushimepaul51@gmail.com';
$subject = 'Test Envoi Email - ABEMARKET cPanel';
$message = '<div style="font-family:Arial,sans-serif;padding:20px;background:#f9f9f9;border-radius:10px;">
    <h2 style="color:#ff6600;">Test Email ABEMARKET</h2>
    <p>Ceci est un message de test envoyé depuis le serveur cPanel avec <strong>Cpanel_email_lib</strong> et l\'adresse <strong>abemarket@abe.bi</strong>.</p>
    <p>Si vous recevez ce message, la configuration et l\'envoi fonctionnent parfaitement !</p>
    <p>Cordialement,<br>L\'équipe Technique ABEMARKET</p>
</div>';

$result = $CI->cpanel_email_lib->send_email($to, $subject, $message);

echo "<h1>Résultat du test d'envoi d'email</h1>";
if ($result['success'] ?? false) {
    echo "<p style='color:green;font-weight:bold;'>Succès : Email envoyé avec succès à $to !</p>";
} else {
    echo "<p style='color:red;font-weight:bold;'>Erreur lors de l'envoi :</p>";
    echo "<pre>" . print_r($result, true) . "</pre>";
    echo "<h3>Debugger :</h3>";
    echo "<pre>" . $CI->email->print_debugger() . "</pre>";
}
?>
