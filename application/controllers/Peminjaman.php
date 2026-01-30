<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'Peminjaman_model',
            'Buku_fisik_model'
        ]);
    }

    // =======================
    // LIST PEMINJAMAN
    // =======================
    public function index()
    {
        $this->only_role([1]);
        $data['title'] = 'Peminjaman Buku';
        $data['peminjaman'] = $this->Peminjaman_model->get_all();

        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('peminjaman/index',$data);
        $this->load->view('templates/footer');
    }

    // =======================
    // FORM PINJAM
    // =======================
    public function tambah()
{
    $this->only_role([1]);

    $this->load->model('User_model');

    $data['title'] = 'Tambah Peminjaman';
    $data['buku']  = $this->Buku_fisik_model->get_all();
    $data['siswa'] = $this->User_model->get_siswa(); // ROLE = 3

    $this->load->view('templates/header',$data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('peminjaman/tambah',$data);
    $this->load->view('templates/footer');
}


    // =======================
    // SIMPAN PINJAM
    // =======================
    public function simpan()
{
    $this->only_role([1]);

    $id_user = $this->input->post('id_user', TRUE);
    $id_buku = $this->input->post('id_buku', TRUE);

    // 🔒 CEK LIMIT SISWA
    $total = $this->Peminjaman_model->count_pinjam_aktif($id_user);
    if ($total >= $this->config->item('max_pinjam')) {
        $this->session->set_flashdata(
            'error',
            'Siswa ini sudah mencapai limit peminjaman (maks '.$this->config->item('max_pinjam').' buku)'
        );
        redirect('peminjaman/tambah');
        return;
    }

    $buku = $this->Buku_fisik_model->get_by_id($id_buku);
    if (!$buku || $buku->stok < 1) {
        $this->session->set_flashdata('error','Stok buku habis');
        redirect('peminjaman/tambah');
        return;
    }

    $this->Peminjaman_model->insert([
        'id_user'             => $id_user,
        'id_buku'             => $id_buku,
        'tanggal_pinjam'      => date('Y-m-d'),
        'tanggal_jatuh_tempo' => date('Y-m-d', strtotime('+7 days')), /*ngarobah waktu pinjam +7 artina 7poe*/
        'status'              => 'dipinjam'
    ]);

    $this->db
        ->where('id_buku',$id_buku)
        ->set('stok','stok-1',FALSE)
        ->update('buku_fisik');

    $this->session->set_flashdata('success','Peminjaman berhasil ditambahkan');
    redirect('peminjaman');
}


    // =======================
    // KEMBALIKAN BUKU
    // =======================
    public function kembali($id)
    {
        $this->only_role([1]);
        $pinjam = $this->Peminjaman_model->get_by_id($id);

        if (!$pinjam || $pinjam->status !== 'dipinjam') {
            show_404();
        }

        // update peminjaman
        $this->Peminjaman_model->update($id,[
            'status' => 'kembali',
            'tanggal_kembali' => date('Y-m-d')
        ]);

        // tambah stok buku
        $this->db
            ->where('id_buku',$pinjam->id_buku)
            ->set('stok','stok+1',FALSE)
            ->update('buku_fisik');

        $this->session->set_flashdata('success','Buku berhasil dikembalikan');
        redirect('peminjaman');
    }
    public function pinjam($id_buku)
{
    $this->only_role([3]); // SISWA

    $id_user = $this->user['id_user'];

    // 🔒 CEK LIMIT
    $total = $this->Peminjaman_model->count_pinjam_aktif($id_user);
    if ($total >= $this->config->item('max_pinjam')) {
        $this->session->set_flashdata(
            'error',
            'Limit peminjaman tercapai (maksimal '.$this->config->item('max_pinjam').' buku)'
        );
        redirect('buku/detail/'.$id_buku);
        return;
    }

    $buku = $this->Buku_fisik_model->get_by_id($id_buku);
    if (!$buku || $buku->stok < 1) {
        show_error('Stok buku habis');
    }

    // cegah pinjam buku sama
    if ($this->Peminjaman_model->cek_pinjam_aktif($id_user, $id_buku)) {
        $this->session->set_flashdata('error','Buku ini masih kamu pinjam');
        redirect('buku/detail/'.$id_buku);
    }

    // simpan
    $this->Peminjaman_model->insert([
        'id_user'             => $id_user,
        'id_buku'             => $id_buku,
        'tanggal_pinjam'      => date('Y-m-d'),
        'tanggal_jatuh_tempo' => date('Y-m-d', strtotime('+2 days')),
        'status'              => 'dipinjam'
    ]);

    // kurangi stok
    $this->db
        ->where('id_buku',$id_buku)
        ->set('stok','stok-1',FALSE)
        ->update('buku_fisik');

    $this->session->set_flashdata('success','Buku berhasil dipinjam');
    redirect('peminjaman/daftar');
}

public function daftar()
{
    $this->only_role([3]);

    $data['title'] = 'Daftar Pinjaman';
    $data['peminjaman'] = $this->Peminjaman_model
        ->get_aktif_by_user($this->user['id_user']);

    $this->load->view('templates/header',$data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('peminjaman/daftar',$data);
    $this->load->view('templates/footer');
}
public function riwayat()
{
    $this->only_role([3]);

    $data['title'] = 'Riwayat Pinjaman';
    $data['peminjaman'] = $this->Peminjaman_model
        ->get_riwayat_by_user($this->user['id_user']);

    $this->load->view('templates/header',$data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('peminjaman/riwayat',$data);
    $this->load->view('templates/footer');
}

}
