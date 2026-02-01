<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model {

    public function get($name)
    {
        return $this->db
            ->get_where('settings', ['name' => $name])
            ->row('value');
    }

    public function set($name, $value)
    {
        return $this->db
            ->where('name', $name)
            ->update('settings', ['value' => $value]);
    }
}
