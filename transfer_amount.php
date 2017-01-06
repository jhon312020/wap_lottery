<?php require_once("includes/head.php");
	require_once("includes/check-authentication.php");
	$mem_id = $_SESSION['lottery']['memberid'];
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$member_info = mysql_fetch_array(mysql_query("SELECT * FROM lottery_member_registration WHERE member_id = '".$mem_id."'"));
	$resotherGame = mysql_query("SELECT * FROM lottery_other_game");
	if(isset($_REQUEST['key']) && $_REQUEST['key'] == 'transferAmount') {
		
		
		$transfer_type = $_REQUEST['transfer_type'];
		
		if($transfer_type == '1') {
			$from_game = "togel";
			$to_game = $_REQUEST['game_name'];
		}
		if($transfer_type == '2') {
			$from_game = $_REQUEST['game_name'];
			$to_game = "togel";
		}
			
		
		$res_Check = mysql_query("SELECT t_id FROM lottery_amount_transfer WHERE t_status = '0' AND t_from_game = '$from_game' AND t_to_game = '$to_game' AND t_member_id = '".$_SESSION['lottery']['memberid']."'");
		
		if(mysql_num_rows($res_Check) > 0)
		{
			
			header("Location:transfer_amount.php?msg=errorTransfer");
			exit();
		}
		else
		{
			
			$transfer_amount = $_REQUEST['transfer_amount'];
			$transfer_amount = str_replace(',', '', $transfer_amount);
			
			$details = $_REQUEST['details'];
			$resTransfer= mysql_query("INSERT INTO lottery_amount_transfer(
			t_member_id, 
			t_type, 
			t_from_game, 
			t_to_game, 
			t_amount, 
			t_details, 
			t_datetime, 
			t_approve_by, 
			t_approve_on)VALUES(
			'".$_SESSION['lottery']['memberid']."',
			'".$transfer_type."',
			'".$from_game."',
			'".$to_game."',
			'".$transfer_amount."',
			'".$details."',
			'".date('Y-m-d h:i:s')."',
			'',
			'".date('Y-m-d h:i:s')."')");
			header("Location:transfer_amount.php?msg=successTransfer");
			exit();
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once("includes/html_head.php");?>
		<!--<script type="text/javascript">
			$(document).ready(function(){
			$("#transfer_typeLain").click(function(){
			$("#gameAll").show();
			});
			$("#transfer_typeTogel").click(function(){
			$("#gameAll").hide();
			});
		});
		</script>-->
		<script type="text/javascript">
			$(document).ready(function(){
				
				$('#transfer_amount').number( true, 0 );
				
				$("#transfer_amount").blur(function(){
					var amount = parseFloat($("#transfer_amount").val());
					var totalBalance = parseFloat($("#credit_balance").val());     
					if(amount > totalBalance) {
						alert("You don't have that much balance to transfer");
						$("#transfer_amount").val('');
					}
				});
			});
		</script>
	</head>
	<body>
		<?php require_once("includes/header.php");?>
		<!--end of page head!-->
		
		<div class="container-fluid main-body-area  menu-padding">
			<div class="container">
				<div class="col-md-12  scroll-text-area  rdc-padding">
					<div class="col-md-2 arrow-right">
						<p style="margin-top:25px; color:#fff;">Information</p>
					</div>
					<div class="col-md-10 scr-pd-left">
						<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor Aenean massa.</p>
					</div>
				</div>
				<!--Start of game Page-->
				<div class="col-md-12 mrg-top-20 game-page-area" >
					<?php require_once("includes/left_panel_game.php");?>
					<?php 
						$totalCreditBalance = $remainingBalance + $winSum;
					?>
					<!--end-of col-md-3-->
					<div class="col-md-9 rdc-padding">
						<div class="information-page-area" style="padding:25px 15px;">
							<div class="col-md-12 profileForm">
								<div class="error" style="display:none; color:#ee3147; font-weight:bold; padding:5px; text-align:center;"></div>
								
								<div class="success" style="display:none; color:#093; font-weight:bold; padding:5px; text-align:center;"></div>
								
								
								<h2>Form Transfer</h2>
								<form name="frm" id="member-form-update" method="POST" action="" role="form" enctype="multipart/form-data">
									<input type="hidden" name="key" value="transferAmount">
									<input type="hidden" name="balance" id="credit_balance" value="<?php echo $totalCreditBalance;?>">
									<div class="form-group">
										<label for="email">Transfer Type :</label>
										<label class="radio-inline"><input type="radio" name="transfer_type" id="transfer_typeLain" value="1" checked>Transfer ke Game Lain</label>
										<label class="radio-inline"><input type="radio" name="transfer_type" id="transfer_typeTogel" value="2">Transfer kembali ke Togel</label>
									</div> 
									<div class="form-group">
										<label for="email">Game Transfer :</label>
										<select name="game_name" id="game_name" class="form-control">
											<option value="">Please Select</option>
											<?php while($arrOtherGame = mysql_fetch_array($resotherGame)) {?>
												<option value="<?php echo $arrOtherGame['g_name']; ?>"><?php echo $arrOtherGame['g_name']; ?></option>
											<?php }?>
										</select>
									</div>
									<div class="form-group">
										<label for="email">Jumlah Transfer:</label>
										<input type="text" class="form-control" name="transfer_amount" id="transfer_amount">
									</div>
									
									<div class="form-group">
										<label for="email">Pesan/keterangan:</label>
										<textarea name="details" id="details" class="form-control"></textarea>
									</div>
									<input type="submit" name="submit" value="Submit" class="btn btn-default subbtn" style="font-size:15px;">
								</form>
							</div>
							
							<div class="clear"></div>
						</div>
						<!--end-of information-page-area--> 
						
						
						<div class="col-md-12 margin50">
							<?php require_once("includes/bottom_box.php");?>
						</div>
					</div>
					
					<!--end-of col-md-9--> 
					
					<!--end of col-md-12-->
					<div class="clear"></div>
				</div>
				<!--end of game-area-->
				<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == "successTransfer"){?>
					<script type="text/javascript">
						$('.success').show();
						$('.success').html("<p>Thank You,</p><p>You have to wait for admin approval.</p>");
					</script>
				<?php }?>
				<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == "errorTransfer"){?>
					<script type="text/javascript">
						
						$('.error').show();
						$('.error').html("<p>Error ! You can't send new request</p>");
						
						alert('You need to wait for admin to process old request.');
					</script>
				<?php }?>
				
				<?php require_once("includes/footer.php");?>
				<!--end-of col-md-12--> 
				
			</div>
			
			<!--end-of container--> 
		</div>
		<!--end-of container-fluid main-body-area-->
		
	</body>
</html>