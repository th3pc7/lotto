<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script> var useScript = "admin_lotto"; </script>

<div id="step-1" class="<?php echo ($success==='false') ? '':'hide'; ?>">
  <h2>จัดการ - หวยงวดวันที่ <span id="span-date"><?php echo ($date_round_lotto===null) ? '':$date_round_lotto; ?></span></h2>
  <form id="admit-lotto-now">
    <input name="Action" type="text" value="submitReward" class="hide">
    <input name="top3" type="text" placeholder="รางวัลที่หนึ่ง" value="<?php echo ($data_reward_now_round===null||$data_reward_now_round->q===null) ? '':$data_reward_now_round->q; ?>"><br>
    <input name="bot2" type="text" placeholder="2 ตัวล่าง" value="<?php echo ($data_reward_now_round===null||$data_reward_now_round->w===null) ? '':$data_reward_now_round->w; ?>"><br>
    <input name="bot3_1" type="text" placeholder="3 ตัวล่าง" value="<?php echo ($data_reward_now_round===null||$data_reward_now_round->e===null) ? '':$data_reward_now_round->e; ?>"><br>
    <input name="bot3_2" type="text" placeholder="3 ตัวล่าง" value="<?php echo ($data_reward_now_round===null||$data_reward_now_round->r===null) ? '':$data_reward_now_round->r; ?>"><br>
    <input name="bot3_3" type="text" placeholder="3 ตัวล่าง" value="<?php echo ($data_reward_now_round===null||$data_reward_now_round->t===null) ? '':$data_reward_now_round->t; ?>"><br>
    <input name="bot3_4" type="text" placeholder="3 ตัวล่าง" value="<?php echo ($data_reward_now_round===null||$data_reward_now_round->y===null) ? '':$data_reward_now_round->y; ?>"><br>
    <button type="submit">อัพเดทผลหวย</button>
  </form>
  <button id="btn-close-this-round-lotto">ปิดรับหวย</button>
  <button id="btn-open-this-round-lotto">เปิดรับหวย</button>
  <button id="btn-success-this-round-lotto">เสร็จสิ้น หวยงวดนี้</button>
</div>

<div id="step-2" class="<?php echo ($success==='false') ? 'hide':''; ?>">
  <h2>เปิด - รับหวยงวดใหม่</h2>
  <form id="new_lotto_round">
    <input id="date-new-round" name="date" type="date">
    <button type="submit">Save</button> <button type="reset">Reset</button>
  </form>
</div>
