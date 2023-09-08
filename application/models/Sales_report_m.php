<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_report_m extends CI_Model
{
    var $table = 'stokin';

    function __construct()
    {
        parent::__construct();
    }

    public function get($id = null)
    {
        $this->db->from('user');
        if ($id != null) {
            $this->db->where('user_id', $id);
        }
        $query = $this->db->get();
        return $query;
    }

    public function getDraft()
    {
        $this->db->select('id_transaksi');
        $this->db->from('transaksi');
        $this->db->where('is_draft', '1');
        $this->db->where('status', 'in');
        return $this->db->get()->row();
    }

    public function getItem($id)
    {
        $this->db->select('st.name AS satuan_name, i.purchase_price AS harga, i.name');
        $this->db->from('item i');
        $this->db->where('i.id_item', $id);

        $this->db->join('satuan st', 'st.id_satuan = i.id_satuan');
        return $this->db->get()->row();
    }

    private function _get_data_query()
    {
        $this->db->select('t.id_transaksi, no_faktur, tgl, u.name AS kasir, t.description, is_draft, COUNT(td.id_transaksi_d) AS jml, SUM(td.total_price) AS total,, t.cash, t.change, c.name AS customer');
        $this->db->from('transaksi t');
        $this->db->join('transaksi_d td', 'td.`id_transaksi` = t.`id_transaksi`', 'left');
        $this->db->join('user u', 'u.user_id = t.user_id', 'left');
        $this->db->join('customer c', 'c.id_customer = t.id_customer', 'left');

        $this->db->where('t.status', 'out');

        $this->db->group_by('t.id_transaksi');

        if (isset($_POST['awal']) && isset($_POST['akhir'])) {
            $this->db->where('t.tgl BETWEEN "' . date('Y-m-d', strtotime($_POST['awal'])) . '" and "' . date('Y-m-d', strtotime($_POST['akhir'])) . '"');
        }

        if (($_POST['search']['value']) != null) {
            $this->db->or_like('no_faktur', $_POST['search']['value']);
        }

        $this->db->order_by('t.id_transaksi', 'DESC');
    }

    public function getDataAll()
    {
        $this->_get_data_query();
        $query = $this->db->get();
        return $query->result();
    }

    public function getData()
    {
        $this->_get_data_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered_data()
    {
        $this->_get_data_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_data()
    {
        $this->db->select('t.id_transaksi, no_faktur, tgl, s.name AS supplier, t.description, is_draft, COUNT(td.id_transaksi_d) AS jml, SUM(td.total_price) AS total');
        $this->db->from('transaksi t');
        $this->db->join('transaksi_d td', 'td.`id_transaksi` = t.`id_transaksi`', 'left');
        $this->db->join('supplier s', 's.`id_supplier` = t.`id_supplier`', 'left');
        $this->db->where('t.status', 'out');
        $this->db->group_by('t.id_transaksi');
        return $this->db->count_all_results();
    }

    private function _get_data_TransaksiListIncoming()
    {
        if ($_POST['id_transaksi'] != null) {
            $this->db->select('i.item_code AS kode_brg, i.name AS nama_brg, td.qty, s.name AS satuan_name, td.price, td.total_price, td.id_transaksi_d, td.expired');
            $this->db->from('transaksi_d td');
            $this->db->join('item i', 'i.id_item = td.id_item');
            $this->db->join('satuan s', 's.id_satuan = i.id_satuan');
            $this->db->join('transaksi t', 'td.id_transaksi = t.id_transaksi');

            if (($_POST['search']['value']) != null) {
                $this->db->like('i.name', $_POST['search']['value']);
            }
            if (($_POST['status']) == 'in') {
                $this->db->where('t.status', 'in');
            } else {
                $this->db->where('t.status', 'out');
            }

            $this->db->where('td.id_transaksi', $_POST['id_transaksi']);

            $this->db->order_by('td.id_transaksi_d', 'DESC');
        } else {
            $this->db->from('transaksi');
            if (($_POST['status']) == 'in') {
                $this->db->where('status', 'in');
            } else {
                $this->db->where('status', 'out');
            }
            $this->db->where('is_draft', 'yes');
        }
    }

    public function getTransaksiListIncoming()
    {
        $this->_get_data_TransaksiListIncoming();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered_TransaksiListIncoming()
    {
        $this->_get_data_TransaksiListIncoming();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_TransaksiListIncoming()
    {
        if ($_POST['id_transaksi'] != null) {
            $this->db->select('i.item_code AS kode_brg, i.name AS nama_brg, td.qty, s.name AS satuan_name, td.price, td.total_price, td.id_transaksi_d');
            $this->db->from('transaksi_d td');
            $this->db->join('item i', 'i.id_item = td.id_item');
            $this->db->join('satuan s', 's.id_satuan = i.id_satuan');
            $this->db->join('transaksi t', 'td.id_transaksi = t.id_transaksi');


            if (($_POST['search']['value']) != null) {
                $this->db->like('i.name', $_POST['search']['value']);
            }
            if (($_POST['status']) == 'in') {
                $this->db->where('t.status', 'in');
            } else {
                $this->db->where('t.status', 'out');
            }

            $this->db->where('td.id_transaksi', $_POST['id_transaksi']);

            $this->db->order_by('td.id_transaksi_d', 'DESC');
        } else {
            $this->db->from('transaksi');
            if (($_POST['status']) == 'in') {
                $this->db->where('status', 'in');
            } else {
                $this->db->where('status', 'out');
            }
            $this->db->where('is_draft', 'yes');
        }
        return $this->db->count_all_results();
    }

    public function create($post)
    {
        if ($post['id_transaksi'] == null) {
            $params = [
                'is_draft' => '1',
                'status' => 'in',
            ];
            $this->db->insert('transaksi', $params);
            $id_inv = $this->db->insert_id();
        } else {
            $id_inv = $post['id_transaksi'];
        }

        $params = [
            'id_transaksi' => $id_inv,
            'id_item' => $post['id_item'],
            'expired' => ($post['kadaluarsa'] == '') ? null : $post['kadaluarsa'],
            'batch' => ($post['batch'] == '') ? null : $post['batch'],
            'qty' => $post['qty'],
            'price' => preg_replace("/[^0-9]/", '', $post['harga']),
            'total_price' => preg_replace("/[^0-9]/", '', $post['total']),
            'expired' => $post['kadaluarsa'],
        ];
        $this->db->insert('transaksi_d', $params);

        return $id_inv;
    }

    public function akhiritransaksi($post)
    {
        $params = [
            'no_faktur'     => $post['no_faktur'],
            'tgl'           => $post['tgl'],
            'id_supplier'   => $post['supplier'],
            'description'   => $post['desc'],
            'is_draft'      => '0'
        ];

        $this->db->where('id_transaksi', $post['id_transaksi']);
        $this->db->update('transaksi', $params);
    }

    private function _getQtySatuan($id_item, $id_satuan)
    {
        $this->db->select('qty_satuan');
        $this->db->from('item_d');
        $this->db->where('id_item', $id_item);
        $this->db->where('id_satuan', $id_satuan);
        return $this->db->get()->row();
    }

    public function update($post)
    {
        $params = [
            'id_item' => $post['id_item'],
            'expired' => ($post['kadaluarsa'] == '') ? null : $post['kadaluarsa'],
            'batch' => ($post['batch'] == '') ? null : $post['batch'],
            'qty' => $post['qty'],
            'price' => preg_replace("/[^0-9]/", '', $post['harga']),
            'total_price' => preg_replace("/[^0-9]/", '', $post['total']),
            'expired' => $post['kadaluarsa'],
        ];
        $this->db->where('id_transaksi_d', $post['id_transaksi_d']);
        $this->db->update('transaksi_d', $params);
    }

    public function getdataById($id)
    {
        $this->db->select('t.id_transaksi, t.no_faktur, t.tgl, t.id_supplier, t.description');
        $this->db->from('transaksi t');
        $this->db->where('t.id_transaksi', $id);
        return $this->db->get()->row();
    }

    public function getdataByIdBarang($id)
    {
        $this->db->select('td.*, i.name as nama_item, i.item_code, s.name as nama_satuan');

        $this->db->from('transaksi_d td');
        $this->db->join('item i', 'td.id_item = i.id_item');
        $this->db->join('satuan s', 'i.id_satuan = s.id_satuan');
        $this->db->where('id_transaksi_d', $id);
        return $this->db->get()->row();
    }

    public function delete()
    {
        $this->db->delete('transaksi', ['id_transaksi' => $_POST['id_transaksi']]);
    }

    public function deleteBarang()
    {
        $this->db->delete('transaksi_d', ['id_transaksi_d' => $_POST['id_transaksi_d']]);
    }
}
