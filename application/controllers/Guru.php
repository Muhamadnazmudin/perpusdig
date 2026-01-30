<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guru extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1]);
        $this->load->model('Guru_model');
    }

    public function index()
    {
        $data['title'] = 'Data Guru';
        $data['guru']  = $this->Guru_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('guru/index', $data);
        $this->load->view('templates/footer');
    }

    public function tambah()
{
    $nip = $this->input->post('nip');

    // simpan guru
    $this->Guru_model->insert([
        'nip'       => $nip,
        'nama_guru' => $this->input->post('nama_guru'),
        'email'     => $this->input->post('email')
    ]);

    // otomatis buat akun login
    $this->db->insert('users', [
        'id_role'  => 2, // guru
        'username' => $nip,
        'password' => password_hash($nip, PASSWORD_DEFAULT),
        'nama'     => $this->input->post('nama_guru'),
        'status'   => 'aktif'
    ]);

    $this->session->set_flashdata('success','Guru & akun login berhasil dibuat');
    redirect('guru');
}

    public function sync_guru_user()
{
    $guru = $this->db->get('guru')->result();

    foreach ($guru as $g) {
        $cek = $this->db->get_where('users', [
            'username' => $g->nip
        ])->row();

        if (!$cek) {
            $this->db->insert('users', [
                'id_role'  => 2,
                'username' => $g->nip,
                'password' => password_hash($g->nip, PASSWORD_DEFAULT),
                'nama'     => $g->nama_guru,
                'status'   => 'aktif'
            ]);
        }
    }

    echo "Sinkron guru → users selesai";
}

}
