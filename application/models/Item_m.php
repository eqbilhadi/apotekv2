<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Item_m extends CI_Model
{
    var $table = 'item';

    function __construct()
    {
        parent::__construct();
    }

    public function get($id = null)
    {
        $this->db->from('item');
        if ($id != null) {
            $this->db->where('id_item', $id);
        }
        $query = $this->db->get();
        return $query;
    }

    private function _get_data_query()
    {
        $this->db->select('i.id_item, item_code, item_type, i.name AS item_name, ctg.name AS category_name, st.name AS satuan_name, i.selling_price AS h_jual, i.purchase_price AS h_beli, i.item_code, lc.name as lokasi, i.barcode');
        $this->db->from('item i');
        $this->db->join('category ctg', 'ctg.id_category = i.id_category', 'left');
        $this->db->join('location lc', 'lc.id_location = i.id_location', 'left');
        $this->db->join('satuan st', 'st.id_satuan = i.id_satuan', 'left');

        if (($_POST['search']['value']) != null) {
            $this->db->like('i.name', $_POST['search']['value']);
            $this->db->or_like('i.item_code', $_POST['search']['value']);
            $this->db->or_like('i.barcode', $_POST['search']['value']);
        }
        if ($_POST['tipe'] != null && $_POST['tipe'] != 'xx') {
            $this->db->where('i.item_type', $_POST['tipe']);
        }
        if ($_POST['kategori'] != null && $_POST['kategori'] != 'xx') {
            $this->db->where('ctg.id_category', $_POST['kategori']);
        }
        if ($_POST['lokasi'] != null && $_POST['lokasi'] != 'xx') {
            $this->db->where('lc.id_location', $_POST['lokasi']);
        }

        $this->db->order_by('i.id_item', 'DESC');
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

    public function getDataAll()
    {
        $this->_get_data_query();
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
        $this->db->select('i.id_item, item_code, item_type, i.name AS item_name, ctg.name AS category_name, st.name AS satuan_name, i.selling_price AS h_jual, i.purchase_price AS h_beli, i.item_code, lc.name as lokasi');
        $this->db->from('item i');
        $this->db->join('category ctg', 'ctg.id_category = i.id_category', 'left');
        $this->db->join('location lc', 'lc.id_location = i.id_location', 'left');
        $this->db->join('satuan st', 'st.id_satuan = i.id_satuan', 'left');
        return $this->db->count_all_results();
    }

    public function create($post)
    {
        $params = [
            'item_code' => $post['kode'],
            'item_type' => $post['tipe'],
            'barcode' => $post['barcode'],
            'name' => $post['nama'],
            'id_category' => $post['kategori'],
            'id_satuan' => $post['satuan'],
            'purchase_price' => preg_replace("/[^0-9]/", '', $post['hrg_beli']),
            'selling_price' => preg_replace("/[^0-9]/", '', $post['hrg_jual']),
            'min_stok' => $post['min_stok'],
            'id_location' => $post['lokasi']
        ];
        $this->db->insert('item', $params);
    }

    public function update($post)
    {
        $params = [
            'item_code' => $post['kode'],
            'item_type' => $post['tipe'],
            'barcode' => $post['barcode'],
            'name' => $post['nama'],
            'id_category' => $post['kategori'],
            'id_satuan' => $post['satuan'],
            'purchase_price' => preg_replace("/[^0-9]/", '', $post['hrg_beli']),
            'selling_price' => preg_replace("/[^0-9]/", '', $post['hrg_jual']),
            'min_stok' => $post['min_stok'],
            'id_location' => $post['lokasi']
        ];
        $this->db->where('id_item', $post['id_item']);
        $this->db->update('item', $params);
    }

    public function getdataById($id)
    {
        $this->db->from('item');
        $this->db->where('id_item', $id);

        return $this->db->get()->row();
    }

    public function delete($id)
    {
        $this->db->delete($this->table, ['id_item' => $id]);
    }



    //GET STOK
    private function _get_data_stok()
    {
        $this->db->select('i.name AS item_name, i.item_code, TRIM(s.stok)+0 AS stok, s.expired, s.id_stok, st.name AS satuan_name');
        $this->db->from('stok s');
        $this->db->join('item i', 'i.id_item = s.id_item');
        $this->db->join('item_d id', 'id.id_item = i.id_item');
        $this->db->join('satuan st', 'st.id_satuan = id.id_satuan');
        $this->db->where('id.st_satuan', 'utama');

        if ($_POST['stok'] == 'habis') {
            $stok = 's.stok <= i.min_stok';
            $this->db->where($stok);
        }
        if ($_POST['stok'] == 'kadaluarsa') {
            $not = 's.expired <> "0000-00-00"';
            $expired = date('Y-m-d');
            $this->db->where('s.expired <=', $expired);
            $this->db->where($not);
            // $this->db->or_where('s.expired <=', $expired);
        }

        if (($_POST['search']['value']) != null) {
            $this->db->or_like('i.name', $_POST['search']['value']);
        }
    }

    public function getStok()
    {
        $this->_get_data_stok();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered_stok()
    {
        $this->_get_data_stok();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_stok()
    {
        $this->db->select('i.name AS item_name, i.item_code, TRIM(s.stok)+0 AS stok, s.expired, s.id_stok, st.name AS satuan_name');
        $this->db->from('stok s');
        $this->db->join('item i', 'i.id_item = s.id_item');
        $this->db->join('item_d id', 'id.id_item = i.id_item');
        $this->db->join('satuan st', 'st.id_satuan = id.id_satuan');
        $this->db->where('id.st_satuan', 'utama');
        return $this->db->count_all_results();
    }

    public function saveImport($post)
    {
        foreach ($post as $key => $v) {
            $st = $this->db->query("SELECT id_satuan FROM satuan WHERE name LIKE '%" . $v['id_satuan'] . "%'")->row();
            $ctg = $this->db->query("SELECT id_category FROM category WHERE name LIKE '%" . $v['id_kategori'] . "%'")->row();
            $params = [
                'item_code' => $v['item_code'],
                'item_type' => $v['item_type'],
                'name' => $v['name'],
                'id_location' => $v['location'],
                'id_satuan' => $st->id_satuan ?? null,
                'id_category' => $ctg->id_category ?? null,
                'purchase_price' => $v['purchase_price'],
                'selling_price' => $v['selling_price'],
                'min_stok' => $v['min_stok'] ?? null
            ];
            $this->db->insert('item', $params);
        }
    }
}
