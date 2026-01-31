<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1]); // ADMIN
        $this->load->model([
            'Kunjungan_model',
            'Peminjaman_model',
            'Buku_fisik_model'
        ]);
    }

    /* ================= LAPORAN KUNJUNGAN ================= */
    public function kunjungan()
{
    $data = [
        'title'   => 'Laporan Kunjungan',
        'kelas'   => $this->db->get('kelas')->result(),
        'jurusan' => $this->db->get('jurusan')->result(),
        'laporan' => $this->Kunjungan_model->filter_laporan(
            $this->input->get()
        )
    ];

    $this->_view('laporan/kunjungan', $data);
}


    /* ================= LAPORAN PEMINJAMAN ================= */
    public function peminjaman()
    {
        $data = [
            'title'   => 'Laporan Peminjaman',
            'laporan' => $this->Peminjaman_model->get_laporan()
        ];

        $this->_view('laporan/peminjaman', $data);
    }

    /* ================= LAPORAN TOTAL BUKU ================= */
    public function buku()
{
    $data = [
        'title' => 'Laporan Total Buku',
        'buku'  => $this->Buku_fisik_model->get_rekap()
    ];

    $this->_view('laporan/buku', $data);
}


    /* ================= LAPORAN LAINNYA ================= */
    public function lainnya()
    {
        $data = [
            'title' => 'Laporan Lain-lain',
            'data'  => [
                'total_siswa'      => $this->db->count_all('siswa'),
                'total_kunjungan'  => $this->db->count_all('kunjungan'),
                'total_peminjaman' => $this->db->count_all('peminjaman')
            ]
        ];

        $this->_view('laporan/lainnya', $data);
    }

    /* ================= VIEW HELPER ================= */
    private function _view($view, $data)
    {
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $this->data);
        $this->load->view('templates/topbar', $this->data);
        $this->load->view($view, $data);
        $this->load->view('templates/footer');
    }
    public function kunjungan_excel()
{
    $data = $this->Kunjungan_model->filter_laporan($this->input->get());

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=laporan_kunjungan.xls");

    echo "Tanggal\tJam\tNIS\tNama\tKelas\tJurusan\tTujuan\n";
    foreach($data as $d){
        echo "{$d->tanggal}\t{$d->jam}\t{$d->nis}\t{$d->nama_siswa}\t{$d->nama_kelas}\t{$d->nama_jurusan}\t{$d->tujuan}\n";
    }
}
public function kunjungan_pdf()
{
    $data['laporan'] = $this->Kunjungan_model->filter_laporan($this->input->get());

    $html = $this->load->view('laporan/kunjungan_pdf', $data, true);

    $this->load->library('pdf');
    $this->pdf->loadHtml($html);
    $this->pdf->setPaper('A4', 'portrait'); // POTRAIT
    $this->pdf->render();
    $this->pdf->stream('laporan_kunjungan.pdf', ['Attachment' => false]);
}

public function peminjaman_excel()
{
    $data = $this->Peminjaman_model->get_laporan(
        $this->input->get('awal'),
        $this->input->get('akhir')
    );

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=laporan_peminjaman.xls");

    echo "Tanggal\tNama\tNIS\tKelas\tBuku\tStatus\n";
    foreach ($data as $d) {
        echo "{$d->tanggal_pinjam}\t{$d->nama}\t{$d->nis}\t{$d->nama_kelas}\t{$d->judul}\t{$d->status}\n";
    }
}
public function peminjaman_pdf()
{
    $awal  = $this->input->get('awal');
    $akhir = $this->input->get('akhir');

    $data['awal'] = $awal;
    $data['akhir'] = $akhir;

    $data['laporan'] = $this->Peminjaman_model
        ->get_laporan($awal, $akhir);

    $html = $this->load->view('laporan/peminjaman_pdf', $data, true);

    $this->load->library('pdf');
    $this->pdf->loadHtml($html);
    $this->pdf->setPaper('A4', 'potrait'); // landscape biar lega
    $this->pdf->render();
    $this->pdf->stream(
        'laporan_peminjaman.pdf',
        ['Attachment' => false]
    );
}

}
