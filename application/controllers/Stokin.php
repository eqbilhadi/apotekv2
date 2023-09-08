<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Stokin extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Stokin_m', 'stokin');
        $this->load->model('category_m', 'category');
        $this->load->model('satuan_m', 'satuan');
        $this->load->model('item_m', 'item');
        $this->load->model('supplier_m', 'supplier');
        $this->load->model('type_m', 'type');
    }

    public function index()
    {
        check_not_login();
        $category = $this->category->get();
        $satuan = $this->satuan->get();
        $supplier = $this->supplier->get();
        $tipe = $this->type->get();
        $data = array(
            'page' => 'Pembelian',
            'kategori' => $category,
            'satuan' => $satuan,
            'tipe' => $tipe,
            'supplier' => $supplier
        );
        $this->template->load('template', 'stokin/index', $data);
    }

    public function getDraft()
    {
        $dt = $this->stokin->getDraft();
        $this->output->set_content_type('application/json')->set_output(json_encode($dt->id_transaksi ?? null));
    }

    public function getStokIn()
    {
        $results = $this->stokin->getData();
        $data = array(
            'data' => $this->stokin->getDataAll(),
            'start' => (isset($_POST['awal'])) ? date('Y-m-d', strtotime($_POST['awal'])) : '',
            'end' => (isset($_POST['akhir'])) ? date('Y-m-d', strtotime($_POST['akhir'])) : '',
        );
        $this->session->set_userdata('data_export', $data);
        $data = [];
        $no = $_POST['start'];
        foreach ($results as $result) {
            $row = array();
            $row[] = ++$no;
            $row[] = $result->no_faktur ?? '-';
            if ($result->tgl != '') {
                $row[] = longdate_indo($result->tgl) ?? '-';
            } else {
                $row[] = '-';
            }
            $row[] = $result->description ?? '-';
            $row[] = $result->supplier ?? '-';
            $row[] = $result->jml;
            $row[] = 'Rp. ' . number_format($result->total ?? 0, 0, ',', '.');
            if ($result->is_draft == 1) {
                $row[] = 'Draft';
            } else {
                $row[] = '
                <a href="#" class="btn btn-primary btn-xs" id=' . "btnEdit" . $result->id_transaksi . ' onclick="byid(' . "'" . $result->id_transaksi . "','edit'" . ')"><i class="fas fa-tasks"></i></a>
                <a href="#" class="btn btn-danger btn-xs" id=' . "btnDelete" . $result->id_transaksi . ' onclick="byid(' . "'" . $result->id_transaksi . "','delete'" . ')"><i class="fas fa-ban"></i>';
            }
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->stokin->count_all_data(),
            "recordsFiltered" => $this->stokin->count_filtered_data(),
            "data" => $data
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function getTransaksiList()
    {
        if ($this->input->is_ajax_request()) {
            $results = $this->stokin->getTransaksiListIncoming();
            $data = [];
            $no = $_POST['start'];
            foreach ($results as $result) {
                $row = array();
                $row[] = ++$no;
                $row[] = $result->kode_brg ?? '';
                $row[] = $result->nama_brg;
                $row[] = $result->batch;
                $row[] = $result->expired;
                $row[] = $result->qty;
                $row[] = $result->satuan_name;
                $row[] = number_format($result->price ?? 0, 0, ',', '.');
                $row[] = number_format($result->total_price ?? 0, 0, ',', '.');
                if ($_POST['status'] == 'in') {
                    $row[] = '
                        <a href="#" class="btn btn-warning btn-xs" id=' . "btnEdit" . $result->id_transaksi_d . ' onclick="byidBarang(' . "'" . $result->id_transaksi_d . "','edit'" . ')"><span class="fa fa-pen"> Edit</span></a>
                        <a href="#" class="btn btn-danger btn-xs" id=' . "btnDelete" . $result->id_transaksi_d . ' onclick="byidBarang(' . "'" . $result->id_transaksi_d . "','delete'" . ')"><span class="fa fa-trash"> Hapus</span></a>';
                } else {
                    $row[] = '
                        <a href="#" class="btn btn-danger btn-xs" id=' . "btnDelete" . $result->id_transaksi_d . ' onclick="byidBarang(' . "'" . $result->id_transaksi_d . "','delete'" . ')"><span class="fa fa-trash"> Hapus</span></a>';
                }
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->stokin->count_all_TransaksiListIncoming(),
                "recordsFiltered" => $this->stokin->count_filtered_TransaksiListIncoming(),
                "data" => $data
            );

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        } else {
            redirect('inventory/incoming');
        }
    }

    public function getItem()
    {
        $results = $this->item->getData();
        $data = [];
        $no = $_POST['start'];
        foreach ($results as $result) {
            $row = array();
            $row[] = $result->item_code;
            $row[] = $result->item_name;
            $row[] = '
			<button type="text" class="btn btn-success btn-xs" id=' . "btnPilih" . $result->id_item . ' onclick="pilih(' . "'" . $result->id_item . "','" . $result->item_name . "','" . $result->item_code . "'" . ')"><i class="fas fa-check-square"></i> Pilih</button>
            ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->item->count_all_data(),
            "recordsFiltered" => $this->item->count_filtered_data(),
            "data" => $data
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function pilihItem()
    {
        $item = $this->stokin->getItem($_POST['id_item']);
        $this->output->set_content_type('application/json')->set_output(json_encode($item));
    }
    public function cariItem()
    {
        $this->db->select('st.name AS satuan_name, i.purchase_price AS harga, i.name, i.id_item');
        $this->db->from('item i');
        $this->db->where('i.item_code', $_POST['kode']);

        $this->db->join('satuan st', 'st.id_satuan = i.id_satuan');
        $output = $this->db->get()->row();
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function addBarang()
    {
        $this->form_validation->set_rules('kode', 'Kode', 'required');
        $this->form_validation->set_rules('qty', 'Qty', 'required');

        $this->form_validation->set_message('required', '%s wajib disi!');
        if ($this->form_validation->run() == FALSE) {
            $msg = [
                'error' => [
                    'kode' => form_error('kode'),
                    'qty' => form_error('qty'),
                ]
            ];
        } else {
            $post = $this->input->post(null, TRUE);
            $id_trans = $this->stokin->create($post);
            if ($this->db->affected_rows() > 0) {
                $msg = [
                    'success' => 'Data berhasil disimpan',
                    'id_trans' => $id_trans
                ];
            } else {
                $msg = [
                    'failed' => 'Data gagal disimpan'
                ];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }

    public function updateBarang()
    {
        $this->form_validation->set_rules('kode', 'Kode', 'required');
        $this->form_validation->set_rules('qty', 'Qty', 'required');

        $this->form_validation->set_message('required', '%s wajib disi!');
        if ($this->form_validation->run() == FALSE) {
            $msg = [
                'error' => [
                    'kode' => form_error('kode'),
                    'qty' => form_error('qty'),
                ]
            ];
        } else {
            $post = $this->input->post(null, TRUE);
            $id_trans = $this->stokin->update($post);
            if ($this->db->affected_rows() > 0) {
                $msg = [
                    'success' => 'Data berhasil diubah',
                ];
            } else {
                $msg = [
                    'failed' => 'Data gagal diubah'
                ];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }

    public function akhiriTransaksi()
    {
        $id_trans = $_POST['id_transaksi'];
        $cektable = $this->db->query("SELECT * FROM transaksi_d WHERE id_transaksi = '$id_trans'");
        if ($cektable->num_rows() == 0) {
            $msg = [
                'kosong' => 'Belum ada transaksi'
            ];
        } else {
            $this->form_validation->set_rules('no_faktur', 'No Faktur', 'required');
            $this->form_validation->set_rules('tgl', 'Tanggal', 'required');
            $this->form_validation->set_rules('supplier', 'Supplier', 'required');

            $this->form_validation->set_message('required', '%s wajib disi!');
            if ($this->form_validation->run() == FALSE) {
                $msg = [
                    'error' => [
                        'no_faktur' => form_error('no_faktur'),
                        'tgl' => form_error('tgl'),
                        'supplier' => form_error('supplier'),
                    ]
                ];
            } else {
                $post = $this->input->post(null, TRUE);
                $id_trans = $this->stokin->akhiritransaksi($post);
                if ($this->db->affected_rows() > 0) {
                    $msg = [
                        'success' => 'Data berhasil disimpan',
                    ];
                } else {
                    $msg = [
                        'failed' => 'Data gagal disimpan'
                    ];
                }
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }

    public function byid($id)
    {
        $data = $this->stokin->getdataById($id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function byidBarang($id)
    {
        $data = $this->stokin->getdataByIdBarang($id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function update()
    {
        $this->form_validation->set_rules('nama', 'Nama stokin', 'required');

        $this->form_validation->set_message('required', '%s masih kosong, silahkan isi!');
        if ($this->form_validation->run() == FALSE) {
            $msg = [
                'error' => [
                    'nama' => form_error('nama'),
                ]
            ];
        } else {
            $post = $this->input->post(null, TRUE);
            $this->stokin->update($post);
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

    public function delete()
    {
        $this->stokin->delete();
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

    public function deleteBarang()
    {
        $this->stokin->deleteBarang();
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

    public function getOptSatuan()
    {
        $type = 'opt';
        $satuan = $this->item->getSatuanItem($_POST['id_item'], $type);
        $id_satuan = $_POST['id_satuan'] ?? null;
        $output = '<option value=""></option>';
        foreach ($satuan as $dt) {
            if ($id_satuan) {
                if ($id_satuan == $dt->id_satuan) {
                    $output .= '<option value="' . $dt->id_satuan . '" selected>' . $dt->name . '</option>';
                } else {
                    $output .= '<option value="' . $dt->id_satuan . '">' . $dt->name . '</option>';
                }
            } else {
                $output .= '<option value="' . $dt->id_satuan . '">' . $dt->name . '</option>';
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function exportpdf()
    {
        $data = $this->session->userdata('data_export');
        // echo"<pre>";
        // print_r($data);
        // die;
        $filename = 'Daftar Pembelian';
        $paper = 'A4';
        $orientation = 'landscape';
        $this->load->library('mypdf');
        $this->mypdf->generate('stokin/pdf_stokin', $data, $filename, $paper, $orientation);
    }

    public function excel()
    {
        $download = $this->session->userdata('data_export');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No Faktur');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Jumlah Item');
        $sheet->setCellValue('D1', 'Total Pembelian');

        $column = 2;
        foreach ($download['data'] as $value) {
            $sheet->setCellValue('A' . $column, $value->no_faktur ?? '-');
            $sheet->setCellValue('B' . $column, $value->tgl ?? '-');
            $sheet->setCellValue('C' . $column, $value->jml ?? '-');
            $sheet->setCellValue('D' . $column, $value->total ?? '-');
            $spreadsheet->getActiveSheet()->getStyle('C' . $column)->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
            $spreadsheet->getActiveSheet()->getStyle('D' . $column)->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
            $column++;
        }

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocuments.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Daftar Pembelian.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save("php://output");
        exit();
    }

    // public function getStok()
    // {
    //     $this->db->select('id_item_d');
    //     $this->db->from('item_d');
    //     $this->db->where('id_item', $_POST['id_item']);
    //     $this->db->where('id_satuan', $_POST['id_satuan']);
    //     $rawIdItemD = $this->db->get()->row();
    //     $id_item_d = $rawIdItemD->id_item_d;

    //     $this->db->select('stok');
    //     $this->db->from('stok');
    //     $this->db->where('id_item_d', $id_item_d);
    //     $stok = $this->db->get()->row();
    //     $this->output->set_content_type('application/json')->set_output(json_encode($stok));
    // }
}
