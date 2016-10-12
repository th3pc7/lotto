<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agent extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('user_class')!=='agent'){
			header('location:'.base_url());
			die();
		}
	}

	public function actions(){
		$action_name = $this->input->post('Action');
		if($action_name===null){ die(); }
		switch($action_name){
			case 'add_user':
				$this->add_new_user();
				break;
			case 'load_all_customer':
				$this->load_all_customer();
				break;
			case 'add_limit':
				$this->add_new_limit();
				break;
			case 'load_all_limit':
				$this->load_all_limit();
				break;
			case 'load_all_customer_st':
				$this->load_all_customer_st();
				break;
			case 'del-limit':
				$this->del_limit_by_id();
				break;
			case 'loadThisDate':
				$this->user_action->load_page('tmp/table_clear_gold',array(
					'obj_customer'=>$this->load_obj_customer($this->session->userdata('user_id'), $this->input->post('value'))
				));
				break;
			case 'load_data_risk':
				$this->load->model('lotto_model');
				$dataLotto = $this->lotto_model->load_all_forward_to_me($this->session->userdata('user_id'));
				$this->user_action->load_page('tmp/table_risk',array(
					'me_id'=>$this->session->userdata('user_id'),
					'dataLotto'=>$dataLotto
				));
				break;
			case 'setPercent':
				$this->set_customer_percent();
				break;
			case 'setCredit':
				$this->set_customer_credit();
				break;
			case 'setCal':
				$this->setCal();
				break;
			default:
				die();
		}
	}

	public function index(){
		$this->load->model('user_model');
		$this->load->model('lotto_model');
		$this->user_action->load_page('agent_app_view',array(
			'tmp' => 'agent_page',
			'data' => array(
				'user_info' => $this->user_model->get_all_user_data($this->session->userdata('user_id'))
			)
		));
	}

	public function clearGold(){
		$this->load->model('user_model');
		$this->load->model('lotto_model');
		$obj_binggo = $this->lotto_model->get_arr_last_binggo();
		if(count($obj_binggo)>0&&$obj_binggo[0]['success']==='true'){
			$this->user_action->load_page('agent_app_view',array(
				'tmp'=>'clear_gold_page',
				'data'=>array(
					'obj_customer'=>$this->load_obj_customer($this->session->userdata('user_id'), $obj_binggo[0]['id']),
					'date_picker'=>$this->lotto_model->load_list_date_picker()
				)
			));
		}
		else{
			$this->user_action->load_page('agent_app_view',array(
				'tmp'=>'clear_gold_page',
				'data'=>array(
					'obj_customer'=>$this->load_obj_customer($this->session->userdata('user_id')),
					'date_picker'=>$this->lotto_model->load_list_date_picker()
				)
			));
		}
	}

	public function customer(){
		$this->load->model('user_model');
		$this->user_action->load_page('agent_app_view',array(
			'tmp'=>'customer_manager',
			'data'=>array(
				'all_customer'=>$this->user_model->load_customer_table_data_from_agent_id($this->session->userdata('user_id')),
				'all_customer_setting'=>$this->load_all_customer_setting()
			)
		));
	}

	public function limit(){
		$this->load->model('user_model');
		$this->load->model('lotto_model');
		$this->user_action->load_page('agent_app_view',array(
			'tmp'=>'agent_limit',
			'data'=>array(
				'agent_limit'=>$this->user_model->load_all_agent_limit_from_agent_id($this->session->userdata('user_id')),
				'lotto_type'=>$this->lotto_model->load_lotto_type_data()
			)
		));
	}

	public function risk(){
		$this->load->model('lotto_model');
		$dataLotto = $this->lotto_model->load_all_forward_to_me($this->session->userdata('user_id'));
		$this->user_action->load_page('agent_app_view',array(
			'tmp'=>'agent_risk',
			'data'=>array(
				'me_id'=>$this->session->userdata('user_id'),
				'dataLotto'=>$dataLotto
			)
		));
	}

	private function setCal(){
		$this->load->model('user_model');
		$customer_id = $this->input->post('user');
		if(count($this->user_model->check_agent($this->session->userdata('user_id'),$customer_id))===0){ echo 'คุณไม่มีสิทธิในการกระทำนี้'; die(); }
		$field = ($this->input->post('filed')==='rew') ? 'reward':'discount';
		$this->user_model->update_setting_cal_user($this->input->post('user'), $this->input->post('type'), $field, c_number($this->input->post('value')));
		echo 'pass';
	}

	private function load_all_customer_setting(){
		$ret = array();
		$all_customer = $this->user_model->load_customer_id_user_name_from_agent_id($this->session->userdata('user_id'));
		foreach($all_customer as $customer){
			$data_cal1 = $this->user_model->load_discount_reward($customer['id'],'1');
			$data_cal2 = $this->user_model->load_discount_reward($customer['id'],'2');
			$data_cal3 = $this->user_model->load_discount_reward($customer['id'],'3');
			$data_cal4 = $this->user_model->load_discount_reward($customer['id'],'4');
			$data_cal5 = $this->user_model->load_discount_reward($customer['id'],'5');
			$data_cal6 = $this->user_model->load_discount_reward($customer['id'],'6');
			$data_cal7 = $this->user_model->load_discount_reward($customer['id'],'7');
			$data_cal8 = $this->user_model->load_discount_reward($customer['id'],'8');
			array_push($ret,array(
				'id'=>$customer['id'],
				'name'=>$customer['name'],
				'user'=>$customer['user'],
				'3bon_dis'=>$data_cal1->discount,
				'3lang_dis'=>$data_cal2->discount,
				'3tood_dis'=>$data_cal3->discount,
				'2bon_dis'=>$data_cal4->discount,
				'2lang_dis'=>$data_cal5->discount,
				'2tood_dis'=>$data_cal6->discount,
				'1bon_dis'=>$data_cal7->discount,
				'1lang_dis'=>$data_cal8->discount,
				'3bon_rew'=>$data_cal1->reward,
				'3lang_rew'=>$data_cal2->reward,
				'3tood_rew'=>$data_cal3->reward,
				'2bon_rew'=>$data_cal4->reward,
				'2lang_rew'=>$data_cal5->reward,
				'2tood_rew'=>$data_cal6->reward,
				'1bon_rew'=>$data_cal7->reward,
				'1lang_rew'=>$data_cal8->reward
			));
		}
		return $ret;
	}

	private function set_customer_credit(){
		$this->load->model('user_model');
		$customer_id = $this->input->post('customer_id');
		if(count($this->user_model->check_agent($this->session->userdata('user_id'),$customer_id))===0){ echo 'คุณไม่มีสิทธิในการกระทำนี้'; die(); }
		else{
			$new_credit = c_number($this->input->post('credit'));
			$agent_credit = c_number($this->user_model->get_credit_and_balance($this->session->userdata('user_id'))->credit);
			$customer_credit = c_number($this->user_model->get_credit_and_balance($customer_id)->credit);
			if($new_credit - $customer_credit > $agent_credit){ echo 'เครดิตของคุณไม่เพียงพอ'; die(); }
			else{
				$this->user_model->update_user_credit($customer_id, $new_credit);
				$this->user_model->update_user_credit($this->session->userdata('user_id'), $agent_credit-($new_credit-$customer_credit));
				echo 'pass';
			}
		}
	}

	private function set_customer_percent(){
		$this->load->model('user_model');
		$customer_id = $this->input->post('customer_id');
		if(count($this->user_model->check_agent($this->session->userdata('user_id'),$customer_id))===0){ echo 'คุณไม่มีสิทธิในการกระทำนี้'; die(); }
		else{
			$agent_percent = c_number($this->user_model->get_percent_from_user_id($this->session->userdata('user_id')));
			$new_percent = c_number($this->input->post('percent'));
			if($new_percent>$agent_percent || $new_percent<0){ echo 'คุณสามารถตั้งเปอร์เซ็นได้ไม่เกิน '.number_format($agent_percent,2,'.',',').'%'; die(); }
			else{
				$this->user_model->set_customer_percent($customer_id, $new_percent);
				echo 'pass';
			}
		}
	}

	private function load_obj_customer($agent_id, $binggo_id=null){
		$ret = array();
		$this->load->model('user_model');
		$this->load->model('lotto_model');
		$all_data = $this->user_model->load_customer_id_user_name_from_agent_id($agent_id);
		foreach($all_data as $data){
			$obj = array(
				'customer-name' => $data['name'],
				'customer-buy' => 0,
				'customer-discount' => 0,
				'customer-reward' => 0,
				'customer-sum' => 0,
				'agent-accept' => 0,
				'agent-discount' => 0,
				'agent-reward' => 0,
				'agent-sum' => 0,
				'company-accept' => 0,
				'company-discount' => 0,
				'company-reward' => 0,
				'company-sum' => 0
			);
			if($binggo_id===null){ $all_lotto_buy = $this->lotto_model->load_lotto_forward_by_user_id($agent_id, $data['id']); }
			else{ $all_lotto_buy = $this->lotto_model->load_lotto_forward_by_user_id_and_binggo_id($agent_id, $data['id'],$binggo_id); }
			foreach($all_lotto_buy as $lotto_buy){
				$lotto_forward_arr = explode(',',$lotto_buy['forward']);
				$customer_index = array_search('U'.$data['id'], $lotto_forward_arr);
				$agent_index = array_search('U'.$agent_id, $lotto_forward_arr);
				$obj['customer-buy'] = $obj['customer-buy'] - floatval($lotto_forward_arr[$customer_index+1]);
				$obj['customer-discount'] = $obj['customer-discount']+(floatval($lotto_forward_arr[$customer_index+1])*floatval($lotto_forward_arr[$customer_index+2])/100);
				$obj['customer-reward'] = $obj['customer-reward']+(intval($lotto_buy['binggo'])*floatval($lotto_forward_arr[$customer_index+1])*floatval($lotto_forward_arr[$customer_index+3]));
				$obj['company-accept'] = $obj['company-accept'] + floatval($lotto_forward_arr[$agent_index+1]);
				$obj['company-discount'] = $obj['company-discount']-(floatval($lotto_forward_arr[$agent_index+1])*floatval($lotto_forward_arr[$agent_index+2])/100);
				$obj['company-reward'] = $obj['company-reward']-(intval($lotto_buy['binggo'])*floatval($lotto_forward_arr[$agent_index+1])*floatval($lotto_forward_arr[$agent_index+3]));
			}
			$obj['customer-sum'] = $obj['customer-buy'] + $obj['customer-discount'] + $obj['customer-reward'];
			$obj['company-sum'] = $obj['company-accept'] + $obj['company-discount'] + $obj['company-reward'];
			$obj['agent-accept'] = -$obj['customer-buy'] - $obj['company-accept'];
			$obj['agent-discount'] = -$obj['customer-discount'] - $obj['company-discount'];
			$obj['agent-reward'] = -$obj['customer-reward'] - $obj['company-reward'];
			$obj['agent-sum'] = -$obj['customer-sum'] - $obj['company-sum'];
			array_push($ret, $obj);
		}
		return $ret;
	}

	private function load_all_limit(){
		$this->load->model('user_model');
		$this->user_action->load_page('tmp/table_agent_lotto_limit',array(
			'agent_limit'=>$this->user_model->load_all_agent_limit_from_agent_id($this->session->userdata('user_id'))
		));
	}

	private function load_all_customer(){
		$this->load->model('user_model');
		$this->user_action->load_page('tmp/table_all_customer',array(
			'all_customer'=>$this->user_model->load_customer_table_data_from_agent_id($this->session->userdata('user_id'))
		));
	}

	private function load_all_customer_st(){
		$this->load->model('user_model');
		$this->user_action->load_page('tmp/table_all_customer_setting',array(
			'all_customer_setting'=>$this->load_all_customer_setting()
		));
	}

	private function add_new_limit(){
		$this->load->model('lotto_model');
		$type = c_number($this->input->post('type'));
		$number = (c_text($this->input->post('number'))===null) ? '*' : c_text($this->input->post('number'));
		$price = c_number($this->input->post('price'));
		if($type===null||$price===null){ echo 'ใส่ข้อมูลไม่ถูกต้อง'; }
		else{
			if(check_number_and_type($number, $type)===true || $number==='*'){
				if($this->lotto_model->count_this_lotto_limit($this->session->userdata('user_id'), $type, $number)>0){
					echo 'กรุณาลองใหม่ ข้อมูลซ้ำ';
				}
				else{
					$this->lotto_model->save_limit_lotto($this->session->userdata('user_id'), $type, $number, $price);
					echo 'pass';
				}
			}
			else{ echo 'ใส่หมายเลขไม่ตรงกับชนิด'; }
		}
	}

	private function add_new_user(){
		$username = c_text($this->input->post('Username'));
		$pass = c_text($this->input->post('Password'));
		$name = c_text($this->input->post('Name'));
		$userType = c_text($this->input->post('Usertype'));
		$userType = ($userType==='agent') ? 'agent' : 'user';
		$credit = c_number($this->input->post('Credit'));
		$percent = c_number($this->input->post('Percent'));
		if($username===null||$pass===null||$name===null||$credit===null||$percent===null){ echo 'กรุณาใส่ข้อมูลให้ถูกต้อง'; die(); }
		$this->load->model('user_model');
		$agent_data = $this->user_model->get_all_user_data($this->session->userdata('user_id'));
		if($this->user_model->same_user_name($username)>0){ echo 'Username นี้มีคนใช้แล้ว กรุณาตั้งใหม่ค่ะ'; }
		elseif(floatval($agent_data['credit'])<$credit){ echo 'เครดิตคงเหลือของท่านไม่พอ'; }
		elseif(floatval($agent_data['percent'])<$percent){ echo 'ท่านตั้งค่าเปอร์เซ็นไม่ถูกต้อง'; }
		else{
			$new_user_id = $this->user_model->add_new_user($agent_data['user_map'], $username, md5($pass), $name, $userType, $credit, $percent, $agent_data['id']);
			$this->user_model->update_user_credit($agent_data['id'], floatval($agent_data['credit']) - $credit);
			$this->append_cal_setting($this->session->userdata('user_id'), $new_user_id);
			echo 'pass';
		}
	}

	private function append_cal_setting($agent_id, $user_id){
		$this->load->model('user_model');
		$data_cal_agent = $this->user_model->get_all_cal_setting_from_user_id($agent_id);
		foreach($data_cal_agent as $data_cal){
			$this->user_model->save_cal_setting($user_id, $data_cal['lotto_type_id'], $data_cal['discount'], $data_cal['reward']);
		}
	}

	private function del_limit_by_id(){
		$this->load->model('lotto_model');
		$limit_id = c_number($this->input->post('limitID'));
		if($limit_id===null){ echo 'หมายเลขไอดีไม่ถูกต้อง'; }
		else{
			$this->lotto_model->del_limit($this->session->userdata('user_id'), $limit_id);
			echo 'pass';
		}
	}

}
