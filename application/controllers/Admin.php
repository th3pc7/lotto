<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('user_class')!=='admin'){
			header('location:'.base_url());
			die();
		}
	}

	public function actions(){
		$action_name = $this->input->post('Action');
		if($action_name===null){ die(); }
		switch($action_name){
			case 'submitReward':
				$this->update_lotto_reward();
				$this->submit_lotto_reward();
				echo 'pass';
				break;
			case 'newRound':
				$this->open_new_round();
				break;
			case 'closeThisRound':
				$this->load->model('lotto_model');
				$this->config_model->set_item('accept_betting','deny');
				$this->lotto_model->update_lotto_reward($this->lotto_model->get_last_binggo_id(),'close_datetime',date('Y-m-d H:i:s'));
				echo 'pass';
				break;
			case 'openThisRound':
				$this->config_model->set_item('accept_betting','accept');
				echo 'pass';
				break;
			case 'successThisRound':
				$this->load->model('lotto_model');
				$this->config_model->set_item('accept_betting','deny');
				$this->move_table();
				$this->lotto_model->set_last_round_success($this->lotto_model->get_last_binggo_id());
				break;
			default:
				die();
		}
	}

	public function index(){
		$this->user_action->load_page('admin_app_view',array(
			'tmp'=>'admin_page',
			'data'=>null
		));
	}

	public function lotto(){
		$this->load->model('lotto_model');
		$success = $this->lotto_model->get_last_lotto_success();
		$success = ($success===null) ? $success : $success->success;
		$data_open = null;
		if($success==='false'){ $data_open = $this->lotto_model->get_last_lotto_date(); }
		$this->user_action->load_page('admin_app_view',array(
			'tmp'=>'admin_lotto',
			'data'=>array(
				'success' => $success,
				'date_round_lotto' => $data_open,
				'data_reward_now_round' => $this->lotto_model->get_data_reward_this_round($this->lotto_model->get_last_binggo_id())
			)
		));
	}

	private function move_table(){
		$this->load->model('user_model');
		$this->load->model('lotto_model');
		while($this->lotto_model->count_all()>0){
			$lotto_data = $this->lotto_model->load_to_cal_lotto();
			$binggo_id =  $this->lotto_model->get_last_binggo_id();
			foreach($lotto_data as $lotto){
				$all_cal_data = explode(',', $lotto['forward']);
				$lotto_id = str_replace('L','',array_shift($all_cal_data));
				array_shift($all_cal_data); array_shift($all_cal_data); array_shift($all_cal_data);
				if(count($all_cal_data)%4!==0||$lotto['binggo_id']!==$binggo_id||$lotto['status']!=='betting'){
					$this->lotto_model->move_buy_lotto_to_table($lotto_id, 'error_lotto');
				}
				else{
					$binggo_s = intval($lotto['binggo']);
					$old_forward = 0;
					$old_reward = 0;
					$old_discount = 0;
					while(count($all_cal_data)>0){
						$user_id = str_replace('U','',array_shift($all_cal_data));
						$price = c_number(array_shift($all_cal_data));
						$discount = c_number(array_shift($all_cal_data)) * $price / 100;
						$reward = c_number(array_shift($all_cal_data)) * $binggo_s * $price;
						$this->user_model->append_balance($user_id,round($old_forward+$reward+$discount-$price-$old_reward-$old_discount,2));
						$old_forward = $price;
						$old_reward = $reward;
						$old_discount = $discount;
					}
					$this->lotto_model->move_buy_lotto_to_table($lotto_id, 'stock_lotto');
				}
			}
		}
		echo 'pass';
	}

	private function update_lotto_reward(){
		$this->load->model('lotto_model');
		$topLotto = c_text($this->input->post('top3'));
		$bot2 = c_text($this->input->post('bot2'));
		$bot3_1 = c_text($this->input->post('bot3_1'));
		$bot3_2 = c_text($this->input->post('bot3_2'));
		$bot3_3 = c_text($this->input->post('bot3_3'));
		$bot3_4 = c_text($this->input->post('bot3_4'));
		$binggo_id = $this->lotto_model->get_last_binggo_id();
		$this->lotto_model->update_lotto_reward($binggo_id,'q',$topLotto);
		$this->lotto_model->update_lotto_reward($binggo_id,'w',$bot2);
		$this->lotto_model->update_lotto_reward($binggo_id,'e',$bot3_1);
		$this->lotto_model->update_lotto_reward($binggo_id,'r',$bot3_2);
		$this->lotto_model->update_lotto_reward($binggo_id,'t',$bot3_3);
		$this->lotto_model->update_lotto_reward($binggo_id,'y',$bot3_4);
	}

	private function open_new_round(){
		if(c_text($this->input->post('date'))!==null){
			$this->load->model('lotto_model');
			$this->lotto_model->add_new_round_lotto($this->input->post('date'));
			$this->config_model->set_item('accept_betting','accept');
			echo 'pass';
		}
		else{ echo 'วันที่ไม่ถูกต้องค่ะ'; }
	}

	private function submit_lotto_reward(){
		$this->load->model('user_model');
		$this->load->model('lotto_model');
		$last_binggo_id = $this->lotto_model->get_last_binggo_id();
		$this->lotto_model->disabled_fail_lotto($last_binggo_id);
		$data_reward_lotto = $this->lotto_model->get_lotto_reward_resualt($last_binggo_id)[0];
		$lotto_all_type = $this->lotto_model->load_lotto_type();
		foreach($lotto_all_type as $lotto_type){
			$arr_number_rewards = $this->get_check_reward_status($data_reward_lotto, $lotto_type['code_check']);
			$this->lotto_model->update_all_lotto_reward($lotto_type['id'], $arr_number_rewards);
		}
	}

	private function get_check_reward_status($data_reward_lotto, $code_check){
		$ret = array();
		$code_check_all = explode(',', $code_check);
		foreach($code_check_all as $code){
			$number_true = $this->get_resual_number($data_reward_lotto, $code);
			$ret = array_merge($ret, $number_true);
		}
		return $ret;
	}

	private function get_resual_number($obj_number, $code){
		$code = str_split($code);
		$posi = 0;
		$str = '';
		$keep = false;
		foreach($code as $char){
			if($char==='['){ $keep = true; continue; }
			elseif($char===']'){ $keep = false; continue; }
			elseif($char==='='||$char==='!'||$char==='*'){ continue; }
			if($keep===true){ $str = $str.$obj_number->{$char}[$posi]; $posi = $posi + 1; }
			else{ $posi = $posi + 1; }
		}
		if($code[0]==='='){
			return array($str);
		}
		elseif($code[0]==='!'){
			return $this->get_number_fac($str);
		}
		elseif($code[0]==='*'){
			return str_split($str);
		}
		else{ echo 'บอก Programer อัพเดทระบบ'; }
		return array(9999999999);
	}

	private function get_number_fac($number){
		if(strlen($number)===3){
			return array($number[0].$number[1].$number[2],$number[0].$number[2].$number[1],$number[1].$number[2].$number[0],$number[1].$number[0].$number[2],$number[2].$number[0].$number[1],$number[2].$number[1].$number[0]);
		}
		elseif(strlen($number)===2){
			return array($number[0].$number[1],$number[1].$number[0]);
		}
		return array(9999999999);
	}

}
