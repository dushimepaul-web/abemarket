<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Fonction d'échappement HTML pour CI3
if (!function_exists('esc')) {
    function esc($string, $context = 'html') {
        if ($context === 'html') {
            return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
        } elseif ($context === 'attr') {
            return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
        } elseif ($context === 'url') {
            return urlencode($string);
        } elseif ($context === 'js') {
            return json_encode($string, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        }
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}