<?php
if (!function_exists('esc')) {
    function esc($data, $context = 'html') {
        if ($context === 'html') {
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }
}