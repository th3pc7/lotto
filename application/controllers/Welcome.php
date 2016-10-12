<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		if($this->session->userdata('user_id')!==null){
			header('location:'.base_url().$this->session->userdata('user_class').'/');
			die();
		}
		$this->user_action->load_page('login_view',array(
			'tmp'=>'front_view',
			'data'=>null
		));
	}
}
