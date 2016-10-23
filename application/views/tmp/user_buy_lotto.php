<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script> var useScript = "user_buy_lotto"; </script>

<div style="margin:16px;background-color:#ffc164;padding:16px;border-radius:8px;width:600px;">
    <h3>เลขอั้น(จ่ายครึ่งราคา)</h3>
    <h4>สองตัว</h4>
    09 15 52  29  89 98  70  07  13  31
    <h4>สามตัว</h4>
    889, 988, 898, 998, 989, 899, 139, 193, 913, 931, 391, 319, 929, 299, 489, 992<br>
    904, 901, 910, 970, 907, 097, 790, 709, 079, 880, 089, 098, 908, 980, 890, 809<br> <br>
    <span style="color:red;">*** หมายเหตุ - เต็ง/โต๊ด/หน้า/หลัง/บน/ล่าง  จ่ายครึ่งทุกกรณีครับ</span>
</div>

<div>
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
