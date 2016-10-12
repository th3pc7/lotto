<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script> var useScript = "risk"; </script>

<h2>ความเสี่ยง</h2>
<input id="inp-cal" type="text" placeholder="จำนวนที่ต้องการจะเก็บ"><button onclick="fn_btn_click();">Cal</button> <button onclick="load_new_data();">Update data</button>
<div id="paste-tabl-risk"> <?php $this->load->view('tmp/table_risk', $data); ?> </div>