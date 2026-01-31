<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf {

    protected $dompdf;

    public function __construct()
    {
        // autoload dompdf
        require_once APPPATH . 'third_party/dompdf/vendor/autoload.php';

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('chroot', FCPATH);

        $this->dompdf = new Dompdf($options);
    }

    public function loadHtml($html)
    {
        $this->dompdf->loadHtml($html);
    }

    public function setPaper($size = 'A4', $orientation = 'portrait')
    {
        $this->dompdf->setPaper($size, $orientation);
    }

    public function render()
    {
        $this->dompdf->render();
    }

    public function stream($filename = 'document.pdf', $options = [])
    {
        $this->dompdf->stream($filename, $options);
    }
}
