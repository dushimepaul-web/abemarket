<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adresse extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Adresse_model');
        $this->load->model('Location_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    /**
     * Page principale - Liste des adresses
     */
    public function index() {
        $data['title'] = 'Gestion des adresses';
        
        $config['base_url'] = base_url('Adresse/index');
        $config['total_rows'] = $this->Adresse_model->count_adresses_by_user($this->session->userdata('id_utilisateur'));
        $config['per_page'] = 10;
        $config['full_tag_open'] = '<ul class="pagination justify-content-end mb-0">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');
        
        $this->pagination->initialize($config);
        
        $data['adresses'] = $this->Adresse_model->get_adresses_by_user(
            $this->session->userdata('id_utilisateur'),
            $config['per_page'],
            $this->uri->segment(3)
        );
        
        $data['provinces'] = $this->Location_model->get_all_provinces();
        
        $this->load->view('Adresse_View', $data);
    }

    /**
     * Page Ajouter/Modifier adresse
     */
    public function adresse_add_edit($id = null) {
        $data['title'] = $id ? 'Modifier l\'adresse' : 'Ajouter une adresse';
        $data['provinces'] = $this->Location_model->get_all_provinces();
        $data['communes'] = [];
        $data['quartiers'] = [];

        if ($id) {
            $data['adresse'] = $this->Adresse_model->get_adresse_by_id($id, $this->session->userdata('id_utilisateur'));
            if (!$data['adresse']) {
                show_404();
            }
            
            // Charger les communes de la province sélectionnée
            if ($data['adresse']->id_province) {
                $data['communes'] = $this->Location_model->get_communes_by_province($data['adresse']->id_province);
            }
            
            // Charger les quartiers de la commune sélectionnée
            if ($data['adresse']->id_commune) {
                $data['quartiers'] = $this->Location_model->get_quartiers_by_commune($data['adresse']->id_commune);
            }
        }

        $this->load->view('adresse_add_edit', $data);
    }

    /**
     * Page Détails adresse
     */
    public function adresse_detail($id) {
        $data['title'] = 'Détails de l\'adresse';
        $data['adresse'] = $this->Adresse_model->get_adresse_by_id($id, $this->session->userdata('id_utilisateur'));
        
        if (!$data['adresse']) {
            show_404();
        }
        
        $this->load->view('adresse_detail', $data);
    }

    /**
     * Ajouter une nouvelle adresse
     */
    public function ajouter() {
        $this->form_validation->set_rules('type_adresse', 'Type d\'adresse', 'required|in_list[domicile,travail,autre]');
        $this->form_validation->set_rules('nom_complet', 'Nom complet', 'required|max_length[200]');
        $this->form_validation->set_rules('telephone', 'Téléphone', 'required|max_length[20]');
        $this->form_validation->set_rules('id_province', 'Province', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('id_commune', 'Commune', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('adresse_ligne', 'Adresse', 'required|max_length[255]');

        if ($this->form_validation->run() == FALSE) {
            $response = ['success' => false, 'errors' => validation_errors()];
        } else {
            $data = [
                'id_utilisateur' => $this->session->userdata('id_utilisateur'),
                'type_adresse' => $this->input->post('type_adresse'),
                'nom_complet' => $this->input->post('nom_complet'),
                'telephone' => $this->input->post('telephone'),
                'id_province' => $this->input->post('id_province'),
                'id_commune' => $this->input->post('id_commune'),
                'id_quartier' => $this->input->post('id_quartier') ?: null,
                'adresse_ligne' => $this->input->post('adresse_ligne'),
                'point_repere' => $this->input->post('point_repere'),
                'latitude' => $this->input->post('latitude') ?: 0,
                'longitude' => $this->input->post('longitude') ?: 0,
                'instructions_livraison' => $this->input->post('instructions_livraison'),
                'est_par_defaut' => $this->input->post('est_par_defaut') ? 1 : 0,
                'est_actif' => 1
            ];

            if ($data['est_par_defaut'] == 1) {
                $this->Adresse_model->remove_default_flag($this->session->userdata('id_utilisateur'));
            }

            $id = $this->Adresse_model->ajouter_adresse($data);

            if ($id) {
                $response = ['success' => true, 'message' => 'Adresse ajoutée avec succès', 'id' => $id];
            } else {
                $response = ['success' => false, 'message' => 'Erreur lors de l\'ajout'];
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    /**
     * Modifier une adresse
     */
    public function modifier($id) {
        $adresse = $this->Adresse_model->get_adresse_by_id($id, $this->session->userdata('id_utilisateur'));

        if (!$adresse) {
            show_404();
        }

        $this->form_validation->set_rules('type_adresse', 'Type d\'adresse', 'required|in_list[domicile,travail,autre]');
        $this->form_validation->set_rules('nom_complet', 'Nom complet', 'required|max_length[200]');
        $this->form_validation->set_rules('telephone', 'Téléphone', 'required|max_length[20]');

        if ($this->form_validation->run() == FALSE) {
            $response = ['success' => false, 'errors' => validation_errors()];
        } else {
            $data = [
                'type_adresse' => $this->input->post('type_adresse'),
                'nom_complet' => $this->input->post('nom_complet'),
                'telephone' => $this->input->post('telephone'),
                'id_province' => $this->input->post('id_province'),
                'id_commune' => $this->input->post('id_commune'),
                'id_quartier' => $this->input->post('id_quartier') ?: null,
                'adresse_ligne' => $this->input->post('adresse_ligne'),
                'point_repere' => $this->input->post('point_repere'),
                'instructions_livraison' => $this->input->post('instructions_livraison'),
                'latitude' => $this->input->post('latitude') ?: 0,
                'longitude' => $this->input->post('longitude') ?: 0,
                'est_par_defaut' => $this->input->post('est_par_defaut') ? 1 : 0
            ];

            if ($data['est_par_defaut'] == 1) {
                $this->Adresse_model->remove_default_flag($this->session->userdata('id_utilisateur'), $id);
            }

            $updated = $this->Adresse_model->modifier_adresse($id, $data, $this->session->userdata('id_utilisateur'));

            if ($updated) {
                $response = ['success' => true, 'message' => 'Adresse modifiée avec succès'];
            } else {
                $response = ['success' => false, 'message' => 'Erreur lors de la modification'];
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    /**
     * Supprimer une adresse
     */
    public function supprimer($id) {
        $deleted = $this->Adresse_model->supprimer_adresse($id, $this->session->userdata('id_utilisateur'));

        if ($deleted) {
            $response = ['success' => true, 'message' => 'Adresse supprimée avec succès'];
        } else {
            $response = ['success' => false, 'message' => 'Erreur lors de la suppression'];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    /**
     * Définir adresse par défaut
     */
    public function set_default($id) {
        $this->Adresse_model->remove_default_flag($this->session->userdata('id_utilisateur'));
        $updated = $this->Adresse_model->set_adresse_par_defaut($id, $this->session->userdata('id_utilisateur'));

        if ($updated) {
            $response = ['success' => true, 'message' => 'Adresse par défaut mise à jour'];
        } else {
            $response = ['success' => false, 'message' => 'Erreur lors de la mise à jour'];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    /**
     * Récupérer les communes par province (AJAX)
     */
    public function get_communes($id_province) {
        $communes = $this->Location_model->get_communes_by_province($id_province);
        $this->output->set_content_type('application/json')->set_output(json_encode($communes));
    }

    /**
     * Récupérer les quartiers par commune (AJAX)
     */
    public function get_quartiers($id_commune) {
        $quartiers = $this->Location_model->get_quartiers_by_commune($id_commune);
        $this->output->set_content_type('application/json')->set_output(json_encode($quartiers));
    }

    /**
     * Exporter les adresses en CSV
     */
    public function exporter() {
        $ids = $this->input->get('ids');
        
        if ($ids) {
            $ids_array = explode(',', $ids);
            $adresses = $this->Adresse_model->get_adresses_by_ids($ids_array, $this->session->userdata('id_utilisateur'));
        } else {
            $adresses = $this->Adresse_model->get_adresses_by_user($this->session->userdata('id_utilisateur'));
        }

        $this->load->dbutil();
        $this->load->helper('download');

        $delimiter = ",";
        $newline = "\r\n";
        $enclosure = '"';

        $data = [];
        $headers = ['ID', 'Type', 'Nom complet', 'Téléphone', 'Province', 'Commune', 'Quartier', 'Adresse', 'Point de repère', 'Latitude', 'Longitude', 'Instructions', 'Par défaut', 'Date création'];
        $data[] = $headers;

        foreach ($adresses as $adresse) {
            $row = [
                $adresse->id_adresse,
                $adresse->type_adresse,
                $adresse->nom_complet,
                $adresse->telephone,
                $adresse->province_name,
                $adresse->commune_name,
                $adresse->quartier_name,
                $adresse->adresse_ligne,
                $adresse->point_repere,
                $adresse->latitude,
                $adresse->longitude,
                $adresse->instructions_livraison,
                $adresse->est_par_defaut ? 'Oui' : 'Non',
                date('d/m/Y H:i', strtotime($adresse->date_creation))
            ];
            $data[] = $row;
        }

        $csv = $this->dbutil->csv_from_result((object)['result' => (object)$data], $delimiter, $newline, $enclosure);
        force_download('adresses_' . date('Y-m-d') . '.csv', $csv);
    }

    /**
     * Importer des adresses depuis un CSV
     */
    public function importer() {
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] != UPLOAD_ERR_OK) {
            $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Aucun fichier valide n\'a été uploadé']));
            return;
        }

        $skip_first_row = $this->input->post('skip_first_row') == '1';
        $file = $_FILES['csv_file']['tmp_name'];
        
        if (($handle = fopen($file, "r")) !== FALSE) {
            $row_count = 0;
            $imported_count = 0;
            $errors = [];

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row_count++;

                if ($skip_first_row && $row_count == 1) {
                    continue;
                }

                if (count($data) < 7) {
                    $errors[] = "Ligne $row_count: Format incorrect";
                    continue;
                }

                list($nom_complet, $telephone, $adresse_ligne, $province_name, $commune_name, $quartier_name, $type_adresse) = $data;

                $nom_complet = trim($nom_complet);
                $telephone = trim($telephone);
                $adresse_ligne = trim($adresse_ligne);
                $province_name = trim($province_name);
                $commune_name = trim($commune_name);
                $type_adresse = in_array(trim($type_adresse), ['domicile', 'travail', 'autre']) ? trim($type_adresse) : 'domicile';

                if (empty($nom_complet) || empty($telephone) || empty($adresse_ligne)) {
                    $errors[] = "Ligne $row_count: Champs obligatoires manquants";
                    continue;
                }

                $province = $this->Location_model->get_province_by_name($province_name);
                if (!$province) {
                    $errors[] = "Ligne $row_count: Province '$province_name' non trouvée";
                    continue;
                }

                $commune = $this->Location_model->get_commune_by_name($commune_name, $province->id_province);
                if (!$commune) {
                    $errors[] = "Ligne $row_count: Commune '$commune_name' non trouvée";
                    continue;
                }

                $quartier_id = null;
                if (!empty($quartier_name)) {
                    $quartier = $this->Location_model->get_quartier_by_name($quartier_name, $commune->id_commune);
                    if ($quartier) {
                        $quartier_id = $quartier->id_quartier;
                    }
                }

                $address_data = [
                    'id_utilisateur' => $this->session->userdata('id_utilisateur'),
                    'type_adresse' => $type_adresse,
                    'nom_complet' => $nom_complet,
                    'telephone' => $telephone,
                    'id_province' => $province->id_province,
                    'id_commune' => $commune->id_commune,
                    'id_quartier' => $quartier_id,
                    'adresse_ligne' => $adresse_ligne,
                    'est_par_defaut' => 0,
                    'est_actif' => 1,
                    'latitude' => 0,
                    'longitude' => 0
                ];

                if ($this->Adresse_model->ajouter_adresse($address_data)) {
                    $imported_count++;
                } else {
                    $errors[] = "Ligne $row_count: Erreur lors de l'insertion";
                }
            }
            fclose($handle);

            if ($imported_count > 0) {
                $message = "$imported_count adresse(s) importée(s) avec succès.";
                if (!empty($errors)) {
                    $message .= " " . count($errors) . " erreur(s) rencontrée(s).";
                }
                $response = ['success' => true, 'message' => $message, 'errors' => $errors];
            } else {
                $response = ['success' => false, 'message' => 'Aucune adresse n\'a été importée', 'errors' => $errors];
            }
        } else {
            $response = ['success' => false, 'message' => 'Impossible d\'ouvrir le fichier CSV'];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}
?>