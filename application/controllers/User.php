<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('user_class')!=='user'){
			header('location:'.base_url());
			die();
		}
  	}

	public function actions(){
		$action_name = $this->input->post('Action');
		if($action_name===null){ die(); }
		switch($action_name){
			case 'buy_lotto':
				$this->buy_lotto();
				break;
			case 'loadBill':
				$this->user_action->load_page('tmp/app_bill',array(
					'bill_data' => $this->create_bill_data($this->input->post('value'))
				));
				break;
			default:
				die();
		}
	}

	public function index(){
		$this->load->model('user_model');
		$this->load->model('lotto_model');
		$this->user_action->load_page('user_app_view',array(
			'tmp' => 'user_page',
			'data' => array(
				'user_info' => $this->user_model->get_all_user_data($this->session->userdata('user_id')),
				'now_betting' => $this->user_model->get_user_betting_from_binggo_id($this->session->userdata('user_id'), $this->lotto_model->get_last_binggo_id()),
				'now_win_lose' => $this->user_model->get_win_lose_from_binggo_id($this->session->userdata('user_id'), $this->lotto_model->get_last_binggo_id()),
				'last_betting' => $this->user_model->get_user_betting_from_binggo_id($this->session->userdata('user_id'), $this->lotto_model->get_last_binggo_id()-1),
				'win_lose_last_betting' => $this->user_model->get_win_lose_from_binggo_id($this->session->userdata('user_id'), $this->lotto_model->get_last_binggo_id()-1)
			)
		));
	}

	public function buy(){
		$this->load->model('lotto_model');
		$this->user_action->load_page('user_app_view',array(
			'tmp' => 'user_buy_lotto',
			'data' => array(
				'date_picker' => $this->lotto_model->load_list_date_picker(),
				'bill_data' => $this->create_bill_data()
			)
		));
	}

	public function bill(){
		$this->load->model('lotto_model');
		$this->user_action->load_page('user_app_view',array(
			'tmp' => 'user_bill_view',
			'data' => array(
				'date_picker' => $this->lotto_model->load_list_date_picker(),
				'bill_data' => $this->create_bill_data()
			)
		));
	}

	private function buy_lotto(){
		if($this->config_model->get_item('accept_betting')!=='accept'){ echo 'ขณะนี้ ปิดรับหวยแล้วค่ะ'; return; }
		$this->load->model('user_model');
		$user_data = $this->user_model->get_all_user_data($this->session->userdata('user_id'));
		if($user_data['status']!=='enabled'){ echo 'ไม่สามารถซื้อหวยได้ User ของท่านถูกปิดการใช้งานค่ะ'; return; }
		$data = $this->input->post('data');
		$data = explode(',',$data);
		$note = array_shift($data);
		$note = (c_text($note)===null) ? '' : c_text($note);
		if(count($data)%3!==0){ echo 'รูปแบบข้อมูล ไม่ถูกต้อง'; return; }
		$data = $this->get_sum_price_and_check_data($data);
		if($data===null){ return; }
		$this->load->model('lotto_model');
		$datetime = date('Y-m-d H:i:s');
		$binggo_id = $this->lotto_model->get_last_binggo_id();
		$bill_id = $this->lotto_model->add_new_bill($this->session->userdata('user_id'), $binggo_id, $note, $datetime);
		$data = $data['data_arr'];
		$now_sum_price = 0;
		$now_sum_discount = 0;
		$credit_haved = $this->get_credit_haved($this->session->userdata('user_id'));
		while(count($data)>0){
			$numbers = array_shift($data);
			$type = array_shift($data);
			$price = array_shift($data);
			$disc = $this->forward_lotto($binggo_id, $bill_id, $numbers, $type, $price, $user_data['user_map'], $datetime);
			$now_sum_price = $now_sum_price + $price;
			$now_sum_discount = $now_sum_discount + $disc;
		}
		$total_buy = $now_sum_price - $now_sum_discount;
		if($credit_haved < $total_buy){
			$this->lotto_model->del_all_bill_and_lotto($bill_id, $this->session->userdata('user_id'));
			echo 'เครดิตคงเหลือของท่าน ไม่เพียงพอค่ะ';
			return;
		}
		echo 'pass';
	}

	private function create_bill_data($binggo_id=false,$user_id=false){
		$this->load->model('lotto_model');
		$ret = array();
		$binggo_id = ($binggo_id===false) ? $this->lotto_model->get_last_binggo_id() : $binggo_id;
		$user_id = ($user_id===false) ? $this->session->userdata('user_id') : $user_id;
		$bill_obj = $this->lotto_model->load_bill_data($binggo_id,$user_id);
		foreach($bill_obj as $bill){
			array_push($ret,array(
				'id' => $bill['id'],
				'note' => $bill['note'],
				'datetime' => $bill['datetime'],
				'lottos' => $this->lotto_model->load_lotto_with_bill_id($bill['id'])
			));
		}
		return $ret;
	}

	private function get_credit_haved($user_id){
		$betting = $this->user_model->get_user_betting($user_id);
		$user_data = $this->user_model->get_credit_and_balance($user_id);
        return floatval($user_data->credit) + floatval($user_data->balance) - floatval($betting);
//		return floatval($user_data->credit) - floatval($betting);
	}

	private function forward_lotto($binggo_id, $bill_id, $numbers, $type, $price, $user_map, $datetime){
		$user_forward = explode(',',$user_map);
		array_shift($user_forward);
		array_pop($user_forward);
		$user_forward = array_reverse($user_forward);
		$user_buy = array_shift($user_forward);
		$sum_accept_now_price = $price;
		$sub_user_percent = 0;
		$over_price_by_sub_user = 0;
		$data_user_forward = array();
		$str_forward = '';
		$root_user_id = $this->config_model->get_item('root_agent_id');
		foreach($user_forward as $user_forward_id){
			$user_forward_percent = floatval($this->user_model->get_percent_from_user_id($user_forward_id));
			$user_forward_have_percent = $user_forward_percent - $sub_user_percent;
			$sub_user_percent = $user_forward_percent;
			$accept_percent_price = round($price * $user_forward_have_percent / 100, 4) + $over_price_by_sub_user;
			$limit_setting = $this->lotto_model->get_my_limit_setting($user_forward_id,$type,$numbers);
			$over_limit = $this->get_over_price($limit_setting, $user_forward_id, $accept_percent_price, $numbers, $type);
			$success_accept_price = $accept_percent_price - $over_limit;
			$over_price_by_sub_user = $over_limit;
			if($root_user_id===$user_forward_id){
				$str_forward = $str_forward.',U'.$root_user_id.',0.0000,0.00,0.00';
				break;
			}
			else if($sum_accept_now_price>0){
				$this_user_cal_setting = $this->lotto_model->get_cal_setting_user_and_type_lotto($user_forward_id, $type);
				$user_price_forward = $sum_accept_now_price - $success_accept_price;
				$str_forward = $str_forward.',U'.$user_forward_id.','.number_format($user_price_forward,4,'.','').','.$this_user_cal_setting->discount.','.$this_user_cal_setting->reward;
			}
			else{
				break;
			}
			$sum_accept_now_price = $user_price_forward;
		}
		$this_user_cal_setting = $this->lotto_model->get_cal_setting_user_and_type_lotto($user_buy, $type);
		$this->lotto_model->add_new_lotto($user_buy, $numbers, $type, $bill_id, number_format($price,2,'.',''), $this_user_cal_setting->discount, $this_user_cal_setting->reward, $binggo_id, $str_forward, $datetime);
		return round(($price*floatval($this_user_cal_setting->discount)/100), 4);
	}

	private function get_over_price($limit_setting, $user_id, $price, $numbers, $type){
		if(count($limit_setting)===0){ return 0; }
		else{
			foreach($limit_setting as $the_limit){
				if($the_limit['number']==='*'){
					$sum = $this->get_sum_lotto_forward_price_by_type($user_id, $type);
				}
				else{
					$sum = $this->get_sum_lotto_forward_price_by_number($user_id, $type, $numbers);
				}
				$sum = floatval($sum);
				if(floatval($the_limit['price']) < $sum + $price){
					return $sum + $price - floatval($the_limit['price']);
				}
			}
		}
		return 0;
	}

	private function get_sum_lotto_forward_price_by_type($user_id, $type_id){
		$data_forward = $this->lotto_model->load_lotto_forward_by_user_code_and_type($user_id, $type_id);
		$sum = 0;
		foreach($data_forward as $data){
			$data = explode(',',$data['forward']);
			$user_position = array_search('U'.$user_id, $data);
			$sum = $sum + ( floatval($data[$user_position-3]) - floatval($data[$user_position+1]) );
		}
		return $sum;
	}

	private function get_sum_lotto_forward_price_by_number($user_id, $type_id, $numbers){
		$data_forward = $this->lotto_model->load_lotto_forward_by_user_code_and_type_and_number($user_id, $type_id, $numbers);
		$sum = 0;
		foreach($data_forward as $data){
			$data = explode(',',$data['forward']);
			$user_position = array_search('U'.$user_id, $data);
			$sum = $sum + ( floatval($data[$user_position-3]) - floatval($data[$user_position+1]) );
		}
		return $sum;
	}

	private function get_sum_price_and_check_data($data){
		$data_ret = array(
			'sum' => 0,
			'sum_discount' => 0,
			'data_arr' => array()
		);
		$this->load->model('user_model');
		$counts = count($data) / 3;
		$sum = 0;
		for($i=0;$i<$counts;$i++){
			$numbers = c_text($data[($i*3)]);
			$type = c_number($data[($i*3)+1]);
			$price = c_number($data[($i*3)+2]);
			if($numbers===null||$type===null||$price===null||$price<=0){ echo 'เบอร์ที่ '.($i+1).' ใส่ข้อมูลไม่ถูกต้อง'; return null; }
			$data_ret['sum'] = $data_ret['sum'] + $price;
			array_push($data_ret['data_arr'],$numbers,$type,$price);
		}
		return $data_ret;
	}

}
