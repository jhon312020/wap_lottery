<?php require_once("includes/head.php");
	require_once("includes/check-authentication.php");
	$mem_id = $_SESSION['lottery']['memberid'];
	$member_info = mysql_fetch_array(mysql_query("SELECT * FROM lottery_member_registration WHERE member_id = '".$mem_id."'"));
	if(isset($_REQUEST['key']) && $_REQUEST['key'] == 'withdrawAmount') {
		
		$res_Check = mysql_query("SELECT w_id FROM lottery_amount_withdraw WHERE w_status = '0' AND w_from_mem_id = '".$_SESSION['lottery']['memberid']."'");
		
		if(mysql_num_rows($res_Check) > 0)
		{
			
			header("Location:withdraw_amount.php?msg=errorWithdraw");
			exit();
		}
		else
		{
			
			$bankDetails = $_REQUEST['accountName']." / ".$_REQUEST['bankName']." / ".$_REQUEST['accountNumber'];
			$withdraw_amount = $_REQUEST['withdrawl_amount'];
			$withdraw_amount = str_replace(',', '', $withdraw_amount);
			
			$resWithdraw = mysql_query("INSERT INTO lottery_amount_withdraw(w_id, w_from_mem_id, w_from_bank, w_amount, w_date_time, w_status, w_approved_by, w_approved_on) VALUES('','".$_SESSION['lottery']['memberid']."','".$bankDetails."', '".$withdraw_amount."','".date('Y-m-d H:i:s')."','0','','')");
			header("Location:withdraw_amount.php?msg=successWithdraw");
			exit();
			
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once("includes/html_head.php");?>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#withdrawl_amount').number( true, 0 );
				$("#withdrawl_amount").blur(function(){
					var amount = parseFloat($("#withdrawl_amount").val());
					//alert(amount);
					var totalBalance = parseFloat($("#credit_balance").val());
					//alert(totalBalance);
					if(amount <50000) {
						alert("Minimum Value is : 50000");
						$("#withdrawl_amount").val('');
					}
					if(amount>totalBalance) {
						alert("You don't have that much balance to withdrawl");
						$("#withdrawl_amount").val('');
					}
				});
				$("#withdrawl_amount").keydown(function (e) {
					// Allow: backspace, delete, tab, escape, enter and .
					if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
					// Allow: Ctrl+A, Command+A
					(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
					// Allow: home, end, left, right, down, up
					(e.keyCode >= 35 && e.keyCode <= 40)) {
						// let it happen, don't do anything
						return;
					}
					// Ensure that it is a number and stop the keypress
					if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
						e.preventDefault();
					}
				});
				
				$("#member-form-update").submit(function(){
					var amount = parseFloat($("#withdrawl_amount").val());
					//alert(amount);
					var totalBalance = parseFloat($("#credit_balance").val());
					//alert(totalBalance);
					if(amount <50000) {
						alert("Minimum Value is : 50000");
						$("#withdrawl_amount").val('');
						return false;
					}
					if(amount>totalBalance) {
						alert("You don't have that much balance to withdrawl");
						$("#withdrawl_amount").val('');
						return false;
					}
				});
			});
		</script>
	</head>
	<body>
		<?php require_once("includes/navigation.php");?>
		
	
<div class="container-fluid">
<a href="my-account.php" class="btn btn-danger btn-xs" style="margin-bottom: 5px;">HOME</a> 

<br /><br />
<?php 
	$resBankInfo = mysql_query("SELECT * FROM lottery_bank WHERE bank_name = '".$member['member_bank_name']."'");
	$arrBank = mysql_fetch_array($resBankInfo);
	$totalCreditBalance = $remainingBalance + $winSum;
?>
<div class="row">
  <div class="col-md-12 bg-warning text-center table-bordered">
  	<strong><U>REK. TUJUAN:</U></strong>
	<div align="center">
	<table>
		<tr>
			<td style="padding:2px 5px 2px 5px">BANK</TD><TD>:</TD><TD style="padding:2px 5px 2px 5px"><?php echo $member['member_bank_name']; ?></td>
		</TR>
		<tr>
			<td style="padding:2px 5px 2px 5px">NO. REK</TD><TD>:</TD><TD style="padding:2px 5px 2px 5px"><?php echo $member['member_account_no']; ?></td>
		</tr>
		<tr>
			<td style="padding:2px 5px 2px 5px">AT. NAMA</TD><TD>:</TD><TD style="padding:2px 5px 2px 5px"><?php echo $member['member_username']; ?></td>
		</tr>
	</table>
  </div>
</div>
</div>
<br />
<div class="error alert alert-danger" style="display:none;" role="alert">ggg</div>
<div class="success alert alert-success" style="display:none;" role="alert">ggg</div>
<form id="member-form-update" method="post" action="" class="form-horizontal">
	<input type="hidden" name="key" value="withdrawAmount">
	<input type="hidden" name="accountName" value="<?php echo $member_info['member_account_name']; ?>">
	<input type="hidden" name="bankName" value="<?php echo $member_info['member_bank_name']; ?>">
	<input type="hidden" name="accountNumber" value="<?php echo $member_info['member_account_no']; ?>">
	<input type="hidden" name="balance" id="credit_balance" value="<?php echo $totalCreditBalance;?>">
	<div class="form-group">
		<label for="inputPassword3" class="col-sm-2 control-label">Min Withdraw</label>
		<div class="col-sm-10">
		  <input type="text" class="form-control" name="mdepo" readonly="readonly" value="50000.000">
		</div>
 	</div>
	<div class="form-group">
		<label for="inputPassword3" class="col-sm-2 control-label">DATE</label>
		<div class="col-sm-10">
		  <input type="text" class="form-control" name="tanggal" readonly="readonly" value="<?php echo Date('Y-m-d H:i:s'); ?>">
		</div>
 	</div>
  <div class="form-group">
		<label for="inputPassword3" class="col-sm-2 control-label">Amount</label>
		<div class="col-sm-10">
		  <input type="amount" class="form-control" id="withdrawl_amount" placeholder="Only Numeric" name="withdrawl_amount" maxlength="15">
		</div>
 	</div>
	<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" name="submit" value="Submit" class="btn btn-default" name="submit">Request Withdraw</button><br>
    </div>
  </div>
</form>
</div>
<hr/>		
		<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == "successWithdraw"){?>
			<script type="text/javascript">
				$('.success').show();
				$('.success').html("<p>Thank You,</p><p>You have to wait for admin approval.</p>");
			</script>
		<?php }?>
		
		
		<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == "errorWithdraw"){?>
			<script type="text/javascript">
				$('.error').show();
				$('.error').html("<p>Error ! You can't send new request</p>");
				
				alert('You need to wait for admin to process old request.');
			</script>
		<?php } ?>
		<hr/>
		<?php include("includes/footer.php");?>
	</body>
</html>