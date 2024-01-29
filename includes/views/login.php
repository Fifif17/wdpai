<?php
include_once("includes/visualComponents/beforeContent.php");
?>

<link rel="stylesheet" href="includes/css/login.css">

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
        <form class="login" action="login" method="POST">
            <input name="email" type="text" placeholder="email@email.com">
            <input name="password" type="password" placeholder="password">
            <br><br>
            <button class="loginForm" type="submit">LOGIN</button>
        </form>
        <div class="registerContainer">
            <button id="register" class="loginForm">REGISTER</button>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("register").onclick = function () {
            location.href = "register";
        }
    });
</script>


<?php
include_once("includes/visualComponents/afterContent.php");
?>