<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script> var useScript = false; </script>

username : <?php echo $user_info['user']; ?><br>
ประเภท : <?php echo ($user_info['class']==='agent') ? 'เอเย่นต์':'ผู้ซื้อ'; ?><br>
เครดิต : <?php echo number_format($user_info['credit'],2,'.',','); ?><br>
ยอดได้เสีย : <?php echo number_format($user_info['balance'],2,'.',','); ?><br>
สถานะบัญชี : <?php echo ($user_info['status']==='enabled') ? 'ปกติ':'ถูกปิดการใช้งาน'; ?><br>
วันที่สมัคร : <?php echo $user_info['datetime']; ?><br>
ยอดพนันงวดนี้ : <?php echo number_format($now_betting,2,'.',','); ?><br>
ยอดได้เสียงวดนี้ : <?php echo number_format($now_win_lose,2,'.',','); ?><br>
ยอดพนันงวดที่ผ่านมา : <?php echo number_format($last_betting,2,'.',','); ?><br>
ยอดได้เสียงวดที่ผ่านมา : <?php echo number_format($win_lose_last_betting,2,'.',','); ?><br>