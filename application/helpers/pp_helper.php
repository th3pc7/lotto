<?php

function c_text($str){
    $str = trim($str);
    if($str===''){ return null; }
    else{ return htmlspecialchars($str); }
}

function c_number($str_number,$point=4){
    if(is_numeric($str_number)){ return round(floatval($str_number),$point); }
    else{ return null; }
}

function check_number_and_type($number, $type_id){
    $CI =& get_instance();
    $CI->load->model('lotto_model');
    if(trim($number)===''||trim($type_id)===''){ return false; }
    elseif($CI->lotto_model->count_lotto_type_id($type_id)===0){ return false; }
    else{
        $length = intval($CI->lotto_model->get_number_length_from_id($type_id));
        if($length===strlen($number)){ return true; }
        else{ return false; }
    }
}