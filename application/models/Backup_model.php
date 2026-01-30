<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backup_model extends CI_Model {

    public function backup_database()
    {
        $this->load->dbutil();

        $prefs = [
            'format'   => 'zip',
            'filename' => 'perpusdig_' . date('Y-m-d_H-i-s') . '.sql'
        ];

        $backup = $this->dbutil->backup($prefs);

        return [
            'name' => 'backup_perpusdig_' . date('Y-m-d_H-i-s') . '.zip',
            'data' => $backup
        ];
    }

    public function restore_database($tmp_file)
{
    $sql = file_get_contents($tmp_file);

    // 🔥 MATIKAN FOREIGN KEY CHECK
    $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

    foreach (explode(";\n", $sql) as $query) {
        $query = trim($query);
        if ($query) {
            $this->db->query($query);
        }
    }

    // 🔥 AKTIFKAN LAGI
    $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
}

}
