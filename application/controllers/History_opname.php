<?php
defined('BASEPATH') or exit('No direct script access allowed');

class History_Opname extends CI_Controller
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
            'page' => 'Riwayat Stok Opname',
        );
        $this->template->load('template', 'history_opname/index', $data);
    }

    public function getIndex()
    {
        $results = $this->opname->getHistoryOpname();
        $data = [];
        $no = $_POST['start'];

        foreach ($results as $result) {
            $row = array();
            $row[] = ++$no;
            $row[] = date("d/m/Y", strtotime($result->tgl)) . ' | ' . date("H:i", strtotime($result->jam));
            $row[] = $result->item;
            $row[] = $result->petugas;
            $row[] = $result->selisih;
            $row[] = 'Rp. ' . number_format($result->penyesuaian ?? 0, 0, ',', '.');
            $row[] = '
			<button type="button" class="btn btn-primary btn-xs" id=' . "btnOpname" . $result->id_item . ' onclick="opname(' . "'" . $result->id_transaksi . "','"  . $result->item . "','detail'" . ')"><i class="fas fa-tasks"></i>  Detail</button>
            <button type="button" class="btn btn-danger btn-xs" id=' . "btnCancel" . $result->id_item . ' onclick="opname(' . "'" . $result->id_transaksi . "','"  . $result->item . "','hapus'" . ')"><i class="fas fa-ban"></i>  Batalkan</button>
            ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->opname->count_all_HistoryOpname(),
            "recordsFiltered" => $this->opname->count_filtered_HistoryOpname(),
            "data" => $data
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function getRiwayatOpnameItem()
    {
        $results = $this->opname->getRiwayatOpnameItem();
        $data = [];
        $no = $_POST['start'];

        foreach ($results as $result) {
            $row = array();
            $row[] = ++$no;
            $row[] = $result->expired;
            $row[] = $result->batch ?? '-';
            $row[] = 'Rp. ' . number_format($result->price ?? 0, 0, ',', '.');
            if ($result->status == 'out') {
                $row[] = '-' . $result->qty . ' ' . $result->satuan;
            } else {
                $row[] = $result->qty . ' ' . $result->satuan;
            }
            $row[] = 'Rp. ' . number_format($result->total_price ?? 0, 0, ',', '.');
            $row[] = $result->note ?? '-';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->opname->count_all_RiwayatOpnameItem(),
            "recordsFiltered" => $this->opname->count_filtered_RiwayatOpnameItem(),
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

    public function delete($id)
    {
        $this->db->delete('transaksi', ['id_transaksi' => $id]);
        if ($this->db->affected_rows() > 0) {
            $msg = [
                'success' => 'Berhasil membatalkan stok opname'
            ];
        } else {
            $msg = [
                'failed' => 'Gagal membatalkan stok opname'
            ];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }
}
