<?php
session_start();

include_once("includes/visualComponents/beforeContent.php");

// $db = new Database();
// $stmt = $db->connect()->prepare('SELECT * FROM users');
// $stmt->execute();

// $val = $stmt->fetchAll();

// print_r($val);

if (isset($_SESSION['uid'])) {
    echo $_SESSION['uid'];
}
if (isset($_SESSION['admin'])) {
    echo $_SESSION['admin'];
}
?>

<script type="text/javascript" src="includes/js/setHistory.js" defer></script>
<script type="text/javascript" src="includes/js/search.js" defer></script>


<?php
if (isset($_SESSION['admin'])) {
    echo "<div class='addSet'>";
    echo "<button>Add Set</button>";
    echo "</div>";
}
?>

<div class="searchBar">
    <input id="searchbar" placeholder="Search" oninput="searchSets()">
</div>



<div class="setContainer">
    <!-- <?php var_dump($sets)?> -->
    <?php foreach ($sets as $set) : ?>
        <div class="wordSet">
            <span id="set_id" hidden><?= $set->getId(); ?></span>
            <img src="<?= $set->getImage(); ?>">
            <h1><?= $set->getName(); ?></h1>
            <span><?= $set->getWordCount() ?> <?= ($set->getWordCount() > 1) ? 'words' : 'word' ?></span>
            <?php if (isset($_SESSION['admin'])) : ?>
                <div class="delSet">
                    <button class="Delete">Delete</button>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>


<script>
    var uid = <?php echo json_encode($_SESSION['uid']); ?>;
</script>


<?php
include_once("includes/visualComponents/afterContent.php");
?>