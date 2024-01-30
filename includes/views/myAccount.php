<?php
// session_start();

include_once("includes/visualComponents/beforeContent.php");
?>

<script type="text/javascript" src="includes/js/sets.js" defer></script>

<center>
    <div class="logoutContainer">
        <button class="goBack" onclick="window.history.back();">Go back</button>
        <br><br>
        <button id="logout" onclick="location.href='logout'">LOGOUT</button>
    </div>
</center>

<h1>HISTORY</h1>
<div class="setContainer">
    <?php
        foreach ($sets as $set) : ?>
        
            <div class="wordSet">
                <span id="set_id" hidden><?= $set->getId(); ?></span>
                
                <img src="<?= $set->getImage(); ?>">
                <h1><?= $set->getName(); ?></h1>
                <span><?= $set->getWordCount() ?> <?= ($set->getWordCount() > 1) ? 'words' : 'word' ?></span>
            </div>
        <?php endforeach; ?>
</div>

<script>
    var uid = <?php echo json_encode($_SESSION['uid']); ?>;
</script>

<?php
include_once("includes/visualComponents/afterContent.php");
?>