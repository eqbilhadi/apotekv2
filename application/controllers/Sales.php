<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('item_m', 'item');
        $this->load->model('customer_m', 'customer');
        $this->load->model('sales_m', 'sales');
        $this->load->model('category_m', 'category');
        $this->load->model('type_m', 'tipe');
    }

    public function index()
    {
        check_not_login();
        $customer = $this->customer->get();
        $kategori =  $this->category->get();
        $tipe =  $this->tipe->get();
        $data = array(
            'page' => 'Kasir',
            'customer' => $customer,
            'invoice' => $this->sales->invoice(),
            'kategori' => $kategori,
            'tipe' => $tipe,
        );
        $this->template->load('template', 'sales/index', $data);
    }

    public function cetak($id_trans)
    {
        check_not_login();
        $struk = $this->sales->getStruk($id_trans);
        $dt = $this->db->query("SELECT SUM(td.total_price) AS total FROM transaksi t JOIN transaksi_d td ON t.id_transaksi = td.id_transaksi WHERE t.id_transaksi = $id_trans")->row();
        $data = array(
            'page' => 'Item',
            'data' => $struk,
            'totalprice' => $dt->total
        );
        $this->load->view('print/cetak', $data);
    }

    public function getKeranjang()
    {
        $results = $this->sales->getKeranjang();
        $data = [];
        $no = $_POST['start'];
        foreach ($results as $result) {
            $row = array();
            $row[] = ++$no;
            $row[] = $result->item_code;
            $row[] = $result->item_name;
            $row[] = number_format($result->price, 0, ',', '.');
            $row[] = $result->qty . ' ' . $result->satuan_name;
            $row[] = number_format($result->total_price, 0, ',', '.');;
            $row[] = '
            <button type="button" class="btn btn-warning btn-xs" id=' . "btnEditFromBasket" . $result->id_basket . ' onclick="byidFromBasket(' . "'" . $result->id_basket . "','" . $result->id_item . "'" . ')"><i class="fas fa-pen"></i> Edit</button>
            <button type="button" class="btn btn-danger btn-xs" id=' . "btnRemove" . $result->id_basket . ' onclick="removeFromBasket(' . "'" . $result->id_basket . "','" . $result->item_name . "'" . ')"><i class="fas fa-trash"></i> Hapus</button>
            ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "data" => $data
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function getItem()
    {
        $results = $this->sales->getItem();
        $data = [];
        foreach ($results as $result) {
            $row = array();
            $row[] = $result->barcode;
            $row[] = $result->nm_brg;
            $row[] = $result->saldo;
            $row[] = '
			<button type="button" class="btn btn-success btn-sm" id=' . "btnPilih" . $result->id_item . ' onclick="pilih(' . "'" . $result->id_item . "','"  . $result->nm_brg . "','"  . $result->saldo . "'" . ')"><i class="fas fa-cart-plus"></i></button>
            ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->sales->count_all_item(),
            "recordsFiltered" => $this->sales->count_filtered_item(),
            "data" => $data
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function getDataItem()
    {
        $type = 'opt';
        $satuan = $this->item->getSatuanItem($_POST['id_item'], $type);
        $id_satuan = $_POST['id_satuan'] ?? null;
        $output = '';
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
        $dataItem = $this->db->query("SELECT i.name AS item_name, i.item_code, i.selling_price AS item_price, TRIM(st.stok)+0 AS stok
                                      FROM stok st
                                      JOIN item i ON i.id_item = st.id_item
                                      WHERE id_stok = {$_POST['id_stok']}")->row();

        $data = [
            'option' => $output,
            'data' => $dataItem
        ];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function getPriceStokFromSatuan()
    {
        $r = $this->db->query("SELECT qty_satuan FROM item_d WHERE id_item = {$_POST['id_item']} AND id_satuan = {$_POST['id_satuan']}")->row();
        $dataItem = $this->db->query("SELECT i.name AS item_name, i.selling_price AS item_price, TRIM(st.stok)+0 AS stok
                                      FROM stok st
                                      JOIN item i ON i.id_item = st.id_item
                                      WHERE id_stok = {$_POST['id_stok']}")->row();
        $data = [
            'satuan' => $r->qty_satuan,
            'stok' => $dataItem->stok,
            'price' => $dataItem->item_price
        ];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function grandTotal()
    {
        $this->db->select('SUM(total_price) AS grandTotal');
        $this->db->from('basket');

        $query = $this->db->get()->row();

        $data = $query->grandTotal;
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function addItemtoBasket()
    {
        $post = $this->input->post(null, TRUE);
        $this->sales->addItemtoBasket($post);
        if ($this->db->affected_rows() > 0) {
            $msg = [
                'success' => 'Berhasil memasukkan ke keranjang',
            ];
        } else {
            $msg = [
                'error' => 'Gagal memasukkan ke keranjang'
            ];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }

    public function addItemtoBasket_by_barcode()
    {
        $post = $this->input->post(null, TRUE);
        $this->sales->addItemtoBasket_by_barcode($post);
        if ($this->db->affected_rows() > 0) {
            $msg = [
                'success' => 'Berhasil memasukkan ke keranjang',
            ];
        } else {
            $msg = [
                'error' => 'Gagal memasukkan ke keranjang'
            ];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }

    public function byidFromBasket()
    {
        $id_item = $_POST['id_item'];
        $data = $this->db->query("SELECT *
        FROM
        (
            SELECT id_item, kode_brg, nm_brg, (IFNULL(saldo_in,0) - IFNULL(saldo_out,0)) AS saldo, harga, jml_beli, satuan, total_price
            FROM
            (
                SELECT
                i.`item_code` AS kode_brg,
                i.`id_item`,
                b.`price`,
                s.name AS satuan,
                b.`qty` AS jml_beli,
                b.`total_price`,
                i.`selling_price` AS harga,
                i.`name` AS nm_brg,
                SUM(CASE WHEN t.`status` = 'in' THEN td.`qty` END) AS saldo_in,
                SUM(CASE WHEN t.`status` = 'out' THEN td.`qty` END) AS saldo_out
                FROM transaksi_d td
                JOIN item i ON i.`id_item` = td.`id_item`
                JOIN transaksi t ON t.`id_transaksi` = td.`id_transaksi`
                JOIN basket b ON b.id_item = i.id_item
                JOIN satuan s ON s.`id_satuan` = i.`id_satuan`
                WHERE b.id_item = $id_item
                GROUP BY td.`id_item`
            )f
        )f")->row();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function editFromBasket()
    {
        $post = $this->input->post(null, TRUE);
        if ($post['qty'] > $post['stok']) {
            $msg = [
                'stok' => 'Stok tidak cukup'
            ];
        } else {
            $this->sales->editFromBasket($post);
            if ($this->db->affected_rows() > 0) {
                $msg = [
                    'success' => 'Berhasil'
                ];
            } else {
                $msg = [
                    'error' => 'Gagal'
                ];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }

    public function removeFromBasket()
    {
        $post = $this->input->post(null, TRUE);
        $this->sales->removeFromBasket($post);
        if ($this->db->affected_rows() > 0) {
            $msg = [
                'success' => 'Berhasil menghapus dari keranjang'
            ];
        } else {
            $msg = [
                'error' => 'Gagal menghapus dari keranjang'
            ];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }

    public function processPembayaran()
    {
        $post = $this->input->post(null, TRUE);
        $basket = $this->db->query('SELECT * FROM basket')->result();
        $totalHarga = preg_replace("/[^0-9]/", '', $post['final_total']);
        $cash = preg_replace("/[^0-9]/", '', $post['cash']);
        if ($post['cash'] == '') {
            $msg = [
                'failed' => 'Cash masih kosong, silahkan input!'
            ];
        } else if ($cash < $totalHarga) {
            $msg = [
                'failed' => 'Cash masih kurang!'
            ];
        } else if (empty($basket)) {
            $msg = [
                'failed' => 'Keranjang masih kosong tidak ada barang yang dibeli'
            ];
        } else {
            $id_trans = $this->sales->processPembayaran($post);
            if ($this->db->trans_status() == TRUE) {
                $msg = [
                    'success' => 'Pembayaran sukses',
                    'id_trans' => $id_trans
                ];
            } else {
                $msg = [
                    'error' => 'Pembayaran gagal'
                ];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }

    public function cancelPembayaran()
    {
        $this->db->truncate('transaksi_d_temp');

        if ($this->db->truncate('basket')) {
            $msg = [
                'success' => 'Berhasil'
            ];
        } else {
            $msg = [
                'error' => 'Gagal'
            ];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }

    public function getNoInvoice()
    {
        $msg = [
            'invoice' => $this->sales->invoice()
        ];
        $this->output->set_content_type('application/json')->set_output(json_encode($msg));

    }
}
