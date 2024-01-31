<?php
$csrfToken = bin2hex(random_bytes(32));
$_SESSION['csrfToken'] = $csrfToken;

include_once("includes/visualComponents/beforeContent.php");
?>

<link rel="stylesheet" href="includes/css/learnPage.css">

<script type="text/javascript" src="includes/js/learn.js" defer></script>

<div class="learnContainer">
<button class="goBack" onclick="window.location.href='setPage?id=<?= $_GET['id']; ?>';">Go back</button>
    <h2><?= ($wordCount-$currentWordIndex); ?> left</h2>
    <div class="learnWords" onclick="toggleWords()">
        <span id="wordEn" class="visible"><?= $words[$currentWordIndex]->getEn(); ?></span>
        <span id="wordPl" class="hidden"><?= $words[$currentWordIndex]->getPl(); ?></span>
    </div>
    <br>
    <div class="buttonContainer">
        <form method="post">
            <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">
            <div class="learnButtonContainer">
                <button type="submit" name="iKnow">I know</button>
                <button type="submit" name="iDontKnow">I don't know</button>
            </div>
        </form>
    </div>
</div>

<script>
    console.log(<?=$currentWordIndex?>);
</script>
<?php
include_once("includes/visualComponents/afterContent.php");
?>