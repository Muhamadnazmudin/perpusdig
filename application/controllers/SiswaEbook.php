<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SiswaEbook extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([3]); // SISWA
        $this->load->model('Ebook_model');
        $this->load->library('pagination');
        $this->load->model('Riwayat_model');

    }

    public function index()
{
    $kelas = $this->input->get('kelas', TRUE);
    $mapel = $this->input->get('mapel', TRUE);

    $limit  = 10;
    $offset = $this->input->get('per_page') ?? 0;

    $total = $this->Ebook_model->count_filtered($kelas, $mapel);

    $config['base_url'] = site_url('SiswaEbook') . '?' . http_build_query([
        'kelas' => $kelas,
        'mapel' => $mapel
    ]);
    $config['total_rows'] = $total;
    $config['per_page']  = $limit;
    $config['page_query_string'] = TRUE;

    $this->pagination->initialize($config);

    $data = [
        'title'         => 'E-Book Digital',
        'ebook'         => $this->Ebook_model->get_filtered($limit, $offset, $kelas, $mapel),
        'pagination'    => $this->pagination->create_links(),
        'mapel_list'    => $this->Ebook_model->get_mapel_unik(),
        'filter_kelas'  => $kelas,
        'filter_mapel'  => $mapel
    ];

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('siswa/ebook/index', $data);
    $this->load->view('templates/footer');
}

   public function baca($id)
{
    $this->only_role([3]); // SISWA

    $ebook = $this->Ebook_model->get_by_id($id);
    if (!$ebook) {
        show_404();
    }

    // ================= RIWAYAT BACA =================
    $this->Riwayat_model->save_or_update(
        $this->user['id_user'],
        $id
    );
    // ================================================

    $data['title'] = $ebook->judul;
    $data['ebook'] = $ebook;

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('siswa/ebook/baca', $data);
    $this->load->view('templates/footer');
}
public function riwayat()
{
    $this->only_role([3]);

    $data = [
        'title' => 'Riwayat Bacaan Saya',
        'ebook' => $this->Riwayat_model
                        ->get_by_user($this->user['id_user'])
    ];

    $this->load->view('templates/header',$data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('siswa/ebook/riwayat',$data);
    $this->load->view('templates/footer');
}
public function favorit($id)
{
    $this->load->model('Favorit_model');
    $this->Favorit_model->toggle($this->user['id_user'], $id);
    redirect('SiswaEbook/baca/'.$id);
}
public function update_progress()
{
    $page = $this->input->post('page');
    $id_ebook = $this->input->post('id_ebook');

    $this->Riwayat_model->update_progress(
        $this->user['id_user'],
        $id_ebook,
        $page
    );
}


}
