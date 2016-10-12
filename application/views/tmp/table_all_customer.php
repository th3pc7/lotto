<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<table>
    <thead>
        <tr>
            <td>ชื่อลูกค้า</td>
            <td>ประเภท</td>
            <td>เปอร์เซ็น</td>
            <td>เครดิต</td>
            <td>ยอดได้เสีย</td>
            <td>ยอดเครดิตพนัน</td>
            <!-- <td>เครดิตคงเหลือ</td> -->
            <td>เข้าระบบล่าสุด</td>
            <td>วันที่สมัคร</td>
        </tr>
    </thead>
    <tbody>
    
        <?php if(count($all_customer)===0): ?>
        <tr><td colspan="9">ยังไม่มีสมาชิก</td></tr>
        <?php endif; ?>

        <?php
            $customers = array();
            foreach($all_customer as $customer){
                if(array_key_exists($customer['id'] ,$customers)){
                    if($customer['status']==='betting'){ $customers[$customer['id']]['betting'] = $customers[$customer['id']]['betting'] + $customer['betting']; }
                }
                else{
                    $customers[$customer['id']] = $customer;
                    if($customer['status']!=='betting'){ $customers[$customer['id']]['betting'] = 0; }
                }
            }
            
            foreach($customers as $customer):
        ?>
        <tr>
            <td><?php echo $customer['user'].'('.$customer['name'].')'; ?></td>
            <td><?php echo $customer['class']; ?></td>
            <td class="text-right"><a href="#" class="a-set-percent" data-customerid="<?php echo $customer['id']; ?>"><?php echo ($customer['class']==='agent') ? $customer['percent'] : ''; ?></a></td>
            <td class="text-right"><a href="#" class="a-set-credit" data-customerid="<?php echo $customer['id']; ?>"><?php echo number_format($customer['credit'],2,'.',','); ?></a></td>
            <td class="text-right"><?php echo number_format($customer['balance'],2,'.',','); ?></td>
            <td class="text-right"><?php echo number_format(-$customer['betting'],2,'.',','); ?></td>
            <!-- <td><?php echo number_format(floatval($customer['credit'])+floatval($customer['balance'])-floatval($customer['betting']),2,'.',','); ?></td> -->
            <td class="text-right"><?php echo ($customer['last_login']!==null) ? $customer['last_login'] : 'ยังไม่เคย Login'; ?></td>
            <td class="text-right"><?php echo $customer['datetime']; ?></td>
        </tr>
        <?php endforeach; ?>

    </tbody>
</table>
