<?php require_once("includes/head.php");
	require_once("includes/check-authentication.php");
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
	
	$arrBettingLimit = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = 'Kembang'"));
	$minbetAmount = $arrBettingLimit['bt_min_bet_amount'];
	$maxbetAmount = $arrBettingLimit['bt_max_bet_amount'];
	
	$kembangCMS = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '40'"));
	$resCategoryKembang = mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = 'Kembang'");
	$resCategoryKempis = mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = 'Kempis'");
	$resCategoryKember = mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = 'Kembar'");
	
	$discountKey = [];
	while($arrCategoryKembang = mysql_fetch_array($resCategoryKembang)) {
		$discountKey['kembang'][strtolower($arrCategoryKembang['g_name'])] = $arrCategoryKembang['g_kei'];
	}
	while($arrCategoryKempis = mysql_fetch_array($resCategoryKempis)) {
		$discountKey['kempis'][strtolower($arrCategoryKembang['g_name'])] = $arrCategoryKembang['g_kei'];
	}
	while($arrCategoryKember = mysql_fetch_array($resCategoryKember)) {
		$discountKey['kembar'][strtolower($arrCategoryKembang['g_name'])] = $arrCategoryKembang['g_kei'];
	}
	
	if(isset($_REQUEST['key']) && $_REQUEST['key'] == "pKembang") {
		$availableBalace = $_REQUEST['avlbl_blnce'];
		$availableBalace = $availableBalace * 1;
		
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$t=time();
		
		/* Form input from the given code */
		$market = $_REQUEST['market'];
		$period = $_REQUEST['period'];
		$gameTypeC = $_REQUEST['gametypeC'];
		$gameTypeH = $_REQUEST['gametypeH'];
					
		$inputs = [];
		$modTotalPAmount = 0;
		$path = explode('&msg=', $_SERVER['REQUEST_URI']);
		$url = $path[0];
		$crushList = array('Depan', 'Tengah', 'Belakang');
		$model_name = $_REQUEST['tbk'];
		foreach($model_name as $inputName=>$inputValue) {
			$rows = explode(',', trim($inputValue));
			if(trim($inputValue) != '') {
				if(count($rows) > 0 && count($rows) <= 10) {
					foreach($rows as $row) {
						$columns = explode('#', $row);
						//Check row value should contain both lottery no and bet amount
						if(count($columns) == 2) {
							$crushPositions = explode('*', $columns[0]);
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
								foreach($crushPositions as $crushPosition) {
									//lottery number should be an integer
									if(in_array($crushPosition, $crushList)) {
										/* Calculate the discount value */
										$position = $crushPosition;
										$kei = $discountKey[$inputName][strtolower($position)];
										if($kei < 0){
											$discount = ($betAmount * $kei)/100;
										} else{
											$discount = 0;
										}	
										$paybleAmount = $betAmount - $discount;
										$modTotalPAmount += $paybleAmount;
										$inputs[] = array('gameType' => strtoupper($inputName), 'gamePosition' => strtoupper($position), 'betAmount' => $betAmount, 'discount' => abs($discount), 'paybleAmount' => $paybleAmount);
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
			}
		}
		//print_r($inputs);die;
		
		if($modTotalPAmount<$availableBalace) {
			foreach($inputs as $input) {
				$market = $_REQUEST['market'];
				$period = $_REQUEST['period'];
				$gameType = $_REQUEST['gametype'];
				$gamePosition = $input['gamePosition'];
				$crushType = $input['gameType'];
				$betAmount = $input['betAmount'];
				$modBetAmount = str_replace( ',', '', $betAmount);
				$kei = $input['discount'];
				$modkei = str_replace( ',', '', $kei);
				$paybleAmount = $_REQUEST['payble_amount'][$i];
				$modPaybleAmount = str_replace( ',', '', $paybleAmount);
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
					'',
					'".$gamePosition."',
					'',
					'".$crushType."',
					'".$modBetAmount."',
					'".$modkei."',
					'".$modPaybleAmount."',
					'".$purchaseDate."',
					'".$purchaseTime."',
					'".$t."',
					'1',
					'0')");
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
		TARUHAN - DASAR
		<br />
		PERIOD : <?php echo $period;?><hr />
		
		<form class="form-horizontal" method="post" action="" name="frm_shio">
			<input type="hidden" name="market" id="marketn" value="<?php echo $marketName;?>">
			<input type="hidden" name="period" value="<?php echo $period; ?>">
			<input type="hidden" name="avlbl_blnce" value="<?php echo $availableBalance;?>">
			<input type="hidden" name="gametype" id="gametypeC" value="Kembang">
			<input type="hidden" name="key" value="pKembang">
			<?php echo $kembangCMS['cms_page_details']; ?>
			<div class="form-group"> 
				<label class="col-xs-4 control-label">KEI</label> 
				<div class="col-xs-8">
					<p class="form-control-static">:  
					<?php 
						while($arrCategoryKembang = mysql_fetch_array($resCategoryKembang)) {
							echo "Kembang ".$arrCategoryKembang['g_name'].' = '.$arrCategoryKembang['g_kei'].' x, ';
						}
						echo "<br>&nbsp;&nbsp;";
						while($arrCategoryKempis = mysql_fetch_array($resCategoryKempis)) {
							echo "Kempis ".$arrCategoryKempis['g_name'].' = '.$arrCategoryKempis['g_kei'].' x, ';
						}
						echo "<br>&nbsp;&nbsp;";
						while($arrCategoryKember = mysql_fetch_array($resCategoryKember)) {
							echo "Kembar ".$arrCategoryKember['g_name'].' = '.$arrCategoryKember['g_kei'].' x, ';
						}
					?>
				</p>
				</div> 
			</div>
			<hr/>
			<div class="form-group"> 
				<small><mark>DONT REFRESH THIS PAGE  & Max bet 10 Record</mark></small><br />
				Contoh Bet : Depan#10000  <small>atau </small>Tengah*Belakang#10000 <small>atau </small> Depan*Tengah#10000,Belakang#20000
			</div>
			<hr />
			<div class="form-group"> 
				<label class="col-xs-4 control-label">Kembang</label> 
				<div class="col-xs-8"><input type="text" class="form-control" placeholder="BET Kembang" name="tbk[kembang]" id="kembang" value="" > </div> 
			</div>
			
			<div class="form-group"> 
				<label class="col-xs-4 control-label">Kempis</label> 
				<div class="col-xs-8"><input type="text" class="form-control" placeholder="BET Kempis" name="tbk[kempis]" id="kempis" value="" > </div> 
			</div>
			
			<div class="form-group"> 
				<label class="col-xs-4 control-label">Kembar</label> 
				<div class="col-xs-8"><input type="text" class="form-control" placeholder="BET Kembar" name="tbk[kembar]" id="kembar" value="" > </div> 
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