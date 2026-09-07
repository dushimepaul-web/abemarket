<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cpanel_email_lib {
    
    protected $CI;
    protected $from_email;
    protected $from_name;
    
    public function __construct() {
        $this->CI =& get_instance();
        
        // Configurer l'email expéditeur pour ABEMARKET
        $this->from_email = 'abemarket@abe.bi';
        $this->from_name = 'ABEMARKET';
        
        // Charger la librairie email
        $this->CI->load->library('email');
    }
    
    public function send_email($to, $subject, $message) {
        // Configuration pour sendmail (standard sur cPanel)
        $config = array(
            'protocol' => 'sendmail',
            'mailpath' => '/usr/sbin/sendmail',
            'charset' => 'utf-8',
            'mailtype' => 'html',
            'newline' => "\r\n",
            'crlf' => "\r\n",
            'wordwrap' => TRUE
        );
        
        $this->CI->email->initialize($config);
        $this->CI->email->clear(); // Important: nettoie les données précédentes
        $this->CI->email->from($this->from_email, $this->from_name);
        $this->CI->email->to($to);
        $this->CI->email->subject($subject);
        $this->CI->email->message($message);
        
        // Tentative d'envoi
        if ($this->CI->email->send()) {
            log_message('info', 'Email sent successfully to: ' . $to);
            return ['success' => true, 'status' => 200];
        } else {
            $error = $this->CI->email->print_debugger(['headers']);
            log_message('error', 'Email error to ' . $to . ': ' . $error);
            return ['success' => false, 'message' => $error];
        }
    }  
}
