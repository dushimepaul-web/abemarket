<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Payment Gateway Library - Flutterwave Integration pour ABEMARKET
 * 
 * Gère l'initialisation, la vérification et le webhook des paiements Flutterwave.
 * Sécurité : valide les webhooks via HMAC, ne stocke jamais les clés en dur dans le code.
 */
class Payment_gateway {

    private $CI;
    private $config;
    private $secret_key;
    private $base_url;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->config('flutterwave', TRUE);
        $this->config = $this->CI->config->item('flutterwave');
        
        $this->secret_key = $this->config['secret_key'];
        $this->base_url = $this->config['base_url'][$this->config['mode']];
    }

    /**
     * Initialiser un paiement Flutterwave
     * 
     * @param array $data Données de la commande
     *   - amount : montant en BIF
     *   - email : email du client
     *   - phone : numéro de téléphone (mobile money)
     *   - nom_complet : nom du client
     *   - numero_commande : numéro de commande interne
     *   - payment_method : code du mode de paiement (BANCOBU, LUMICASH, etc.)
     * @return array ['success' => bool, 'data' => array, 'error' => string]
     */
    public function initialize_payment($data) {
        $redirect_url = base_url('payment/callback');
        
        $payload = [
            'tx_ref'         => $data['numero_commande'] . '-' . time(),
            'amount'         => $data['amount'],
            'currency'       => $this->config['currency'],
            'redirect_url'   => $redirect_url,
            'meta'           => [
                'numero_commande' => $data['numero_commande'],
                'id_utilisateur'  => $data['id_utilisateur'] ?? '',
            ],
            'customer'       => [
                'email'       => $data['email'],
                'phone_number' => $data['phone'] ?? '',
                'name'        => $data['nom_complet'],
            ],
            'customizations' => [
                'title'       => 'ABEMARKET',
                'description' => 'Paiement commande ' . $data['numero_commande'],
                'logo'        => base_url('assets/frontend/images/logo/logo.png'),
            ],
        ];

        // Configurer le type de paiement selon la méthode choisie
        $payment_type = $this->config['payment_mapping'][$data['payment_method']] ?? null;
        
        if ($payment_type === 'card') {
            $payload['payment_options'] = 'card';
        } elseif ($payment_type === 'mobile_money_bdi') {
            $payload['payment_options'] = 'mobilemoney_bdi';
        } else {
            // Pour cash on delivery ou autres — Flutterwave gère automatiquement
            $payload['payment_options'] = 'card,mobilemoney_bdi';
        }

        $response = $this->call_api('/payments', $payload);
        
        return $response;
    }

    /**
     * Vérifier le statut d'un paiement par reference Flutterwave
     * 
     * @param string $tx_ref Référence de transaction
     * @return array ['success' => bool, 'data' => array, 'error' => string]
     */
    public function verify_payment($tx_ref) {
        $response = $this->call_api('/transactions/verify?txref=' . urlencode($tx_ref), [], 'GET');
        return $response;
    }

    /**
     * Vérifier la signature d'un webhook Flutterwave (sécurité)
     * 
     * @param string $signature En-tête Verif-Hash du webhook
     * @param string $body Corps de la requête webhook
     * @return bool
     */
    public function verify_webhook_signature($signature, $body) {
        $expected_hash = hash_hmac('sha512', $body, $this->config['webhook_secret']);
        return hash_equals($expected_hash, $signature);
    }

    /**
     * Appel API Flutterwave
     * 
     * @param string $endpoint Endpoint de l'API (ex: /payments)
     * @param array $payload Données à envoyer
     * @param string $method GET ou POST
     * @return array
     */
    private function call_api($endpoint, $payload = [], $method = 'POST') {
        $url = $this->base_url . $endpoint;
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => ($method === 'POST'),
            CURLOPT_POSTFIELDS     => ($method === 'POST') ? json_encode($payload) : null,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->secret_key,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT        => 30,
        ]);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            log_message('error', 'Flutterwave API Error: ' . $error);
            return ['success' => false, 'error' => 'Erreur de connexion au serveur de paiement'];
        }
        
        $decoded = json_decode($response, true);
        
        if (!$decoded) {
            log_message('error', 'Flutterwave API: Réponse invalide - ' . substr($response, 0, 200));
            return ['success' => false, 'error' => 'Réponse invalide du serveur de paiement'];
        }
        
        if ($http_code >= 200 && $http_code < 300 && isset($decoded['status']) && $decoded['status'] === 'success') {
            return ['success' => true, 'data' => $decoded['data'] ?? $decoded];
        }
        
        $error_msg = $decoded['message'] ?? $decoded['status'] ?? 'Erreur inconnue';
        log_message('error', 'Flutterwave API Error: ' . $error_msg . ' | HTTP ' . $http_code);
        return ['success' => false, 'error' => $error_msg, 'full_response' => $decoded];
    }

    /**
     * Enregistrer une transaction dans la base de données
     * 
     * @param array $order_data Données de la commande
     * @param array $payment_data Données de réponse Flutterwave
     * @return int|false ID de la transaction ou false
     */
    public function record_transaction($order_data, $payment_data) {
        $transaction_data = [
            'reference_interne'    => $order_data['numero_commande'],
            'id_commande'          => $order_data['id_commande'],
            'id_utilisateur'       => $order_data['id_utilisateur'],
            'id_mode_payement'     => $order_data['id_mode_payement'],
            'type_transaction'     => 'paiement',
            'montant'              => $order_data['montant_total'],
            'frais'                => $order_data['frais_flutterwave'] ?? 0,
            'montant_net'          => $order_data['montant_total'] - ($order_data['frais_flutterwave'] ?? 0),
            'devise'               => $this->config['currency'],
            'telephone_payeur'     => $order_data['phone'] ?? null,
            'nom_payeur'           => $order_data['nom_complet'] ?? null,
            'reference_operateur'  => $payment_data['flw_ref'] ?? $payment_data['tx_ref'] ?? null,
            'statut'               => $this->map_status($payment_data['status'] ?? 'pending'),
            'message_statut'       => $payment_data['message'] ?? '',
            'date_confirmation'    => !empty($payment_data['created_at']) ? date('Y-m-d H:i:s', strtotime($payment_data['created_at'])) : null,
            'adresse_ip'           => $this->CI->input->ip_address(),
            'date_creation'        => date('Y-m-d H:i:s'),
        ];
        
        $this->CI->db->insert('transactions_paiement', $transaction_data);
        return $this->CI->db->insert_id();
    }

    /**
     * Mapper le statut Flutterwave vers notre statut interne
     */
    private function map_status($flutterwave_status) {
        $mapping = [
            'successful' => 'confirme',
            'success'    => 'confirme',
            'pending'    => 'en_attente',
            'failed'     => 'echoue',
            'reversed'   => 'rembourse',
            'cancelled'  => 'annule',
        ];
        return $mapping[strtolower($flutterwave_status)] ?? 'en_attente';
    }
}
