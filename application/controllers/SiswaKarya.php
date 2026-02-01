<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SiswaKarya extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([3]); // SISWA
        $this->load->model('Karya_model');
    }

    /* ===================== INDEX ===================== */
    public function index()
    {
        $data = [
            'title' => 'Karya Saya',
            'karya' => $this->Karya_model
                            ->get_by_user($this->user['id_user'])
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('siswa/karya/index', $data);
        $this->load->view('templates/footer');
    }

    /* ===================== TAMBAH ===================== */
    public function tambah()
    {
        $data['title'] = 'Tambah Karya';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('siswa/karya/tambah', $data);
        $this->load->view('templates/footer');
    }

    /* ===================== SIMPAN ===================== */
    public function simpan()
    {
        $judul = trim($this->input->post('judul', true));
        $jenis = trim($this->input->post('jenis', true));

        if ($judul === '' || $jenis === '') {
            $this->session->set_flashdata('error','Data wajib belum lengkap');
            redirect('SiswaKarya/tambah');
            return;
        }

        if (empty($_FILES['file_local']['name'])) {
            $this->session->set_flashdata('error','File PDF wajib diupload');
            redirect('SiswaKarya/tambah');
            return;
        }

        $data = [
            'judul'      => $judul,
            'jenis'      => $jenis,
            'mapel'      => $this->input->post('mapel', true),
            'kelas'      => strtoupper($this->input->post('kelas', true)),
            'source'     => 'LOCAL',
            'file_local' => $this->_upload_karya(),
            'created_by' => $this->user['id_user'],
            'status'     => 'PENDING',
            'is_public'  => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('ebook', $data);

        $this->session->set_flashdata(
            'success',
            'Karya berhasil dikirim dan menunggu persetujuan admin'
        );

        redirect('SiswaKarya');
    }

    /* ===================== UPLOAD ===================== */
    private function _upload_karya()
    {
        $config = [
            'upload_path'   => './assets/uploads/ebook/',
            'allowed_types' => 'pdf',
            'max_size'      => 10240,
            'encrypt_name'  => true
        ];

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file_local')) {
            $this->session->set_flashdata(
                'error',
                $this->upload->display_errors()
            );
            redirect($this->agent->referrer());
            exit;
        }

        return $this->upload->data('file_name');
    }
    public function edit($id)
{
    $karya = $this->Karya_model->get_by_id($id);

    // tidak ditemukan / bukan miliknya
    if (
        !$karya ||
        $karya->created_by != $this->user['id_user']
    ) {
        show_404();
    }

    // ❌ tidak boleh edit jika sudah approved
    if ($karya->status === 'APPROVED') {
        $this->session->set_flashdata(
            'error',
            'Karya yang sudah disetujui tidak dapat diedit'
        );
        redirect('SiswaKarya');
    }

    $data = [
        'title' => 'Edit Karya',
        'karya' => $karya
    ];

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('siswa/karya/edit', $data);
    $this->load->view('templates/footer');
}
public function update($id)
{
    $karya = $this->Karya_model->get_by_id($id);

    if (
        !$karya ||
        $karya->created_by != $this->user['id_user'] ||
        $karya->status === 'APPROVED'
    ) {
        show_404();
    }

    $data = [
        'judul' => $this->input->post('judul', true),
        'jenis' => $this->input->post('jenis', true),
        'mapel' => $this->input->post('mapel', true),
        'status' => 'PENDING', // reset review
        'is_public' => 0
    ];

    $this->Karya_model->update_status($id, $data);

    $this->session->set_flashdata(
        'success',
        'Karya berhasil diperbarui dan menunggu review ulang'
    );

    redirect('SiswaKarya');
}

}
