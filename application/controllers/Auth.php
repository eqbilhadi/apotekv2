<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function login()
    {
        check_already_login();
        $this->load->view('login');
    }

    public function process()
    {
        $post = $this->input->post(null, TRUE);
        $this->load->model('User_m');
        $query = $this->User_m->login($post);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $params = array(
                'userid' => $row->user_id,
                'level' => $row->level
            );
            $this->session->set_userdata($params);
            $msg = [
                'success' => 'Login Berhasil'
            ];
        } else {
            $msg = [
                'failed' => 'Login Gagal'
            ];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($msg));
    }

    public function logout()
    {
        $params = array('userid', 'level');
        $this->session->unset_userdata($params);
        redirect('auth/login');
    }
}
