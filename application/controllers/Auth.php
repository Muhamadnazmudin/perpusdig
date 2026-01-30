<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function login()
    {
        if ($this->session->userdata('login')) {
            redirect('dashboard');
        }

        if ($this->input->post()) {

            $username = $this->input->post('username', TRUE);
            $password = $this->input->post('password');

            // 🔑 ambil user dari tabel users (SEMUA ROLE)
            $user = $this->User_model->get_by_username($username);

            if ($user && password_verify($password, $user['password'])) {

                $this->session->set_userdata([
                    'login' => true,
                    'user'  => [
                        'id_user'  => $user['id_user'],
                        'id_role'  => $user['id_role'],
                        'username' => $user['username'],
                        'nama'     => $user['nama']
                    ]
                ]);

                redirect('dashboard');

            } else {
                $this->session->set_flashdata('error', 'Username atau password salah');
            }
        }

        $this->load->view('auth/login');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
