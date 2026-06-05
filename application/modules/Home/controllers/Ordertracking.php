<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ordertracking extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Charger les modèles
        $this->load->model('Ordertracking_model');
        $this->load->model('Home_model');
        // Charger les helpers
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('file');
        
        // Démarrer la session si nécessaire
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Page principale de suivi de commande
     */
    public function index($numero_commande = null) {
         $data['settings'] = $this->Home_model->getSiteSettings();

        $data = [
            'commande' => null,
            'articles' => [],
            'historique' => [],
            'adresse' => null,
            'qr_code' => null,
            'positions_gps' => [],
            'transporteur' => null,
            'erreur' => null,
            'page_title' => 'Suivi de commande',
            'statuts_labels' => [
                'en_attente' => ['label' => 'En attente', 'icon' => 'ri-time-line', 'color' => 'warning'],
                'confirme' => ['label' => 'Confirmée', 'icon' => 'ri-checkbox-circle-line', 'color' => 'info'],
                'en_preparation' => ['label' => 'En préparation', 'icon' => 'ri-refresh-line', 'color' => 'primary'],
                'expedie' => ['label' => 'Expédiée', 'icon' => 'ri-truck-line', 'color' => 'success'],
                'en_livraison' => ['label' => 'En livraison', 'icon' => 'ri-car-line', 'color' => 'warning'],
                'livre' => ['label' => 'Livrée', 'icon' => 'ri-home-4-line', 'color' => 'success'],
                'annule' => ['label' => 'Annulée', 'icon' => 'ri-close-circle-line', 'color' => 'danger'],
                'retourne' => ['label' => 'Retournée', 'icon' => 'ri-arrow-go-back-line', 'color' => 'dark']
            ]
        ];
        
        // Récupérer le numéro de commande
        if ($numero_commande === null && $this->input->get('numero_commande')) {
            $numero_commande = $this->input->get('numero_commande');
        } elseif ($numero_commande === null && $this->input->post('numero_commande')) {
            $numero_commande = $this->input->post('numero_commande');
        }
        
        if ($numero_commande) {
            $commande = $this->Ordertracking_model->getCommandeByNumero($numero_commande);
            
            if ($commande) {
                if ($this->userCanViewCommande($commande['id_utilisateur'])) {
                    $data['commande'] = $commande;
                    $data['articles'] = $this->Ordertracking_model->getArticlesCommande($commande['id_commande']);
                    $data['historique'] = $this->Ordertracking_model->getHistoriqueStatuts($commande['id_commande']);
                    $data['qr_code'] = $this->Ordertracking_model->getQrCode($commande['id_commande']);
                    $data['positions_gps'] = $this->Ordertracking_model->getPositionsGPS($commande['id_commande']);
                    
                    if ($commande['id_adresse_livraison']) {
                        $data['adresse'] = $this->Ordertracking_model->getAdresse($commande['id_adresse_livraison']);
                    }
                    
                    // Transporteur info
                    if ($commande['transporteur_nom']) {
                        $data['transporteur'] = [
                            'nom' => $commande['transporteur_nom'],
                            'telephone' => $commande['transporteur_telephone'],
                            'whatsapp' => $commande['transporteur_whatsapp'],
                            'type_vehicule' => $commande['type_vehicule'],
                            'note_moyenne' => $commande['note_moyenne']
                        ];
                    }
                    
                    // Calculer les totaux pour le résumé
                    $data['sous_total'] = $commande['sous_total'];
                    $data['frais_livraison'] = $commande['frais_livraison'];
                    $data['montant_total'] = $commande['montant_total'];
                    $data['nombre_articles'] = array_sum(array_column($data['articles'], 'quantite'));
                    
                } else {
                    $data['erreur'] = "Vous n'avez pas l'autorisation de voir cette commande.";
                }
            } else {
                $data['erreur'] = "Commande non trouvée. Vérifiez le numéro de commande.";
            }
        }
        
        // Charger les vues
        $this->load->view('order_tracking_view', $data);
    }
    
    /**
     * Met à jour le statut (AJAX)
     */
    public function updateOrderStatus() {
        $this->output->set_content_type('application/json');
        
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            return $this->output->set_output(json_encode(['success' => false, 'message' => 'Méthode non autorisée']));
        }
        
        $id_commande = $this->input->post('id_commande');
        $nouveau_statut = $this->input->post('statut');
        $commentaire = $this->input->post('commentaire');
        
        if (!$id_commande || !$nouveau_statut) {
            return $this->output->set_output(json_encode(['success' => false, 'message' => 'Paramètres manquants']));
        }
        
        if (!$this->userHasRole(['super_admin', 'admin', 'livreur'])) {
            return $this->output->set_output(json_encode(['success' => false, 'message' => 'Permission refusée']));
        }
        
        $result = $this->Ordertracking_model->updateOrderStatus($id_commande, $nouveau_statut, $commentaire, $_SESSION['user_id']);
        
        return $this->output->set_output(json_encode($result));
    }
    
    /**
     * Confirme la livraison par QR (AJAX)
     */
    public function confirmDeliveryByQR() {
        $this->output->set_content_type('application/json');
        
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            return $this->output->set_output(json_encode(['success' => false, 'message' => 'Méthode non autorisée']));
        }
        
        $token = $this->input->post('token');
        
        if (!$token) {
            return $this->output->set_output(json_encode(['success' => false, 'message' => 'Token QR manquant']));
        }
        
        $result = $this->Ordertracking_model->confirmDeliveryByQR($token, $_SESSION['user_id']);
        
        return $this->output->set_output(json_encode($result));
    }
    
    /**
     * Génère la facture PDF
     */
    public function generateInvoice($id_commande) {
        // Logique de génération PDF
        $this->load->library('pdf');
        // ... code pour générer PDF
    }
    
    /**
     * Vérifie les droits d'accès
     */
    private function userCanViewCommande($id_utilisateur_commande) {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        
        if ($_SESSION['user_id'] == $id_utilisateur_commande) {
            return true;
        }
        
        if ($this->userHasRole(['super_admin', 'admin', 'moderateur', 'support'])) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Vérifie les rôles utilisateur
     */
    private function userHasRole($roles) {
        if (!isset($_SESSION['user_roles'])) {
            return false;
        }
        
        $user_roles = $_SESSION['user_roles'];
        return count(array_intersect($roles, $user_roles)) > 0;
    }
}