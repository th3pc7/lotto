<?php

  defined('BASEPATH') OR exit('No direct script access allowed');

  if($this->input->post('custom_load')==='true'){
    $this->load->view('tmp/'.$tmp,$data);
    return;
  }

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Lotto</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/main.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/user.css">

  <link href="<?php echo base_url() ?>MetroUI\docs\css\metro-icons.css" rel="stylesheet">
  <link href="<?php echo base_url() ?>MetroUI\docs\css\metro.css" rel="stylesheet">
  <script src="<?php echo base_url(); ?>MetroUI\docs\js\jquery-2.1.3.min.js"></script>
  <script src="<?php echo base_url(); ?>MetroUI\docs\js\metro.js"></script>

</head>
<body class="body-login">
  <div id="body" class="div-login"><?php $this->load->view('tmp/'.$tmp,$data); ?></div>
  <script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  <script src="<?php echo base_url(); ?>js/main.js"></script>
</body>
</html>
