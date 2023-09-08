<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Type_m extends CI_Model
{
    var $table = 'type';

    function __construct()
    {
        parent::__construct();
    }

    public function get($id = null)
    {
        $this->db->from('type');
        if ($id != null) {
            $this->db->where('id_type', $id);
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

        $this->db->order_by('id_type', 'DESC');
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
        foreach ($post['nama'] as $value) {
            $params[] = array(
                'name' => $value
            );
        }
        // print_r($params);
        // die;
        $this->db->insert_batch($this->table, $params);
    }

    public function update($post)
    {
        $params = [
            'name' => $post['nama'][0],
        ];
        // print_r($params);
        // die;

        $this->db->where('id_type', $post['id_type']);
        $this->db->update($this->table, $params);
    }

    public function getdataById($id)
    {
        return $this->db->get_where($this->table, ['id_type' => $id])->row();
    }

    public function delete($id)
    {
        $this->db->delete($this->table, ['id_type' => $id]);
    }
}
