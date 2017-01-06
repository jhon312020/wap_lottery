<?php 
if($_SESSION['lottery']['memberid'] == '') {
	header("Location:index.php");
	exit;
}

/* $pageName = basename($_SERVER['PHP_SELF']); 

if($pageName != 'agreement.php')
{
	if(!isset($_SESSION['lottery']['agreement'])) {
		header("Location:agreement.php");
		exit;
	}
	if($_SESSION['lottery']['agreement'] == 0) {
		header("Location:agreement.php");
		exit; 
	}
} */
?>