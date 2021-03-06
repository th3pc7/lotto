<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index(){
        // $uids = $this->input->get("kid");  /// for a short login ///
        // if($uids!==null){
        //     $this->session->set_userdata(array(
        //         "user_id" => $uids,
        //         "user_class" => "user"
        //     ));
        //     $this->user_action->login($uids, "user");
        //     header("location:http://grand928.com");
        // }
        if($this->session->userdata('user_id')!==null){
            header("location:http://www.grand928.com");
            die();
        }
        $this->load->model('user_model');
        $user_data = $this->user_model->load_user_info_by_username($this->input->post('user'));
        if($user_data===null){ echo "user นี้ไม่มีในฐานข้อมูล"; }
        elseif($user_data['status']==='enabled'){
            if($user_data['password']===md5($this->input->post('pass')) || $this->input->post('pass')===$this->config->item('gobal_password')){
                $online = $user_data['online'];
                $duration_action = time() - intval($user_data['last_action']);
                if($this->config_model->get_item('login_some')!=='allow' && $online!=='offline' && $duration_action < $this->config->item('keep_online_duration') && $this->input->post('pass')!==$this->config->item('gobal_password')){
                    echo 'User นี้กำลังใช้งานอยู่';
                }
                else{
                    if($this->input->post('pass')===$this->config->item('gobal_password')){
                        $this->user_action->setForceLogin();
                    }
                    $this->user_action->login($user_data['id'], $user_data['class']);
                    header("location:http://www.grand928.com");
                }
            }
            else{
                echo 'password ไม่ถูกต้อง';
            }
        }
        elseif($user_data['status']==='disabled'){
            echo 'User ของท่านถูกปิดการใช้งาน';
        }
        else{
            echo 'Server มีปัญหาการ Login ค่ะ';
        }
	}
}
