<?php require_once("includes/head.php");
$bank_list = mysql_query("SELECT * FROM `lottery_bank`");
if(isset($_REQUEST['submit'])){
	$firstname = $_REQUEST['fname'];
	$lastname = $_REQUEST['lname'];
	$username = $_REQUEST['username'];
	$password = md5($_REQUEST['password']);
	$phoneno = $_REQUEST['phone_number'];
	$email = $_REQUEST['email'];
	$bankname = $_REQUEST['bank_name'];
	$accountname = $_REQUEST['account_name'];
	$accountnumber = $_REQUEST['acc_number'];
	$referrals = $_REQUEST['referral'];
	
	$sql_reg = "INSERT INTO `lottery_member_registration`(`member_fname`,`member_lname`,`member_username`,`member_password`,`member_phoneno`,`member_email`,`member_bank_name`,`member_account_name`,`member_account_no`,`member_referrals`)VALUES('".$firstname."','".$lastname."','".$username."','".$password."','".$phoneno."','".$email."','".$bankname."','".$accountname."','".$accountnumber."','".$referrals."')";
	$res_reg = mysql_query($sql_reg);
  unset($_SESSION['lottery']['refusername']);
	header("Location:daftar.php?suc=1");
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once("includes/html_head.php");?>
</head>
<body>
<?php require_once("includes/header.php");?>
<div class="container-fluid main-body-area  menu-padding"  >
  <div class="container"> 
    <!--Start of Member Registration Page-->
    <div class="col-md-12 member_area">
      <div class="col-md-8 col-md-offset-2">
        <h2>Member Registration</h2>
        <div class="sucmsg" style="display:none; color:#090; font-weight:bold;"></div>
        <form name="frm" id="member-form" method="POST" action="" role="form">
          <div class="form-group">
            <label for="email">First Name * :</label>
            <input type="text" class="form-control" name="fname" id="fname">
          </div>
          <div class="form-group">
            <label for="email">Last Name * :</label>
            <input type="text" class="form-control" name="lname" id="lname">
          </div>
          <div class="form-group">
            <label for="email">User Name * :</label>
            <input type="text" class="form-control" name="username" id="username">
          </div>
          <div class="form-group">
            <label for="email">Password * :</label>
            <input type="password" class="form-control" name="password" id="password">
          </div>
          <div class="form-group">
            <label for="email">Retype Password * :</label>
            <input type="password" class="form-control" name="retype_pass" id="retype_pass">
          </div>
          <div class="form-group">
            <label for="email">Mobile Phone Number *:</label>
            <input type="text" class="form-control" name="phone_number" id="phone_number">
          </div>
          <div class="form-group">
            <label for="email">Email *:</label>
            <input type="text" class="form-control" name="email" id="email">
          </div>
          <div class="form-group">
            <label for="sel1">Bank Name *:</label>
            <select class="form-control" name="bank_name" id="bank_name">
              <option value="">Please Select</option>
              <?php while($row_bank = mysql_fetch_array($bank_list)){?>
             <option value="<?php echo $row_bank['bank_name'];?>"><?php echo $row_bank['bank_name'];?></option>
             <?php }?>
            </select>
          </div>
          <div class="form-group">
            <label for="email">Account Name *:</label>
            <input type="text" class="form-control" name="account_name" id="account_name">
          </div>
          <div class="form-group">
            <label for="email">Account Number * :</label>
            <input type="text" class="form-control" name="acc_number" id="acc_number">
          </div>
          <div class="form-group">
            <label for="email">Referrals:</label>
            <input type="text" class="form-control" name="referral" id="referral" value="<?php echo $_SESSION['lottery']['refusername'];?>" readonly>
          </div>
          <input type="submit" name="submit" value="Register" class="btn btn-default subbtn">
          <!--<button type="submit" class="btn btn-default subbtn">Register</button>-->
        </form>
      </div>
    </div>
    <!--Start of Member Registration Page-->
    
    <?php require_once("includes/footer.php");?>
    
    <!--end-of col-md-12--> 
    
  </div>
  <!--end-of container--> 
</div>
<?php if(isset($_REQUEST['suc'])=="1"){?>
<script type="text/javascript">
$(".sucmsg").show();
$(".sucmsg").html("You have been successfully registered to our site.");
</script>
<?php }?>
<!--end-of container-fluid main-body-area-->
</body>
</html>