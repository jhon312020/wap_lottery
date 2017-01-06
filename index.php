<?php
	require_once("includes/head.php");
	if(isset($_REQUEST['submit'])){
	$error = 0;
		if(isset($_REQUEST['header_login'])){
		
			if(empty($_SESSION['head_captcha_code'] ) || strcasecmp($_SESSION['head_captcha_code'], $_POST['captcha_code_1']) != 0){  
			header("Location:index.php?msg=captcha_error");
			
			$error = 1;
			}
		}
		if(isset($_REQUEST['main_login'])){
		
			if(empty($_SESSION['login_captcha_code'] ) || strcasecmp($_SESSION['login_captcha_code'], $_POST['captcha_code_2']) != 0){  
			header("Location:index.php?msg=captcha_error");
			
			$error = 1;
			}
		}
		if($error == 0)
		{
			$username = $_REQUEST['uname'];
			$password = md5($_REQUEST['pass']);
			
			$sql_login = "SELECT * FROM `lottery_member_registration` WHERE 
			`member_username` = '".$username."' AND 
			`member_password` = '".$password."'";
			//echo $sql_login; exit;
			$res_login = mysql_query($sql_login);
			$row_login = mysql_fetch_array($res_login);
			$count_login = mysql_num_rows($res_login);
			//echo $count_login; exit;
			$member_id = $row_login['member_id'];
			$member_status = $row_login['member_status'];
			
			
			// echo $member_status; exit;
			if($member_status == '3') {
				header("Location:index.php?msgStatus=blacklist&player_id=".$member_id);
				}else if($count_login == "0"){
				header("Location:index.php?msg=error");
				}else{
				
				$res = mysql_query("UPDATE lottery_member_registration SET last_login_ip='".$_SERVER['REMOTE_ADDR']."' WHERE member_id = '$member_id'");
				
				$_SESSION['lottery']['memberid'] = $member_id;
				$_SESSION['lottery']['agreement'] = 0; 
				header("Location:my-account.php");
				//header("Location:my-account.php");
				exit();
				
			}
		}
	}
	
?>

<!DOCTYPE html> <html lang="en"> 
<head> <meta charset="utf-8"> <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
<?php require_once("includes/html_head.php");?>
</head> 
<body>
<div style="padding:5px 10px 5px 10px">
<a href="index.php"><img src="../images/logo.png" width="95%" /></a>
<hr>
<?php 
		$playerid = $_REQUEST['player_id'];
		$arrnotes = mysql_fetch_array(mysql_query("SELECT * FROM lottery_member_registration WHERE member_id = '".$playerid."'"));
		$notes = $arrnotes['member_notes'];
	?>
<div class="blacklistMember alert alert-danger" style="display:none;" role="alert"></div>
<div class="blackReason alert alert-danger" style="display:none;" role="alert">Reason : <?php echo $notes;?></div>
<div style="display:none;" class="errorlogin alert alert-danger" role="alert"> <ul> <li></li> </ul> </div>
<form class="form-horizontal" method="post" action="" name="frm" id="frm">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Username</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="uname" name="uname" placeholder="username" maxlength="25">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="pass" placeholder="Password" name="pass" maxlength="25">
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label"></label>
    <div class="col-sm-10">
		<input type="hidden" name="main_login" value="1">
	  <img src="captcha.php?type=login" id='captchaimg'><br>
		<input id="captcha_code" name="captcha_code_2" placeholder="Code" type="text" class="form-control" >
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default" value="Submit" name="submit">Sign in</button><br>
			Need Account ? <a href="member-registration.php">REGISTER </a>Here 
    </div>
  </div>
</form>
</div>
<!--end-of col-md-12--> 
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg'] =="error"){?> 
	<script type="text/javascript">
		$(".errorlogin").show();
		$(".errorlogin").html("Access denied. Incorrect login or password!");
	</script>
<?php }?>
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg'] =="captcha_error"){?> 
	<script type="text/javascript">
		$(".errorlogin").show();
		$(".errorlogin").html("The Validation Captcha code does not match!");
	</script>
<?php }?>

<?php if(isset($_REQUEST['msgStatus'])=="blacklist"){?> 
	<script type="text/javascript">
		$(".blacklistMember").show();
		$(".blackReason").show();
		$(".blacklistMember").html("You have been blacklisted from Admin end !");
	</script>
<?php }?>
</body>
</html>


