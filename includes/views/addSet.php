<?php
include_once("includes/visualComponents/beforeContent.php");
?>

<center>
    <button class="goBack" onclick="window.history.back();">Go back</button><br><br><br>
    <form action="addSet" method="POST" ENCTYPE="multipart/form-data">
        <input name="setName" type="text" placeholder="set name" required>

        <input type="file" name="file" required><br><br>
        <input type="hidden" name="uid" value="<?= $_SESSION['uid']; ?>">

        <button type="submit">Add Set</button>
    </form>
</center>
<?php
include_once("includes/visualComponents/afterContent.php");
?>