<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman_model extends CI_Model {

    /* ================= ADMIN ================= */

    public function get_all()
    {
        return $this->db
            ->select('
    peminjaman.*,
    buku_fisik.judul,
    users.nama,
    DATEDIFF(CURDATE(), peminjaman.tanggal_jatuh_tempo) AS hari_terlambat
')
            ->from('peminjaman')
            ->join('buku_fisik','buku_fisik.id_buku = peminjaman.id_buku')
            ->join('users','users.id_user = peminjaman.id_user')
            ->order_by('peminjaman.id_pinjam','DESC')
            ->get()
            ->result();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->get_where('peminjaman',['id_pinjam'=>$id])
            ->row();
    }

    public function insert($data)
    {
        return $this->db->insert('peminjaman', $data);
    }

    public function update($id, $data)
    {
        return $this->db
            ->where('id_pinjam',$id)
            ->update('peminjaman',$data);
    }

    /* ================= SISWA ================= */

    // Cek apakah siswa masih meminjam buku ini
    public function cek_pinjam_aktif($id_user, $id_buku)
    {
        return $this->db
            ->get_where('peminjaman',[
                'id_user' => $id_user,
                'id_buku' => $id_buku,
                'status'  => 'dipinjam'
            ])
            ->row();
    }

    // Daftar pinjaman AKTIF siswa
    public function get_aktif_by_user($id_user)
    {
        return $this->db
            ->select('
    peminjaman.*,
    buku_fisik.judul,
    DATEDIFF(CURDATE(), peminjaman.tanggal_jatuh_tempo) AS hari_terlambat
')

            ->from('peminjaman')
            ->join('buku_fisik','buku_fisik.id_buku = peminjaman.id_buku')
            ->where('peminjaman.id_user',$id_user)
            ->where('peminjaman.status','dipinjam')
            ->order_by('peminjaman.id_pinjam','DESC')
            ->get()
            ->result();
    }

    // Riwayat pinjaman siswa
    public function get_riwayat_by_user($id_user)
    {
        return $this->db
            ->select('
    peminjaman.*,
    buku_fisik.judul,
    DATEDIFF(CURDATE(), peminjaman.tanggal_jatuh_tempo) AS hari_terlambat
')
            ->from('peminjaman')
            ->join('buku_fisik','buku_fisik.id_buku = peminjaman.id_buku')
            ->where('peminjaman.id_user',$id_user)
            ->order_by('peminjaman.id_pinjam','DESC')
            ->get()
            ->result();
    }
    public function count_pinjam_aktif($id_user)
{
    return $this->db
        ->where('id_user', $id_user)
        ->where('status', 'dipinjam')
        ->count_all_results('peminjaman');
}

}
