<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dashboard_model');
    }

    public function index()
{
    // ⬇️ AMBIL DARI MY_Controller (BENAR)
    $role = $this->user['id_role'];
    $user_id = $this->user['id_user'];

    $data['title'] = 'Dashboard';

    // ================= ADMIN =================
    if ($role === 1) {

        $data['stat'] = $this->Dashboard_model->get_stat_admin();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('dashboard/admin', $data);
        $this->load->view('templates/footer');
        return;
    }

    // ================= GURU =================
    if ($role === 2) {

        $data['stat'] = $this->Dashboard_model->get_stat_guru($user_id);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('dashboard/guru', $data);
        $this->load->view('templates/footer');
        return;
    }

    // ================= SISWA =================
    if ($role === 3) {

        $data['stat'] = $this->Dashboard_model->get_stat_siswa($user_id);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('dashboard/siswa', $data);
        $this->load->view('templates/footer');
        return;
    }

    // kalau role aneh
    show_error('Role tidak dikenal', 403);
}

}
