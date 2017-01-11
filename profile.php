<?php
	require_once("includes/head.php");
	if(isset($_GET['action']))
	{
		if($_GET['action'] == 'agree')
		{
			$_SESSION['lottery']['agreement'] = 1;
		}
	}
	//a6a898e784ee9481dd656d72fff7cb27
	require_once("includes/check-authentication.php");
	$mem_id = $_SESSION['lottery']['memberid'];
	$member_info = mysql_fetch_array(mysql_query("SELECT * FROM `lottery_member_registration` WHERE `member_id` = '".$mem_id."'"));
	$bank_list = mysql_query("SELECT * FROM `lottery_bank`");
	
	$arrHomeInformation = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '23'"));
	if(isset($_REQUEST['submit'])){
		$oldPassword = $_REQUEST['oldPassword'];
		$newPassword = $_REQUEST['newPassword'];
		$confirmPassword = $_REQUEST['confirmPassword'];
		if($oldPassword != ""){
			if($newPassword == $confirmPassword && $newPassword && $confirmPassword){
				$oldPassword = md5($oldPassword);
				$newPassword = md5($newPassword);
			
				$sql_login = "SELECT * FROM `lottery_member_registration` WHERE 
				`member_id` = '".$_SESSION['lottery']['memberid']."'";
				//echo $sql_login; exit;
				$res_login = mysql_query($sql_login);
				$row_login = mysql_fetch_assoc($res_login);
				$DBPassword = $row_login['member_password'];
				
				if($oldPassword == $DBPassword)
				{
					$sql_update_member = "UPDATE `lottery_member_registration` SET `member_password` = '".$newPassword."'  WHERE member_id = '".$_SESSION['lottery']['memberid']."'";
					$res_update_member = mysql_query($sql_update_member);
					header("location:profile.php?suc=1");
					exit();
				}
				else{
					header("location:profile.php?err=1");
					exit();
				}
			}
			else{
				header("location:profile.php?err=2");
				exit();
			}
		}
		
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once("includes/html_head.php");?>
</head> 
<body> 
<div class="container-fluid">
<?php include("includes/navigation.php");?>
<div class="success alert alert-success" style="display:none;"></div>
<div class="error alert alert-danger" style="display:none;"></div>
<form name="frm_register" id="member-form" method="POST" action="" role="form" class="form-horizontal">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Username</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="username" name="username" placeholder="username" maxlength="25" value="<?php echo $member_info['member_username']; ?>" readonly>
    </div>
  </div>
 
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Mobile Number</label>
    <div class="col-sm-10">
      <input type="number" class="form-control" id="phone_number" name="phone_number" placeholder="Mobile Number" maxlength="20" value="<?php echo $member_info['member_phoneno']; ?>" readonly>
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-10">
      <input type="email" class="form-control" id="email" name="email" placeholder="anonymous@email.com" maxlength="35" value="<?php echo $member_info['member_email']; ?>" readonly>
    </div>
  </div>
   <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Bank Name</label>
    <div class="col-sm-10">
		<input type="email" class="form-control" id="email" name="email" placeholder="anonymous@email.com" maxlength="35" value="<?php echo $member_info['member_bank_name']; ?>" readonly>
  </div>
	</div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Account Name</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="account_name" name="account_name" placeholder="Account Name" maxlength="25" value="<?php echo $member_info['member_account_name']; ?>" readonly>
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Account Number</label>
    <div class="col-sm-10">
      <input type="number" class="form-control" id="acc_number" name="acc_number" placeholder="Account Number" maxlength="25" value="<?php echo $member_info['member_account_no']; ?>" readonly>
    </div>
  </div>
	<div class="form-group">
		<label for="referral" class="col-sm-2 control-label">Referrals:</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="referral" id="referral" value="<?php echo $member_info['member_referrals']; ?>" readonly>
		</div>
	</div>
	<div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Old Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="oldPassword" placeholder="Old Password" name="oldPassword" maxlength="25">
    </div>
  </div>
   <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">New Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="newPassword" placeholder="New Password" name="newPassword" maxlength="25">
    </div>
  </div>
	<div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Retype Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="confirmPassword" placeholder="Re Password" name="confirmPassword" maxlength="25">
    </div>
  </div>
	
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" value="submit"  class="btn btn-default" name="submit">Update</button><br>
    </div>
  </div>
</form>
<?php if($_REQUEST['suc']=='1') { ?>
	<script type="text/javascript">
		$('.success').show();
		$('.success').html("You have successfully updated your profile.");
	</script>
<?php } ?>
<?php if($_REQUEST['err']=='1') { ?>
	<script type="text/javascript">
		$('.error').show();
		$('.error').html("Invalid Old Password");
	</script>
<?php } ?>
<?php if($_REQUEST['err']=='2') { ?>
	<script type="text/javascript">
		$('.error').show();
		$('.error').html("New & Confirm Password Not Match");
	</script>
<?php } ?>
</div>
<?php include("includes/footer.php");?>
</body> 
</html>