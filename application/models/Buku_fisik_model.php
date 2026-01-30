<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buku_fisik_model extends CI_Model {

    /* ================= LIST ================= */

    public function get_all()
    {
        return $this->db
            ->select('
                buku_fisik.*,
                kategori.nama_kategori,
                rak.kode_rak
            ')
            ->from('buku_fisik')
            ->join('kategori', 'kategori.id_kategori = buku_fisik.id_kategori', 'left')
            ->join('rak', 'rak.id_rak = buku_fisik.id_rak', 'left')
            ->order_by('buku_fisik.judul', 'ASC')
            ->get()
            ->result();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->get_where('buku_fisik', ['id_buku' => $id])
            ->row();
    }

    /* ================= INSERT ================= */

    public function insert($data)
    {
        return $this->db->insert('buku_fisik', $data);
    }

    // ✅ UNTUK IMPORT EXCEL
    public function insert_batch($data)
    {
        return $this->db->insert_batch('buku_fisik', $data);
    }

    /* ================= UPDATE ================= */

    public function update($id, $data)
    {
        return $this->db
            ->where('id_buku', $id)
            ->update('buku_fisik', $data);
    }

    /* ================= DELETE ================= */

    public function delete($id)
    {
        return $this->db
            ->where('id_buku', $id)
            ->delete('buku_fisik');
    }

    /* ================= DETAIL ================= */

    public function get_detail($id)
    {
        return $this->db
            ->select('
                buku_fisik.*,
                kategori.nama_kategori,
                rak.kode_rak,
                rak.lokasi
            ')
            ->from('buku_fisik')
            ->join('kategori', 'kategori.id_kategori = buku_fisik.id_kategori', 'left')
            ->join('rak', 'rak.id_rak = buku_fisik.id_rak', 'left')
            ->where('buku_fisik.id_buku', $id)
            ->get()
            ->row();
    }

    /* ================= REKAP ================= */

    // 🔧 FIX: sebelumnya pakai kolom "kategori" (tidak ada)
    public function get_rekap()
    {
        return $this->db
            ->select('
                kategori.nama_kategori,
                COUNT(buku_fisik.id_buku) AS total
            ')
            ->from('buku_fisik')
            ->join('kategori', 'kategori.id_kategori = buku_fisik.id_kategori', 'left')
            ->group_by('kategori.id_kategori')
            ->get()
            ->result();
    }
public function cek_isbn($isbn)
{
    return $this->db
        ->where('isbn', $isbn)
        ->get('buku_fisik')
        ->row();
}
public function isbn_exists($isbn, $exclude_id = null)
{
    if (empty($isbn)) {
        return false;
    }

    $this->db->where('isbn', $isbn);

    if ($exclude_id) {
        $this->db->where('id_buku !=', $exclude_id);
    }

    return $this->db->get('buku_fisik')->row();
}

}
