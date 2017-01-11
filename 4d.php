<?php require_once("includes/head.php");
	require_once("includes/check-authentication.php");
	$arr4dDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '4'"));
	$marketName = $_REQUEST['market'];
	$_SESSION['marketName'] = $marketName;
	//date_default_timezone_set("Asia/Kuala_Lumpur");
	//$currentDate = date('Y-m-d');
	//$prevDate = date('Y-m-d', strtotime('-1 day'));
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
	
	
	$arrBettingLimit4d = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = '4D'"));
	$arrdiscountPercentage4d = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_type = '4D' and g_market_name = '".$marketName."'"));
	if($arrdiscountPercentage4d['g_kei'] == '0') {
		$discountPercentage4d = $arrdiscountPercentage4d['g_discount'];
	} else {
		$discountPercentage4d = $arrdiscountPercentage4d['g_kei'];
	}
	$minbetAmount4d = $arrBettingLimit4d['bt_min_bet_amount'];
	$maxbetAmount4d = $arrBettingLimit4d['bt_max_bet_amount'];
	if(!isset($minbetAmount4d)) {
		$minbetAmount4d = 0;
	}
	if(!isset($maxbetAmount4d)) {
		$maxbetAmount4d = 0;
	}
	
	$arrBettingLimit3d = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = '3D'"));
	$arrdiscountPercentage3d = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_type = '3D' and g_market_name = '".$marketName."'"));
	if($arrdiscountPercentage3d['g_kei'] == '0') {
		$discountPercentage3d = $arrdiscountPercentage3d['g_discount'];
	} else {
		$discountPercentage3d = $arrdiscountPercentage3d['g_kei'];
	}
	$minbetAmount3d = $arrBettingLimit3d['bt_min_bet_amount'];
	$maxbetAmount3d = $arrBettingLimit3d['bt_max_bet_amount'];
	if(!isset($minbetAmount3d)) {
		$minbetAmount3d = 0;
	}
	if(!isset($maxbetAmount3d)) {
		$maxbetAmount3d = 0;
	}
	
	
	
	$arrBettingLimit2d = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = '2D'"));
	$arrdiscountPercentage2d = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_type = '2D' and g_market_name = '".$marketName."'"));
	if($arrdiscountPercentage2d['g_kei'] == '0') {
		$discountPercentage2d = $arrdiscountPercentage2d['g_discount'];
	} else {
		$discountPercentage2d = $arrdiscountPercentage2d['g_kei'];
	}
	$minbetAmount2d = $arrBettingLimit2d['bt_min_bet_amount'];
	$maxbetAmount2d = $arrBettingLimit2d['bt_max_bet_amount'];
	if(!isset($minbetAmount2d)) {
		$minbetAmount2d = 0;
	}
	if(!isset($maxbetAmount2d)) {
		$maxbetAmount2d = 0;
	}
	
	
	
	$arrBettingLimit2dd = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = '2D D'"));
	$arrdiscountPercentage2dd = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_type = '2D D' and g_market_name = '".$marketName."'"));
	if($arrdiscountPercentage2dd['g_kei'] == '0') {
		$discountPercentage2dd = $arrdiscountPercentage2dd['g_discount'];
	} else {
		$discountPercentage2dd = $arrdiscountPercentage2dd['g_kei'];
	}
	$minbetAmount2dd = $arrBettingLimit2dd['bt_min_bet_amount'];
	$maxbetAmount2dd = $arrBettingLimit2dd['bt_max_bet_amount'];
	if(!isset($minbetAmount2dd)) {
		$minbetAmount2dd = 0;
	}
	if(!isset($maxbetAmount2dd)) {
		$maxbetAmount2dd = 0;
	}
	
	$arrBettingLimit2dt = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = '2D T'"));
	$arrdiscountPercentage2dt = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_type = '2D T' and g_market_name = '".$marketName."'"));
	if($arrdiscountPercentage2dt['g_kei'] == '0') {
		$discountPercentage2dt = $arrdiscountPercentage2dt['g_discount'];
	} else {
		$discountPercentage2dt = $arrdiscountPercentage2dt['g_kei'];
	}
	$minbetAmount2dt = $arrBettingLimit2dt['bt_min_bet_amount'];
	$maxbetAmount2dt = $arrBettingLimit2dt['bt_max_bet_amount'];
	
	if(!isset($minbetAmount2dt)) {
		$minbetAmount2dt = 0;
	}
	if(!isset($maxbetAmount2dt)) {
		$maxbetAmount2dt = 0;
	}
	
	$arrBettingLimit2db = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = '2D B'"));
	$arrdiscountPercentage2db = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_type = '2D B' and g_market_name = '".$marketName."'"));
	if($arrdiscountPercentage2db['g_kei'] == '0') {
		$discountPercentage2db = $arrdiscountPercentage2db['g_discount'];
	} else {
		$discountPercentage2db = $arrdiscountPercentage2db['g_kei'];
	}
	$minbetAmount2db = $arrBettingLimit2db['bt_min_bet_amount'];
	$maxbetAmount2db = $arrBettingLimit2db['bt_max_bet_amount'];
	if(!isset($minbetAmount2db)) {
		$minbetAmount2db = 0;
	}
	if(!isset($maxbetAmount2db)) {
		$maxbetAmount2db = 0;
	}
	
	
	if(isset($_REQUEST['key']) && $_REQUEST['key'] == 'purchase4d') {
		$availableBalace = $_REQUEST['avlbl_blnce'];
		$availableBalace = $availableBalace * 1;
		
		/* Form input from the given code */
		$data = str_replace(' ', '', $_REQUEST['macau']);
		
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
						//Bet amount should be greater than or equal to minimum bet amount
						if($betAmount >= $minbetAmount) {
							foreach($lotteryNos as $lottery) {
								//lottery number should be an integer
								if(ctype_digit(strval($lottery))) {
									//Lottery number with in 9
									$duplicate = str_split($lottery);
									$lotteryLength = strlen($lottery);
									if($lotteryLength <= 4)) {
										switch($lotteryLength) {
											case 4:
												$arrdiscountPercentage = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_type = '4D' and g_market_name = '".$market."'"));
											case 2:	
												$arrdiscountPercentage = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_type = '4D' and g_market_name = '".$market."'"));
										}
										/* Calculate the discount value */
										if($arrdiscountPercentage['g_kei'] == '0') {
											$discountPercentage = $arrdiscountPercentage['g_discount'];
										} else {
											$discountPercentage = $arrdiscountPercentage['g_kei'];
										}
										$discount = ($betAmount*$discountPercentage)/100;
										$paybleAmount = $betAmount - $discount;
										$modTotalPAmount += $paybleAmount;
										$inputs[] = array('lotteryNo' => $lottery, 'betAmount' => $betAmount, 'discount' => $discount, 'paybleAmount' => $paybleAmount);
										
									} else {
										header('Location:'.$url.'&msg=Invalid code!');
										exit();
									}
								} else {
									
									header('Location:'.$url.'&msg=Inalid code!');
									exit();
								}
							}
						} else {
							header("Location:$url&msg=Min bet amount $minbetAmount");
							exit();
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
		
		
		//echo $availableBalace; exit;
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$t=time();
		
		if($modTotalPAmount<$availableBalace) {
			foreach($inputs as $input) {
				$category = $_REQUEST['market'];
				$period = $_REQUEST['period'];
				$gameType = $_REQUEST['gametype'][$i];
				$digit = $_REQUEST['d1'][$i].$_REQUEST['d2'][$i].$_REQUEST['d3'][$i].$_REQUEST['d4'][$i]; 
				$betAmount = $input['betAmount'];
				//echo $betAmount; exit;
				$modBetAmount = str_replace( ',', '', $betAmount);
				//echo $modBetAmount; exit;
				$discount = $input['discount'];
				$modDiscount = str_replace( ',', '', $discount);
				//echo $discount; 
				//echo $modDiscount; exit;
				$paybleAmount = $input['paybleAmount'];
				
				$modPaybleAmount = str_replace( ',', '', $paybleAmount);
				//echo $modPaybleAmount; exit;
				$purchaseDateTime = explode(" ",date('Y-m-d h:i:s'));
				$purchaseDate = $purchaseDateTime['0'];
				$purchaseTime = $purchaseDateTime['1'];
				//echo $modBetAmount; exit;
				if($gameType!="N/A" && $_REQUEST['bet_amount'][$i]!=""){
					$resPurchaseLottery = mysql_query("INSERT INTO lottery_purchase(
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
					p_win_count) VALUES(
					
					'".$_SESSION['lottery']['memberid']."',
					'".$category."',
					'".$period."',
					'".$gameType."',
					'".$digit."',
					'',
					'',
					'',
					'".$modBetAmount."', 
					'".$modDiscount."',
					'".$modPaybleAmount."',
					'".$purchaseDate."', 
					'".$purchaseTime."',
					'".$t."',
					'0',
					'0')");
				}
			}
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
		TARUHAN - 4D,3D,2D
		<br />
		PERIOD : <?php echo $period;?><hr />
		<form class="form-horizontal" method="post" action="" name="frm_shio">
			<input type="hidden" name="market" id="marketn" value="<?php echo $marketName;?>">
			<input type="hidden" name="avlbl_blnce" value="<?php echo $availableBalance;?>">
			<input type="hidden" name="period" value="<?php echo $period; ?>">
			<input type="hidden" name="key" value="purchase4d">
			<div class="form-group"> 
				<label class="col-xs-4 control-label">Min bet</label> 
				<div class="col-xs-8">
					<p class="form-control-static">: 
					4D <small> = </small>Rp. <?php echo $minbetAmount4d; ?> ,
					3D <small> = </small>Rp. <?php echo $minbetAmount3d; ?>  ,
					2D <small> = </small>Rp. <?php echo $minbetAmount2d; ?> ,
					2D D <small> = </small>Rp. <?php echo $minbetAmount2dd; ?> ,
					2D T <small> = </small>Rp. <?php echo $minbetAmount2dt; ?> ,
					2D B <small> = </small>Rp. <?php echo $minbetAmount2db; ?>
				</p>
				</div> 
			</div>
			<div class="form-group"> 
				<label class="col-xs-4 control-label">Max bet</label> 
				<div class="col-xs-8">
				<p class="form-control-static">: 
					4D <small> = </small>Rp. <?php echo $maxbetAmount4d; ?> ,
					3D <small> = </small>Rp. <?php echo $maxbetAmount3d; ?>  ,
					2D <small> = </small>Rp. <?php echo $maxbetAmount2d; ?> ,
					2D D <small> = </small>Rp. <?php echo $maxbetAmount2dd; ?> ,
					2D T <small> = </small>Rp. <?php echo $maxbetAmount2dt; ?> ,
					2D B <small> = </small>Rp. <?php echo $maxbetAmount2db; ?>
				</p>
				</div> 
			</div>
			<?php/* <div class="form-group"> 
				<label class="col-xs-4 control-label">Kelipatan</label> 
				<div class="col-xs-8">
				<p class="form-control-static">: 
					4D <small> = </small>Rp. <?php echo ; ?> ,
					3D <small> = </small>Rp. <?php echo ; ?> ,
					2D <small> = </small>Rp. <?php echo ; ?>
					2D D <small> = </small>Rp. <?php echo ; ?> ,
					2D T <small> = </small>Rp. <?php echo ; ?> ,
					2D B <small> = </small>Rp. <?php echo ; ?> ,
				</p>
				</div> 
			</div> */?>
			<div class="form-group"> 
				<label class="col-xs-4 control-label">Discount</label> 
				<div class="col-xs-8">
					<p class="form-control-static">: 
					4D <small> = </small><?php echo $discountPercentage4d; ?> % ,
					3D <small> = </small><?php echo $discountPercentage3d; ?> % ,
					2D <small> = </small><?php echo $discountPercentage2d; ?> % ,
					2D D <small> = </small><?php echo $discountPercentage2dd; ?> % ,
					2D T <small> = </small><?php echo $discountPercentage2dt; ?> % ,
					2D B <small> = </small><?php echo $discountPercentage2db; ?> %
					</p>
				</div> 
			</div>
			<hr />
			<div class="form-group"> 
				<small><mark>DONT REFRESH THIS PAGE & Max bet 120 Record</mark></small><br />
				Contoh Benar : 1234*234*34#1000  <small>atau </small>1234#1000,234*34#5000 <small>atau</small>12*21*11#1000,33*32#1000,234*432#1000,1234*4321#1000<br />
			</div>
			<hr />
			<div class="form-group"> 
				<label class="col-xs-4 control-label">BET COLOK BEBAS</label> 
				<div class="col-xs-8"><input type="text" class="form-control" placeholder="BET 4D,3D,2D" name="dimensi" id="dimensi" value="" > </div> 
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