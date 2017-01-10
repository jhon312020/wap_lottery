<?php
	require_once("includes/head.php");
	if(isset($_GET['action']))
	{
		if($_GET['action'] == 'agree')
		{
			$_SESSION['lottery']['agreement'] = 1;
		}
	}
	require_once("includes/check-authentication.php");
	$mem_id = $_SESSION['lottery']['memberid'];
	$member_info = mysql_fetch_array(mysql_query("SELECT * FROM `lottery_member_registration` WHERE `member_id` = '".$mem_id."'"));
	$bank_list = mysql_query("SELECT * FROM `lottery_bank`");
	
	$arrHomeInformation = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '23'"));
	if(isset($_REQUEST['submit'])){
		/*$firstname = $_REQUEST['fname'];
			$lastname = $_REQUEST['lname'];
			$phoneno = $_REQUEST['phone_number'];
			$email = $_REQUEST['email'];
			$bankname = $_REQUEST['bank_name'];
			$accountname = $_REQUEST['account_name'];
			$accountnumber = $_REQUEST['acc_number'];
			$referrals = $_REQUEST['referral'];
			$sql_update_member = "UPDATE `lottery_member_registration` SET `member_fname` = '".$firstname."',
			`member_lname` = '".$lastname."',
			`member_phoneno` = '".$phoneno."',
			`member_email` = '".$email."',
			`member_bank_name` = '".$bankname."',
			`member_account_name` = '".$accountname."',
			`member_account_no` = '".$accountnumber."',
			`member_referrals` = '".$referrals."'";
			$sql_update_member .= " WHERE member_id = '".$_SESSION['lottery']['memberid']."'";
			
		*/
		$oldPassword = $_REQUEST['oldPassword'];
		$newPassword = $_REQUEST['newPassword'];
		$confirmPassword = $_REQUEST['confirmPassword'];
		
		if($oldPassword != ""){
			if($newPassword == $confirmPassword){
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
					header("location:my-account.php?suc=1");
					exit();
				}
				
				else{
					header("location:my-account.php?err=1");
					exit();
				}
			}
			else{
				header("location:my-account.php?err=2");
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
<?php include("includes/navigation.php");?>
<div class="container-fluid">
	<a href="data_number_output.php" class="btn btn-default form-control" style="margin-bottom: 5px;">KELUARAN</a> 
	<a href="games.php" class="btn btn-default form-control" style="margin-bottom: 5px;">GAMES</a> 
	<a href="transfer_history.php" class="btn btn-default form-control" style="margin-bottom: 5px;">TRANSAKSI</a> 
	<a href="memo_kelur.php" class="btn btn-default form-control" style="margin-bottom: 5px;">MEMO</a> 
	<a href="deposit_amount.php" class="btn btn-default form-control" style="margin-bottom: 5px;">DEPOSIT</a> 
	<a href="withdraw_amount.php" class="btn btn-default form-control" style="margin-bottom: 5px;">WITHDRAW</a> 
	<a href="logout.php" class="btn btn-default form-control" style="margin-bottom: 5px;">LOGOUT</a> </div>
<hr/>
<?php include("includes/footer.php");?>
</body> 
</html>