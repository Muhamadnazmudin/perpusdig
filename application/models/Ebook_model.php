<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ebook_model extends CI_Model {

    private $table = 'ebook';

    public function get_all()
    {
        return $this->db
            ->order_by('id_ebook','DESC')
            ->get($this->table)
            ->result();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->get_where($this->table,['id_ebook'=>$id])
            ->row();
    }

    public function count_filtered($kelas=null,$mapel=null)
    {
        if ($kelas) $this->db->where('kelas',$kelas);
        if ($mapel) $this->db->where('mapel',$mapel);

        return $this->db->count_all_results($this->table);
    }

    public function get_filtered($limit,$offset,$kelas=null,$mapel=null)
    {
        if ($kelas) $this->db->where('kelas',$kelas);
        if ($mapel) $this->db->where('mapel',$mapel);

        return $this->db
            ->limit($limit,$offset)
            ->order_by('id_ebook','DESC')
            ->get($this->table)
            ->result();
    }

    public function delete($id)
    {
        return $this->db
            ->where('id_ebook',$id)
            ->delete($this->table);
    }

    public function get_mapel_unik()
    {
        return $this->db
            ->select('mapel')
            ->distinct()
            ->get($this->table)
            ->result();
    }
}
