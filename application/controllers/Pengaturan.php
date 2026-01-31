<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1]); // ADMIN ONLY
        $this->load->model('Pengaturan_model');
    }

    /* ===================== SEKOLAH ===================== */
    public function sekolah()
    {
        $data = [
            'title'   => 'Pengaturan Sekolah',
            'sekolah' => $this->Pengaturan_model->get()
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $this->data);
        $this->load->view('templates/topbar', $this->data);
        $this->load->view('sekolah/pengaturan', $data);
        $this->load->view('templates/footer');
    }

    public function simpan_sekolah()
{
    $data = [
        'nama_sekolah' => $this->input->post('nama_sekolah', true),
        'npsn' => $this->input->post('npsn', true),
        'alamat_sekolah' => $this->input->post('alamat_sekolah', true),
        'email_sekolah' => $this->input->post('email_sekolah', true),
        'telp_sekolah' => $this->input->post('telp_sekolah', true),
        'website_sekolah' => $this->input->post('website_sekolah', true),
        'akreditasi' => $this->input->post('akreditasi', true),

        'kepala_sekolah' => $this->input->post('kepala_sekolah', true),
        'nip_kepala_sekolah' => $this->input->post('nip_kepala_sekolah', true),
        'kepala_perpustakaan' => $this->input->post('kepala_perpustakaan', true),
        'nip_kepala_perpustakaan' => $this->input->post('nip_kepala_perpustakaan', true),
        'petugas_perpustakaan' => $this->input->post('petugas_perpustakaan', true),
        'nip_petugas_perpustakaan' => $this->input->post('nip_petugas_perpustakaan', true),
    ];

    // upload logo (opsional)
    if (!empty($_FILES['logo_sekolah']['name'])) {
        $logo = $this->_upload_logo();
        if ($logo === false) return;
        $data['logo_sekolah'] = $logo;
    }

    $cek = $this->db->count_all('pengaturan_sekolah');

    if ($cek == 0) {
        $this->db->insert('pengaturan_sekolah', $data);
    } else {
        $this->db->update('pengaturan_sekolah', $data, ['id' => 1]);
    }

    $this->session->set_flashdata('success','Profil sekolah berhasil diperbarui');
    redirect('pengaturan/sekolah');
}
private function _upload_logo()
{
    $config = [
        'upload_path'   => './assets/uploads/logo/',
        'allowed_types' => 'jpg|jpeg|png',
        'max_size'      => 2048,
        'encrypt_name'  => true
    ];

    if (!is_dir($config['upload_path'])) {
        mkdir($config['upload_path'], 0777, true);
    }

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('logo_sekolah')) {
        $this->session->set_flashdata(
            'error',
            strip_tags($this->upload->display_errors())
        );
        redirect('pengaturan/sekolah?edit=1');
        return false;
    }

    return $this->upload->data('file_name');
}

}
