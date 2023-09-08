<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Type extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Type_m', 'type');
    }

    public function index()
    {
        check_not_login();
        $data = array(
			'page' => 'Tipe',
		);
        $this->template->load('template', 'type/index', $data);
    }

    public function getType()
    {
        $results = $this->type->getData();
        $data = [];
        $no = $_POST['start'];
        foreach ($results as $result) {
            $row = array();
            $row[] = ++$no;
            $row[] = $result->name;
            $row[] = '
			<a href="#" class="btn btn-warning btn-xs" id=' . "btnEdit" . $result->id_type . ' onclick="byid(' . "'" . $result->id_type . "','edit'" . ')"><i class="fas fa-pen"></i></a>
            <a href="#" class="btn btn-danger btn-xs" id=' . "btnDelete" . $result->id_type . ' onclick="byid(' . "'" . $result->id_type . "','delete'" . ')"><i class="fas fa-trash"></i></a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->type->count_all_data(),
            "recordsFiltered" => $this->type->count_filtered_data(),
            "data" => $data
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function add()
    {
        $jumlah_nama = count($_POST['nama']);

        $this->form_validation->set_rules('nama[]', 'Nama Kategori', 'required');
        $this->form_validation->set_message('required', '%s masih kosong, silahkan isi!');
        if ($this->form_validation->run() == FALSE) {
            $msg = [
                'error' => validation_errors(),
            ];
        } else {
            $post = $this->input->post(null, TRUE);
            $this->type->create($post);
            if ($this->db->affected_rows() > 0) {
                $msg = [
                    'success' => 'Data berhasil disimpan'
                ];
            } else {
                $msg = [
                    'failed' => 'Data gagal disimpan'
                ];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }

    public function byid($id)
    {
        $data = $this->type->getdataById($id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function update()
    {
        $this->form_validation->set_rules('nama[]', 'Nama tipe', 'required');

        $this->form_validation->set_message('required', '%s masih kosong, silahkan isi!');
        if ($this->form_validation->run() == FALSE) {
            $msg = [
                'error' => [
                    'nama' => form_error('nama'),
                ]
            ];
        } else {
            $post = $this->input->post(null, TRUE);
            $this->type->update($post);
            if ($this->db->affected_rows() > 0) {
                $msg = [
                    'success' => 'Data berhasil diupdate'
                ];
            } else {
                $msg = [
                    'failed' => 'Data gagal diupdate'
                ];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }

    public function delete($id)
    {
        $this->type->delete($id);
        if ($this->db->affected_rows() > 0) {
            $msg = [
                'success' => 'Data berhasil dihapus'
            ];
        } else {
            $msg = [
                'error' => 'Data gagal dihapus'
            ];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }
}
