<?php

include_once("includes/visualComponents/beforeContent.php");

?>

<link rel="stylesheet" type="text/css" href="includes/css/projects.css">

<section class="projectForm">
    <h1>UPLOAD</h1>
    <form action="addProject" method="POST" ENCTYPE="multipart/form-data">
        <?php if(isset($messages)) {
            foreach ($messages as $message) {
                echo $message;
            }
        }
        ?>

        <input name="title" type="text" placeholder="title">
        <textarea name="description" rows="5" placeholder="description"></textarea>

        <input name="file" type="file">
        <button type="submit">send</button>
    </form>
</section>


<?php
include_once("includes/visualComponents/afterContent.php");
?>