<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('has_permission')) {
    function has_permission($permission)
    {
        $CI =& get_instance();
        $role = $CI->session->userdata('role');
        if ($role === 'super_admin') {
            return true;
        }
        $perms = $CI->session->userdata('permissions') ?? [];
        return in_array($permission, $perms);
    }
}

if (!function_exists('has_role')) {
    function has_role($roles)
    {
        $CI =& get_instance();
        $role = $CI->session->userdata('role');
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        return in_array($role, $roles);
    }
}

if (!function_exists('menu_visible')) {
    function menu_visible($required_permission = null, $required_roles = null)
    {
        if ($required_permission !== null && has_permission($required_permission)) {
            return true;
        }
        if ($required_roles !== null && has_role($required_roles)) {
            return true;
        }
        if ($required_permission === null && $required_roles === null) {
            return true;
        }
        return false;
    }
}
