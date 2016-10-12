<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<table>
    <thead>
        <tr>
            <td rowspan=2>ชื่อลูกค้า</td>
            <td colspan=2>3 ตัวบน</td>
            <td colspan=2>3 ตัวล่าง</td>
            <td colspan=2>3 ตัวโต๊ด</td>
            <td colspan=2>2 ตัวบน</td>
            <td colspan=2>2 ตัวล่าง</td>
            <td colspan=2>2 ตัวโต๊ด</td>
            <td colspan=2>1 ตัวบน</td>
            <td colspan=2>1 ตัวล่าง</td>
        </tr>
        <tr>
            <td>ส่วนลด</td>
            <td>จ่าย</td>
            <td>ส่วนลด</td>
            <td>จ่าย</td>
            <td>ส่วนลด</td>
            <td>จ่าย</td>
            <td>ส่วนลด</td>
            <td>จ่าย</td>
            <td>ส่วนลด</td>
            <td>จ่าย</td>
            <td>ส่วนลด</td>
            <td>จ่าย</td>
            <td>ส่วนลด</td>
            <td>จ่าย</td>
            <td>ส่วนลด</td>
            <td>จ่าย</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach($all_customer_setting as $customer_setting): ?>
        <tr id="bind-set-cal">
            <td><?php echo $customer_setting['user'].'('.$customer_setting['name'].')'; ?></td>
            <td class="text-right"><a href="#" data-type="1" data-id="<?php echo $customer_setting['id']; ?>" data-mode="dis"><?php echo $customer_setting['3bon_dis']; ?></a></td>
            <td class="text-right"><a href="#" data-type="1" data-id="<?php echo $customer_setting['id']; ?>" data-mode="rew"><?php echo $customer_setting['3bon_rew']; ?></a></td>
            <td class="text-right"><a href="#" data-type="2" data-id="<?php echo $customer_setting['id']; ?>" data-mode="dis"><?php echo $customer_setting['3lang_dis']; ?></a></td>
            <td class="text-right"><a href="#" data-type="2" data-id="<?php echo $customer_setting['id']; ?>" data-mode="rew"><?php echo $customer_setting['3lang_rew']; ?></a></td>
            <td class="text-right"><a href="#" data-type="3" data-id="<?php echo $customer_setting['id']; ?>" data-mode="dis"><?php echo $customer_setting['3tood_dis']; ?></a></td>
            <td class="text-right"><a href="#" data-type="3" data-id="<?php echo $customer_setting['id']; ?>" data-mode="rew"><?php echo $customer_setting['3tood_rew']; ?></a></td>
            <td class="text-right"><a href="#" data-type="4" data-id="<?php echo $customer_setting['id']; ?>" data-mode="dis"><?php echo $customer_setting['2bon_dis']; ?></a></td>
            <td class="text-right"><a href="#" data-type="4" data-id="<?php echo $customer_setting['id']; ?>" data-mode="rew"><?php echo $customer_setting['2bon_rew']; ?></a></td>
            <td class="text-right"><a href="#" data-type="5" data-id="<?php echo $customer_setting['id']; ?>" data-mode="dis"><?php echo $customer_setting['2lang_dis']; ?></a></td>
            <td class="text-right"><a href="#" data-type="5" data-id="<?php echo $customer_setting['id']; ?>" data-mode="rew"><?php echo $customer_setting['2lang_rew']; ?></a></td>
            <td class="text-right"><a href="#" data-type="6" data-id="<?php echo $customer_setting['id']; ?>" data-mode="dis"><?php echo $customer_setting['2tood_dis']; ?></a></td>
            <td class="text-right"><a href="#" data-type="6" data-id="<?php echo $customer_setting['id']; ?>" data-mode="rew"><?php echo $customer_setting['2tood_rew']; ?></a></td>
            <td class="text-right"><a href="#" data-type="7" data-id="<?php echo $customer_setting['id']; ?>" data-mode="dis"><?php echo $customer_setting['1bon_dis']; ?></a></td>
            <td class="text-right"><a href="#" data-type="7" data-id="<?php echo $customer_setting['id']; ?>" data-mode="rew"><?php echo $customer_setting['1bon_rew']; ?></a></td>
            <td class="text-right"><a href="#" data-type="8" data-id="<?php echo $customer_setting['id']; ?>" data-mode="dis"><?php echo $customer_setting['1lang_dis']; ?></a></td>
            <td class="text-right"><a href="#" data-type="8" data-id="<?php echo $customer_setting['id']; ?>" data-mode="rew"><?php echo $customer_setting['1lang_rew']; ?></a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>