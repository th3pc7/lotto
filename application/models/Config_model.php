<?php

class Config_model extends CI_Model{
    
        public function __construct(){
            parent::__construct();
        }

        public function get_item($item_key){
            $data = $this->db->select('value')
                ->where('key',$item_key)
                ->limit(1)
                ->get('sys_setting')->row();
            return $data->value;
        }

        public function set_item($key, $value){
            $this->db->where('key', $key)
                ->limit(1)
                ->update('sys_setting', array(
                    'value' => $value
                ));
        }

}

?>