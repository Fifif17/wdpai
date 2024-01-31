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
        <button class="goBack" onclick="window.location.href='mainPage';">Go back</button>
    </div>
    <div class="actions">
        <?php if ($set->getWordCount() > 0) : ?>
            <button id="learn" onclick="window.location.href='learnPanel?id=<?= $set->getId(); ?>'">START LEARNING</button>
        <?php endif; ?>
        <p><?= $set->getWordCount() ?> <?= ($set->getWordCount() != 1) ? 'words' : 'word' ?></p>
    </div>
</div>

<center>
    <div class="messages">
        <?php
            if(isset($_SESSION['messages']['messages'])){
                foreach($_SESSION['messages']['messages'] as $message) {
                    echo $message . "<br>";
                }
            }
            if(isset($_SESSION['messages']['unknown'])){
                if ($_SESSION['messages']['unknown'][0]) {
                    echo "<h2>You still need to work on these:</h2><br>";
                    foreach($_SESSION['messages']['unknown'][0] as $message) {
                        echo $message->getEn() . " ; " . $message->getPl() . "<br>";
                    }
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