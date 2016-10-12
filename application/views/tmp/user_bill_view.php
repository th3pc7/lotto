<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script> var useScript = "user_bill"; </script>

<h2>บิลที่ซื้อ</h2>
<select id="picker-date-bill" class="input-control select">
    <?php if(count($data['date_picker'])===0): ?>
    <option value="">ไม่มี</option>
    
    <?php else: foreach($data['date_picker'] as $picker): ?>
    <option value="<?php echo $picker['id']; ?>"><?php echo $picker['binggo_date']; ?></option>
    <?php endforeach; ?>
    
    <?php endif; ?>
</select>

<div id="paste-bill">
    <?php $this->load->view('tmp/app_bill', $data); ?>
</div>