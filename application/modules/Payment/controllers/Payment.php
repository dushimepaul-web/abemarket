<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Payment Controller - Gestion des paiements Flutterwave
 * 
 * Endpoints:
 *   POST /payment/initialize  → Initie un paiement Flutterwave
 *   GET  /payment/callback    → Callback après paiement (redirection Flutterwave)
 *   GET  /payment/verify/{tx_ref} → Vérification manuelle d'un paiement
 *   POST /payment/webhook     → Webhook Flutterwave (notification serveur à serveur)
 */
class Payment extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('Payment_gateway');
        $this->load->model('Home/Home_model');
    }

    /**
     * Initialiser un paiement Flutterwave
     * POST /payment/initialize
     */
    public function initialize() {
        $this->output->set_content_type('application/json');

        if (!$this->session->userdata('id_utilisateur')) {
            echo json_encode(['success' => false, 'message' => 'Non autorisé']);
            return;
        }

        $numero_commande = $this->input->post('numero_commande', TRUE);
        $email = $this->input->post('email', TRUE);
        $phone = $this->input->post('phone', TRUE);
        $nom_complet = $this->input->post('nom_complet', TRUE);
        $payment_method_code = $this->input->post('payment_method_code', TRUE);

        if (empty($numero_commande) || empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
            return;
        }

        // Récupérer la commande
        $order = $this->db->where('numero_commande', $numero_commande)
            ->where('id_utilisateur', $this->session->userdata('id_utilisateur'))
            ->get('commandes')->row_array();

        if (!$order) {
            echo json_encode(['success' => false, 'message' => 'Commande introuvable']);
            return;
        }

        if ($order['statut_paiement'] === 'paye') {
            echo json_encode(['success' => false, 'message' => 'Cette commande est déjà payée']);
            return;
        }

        // Vérifier que le mode de paiement nécessite un paiement en ligne
        $payment_method = $this->db->where('id_mode_payement', $order['id_mode_payement'])
            ->get('mode_payement')->row_array();

        if ($payment_method['code'] === 'ESPECES_LIVRAISON') {
            echo json_encode([
                'success' => true,
                'message' => 'Paiement à la livraison confirmé',
                'redirect' => base_url('payment/success/' . $numero_commande),
                'type' => 'cash_on_delivery'
            ]);
            return;
        }

        // Initialiser le paiement Flutterwave
        $result = $this->payment_gateway->initialize_payment([
            'amount'         => $order['montant_total'],
            'email'          => $email,
            'phone'          => $phone,
            'nom_complet'    => $nom_complet,
            'numero_commande' => $numero_commande,
            'payment_method' => $payment_method_code,
            'id_utilisateur' => $this->session->userdata('id_utilisateur'),
        ]);

        if ($result['success']) {
            $payment_link = $result['data']['link'] ?? null;
            
            if ($payment_link) {
                echo json_encode([
                    'success' => true,
                    'payment_link' => $payment_link,
                    'message' => 'Redirection vers la page de paiement...'
                ]);
            } else {
                // Pas de lien — probablement un mode inline (carte)
                echo json_encode([
                    'success' => true,
                    'client_secret' => $result['data']['client_secret'] ?? null,
                    'data' => $result['data'],
                    'message' => 'Paiement initialisé'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => $result['error'] ?? 'Erreur lors de l\'initialisation du paiement'
            ]);
        }
    }

    /**
     * Callback après paiement Flutterwave (redirection automatique)
     * GET /payment/callback?tx_ref=...&status=...
     */
    public function callback() {
        $tx_ref = $this->input->get('tx_ref', TRUE);
        $status = $this->input->get('status', TRUE);
        $transaction_id = $this->input->get('transaction_id', TRUE);

        if (empty($tx_ref)) {
            $this->session->set_flashdata('error', 'Référence de transaction manquante');
            redirect(base_url('home/cart'));
            return;
        }

        // Extraire le numéro de commande du tx_ref (format: CMD-YYYYMMDD-XXXX-timestamp)
        $parts = explode('-', $tx_ref);
        if (count($parts) >= 4) {
            $numero_commande = $parts[0] . '-' . $parts[1] . '-' . $parts[2];
        } else {
            $numero_commande = $tx_ref;
        }

        // Vérifier le paiement via l'API Flutterwave
        $verification = $this->payment_gateway->verify_payment($tx_ref);

        if ($verification['success']) {
            $payment_data = $verification['data'];
            
            // Récupérer la commande
            $order = $this->db->where('numero_commande', $numero_commande)->get('commandes')->row_array();
            
            if ($order) {
                // Mettre à jour le statut de paiement
                $this->db->where('id_commande', $order['id_commande'])->update('commandes', [
                    'statut_paiement' => 'paye',
                    'date_paiement'   => date('Y-m-d H:i:s'),
                ]);
                
                // Enregistrer la transaction
                $this->payment_gateway->record_transaction($order, $payment_data);
                
                // Vider le panier du client
                $this->Home_model->clearCart($order['id_utilisateur']);
                
                $this->session->set_flashdata('success', 'Paiement confirmé avec succès ! Commande : ' . $numero_commande);
            }
        } else {
            // Paiement échoué — on garde le statut en_attente
            log_message('error', 'Flutterwave callback échoué pour ' . $tx_ref . ': ' . ($verification['error'] ?? 'unknown'));
            $this->session->set_flashdata('error', 'Le paiement n\'a pas pu être confirmé. Veuillez réessayer.');
        }

        redirect(base_url('payment/success/' . $numero_commande));
    }

    /**
     * Page de succès après paiement
     * GET /payment/success/{numero_commande}
     */
    public function success($numero_commande) {
        $data['settings'] = $this->Home_model->getSiteSettings();
        $data['main_categories'] = $this->Home_model->getMainCategories();
        $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
        $data['numero_commande'] = $numero_commande;
        
        $user_id = $this->session->userdata('id_utilisateur');
        
        if ($user_id) {
            $data['cart_count'] = $this->Home_model->getCartCount($user_id);
            $data['wishlist_count'] = $this->Home_model->getWishlistCount($user_id);
            $data['user_profils'] = $this->Home_model->getUserProfils($user_id);
            
            $data['order'] = $this->db->select('c.*, mp.description as mode_paiement_desc, mp.code as mode_paiement_code')
                ->from('commandes c')
                ->join('mode_payement mp', 'mp.id_mode_payement = c.id_mode_payement', 'left')
                ->where('c.numero_commande', $numero_commande)
                ->where('c.id_utilisateur', $user_id)
                ->get()->row_array();
                
            if (!empty($data['order'])) {
                $data['order_items'] = $this->db->select('*')
                    ->from('articles_commande')
                    ->where('id_commande', $data['order']['id_commande'])
                    ->get()->result_array();
                    
                $data['transaction'] = $this->db->where('reference_interne', $numero_commande)
                    ->order_by('date_creation', 'DESC')
                    ->limit(1)
                    ->get('transactions_paiement')->row_array();
            } else {
                $data['order_items'] = [];
                $data['transaction'] = null;
            }
        } else {
            $data['cart_count'] = 0;
            $data['wishlist_count'] = 0;
            $data['user_profils'] = [];
            $data['order'] = null;
            $data['order_items'] = [];
            $data['transaction'] = null;
        }
        
        $data['meta_title'] = 'Paiement confirmé - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
        
        $this->load->view('includes/frontend/Header', $data);
        $this->load->view('Home/views/order_success', $data);
        $this->load->view('includes/frontend/Footer', $data);
    }

    /**
     * Webhook Flutterwave (notification serveur à serveur)
     * POST /payment/webhook
     * 
     * Sécurité : vérifie la signature HMAC du webhook
     */
    public function webhook() {
        $signature = $this->input->server('HTTP_VERIF_HASH', TRUE);
        $raw_body = file_get_contents('php://input');

        // Vérifier la signature du webhook
        if (!$this->payment_gateway->verify_webhook_signature($signature, $raw_body)) {
            log_message('error', 'Flutterwave webhook: Signature invalide');
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid signature']);
            return;
        }

        $payload = json_decode($raw_body, true);
        
        if (!$payload || !isset($payload['event'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid payload']);
            return;
        }

        // Traiter seulement les paiements réussis
        if ($payload['event'] === 'charge.completed' && isset($payload['data'])) {
            $data = $payload['data'];
            $tx_ref = $data['tx_ref'] ?? '';
            $status = $data['status'] ?? '';
            
            log_message('info', 'Flutterwave webhook reçu: ' . $tx_ref . ' | Status: ' . $status);
            
            if ($status === 'successful') {
                // Extraire le numéro de commande
                $parts = explode('-', $tx_ref);
                if (count($parts) >= 4) {
                    $numero_commande = $parts[0] . '-' . $parts[1] . '-' . $parts[2];
                } else {
                    $numero_commande = $tx_ref;
                }

                // Mettre à jour la commande si elle est encore en attente
                $order = $this->db->where('numero_commande', $numero_commande)
                    ->where('statut_paiement', 'en_attente')
                    ->get('commandes')->row_array();

                if ($order) {
                    $this->db->where('id_commande', $order['id_commande'])->update('commandes', [
                        'statut_paiement' => 'paye',
                        'date_paiement'   => date('Y-m-d H:i:s'),
                    ]);
                    
                    $this->payment_gateway->record_transaction($order, $data);
                    
                    // Vider le panier du client
                    $this->load->model('Home/Home_model');
                    $this->Home_model->clearCart($order['id_utilisateur']);
                    
                    log_message('info', 'Flutterwave webhook: Commande ' . $numero_commande . ' marquée comme payée');
                }
            }
        }

        http_response_code(200);
        echo json_encode(['status' => 'success']);
    }

    /**
     * Vérification manuelle d'un paiement
     * GET /payment/verify/{tx_ref}
     */
    public function verify($tx_ref = null) {
        $this->output->set_content_type('application/json');

        if (empty($tx_ref)) {
            echo json_encode(['success' => false, 'message' => 'Référence manquante']);
            return;
        }

        $result = $this->payment_gateway->verify_payment($tx_ref);
        echo json_encode($result);
    }
}
