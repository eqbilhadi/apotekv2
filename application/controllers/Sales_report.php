<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Sales_report extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Sales_report_m', 'sr');
        $this->load->model('category_m', 'category');
        $this->load->model('supplier_m', 'supplier');
    }

    public function index()
    {
        check_not_login();
        $category = $this->category->get();
        $supplier = $this->supplier->get();
        $data = array(
            'page' => 'Daftar Penjualan',
            'kategori' => $category,
            'supplier' => $supplier
        );
        $this->template->load('template', 'sales_report/index', $data);
    }

    public function getDraft()
    {
        $dt = $this->sr->getDraft();
        $this->output->set_content_type('application/json')->set_output(json_encode($dt->id_transaksi ?? null));
    }

    public function getStokIn()
    {
        $results = $this->sr->getData();
        $data = array(
            'data' => $this->sr->getDataAll(),
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
            $row[] = longdate_indo($result->tgl) ?? '-';
            $row[] = $result->kasir ?? '-';
            $row[] = ($result->customer == '') ? 'Umum' : $result->customer;
            $row[] = $result->jml;
            $row[] = 'Rp. ' . number_format($result->cash ?? 0, 0, ',', '.');
            $row[] = 'Rp. ' . number_format($result->change ?? 0, 0, ',', '.');
            $row[] = 'Rp. ' . number_format($result->total ?? 0, 0, ',', '.');
            if ($result->is_draft == 1) {
                $row[] = 'Draft';
            } else {
                $row[] = '
                <a href="#" class="btn btn-primary btn-xs" id=' . "btnEdit" . $result->id_transaksi . ' onclick="byid(' . "'" . $result->id_transaksi . "','edit'" . ')"><i class="fas fa-tasks"></i></a>
                <a href="#" class="btn btn-danger btn-xs" id=' . "btnDelete" . $result->id_transaksi . ' onclick="byid(' . "'" . $result->id_transaksi . "','delete'" . ')"><i class="fas fa-trash"></i></a>';
            }
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->sr->count_all_data(),
            "recordsFiltered" => $this->sr->count_filtered_data(),
            "data" => $data
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function getTransaksiList()
    {
        if ($this->input->is_ajax_request()) {
            $results = $this->sr->getTransaksiListIncoming();
            $data = [];
            $no = $_POST['start'];
            foreach ($results as $result) {
                $row = array();
                $row[] = ++$no;
                $row[] = $result->kode_brg ?? '';
                $row[] = $result->nama_brg;
                $row[] = ($result->expired == '0000-00-00') ? '-' : $result->expired;
                $row[] = $result->batch ?? '-';
                $row[] = $result->qty;
                $row[] = $result->satuan_name;
                $row[] = number_format($result->price ?? 0, 0, ',', '.');
                $row[] = number_format($result->total_price ?? 0, 0, ',', '.');
                if ($_POST['status'] == 'in') {
                    $row[] = '
                    <a href="#" class="btn btn-warning btn-xs" id=' . "btnEdit" . $result->id_transaksi_d . ' onclick="byidBarang(' . "'" . $result->id_transaksi_d . "','edit'" . ')"><span class="fa fa-pen"> Edit</span></a>
                    <a href="#" class="btn btn-danger btn-xs" id=' . "btnDelete" . $result->id_transaksi_d . ' onclick="byidBarang(' . "'" . $result->id_transaksi_d . "','delete'" . ')"><span class="fa fa-trash"> Hapus</span></a>';
                } else {
                    $row[] = '-';
                }
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->sr->count_all_TransaksiListIncoming(),
                "recordsFiltered" => $this->sr->count_filtered_TransaksiListIncoming(),
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
        $item = $this->sr->getItem($_POST['id_item']);
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
            $id_trans = $this->sr->create($post);
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
            $id_trans = $this->sr->update($post);
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
                $id_trans = $this->sr->akhiritransaksi($post);
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
        $data = $this->sr->getdataById($id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function byidBarang($id)
    {
        $data = $this->sr->getdataByIdBarang($id);
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
            $this->sr->update($post);
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
        $this->sr->delete();
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
        $this->sr->deleteBarang();
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
        $filename = 'Daftar Penjualan';
        $paper = 'A4';
        $orientation = 'landscape';
        $this->load->library('mypdf');
        $this->mypdf->generate('sales_report/pdf_sales_report', $data, $filename, $paper, $orientation);
    }

    public function excel()
    {
        $download = $this->session->userdata('data_export');
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No Faktur');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Kasir');
        $sheet->setCellValue('D1', 'Pelanggan');
        $sheet->setCellValue('E1', 'Jumlah Item');
        $sheet->setCellValue('F1', 'Total');
        $sheet->setCellValue('G1', 'Cash');
        $sheet->setCellValue('H1', 'Kembalian');

        $column = 2;
        foreach ($download['data'] as $value) {
            $sheet->setCellValue('A' . $column, $value->no_faktur ?? '-');
            $sheet->setCellValue('B' . $column, $value->tgl ?? '-');
            $sheet->setCellValue('C' . $column, $value->kasir ?? '-');
            $sheet->setCellValue('D' . $column, $value->customer ?? '-');
            $sheet->setCellValue('E' . $column, $value->jml ?? '-');
            $sheet->setCellValue('F' . $column, $value->total ?? '-');
            $sheet->setCellValue('G' . $column, $value->cash ?? '-');
            $sheet->setCellValue('H' . $column, $value->change ?? '-');
            $spreadsheet->getActiveSheet()->getStyle('E' . $column)->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
            $spreadsheet->getActiveSheet()->getStyle('F' . $column)->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
            $spreadsheet->getActiveSheet()->getStyle('G' . $column)->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
            $spreadsheet->getActiveSheet()->getStyle('H' . $column)->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
            $column++;
        }

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocuments.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Daftar Penjualan.xlsx"');
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
