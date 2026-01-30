<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public $user = [];

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
    }

    protected function only_role(array $roles)
    {
        if (!in_array($this->user['id_role'], $roles, true)) {
            show_error('Akses ditolak', 403);
        }
    }
}
