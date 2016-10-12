<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script> var useScript = "agent_limit"; </script>

<?php if(count($lotto_type)!==0): ?>
<h2>เพิ่มเลขอั้น</h2>
<form id="form-add-lotto-limit">
    <input name="Action" type="text" value="add_limit" class="hide">
    <select name="type">
        <?php foreach($lotto_type as $type): ?>
        <option value="<?php echo $type['id']; ?>"><?php echo $type['type_name']; ?></option>
        <?php endforeach; ?>
    </select><br>
    <input name="number" type="text" placeholder="หมายเลข" autocomplete="off"> : หมายเลข ปล.ใส่เครื่องหมาย * เพื่ออั้นทั้งชนิด<br>
    <input name="price" type="text" placeholder="ยอดอั้น" autocomplete="off"> : ยอดอั้น<br>
    <button type="submit">Save</button> <button type="reset">Reset</button>
</form>
<?php endif; ?>

<h2>เลขอั้นทั้งหมด</h2>
<div id="tb-lotto-limit"> <?php $this->load->view('tmp/table_agent_lotto_limit', $data); ?> </div>