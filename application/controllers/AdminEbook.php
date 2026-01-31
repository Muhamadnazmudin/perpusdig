<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class AdminEbook extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1,2]); // ADMIN & GURU
        $this->load->model('Ebook_model');
    }

    /* ===================== INDEX ===================== */
    public function index()
    {
        $data = [
            'title' => 'Manajemen E-Book',
            'ebook' => $this->Ebook_model->get_all()
        ];

        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar', $this->data);
$this->load->view('templates/topbar', $this->data);
        $this->load->view('admin/ebook/index',$data);
        $this->load->view('templates/footer');
    }

    /* ===================== TAMBAH ===================== */
    public function tambah()
    {
        $data['title'] = 'Tambah E-Book';

        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('admin/ebook/tambah',$data);
        $this->load->view('templates/footer');
    }

    public function simpan()
    {
        $file_id = trim($this->input->post('drive_link', true));

        if ($file_id === '') {
            $this->session->set_flashdata('error','File ID Google Drive wajib diisi');
            redirect('AdminEbook/tambah');
            return;
        }

        $data = [
            'judul'      => $this->input->post('judul', true),
            'mapel'      => $this->input->post('mapel', true),
            'kelas'      => strtoupper($this->input->post('kelas', true)), // X/XI/XII/UMUM
            'file_drive' => $file_id,
            'created_at' => date('Y-m-d H:i:s')
        ];

        // upload cover (opsional)
        if (!empty($_FILES['cover']['name'])) {
            $data['cover'] = $this->_upload_cover();
            if ($data['cover'] === false) return;
        }

        $this->db->insert('ebook', $data);

        $this->session->set_flashdata('success','E-Book berhasil ditambahkan');
        redirect('AdminEbook');
    }

    /* ===================== EDIT ===================== */
    public function edit($id)
    {
        $ebook = $this->Ebook_model->get_by_id($id);
        if (!$ebook) show_404();

        $data = [
            'title' => 'Edit E-Book',
            'ebook' => $ebook
        ];

        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('admin/ebook/edit',$data);
        $this->load->view('templates/footer');
    }

    public function update($id)
    {
        $data = [
            'judul' => $this->input->post('judul', true),
            'mapel' => $this->input->post('mapel', true),
            'kelas' => strtoupper($this->input->post('kelas', true))
        ];

        // update drive id (opsional)
        $file_id = trim($this->input->post('drive_link', true));
        if ($file_id !== '') {
            $data['file_drive'] = $file_id;
        }

        // update cover (opsional)
        if (!empty($_FILES['cover']['name'])) {
            $data['cover'] = $this->_upload_cover();
            if ($data['cover'] === false) return;
        }

        $this->db->where('id_ebook', $id)->update('ebook', $data);

        $this->session->set_flashdata('success','E-Book berhasil diperbarui');
        redirect('AdminEbook');
    }

    /* ===================== DELETE ===================== */
    public function delete($id)
    {
        $this->only_role([1]); // ADMIN ONLY
        $this->Ebook_model->delete($id);

        $this->session->set_flashdata('success','E-Book berhasil dihapus');
        redirect('AdminEbook');
    }

    /* ===================== IMPORT ===================== */
    public function import()
    {
        $this->only_role([1]); // ADMIN

        if (empty($_FILES['file']['name'])) {
            $this->session->set_flashdata('error','File belum dipilih');
            redirect('AdminEbook');
            return;
        }

        require FCPATH.'vendor/autoload.php';

        $file = $_FILES['file']['tmp_name'];
        $ext  = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        $reader = ($ext === 'csv') ? new Csv() : new Xlsx();
        $sheet  = $reader->load($file)->getActiveSheet()->toArray();

        $insert = 0;

        foreach ($sheet as $i => $row) {

    if ($i == 0) continue;
    if (empty($row[0])) continue;

    $drive_id = $this->extract_drive_id($row[3]);
    if (!$drive_id) continue;

    $kelas = strtoupper(trim($row[1]));
    if (!in_array($kelas, ['X','XI','XII','UMUM'])) continue;

    $this->db->insert('ebook', [
        'judul'      => trim($row[0]),
        'kelas'      => $kelas,
        'mapel'      => ucwords(strtolower(trim($row[2]))),
        'file_drive' => $drive_id,
        'created_at' => date('Y-m-d H:i:s')
    ]);

    $insert++;
}

        $this->session->set_flashdata(
            'success',
            "Import berhasil: {$insert} data"
        );

        redirect('AdminEbook');
    }

    /* ===================== PRIVATE ===================== */
    private function _upload_cover()
    {
        $config = [
            'upload_path'   => './assets/uploads/cover_ebook/',
            'allowed_types' => 'jpg|jpeg|png',
            'max_size'      => 2048,
            'encrypt_name'  => true
        ];

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('cover')) {
            $this->session->set_flashdata(
                'error',
                $this->upload->display_errors()
            );
            redirect($this->agent->referrer());
            return false;
        }

        return $this->upload->data('file_name');
    }
    private function extract_drive_id($input)
{
    $input = trim($input);

    // kalau sudah ID murni
    if (preg_match('/^[a-zA-Z0-9_-]{10,}$/', $input)) {
        return $input;
    }

    // file/d/ID
    if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $input, $match)) {
        return $match[1];
    }

    // open?id=ID
    if (preg_match('/id=([a-zA-Z0-9_-]+)/', $input, $match)) {
        return $match[1];
    }

    return null; // tidak valid
}

}
