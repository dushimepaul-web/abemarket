<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @author:    dushime paul
 * Email:     dushimeyesupaulin@gmail.com
 * Date :     Le 20/01/2026
 */

class Settings extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') !== TRUE) {
            redirect('Admin');
        }
        // Vérifier les permissions (seul super_admin peut modifier les paramètres)
        $role = $this->session->userdata('role');
        if ($role !== 'super_admin') {
            show_error('Vous n\'avez pas les permissions nécessaires pour accéder à cette page.', 403);
        }
    }

    // Page des paramètres
    public function index()
    {
        $data['settings'] = $this->get_all_settings();
        $data['categories'] = $this->get_setting_categories();
        $this->load->view('settings_view', $data);
    }

    // Mettre à jour les paramètres
    public function update()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Paramètres texte
            $text_settings = [
                'site_name',
                'site_email',
                'site_phone',
                'site_address',
                'site_country',
                'site_youtube',
                'site_instagram',
                'site_facebook',
                'site_linkedln',
                'longitude',
                'latitude',
                'popup_image',
                'password_email16caractere'
            ];
            
            foreach ($text_settings as $key) {
                $value = $this->input->post($key);
                if ($value !== null) {
                    $this->update_setting($key, $value, 0);
                }
            }
            
            // Gestion du logo
            if (!empty($_FILES['site_logo']['name'])) {
                $upload = $this->upload_file($_FILES['site_logo'], 'site_logo');
                if ($upload) {
                    $this->update_setting('site_logo', $upload, 1);
                }
            }


            // Gestion de l'image popup (à ajouter après la gestion du favicon)
           if (!empty($_FILES['popup_image']['name'])) {
            $upload = $this->upload_file($_FILES['popup_image'], 'popup_image');
            if ($upload) {
             $this->update_setting('popup_image', $upload, 1);
            }
           }

            
            // Gestion du favicon
            if (!empty($_FILES['site_favicon']['name'])) {
                $upload = $this->upload_file($_FILES['site_favicon'], 'site_favicon');
                if ($upload) {
                    $this->update_setting('site_favicon', $upload, 1);
                }
            }
            
            $this->session->set_flashdata('success', 'Paramètres mis à jour avec succès.');
            redirect(base_url('Settings'));
        }
    }

    // Ajouter un nouveau paramètre via AJAX
    public function ajouter_parametre()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $key = trim($this->input->post('key'));
            $value = $this->input->post('value');
            $category = $this->input->post('category');
            $is_file = $this->input->post('is_file') ? 1 : 0;
            
            // Validation
            if (empty($key)) {
                echo json_encode(['success' => false, 'message' => 'La clé du paramètre est requise.']);
                return;
            }
            
            // Vérifier si le paramètre existe déjà
            $existing = $this->db->where('KeyValue', $key)->get('settings')->row_array();
            if ($existing) {
                echo json_encode(['success' => false, 'message' => 'Ce paramètre existe déjà.']);
                return;
            }
            
            // Gestion du fichier si c'est un upload
            if ($is_file && !empty($_FILES['file']['name'])) {
                $upload = $this->upload_file($_FILES['file'], $key);
                if ($upload) {
                    $value = $upload;
                }
            }
            
            // Obtenir le prochain ID
            $next_id = $this->db->select_max('IdSetting')->get('settings')->row()->IdSetting + 1;
            
            // Insérer le nouveau paramètre
            $inserted = $this->db->insert('settings', [
                'IdSetting' => $next_id,
                'KeyValue' => $key,
                'Value' => $value,
                'IsFile' => $is_file,
                'TitlePage' => $category
            ]);
            
            if ($inserted) {
                echo json_encode(['success' => true, 'message' => 'Paramètre ajouté avec succès.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout.']);
            }
            return;
        }
    }

    // Modifier un paramètre via AJAX
    public function modifier_parametre()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $id = $this->input->post('id');
            $value = $this->input->post('value');
            
            $setting = $this->db->where('IdSetting', $id)->get('settings')->row_array();
            
            if (!$setting) {
                echo json_encode(['success' => false, 'message' => 'Paramètre non trouvé.']);
                return;
            }
            
            $this->db->where('IdSetting', $id);
            $this->db->update('settings', ['Value' => $value]);
            
            echo json_encode(['success' => true, 'message' => 'Paramètre modifié avec succès.']);
            return;
        }
    }

    // Supprimer un paramètre via AJAX
    public function supprimer_parametre()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $id = $this->input->post('id');
            
            $setting = $this->db->where('IdSetting', $id)->get('settings')->row_array();
            
            if (!$setting) {
                echo json_encode(['success' => false, 'message' => 'Paramètre non trouvé.']);
                return;
            }
            
            // Supprimer le fichier si c'est un upload
            if ($setting['IsFile'] == 1 && $setting['Value']) {
                $file_path = FCPATH . 'attachments/Settings/' . $setting['Value'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            
            $this->db->where('IdSetting', $id);
            $this->db->delete('settings');
            
            echo json_encode(['success' => true, 'message' => 'Paramètre supprimé avec succès.']);
            return;
        }
    }

    // Récupérer tous les paramètres
    private function get_all_settings()
    {
        $settings = $this->db->get('settings')->result_array();
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting['KeyValue']] = [
                'value' => $setting['Value'],
                'is_file' => $setting['IsFile'],
                'title' => $setting['TitlePage'],
                'id' => $setting['IdSetting']
            ];
        }
        return $result;
    }

    // Récupérer les catégories
    private function get_setting_categories()
    {
        $categories = $this->db->query("SELECT DISTINCT TitlePage FROM settings WHERE TitlePage IS NOT NULL AND TitlePage != ''")->result_array();
        
        $result = [];
        foreach ($categories as $cat) {
            if (!empty($cat['TitlePage'])) {
                $result[] = $cat['TitlePage'];
            }
        }
        
        // Catégories par défaut
        $default_categories = ['Général', 'Réseaux sociaux', 'Coordonnées GPS', 'Email', 'API', 'Autres'];
        foreach ($default_categories as $cat) {
            if (!in_array($cat, $result)) {
                $result[] = $cat;
            }
        }
        
        return $result;
    }

    // Mettre à jour un paramètre
    private function update_setting($key, $value, $is_file = 0)
    {
        $existing = $this->db->where('KeyValue', $key)->get('settings')->row_array();
        
        if ($existing) {
            $this->db->where('KeyValue', $key);
            $this->db->update('settings', [
                'Value' => $value,
                'IsFile' => $is_file
            ]);
        } else {
            $next_id = $this->db->select_max('IdSetting')->get('settings')->row()->IdSetting + 1;
            $this->db->insert('settings', [
                'IdSetting' => $next_id,
                'KeyValue' => $key,
                'Value' => $value,
                'IsFile' => $is_file,
                'TitlePage' => 'Général'
            ]);
        }
    }

   private function upload_file($file, $key)
{
    // Pour popup_image, utiliser le dossier uploads/popup/
    if ($key == 'popup_image') {
        $ref_folder = FCPATH . 'uploads/popup/';
    } else {
        $ref_folder = FCPATH . 'attachments/Settings/';
    }
    
    if (!is_dir($ref_folder)) {
        mkdir($ref_folder, 0777, TRUE);
    }
    
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = date("YmdHis") . uniqid() . '.' . strtolower($ext);
    
    // Supprimer l'ancien fichier
    $old = $this->db->select('Value')->where('KeyValue', $key)->get('settings')->row_array();
    if ($old && $old['Value']) {
        $old_path = FCPATH . $old['Value'];
        if (file_exists($old_path)) {
            unlink($old_path);
        }
    }
    
    if (move_uploaded_file($file['tmp_name'], $ref_folder . $filename)) {
        if ($key == 'popup_image') {
            return 'uploads/popup/' . $filename;
        }
        return $filename;
    }
    
    return null;
}
}
?>