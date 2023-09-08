<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stok_m extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }


    private function _get_data_query()
    {
        $subquery = $this->db->select("i.`id_item` AS iditem, i.`barcode` AS ibarcode, i.`item_type` AS tipe, i.`id_category` AS kategori, i.`item_code` AS kode_brg, i.`name` AS nm_brg, st.`name` AS satuan, lc.`name` AS lokasi, i.min_stok, SUM(CASE WHEN td.`status` = 'in' THEN td.`qty` END) AS saldo_in, SUM(CASE WHEN td.`status` = 'out' THEN td.`qty` END) AS saldo_out")
            ->from('item i')
            ->join('transaksi_d td', 'i.`id_item` = td.`id_item`', 'left')
            ->join('satuan st', 'i.`id_satuan` = st.`id_satuan`', 'left')
            ->join('transaksi t', 't.`id_transaksi` = td.`id_transaksi`', 'left')
            ->join('location lc', 'i.`id_location` = lc.`id_location`', 'left')
            ->group_by('i.`id_item`')
            ->get_compiled_select();

        $this->db->select('iditem, kode_brg, ibarcode, satuan, nm_brg, (IFNULL(saldo_in,0) - IFNULL(saldo_out,0)) AS saldo, tipe, kategori, lokasi');
        $this->db->from("($subquery)f");

        if (($_POST['search']['value']) != null) {
            $this->db->like('nm_brg', $_POST['search']['value']);
            $this->db->or_like('kode_brg', $_POST['search']['value']);
            $this->db->or_like('barcode', $_POST['search']['value']);
        }

        if ($_POST['tipe'] != null && $_POST['tipe'] != 'xx') {
            $this->db->where('tipe', $_POST['tipe']);
        }

        if ($_POST['stok'] == 'tipis') {
            $this->db->join('item i', 'i.id_item = f.iditem');
            $where = "(IFNULL(saldo_in,0) - IFNULL(saldo_out,0)) < i.min_stok";
            $this->db->where($where);
            $this->db->where("(IFNULL(saldo_in,0) - IFNULL(saldo_out,0)) <>", 0);
        } else if ($_POST['stok'] == 'habis') {
            $this->db->where('(IFNULL(saldo_in,0) - IFNULL(saldo_out,0)) =', 0);
        } else if ($_POST['stok'] == 'negatif') {
            $this->db->where('(IFNULL(saldo_in,0) - IFNULL(saldo_out,0)) <', 0);
        }

        // $this->db->order_by('nm_brg', 'asc');
    }
    public function getStok()
    {
        $this->_get_data_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered_stok()
    {
        $this->_get_data_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_stok()
    {
        $subquery = $this->db->select("i.`id_item` AS iditem, i.`barcode` AS ibarcode, i.`item_type` AS tipe, i.`id_category` AS kategori, i.`item_code` AS kode_brg, i.`name` AS nm_brg, lc.`name` AS lokasi, i.min_stok, SUM(CASE WHEN td.`status` = 'in' THEN td.`qty` END) AS saldo_in, SUM(CASE WHEN td.`status` = 'out' THEN td.`qty` END) AS saldo_out")
            ->from('item i')
            ->join('transaksi_d td', 'i.`id_item` = td.`id_item`', 'left')
            ->join('satuan st', 'i.`id_satuan` = st.`id_satuan`', 'left')
            ->join('transaksi t', 't.`id_transaksi` = td.`id_transaksi`', 'left')
            ->join('location lc', 'i.`id_location` = lc.`id_location`', 'left')
            ->group_by('i.`id_item`')
            ->get_compiled_select();

        $this->db->select('iditem, kode_brg, ibarcode, nm_brg, (IFNULL(saldo_in,0) - IFNULL(saldo_out,0)) AS saldo, tipe, kategori, lokasi');
        $this->db->from("($subquery)f");
        return $this->db->count_all_results();
    }
}
