<?php
class Siswa_model extends CI_Model {

    public function get_all() {
    $this->db->select('siswa.*, kelas.nama_kelas, jurusan.nama_jurusan');
    $this->db->join('kelas', 'kelas.id_kelas = siswa.id_kelas');
    $this->db->join('jurusan', 'jurusan.id_jurusan = siswa.id_jurusan');
    return $this->db->get('siswa')->result();
}

    public function insert($data) {
        return $this->db->insert('siswa', $data);
    }
    public function count_all()
{
    return $this->db->count_all('siswa');
}

public function get_limit($limit, $offset)
{
    $this->db->select('siswa.*, kelas.nama_kelas, jurusan.nama_jurusan');
    $this->db->join('kelas','kelas.id_kelas=siswa.id_kelas');
    $this->db->join('jurusan','jurusan.id_jurusan=siswa.id_jurusan');
    $this->db->limit($limit, $offset);
    return $this->db->get('siswa')->result();
}
public function get_by_nis($nis)
{
    $this->db->select('
        siswa.*,
        kelas.nama_kelas,
        jurusan.nama_jurusan
    ');
    $this->db->join('kelas','kelas.id_kelas = siswa.id_kelas');
    $this->db->join('jurusan','jurusan.id_jurusan = siswa.id_jurusan');
    return $this->db->get_where('siswa', ['nis' => $nis])->row();
}

}
