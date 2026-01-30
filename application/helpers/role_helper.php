<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function is_admin()
{
    $ci =& get_instance();
    return $ci->session->userdata('role') === 'admin';
}

function is_guru()
{
    $ci =& get_instance();
    return $ci->session->userdata('role') === 'guru';
}

function is_siswa()
{
    $ci =& get_instance();
    return $ci->session->userdata('role') === 'siswa';
}
