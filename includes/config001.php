<?php 
/**
*
* D A T A B A S E   C O N N E C T I O N
*
**/
//print_r($_SERVER['REMOTE_ADDR']); exit;
/*if($_SERVER['REMOTE_ADDR']=='127.0.0.1' || $_SERVER['REMOTE_ADDR']=='::1') {
	//echo "hiii"; exit;
	$con = mysql_connect("localhost", "demosite_lottery", "demosite_lottery@123") or die("Couldn't Connect to the Server");
} else {
	$con = mysql_connect("localhost", "demosite_lottery", "demosite_lottery@123") or die("Couldn't Connect to the Server");
}*/
//$con = mysql_connect("localhost", "sitedemo_loto_gm", "XpKKa(yBb]P5") or die("Couldn't Connect to the Server");
//mysql_select_db("sitedemo_lottery_game") or die("Couldn't open the database");
$con = mysql_connect("localhost", "root", "") or die("Couldn't Connect to the Server");
mysql_select_db("ciestoso_lottery_game") or die("Couldn't open the database");
?>