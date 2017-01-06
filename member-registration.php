<?php require_once("includes/head.php");
	$bank_list = mysql_query("SELECT * FROM `lottery_bank`");
	date_default_timezone_set("Asia/Kuala_Lumpur");
	if(isset($_REQUEST['submit'])){
		if(empty($_SESSION['register_captcha_code'] ) || strcasecmp($_SESSION['register_captcha_code'], $_POST['captcha_code_3']) != 0){  
			header("Location:member-registration.php?msg=captcha_error");
			
		}
		else{
			//$firstname = $_REQUEST['fname'];
			//$lastname = $_REQUEST['lname'];
			
			$firstname = '';
			$lastname = '';
			$username = $_REQUEST['username'];
			$password = md5($_REQUEST['password']);
			$phoneno = $_REQUEST['phone_number'];
			$email = $_REQUEST['email'];
			$bankname = $_REQUEST['bank_name'];
			$accountname = $_REQUEST['account_name'];
			$accountnumber = $_REQUEST['acc_number'];
			$referrals = $_REQUEST['referral'];
			$regDate = date('Y-m-d');
			
			$SQL_check = "SELECT member_id FROM lottery_member_registration WHERE member_bank_name='$bankname' AND member_account_name = '$accountname' AND member_account_no='$accountnumber'";
			$res_check = mysql_query($SQL_check);
			
			if(mysql_num_rows($res_check) > 0)
			{
				
				header("Location:member-registration.php?err=1");
				exit();
			}
			else{
				$sql_reg = "INSERT INTO `lottery_member_registration`(`member_fname`,`member_lname`,`member_username`,`member_password`,`member_phoneno`,`member_email`,`member_bank_name`,`member_account_name`,`member_account_no`,`member_referrals`,`meber_reg_date`)VALUES('".$firstname."','".$lastname."','".$username."','".$password."','".$phoneno."','".$email."','".$bankname."','".$accountname."','".$accountnumber."','".$referrals."','".$regDate."')";
				$res_reg = mysql_query($sql_reg);
				header("Location:member-registration.php?suc=1");
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
<script language="javascript">
function validate()
{
//--CEK USERNAME-------------------------------
	var x=document.forms["frm_register"]["username"].value;
	var regex=/^[a-zA-Z0-9]+$/;
	if (x==null || x=="")
	  {
		  alert("USERNAME SHOULD ISI");
		  document.forms["frm_register"]["username"].focus();
		  return false;
	  }
	 if (x.length < 5) {
		alert("USERNAME AT LEAST 6 CHARACTERS");
		document.forms["frm_register"]["username"].focus();
		
   		return false; // keep form from submitting
 	}
	if (!x.match(regex))
    {
        alert("USERNAME ONLY NUMBERS AND ABZAD");
		document.forms["frm_register"]["username"].focus();
        return false;
    }
//-PASSWORD-------------------
	var x = document.forms["frm_register"]["password"].value;
	var y = document.forms["frm_register"]["retype_pass"].value;
	
 	var regex=/^[a-zA-Z0-9]+$/;
	
	if (x.length < 5) {
		alert("PASSWORD AT LEAST 6 CHARACTERS Abzad and Figures");
		document.forms["frm_register"]["password"].focus();
		document.forms["frm_register"]["password"].select();
		
   		return false; // keep form from submitting
 	}
 
	if (!x.match(regex))
    {
        alert("PASSWORD ONLY NUMBERS AND ABZAD");
		document.forms["frm_register"]["password"].focus();
        return false;
    }
	
	if (x  != y) {
		alert("Password First not the same as a second password.");
		document.forms["frm_register"]["password"].focus();
		document.forms["frm_register"]["password"].select();
		
   		return false; // keep form from submitting
 	}
	
//--EMAIL-------------------------------
	var x=document.forms["frm_register"]["email"].value;
	var atpos=x.indexOf("@");
	var dotpos=x.lastIndexOf(".");
	if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
	  {
	  alert("EMAIL INVALID");
	  document.forms["frm_register"]["email"].focus();
	  return false;
	  }
//-NO HP-------------------
	var x = document.forms["frm_register"]["phone_number"].value;
 	var regex=/^[0-9]+$/;
	if (!x.match(regex))
    {
        alert("NO. ONLY PHONE NUMBERS");
		document.forms["frm_register"]["phone_number"].focus();
        return false;
    }
//-NO REK-------------------
	var x = document.forms["frm_register"]["acc_number"].value;
 	var regex=/^[0-9]+$/;
	if (!x.match(regex))
    {
        alert("NO. ONLY ACCOUNT NUMBERS");
		document.forms["frm_register"]["acc_number"].focus();
        return false;
    }
//--CEK NAMA PEMILIK REKENING-------------------------------
	var x=document.forms["frm_register"]["account_name"].value;
	var regex=/^[-_ a-zA-Z]+$/;
	if (x==null || x=="")
	  {
		  alert("ACCOUNT OWNER NAME SHOULD ISI");
		  document.forms["frm_register"]["account_name"].focus();
		  return false;
	  }
	if (!x.match(regex))
    {
        alert("ACCOUNT OWNER NAME ONLY ABZAD");
		document.forms["frm_register"]["account_name"].focus();
        return false;
    }
 //###############
}
</script>
<div style="padding:5px 10px 5px 10px"><a href="index.php">
<img src="../images/logo.png" width="95%" /></a>
<hr>
<div class="sucmsg alert alert-success" style="display:none;"></div>
<div class="errmsg alert alert-danger" style="display:none;"></div>
<form name="frm_register" id="member-form" method="POST" action="" role="form" class="form-horizontal" onsubmit="return validate();">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Username</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="username" name="username" placeholder="username" maxlength="25">
    </div>
  </div>
 
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="password" placeholder="Password" name="password" maxlength="25">
    </div>
  </div>
  
   <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Retype Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="retype_pass" placeholder="Re Password" name="retype_pass" maxlength="25">
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Mobile Number</label>
    <div class="col-sm-10">
      <input type="number" class="form-control" id="phone_number" name="phone_number" placeholder="Mobile Number" maxlength="20">
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-10">
      <input type="email" class="form-control" id="email" name="email" placeholder="anonymous@email.com" maxlength="35">
    </div>
  </div>
   <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Bank Name</label>
    <div class="col-sm-10">
		<select class="form-control" name="bank_name" id="bank_name">
			<?php while($row_bank = mysql_fetch_array($bank_list)){?>
				<option value="<?php echo $row_bank['bank_name'];?>"><?php echo $row_bank['bank_name'];?></option>
			<?php }?>
		</select>
  </div>
	</div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Account Name</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="account_name" name="account_name" placeholder="Account Name" maxlength="25">
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Account Number</label>
    <div class="col-sm-10">
      <input type="number" class="form-control" id="acc_number" name="acc_number" placeholder="Account Number" maxlength="25">
    </div>
  </div>
	<div class="form-group">
		<label for="referral" class="col-sm-2 control-label">Referrals:</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="referral" id="referral">
		</div>
	</div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label"></label>
    <div class="col-sm-10"> 
	  <img src="captcha.php?type=register"><br>
      <input type="text" class="form-control" id="captcha_code_3" placeholder="Security Captcha" name="captcha_code_3">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" value="Register"  class="btn btn-default" name="submit">REGISTER</button><br>
    </div>
  </div>
</form>
<?php if(isset($_REQUEST['suc'])=="1"){?>
	<script type="text/javascript">
		$(".sucmsg").show();
		$(".sucmsg").html("You have been successfully registered to our site.");
	</script>
<?php }?>
<?php if(isset($_REQUEST['err'])=="1"){?>
	<script type="text/javascript">
		$(".errmsg").show();
		$(".errmsg").html("Bank Name with Account Number already Exists.");
	</script>
<?php }?>

<?php if(isset($_REQUEST['msg'])=="captcha_error"){?> 
	<script type="text/javascript">
		$(".errmsg").show();
		$(".errmsg").html("The Validation Captcha code does not match!");
	</script>
<?php }?>
</div>
</body>
</html>