<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_action {
  public function __construct(){
    $this->CI =& get_instance();
    $this->user_class = $this->get_user_class();
  }

  public function load_page($view_file,$data){
    $this->CI->load->model('user_model');
    if($this->CI->config_model->get_item('online')==='true' || $this->user_class==='admin'){
      $this->CI->load->view($view_file,$data);
      $user_id = $this->CI->session->userdata('user_id');
      if($user_id!==null && $this->CI->session->userdata('forceLogin')===null){
        $this->CI->user_model->update_last_action($this->CI->input->ip_address(), $user_id);
      }
    }
    else{
      echo 'This site is offline.';
    }
  }

  private function get_user_class(){
    $user_class = $this->CI->session->userdata('user_class');
    if($user_class===null){ return 'quest'; }
    return $user_class;
  }

  public function login($user_id, $user_class){
    $this->CI->load->model('user_model');
    $this->CI->session->set_userdata(array(
      'user_id' => $user_id,
      'user_class' => $user_class
    ));
    if($this->CI->session->userdata('forceLogin')===null){
      $this->CI->user_model->update_status_online($user_id,'online');
      $this->CI->user_model->update_last_login($this->CI->input->ip_address(), $user_id);
    }
  }

  public function logout(){
    $this->CI->load->model('user_model');
    $user_id = $this->CI->session->userdata('user_id');
    if($this->CI->session->userdata('forceLogin')===null){
      $this->CI->user_model->update_status_online($user_id,'offline');
      $this->CI->user_model->update_last_action($this->CI->input->ip_address(),$user_id);
    }
    $this->CI->session->sess_destroy();
  }

  public function setForceLogin(){
    $this->CI->session->set_userdata(array(
      'forceLogin' => true
    ));
  }

}
