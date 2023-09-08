<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_m extends CI_Model
{
    var $table = 'supplier';

    function __construct()
    {
        parent::__construct();
    }

    public function get($id = null)
    {
        $this->db->from('supplier');
        if ($id != null) {
            $this->db->where('id_supplier', $id);
        }
        $query = $this->db->get();
        return $query;
    }

    private function _get_data_query()
    {
        $this->db->from($this->table);

        if (($_POST['search']['value']) != null) {
            $this->db->or_like('name', $_POST['search']['value']);
        }

        $this->db->order_by('id_supplier', 'DESC');
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
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function create($post)
    {
        $params = [
            'name' => $post['nama'],
            'phone' => $post['phone'],
            'address' => $post['address'],
            'description' => $post['desc'],
        ];
        $this->db->insert($this->table, $params);
    }

    public function update($post)
    {
        $params = [
            'name' => $post['nama'],
            'phone' => $post['phone'],
            'address' => $post['address'],
            'description' => $post['desc'],
        ];

        $this->db->where('id_supplier', $post['id_supplier']);
        $this->db->update($this->table, $params);
    }

    public function getdataById($id)
    {
        return $this->db->get_where($this->table, ['id_supplier' => $id])->row();
    }

    public function delete($id)
    {
        $this->db->delete($this->table, ['id_supplier' => $id]);
    }
}
