<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rak_model extends CI_Model {

    public function get_all()
    {
        return $this->db
            ->order_by('kode_rak', 'ASC')
            ->get('rak')
            ->result();
    }

    public function insert($data)
    {
        return $this->db->insert('rak', $data);
    }
}
