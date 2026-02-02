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
        $this->load->library('upload'); // 🔑 WAJIB
    }

    /* ===================== INDEX ===================== */
    public function index()
    {
        $this->load->library('pagination');

        $keyword = $this->input->get('q');
        $kelas   = $this->input->get('kelas');
        $mapel   = $this->input->get('mapel');

        $page  = is_numeric($this->input->get('page')) ? $this->input->get('page') : 0;
        $limit = 15;

        $total = $this->Ebook_model->count_filtered($keyword, $kelas, $mapel);

        $config['base_url'] = site_url('AdminEbook');
        $config['total_rows'] = $total;
        $config['per_page'] = $limit;
        $config['reuse_query_string'] = true;
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';

        $this->pagination->initialize($config);

        $data = [
            'title'      => 'Manajemen E-Book',
            'ebook'      => $this->Ebook_model->get_filtered($limit, $page, $keyword, $kelas, $mapel),
            'pagination' => $this->pagination->create_links()
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

    /* ===================== SIMPAN ===================== */
    public function simpan()
    {
        $judul  = trim($this->input->post('judul', true));
        $mapel  = trim($this->input->post('mapel', true));
        $kelas  = strtoupper(trim($this->input->post('kelas', true)));
        $source = $this->input->post('source', true);

        if ($judul === '' || $kelas === '' || !in_array($kelas, ['X','XI','XII','UMUM'])) {
            $this->session->set_flashdata('error','Data tidak valid');
            redirect('AdminEbook/tambah');
            return;
        }

        $data = [
            'judul'      => $judul,
            'mapel'      => $mapel,
            'kelas'      => $kelas,
            'source'     => $source,
            'cover'      => null,
            'created_at' => date('Y-m-d H:i:s')
        ];

        /* ===== DRIVE ===== */
        if ($source === 'DRIVE') {
            $drive_id = trim($this->input->post('drive_link', true));
            if ($drive_id === '') {
                $this->session->set_flashdata('error','File ID Google Drive wajib diisi');
                redirect('AdminEbook/tambah');
                return;
            }
            $data['file_drive'] = $drive_id;
            $data['file_local'] = null;
        }

        /* ===== LOCAL ===== */
        if ($source === 'LOCAL') {
            if (empty($_FILES['file_local']['name'])) {
                $this->session->set_flashdata('error','File ebook wajib diupload');
                redirect('AdminEbook/tambah');
                return;
            }
            $data['file_local'] = $this->_upload_ebook();
            $data['file_drive'] = null;
        }

        /* ===== COVER ===== */
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

    /* ===================== UPDATE ===================== */
    public function update($id)
    {
        $data = [
            'judul' => $this->input->post('judul', true),
            'mapel' => $this->input->post('mapel', true),
            'kelas' => strtoupper($this->input->post('kelas', true))
        ];

        if (!empty($_FILES['cover']['name'])) {
            $data['cover'] = $this->_upload_cover();
            if ($data['cover'] === false) return;
        }

        $this->db->where('id_ebook', $id)->update('ebook', $data);

        $this->session->set_flashdata('success','E-Book berhasil diperbarui');
        redirect('AdminEbook');
    }

    /* ===================== UPLOAD COVER ===================== */
    private function _upload_cover()
    {
        $config = [
            'upload_path'   => './assets/uploads/cover_ebook/',
            'allowed_types' => 'jpg|jpeg|png',
            'max_size'      => 2048,
            'encrypt_name'  => true
        ];

        $this->upload->initialize($config, true);

        if (!$this->upload->do_upload('cover')) {
            $this->session->set_flashdata('error', strip_tags($this->upload->display_errors()));
            redirect('AdminEbook/tambah');
            return false;
        }

        return $this->upload->data('file_name');
    }

    /* ===================== UPLOAD EBOOK ===================== */
    private function _upload_ebook()
    {
        $config = [
            'upload_path'   => './assets/uploads/ebook/',
            'allowed_types' => 'pdf',
            'max_size'      => 10240,
            'encrypt_name'  => true
        ];

        $this->upload->initialize($config, true);

        if (!$this->upload->do_upload('file_local')) {
            $this->session->set_flashdata('error', strip_tags($this->upload->display_errors()));
            redirect('AdminEbook/tambah');
            exit;
        }

        return $this->upload->data('file_name');
    }
    /* ===================== DELETE ===================== */
public function delete($id)
{
    $ebook = $this->Ebook_model->get_by_id($id);
    if (!$ebook) {
        show_404();
    }

    // 🔥 hapus file ebook local jika ada
    if ($ebook->source === 'LOCAL' && !empty($ebook->file_local)) {
        $path = FCPATH . 'assets/uploads/ebook/' . $ebook->file_local;
        if (file_exists($path)) {
            unlink($path);
        }
    }

    // 🔥 hapus cover jika ada
    if (!empty($ebook->cover)) {
        $cover = FCPATH . 'assets/uploads/cover_ebook/' . $ebook->cover;
        if (file_exists($cover)) {
            unlink($cover);
        }
    }

    // 🔥 hapus data DB
    $this->db->where('id_ebook', $id)->delete('ebook');

    $this->session->set_flashdata('success','E-Book berhasil dihapus');
    redirect('AdminEbook');
}

}
