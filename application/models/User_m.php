<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_m extends CI_Model
{
    var $table = 'user';
    var $column_order = array(null, null, 'name', null, null);
    var $order = array(null, null, 'name', null, null);

    function __construct()
    {
        parent::__construct();
    }

    public function login($post)
    {
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('username', $post['username']);
        $this->db->where('password', sha1($post['password']));
        $query = $this->db->get();
        return $query;
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

    private function _get_data_query()
    {
        //Materi yang kosong soal tidak tampil
        $this->db->from($this->table);

        if (($_POST['search']['value']) != null) {
            $this->db->or_like('name', $_POST['search']['value']);
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('user_id', 'DESC');
        }
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

    public function createUser($post)
    {
        $params = [
            'username' => $post['username'],
            'password' => sha1($post['password']),
            'avatar' => $post['img'],
            'name' => $post['nama'],
            'address' => $post['alamat'],
            'level' => $post['level']
        ];
        $this->db->insert($this->table, $params);
    }

    public function updateUser($post)
    {
        $params = [
            'username' => $post['username'],
            'name' => $post['nama'],
            'address' => $post['alamat'],
            'level' => $post['level']
        ];
        if ($post['password'] != null) {
            $params['password'] = sha1($post['password']);
        }
        if ($post['img'] != null) {
            $params['avatar'] = $post['img'];
        }

        $this->db->where('user_id', $post['id']);
        $this->db->update($this->table, $params);
    }

    public function getdataById($id)
    {
        return $this->db->get_where($this->table, ['user_id' => $id])->row();
    }

    public function delete($id)
    {
        $this->db->delete($this->table, ['user_id' => $id]);
    }

    public function getImg($id)
    {
        $this->db->select('avatar');
        $this->db->from($this->table);
        $this->db->where('user_id', $id);
        return $this->db->get();
    }
}
