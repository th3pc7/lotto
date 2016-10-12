<?php

class User_model extends CI_Model{
    
        public function __construct(){
            parent::__construct();
        }

        public function append_balance($user_id, $value){
            $this->db->set('balance', 'balance + '.$value, FALSE)
                ->where('id', $user_id)
                ->limit(1)
                ->update('sys_user');
        }

        public function get_all_user_data($user_id){
            return $this->db->where('id',$user_id)->limit(1)->get('sys_user')->row_array();
        }

        public function get_credit_and_balance($user_id){
            return $this->db->select('credit,balance')
                ->where('id', $user_id)
                ->limit(1)
                ->get('sys_user')->row();
        }

        public function load_user_info_by_username($user){
            return $this->db->select('id,user,password,status,class,online,last_action')
                ->where('user',$user)
                ->limit(1)
                ->get('sys_user')->row_array();
        }

        public function load_customer_table_data_from_agent_id($agent_id){
            return ( $this->db->select('sys_user.id id,user,name,class,credit,balance,percent,last_login,sys_user.datetime datetime,round(price-(price*discount/100),4) betting,buy_lotto.status status')
                ->from('sys_user')
                // ->like('sys_user.user_map',','.$agent_id.',')
                // ->not_like('sys_user.user_map',','.$agent_id.',', 'before')
                ->where('main_user_id',$agent_id)
                ->join('buy_lotto','sys_user.id=buy_lotto.user_id','left')
                ->get()->result_array());
        }

        public function load_all_agent_limit_from_agent_id($agent_id){
            return $this->db->select('limit_lotto.id,type_name,number,price')
                ->from('limit_lotto')
                ->join('lotto_type','limit_lotto.type_id=lotto_type.id')
                ->where('limit_lotto.user_id',$agent_id)
                ->get()->result_array();
        }

        public function same_user_name($username){
            return $this->db->where('user',$username)
                ->from('sys_user')
                ->limit(1)
                ->count_all_results();
        }

        public function add_new_user($agent_map, $username, $pass, $name, $class, $credit, $percent, $agent_id){
            $this->db->insert('sys_user',array(
                'main_user_id' => $agent_id,
                'user' => $username,
                'password' => $pass,
                'name' => $name,
                'class' => $class,
                'credit' => $credit,
                'percent' => $percent,
                'datetime' => date('Y-m-d H:i:s')
            ));
            $insert_id = $this->db->insert_id();
            $this->db->where('id', $insert_id)
                ->update('sys_user',array(
                    'user_map' => $agent_map.$insert_id.','
                ));
            return $insert_id;
        }

        public function update_user_credit($user_id, $value){
            if($value===null){ return; }
            $this->db->where('id', $user_id)
                ->limit(1)
                ->update('sys_user',array(
                    'credit' => $value
                ));
        }

        public function update_status_online($user_id, $value){
            $this->db->where('id', $user_id)
                ->limit(1)
                ->update('sys_user', array(
                    'online' => $value
                ));
        }

        public function update_last_login($ip_addr, $user_id){
            $this->db->where('id', $user_id)
                ->limit(1)
                ->update('sys_user', array(
                    'last_login' => date('Y-m-d H:i:s')
                ));
            $this->update_last_action($ip_addr, $user_id);
        }

        public function update_last_action($ip_addr, $user_id=false){
            if($user_id===false){ return; }
            $this->db->where('id', $user_id)
                ->limit(1)
                ->update('sys_user', array(
                    'last_action' => time(),
                    'last_action_ip' => $ip_addr
                ));
        }

        public function get_all_cal_setting_from_user_id($user_id){
            return $this->db->select('lotto_type_id,discount,reward')
                ->where('user_id',$user_id)
                ->get('cal_setting')->result_array();
        }

        public function save_cal_setting($user_id, $lotto_type, $discount, $reward){
            $this->db->insert('cal_setting',array(
                'user_id' => $user_id,
                'lotto_type_id' => $lotto_type,
                'discount' => $discount,
                'reward' => $reward
            ));
        }

        public function get_percent_from_user_id($user_id){
            return $this->db->select('percent')
                ->from('sys_user')
                ->where('id',$user_id)
                ->limit(1)
                ->get()->row()->percent;
        }

        public function get_user_betting($user_id){
            return $this->db->select('SUM(ROUND(price-(price*discount/100),4)) betting',false)
                ->where('status', 'betting')
                ->where('user_id', $user_id)
                ->get('buy_lotto')->row()->betting;
        }

        public function get_user_betting_from_binggo_id($user_id, $binggo_id){
            if($binggo_id===null||$binggo_id===0){ return 0; }
            $data1 =  $this->db->select('SUM(ROUND(price-(price*discount/100),4)) betting',false)
                ->where('binggo_id',intval($binggo_id))
                ->where('status', 'betting')
                ->where('user_id', $user_id)
                ->get('buy_lotto')->row()->betting;
            $data2 =  $this->db->select('SUM(ROUND(price-(price*discount/100),4)) betting',false)
                ->where('binggo_id',intval($binggo_id))
                ->where('status', 'success')
                ->where('user_id', $user_id)
                ->get('stock_lotto')->row()->betting;
            return $data1 + $data2;
        }

        public function get_win_lose_from_binggo_id($user_id, $binggo_id){
            if($binggo_id===null||$binggo_id===0){ return 0; }
            $data1 = $this->db->select('SUM(ROUND(-price+(price*discount/100)+(price*reward*binggo),4)) betting',false)
                ->where('binggo_id',intval($binggo_id))
                ->where('status', 'betting')
                ->where('user_id', $user_id)
                ->get('buy_lotto')->row()->betting;
            $data2 = $this->db->select('SUM(ROUND(-price+(price*discount/100)+(price*reward*binggo),4)) betting',false)
                ->where('binggo_id',intval($binggo_id))
                ->where('status', 'success')
                ->where('user_id', $user_id)
                ->get('stock_lotto')->row()->betting;
            return $data1 + $data2;
        }

        public function load_customer_id_user_name_from_agent_id($agent_id){
            return $this->db->select('id,user,name')
                ->where('main_user_id', $agent_id)
                ->get('sys_user')->result_array();
        }

        public function check_agent($agent_id, $user_id){
            return $this->db->select('class')
                ->where('id',$user_id)
                ->where('main_user_id',$agent_id)
                ->limit(1)
                ->get('sys_user')->result_array();
        }

        public function set_customer_percent($customer_id, $new_percent){
            if($new_percent===null){ return; }
            $this->db->where('id', $customer_id)
                ->update('sys_user',array(
                    'percent' => $new_percent
                ));
            $this->db->like('user_map', ','.$customer_id.',')
                ->where('percent >',$new_percent)
                ->update('sys_user',array(
                    'percent' => $new_percent
                ));
        }

        public function load_discount_reward($customer_id, $lotto_type){
            return $this->db->select('discount,reward')
                ->where('user_id', $customer_id)
                ->where('lotto_type_id', $lotto_type)
                ->limit(1)
                ->get('cal_setting')->row();
        }

        public function update_setting_cal_user($user_id,$type,$filed,$value){
            if($value===null){ return; }
            $this->db->where('user_id', $user_id)
                ->where('lotto_type_id',$type)
                ->limit(1)
                ->update('cal_setting', array(
                    $filed => $value
                ));
        }

}

?>