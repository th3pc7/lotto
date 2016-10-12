<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
    var me_code = "U<?php echo $me_id; ?>";
    var data_stack = [];
    <?php
        $index = 0;
        foreach($dataLotto as $lotto){
            echo 'data_stack['.$index.'] = "'.$lotto['forward'].'";';
            $index = $index + 1;
        }
    ?>
</script>

<style> #tb-risk,#tb-risk-sc{float:left;width:160px;} </style>
<div id="tb-risk">Loading</div>
<div id="tb-risk-sc">Loading</div>
<div class="clearfix"></div>