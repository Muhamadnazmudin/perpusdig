<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buku_fisik extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1]);
        $this->load->model('Buku_fisik_model');
    }

    public function index()
    {
        $data['title'] = 'Buku Fisik';
        $data['buku']  = $this->Buku_fisik_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('buku_fisik/index', $data);
        $this->load->view('templates/footer');
    }
    public function tambah()
{
    $this->load->model(['Kategori_model','Rak_model']);

    $data['title'] = 'Tambah Buku Fisik';
    $data['kategori'] = $this->Kategori_model->get_all();
    $data['rak'] = $this->Rak_model->get_all();

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('buku_fisik/tambah', $data);
    $this->load->view('templates/footer');
}

public function simpan()
{
    $isbn = trim($this->input->post('isbn', TRUE));
$isbn = ($isbn === '') ? null : $isbn;

    // ===== CEK ISBN DUPLIKAT =====
    if (!empty($isbn)) {
        if ($this->Buku_fisik_model->isbn_exists($isbn)) {
            $this->session->set_flashdata(
                'error',
                'ISBN sudah terdaftar. Gunakan ISBN lain.'
            );
            redirect('buku_fisik/tambah');
            return;
        }
    }

    $data = [
        'isbn'        => $isbn,
        'judul'       => $this->input->post('judul', TRUE),
        'penulis'     => $this->input->post('penulis', TRUE),
        'penerbit'    => $this->input->post('penerbit', TRUE),
        'tahun'       => $this->input->post('tahun', TRUE),
        'id_kategori' => $this->input->post('id_kategori', TRUE),
        'id_rak'      => $this->input->post('id_rak', TRUE),
        'stok'        => $this->input->post('stok', TRUE),
    ];

    // ===== UPLOAD COVER =====
    if (!empty($_FILES['cover']['name'])) {

        $config['upload_path']   = './uploads/cover/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048;
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('cover')) {
            $this->session->set_flashdata(
                'error',
                $this->upload->display_errors('', '')
            );
            redirect('buku_fisik/tambah');
            return;
        }

        $upload = $this->upload->data();
        $data['cover'] = $upload['file_name'];
    }

    $this->Buku_fisik_model->insert($data);

    $this->session->set_flashdata('success', 'Buku berhasil ditambahkan');
    redirect('buku_fisik');
}


public function edit($id)
{
    $this->load->model(['Kategori_model','Rak_model']);

    $data['title'] = 'Edit Buku Fisik';
    $data['buku'] = $this->Buku_fisik_model->get_by_id($id);
    $data['kategori'] = $this->Kategori_model->get_all();
    $data['rak'] = $this->Rak_model->get_all();

    if (!$data['buku']) {
        show_404();
    }

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('buku_fisik/edit', $data);
    $this->load->view('templates/footer');
}
public function update($id)
{
    $buku = $this->Buku_fisik_model->get_by_id($id);

    if (!$buku) {
        show_404();
    }

    $isbn = trim($this->input->post('isbn', TRUE));
$isbn = ($isbn === '') ? null : $isbn;

    // ===== CEK ISBN DUPLIKAT (KECUALI DIRI SENDIRI) =====
    if (!empty($isbn)) {
        if ($this->Buku_fisik_model->isbn_exists($isbn, $id)) {
            $this->session->set_flashdata(
                'error',
                'ISBN sudah digunakan oleh buku lain.'
            );
            redirect('buku_fisik/edit/'.$id);
            return;
        }
    }

    $data = [
        'isbn'        => $isbn,
        'judul'       => $this->input->post('judul', TRUE),
        'penulis'     => $this->input->post('penulis', TRUE),
        'penerbit'    => $this->input->post('penerbit', TRUE),
        'tahun'       => $this->input->post('tahun', TRUE),
        'id_kategori' => $this->input->post('id_kategori', TRUE),
        'id_rak'      => $this->input->post('id_rak', TRUE),
        'stok'        => $this->input->post('stok', TRUE),
    ];

    // ===== UPLOAD COVER BARU =====
    if (!empty($_FILES['cover']['name'])) {

        $config['upload_path']   = './uploads/cover/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048;
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('cover')) {
            $this->session->set_flashdata(
                'error',
                $this->upload->display_errors('', '')
            );
            redirect('buku_fisik/edit/'.$id);
            return;
        }

        // hapus cover lama
        if ($buku->cover && file_exists('./uploads/cover/'.$buku->cover)) {
            unlink('./uploads/cover/'.$buku->cover);
        }

        $upload = $this->upload->data();
        $data['cover'] = $upload['file_name'];
    }

    $this->Buku_fisik_model->update($id, $data);

    $this->session->set_flashdata('success', 'Buku berhasil diperbarui');
    redirect('buku_fisik');
}


public function hapus($id)
{
    $buku = $this->Buku_fisik_model->get_by_id($id);

    if (!$buku) {
        show_404();
    }

    $this->Buku_fisik_model->delete($id);

    $this->session->set_flashdata('success', 'Buku berhasil dihapus');
    redirect('buku-fisik');
}
public function detail($id)
{
    $data['buku'] = $this->Buku_fisik_model->get_detail($id);

    if (!$data['buku']) {
        show_404();
    }

    $data['title'] = 'Detail Buku Fisik';

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('buku_fisik/detail', $data);
    $this->load->view('templates/footer');
}
public function import()
{
    if (empty($_FILES['file_excel']['name'])) {
        $this->session->set_flashdata('error', 'File Excel wajib diupload');
        redirect('buku_fisik/tambah');
        return;
    }

    // ===== UPLOAD FILE EXCEL =====
    $config['upload_path']   = './uploads/excel/';
    $config['allowed_types'] = 'xls|xlsx';
    $config['encrypt_name']  = TRUE;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('file_excel')) {
        $this->session->set_flashdata(
            'error',
            $this->upload->display_errors('', '')
        );
        redirect('buku_fisik/tambah');
        return;
    }

    $file = $this->upload->data();

    // ===== LOAD PHPSPREADSHEET =====
    require FCPATH . 'vendor/autoload.php';

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file['full_path']);
    $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    $default_kategori = $this->input->post('default_kategori');
    $default_rak      = $this->input->post('default_rak');

    $insert = [];
    $inserted = 0;
    $skipped  = 0;

    foreach ($rows as $i => $row) {

        // skip header
        if ($i == 1) {
            continue;
        }

        $isbn = trim($row['A']);
$isbn = ($isbn === '') ? null : $isbn;

        // skip baris kosong
        if (empty($row['B'])) {
            continue;
        }

        // ===== CEK ISBN DUPLIKAT =====
        if (!empty($isbn)) {
            if ($this->Buku_fisik_model->isbn_exists($isbn)) {
                $skipped++;
                continue; // ⛔ SKIP ISBN DUPLIKAT
            }
        }

        $insert[] = [
            'isbn'        => $isbn,
            'judul'       => trim($row['B']),
            'penulis'     => trim($row['C']),
            'penerbit'    => trim($row['D']),
            'tahun'       => trim($row['E']),
            'id_kategori' => !empty($row['F']) ? $row['F'] : $default_kategori,
            'id_rak'      => !empty($row['G']) ? $row['G'] : $default_rak,
            'stok'        => trim($row['H']),
            'created_at'  => date('Y-m-d H:i:s')
        ];
    }

    // ===== INSERT KE DB =====
    if (!empty($insert)) {
        $this->Buku_fisik_model->insert_batch($insert);
        $inserted = count($insert);
    }

    // hapus file excel
    unlink($file['full_path']);

    // ===== FLASH MESSAGE RAPIH =====
    $this->session->set_flashdata(
        'success',
        "Import selesai. Berhasil: {$inserted} data, Dilewati (ISBN duplikat): {$skipped} data."
    );

    redirect('buku_fisik');
}


}
