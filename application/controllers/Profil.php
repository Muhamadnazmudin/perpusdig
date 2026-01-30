<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([3]); // SISWA
        $this->load->model('Siswa_model');
    }

    public function index()
{
    $user = $this->session->userdata('user');

    if (!$user || $user['id_role'] != 3) {
        show_error('Session login tidak valid');
    }

    $nis = $user['username']; // username = NIS

    $siswa = $this->db
        ->select('
            siswa.nis,
            siswa.nama_siswa,
            siswa.qr_code,
            kelas.nama_kelas,
            jurusan.nama_jurusan
        ')
        ->from('siswa')
        ->join('kelas', 'kelas.id_kelas = siswa.id_kelas')
        ->join('jurusan', 'jurusan.id_jurusan = siswa.id_jurusan')
        ->where('siswa.nis', $nis)
        ->get()
        ->row();

    if (!$siswa) {
        show_error('Data siswa tidak ditemukan');
    }

    $data = [
        'title' => 'Profil Siswa',
        'siswa' => $siswa
    ];

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('siswa/profil', $data);
    $this->load->view('templates/footer');
}

}
