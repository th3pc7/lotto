<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script> var useScript = "login"; </script>

<div class="padding-top-10">
<!--    <input class="input-user" id="input-user" type="text">-->
<!--    <input class="input-pass" id="input-pass" type="password">-->
<!--    <button class="btn-login">Login</button>-->

    <br>
    <h1 class="fg-blue Metro heading">Login</h1>
    <br>

    <div class="input-control modern text iconic">
        <input id="input-user" class="input-user" type="text">
        <span class="label">Username</span>
        <span class="informer">Please enter your username</span>
<!--        <span class="placeholder">Input Username</span>-->
        <span class="icon mif-user"></span>
    </div>


    <div class="input-control modern password iconic" data-role="input">
        <input id="input-pass" class="input-user" type="password">
        <span class="label">Password</span>
        <span class="informer">Please enter you password</span>
<!--        <span class="placeholder">Input password</span>-->
        <span class="icon mif-lock"></span>
        <button class="button helper-button reveal"><span class="mif-looks"></span></button>
    </div>

    <div class="input-control modern text">
        <button class="btn-login button success">Login</button>
    </div>
</div>
