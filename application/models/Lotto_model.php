<?php

class Lotto_model extends CI_Model{
    
        public function __construct(){
            parent::__construct();
        }

        public function disabled_fail_lotto($binggo_id){
            $this->db->query('INSERT INTO error_lotto SELECT * FROM buy_lotto WHERE buy_lotto.binggo_id != \''.$binggo_id.'\'');
            $this->db->query('DELETE FROM buy_lotto WHERE buy_lotto.binggo_id != \''.$binggo_id.'\'');
        }

        public function move_buy_lotto_to_table($lotto_id, $table_name){
            $this->db->where('id', $lotto_id)
                ->limit(1)
                ->update('buy_lotto', array(
                    'status' => 'success'
                ));
            $this->db->query('INSERT INTO '.$table_name.' SELECT * FROM buy_lotto WHERE buy_lotto.id = \''.$lotto_id.'\' LIMIT 1');
            $this->db->query('DELETE FROM buy_lotto WHERE buy_lotto.id = \''.$lotto_id.'\' LIMIT 1');
        }

        public function load_to_cal_lotto(){
            return $this->db->select('binggo_id,binggo,forward,status')
                // ->where('status','betting')
                ->limit(100)
                ->get('buy_lotto')->result_array();
        }

        public function count_all(){
            return $this->db->count_all('buy_lotto');
        }

        public function load_bill_data($binggo_id,$user_id){
            return $this->db->select('id,note,datetime')
                ->where('binggo_id',$binggo_id)
                ->where('user_id',$user_id)
                ->order_by('id','desc')
                ->get('bill_lotto')->result_array();
        }

        public function load_lotto_with_bill_id($bill_id){
            $data1 = $this->db->select('number,type_name,price,discount,reward,binggo')
                ->from('buy_lotto')
                ->join('lotto_type','lotto_type.id=buy_lotto.type_id','left')
                ->where('bill_id',$bill_id)
                ->where('status','betting')
                ->get()->result_array();
            $data2 = $this->db->select('number,type_name,price,discount,reward,binggo')
                ->from('stock_lotto')
                ->join('lotto_type','lotto_type.id=stock_lotto.type_id')
                ->where('bill_id',$bill_id)
                ->get()->result_array();
            return array_merge($data1,$data2);
        }

        public function add_new_round_lotto($date){
            $this->db->insert('binggo',array(
                'binggo_date' => $date,
                'open_datetime' => date('Y-m-d H:i:s')
            ));
        }

        public function load_lotto_type_data(){
            return $this->db->select('id,type_name')
                ->get('lotto_type')->result_array();
        }

        public function get_number_length_from_id($type_id){
            return $this->db->select('length')
                ->where('id',$type_id)
                ->limit(1)
                ->get('lotto_type')->row()->length;
        }

        public function count_lotto_type_id($type_id){
            return $this->db->where('id',$type_id)
                ->from('lotto_type')
                ->limit(1)
                ->count_all_results();
        }

        public function count_this_lotto_limit($user_id, $type_id, $number){
            return $this->db->where('type_id',$type_id)
                ->where('user_id',$user_id)
                ->where('number',$number)
                ->from('limit_lotto')
                ->limit(1)
                ->count_all_results();
        }

        public function save_limit_lotto($user_id, $type, $number, $price){
            $this->db->insert('limit_lotto',array(
                'type_id' => $type,
                'number' => $number,
                'user_id' => $user_id,
                'price' => $price
            ));
        }

        public function del_limit($user_id, $limit_id){
            $this->db->limit(1)->delete('limit_lotto',array(
                'id'=>$limit_id,
                'user_id'=>$user_id
            ));
        }

        public function add_new_bill($user_id, $binggo_id, $note, $datetime){
            $this->db->insert('bill_lotto',array(
                'user_id' => $user_id,
                'binggo_id' => $binggo_id,
                'note' => $note,
                'datetime' => $datetime
            ));
            return $this->db->insert_id();
        }

        public function add_new_lotto($user_id, $number, $type_id, $bill_id, $price, $discount, $reward, $binggo_id, $forward, $datetime){
            $this->db->insert('buy_lotto',array(
                'user_id' => $user_id,
                'number' => $number,
                'type_id' => $type_id,
                'bill_id' => $bill_id,
                'price' => $price,
                'discount' => $discount,
                'reward' => $reward,
                'binggo_id' => $binggo_id,
                'datetime' => $datetime,
            ));
            $ins_id = $this->db->insert_id();
            $this->db->where('id', $ins_id)
                ->update('buy_lotto',array(
                    'forward' => 'L'.$ins_id.','.$number.','.$type_id.','.$bill_id.',U'.$user_id.','.$price.','.$discount.','.$reward.$forward
                ));
        }

        public function get_data_reward_this_round($id){
            return $this->db->select('q,w,e,r,t,y')
                ->where('id',$id)
                ->from('binggo')
                ->limit(1)
                ->get()->row();
        }

        public function get_last_binggo_id(){
            if($this->db->count_all('binggo')===0){ return null; }
            return $this->db->select('id')
                ->from('binggo')
                // ->where(array('close_datetime' => null ))
                ->limit(1)
                ->order_by('id', 'desc')
                ->get()->row()->id;
        }

        public function get_last_binggo_id_success(){
            if($this->db->count_all('binggo')===0){ return null; }
            return $this->db->select('id')
                ->from('binggo')
                ->where('success','true')
                // ->where(array('close_datetime' => null ))
                ->limit(1)
                ->order_by('id', 'desc')
                ->get()->row()->id;
        }

        public function get_last_lotto_date(){
            return $this->db->select('binggo_date')
                ->from('binggo')
                ->limit(1)
                ->order_by('id', 'desc')
                ->get()->row()->binggo_date;
        }

        public function get_last_lotto_success(){
            return $this->db->select('success')
                ->from('binggo')
                ->limit(1)
                ->order_by('id', 'desc')
                ->get()->row();
        }

        public function set_last_round_success($id){
            $this->db->where('id', $id)
                ->limit(1)
                ->update('binggo', array(
                    'success' => 'true'
                ));
        }

        public function update_lotto_reward($binggo_id,$filed,$numbers){
            $this->db->where('id', $binggo_id)
                ->limit(1)
                ->update('binggo', array(
                    $filed => $numbers
                ));
        }

        public function get_my_limit_setting($user_id, $type, $numbers){
            return $this->db->select('price,number')
                ->where('user_id', $user_id)
                ->where('type_id', $type)
                ->where_in('number',array($numbers,'*'))
                ->limit(2)
                ->order_by('price','asc')
                ->get('limit_lotto')->result_array();
        }

        public function get_cal_setting_user_and_type_lotto($user_id, $type){
            return $this->db->select('discount,reward')
                ->where('user_id', $user_id)
                ->where('lotto_type_id', $type)
                ->limit(1)
                ->get('cal_setting')->row();
        }

        public function load_all_forward_to_me($user_id){
            return $this->db->select('forward')
                ->where('status','betting')
                ->like('forward',',U'.$user_id.',')
                ->get('buy_lotto')->result_array();
        }

        public function load_lotto_forward_by_user_code_and_type($user_id, $type_id){
            return $this->db->select('forward')
                ->where('status', 'betting')
                ->where('type_id', $type_id)
                ->like('forward',',U'.$user_id.',')
                ->get('buy_lotto')->result_array();
        }

        public function load_lotto_forward_by_user_code_and_type_and_number($user_id, $type_id, $numbers){
            return $this->db->select('forward')
                ->where('status', 'betting')
                ->where('type_id', $type_id)
                ->where('number', $numbers)
                ->like('forward',',U'.$user_id.',')
                ->get('buy_lotto')->result_array();
        }

        public function del_all_bill_and_lotto($bill_id, $user_id){
            $this->db->limit(1)->delete('bill_lotto',array(
                'id'=>$bill_id,
                'user_id'=>$user_id
            ));
            $this->db->delete('buy_lotto',array(
                'user_id'=>$user_id,
                'bill_id'=>$bill_id
            ));
        }

        public function load_lotto_forward_by_user_id($agent_id, $user_id){
            return $this->db->select('forward,binggo')
                ->where('status', 'betting')
                ->like('forward',',U'.$agent_id.',')
                ->like('forward',',U'.$user_id.',')
                ->get('buy_lotto')->result_array();
        }

        public function load_lotto_forward_by_user_id_and_binggo_id($agent_id,$user_id,$binggo_id){
            $data = $this->db->select('forward,binggo')
                ->where('status', 'betting')
                ->where('binggo_id',$binggo_id)
                ->like('forward',',U'.$agent_id.',')
                ->like('forward',',U'.$user_id.',')
                ->get('buy_lotto')->result_array();
            $data2 = $this->db->select('forward,binggo')
                ->where('status', 'success')
                ->where('binggo_id',$binggo_id)
                ->like('forward',',U'.$agent_id.',')
                ->like('forward',',U'.$user_id.',')
                ->get('stock_lotto')->result_array();
            $data = array_merge($data,$data2);
            return $data;
        }

        public function get_lotto_reward_resualt($id){
            return $this->db->select('q,w,e,r,t,y')
                ->where('id',$id)
                ->limit(1)
                ->get('binggo')->result();
        }

        public function load_lotto_type(){
            return $this->db->select('id,code_check')
                ->get('lotto_type')->result_array();
        }

        public function update_all_lotto_reward($lotto_id, $arr_number_rewards){
            $this->db->where('type_id', $lotto_id)
                ->where_in('number', $arr_number_rewards)
                ->update('buy_lotto', array(
                    'binggo' => 1
                ));
            $this->update_all_lotto_no_reward($lotto_id, $arr_number_rewards);
        }

        public function get_arr_last_binggo(){
            return $this->db->select('id,success')
                ->from('binggo')
                ->limit(1)
                ->order_by('id', 'desc')
                ->get()->result_array();
        }

        private function update_all_lotto_no_reward($lotto_id, $arr_number_rewards){
            $this->db->where('type_id', $lotto_id)
                ->where_not_in('number', $arr_number_rewards)
                ->update('buy_lotto', array(
                    'binggo' => 0
                ));
        }

        public function load_list_date_picker(){
            return $this->db->select('id,binggo_date')
                ->from('binggo')
                ->order_by('id', 'desc')
                ->get()->result_array();
        }

}

?>