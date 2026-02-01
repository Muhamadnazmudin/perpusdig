<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Maintenance {

    public function run()
    {
        $CI =& get_instance();
        $CI->load->database();

        // cek maintenance mode
        $row = $CI->db
            ->get_where('settings', ['name' => 'maintenance_mode'])
            ->row();

        if (!$row || $row->value != '1') {
            return; // maintenance OFF
        }

        /**
         * =================================
         * AMBIL URI SEGMENT
         * =================================
         */
        $segment1 = $CI->uri->segment(1); // login
        $segment2 = $CI->uri->segment(2); // admin / null

        /**
         * =================================
         * IZINKAN LOGIN ADMIN SAJA
         * =================================
         */
        if ($segment1 === 'login' && $segment2 === 'admin') {
            return;
        }

        /**
         * =================================
         * IZINKAN ADMIN YANG SUDAH LOGIN
         * =================================
         */
        if (
            isset($CI->user) &&
            isset($CI->user['id_role']) &&
            $CI->user['id_role'] === 1
        ) {
            return;
        }

        // BLOK SEMUA SELAIN ADMIN
        $CI->output->set_status_header(503);
        echo $CI->load->view('maintenance', [], TRUE);
        exit;
    }
}
