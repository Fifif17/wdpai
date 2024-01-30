<?php
include_once("includes/visualComponents/beforeContent.php");
?>

<center>
    <button class="goBack" onclick="window.history.back();">Go back</button><br><br><br>
    <form action="addWord" method="POST">
        <input name="wordEn" type="text" placeholder="English word" required>
        <input name="wordPl" type="text" placeholder="Polish word" required>

        <input name="setId" type="hidden" value="<?= $_GET['id']; ?>">

        <button type="submit">Add Word</button>
    </form>
</center>

<?php
include_once("includes/visualComponents/afterContent.php");
?>