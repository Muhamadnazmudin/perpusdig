<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kunjungan extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1,2]); // ADMIN & PETUGAS
        $this->load->model('Kunjungan_model');
    }

    public function index()
    {
        $data = [
            'title'      => 'Daftar Kunjungan Perpustakaan',
            'kunjungan'  => $this->Kunjungan_model->hari_ini()
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('kunjungan/index', $data);
        $this->load->view('templates/footer');
    }

    // dipanggil oleh JS scan QR
    public function scan()
{
    $token = $this->input->post('token', true);

    $exp = explode('|', $token);
    if (count($exp) < 3) {
        echo json_encode(['status' => false]);
        return;
    }

    $id_siswa = (int)$exp[2];

    $siswa = $this->db
        ->select('
            siswa.id_siswa,
            siswa.nis,
            siswa.nama_siswa,
            kelas.nama_kelas,
            jurusan.nama_jurusan
        ')
        ->join('kelas','kelas.id_kelas=siswa.id_kelas')
        ->join('jurusan','jurusan.id_jurusan=siswa.id_jurusan')
        ->where('siswa.id_siswa', $id_siswa)
        ->get('siswa')
        ->row();

    if (!$siswa) {
        echo json_encode(['status' => false]);
        return;
    }

    echo json_encode([
        'status' => true,
        'siswa'  => $siswa,
        'jam'    => date('H:i:s')
    ]);
}
public function simpan()
{
    $data = [
        'id_siswa' => $this->input->post('id_siswa'),
        'tanggal'  => date('Y-m-d'),
        'jam'      => $this->input->post('jam'),
        'tujuan'   => $this->input->post('tujuan')
    ];

    // anti dobel
    $cek = $this->db->get_where('kunjungan', [
        'id_siswa' => $data['id_siswa'],
        'tanggal'  => $data['tanggal']
    ])->row();

    if ($cek) {
        echo json_encode(['status'=>false,'msg'=>'Siswa sudah tercatat hari ini']);
        return;
    }

    $this->db->insert('kunjungan', $data);

    echo json_encode(['status'=>true,'msg'=>'Kunjungan berhasil disimpan']);
}

}
