<?php require_once("includes/head.php");
	$member_id = $_REQUEST['member_id'];
	
	$marketName = $_SESSION['marketName'];
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$resReferrer = mysql_query("SELECT * FROM lottery_member_registration WHERE member_id = '".$member_id."'");
	$countReferrer = mysql_num_rows($resReferrer);
	$arrReferrer = mysql_fetch_array($resReferrer);
	$refererName = $arrReferrer['member_referrals'];
	if(isset($refererName)) {
		$arrMemberInfo = mysql_fetch_array(mysql_query("SELECT * FROM lottery_member_registration WHERE member_username = '".$refererName."'"));
		$uid = $arrMemberInfo['member_id'];
	}
	/////////////////////////// Fetch Percentage //////////////////////////////////////////
	$arrPercentage = mysql_fetch_array(mysql_query("SELECT * FROM lottery_ref_percentage WHERE lrp_id = '1'"));
	$percentage = $arrPercentage['lrp_percentage'];
	
	/////////////////////////// Fetch Percentage //////////////////////////////////////////
	
	//////////////////////////////// CHECK PURCHASE /////////////////////////////////
	
	if(isset($refererName) && $refererName!=''){
		$resPurchase = mysql_query("SELECT * FROM lottery_purchase WHERE p_member_id = '".$member_id."' and p_status = '1'");
		while($arrPurchase = mysql_fetch_array($resPurchase)) {
			$purchaseID = $arrPurchase['p_id'];
			$betAmount = $arrPurchase['p_bet_amount'];
			$referalAmount = ($betAmount * $percentage)/100;
			$resReferralAmount = mysql_query("INSERT INTO lottery_referral_amount(r_amount_id, r_member_id, r_purchase_id, r_amount,r_date) VALUES('','".$uid."','".$purchaseID."','".$referalAmount."','".date('Y-m-d')."')");
		}
	}
	//////////////////////////////// CHECK PURCHASE /////////////////////////////////
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once("includes/html_head.php");?>
	</head>
	<body>
		<?php require_once("includes/header.php");?>
		<div class="container-fluid main-body-area  menu-padding">
			<div class="container">	
				<div class="col-md-12 mrg-top-20 game-page-area" >
					<?php require_once("includes/left_panel_game.php");?>
					
					<div class="col-md-9 rdc-padding">
						<div class="information-page-area">
							<?php require_once("includes/top_game_category.php"); ?>
							<div class="col-md-12 result-bg-area">
								
								<br/><br/><br/>
								<h2 style="color:#096;">Thank you for purchasing the lottery ticket from Lottery.com</h2>
								<p>We appreciate your interest for buying lottery tickets.</p>
							</div>
							
						</div>
						
      
						<div class="col-md-12 margin50">
							<?php require_once("includes/bottom_box.php");?>
						</div>
					</div>
				</div>
				
				<?php require_once("includes/footer.php");?>
				<!--end-of container--> 
			</div>
			<!--end-of container-fluid main-body-area--> 
		</body>
	</html>	