<table>
    <thead>
        <tr>
            <td>ชนิดหมายเลข</td>
            <td>หมายเลข</td>
            <td>ยอดอั้น</td>
            <td>ดำเนินการ</td>
        </tr>
    </thead>
    <tbody>

        <?php if(count($agent_limit)===0): ?>
        <tr><td colspan="4">ยังไม่ได้กำหนดเลขอั้น</td></tr>
        <?php return; endif; ?>

        <?php foreach($agent_limit as $limit): ?>
        <tr>
            <td><?php echo $limit['type_name']; ?></td>
            <td><?php echo ($limit['number']==='*') ? 'อั้นทั้งชนิด' : $limit['number']; ?></td>
            <td><?php echo number_format($limit['price'],2,'.',','); ?></td>
            <td><button class="del-lotto-limit" data-limitID="<?php echo $limit['id']; ?>"> x </button></td>
        </tr>
        <?php endforeach; ?>

    </tbody>
</table>