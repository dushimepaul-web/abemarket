<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Flutterwave Configuration - ABEMARKET
|--------------------------------------------------------------------------
|
| Inscris-toi sur https://app.flutterwave.com pour obtenir tes clés API.
| - PUBLIC_KEY : Clé publique (commence par FLWPUBK-...)
| - SECRET_KEY : Clé secrète (commence par FLWSECK-...) — JAMAIS exposée côté client
| - ENCRYPTION_KEY : Clé de chiffrement (depuis le dashboard Flutterwave)
| - WEBHOOK_SECRET : Secret pour vérifier les webhooks (depuis le dashboard)
|
| Modes : 'sandbox' (test) ou 'production' (réel)
*/

$config['flutterwave'] = [
    'mode'           => 'sandbox', // 'sandbox' ou 'production'
    
    'public_key'     => 'FLWPUBK-XXXXXXXXXXXXXXXXXXXXX-X',
    'secret_key'     => 'FLWSECK-XXXXXXXXXXXXXXXXXXXXX-X',
    'encryption_key' => 'FLWSECK-XXXXXXXXXXXXXXXXXXXXX',
    'webhook_secret' => 'YOUR_WEBHOOK_SECRET',
    
    'base_url'       => [
        'sandbox'     => 'https://api.flutterwave.com/v3',
        'production'  => 'https://api.flutterwave.com/v3',
    ],
    
    'redirect_url'   => '', // Sera construit dynamiquement
    
    'currency'       => 'BIF',
    
    'countries'      => ['BDI'], // Burundi
    
    // Mapping de tes modes de paiement internes vers les providers Flutterwave
    'payment_mapping' => [
        'BANCOBU'  => 'mobile_money_bdi',
        'LUMICASH' => 'mobile_money_bdi',
        'ECOCASH'  => 'mobile_money_bdi',
        'CARTE'    => 'card',
        'ESPECES_LIVRAISON' => 'cash_on_delivery',
    ],
    
    // Webhook endpoint
    'webhook_url'    => '', // Sera construit dynamiquement
];
