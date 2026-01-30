<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1]);
        $this->load->model('Kategori_model');
    }

    public function index()
    {
        $data['title'] = 'Kategori Buku';
        $data['kategori'] = $this->Kategori_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('kategori/index', $data);
        $this->load->view('templates/footer');
    }

    public function simpan()
    {
        $this->Kategori_model->insert([
            'nama_kategori' => $this->input->post('nama_kategori', TRUE)
        ]);

        $this->session->set_flashdata('success', 'Kategori berhasil ditambahkan');
        redirect('kategori');
    }
}
