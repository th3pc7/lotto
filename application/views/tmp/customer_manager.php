<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script> var useScript = "agent_customer"; </script>

<h2>เพิ่มสมาชิก</h2>
<form id="form-agent-add-user">
    <input name="Action" type="text" value="add_user" class="hide">
    <input name="Username" type="text" placeholder="Username" autocomplete="off"> : Username<br>
    <input name="Password" type="password" placeholder="Password"> : Password<br>
    <input name="Name" type="text" placeholder="Name" autocomplete="off"> : Name<br>
    <select name="Usertype">
        <option value="agent">เอเย่น</option>
        <option value="user">ผู้ซื้อ</option>
    </select><br>
    <input name="Credit" type="text" placeholder="Credit" autocomplete="off"> : Credit<br>
    <input name="Percent" type="text" placeholder="Percent" autocomplete="off"> : Percent<br>
    <button type="submit">Save</button> <button type="reset">Reset</button>
</form>

<h2>สมาชิกทั้งหมด <button id="ref-all-customer">รีเฟรช</button></h2>
<div id="paste-tb-customer"> <?php $this->load->view('tmp/table_all_customer', $data); ?> </div>

<h2>สมาชิกและการจ่าย</h2>
<div id="paste-tb-customer-setting"> <?php $this->load->view('tmp/table_all_customer_setting', $data); ?> </div>