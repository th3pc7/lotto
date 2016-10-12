<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<table>
    <thead>
        <tr>
            <td colspan="5">ลูกค้า</td>
            <td colspan="4">เรา</td>
            <td colspan="4">เอเย่น</td>
        </tr>
        <tr>
            <td>ชื่อลูกค้า</td>
            <td>ยอดซื้อ</td>
            <td>ส่วนลด</td>
            <td>ยอดถูก</td>
            <td class="td-hl-cus">รวม</td>
            <td>ยอดถือสู้</td>
            <td>ส่วนลด</td>
            <td>ยอดถูก</td>
            <td class="td-hl-me">รวม</td>
            <td>ยอดถือสู้</td>
            <td>ส่วนลด</td>
            <td>ยอดถูก</td>
            <td class="td-hl-com">รวม</td>
        </tr>
    </thead>
    <tbody id="ag-page-table-tbody">
        <?php
            $sum = array(
                'customer-buy' => 0,
                'customer-discount' => 0,
                'customer-reward' => 0,
                'customer-sum' => 0,
                'agent-accept' => 0,
                'agent-discount' => 0,
                'agent-reward' => 0,
                'agent-sum' => 0,
                'company-accept' => 0,
                'company-discount' => 0,
                'company-reward' => 0,
                'company-sum' => 0
            );
            foreach($obj_customer as $customer_data):
        ?>
        <tr>
            <td class="text-right"><?php echo $customer_data['customer-name']; ?></td>
            <td class="text-right"><?php echo number_format($customer_data['customer-buy'],2,'.',','); ?></td>
            <td class="text-right"><?php echo number_format($customer_data['customer-discount'],2,'.',','); ?></td>
            <td class="text-right"><?php echo number_format($customer_data['customer-reward'],2,'.',','); ?></td>
            <td class="text-right td-hl-cus"><?php echo number_format($customer_data['customer-sum'],2,'.',','); ?></td>
            <td class="text-right"><?php echo number_format($customer_data['agent-accept'],2,'.',','); ?></td>
            <td class="text-right"><?php echo number_format($customer_data['agent-discount'],2,'.',','); ?></td>
            <td class="text-right"><?php echo number_format($customer_data['agent-reward'],2,'.',','); ?></td>
            <td class="text-right td-hl-me"><?php echo number_format($customer_data['agent-sum'],2,'.',','); ?></td>
            <td class="text-right"><?php echo number_format($customer_data['company-accept'],2,'.',','); ?></td>
            <td class="text-right"><?php echo number_format($customer_data['company-discount'],2,'.',','); ?></td>
            <td class="text-right"><?php echo number_format($customer_data['company-reward'],2,'.',','); ?></td>
            <td class="text-right td-hl-com"><?php echo number_format($customer_data['company-sum'],2,'.',','); ?></td>
        </tr>
        <?php
            $sum['customer-buy'] = $sum['customer-buy'] + $customer_data['customer-buy'];
            $sum['customer-discount'] = $sum['customer-discount'] + $customer_data['customer-discount'];
            $sum['customer-reward'] = $sum['customer-reward'] + $customer_data['customer-reward'];
            $sum['customer-sum'] = $sum['customer-sum'] + $customer_data['customer-sum'];
            $sum['agent-accept'] = $sum['agent-accept'] + $customer_data['agent-accept'];
            $sum['agent-discount'] = $sum['agent-discount'] + $customer_data['agent-discount'];
            $sum['agent-reward'] = $sum['agent-reward'] + $customer_data['agent-reward'];
            $sum['agent-sum'] = $sum['agent-sum'] + $customer_data['agent-sum'];
            $sum['company-accept'] = $sum['company-accept'] + $customer_data['company-accept'];
            $sum['company-discount'] = $sum['company-discount'] + $customer_data['company-discount'];
            $sum['company-reward'] = $sum['company-reward'] + $customer_data['company-reward'];
            $sum['company-sum'] = $sum['company-sum'] + $customer_data['company-sum'];

            endforeach;
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td class="text-right">รวม</td>
            <td class="text-right"><?php echo number_format($sum['customer-buy'],2,'.',','); ?></td>
            <td class="text-right"><?php echo number_format($sum['customer-discount'],2,'.',','); ?></td>
            <td class="text-right"><?php echo number_format($sum['customer-reward'],2,'.',','); ?></td>
            <td class="text-right td-hl-cus"><?php echo number_format($sum['customer-sum'],2,'.',','); ?></td>
            <td class="text-right"><?php echo number_format($sum['agent-accept'],2,'.',','); ?></td>
            <td class="text-right"><?php echo number_format($sum['agent-discount'],2,'.',','); ?></td>
            <td class="text-right"><?php echo number_format($sum['agent-reward'],2,'.',','); ?></td>
            <td class="text-right td-hl-me"><?php echo number_format($sum['agent-sum'],2,'.',','); ?></td>
            <td class="text-right"><?php echo number_format($sum['company-accept'],2,'.',','); ?></td>
            <td class="text-right"><?php echo number_format($sum['company-discount'],2,'.',','); ?></td>
            <td class="text-right"><?php echo number_format($sum['company-reward'],2,'.',','); ?></td>
            <td class="text-right td-hl-com"><?php echo number_format($sum['company-sum'],2,'.',','); ?></td>
        </tr>
    <tfoot>
</table>