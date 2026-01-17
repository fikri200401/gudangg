<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        $CI =& get_instance();
        return $CI->session->userdata('status') === 'login';
    }
}

if (!function_exists('check_login')) {
    function check_login() {
        $CI =& get_instance();
        if (!is_logged_in()) {
            redirect('login');
        }
    }
}

if (!function_exists('get_user_role')) {
    function get_user_role() {
        $CI =& get_instance();
        return $CI->session->userdata('role');
    }
}
