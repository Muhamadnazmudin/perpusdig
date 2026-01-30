<?php
class Guru_model extends CI_Model {

    public function get_all() {
        return $this->db->get('guru')->result();
    }

    public function insert($data) {
        return $this->db->insert('guru', $data);
    }
}
