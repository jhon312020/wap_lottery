<?php require_once("includes/head.php");
	require_once("includes/check-authentication.php");
	$marketName = $_REQUEST['market'];
	//date_default_timezone_set("Asia/Kuala_Lumpur");
	//$currentDate = date('Y-m-d');
	//$currentYear = date('Y');
	
	$prevDate = date('Y-m-d', strtotime('-1 day'));
	if($marketName == "Sandsmacao") {
		$shortCodeMarket = "SM";
		} elseif($marketName == "Sydney") {
		$shortCodeMarket = "SD";
		}elseif($marketName == "Sabang") {
		$shortCodeMarket = "SB";
		}elseif($marketName == "Singapore") {
		$shortCodeMarket = "SG";
		}elseif($marketName == "Johor") {
		$shortCodeMarket = "JH";
		}else{
		$shortCodeMarket = "HK";
	}
	//////////////////////////////////// C H E C K   I F   A N Y    R E S U L T    E X I S T   /////////////////////////
	$resCheckResult = mysql_query("SELECT * FROM lottery_win_number WHERE wn_market = '".$marketName."'");
	$countResult = mysql_num_rows($resCheckResult);
	$i = 1;
	if($countResult == 0) {
		$period =  $shortCodeMarket ."-".str_pad($i, 3, '0', STR_PAD_LEFT);
	}
	if($countResult == 1 || $countResult > 1) {
		$period =  $shortCodeMarket ."-".str_pad($i+$countResult, 3, '0', STR_PAD_LEFT);
	}
	//////////////////////////////////////  C H E C K   I F   A N Y    R E S U L T    E X I S T ///////////////////////////////////
	
	$arrShioDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '38'"));
	if(isset($_REQUEST['key']) && $_REQUEST['key'] == "pShio") {
		$availableBalace = $_REQUEST['avlbl_blnce'];
		$availableBalace = $availableBalace * 1;
		
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$t=time();
		
		$path = explode('&msg=', $_SERVER['REQUEST_URI']);
		$url = $path[0];	
		$betAmount = $modTotalPAmount = $_REQUEST['bet'];
		$is_amount = filter_var($betAmount, FILTER_VALIDATE_INT);
		if($modTotalPAmount<$availableBalace) {
				if($is_amount && $betAmount > 0) {
					$gameType = $_REQUEST['gametype'];
					$arrDiscount = mysql_fetch_array(mysql_query("SELECT * FROM  lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '".$gameType."'"));
					$discountPercentage = $arrDiscount['g_discount'];
					$discount = ($betAmount*$discountPercentage)/100;
					$paybleAmount = $betAmount - $discount;
		
					$category = $_REQUEST['market'];
					$period = $_REQUEST['period'];
					$lotteryNumber = $_REQUEST['shio'];
					$betAmount = $betAmount;
					$modBetAmount = str_replace( ',', '', $betAmount);
					$modDiscount = str_replace( ',', '', $discount);
					$modPaybleAmount = str_replace( ',', '', $paybleAmount);
					$purchaseDateTime = explode(" ",date('Y-m-d H:i:s'));
					$purchaseDate = $purchaseDateTime['0'];
					$purchaseTime = $purchaseDateTime['1'];
					
						$resPurchase = mysql_query("INSERT INTO lottery_purchase(
						p_member_id, 
						p_category, 
						p_period, 
						p_gametype, 
						p_lottery_no, 
						p_position, 
						p_position2, 
						p_crush_type, 
						p_bet_amount, 
						p_discount, 
						p_payble_amount, 
						p_date, 
						p_time,
						p_uniq_time, 
						p_status,
						p_win_count)VALUES(
						'".$_SESSION['lottery']['memberid']."',
						'".$category."',
						'".$period."',
						'".$gameType."',
						'".$lotteryNumber."',
						'',
						'',
						'',
						'".$modBetAmount."',
						'".$modDiscount."',
						'".$modPaybleAmount."',
						'".$purchaseDate."',
						'".$purchaseTime."',
						'".$t."',
						'1',
						'0')");
						
					include("calculate_referral.php");
			
					header("Location:".$url."&msg=success");
					exit();
				} else {
					header("Location:".$url."&msg=bet amount should be greater than zero");
					exit();
				}
			} else {
			header("Location:".$url."&msg=You don't have enough balance for purchasing lottery. Please deposit some amount to you account.");
			exit();
		} 
		
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once("includes/html_head.php");?>
	</head>
	<body>
		<?php require_once("includes/navigation.php");?>
		<div class="container-fluid">
		<a href="my-account.php" class="btn btn-danger btn-xs" style="margin-bottom: 5px;">HOME</a>
		---
		<a href="games.php" class="btn btn-danger btn-xs" style="margin-bottom: 5px;">GAMES</a> 
		<br /><br />
		<?php if(isset($_REQUEST['msg'])) { ?>
			<?php 
			$msg = $_REQUEST['msg'];
			if($msg == 'success') {
			?>
				<div class="alert alert-success" >Thank you for purchasing the lottery ticket from Lottery.com</div>
			<?php } else { ?>
				<div class="alert alert-danger" ><?php echo $_REQUEST['msg']; ?></div>
		<?php } } ?>
		<?php $availableBalance = $remainingBalance + $winSum; ?>
		TARUHAN - SHIO
		<br />
		PERIOD : <?php echo $period;?><hr />
		<form class="form-horizontal" method="post" action="" name="frm_shio">
			<input type="hidden" name="market" id="marketn" value="<?php echo $marketName;?>">
			<input type="hidden" name="period" value="<?php echo $period; ?>">
			<input type="hidden" name="avlbl_blnce" value="<?php echo $availableBalance;?>">
			<input type="hidden" name="gametype" id="gametypeC" value="Shio">
			<input type="hidden" name="key" value="pShio">
			<?php echo $arrShioDetails['cms_page_details']; ?>
			<hr />
			<small><mark>DONT REFRESH THIS PAGE</mark></small><br />
			<hr />
			<div class="form-group"> 
				<div class="col-xs-6"> 
					<select name="shio" class="form-control"> 
					<option value="1">Monyet </option>
					<option value="2">Kambing</option>
					<option value="3">Kuda</option>
					<option value="4">Ular</option>
					<option value="5">Naga</option>
					<option value="6">Kelinci</option>
					<option value="7">Harimau</option>
					<option value="8">Kerbau</option>
					<option value="9">Tikus</option>
					<option value="10">Babi</option>
					<option value="11">Anjing</option>
					<option value="12">Ayam</option>	
					</select>
				</div>
				<div class="col-xs-6"> 
					<input type="text" class="form-control" placeholder="BET" name="bet" id="bet" value="" > 
				</div> 
		</div>
		<div class="form-group"> 
			<div class="col-sm-12">
			<input type="submit" class="btn btn-warning form-control" name="submit" id="submit" value="BELI" onclick="return confirm('PROSES TARUHAN INI???')"/> 
			</div> 
		</div>
		</form></div>
		<hr/>			
		<?php include("includes/footer.php");?>
	</body>
</html>		