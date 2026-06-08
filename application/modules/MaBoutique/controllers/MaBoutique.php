<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MaBoutique extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') !== true) {
            redirect('Admin');
        }
        $role = $this->session->userdata('role');
        if (!in_array($role, ['vendeur', 'super_admin', 'admin'])) {
            show_error('Accès réservé aux vendeurs', 403);
        }
        $this->load->library('upload');
        $this->load->model('Model');
    }

    private function _get_vendeur()
    {
        $vendeur_id = $this->session->userdata('id_vendeur');
        if ($vendeur_id) {
            return $this->Model->readOne('vendeurs', ['id_vendeur' => $vendeur_id]);
        }
        $user_id = $this->session->userdata('id_utilisateur');
        return $this->Model->readOne('vendeurs', ['id_utilisateur' => $user_id]);
    }

    public function index()
    {
        $vendeur = $this->_get_vendeur();
        if (!$vendeur) {
            show_error('Aucune boutique trouvée pour votre compte', 404);
        }
        $vendeur_id = $vendeur['id_vendeur'];

        $config_paiement = $this->Model->readOne('config_paiement_vendeur', ['id_vendeur' => $vendeur_id]);
        $provinces = $this->Model->read('provinces', ['est_actif' => 1]);

        $data = [
            'vendeur' => $vendeur,
            'config_paiement' => $config_paiement ?: [],
            'provinces' => $provinces,
        ];

        $this->load->view('MaBoutique_View', $data);
    }

    public function update()
    {
        $vendeur = $this->_get_vendeur();
        if (!$vendeur) {
            show_error('Aucune boutique trouvée', 404);
        }
        $vendeur_id = $vendeur['id_vendeur'];
        $data = [
            'nom_boutique' => $this->input->post('nom_boutique'),
            'description' => $this->input->post('description'),
            'type_vendeur' => $this->input->post('type_vendeur'),
            'nom_entreprise' => $this->input->post('nom_entreprise') ?: null,
            'numero_nif' => $this->input->post('numero_nif') ?: null,
            'numero_rc' => $this->input->post('numero_rc') ?: null,
            'id_province' => $this->input->post('id_province') ?: null,
            'id_commune' => $this->input->post('id_commune') ?: null,
            'id_quartier' => $this->input->post('id_quartier') ?: null,
            'telephone' => $this->input->post('telephone') ?: null,
            'whatsapp' => $this->input->post('whatsapp') ?: null,
        ];

        if (!empty($this->input->post('nom_boutique'))) {
            $data['slug_boutique'] = url_title(trim($this->input->post('nom_boutique')), '-', true);
        }

        if (!empty($_FILES['logo']['name'])) {
            $config['upload_path'] = './uploads/logos/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
            $config['max_size'] = 2048;
            $config['encrypt_name'] = true;
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }
            $this->upload->initialize($config);
            if ($this->upload->do_upload('logo')) {
                $data['logo_boutique'] = 'uploads/logos/' . $this->upload->data('file_name');
            }
        }

        $this->Model->update('vendeurs', ['id_vendeur' => $vendeur_id], $data);

        $this->session->set_flashdata('success', 'Boutique mise à jour avec succès.');
        redirect('ma-boutique');
    }

    public function update_paiement()
    {
        $vendeur = $this->_get_vendeur();
        if (!$vendeur) {
            show_error('Aucune boutique trouvée', 404);
        }
        $vendeur_id = $vendeur['id_vendeur'];
        $config = $this->Model->readOne('config_paiement_vendeur', ['id_vendeur' => $vendeur_id]);

        $data = [
            'methode_principale' => $this->input->post('methode_principale'),
            'operateur_mobile' => $this->input->post('operateur_mobile'),
            'numero_mobile_money' => $this->input->post('numero_mobile_money'),
            'nom_abonne_mobile' => $this->input->post('nom_abonne_mobile'),
            'nom_titulaire' => $this->input->post('nom_titulaire'),
            'numero_compte' => $this->input->post('numero_compte'),
            'nom_banque' => $this->input->post('nom_banque'),
        ];

        if ($config) {
            $this->Model->update('config_paiement_vendeur', ['id_vendeur' => $vendeur_id], $data);
        } else {
            $data['id_vendeur'] = $vendeur_id;
            $this->Model->create('config_paiement_vendeur', $data);
        }

        $this->session->set_flashdata('success', 'Configuration de paiement mise à jour avec succès.');
        redirect('ma-boutique');
    }

    public function get_communes()
    {
        $id_province = $this->input->post('id_province');
        $communes = $this->Model->read('communes', ['id_province' => $id_province, 'est_actif' => 1]);
        $options = '<option value="">Sélectionner une commune</option>';
        foreach ($communes as $c) {
            $options .= '<option value="' . $c['id_commune'] . '">' . $c['commune_name'] . '</option>';
        }
        echo $options;
    }
}
