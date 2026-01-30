<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_model extends CI_Model {

    public function get_all()
    {
        return $this->db
            ->order_by('nama_kategori', 'ASC')
            ->get('kategori')
            ->result();
    }

    public function insert($data)
    {
        return $this->db->insert('kategori', $data);
    }
}
