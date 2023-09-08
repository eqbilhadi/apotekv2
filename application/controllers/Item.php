<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Item extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('item_m', 'item');
        $this->load->model('category_m', 'category');
        $this->load->model('satuan_m', 'satuan');
        $this->load->model('location_m', 'lokasi');
        $this->load->model('type_m', 'type');
    }

    public function index()
    {
        check_not_login();
        $category = $this->category->get();
        $lokasi = $this->lokasi->get();
        $satuan = $this->satuan->get();
        $type = $this->type->get();
        $data = array(
            'page' => 'Item',
            'kategori' => $category,
            'satuan' => $satuan,
            'lokasi' => $lokasi,
            'tipe' => $type
        );
        $this->template->load('template', 'item/index', $data);
    }

    public function getItem()
    {
        $results = $this->item->getData();
        $exp_data = $this->item->getDataAll();
        $this->session->set_userdata('data_export', $exp_data);
        $data = [];
        $no = $_POST['start'];
        // echo "<pre>";
        // print_r($results);
        // die;
        foreach ($results as $result) {
            $row = array();
            $row[] = ++$no;
            $row[] = $result->item_name;
            if ($result->barcode != null) {
                $row[] = '<svg class="barcode"
                            jsbarcode-format="CODE128"
                            jsbarcode-value="' . $result->barcode . '"
                            jsbarcode-height="20"
                            jsbarcode-fontSize="7"
                            jsbarcode-width="1">
                        </svg>';
            } else {
                $row[] = '-';
            }
            $row[] = $result->item_code;
            $row[] = $result->satuan_name;
            $row[] = "Rp. " . number_format($result->h_beli ?? 0, 0, ',', '.');
            $row[] = "Rp. " . number_format($result->h_jual ?? 0, 0, ',', '.');
            $row[] = $result->lokasi ?? '-';
            $row[] = '
			<a href="#" class="btn btn-warning btn-xs" id=' . "btnEdit" . $result->id_item . ' onclick="byid(' . "'" . $result->id_item . "','edit'" . ')"><i class="fas fa-pen"></i></a>
            <a href="#" class="btn btn-danger btn-xs" id=' . "btnDelete" . $result->id_item . ' onclick="byid(' . "'" . $result->id_item . "','delete'" . ')"><i class="fas fa-trash"></i></a>';
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

    public function add()
    {
        $this->form_validation->set_rules('nama', 'Nama item', 'required');
        $this->form_validation->set_rules('tipe', 'Tipe item', 'required');
        $this->form_validation->set_rules('kategori', 'Kategori item', 'required');
        $this->form_validation->set_rules('hrg_beli', 'Harga Beli item', 'required');
        $this->form_validation->set_rules('hrg_jual', 'Harga Jual item', 'required');
        $this->form_validation->set_rules('kode', 'Kode item', 'required');
        $this->form_validation->set_rules('satuan', 'Satuan item', 'required');

        $this->form_validation->set_message('required', '%s masih kosong, silahkan isi!');
        if ($this->form_validation->run() == FALSE) {
            $msg = [
                'error' => [
                    'nama' => form_error('nama'),
                    'tipe' => form_error('tipe'),
                    'kategori' => form_error('kategori'),
                    'hrg_beli' => form_error('hrg_beli'),
                    'hrg_jual' => form_error('hrg_jual'),
                    'kode' => form_error('kode'),
                    'satuan' => form_error('satuan'),
                ]
            ];
        } else {
            $post = $this->input->post(null, TRUE);

            $this->item->create($post);
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
        $data = $this->item->getdataById($id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function update()
    {
        $this->form_validation->set_rules('nama', 'Nama item', 'required');

        $this->form_validation->set_message('required', '%s masih kosong, silahkan isi!');
        if ($this->form_validation->run() == FALSE) {
            $msg = [
                'error' => [
                    'nama' => form_error('nama'),
                ]
            ];
        } else {
            $post = $this->input->post(null, TRUE);
            $this->item->update($post);
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
        $this->item->delete($id);
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

    public function import()
    {
        $file = $_FILES['excel']['name'];
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        if ($ext == 'xls') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            $reader->setReadDataOnly(true);
            $reader->setReadEmptyCells(false);
        } else if ($ext == 'xlsx') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $reader->setReadDataOnly(true);
            $reader->setReadEmptyCells(false);
        }
        $spreadsheet = $reader->load($_FILES['excel']['tmp_name']);
        $sheetdata = $spreadsheet->getActiveSheet()->toArray();
        $sheetcount = count($sheetdata);
        if ($sheetcount > 1) {
            $data = array();
            for ($i = 5; $i < $sheetcount; $i++) {
                $id = $sheetdata[$i][0];
                $tipe = $sheetdata[$i][1];
                $name = $sheetdata[$i][2];
                $satuan = $sheetdata[$i][3];
                // $st = $this->db->query("SELECT id_satuan FROM satuan WHERE name LIKE '%" . $satuan . "%'")->row();
                // $id_satuan = $st->id_satuan ?? '';
                $kode = $sheetdata[$i][4];
                $kategori = $sheetdata[$i][5];
                // $ktg = $this->db->query("SELECT id_category FROM category WHERE name LIKE '%" . $kategori . "%'")->row();
                // $id_kategori = $ktg->id_category ?? '';
                $harga_beli = $sheetdata[$i][6];
                $harga_jual = $sheetdata[$i][7];
                $min_stok = $sheetdata[$i][8];
                $lokasi = $sheetdata[$i][9];

                $data[] = array(
                    'no'                => $id,
                    'item_type'         => $tipe,
                    'name'              => $name,
                    'id_satuan'         => $satuan,
                    'item_code'         => $kode,
                    'id_kategori'       => $kategori,
                    'purchase_price'    => $harga_beli,
                    'selling_price'     => $harga_jual,
                    'min_stok'          => $min_stok,
                    'location'          => $lokasi,
                );
            };
        }

        $this->session->set_userdata('datapreview', $data);
        if (!empty($this->session->userdata('datapreview'))) {
            $msg = [
                'success' => 'Berhasil preview excel',
            ];
        } else {
            $msg = [
                'failed' => 'Gagal preview excel'
            ];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }

    public function preview()
    {
        $result = $this->session->userdata('datapreview');
        $data = [];
        $no = $_POST['start'];
        foreach ($result as $p) {
            $row = array();
            $row[] = ++$no;
            $row[] = $p["name"];
            $row[] = $p["item_type"];
            $row[] = $p["item_code"];
            $row[] = $p["id_satuan"];
            $row[] = $p["id_kategori"];
            $row[] = $p["purchase_price"];
            $row[] = $p["selling_price"];
            $row[] = $p["min_stok"];
            $row[] = $p["location"];
            $row[] = '
            <a href="#" class="btn btn-danger btn-xs" id=' . "btnDeletePreview" . $p['no'] . ' onclick="byidPreview(' . "'" . $p['no'] . "','delete'" . ')"><i class="fas fa-trash"></i></a>';
            $data[] = $row;
        }
        $output = array(
            "data" => $data
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function byidPreview()
    {
        $result = $this->session->userdata('datapreview');
        foreach ($result as $r) {
            if ($r['no'] == $_POST['id']) {
                $data = $r;
            }
        }
        // print_r($data);
        // die;
        // $data = $result[$_POST['id']];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function deletePreview($id)
    {
        $result = $this->session->userdata('datapreview');
        $index = -1;
        foreach ($result as $key => $data) {
            if ($data['no'] == $id) {
                $index = $key;
                break;
            }
        }

        if ($index != -1) {
            unset($result[$index]);
            $this->session->set_userdata('datapreview', $result);
            $msg = [
                'success' => 'Sukses menghapus'
            ];
        } else {
            $msg = [
                'success' => 'Sukses menghapus'
            ];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }

    public function savePreview()
    {
        $post = $this->session->userdata('datapreview');
        $this->item->saveImport($post);
        if ($this->db->affected_rows() > 0) {
            $msg = [
                'success' => 'Berhasil menyimpan',
            ];
        } else {
            $msg = [
                'success' => 'Gagal menyimpan',
            ];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }

    public function downloadFormat()
    {
        $this->load->helper('download');

        force_download('assets/Template Import Item.xlsx', NULL);
    }

    public function exportpdf()
    {
        $data['data'] = $this->session->userdata('data_export');
        $filename = 'Daftar Item Apotek';
        $paper = 'A4';
        $orientation = 'landscape';
        $this->load->library('mypdf');
        $this->mypdf->generate('item/pdfitem', $data, $filename, $paper, $orientation);
    }

    public function excel()
    {
        $download = $this->session->userdata('data_export');
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Nama Item');
        $sheet->setCellValue('B1', 'Kode Item');
        $sheet->setCellValue('C1', 'Satuan');
        $sheet->setCellValue('D1', 'Harga Beli');
        $sheet->setCellValue('E1', 'Harga Jual');
        $sheet->setCellValue('F1', 'Lokasi Item');
        // $sheet->setCellValue('G1', 'Barcode');

        $column = 2;
        foreach ($download as $value) {
            $sheet->setCellValue('A' . $column, $value->item_name ?? '-');
            $sheet->setCellValue('B' . $column, $value->item_code ?? '-');
            $sheet->setCellValue('C' . $column, $value->satuan_name ?? '-');
            $sheet->setCellValue('D' . $column, $value->h_beli ?? '-');
            $sheet->setCellValue('E' . $column, $value->h_jual ?? '-');
            $sheet->setCellValue('F' . $column, $value->lokasi ?? '-');
            // if ($value->barcode != '') {
            //     require 'vendor/autoload.php';

            //     // This will output the barcode as HTML output to display in the browser
            //     $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
            //     $sheet->setCellValue('G' . $column, '-');
            // } else {
            //     $sheet->setCellValue('G' . $column, '-');
            // }
            $spreadsheet->getActiveSheet()->getStyle('D' . $column)->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
            $spreadsheet->getActiveSheet()->getStyle('E' . $column)->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
            $column++;
        }

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        // $sheet->getColumnDimension('G')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocuments.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Daftar Item.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save("php://output");
        exit();
    }
}
