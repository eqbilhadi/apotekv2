<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
{
    public function index()
    {
        $this->load->library('mypdf');
        $this->mypdf->generate('sales_report/report');
    }
    
}