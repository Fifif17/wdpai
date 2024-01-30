<?php
include_once("includes/visualComponents/beforeContent.php");
?>

<link rel="stylesheet" href="includes/css/setPage.css">

<script type="text/javascript" src="includes/js/words.js" defer></script>

<div class="learnBar">
    <div class="info">
        <img src="<?= $set->getImage(); ?>">
        <h1><?= $set->getName(); ?></h1>
        <?php
            if (isset($_SESSION['admin'])) {
                echo "<button class='addElement goBack' onclick='window.location.href=\"addWord?id={$set->getId()}\"'>Add Word</button>";
            }
        ?>
        <button class="goBack" onclick="window.history.back();">Go back</button>
    </div>
    <div class="actions">
        <button id="learn">START LEARNING</button>
        <p><?= $set->getWordCount() ?> <?= ($set->getWordCount() != 1) ? 'words' : 'word' ?></p>
    </div>
</div>

<center>
    <div class="messages">
        <?php
            if(isset($_SESSION['messages'])){
                foreach($_SESSION['messages'] as $message) {
                    echo $message[0];
                }
            }
            unset($_SESSION['messages']);
        ?>
    </div>
    <br><br>
</center>

<div class="wordsContainer">
    <?php foreach ($set->getWords() as $word) : ?>
        <div class="word">
            <span id="wordEn"><?= $word->getEn(); ?></span>
            <span id="wordPl"><?= $word->getPl(); ?></span>
            <?php if (isset($_SESSION['admin'])) : ?>
                <div class="delSet">
                    <button class="Delete">Delete</button>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<?php
include_once("includes/visualComponents/afterContent.php");
?>