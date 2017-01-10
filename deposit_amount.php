<?php 
	require_once("includes/head.php");
	require_once("includes/check-authentication.php");
	$mem_id = $_SESSION['lottery']['memberid'];
	date_default_timezone_set("Asia/Kuala_Lumpur");
	//echo "SELECT * FROM lottery_bank WHERE bank_status = '1'"; exit;
	$resBankInfo = mysql_query("SELECT * FROM lottery_bank WHERE bank_status = '1'");
	if(isset($_REQUEST['key']) && $_REQUEST['key'] == 'depositAmount') {
	
		$res_Check = mysql_query("SELECT deposit_id FROM lottery_amount_deposit WHERE deposit_status = '0' AND deposit_mem_id = '".$_SESSION['lottery']['memberid']."'");
		
		if(mysql_num_rows($res_Check) > 0)
		{
			
			header("Location:deposit_amount.php?msg=errorDeposit");
			exit();
		}
		else
		{
			//print_r($_REQUEST); exit;
			$fund_amount = $_REQUEST['amount'];
			
			$fund_amount = str_replace(',', '', $fund_amount);

			$to_bank = $_REQUEST['account_details'];
			
			$amountUpdate = mysql_query("INSERT INTO lottery_amount_deposit(
			deposit_mem_id, 
			deposit_to_bank, 
			deposit_amount, 
			deposit_date_time,
			deposit_status,
			approved_by,
			approved_on) VALUES(
			'".$_SESSION['lottery']['memberid']."',
			'".$to_bank."', 
			'".$fund_amount."',
			'".date('Y-m-d h:i:s')."',
			'0',
			'',
			'".date('Y-m-d h:i:s')."')");
			header("Location:deposit_amount.php?msg=successDeposit");
			exit();
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once("includes/html_head.php");?>
		<script>
		$(document).ready(function(){
			$("#member-form-update").submit(function(){
					var amount = $("#amountDeposit").val();
					amount *= 1;
					if(amount < 50000) {
						alert("Minimum Value is : 50000");
						$("#amountDeposit").val('');
						return false;
					}
				});
			$("#amountDeposit").keydown(function (e) {
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
		});
		</script>
	</head>
	<body>
		<?php require_once("includes/navigation.php");?>
		<!--end of page head!-->
		
<div class="container-fluid">
<a href="my-account.php" class="btn btn-danger btn-xs" style="margin-bottom: 5px;">HOME</a> 

<br /><br />
<?php 
	$resBankInfo = mysql_query("SELECT * FROM lottery_bank WHERE bank_name = '".$member['member_bank_name']."'");
	$arrBank = mysql_fetch_array($resBankInfo);
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
	<input type="hidden" name="key" value="depositAmount">
	<input type="hidden" name="account_details" id="account_details" value="<?php echo $arrBank['bank_name']." / ". $arrBank['bank_account_no']." - ".$arrBank['bank_account_name'];?>" />
	<div class="form-group">
		<label for="inputPassword3" class="col-sm-2 control-label">Min Deposit</label>
		<div class="col-sm-10">
		  <input type="text" class="form-control" name="mdepo" readonly="readonly" value="50000.000">
		</div>
 	</div>
	<div class="form-group">
		<label for="inputPassword3" class="col-sm-2 control-label">TANGGAL</label>
		<div class="col-sm-10">
		  <input type="text" class="form-control" name="tanggal" readonly="readonly" value="<?php echo Date('Y-m-d H:i:s'); ?>">
		</div>
 	</div>
  <div class="form-group">
		<label for="inputPassword3" class="col-sm-2 control-label">Jumlah</label>
		<div class="col-sm-10">
		  <input type="text" class="form-control" id="amountDeposit" placeholder="Only Numeric" name="amount" maxlength="15">
		</div>
 	</div>
	<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" name="submit" value="Submit" class="btn btn-default" name="submit">Request Deposit</button><br>
    </div>
  </div>
</form>
</div>
<hr/>
	<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == "successDeposit"){?>
		<script type="text/javascript">
			$('.success').show();
			$('.success').html("<p>Terima kasih,</p><p>Bagian Keuangan kami akan melakukan pengecekkan , dan akan segera menambah kredit bila Dana sudah </p>");
		</script>
	<?php } ?>
	<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == "errorDeposit"){?>
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