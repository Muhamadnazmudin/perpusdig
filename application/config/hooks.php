<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hook['post_controller_constructor'][] = [
    'class'    => 'Maintenance',
    'function' => 'run',
    'filename' => 'Maintenance.php',
    'filepath' => 'hooks',
];
