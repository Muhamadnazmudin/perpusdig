<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/phpqrcode/phpqrcode.php';

class Qrcode {

    public function generate($text, $filepath, $size = 6)
    {
        QRcode::png(
            $text,
            $filepath,
            QR_ECLEVEL_L,
            $size
        );
    }
}
