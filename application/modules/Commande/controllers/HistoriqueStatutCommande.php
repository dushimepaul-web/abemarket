<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HistoriqueStatutCommande extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('HistoriqueStatutCommande_model');
        $this->load->model('Commande_model');
        $this->load->library('pagination');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    private function is_admin() {
        $user_id = $this->session->userdata('id_utilisateur');
        $this->db->select('up.id_profil')
                 ->from('utilisateur_profils up')
                 ->where('up.id_utilisateur', $user_id)
                 ->where_in('up.id_profil', [1, 2, 3]);
        return $this->db->get()->num_rows() > 0;
    }

    private function get_vendeur_id() {
        $user_id = $this->session->userdata('id_utilisateur');
        $vendeur = $this->db->where('id_utilisateur', $user_id)->get('vendeurs')->row();
        return $vendeur ? $vendeur->id_vendeur : null;
    }

    /**
     * Historique d'une commande
     */
    public function index($id_commande) {
        // Vérifier les droits
        $commande = $this->Commande_model->get_commande_by_id($id_commande);
        
        if (!$commande) {
            show_404();
        }
        
        $id_vendeur = $this->get_vendeur_id();
        $is_admin = $this->is_admin();
        
        if (!$is_admin && $id_vendeur) {
            // Vérifier si le vendeur a le droit de voir cette commande
            $article = $this->db->where('id_commande', $id_commande)
                                ->where('id_vendeur', $id_vendeur)
                                ->get('articles_commande')
                                ->row();
            if (!$article) {
                show_error('Accès non autorisé', 403);
            }
        } elseif (!$is_admin && !$id_vendeur) {
            // Client - vérifier que c'est sa commande
            $user_id = $this->session->userdata('id_utilisateur');
            if ($commande->id_utilisateur != $user_id) {
                show_error('Accès non autorisé', 403);
            }
        }
        
        $data['historique'] = $this->HistoriqueStatutCommande_model->get_by_commande($id_commande);
        $data['commande'] = $commande;
        $data['title'] = 'Historique des statuts - Commande #' . $commande->numero_commande;
        $data['is_admin'] = $is_admin;
        $data['is_vendeur'] = !$is_admin && $id_vendeur ? true : false;
        
        $this->load->view('historique_statut_list', $data);
    }

    /**
     * Ajouter un statut (AJAX)
     */
    public function add() {
        $this->output->set_content_type('application/json');
        
        $id_commande = $this->input->post('id_commande');
        $statut = $this->input->post('statut');
        $commentaire = $this->input->post('commentaire');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        
        if (empty($id_commande) || empty($statut)) {
            echo json_encode(['success' => false, 'message' => 'Données manquantes']);
            return;
        }
        
        // Vérifier les droits
        $commande = $this->Commande_model->get_commande_by_id($id_commande);
        
        if (!$commande) {
            echo json_encode(['success' => false, 'message' => 'Commande non trouvée']);
            return;
        }
        
        $id_vendeur = $this->get_vendeur_id();
        $is_admin = $this->is_admin();
        
        if (!$is_admin && $id_vendeur) {
            // Vérifier si le vendeur peut modifier cette commande
            $article = $this->db->where('id_commande', $id_commande)
                                ->where('id_vendeur', $id_vendeur)
                                ->get('articles_commande')
                                ->row();
            if (!$article) {
                echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
                return;
            }
        } elseif (!$is_admin && !$id_vendeur) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $data = [
            'id_commande' => $id_commande,
            'statut' => $statut,
            'commentaire' => $commentaire,
            'modifie_par' => $this->session->userdata('id_utilisateur'),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        $inserted = $this->HistoriqueStatutCommande_model->add($data);
        
        if ($inserted) {
            // Mettre à jour le statut de la commande
            $this->db->where('id_commande', $id_commande);
            $this->db->update('commandes', ['statut_commande' => $statut]);
            
            echo json_encode(['success' => true, 'message' => 'Statut ajouté avec succès']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout']);
        }
    }

    /**
     * Supprimer un historique (Admin uniquement)
     */
    public function delete($id) {
        $this->output->set_content_type('application/json');
        
        if (!$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $deleted = $this->HistoriqueStatutCommande_model->delete($id);
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Historique supprimé' : 'Erreur'
        ]);
    }

    /**
     * Exporter l'historique (Admin)
     */
    public function exporter($id_commande) {
        if (!$this->is_admin()) {
            show_error('Accès non autorisé', 403);
        }
        
        $historique = $this->HistoriqueStatutCommande_model->get_by_commande($id_commande);
        $commande = $this->Commande_model->get_commande_by_id($id_commande);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=historique_commande_' . $commande->numero_commande . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Date', 'Statut', 'Commentaire', 'Modifié par', 'Position GPS']);
        
        foreach ($historique as $h) {
            $modifie_par = '';
            if ($h->modifie_par) {
                $user = $this->db->where('id_utilisateur', $h->modifie_par)->get('utilisateurs')->row();
                $modifie_par = $user ? $user->prenom . ' ' . $user->nom : '#' . $h->modifie_par;
            }
            
            $gps = '';
            if ($h->latitude && $h->longitude) {
                $gps = $h->latitude . ', ' . $h->longitude;
            }
            
            fputcsv($output, [
                date('d/m/Y H:i:s', strtotime($h->date_creation)),
                $h->statut,
                $h->commentaire ?? '-',
                $modifie_par ?: 'Système',
                $gps ?: '-'
            ]);
        }
        
        fclose($output);
    }
}
?>