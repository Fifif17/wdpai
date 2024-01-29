<?php
session_start();

include_once("includes/visualComponents/beforeContent.php");

$db = new Database();
$stmt = $db->connect()->prepare('SELECT * FROM users');
$stmt->execute();

$val = $stmt->fetchAll();

// print_r($val);

if (isset($_SESSION['uid'])) {
    echo $_SESSION['uid'];
}

?>



<div>
    <span>PLACEHOLDER MAIN PAGE</span>
</div>


<?php
include_once("includes/visualComponents/afterContent.php");
?>