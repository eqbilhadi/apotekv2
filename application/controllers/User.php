<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('User_m', 'user');
    }

    public function index()
    {
        check_not_login();
        check_admin();
        $data = array(
			'page' => 'Pengaturan Pengguna',
		);
        $this->template->load('template', 'user/index', $data);
    }

    public function getUser()
    {
        $results = $this->user->getData();
        // print_r($results);
        // die;
        $data = [];
        $no = $_POST['start'];
        foreach ($results as $result) {
            $row = array();
            $row[] = ++$no;
            if ($result->avatar != null) {
                $row[] = '<div class="user-block"><img src="' . base_url('assets/img/users/') . $result->avatar . '" class="img-circle" alt=""></div>';
            } else {
                $row[] = '-';
            }
            $row[] = $result->name;
            $row[] = ($result->level == 1) ? '<a href="#" class="badge badge-danger mb-1">Admin</a>' : '<a href="#" class="badge badge-success mb-1">Karyawan</a>';
            if($result->level == 1) {
                $row[] = '
                <a href="#" class="btn btn-warning btn-xs" id=' . "btnEdit" . $result->user_id . ' onclick="byid(' . "'" . $result->user_id . "','edit'" . ')"><i class="fas fa-pen"></i></a>';
            } else {
                $row[] = '
                <a href="#" class="btn btn-warning btn-xs" id=' . "btnEdit" . $result->user_id . ' onclick="byid(' . "'" . $result->user_id . "','edit'" . ')"><i class="fas fa-pen"></i></a>
                <a href="#" class="btn btn-danger btn-xs" id=' . "btnDelete" . $result->user_id . ' onclick="byid(' . "'" . $result->user_id . "','delete'" . ')"><i class="fas fa-trash"></i></a>';
            }
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->user->count_all_data(),
            "recordsFiltered" => $this->user->count_filtered_data(),
            "data" => $data
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function add()
    {
        $type = 'add';
        $this->validation($type);

        if ($this->form_validation->run() == FALSE) {
            $msg = [
                'error' => [
                    'nama' => form_error('nama'),
                    'username' => form_error('username'),
                    'password' => form_error('password'),
                    'konfpass' => form_error('konfpass'),
                ]
            ];
        } else {
            $config['upload_path']   = './assets/img/users';
            $config['allowed_types'] = 'jpg|png|jpeg';
            // $config['max_size']		 = 8024;
            $config['file_name']     = 'imgUser_' . substr(md5(rand()), 0, 7);
            $this->load->library('upload', $config);
            $post = $this->input->post(null, TRUE);
            if (@$_FILES['img']['name'] != null) {
                if ($this->upload->do_upload('img')) {
                    $post['img'] = $this->upload->data('file_name');
                    $this->user->createUser($post);
                    if ($this->db->affected_rows() > 0) {
                        $msg = [
                            'success' => 'Data berhasil disimpan'
                        ];
                    }
                } else {
                    $msg = [
                        'failedUpload' => 'Gagal upload foto, gunakan format jpg, png, atau jpeg dan ukuran maksimal 1 mb!'
                    ];
                }
            } else {
                $post['img'] = null;
                $this->user->createUser($post);
                if ($this->db->affected_rows() > 0) {
                    $msg = [
                        'success' => 'Data berhasil disimpan'
                    ];
                }
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }

    public function byid($id)
    {
        $data = $this->user->getdataById($id);
        // print_r($data);
        // die;
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function update()
    {
        $type = 'edit';
        $post = $this->input->post(null, TRUE);
        $this->validation($type, $post);
        if ($this->form_validation->run() == FALSE) {
            $msg = [
                'error' => [
                    'nama' => form_error('nama'),
                    'username' => form_error('username'),
                    'password' => form_error('password'),
                    'konfpass' => form_error('konfpass'),
                ]
            ];
        } else {
            $config['upload_path']   = './assets/img/users';
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['file_name']     = 'imgUser_' . substr(md5(rand()), 0, 7);
            $this->load->library('upload', $config);
            $post = $this->input->post(null, TRUE);
            if (@$_FILES['img']['name'] != null) {
                if ($this->upload->do_upload('img')) {
                    $item = $this->user->getImg($post['id'])->row();
                    if ($item->avatar != null) {
                        $target_file = './assets/img/users/' . $item->avatar;
                        unlink($target_file);
                    }

                    $post['img'] = $this->upload->data('file_name');
                    $this->user->updateUser($post);
                    if ($this->db->affected_rows() > 0) {
                        $msg = [
                            'success' => 'Data berhasil diupdate'
                        ];
                    }
                } else {
                    $msg = [
                        'failedUpload' => 'Gagal upload foto, gunakan format jpg, png, atau jpeg dan ukuran maksimal 1 mb!'
                    ];
                }
            } else {
                $post['img'] = null;
                $this->user->updateUser($post);
                if ($this->db->affected_rows() > 0) {
                    $msg = [
                        'success' => 'Data berhasil diupdate'
                    ];
                }
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }

    public function delete($id)
    {
        $item = $this->user->getImg($id)->row();
        if ($item->avatar != null) {
            $target_file = './assets/img/users/' . $item->avatar;
            unlink($target_file);
        }
        $this->user->delete($id);
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

    private function validation($type, $post = null)
    {
        if ($type == 'add') {
            $this->form_validation->set_rules('nama', 'Nama', 'required');
            $this->form_validation->set_rules('username', 'Username', 'required|is_unique[user.username]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
            $this->form_validation->set_rules('konfpass', 'Konfirmasi Password', 'required|matches[password]');

            $this->form_validation->set_message('required', '%s wajib diisi!');
            $this->form_validation->set_message('min_length', 'Minimal karakter %s 5');
            $this->form_validation->set_message('matches', '%s tidak sesuai dengan password');
            $this->form_validation->set_message('is_unique', '%s sudah dipakai, silahkan diganti dengan yang lain');
        } else if ($type == 'edit') {
            $this->form_validation->set_rules('nama', 'Nama', 'required');
            $this->form_validation->set_rules('username', 'Username', 'required|callback_username_check');
            if ($post['password']) {
                $this->form_validation->set_rules('password', 'Password', 'min_length[5]');
                $this->form_validation->set_rules('konfpass', 'Konfirmasi Password', 'matches[password]');
            }
            if ($post['konfpass']) {
                $this->form_validation->set_rules('konfpass', 'Konfirmasi Password', 'matches[password]');
            }

            $this->form_validation->set_message('required', '%s wajib diisi!');
            $this->form_validation->set_message('min_length', 'Minimal karakter %s 5');
            $this->form_validation->set_message('matches', '%s tidak sesuai dengan password');
            $this->form_validation->set_message('is_unique', '%s sudah dipakai, silahkan diganti dengan yang lain');
        }
    }

    function username_check()
    {
        $post = $this->input->post(null, TRUE);
        $query = $this->db->query("SELECT * FROM user WHERE username = '$post[username]' AND user_id != '$post[id]'");
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('username_check', '%s sudah dipakai, silahkan diganti dengan yang lain');
            return false;
        } else {
            return true;
        }
    }
}
