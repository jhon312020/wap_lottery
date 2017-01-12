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
	
	$arrBettingLimit = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = '50-50'"));
	$minbetAmount = $arrBettingLimit['bt_min_bet_amount'];
	if(!isset($minbetAmount)) {
		$minbetAmount = 0;
	}
	$maxbetAmount = $arrBettingLimit['bt_max_bet_amount'];
	if(!isset($maxbetAmount)) {
		$maxbetAmount = 0;
	}
	
	$CMSDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '37'"));
	$arr5050Genap = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'Genap' and g_position = 'AS'"));
	
	$arr5050Ganjil = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'Ganjil' and g_position = 'AS'"));
	$arr5050KOPGenap = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'Genap' and g_position = 'KOP'"));
	$arr5050KOPGanjil = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'Ganjil' and g_position = 'KOP'"));
	
	$arr5050KepalaGenap = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'Genap' and g_position = 'KEPALA'"));
	$arr5050KepalaGanjil = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'Ganjil' and g_position = 'KEPALA'"));
	
	
	
	$arr5050EkorGenap = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'Genap' and g_position = 'EKOR'"));
	$arr5050EkorGanjil = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'Ganjil' and g_position = 'EKOR'"));
	
	/**
		*
		* B   E   S   A    R           K    E   C    I     L
		*
	**/
	$arr5050ASBESAR = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'BESAR' and g_position = 'AS'"));
	$arr5050ASKECIL = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'KECIL' and g_position = 'AS'"));
	
	$arr5050KOPBESAR = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'BESAR' and g_position = 'KOP'"));
	$arr5050KOPKECIL = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'KECIL' and g_position = 'KOP'"));
	
	$arr5050KepalaBESAR = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'BESAR' and g_position = 'KEPALA'"));
	$arr5050KepalaKECIL = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'KECIL' and g_position = 'KEPALA'"));
	
	
	
	$arr5050EKORBESAR = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'BESAR' and g_position = 'EKOR'"));
	$arr5050EKORKECIL = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'KECIL' and g_position = 'EKOR'"));
	
	/**
		*
		* I   N    S   E    R    T 
		*
	**/
	if(isset($_REQUEST['key']) && $_REQUEST['key'] == "p50") {
		$availableBalace = $_REQUEST['avlbl_blnce'];
		$availableBalace = $availableBalace * 1;
		
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$t=time();
		
		/* Form input from the given code */
		$data = str_replace(' ', '', $_REQUEST['dasar']);
		
		$market = $_REQUEST['market'];
		$period = $_REQUEST['period'];
		$gameType = $_REQUEST['gametype'];
					
		$inputs = [];
		$modTotalPAmount = 0;
		$path = explode('&msg=', $_SERVER['REQUEST_URI']);
		$url = $path[0];
		$rows = explode(',', trim($data));
		$crushList = array('Genap', 'Ganjil', 'Besar', 'Kecil');
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
										$arrDiscount = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$market."' and g_type = '".$gameType."' and g_name = '".strtolower($position)."' and g_position = '".strtoupper($inputName)."'"));
										$discountPercentage = $arrDiscount['g_kei'];
										if($discountPercentage < 0){
											$discount = ($betAmount * $discountPercentage)/100;
										} else{
											$discount = 0;
										}
										
										$paybleAmount = $betAmount - $discount;
										$modTotalPAmount += $paybleAmount;
										$inputs[] = array('lotteryPosition' => strtoupper($inputName), 'crushType' => $position, 'betAmount' => $betAmount, 'discount' => abs($discount), 'paybleAmount' => $paybleAmount);
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
				$lotteryPosition = $input['lotteryPosition'];
				$crushType = $input['crushType'];
				$betAmount = $input['betAmount'];
				$modBetAmount = str_replace( ',', '', $betAmount);
				$discount = $input['discount'];
				$modDiscount = str_replace( ',', '', $discount);
				$paybleAmount = $input['paybleAmount'];
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
				'".$lotteryPosition."',
				'',
				'".$crushType."',
				'".$modBetAmount."',
				'".$modDiscount."',
				'".$modPaybleAmount."',
				'".$purchaseDate."',
				'".$purchaseTime."',
				'".$t."',
				'0',
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
			<input type="hidden" name="gametype" id="gametypeC" value="50-50">
			<input type="hidden" name="key" value="p50">
			<?php echo $CMSDetails['cms_page_details']; ?>
			<div class="form-group"> 
				<label class="col-xs-4 control-label">KEI </label> 
				<div class="col-xs-8">
					<p class="form-control-static"> 
						<TABLE>
							<TR>
								<TD>AS</TD><TD><small>=</small></TD>
								<TD>Besar</TD><TD>:</TD><TD> <?php echo $arr5050ASBESAR['g_kei']; ?> </TD><TD>,</TD>
								<TD>Kecil</TD><TD>:</TD><TD> <?php echo $arr5050ASKECIL['g_kei']; ?> </TD><TD>,</TD>
								<TD>Genap</TD><TD>:</TD><TD> <?php echo $arr5050Genap['g_kei']; ?> </TD><TD>,</TD>
								<TD>Ganjil</TD><TD>:</TD><TD> <?php echo $arr5050Ganjil['g_kei']; ?> </TD>
							</TR>
							<TR>
								<TD>KOP</TD><TD><small>=</small></TD>
								<TD>Besar</TD><TD>:</TD><TD> <?php echo $arr5050KOPBESAR['g_kei']; ?> </TD><TD>,</TD>
								<TD>Kecil</TD><TD>:</TD><TD> <?php echo $arr5050KOPKECIL['g_kei']; ?> </TD><TD>,</TD>
								<TD>Genap</TD><TD>:</TD><TD> <?php echo $arr5050KOPGenap['g_kei']; ?> </TD><TD>,</TD>
								<TD>Ganjil</TD><TD>:</TD><TD> <?php echo $arr5050KOPGanjil['g_kei']; ?> </TD>
							</TR>
							<TR>
								<TD>Kepala</TD><TD><small>=</small></TD>
								<TD>Besar</TD><TD>:</TD><TD> <?php echo $arr5050KepalaBESAR['g_kei']; ?> </TD><TD>,</TD>
								<TD>Kecil</TD><TD>:</TD><TD> <?php echo $arr5050KepalaKECIL['g_kei']; ?> </TD><TD>,</TD>
								<TD>Genap</TD><TD>:</TD><TD> <?php echo $arr5050KepalaGenap['g_kei']; ?> </TD><TD>,</TD>
								<TD>Ganjil</TD><TD>:</TD><TD> <?php echo $arr5050KepalaGanjil['g_kei']; ?> </TD>
							</TR>
							<TR>
								<TD>Ekor</TD><TD><small>=</small></TD>
								<TD>Besar</TD><TD>:</TD><TD> <?php echo $arr5050EKORBESAR['g_kei']; ?> </TD><TD>,</TD>
								<TD>Kecil</TD><TD>:</TD><TD> <?php echo $arr5050EKORKECIL['g_kei']; ?> </TD><TD>,</TD>
								<TD>Genap</TD><TD>:</TD><TD> <?php echo $arr5050EkorGenap['g_kei']; ?> </TD><TD>,</TD>
								<TD>Ganjil</TD><TD>:</TD><TD> <?php echo $arr5050EkorGanjil['g_kei']; ?> </TD>
							</TR>
						</TABLE>
					</p>
				</div> 
			</div>
			<hr />
			<div class="form-group"> 
			<small><mark>DONT REFRESH THIS PAGE  & Max bet 120 Record</mark></small><br />
				Contoh Bet : Genap#10000  <small>atau </small>Besar*Kecil#10000 <small>atau </small> Genap*Ganjil#10000,Besar#20000
			</div>
			<hr />
			<div class="form-group"> 
				<label class="col-xs-4 control-label">AS</label> 
				<div class="col-xs-8"><input type="text" class="form-control" placeholder="BET AS" name="tbk[as]" id="tbk_as" value="" > </div> 
			</div>
			
			<div class="form-group"> 
				<label class="col-xs-4 control-label">KOP</label> 
				<div class="col-xs-8"><input type="text" class="form-control" placeholder="BET KOP" name="tbk[kop]" id="tbk_kop" value="" > </div> 
			</div>
			<div class="form-group"> 
				<label class="col-xs-4 control-label">KEPALA</label> 
				<div class="col-xs-8"><input type="text" class="form-control" placeholder="BET KEPALA" name="tbk[kepala]" id="tbk_kepala" value="" > </div> 
			</div>
			
			<div class="form-group"> 
				<label class="col-xs-4 control-label">EKOR</label> 
				<div class="col-xs-8"><input type="text" class="form-control" placeholder="BET EKOR" name="tbk[ekor]" id="tbk_ekor" value="" > </div> 
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