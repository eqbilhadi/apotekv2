<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Opname_m extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    private function _get_data_item()
    {
        $subquery = $this->db->select("i.`id_item`, i.`item_type` AS tipe, i.udt AS tgl_opname, lc.name AS location, i.`id_category` AS kategori, i.`item_code` AS kode_brg, i.`name` AS nm_brg, SUM(CASE WHEN td.`status` = 'in' THEN td.`qty` END) AS saldo_in, SUM(CASE WHEN td.`status` = 'out' THEN td.`qty` END) AS saldo_out")
            ->from('transaksi_d td')
            ->join('item i', 'i.`id_item` = td.`id_item`')
            ->join('transaksi t', 't.`id_transaksi` = td.`id_transaksi`')
            ->join('location lc', 'lc.id_location = i.id_location', 'left')
            ->group_by('td.`id_item`')
            ->get_compiled_select();

        $this->db->select('id_item, kode_brg, nm_brg, (IFNULL(saldo_in,0) - IFNULL(saldo_out,0)) AS saldo, tipe, kategori, tgl_opname, location');
        $this->db->from("($subquery)f");

        if (($_POST['search']['value']) != null) {
            $this->db->like('nm_brg', $_POST['search']['value']);
            $this->db->or_like('kode_brg', $_POST['search']['value']);
        }

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
        $subquery = $this->db->select("i.`id_item`, i.`item_type` AS tipe, i.`id_category` AS kategori, i.`item_code` AS kode_brg, i.`name` AS nm_brg, SUM(CASE WHEN t.`status` = 'in' THEN td.`qty` END) AS saldo_in, SUM(CASE WHEN t.`status` = 'out' THEN td.`qty` END) AS saldo_out")
            ->from('transaksi_d td')
            ->join('item i', 'i.`id_item` = td.`id_item`')
            ->join('transaksi t', 't.`id_transaksi` = td.`id_transaksi`')
            ->join('location lc', 'lc.id_location = i.id_location', 'left')
            ->group_by('td.`id_item`')
            ->get_compiled_select();

        $this->db->select('id_item, kode_brg, nm_brg, (IFNULL(saldo_in,0) - IFNULL(saldo_out,0)) AS saldo, tipe, kategori');
        $this->db->from("($subquery)f");

        return $this->db->count_all_results();
    }

    private function _get_data_opnameItem()
    {
        $subquery = $this->db->select("td.id_transaksi_d, i.item_code AS kode_brg, i.name AS nm_brg, td.batch, td.expired, i.selling_price as harga, td.id_item, s.name AS satuan, SUM(CASE WHEN td.`status` = 'in' THEN td.`qty` END) AS saldo_in, SUM(CASE WHEN td.`status` = 'out' THEN td.`qty` END) AS saldo_out")
            ->from('transaksi_d td')
            ->join('item i', 'i.`id_item` = td.`id_item`')
            ->join('satuan s', 's.id_satuan = i.id_satuan')
            ->join('transaksi t', 't.`id_transaksi` = td.`id_transaksi`')
            ->where('td.id_item', $_POST['id_item'])
            ->group_by(array('td.`id_item`', 'td.`reff`'))
            ->get_compiled_select();

        $this->db->select('id_transaksi_d, id_item, kode_brg, nm_brg, batch, expired, harga, satuan, (IFNULL(saldo_in,0) - IFNULL(saldo_out,0)) AS stok_sistem,');
        $this->db->from("($subquery)f");


        // $this->db->select('td.id_transaksi_d, i.item_code AS kode_brg, i.name AS nm_brg, td.batch, td.expired, i.selling_price as harga, td.id_item, s.name AS satuan');
        // $this->db->select("SUM(CASE WHEN t.status = 'in' THEN td.qty ELSE -td.qty END) AS stok_sistem");
        // $this->db->from('transaksi_d td');
        // $this->db->join('item i', 'i.id_item = td.id_item');
        // $this->db->join('satuan s', 's.id_satuan = i.id_satuan');
        // $this->db->join('transaksi t', 't.id_transaksi = td.id_transaksi');
        // if (isset($_POST['id_item'])) {
        //     $this->db->where('td.id_item', $_POST['id_item']);
        // }
        // $this->db->group_by(array('td.`id_item`', 'td.`reff`'));
    }
    public function getopnameItem()
    {
        $this->_get_data_opnameItem();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered_opnameItem()
    {
        $this->_get_data_opnameItem();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_opnameItem()
    {
        $subquery = $this->db->select("td.id_transaksi_d, i.item_code AS kode_brg, i.name AS nm_brg, td.batch, td.expired, i.selling_price as harga, td.id_item, s.name AS satuan, SUM(CASE WHEN td.`status` = 'in' THEN td.`qty` END) AS saldo_in, SUM(CASE WHEN td.`status` = 'out' THEN td.`qty` END) AS saldo_out")
            ->from('transaksi_d td')
            ->join('item i', 'i.`id_item` = td.`id_item`')
            ->join('satuan s', 's.id_satuan = i.id_satuan')
            ->join('transaksi t', 't.`id_transaksi` = td.`id_transaksi`')
            ->where('td.id_item', $_POST['id_item'])
            ->group_by(array('td.`id_item`', 'td.`reff`'))
            ->get_compiled_select();

        $this->db->select('id_transaksi_d, id_item, kode_brg, nm_brg, batch, expired, harga, satuan, (IFNULL(saldo_in,0) - IFNULL(saldo_out,0)) AS stok_sistem,');
        $this->db->from("($subquery)f");
        // $this->db->select('td.id_transaksi_d, i.item_code AS kode_brg, i.name AS nm_brg, td.batch, td.expired, i.selling_price as harga, td.id_item');
        // $this->db->select("SUM(CASE WHEN t.status = 'in' THEN td.qty ELSE -td.qty END) AS stok_sistem");
        // $this->db->from('transaksi_d td');
        // $this->db->join('item i', 'i.id_item = td.id_item');
        // $this->db->join('satuan s', 's.id_satuan = i.id_satuan');
        // $this->db->join('transaksi t', 't.id_transaksi = td.id_transaksi');
        // if (isset($_POST['id_item'])) {
        //     $this->db->where('td.id_item', $_POST['id_item']);
        // }
        // $this->db->group_by(array('td.`id_item`', 'td.`reff`'));

        return $this->db->count_all_results();
    }

    public function save($post)
    {
        $params = [
            'status'    => 'in',
            'tgl'       => date('Y-m-d'),
            'user_id'   => $this->fungsi->user_login()->user_id,
            'type'      => 'opname'
        ];
        $this->db->insert('transaksi', $params);
        $id_transaksi = $this->db->insert_id();

        $qty = $post['sisa'];
        foreach ($qty as $key => $value) {
            $id_transaksi_d = $post['id_transaksi_d'][$key];
            $item = $this->db->query("SELECT * FROM transaksi_d WHERE id_transaksi_d = $id_transaksi_d")->row();
            $total = $value * $item->price;
            if ($value < 0) {
                $status = 'out';
            } else {
                $status = 'in';
            }
            $newVal = preg_replace("/[^0-9]/", '', $value);
            $paramsD = [
                'id_transaksi'  => $id_transaksi,
                'reff'          => $post['id_transaksi_d'][$key],
                'status'        => $status,
                'id_item'       => $item->id_item,
                'expired'       => $item->expired,
                'batch'         => $item->batch,
                'qty'           => $newVal,
                'price'         => $item->price,
                'total_price'   => $total,
                'note'          => $post['note'][$key]
            ];
            $this->db->insert('transaksi_d', $paramsD);
        }

        $id_transaksi_d = $post['id_transaksi_d'][0];
        $item = $this->db->query("SELECT * FROM transaksi_d WHERE id_transaksi_d = $id_transaksi_d")->row();

        $object = [
            'udt' => date('Y-m-d H:i:s')
        ];
        $this->db->where('id_item', $item->id_item);
        $this->db->update('item', $object);
    }

    // ========================= HISTORY OPNAME ==========================================

    private function _get_data_HistoryOpname()
    {
        $this->db->select("t.id_transaksi, t.tgl, u.name AS petugas, SUM(CASE WHEN td.status = 'in' THEN td.qty ELSE -td.qty END) AS selisih, SUM(td.`total_price`) AS penyesuaian, i.`name` AS item, td.id_item, t.cdt AS jam");
        $this->db->from("transaksi t");
        $this->db->join('transaksi_d td', 't.`id_transaksi` = td.`id_transaksi`');
        $this->db->join('user u', 'u.user_id = t.`user_id`');
        $this->db->join('item i', 'i.`id_item` = td.`id_item`');
        $this->db->where('t.type', 'opname');
        $this->db->group_by('t.id_transaksi');

        if (($_POST['search']['value']) != null) {
            $this->db->like('i.name', $_POST['search']['value']);
        }

        $this->db->order_by('t.id_transaksi', 'DESC');
    }
    public function getHistoryOpname()
    {
        $this->_get_data_HistoryOpname();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered_HistoryOpname()
    {
        $this->_get_data_HistoryOpname();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_HistoryOpname()
    {
        $this->db->select('t.id_transaksi, t.tgl, u.name AS petugas, SUM(td.`qty`) AS selisih, SUM(td.`total_price`) AS penyesuaian, i.`name` AS item, td.id_item');
        $this->db->from("transaksi t");
        $this->db->join('transaksi_d td', 't.`id_transaksi` = td.`id_transaksi`');
        $this->db->join('user u', 'u.user_id = t.`user_id`');
        $this->db->join('item i', 'i.`id_item` = td.`id_item`');
        $this->db->where('t.type', 'opname');
        $this->db->group_by('t.id_transaksi');

        return $this->db->count_all_results();
    }



    private function _get_data_RiwayatOpnameItem()
    {
        $this->db->select('td.`id_transaksi_d`, i.`name` AS item, td.`expired`, td.`batch`, td.`qty`, td.`total_price`, td.`note`, s.`name` AS satuan, td.price,  td.status');
        $this->db->from('transaksi t');
        $this->db->join('transaksi_d td', 't.`id_transaksi` = td.`id_transaksi`');
        $this->db->join('item i', 'i.id_item = td.id_item');
        $this->db->join('satuan s', 's.id_satuan = i.id_satuan');
        $this->db->where('td.id_transaksi', $_POST['id_transaksi']);

        $this->db->order_by('td.id_transaksi_d', 'DESC');
    }

    public function getRiwayatOpnameItem()
    {
        $this->_get_data_RiwayatOpnameItem();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered_RiwayatOpnameItem()
    {
        $this->_get_data_RiwayatOpnameItem();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_RiwayatOpnameItem()
    {
        $this->db->select('td.`id_transaksi_d`, i.`name` AS item, td.`expired`, td.`batch`, td.`qty`, td.`total_price`, td.`note`, s.`name` AS satuan');
        $this->db->from('transaksi t');
        $this->db->join('transaksi_d td', 't.`id_transaksi` = td.`id_transaksi`');
        $this->db->join('item i', 'i.id_item = td.id_item');
        $this->db->join('satuan s', 's.id_satuan = i.id_satuan');
        $this->db->where('td.id_transaksi', $_POST['id_transaksi']);

        $this->db->order_by('td.id_transaksi_d', 'DESC');
        return $this->db->count_all_results();
    }
}
