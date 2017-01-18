<?php require_once("includes/head.php");
	require_once("includes/check-authentication.php");
	$arrColokbebasCMS = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '31'"));
	$marketName = $_REQUEST['market'];
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$currentDate = date('Y-m-d');
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
	
	
	//echo "SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = 'Colok Bebas'"; exit;
	$arrBettingLimit = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = 'Colok Bebas'"));
	$minbetAmount = $arrBettingLimit['bt_min_bet_amount'];
	if(!isset($minbetAmount)) {
		$minbetAmount = 0;
	}
	
	$maxbetAmount = $arrBettingLimit['bt_max_bet_amount'];
	if(!isset($maxbetAmount)) {
		$maxbetAmount = 0;
	}
	if(isset($_REQUEST['key']) && $_REQUEST['key'] == "pColokBebas") {
		//$totalPAmount = $_REQUEST['totalPay'];
		//$modTotalPAmount = str_replace( ',', '', $totalPAmount);
		//echo $modTotalPAmount; exit;
		$availableBalace = $_REQUEST['avlbl_blnce'];
		
		/* Form input from the given code */
		$data = str_replace(' ', '', $_REQUEST['colbeb']);
		
		$market = $_REQUEST['market'];
		$period = $_REQUEST['period'];
		$gameType = $_REQUEST['gametype'];
					
		$inputs = [];
		$modTotalPAmount = 0;
		$path = explode('&msg=', $_SERVER['REQUEST_URI']);
		$url = $path[0];
		$rows = explode(',', trim($data));
		if(count($rows) > 0 && count($rows) <= 10) {
			foreach($rows as $row) {
				$columns = explode('#', $row);
				//Check row value should contain both lottery no and bet amount
				if(count($columns) == 2) {
					$lotteryNos = explode('*', $columns[0]);
					$betAmount = $columns[1];
					$is_amount = filter_var($betAmount, FILTER_VALIDATE_INT);
					//Bet amount should be an integer
					if($is_amount) {
						//Check bet amount with minimum and maximum bet amount
						if($minbetAmount && $betAmount < $minbetAmount) {
							header("Location:$url&msg=Min bet amount $minbetAmount");
							exit();
						}
						if($maxbetAmount && $betAmount > $maxbetAmount) {
							header("Location:$url&msg=Max bet amount $maxbetAmount");
							exit();
						}
						foreach($lotteryNos as $lottery) {
							//lottery number should be an integer
							$is_lottery = filter_var($lottery, FILTER_VALIDATE_INT);
							if(is_int($is_lottery)) {
								//Lottery number with in 9
								if(strlen($lottery) == 1 && $lottery <= 9) {
									//Check for duplicate lottery number.
									if(!isset($inputs[$lottery])) {
										/* Calculate the discount value */
										$arrDiscount = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$market."' and g_type = '".$gameType."'"));
										$discountPercentage = $arrDiscount['g_discount'];	
										
										$discount = ($betAmount*$discountPercentage)/100;
										$paybleAmount = $betAmount - $discount;
										$modTotalPAmount += $paybleAmount;
										$inputs[] = array('lotteryNo' => (int) $lottery, 'betAmount' => $betAmount, 'discount' => $discount, 'paybleAmount' => $paybleAmount);
									} else {
										header('Location:'.$url.'&msg=Lottery no should be unique');
										exit();
									}
								} else {
									header('Location:'.$url.'&msg=Lottery number less than or equal to 9');
									exit();
								}
							} else {
								
								header('Location:'.$url.'&msg=Inalid code!');
								exit();
							}
						}
					} else {
						header('Location:'.$url.'&msg=Invalid code!');
						exit();
					}
				} else {
					header('Location:'.$url.'&msg=Invalid code');
					exit();
				}
			}
		} else {
			header('Location:'.$url.'&msg=maximun bet allowed 10');
			exit();
		}
		//print_r($inputs);die;
		/**/
		$modTotalPAmount = $modTotalPAmount * 1 ;
		$availableBalace = $availableBalace * 1;
		
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$t=time();
		if($modTotalPAmount<$availableBalace) {
			
			foreach($inputs as $input) {
					$lotteryNumber = $input['lotteryNo'];
					$betAmount = $input['betAmount'];
					$modBetAmount = str_replace( ',', '', $betAmount);
					
					// echo $modBetAmount;
					$discount = $input['discount'];
					$modDiscount = str_replace( ',', '', $discount);
					// echo $modDiscount;
					$paybleAmount = $input['paybleAmount'];
					$modPaybleAmount = str_replace( ',', '', $paybleAmount);
					//echo $modPaybleAmount; exit;
					$purchaseDateTime = explode(" ", date('Y-m-d H:i:s'));
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
					'".$market."',
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
					
			} 
			
			include("calculate_referral.php");
			
			header("Location:".$url."&msg=success");
			exit();
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
		BET - COLOK BEBAS
		<br />
		PERIOD : <?php echo $period;?><hr />
		<form class="form-horizontal" method="post" action="" name="frm_shio">
			<input type="hidden" name="market" id="marketn" value="<?php echo $marketName;?>">
			<input type="hidden" name="period" value="<?php echo $period; ?>">
			<input type="hidden" name="avlbl_blnce" value="<?php echo $availableBalance;?>">
			<input type="hidden" name="gametype" id="gametypeC" value="Colok Bebas">
			<input type="hidden" name="key" value="pColokBebas">
			<?php echo $arrColokbebasCMS['cms_page_details']; ?>
			<hr />
			<div class="form-group"> 
					<small><mark>DONT REFRESH THIS PAGE  & Max bet 10 Record</mark></small><br />
				Example Correct : 1#10000  <small>atau </small>0*3#10000 <small>atau </small> 2*0#10000,5#3000<br />
			</div>
			<hr />
			<div class="form-group"> 
				<label class="col-xs-4 control-label">BET COLOK BEBAS</label> 
				<div class="col-xs-8"><input type="text" class="form-control" placeholder="BET COLOK BEBAS" name="colbeb" id="colbeb" value="" > </div> 
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