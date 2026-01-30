<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kunjungan_model extends CI_Model {

    public function hari_ini()
{
    return $this->db
        ->select('
            siswa.nama_siswa,
            siswa.nis,
            kelas.nama_kelas,
            jurusan.nama_jurusan,
            kunjungan.jam,
            kunjungan.tujuan
        ')
        ->from('kunjungan')
        ->join('siswa','siswa.id_siswa = kunjungan.id_siswa')
        ->join('kelas','kelas.id_kelas = siswa.id_kelas')
        ->join('jurusan','jurusan.id_jurusan = siswa.id_jurusan')
        ->where('kunjungan.tanggal', date('Y-m-d'))
        ->order_by('kunjungan.jam','DESC')
        ->get()
        ->result();
}


    public function tambah($id_siswa)
    {
        $data = [
            'id_siswa' => $id_siswa,
            'tanggal'  => date('Y-m-d'),
            'jam'      => date('H:i:s')
        ];

        try {
            $this->db->insert('kunjungan', $data);
            return ['status' => true, 'msg' => 'Kunjungan berhasil dicatat'];
        } catch (Exception $e) {
            return ['status' => false, 'msg' => 'Siswa sudah tercatat hari ini'];
        }
    }
    public function get_all_laporan()
{
    return $this->db
        ->select('
            kunjungan.tanggal,
            kunjungan.jam,
            kunjungan.tujuan,
            siswa.nis,
            siswa.nama_siswa,
            kelas.nama_kelas,
            jurusan.nama_jurusan
        ')
        ->join('siswa','siswa.id_siswa=kunjungan.id_siswa')
        ->join('kelas','kelas.id_kelas=siswa.id_kelas')
        ->join('jurusan','jurusan.id_jurusan=siswa.id_jurusan')
        ->order_by('kunjungan.tanggal','DESC')
        ->order_by('kunjungan.jam','DESC')
        ->get('kunjungan')
        ->result();
}
public function filter_laporan($filter)
{
    $this->db
        ->select('
            kunjungan.tanggal,
            kunjungan.jam,
            kunjungan.tujuan,
            siswa.nis,
            siswa.nama_siswa,
            kelas.nama_kelas,
            jurusan.nama_jurusan
        ')
        ->join('siswa','siswa.id_siswa=kunjungan.id_siswa')
        ->join('kelas','kelas.id_kelas=siswa.id_kelas')
        ->join('jurusan','jurusan.id_jurusan=siswa.id_jurusan');

    if (!empty($filter['kelas'])) {
        $this->db->where('kelas.nama_kelas', $filter['kelas']);
    }

    if (!empty($filter['jurusan'])) {
        $this->db->where('jurusan.nama_jurusan', $filter['jurusan']);
    }

    if (!empty($filter['from'])) {
        $this->db->where('kunjungan.tanggal >=', $filter['from']);
    }

    if (!empty($filter['to'])) {
        $this->db->where('kunjungan.tanggal <=', $filter['to']);
    }

    return $this->db
        ->order_by('kunjungan.tanggal','DESC')
        ->order_by('kunjungan.jam','DESC')
        ->get('kunjungan')
        ->result();
}

}
