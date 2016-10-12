<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<?php foreach ($bill_data as $bill): ?>
<table>
    <thead>
        <tr>
            <td colspan="5">
                <?php echo '#' . $bill['id'] . ' - ' . (($bill['note']==='') ? 'ไม่มีหมายเหตุบิล' : $bill['note']); ?>
            </td>
        </tr>
        <tr>
            <td>ประเภท</td>
            <td>หมายเลข</td>
            <td>ราคา</td>
            <td>ส่วนลด</td>
            <td>เงินรางวัล</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $sum = 0;
            foreach ($bill['lottos'] as $lotto):
            $price = floatval($lotto['price']);
            $discount = $price * floatval($lotto['discount']) / 100;
            $reward = intval($lotto['binggo']) * floatval($lotto['reward']) * $price;
            $sum = $sum + $discount + $reward - $price;
        ?>
        <tr>
            <td><?php echo $lotto['type_name']; ?></td>
            <td><?php echo $lotto['number']; ?></td>
            <td><?php echo $lotto['price']; ?></td>
            <td><?php echo number_format($discount, 2, '.', ','); ?></td>
            <td><?php echo number_format($reward, 2, '.', ','); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
    <tr>
        <td colspan="3"><?php echo $bill['datetime']; ?></td>
        <td colspan="2">ยอดสุทธิ &nbsp;<?php echo number_format($sum, 2, '.', ','); ?></td>
    </tr>
    </tfoot>
</table>
<?php endforeach; ?>

<?php if (count($bill_data) === 0) {
    echo 'ไม่มีบิล';
} ?>