<?php
include_once("includes/visualComponents/beforeContent.php");

if (isset($_SESSION['uid'])) {
    echo '<script defer>';
    echo 'var uid = ' . json_encode($_SESSION['uid']) . ';';
    echo '</script>';
} else {
    echo '<script defer>';
    echo 'var uid = -1;';
    echo '</script>';
}
?>

<script type="text/javascript" src="includes/js/sets.js" defer></script>
<script type="text/javascript" src="includes/js/search.js" defer></script>


<?php
if (isset($_SESSION['admin'])) {
    echo "<div class='addSet'>";
    echo "<button class='addElement' onclick='window.location.href=\"addSet\"'>Add Set</button>";
    echo "</div>";
}
?>
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

<div class="searchBar">
    <input id="searchbar" placeholder="Search" oninput="searchSets()">
</div>


<div class="setContainer">
    <?php foreach ($sets as $set) : ?>
        <div class="wordSet">
            <span id="set_id" hidden><?= $set->getId(); ?></span>
            <img src="<?= $set->getImage(); ?>">
            <h1><?= $set->getName(); ?></h1>
            <span><?= $set->getWordCount() ?> <?= ($set->getWordCount() > 1) ? 'words' : 'word' ?></span>
            <?php if (isset($_SESSION['admin'])) : ?>
                <div class="delSet">
                    <button class="Delete" dataSetId="<?= $set->getId(); ?>">Delete</button>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>


<?php
include_once("includes/visualComponents/afterContent.php");
?>