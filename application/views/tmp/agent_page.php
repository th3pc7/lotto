<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script> var useScript = false; </script>

<h2>ข้อมูลบัญชี</h2>
<div class="label-head">username</div> : <div class="label-body"><?php echo $user_info['user']; ?></div><br>
<div class="label-head">ประเภท</div> : <div class="label-body"><?php echo ($user_info['class']==='agent') ? 'เอเย่นต์':'ผู้ซื้อ'; ?></div><br>
<div class="label-head">เครดิต</div> : <div class="label-body"><?php echo number_format($user_info['credit'],2,'.',','); ?></div><br>
<!-- <div class="label-head">ยอดได้เสีย</div> : <div class="label-body"><?php echo number_format($user_info['balance'],2,'.',','); ?></div><br> -->
<div class="label-head">สถานะบัญชี</div> : <div class="label-body"><?php echo ($user_info['status']==='enabled') ? 'ปกติ':'ถูกปิดการใช้งาน'; ?></div><br>
<div class="label-head">วันที่สมัคร</div> : <div class="label-body"><?php echo $user_info['datetime']; ?></div><br>