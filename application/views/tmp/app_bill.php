<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$sums_count = 0;
$sums_price = 0;
$sums_discount = 0;
$sums_reward = 0;
$sums_static = 0;
$o_sp = 0;
$o_sd = 0;
$o_sr = 0;
$t_sp = 0;
$t_sd = 0;
$t_sr = 0;
$h_sp = 0;
$h_sd = 0;
$h_sr = 0;
$so = 0;
$st = 0;
$sh = 0;
?>

<script>
    function collapes(elm){
        var mini = (elm.innerText=="ขยาย") ? true : false;
        var tbody = elm.parentElement.parentElement.parentElement.parentElement.querySelector('tbody');
        if(mini){
            tbody.style.display = "";
            elm.innerHTML = "ย่อ";
        }
        else{
            console.log(0);
            tbody.style.display = "none";
            elm.innerHTML = "ขยาย";
        }
    }
</script>

<div style="position:relative;padding-top:126px;">
<?php foreach ($bill_data as $bill): ?>
    <table>
        <thead>
            <tr>
                <td colspan="5">
                    <?php echo '#' . $bill['id'] . ' - ' . (($bill['note']==='') ? 'ไม่มีหมายเหตุบิล' : $bill['note']); ?>
                    <div style="float:right;cursor:pointer;color:blue;text-decoration:underline;" onclick="collapes(this);">ขยาย</div>
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
        <tbody style="display:none;">
            <?php
                $sum = 0;
                $bill_price = 0;
                $bill_disc = 0;
                $bill_reward = 0;
                $o_p = 0;
                $o_d = 0;
                $o_r = 0;
                $t_p = 0;
                $t_d = 0;
                $t_r = 0;
                $tt_p = 0;
                $tt_d = 0;
                $tt_r = 0;
                foreach ($bill['lottos'] as $lotto):
                    ++$sums_count;
                    $price = floatval($lotto['price']);
                    $bill_price = $bill_price + $price;
                    $sums_price = $sums_price + $price;
                    $discount = $price * floatval($lotto['discount']) / 100;
                    $bill_disc = $bill_disc + $discount;
                    $sums_discount = $sums_discount + $discount;
                    $reward = intval($lotto['binggo']) * floatval($lotto['reward']) * $price;
                    $bill_reward = $bill_reward + $reward;
                    $sums_reward = $sums_reward + $reward;
                    $sum = $sum + $discount + $reward - $price;
                    if(strlen($lotto['number'])===1){
                        $o_p = $o_p + $price;
                        $o_d = $o_d + $discount;
                        $o_r = $o_r + $reward;
                        $o_sp = $o_sp + $price;
                        $o_sd = $o_sd + $discount;
                        $o_sr = $o_sr + $reward;
                        ++$so;
                    }
                    else if(strlen($lotto['number'])===2){
                        $t_p = $t_p + $price;
                        $t_d = $t_d + $discount;
                        $t_r = $t_r + $reward;
                        $t_sp = $t_sp + $price;
                        $t_sd = $t_sd + $discount;
                        $t_sr = $t_sr + $reward;
                        ++$st;
                    }
                    else{
                        $tt_p = $tt_p + $price;
                        $tt_d = $tt_d + $discount;
                        $tt_r = $tt_r + $reward;
                        $h_sp = $h_sp + $price;
                        $h_sd = $h_sd + $discount;
                        $h_sr = $h_sr + $reward;
                        ++$sh;
                    }
            ?>
            <tr <?php if($reward > 0){ echo 'style="background-color:#81ff81;"'; } ?>>
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
            <td colspan="2">1 ตัว</td>
            <td><?php echo number_format($o_p, 2, '.', ','); ?></td>
            <td><?php echo number_format($o_d, 2, '.', ','); ?></td>
            <td><?php echo number_format($o_r, 2, '.', ','); ?></td>
        </tr>
        <tr>
            <td colspan="2">2 ตัว</td>
            <td><?php echo number_format($t_p, 2, '.', ','); ?></td>
            <td><?php echo number_format($t_d, 2, '.', ','); ?></td>
            <td><?php echo number_format($t_r, 2, '.', ','); ?></td>
        </tr>
        <tr>
            <td colspan="2">3 ตัว</td>
            <td><?php echo number_format($tt_p, 2, '.', ','); ?></td>
            <td><?php echo number_format($tt_d, 2, '.', ','); ?></td>
            <td><?php echo number_format($tt_r, 2, '.', ','); ?></td>
        </tr>
        <tr>
            <td colspan="2">รวม</td>
            <td><?php echo number_format($bill_price, 2, '.', ','); ?></td>
            <td><?php echo number_format($bill_disc, 2, '.', ','); ?></td>
            <td><?php echo number_format($bill_reward, 2, '.', ','); ?></td>
        </tr>
        <tr>
            <td colspan="3"><?php echo $bill['datetime']; ?></td>
            <td colspan="2">ยอดสุทธิ &nbsp;<?php echo number_format($sum, 2, '.', ','); $sums_static = $sums_static + $sum; ?></td>
        </tr>
        </tfoot>
    </table>
<?php endforeach; ?>

<?php if (count($bill_data) === 0) {
    echo 'ไม่มีบิล';
} ?>


    <table id="tb-sums" style="float:right;position:absolute;top:0px;margin-top:10px;">
        <thead>
            <tr>
                <td>ชนิด</td>
                <td>จำนวนซื้อ(เบอร์)</td>
                <td>ยอดซื้อ</td>
                <td>ยอดส่วนลด</td>
                <td>ยอดรางวัล</td>
                <td>ยอดสุทธิ</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1 ตัว</td>
                <td><?php echo $so; ?></td>
                <td><?php echo number_format($o_sp, 2, '.', ','); ?></td>
                <td><?php echo number_format($o_sd, 2, '.', ','); ?></td>
                <td><?php echo number_format($o_sr, 2, '.', ','); ?></td>
                <td><?php echo number_format(-$o_sp+$o_sd+$o_sr, 2, '.', ','); ?></td>
            </tr>
            <tr>
                <td>2 ตัว</td>
                <td><?php echo $st; ?></td>
                <td><?php echo number_format($t_sp, 2, '.', ','); ?></td>
                <td><?php echo number_format($t_sd, 2, '.', ','); ?></td>
                <td><?php echo number_format($t_sr, 2, '.', ','); ?></td>
                <td><?php echo number_format(-$t_sp+$t_sd+$t_sr, 2, '.', ','); ?></td>
            </tr>
            <tr>
                <td>3 ตัว</td>
                <td><?php echo $sh; ?></td>
                <td><?php echo number_format($h_sp, 2, '.', ','); ?></td>
                <td><?php echo number_format($h_sd, 2, '.', ','); ?></td>
                <td><?php echo number_format($h_sr, 2, '.', ','); ?></td>
                <td><?php echo number_format(-$h_sp+$h_sd+$h_sr, 2, '.', ','); ?></td>
            </tr>
            <tr>
                <td>รวม</td>
                <td><?php echo $sums_count ?></td>
                <td><?php echo number_format($sums_price, 2, '.', ','); ?></td>
                <td><?php echo number_format($sums_discount, 2, '.', ','); ?></td>
                <td><?php echo number_format($sums_reward, 2, '.', ','); ?></td>
                <td><?php echo number_format($sums_static, 2, '.', ','); ?></td>
            </tr>
        </tbody>
    </table>
</div>