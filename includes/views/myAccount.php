<?php
session_start();

include_once("includes/visualComponents/beforeContent.php");
?>

<center>
    <div class="logoutContainer">
        <button id="logout" onclick="location.href='logout'">LOGOUT</button>
    </div>
</center>

<div>
    <h1>HISTORY</h1>
    <div>placeholder history</div>
</div>


<?php
include_once("includes/visualComponents/afterContent.php");
?>