<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buku extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([3]); // SISWA
        $this->load->model('Buku_fisik_model');
    }

    // LIST BUKU UNTUK SISWA
    public function index()
    {
        $data['title'] = 'Buku Perpustakaan';
        $data['buku']  = $this->Buku_fisik_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('buku_siswa/index', $data);
        $this->load->view('templates/footer');
    }

    // DETAIL BUKU UNTUK SISWA
    public function detail($id)
    {
        $data['buku'] = $this->Buku_fisik_model->get_detail($id);

        if (!$data['buku']) {
            show_404();
        }

        $data['title'] = 'Detail Buku';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('buku_siswa/detail', $data);
        $this->load->view('templates/footer');
    }
}
