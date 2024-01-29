<?php
include_once("includes/visualComponents/beforeContent.php");
?>

<link rel="stylesheet" href="includes/css/login.css">
<script type="text/javascript" src="includes/js/register.js" defer></script>

<center>
<div class="messages">
    <?php if(isset($messages)) {
        foreach ($messages as $message) {
            echo $message;
        }
    }
    ?>
</div>
</center>

<div class="loginContainer">
    <div class="leftSection">
        <img src="includes/assets/logo3.png">
    </div>

    <div class="rightSection">
        <form class="login" action="register" method="POST">
            <input name="login" type="text" placeholder="username">
            <input name="email" type="text" placeholder="email@email.com">
            <input name="password" type="password" placeholder="password">
            <input name="firstname" type="text" placeholder="firstname">
            <input name="lastname" type="text" placeholder="lastname">
            <input name="phone" type="text" placeholder="phone">
            <br><br>
            <button class="loginForm" type="submit">REGISTER</button>
        </form>
    </div>
</div>


<?php
include_once("includes/visualComponents/afterContent.php");
?>