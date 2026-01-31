<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }
private function generate_math_captcha()
{
    $a = rand(1, 10);
    $b = rand(1, 10);
    $ops = ['+', '-', 'x'];
    $op = $ops[array_rand($ops)];

    switch ($op) {
        case '+': $hasil = $a + $b; break;
        case '-': $hasil = $a - $b; break;
        case 'x': $hasil = $a * $b; break;
    }

    // simpan jawaban di session
    $this->session->set_userdata('math_captcha', $hasil);

    return "$a $op $b";
}

    public function login()
{
    if ($this->session->userdata('login')) {
        redirect('dashboard');
    }

    // ================= GET REQUEST =================
    if (!$this->input->post()) {
        $data['soal'] = $this->generate_math_captcha();
        $this->load->view('auth/login', $data);
        return;
    }

    // ================= POST REQUEST =================
    $username = $this->input->post('username', TRUE);
    $password = $this->input->post('password');
    $jawaban  = $this->input->post('captcha');

    // ambil jawaban captcha lama
    $captcha_benar = $this->session->userdata('math_captcha');

    if ($jawaban != $captcha_benar) {
        $this->session->set_flashdata('error', 'Jawaban hitungan salah !!!');
        redirect('login');
    }

    // hapus captcha SETELAH benar
    $this->session->unset_userdata('math_captcha');

    $user = $this->User_model->get_by_username($username);

    if ($user && $user['id_role'] == 3 && password_verify($password, $user['password'])) {

        $this->session->set_userdata([
            'login' => true,
            'theme' => $user['theme'] ?? 'light',
            'user' => [
                'id_user'  => $user['id_user'],
                'id_role'  => $user['id_role'],
                'username' => $user['username'],
                'nama'     => $user['nama']
            ]
        ]);

        redirect('dashboard');
    }

    $this->session->set_flashdata('error', 'Login siswa gagal');
    redirect('login');
}

public function login_admin()
{
    if ($this->session->userdata('login')) {
        redirect('dashboard');
    }

    if ($this->input->post()) {

        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password');

        $user = $this->User_model->get_by_username($username);

        // ❗ hanya ADMIN / PETUGAS (contoh: role 1 & 2)
        if ($user && in_array($user['id_role'], [1, 2]) 
            && password_verify($password, $user['password'])) {

            $this->session->set_userdata([
                'login' => true,
                'theme' => $user['theme'] ?? 'light',
                'user'  => [
                    'id_user'  => $user['id_user'],
                    'id_role'  => $user['id_role'],
                    'username' => $user['username'],
                    'nama'     => $user['nama']
                ]
            ]);

            redirect('dashboard');

        } else {
            $this->session->set_flashdata(
                'error',
                'Login admin gagal'
            );
        }
    }

    $this->load->view('auth/login_admin');
}


    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
