<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script> var useScript = "clear_gold"; </script>

<h2>เคลียร์เงิน</h2>
<select id="picker-clear-gold">
    <?php if(count($data['date_picker'])===0): ?>
    <option value="">ไม่มี</option>
    
    <?php else: foreach($data['date_picker'] as $picker): ?>
    <option value="<?php echo $picker['id']; ?>"><?php echo $picker['binggo_date']; ?></option>
    <?php endforeach; ?>
    
    <?php endif; ?>
</select>
<div id="paste-table-clear"><?php $this->load->view('tmp/table_clear_gold', $data); ?></div>