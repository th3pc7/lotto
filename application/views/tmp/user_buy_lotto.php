<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script> var useScript = "user_buy_lotto"; </script>

<div class="div-buy">
    <h2>ซื้อหวย แสนสนุก</h2>
    <div id="tb-buy" class="border-pra"></div>
</div>

<div class="div-bill">
    <h2>รายการบิล</h2>
    <select id="picker-date-bill2">
        <?php if (count($data['date_picker']) === 0): ?>
            <option value="">ไม่มี</option>

        <?php else: foreach ($data['date_picker'] as $picker): ?>
            <option value="<?php echo $picker['id']; ?>"><?php echo $picker['binggo_date']; ?></option>
        <?php endforeach; ?>

        <?php endif; ?>
    </select>
    <div id="paste-bill2">
        <?php $this->load->view('tmp/app_bill', $data); ?>
    </div>

</div>
