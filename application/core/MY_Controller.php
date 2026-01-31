<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public $user = [];
    public $data = []; // 🔥 TAMBAH BARIS INI

    public function __construct()
    {
        parent::__construct();

        // 🔥 ABAIKAN REQUEST FILE (favicon.ico, dll)
        if (strpos($this->router->class, '.') !== false) {
            return;
        }

        $public_controllers = ['home', 'auth'];
        $current_controller = strtolower($this->router->class);

        if (!in_array($current_controller, $public_controllers, true)) {

            if (!$this->session->userdata('login')) {
                redirect('auth/login');
                exit;
            }

            $this->user = $this->session->userdata('user');

            if (!isset($this->user['id_role'])) {
                $this->session->sess_destroy();
                redirect('auth/login');
                exit;
            }

            $this->user['id_role'] = (int) $this->user['id_role'];
        }

        // 🔔 NOTIF PENGAJUAN PEMINJAMAN (ADMIN)
        if (!empty($this->user) && isset($this->user['id_role']) && $this->user['id_role'] === 1) {
            $this->load->model('Peminjaman_model');
            $this->data['total_menunggu'] = $this->Peminjaman_model->count_menunggu();
        } else {
            $this->data['total_menunggu'] = 0;
        }
    }

    protected function only_role(array $roles)
    {
        if (!in_array($this->user['id_role'], $roles, true)) {
            show_error('Akses ditolak', 403);
        }
    }
}
