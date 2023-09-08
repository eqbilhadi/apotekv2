<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Opname extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('opname_m', 'opname');
    }

    public function index()
    {
        check_not_login();
        $data = array(
            'page' => 'Stok Opname',
        );
        $this->template->load('template', 'opname/index', $data);
    }

    public function getIndex()
    {
        $results = $this->opname->getItem();
        $data = [];
        foreach ($results as $result) {
            $row = array();
            $row[] = $result->kode_brg;
            $row[] = $result->nm_brg;
            $row[] = $result->saldo;
            $row[] = $result->location;
            $row[] = $result->tgl_opname ? date("d/m/Y H:i", strtotime($result->tgl_opname)) : '<p class="text-danger">belum pernah</p>';
            $row[] = '
			<button type="button" class="btn btn-success btn-sm" id=' . "btnOpname" . $result->id_item . ' onclick="opname(' . "'" . $result->id_item . "','"  . $result->nm_brg . "'" . ')"><i class="fas fa-box-open"></i> Opname Sekarang</button>
            ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->opname->count_all_item(),
            "recordsFiltered" => $this->opname->count_filtered_item(),
            "data" => $data
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function getOpnameItem()
    {
        $results = $this->opname->getOpnameItem();
        $data = [];
        $no = $_POST['start'];

        foreach ($results as $result) {
            $row = array();
            if ($result->stok_sistem != 0) {
                $row[] = ++$no;
                $row[] = $result->expired;
                $row[] = $result->batch;
                $row[] = $result->harga;
                $row[] = $result->stok_sistem . ' ' . $result->satuan;
                $row[] = '<input type="number" name="stok_real" id="stok_real' . $result->id_transaksi_d . '" class="form-control form-control-sm" onkeyup="getSisa(' .  $result->stok_sistem . ',' . $result->id_transaksi_d  . ',' . "'" . $result->satuan . "'" . ')" style="text-align:center;" value="' . $result->stok_sistem . '">';
                $row[] = '<p id="sisaText' . $result->id_transaksi_d . '">0 ' . $result->satuan . '</p> <input type="hidden" name="sisa[]" id="sisa' . $result->id_transaksi_d . '" class="form-control form-control-sm" value="0">';
                $row[] = '<input type="text" name="note[]" id="note" class="form-control form-control-sm"> <input type="hidden" name="id_transaksi_d[]" id="id_transaksi_d" class="form-control form-control-sm" value="' . $result->id_transaksi_d . '">';
                $row[] = '<input type="checkbox" name="verif[]" id="verif" class="form-control form-control-sm">
                ';
                $row[] = '
                <button type="button" class="btn btn-success btn-sm" id=' . "btnOpname" . $result->id_item . ' onclick="opname(' . "'" . $result->id_item . "','"  . $result->nm_brg . "'" . ')"><i class="fas fa-box-open"></i> Opname Sekarang</button>
                ';
                $data[] = $row;
            }
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->opname->count_all_opnameItem(),
            "recordsFiltered" => $this->opname->count_filtered_opnameItem(),
            "data" => $data
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function save()
    {
        $post = $this->input->post(null, TRUE);
        $this->opname->save($post);
        if ($this->db->affected_rows() > 0) {
            $msg = [
                'success' => 'Berhasil'
            ];
        } else {
            $msg = [
                'failed' => 'Gagal'
            ];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }
}
