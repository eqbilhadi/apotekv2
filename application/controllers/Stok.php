<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stok extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Stok_m', 'stok');
        $this->load->model('Type_m', 'type');
    }

    public function index()
    {
        check_not_login();
        $type = $this->type->get();
        $data = array(
            'page' => 'Daftar Produk',
            'tipe' => $type
        );
        $this->template->load('template', 'stok/index', $data);
    }

    public function getStok()
    {
        $results = $this->stok->getStok();
        // echo"<pre>";
        // print_r($results);
        // die;
        $data = [];
        $no = $_POST['start'];
        foreach ($results as $result) {
            $row = array();
            $row[] = ++$no;
            $row[] = $result->nm_brg;
            $row[] = $result->lokasi ?? '-';
            $row[] = $result->saldo . ' ' . $result->satuan;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->stok->count_all_stok(),
            "recordsFiltered" => $this->stok->count_filtered_stok(),
            "data" => $data
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }
}
