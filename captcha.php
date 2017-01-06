<?php
	session_start();
	include("./phptextClass.php");
	$type = 0;
	if(isset($_REQUEST['type']))
	{
		if($_REQUEST['type'] == "head")
		{
			$type = 1;
		}
		if($_REQUEST['type'] == "login")
		{
			$type = 2;
		}
		if($_REQUEST['type'] == "register")
		{
			$type = 3;
		}
		if($_REQUEST['type'] == "admin")
		{
			$type = 4;
		}
	}
	/*create class object*/
	$phptextObj = new phptextClass();	
	/*phptext function to genrate image with text*/
	$phptextObj->phpcaptcha($type,'#FF0000','#fff',80,30,10,25,1);	
 ?>
