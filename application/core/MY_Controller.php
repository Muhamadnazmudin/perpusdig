<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public $user = [];

    public function __construct()
    {
        parent::__construct();

        // CONTROLLER PUBLIK (TIDAK PERLU LOGIN)
        $public_controllers = ['home', 'auth'];

        $current_controller = strtolower($this->router->class);

        // JIKA BUKAN CONTROLLER PUBLIK → WAJIB LOGIN
        if (!in_array($current_controller, $public_controllers, true)) {

            if (!$this->session->userdata('login')) {
                redirect('auth/login');
                exit;
            }

            $this->user = $this->session->userdata('user');

            // HARD GUARD
            if (!isset($this->user['id_role'])) {
                $this->session->sess_destroy();
                redirect('auth/login');
                exit;
            }

            // CAST SEKALI DI SINI
            $this->user['id_role'] = (int) $this->user['id_role'];
        }
    }

    protected function only_role(array $roles)
    {
        if (!in_array($this->user['id_role'], $roles, true)) {
            show_error('Akses ditolak', 403);
        }
    }
}
