<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_m extends CI_Model
{
    var $table = 'customer';

    function __construct()
    {
        parent::__construct();
    }

    private function _get_data_query()
    {
        $this->db->select('b.id_basket, b.id_item, b.item_code, i.name AS item_name, b.price, b.qty, s.name AS satuan_name, b.total_price,');
        $this->db->from('basket b');
        $this->db->join('item i', 'i.id_item = b.id_item');
        $this->db->join('satuan s', 's.id_satuan = i.id_satuan');
    }

    public function getKeranjang()
    {
        $this->_get_data_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function invoice()
    {
        $sql = "SELECT MAX(MID(no_faktur,10,4)) AS invoice FROM transaksi 
        WHERE MID(no_faktur,4,6) = DATE_FORMAT(CURDATE(), '%y%m%d') AND status = 'out'";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $n = ((int)$row->invoice) + 1;
            $no = sprintf("%'.04d", $n);
        } else {
            $no = "0001";
        }
        $invoice = "APT" . date('ymd') . $no;
        return $invoice;
    }


    private function _get_data_item()
    {
        $where = 'i.id_item NOT IN (SELECT id_item FROM basket)';
        $subquery = $this->db->select("i.`id_item`, i.`barcode`, i.`item_type` AS tipe, i.`id_category` AS kategori, i.`item_code` AS kode_brg, i.`name` AS nm_brg, SUM(CASE WHEN td.`status` = 'in' THEN td.`qty` END) AS saldo_in, SUM(CASE WHEN td.`status` = 'out' THEN td.`qty` END) AS saldo_out")
            ->from('transaksi_d td')
            ->join('item i', 'i.`id_item` = td.`id_item`')
            ->join('transaksi t', 't.`id_transaksi` = td.`id_transaksi`')
            ->where($where)
            ->group_by('td.`id_item`')
            ->get_compiled_select();

        $this->db->select('id_item, kode_brg, barcode, nm_brg, (IFNULL(saldo_in,0) - IFNULL(saldo_out,0)) AS saldo, tipe, kategori');
        $this->db->from("($subquery)f");

        if (($_POST['search']['value']) != null) {
            $this->db->like('nm_brg', $_POST['search']['value']);
            $this->db->or_like('kode_brg', $_POST['search']['value']);
            $this->db->or_like('barcode', $_POST['search']['value']);
        }

        if ($_POST['tipe'] != null && $_POST['tipe'] != 'xx') {
            $this->db->where('tipe', $_POST['tipe']);
        }
        if ($_POST['kategori'] != null && $_POST['kategori'] != 'xx') {
            $this->db->where('kategori', $_POST['kategori']);
        }
        $this->db->where('(IFNULL(saldo_in,0) - IFNULL(saldo_out,0)) <> 0');
        
        $this->db->order_by('nm_brg', 'asc');
    }
    public function getItem()
    {
        $this->_get_data_item();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered_item()
    {
        $this->_get_data_item();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_item()
    {
        $subquery = $this->db->select("i.`item_code` AS kode_brg, i.`name` AS nm_brg, SUM(CASE WHEN t.`status` = 'in' THEN td.`qty` END) AS saldo_in, SUM(CASE WHEN t.`status` = 'out' THEN td.`qty` END) AS saldo_out")
            ->from('transaksi_d td')
            ->join('item i', 'i.`id_item` = td.`id_item`')
            ->join('transaksi t', 't.`id_transaksi` = td.`id_transaksi`')
            ->group_by('td.`id_transaksi_d`')
            ->get_compiled_select();

        $this->db->select('kode_brg, nm_brg, (IFNULL(saldo_in,0) - IFNULL(saldo_out,0)) AS saldo');
        $this->db->from("($subquery)f");
        return $this->db->count_all_results();
    }


    public function addItemtoBasket($post)
    {
        $id_item = $post['id_item'];
        $brg = $this->db->query("SELECT *, SUM(CASE WHEN td.status = 'in' THEN td.qty ELSE -td.qty END) AS qty FROM transaksi_d td WHERE id_item = '$id_item' GROUP BY id_item, reff ORDER BY expired ASC ")->result();
        $brgout = $this->db->query("SELECT SUM(qty) AS qty FROM transaksi_d td JOIN transaksi t ON td.id_transaksi = t.id_transaksi WHERE id_item = '$id_item' AND td.status = 'out'")->result();
        foreach ($brg as $key => $val) {
            if (!empty($brgout[$key])) {
                $qty[] = $val->qty - $brgout[$key]->qty;
            } else {
                $qty[] = $val->qty;
            }
        }

        for ($i = 0; $i < count($qty); $i++) {
            if ($qty[$i] < 0) {
                $j = $i + 1;
                if (!empty($qty[$j])) {
                    $qty[$j] = $qty[$j] + $qty[$i];
                }
                $qty[$i] = 0;
            }
        }

        foreach ($brg as $key => $val) {
            $qty[$key] = $val->qty;
        }


        $sum = 0;
        $p  = 0;
        $result = [];
        $jml = $post['qty'];
        foreach ($qty as $index => $element) {
            if ($element !== 0) {
                if ($element >= $post['qty']) {
                    if ($sum != 0) {
                        $brg[$index]->jml_keluar = $jml - $sum;
                    } else {
                        $brg[$index]->jml_keluar = $jml;
                    }
                    $result[] = $brg[$index];
                    break;
                } else {
                    $sum += $element;
                    if ($sum <= $post['qty']) {
                        $result[] = $brg[$index];
                        $brg[$index]->jml_keluar = $element;
                        $p += $element;
                        if ($sum == $post['qty']) {
                            // $result[] = $brg[$index];
                            break;
                        }
                    }
                    if ($sum >= $post['qty']) {
                        $brg[$index]->jml_keluar = $jml - $p;
                        $result[] = $brg[$index];
                        break;
                    }
                }
            }
        }

        $data = $this->db->query("SELECT * FROM item WHERE id_item = $id_item")->row();
        $totalHarga = $post['qty'] * $data->selling_price;
        $params = [
            'id_item' => $id_item,
            'item_code' => $data->item_code,
            'price' => $data->selling_price,
            'total_price' => $totalHarga,
            'qty' => $post['qty'],
        ];
        $this->db->insert('basket', $params);
        $id_basket = $this->db->insert_id();

        foreach ($result as $val) {
            $total = $data->selling_price * $val->jml_keluar;
            $paramsTemp[] = [
                'reff'          => $val->id_transaksi_d,
                'id_basket'     => $id_basket,
                'id_item'       => $val->id_item,
                'expired'       => $val->expired,
                'batch'         => $val->batch,
                'qty'           => $val->jml_keluar,
                'price'         => $data->selling_price,
                'total_price'   => $total
            ];
        }
        $this->db->insert_batch('transaksi_d_temp', $paramsTemp);
    }

    public function addItemtoBasket_by_barcode($post)
    {
        
        $barcode = $post['barcode'];
        $sql = "SELECT id_item FROM item WHERE barcode = '$barcode'";
        $i = $this->db->query($sql)->row();
        $id_item = $i->id_item;
        $brg = $this->db->query("SELECT *, SUM(CASE WHEN td.status = 'in' THEN td.qty ELSE -td.qty END) AS qty FROM transaksi_d td WHERE id_item = '$id_item' GROUP BY id_item, reff ORDER BY expired ASC ")->result();
        $brgout = $this->db->query("SELECT SUM(qty) AS qty FROM transaksi_d td JOIN transaksi t ON td.id_transaksi = t.id_transaksi WHERE id_item = '$id_item' AND td.status = 'out'")->result();
        foreach ($brg as $key => $val) {
            if (!empty($brgout[$key])) {
                $qty[] = $val->qty - $brgout[$key]->qty;
            } else {
                $qty[] = $val->qty;
            }
        }

        for ($i = 0; $i < count($qty); $i++) {
            if ($qty[$i] < 0) {
                $j = $i + 1;
                if (!empty($qty[$j])) {
                    $qty[$j] = $qty[$j] + $qty[$i];
                }
                $qty[$i] = 0;
            }
        }

        foreach ($brg as $key => $val) {
            $qty[$key] = $val->qty;
        }


        $sum = 0;
        $p  = 0;
        $result = [];
        $jml = $post['qty'];
        foreach ($qty as $index => $element) {
            if ($element !== 0) {
                if ($element >= $post['qty']) {
                    if ($sum != 0) {
                        $brg[$index]->jml_keluar = $jml - $sum;
                    } else {
                        $brg[$index]->jml_keluar = $jml;
                    }
                    $result[] = $brg[$index];
                    break;
                } else {
                    $sum += $element;
                    if ($sum <= $post['qty']) {
                        $result[] = $brg[$index];
                        $brg[$index]->jml_keluar = $element;
                        $p += $element;
                        if ($sum == $post['qty']) {
                            // $result[] = $brg[$index];
                            break;
                        }
                    }
                    if ($sum >= $post['qty']) {
                        $brg[$index]->jml_keluar = $jml - $p;
                        $result[] = $brg[$index];
                        break;
                    }
                }
            }
        }

        $data = $this->db->query("SELECT * FROM item WHERE id_item = $id_item")->row();
        $totalHarga = $post['qty'] * $data->selling_price;
        $params = [
            'id_item' => $id_item,
            'item_code' => $data->item_code,
            'price' => $data->selling_price,
            'total_price' => $totalHarga,
            'qty' => $post['qty'],
        ];
        $this->db->insert('basket', $params);
        $id_basket = $this->db->insert_id();

        foreach ($result as $val) {
            $total = $data->selling_price * $val->jml_keluar;
            $paramsTemp[] = [
                'reff'          => $val->id_transaksi_d,
                'id_basket'     => $id_basket,
                'id_item'       => $val->id_item,
                'expired'       => $val->expired,
                'batch'         => $val->batch,
                'qty'           => $val->jml_keluar,
                'price'         => $data->selling_price,
                'total_price'   => $total
            ];
        }
        $this->db->insert_batch('transaksi_d_temp', $paramsTemp);
    }

    public function editFromBasket($post)
    {
        $this->db->truncate('transaksi_d_temp');
        $id_item = $post['id_item'];
        $brg = $this->db->query("SELECT *, SUM(CASE WHEN td.status = 'in' THEN td.qty ELSE -td.qty END) AS qty FROM transaksi_d td WHERE id_item = '$id_item' GROUP BY id_item, reff ORDER BY expired ASC ")->result();
        $brgout = $this->db->query("SELECT SUM(qty) AS qty FROM transaksi_d td JOIN transaksi t ON td.id_transaksi = t.id_transaksi WHERE id_item = '$id_item' AND td.status = 'out'")->result();
        foreach ($brg as $key => $val) {
            if (!empty($brgout[$key])) {
                $qty[] = $val->qty - $brgout[$key]->qty;
            } else {
                $qty[] = $val->qty;
            }
        }
        for ($i = 0; $i < count($qty); $i++) {
            if ($qty[$i] < 0) {
                $j = $i + 1;
                if (!empty($qty[$j])) {
                    $qty[$j] = $qty[$j] + $qty[$i];
                }
                $qty[$i] = 0;
            }
        }
        foreach ($brg as $key => $val) {
            $qty[$key] = $val->qty;
        }
        $sum = 0;
        $p  = 0;
        $result = [];
        $jml = $post['qty'];
        foreach ($qty as $index => $element) {
            if ($element !== 0) {
                if ($element >= $post['qty']) {
                    if ($sum != 0) {
                        $brg[$index]->jml_keluar = $jml - $sum;
                    } else {
                        $brg[$index]->jml_keluar = $jml;
                    }
                    $result[] = $brg[$index];
                    break;
                } else {
                    $sum += $element;
                    if ($sum <= $post['qty']) {
                        $result[] = $brg[$index];
                        $brg[$index]->jml_keluar = $element;
                        $p += $element;
                        if ($sum == $post['qty']) {
                            // $result[] = $brg[$index];
                            break;
                        }
                    }
                    if ($sum >= $post['qty']) {
                        $brg[$index]->jml_keluar = $jml - $p;
                        $result[] = $brg[$index];
                        break;
                    }
                }
            }
        }
        $params = [
            'price' => preg_replace("/[^0-9]/", '', $post['harga']),
            'total_price' => preg_replace("/[^0-9]/", '', $post['total']),
            'qty' => $post['qty'],
        ];
        $this->db->where('id_item', $post['id_item']);
        $this->db->update('basket', $params);

        $data = $this->db->query("SELECT * FROM basket WHERE id_item = $id_item")->row();

        foreach ($result as $val) {
            $harga = preg_replace("/[^0-9]/", '', $post['harga']);
            $total = $harga * $val->jml_keluar;
            $paramsTemp[] = [
                'reff'          => $val->id_transaksi_d,
                'id_basket'     => $data->id_basket,
                'id_item'       => $val->id_item,
                'expired'       => $val->expired,
                'batch'         => $val->batch,
                'qty'           => $val->jml_keluar,
                'price'         => $harga,
                'total_price'   => $total
            ];
        }
        $this->db->insert_batch('transaksi_d_temp', $paramsTemp);
    }

    public function removeFromBasket($post)
    {
        $this->db->delete('basket', ['id_basket' => $post['id_basket']]);
        $this->db->delete('transaksi_d_temp', ['id_basket' => $post['id_basket']]);
    }

    public function processPembayaran($post)
    {
        $invoice = $this->invoice();
        $totalHarga = preg_replace("/[^0-9]/", '', $post['final_total']);
        $cash = preg_replace("/[^0-9]/", '', $post['cash']);
        $change = preg_replace("/[^0-9]/", '', $post['change']);
        $this->db->trans_start();
        $params = [
            'tgl'           => $post['date'],
            'id_customer'   => $post['customer'],
            'status'        => 'out',
            'user_id'       => $post['user_id'],
            'no_faktur'     => $invoice,
            'cash'          => $cash,
            'change'        => $change,
            'description'   => $post['note'],
        ];
        $this->db->insert('transaksi', $params);
        $id_transaksi = $this->db->insert_id();

        $basket = $this->db->query("SELECT * FROM transaksi_d_temp")->result();
        //ADD DATA to TB TRANSAKSI_D
        foreach ($basket as $val) {
            $paramsStokout = [
                'id_transaksi'  => $id_transaksi,
                'status'        => 'out',
                'id_item'       => $val->id_item,
                'qty'           => $val->qty,
                'reff'          => $val->reff,
                'batch'         => $val->batch,
                'expired'       => $val->expired,
                'price'         => $val->price,
                'total_price'   => $val->total_price,
            ];
            $this->db->insert('transaksi_d', $paramsStokout);
        }
        $this->db->truncate('basket');
        $this->db->truncate('transaksi_d_temp');

        $this->db->trans_complete();
        return $id_transaksi;
    }

    private function _getQtySatuan($id_item, $id_satuan)
    {
        $this->db->select('qty_satuan');
        $this->db->from('item_d');
        $this->db->where('id_item', $id_item);
        $this->db->where('id_satuan', $id_satuan);
        return $this->db->get()->row();
    }

    public function getStruk($id)
    {
        $query = "SELECT sl.tgl, c.name AS customer_name, usr.name AS kasir, sl.no_faktur,
        sl.cash, sl.change, sl.description, i.name AS item_name, st.name AS satuan_name, sd.price AS item_price, sd.qty, i.`item_code`,
        sd.total_price AS item_totalprice
        FROM transaksi sl
        JOIN transaksi_d sd ON sl.id_transaksi = sd.id_transaksi
        LEFT JOIN customer c ON c.`id_customer` = sl.`id_customer`
        JOIN USER usr ON usr.`user_id` = sl.`user_id`
        JOIN item i ON i.id_item = sd.id_item
        JOIN satuan st ON st.`id_satuan` = i.id_satuan
        WHERE sl.id_transaksi = $id";
        return $this->db->query($query)->result();
    }
}
