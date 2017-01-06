<?php require_once("includes/head.php");
$reference = $_REQUEST['ref'];
$_SESSION['lottery']['refusername'] = $reference;
header("Location:daftar.php");
exit();
?>