<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script> var useScript = false; </script>

<div class="label-head">username</div> : <?php echo $user_info['user']; ?><br>
<div class="label-head">ประเภท</div> : <?php echo ($user_info['class']==='agent') ? 'เอเย่นต์':'ผู้ซื้อ'; ?><br>
<div class="label-head">เครดิต</div> : <?php echo number_format($user_info['credit'], 2, '.', ','); ?><br>
<div class="label-head">ยอดคงค้าง</div> : <?php echo number_format($user_info['balance'], 2, '.', ',').(($user_info['balance']>=0) ? '':' <span style="font-size:10px;color:red;">(ยอดนี้ต้องจ่ายให้เอเย่นต์)</span>'); ?><br>
<div class="label-head">สถานะบัญชี</div> : <?php echo ($user_info['status']==='enabled') ? 'ปกติ':'ถูกปิดการใช้งาน'; ?><br>
<div class="label-head">วันที่สมัคร</div> : <?php echo $user_info['datetime']; ?><br>
<div class="label-head">ยอดพนันงวดนี้</div> : <?php echo number_format($now_betting,2,'.',','); ?><br>
<div class="label-head">ยอดได้เสียงวดนี้</div> : <?php echo number_format($now_win_lose,2,'.',','); ?><br>
<div class="label-head">ยอดพนันงวดที่ผ่านมา</div> : <?php echo number_format($last_betting,2,'.',','); ?><br>
<div class="label-head">ยอดได้เสียงวดที่ผ่านมา</div> : <?php echo number_format($win_lose_last_betting,2,'.',','); ?><br>

<style>
    .label-head{width:160px;}
</style>