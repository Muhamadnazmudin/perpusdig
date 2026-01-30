<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;


class Siswa extends MY_Controller {

    private $per_page = 20;

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1]);

        $this->load->model([
            'Siswa_model',
            'Kelas_model',
            'Jurusan_model'
        ]);

        $this->load->library('pagination');

        // phpqrcode (load sekali)
        require_once APPPATH . 'libraries/phpqrcode/phpqrcode.php';
    }

    /* =========================================================
     * INDEX + PAGINATION
     * ========================================================= */
    public function index($offset = 0)
    {
        $total = $this->Siswa_model->count_all();

        $config['base_url']    = site_url('siswa/index');
        $config['total_rows']  = $total;
        $config['per_page']    = $this->per_page;
        $config['uri_segment'] = 3;

        // bootstrap pagination
        $config['full_tag_open']  = '<nav><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['num_tag_open']   = '<li class="page-item">';
        $config['num_tag_close']  = '</li>';
        $config['cur_tag_open']   = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']  = '</span></li>';
        $config['attributes']     = ['class' => 'page-link'];

        $this->pagination->initialize($config);

        $data['title']      = 'Data Siswa';
        $data['siswa']      = $this->Siswa_model->get_limit($this->per_page, $offset);
        $data['kelas']      = $this->Kelas_model->get_all();
        $data['jurusan']    = $this->Jurusan_model->get_all();
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('siswa/index', $data);
        $this->load->view('templates/footer');
    }

    /* =========================================================
     * TAMBAH SISWA
     * ========================================================= */
    public function tambah()
    {
        $nis = $this->input->post('nis');

        $this->db->insert('siswa', [
            'nis'        => $nis,
            'nama_siswa' => $this->input->post('nama_siswa'),
            'id_kelas'   => $this->input->post('id_kelas'),
            'id_jurusan' => $this->input->post('id_jurusan')
        ]);

        $id_siswa = $this->db->insert_id();

        // generate QR (sekali)
        $qr_path = $this->generate_qr_siswa($id_siswa, $nis);

        $this->db->where('id_siswa', $id_siswa)
                 ->update('siswa', ['qr_code' => $qr_path]);

        // akun login
        $this->db->insert('users', [
            'id_role'  => 3,
            'username' => $nis,
            'password' => password_hash($nis, PASSWORD_DEFAULT),
            'nama'     => $this->input->post('nama_siswa'),
            'status'   => 'aktif'
        ]);

        $this->session->set_flashdata('success', 'Siswa berhasil ditambahkan');
        redirect('siswa');
    }

    /* =========================================================
     * EDIT SISWA (QR TIDAK DIUBAH)
     * ========================================================= */
    public function edit()
    {
        $id = $this->input->post('id_siswa');

        $this->db->where('id_siswa', $id)
                 ->update('siswa', [
                     'nis'        => $this->input->post('nis'),
                     'nama_siswa' => $this->input->post('nama_siswa'),
                     'id_kelas'   => $this->input->post('id_kelas'),
                     'id_jurusan' => $this->input->post('id_jurusan')
                 ]);

        $this->session->set_flashdata('success', 'Data siswa diperbarui');
        redirect('siswa');
    }

    /* =========================================================
     * HAPUS SISWA
     * ========================================================= */
    public function hapus($id)
    {
        $siswa = $this->db->get_where('siswa', ['id_siswa' => $id])->row();

        if ($siswa && $siswa->qr_code && file_exists(FCPATH.$siswa->qr_code)) {
            unlink(FCPATH.$siswa->qr_code);
        }

        $this->db->delete('siswa', ['id_siswa' => $id]);
        $this->db->delete('users', ['username' => $siswa->nis]);

        $this->session->set_flashdata('success', 'Siswa berhasil dihapus');
        redirect('siswa');
    }

    /* =========================================================
     * IMPORT SISWA (CSV / XLSX – TANPA REGENERATE QR)
     * ========================================================= */
    public function import()
{
    $this->only_role([1]); // ADMIN

    if (empty($_FILES['file']['name'])) {
        $this->session->set_flashdata('error','File belum dipilih');
        redirect('siswa');
        return;
    }

    // sama persis seperti AdminEbook
    require FCPATH.'vendor/autoload.php';

    $file = $_FILES['file']['tmp_name'];
    $ext  = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    if ($ext !== 'xlsx') {
        $this->session->set_flashdata('error','File harus Excel (.xlsx)');
        redirect('siswa');
        return;
    }

    $reader = new Xlsx();
    $sheet  = $reader->load($file)->getActiveSheet()->toArray();

    $insert = 0;

    foreach ($sheet as $i => $row) {

        // skip header
        if ($i == 0) continue;

        // validasi kolom
        if (empty($row[0]) || empty($row[1])) continue;

        $nis        = trim((string)$row[0]); // penting: string
        $nama       = trim($row[1]);
        $id_kelas   = (int)$row[2];
        $id_jurusan = (int)$row[3];

        if ($nis === '' || $nama === '') continue;

        // skip jika NIS sudah ada
        $cek = $this->db->get_where('siswa', ['nis' => $nis])->row();
        if ($cek) continue;

        // insert siswa
        $this->db->insert('siswa', [
            'nis'        => $nis,
            'nama_siswa' => $nama,
            'id_kelas'   => $id_kelas,
            'id_jurusan' => $id_jurusan
        ]);

        $id_siswa = $this->db->insert_id();

        // generate QR (sekali)
        $qr = $this->generate_qr_siswa($id_siswa, $nis);

        $this->db->where('id_siswa', $id_siswa)
                 ->update('siswa', ['qr_code' => $qr]);

        // akun login
        $this->db->insert('users', [
            'id_role'  => 3,
            'username' => $nis,
            'password' => password_hash($nis, PASSWORD_DEFAULT),
            'nama'     => $nama,
            'status'   => 'aktif'
        ]);

        $insert++;
    }

    $this->session->set_flashdata(
        'success',
        "Import siswa berhasil: {$insert} data"
    );

    redirect('siswa');
}


    /* =========================================================
     * GENERATE QR (PRIVATE)
     * ========================================================= */
    private function generate_qr_siswa($id_siswa, $nis)
    {
        $dir = FCPATH . 'assets/qrcode/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $token = 'PERPUS|SISWA|' . $id_siswa . '|' . md5($nis . time());
        $file  = 'siswa_' . $id_siswa . '.png';

        QRcode::png($token, $dir.$file, QR_ECLEVEL_L, 6);

        return 'assets/qrcode/' . $file;
    }
}
