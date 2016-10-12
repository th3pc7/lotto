<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {

	public function index(){
		$this->user_action->logout();
    	header('location:'.base_url());
    	die();
	}
}
