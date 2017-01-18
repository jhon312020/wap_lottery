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
	}//////////////////////////////////// C H E C K   I F   A N Y    R E S U L T    E X I S T   /////////////////////////
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
	
	$arrBettingLimit = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = 'Kombinasi'"));
	$minbetAmount = $arrBettingLimit['bt_min_bet_amount'];
	if(!isset($minbetAmount)) {
		$minbetAmount = 0;
	}
	$maxbetAmount = $arrBettingLimit['bt_max_bet_amount'];
	if(!isset($maxbetAmount)) {
		$maxbetAmount = 0;
	}
	$arrKombinasiCMS = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '41'"));
	if(isset($_REQUEST['key']) && $_REQUEST['key'] == "pKombinasi") {
		/* echo "<pre>";
		print_r($_POST);die; */
		$availableBalace = $_REQUEST['avlbl_blnce'];
		$availableBalace = $availableBalace * 1;
		
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$t=time();
		$marketName = $_REQUEST['market'];
		$inputs = [];
		$model_name = $_REQUEST['tbk'];
		$path = explode('&msg=', $_SERVER['REQUEST_URI']);
		$url = $path[0];
		$modTotalPAmount = 0;
		$arrKombinasiDetails = mysql_fetch_array(mysql_query("SELECT * FROM  lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = 'Kombinasi'"));
		$discountPercentage = $arrKombinasiDetails['g_kei'];
		foreach($model_name['gmCrushTyp'] as $inputKey=>$inputValue) {
			foreach($inputValue as $keyPosition=>$data) {
				$firstCrushtPosition = $model_name['crush_position1'][$inputKey][$keyPosition];
				$secondCrushtPosition = $model_name['crush_position2'][$inputKey][$keyPosition];
				$cType = $data;
				$betAmount = $model_name['betamount'][$inputKey][$keyPosition];
				$is_amount = filter_var($betAmount, FILTER_VALIDATE_INT);
				//Bet amount should be an integer
				if($is_amount) {
					$modBetAmount = str_replace( ',', '', $betAmount);
					//Check bet amount with minimum and maximum bet amount
					if($minbetAmount && $betAmount < $minbetAmount) {
						header("Location:$url&msg=Min bet amount $minbetAmount");
						exit();
					}
					if($maxbetAmount && $betAmount > $maxbetAmount) {
						header("Location:$url&msg=Max bet amount $maxbetAmount");
						exit();
					}
					$discount = ($betAmount*$discountPercentage)/100;
					$paybleAmount = $betAmount - $discount;
					
					$modTotalPAmount += $paybleAmount;
					$inputs[] = array('gameType' => $data, 'crush_position1' => $firstCrushtPosition, 'crush_position2' => $secondCrushtPosition, 'betAmount' => $betAmount, 'discount' => $discount, 'paybleAmount' => $paybleAmount);
				}
			}
		}
		//print_r($inputs);die;
		if($modTotalPAmount<$availableBalace) {
			foreach($inputs as $input) {
				$market = $_REQUEST['market'];
				$period = $_REQUEST['period'];
				$gameType = $_REQUEST['gametype'];
				$asCrushtPosition = $input['crush_position1'];
				$kopCrushPosition = $input['crush_position2'];
				$cType = $input['gameType'];
				$betAmount = $input['betAmount'];
				$modBetAmount = str_replace( ',', '', $betAmount);
				$kei = $input['discount'];
				$modKei = str_replace( ',', '', $kei);
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
					'".$asCrushtPosition."',
					'".$kopCrushPosition."',
					'".$cType."',
					'".$modBetAmount."',
					'".$modKei."',
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
			<form role="form" name="kombinasiForm" id="kombinasiForm" action="" method="POST" class="form-horizontal">
				<input type="hidden" name="market" id="marketn" value="<?php echo $marketName;?>">
				<input type="hidden" name="avlbl_blnce" value="<?php echo $availableBalance;?>">
				<input type="hidden" name="period" value="<?php echo $period; ?>">
				<input type="hidden" name="gametype" id="gametypeC" value="Kombinasi">
				<input type="hidden" name="key" value="pKombinasi">
				<?php echo $arrKombinasiCMS['cms_page_details']; ?>
				<br>
				<div class="table-responsive">
					<table class="table">
						<tbody>
							<tr>
								<td colspan="7" class="even-header">AS vs KOP</td>
							</tr>
							<tr>
								<th>NO</th>
								<th style="width:30%;">As</th>
								<th style="width:30%">KOP</th>
								<th style="width:30%">BET</th>
							</tr>
							<?php 
								for($i=0; $i<3; $i++) {?>
								<input type="hidden" name="tbk[gmCrushTyp][first][]" value="AS vs KOP">
								<tr>
									<td><?php echo $i+1;?></td>
									<td>
										<select class="form-control country-input-width" name="tbk[crush_position1][first][]" id="as_crush_position_<?php echo $i;?>">
											<option value="AS_EVEN">AS EVEN</option>
											<option value="AS_ODD">AS ODD</option>
											<option value="AS_LARGE">AS LARGE</option>
											<option value="AS_SMALL">AS SMALL</option>
										</select>
									</td>
									<td>
										<select class="form-control country-input-width" name="tbk[crush_position2][first][]" id="kop_crush_position_<?php echo $i;?>">
											<option value="KOP_EVEN">KOP EVEN</option>
											<option value="KOP_ODD">KOP ODD</option>
											<option value="KOP_BIG">KOP BIG</option>
											<option value="KOP_SMALL">KOP SMALL</option>
										</select>
									</td>
									<td><input type="text" class="form-control  rdc-padding checkBetAmount" name="tbk[betamount][first][]" id="betamount_<?php echo $i;?>" data-thousands=","></td>
								</tr>
							<?php }?>
							<tr>
								<td colspan="7" class="even-header">KEPALA vs EKOR</td>
							</tr>
							<tr>
								<th>NO</th>
								<th style="width:120px;">KEPALA</th>
								<th style="width:120px;">EKOR</th>
								<th>BET</th>
							</tr>
							<?php for($j=0; $j<3; $j++) {?>
								<input type="hidden" name="tbk[gmCrushTyp][second][]" value="KEPALA vs EKOR">
								<tr>
									<td><?php echo $j+1;?></td>
									<td>
										<select class="form-control country-input-width" name="tbk[crush_position1][second][]" id="head_crush_position_<?php echo $j;?>">
											<option value="KEPALA_EVEN">KEPALA EVEN</option>
											<option value="KEPALA_ODD">KEPALA ODD</option>
											<option value="KEPALA_BIG">KEPALA BIG</option>
											<option value="KEPALA_SMALL">KEPALA SMALL</option>
										</select>
									</td>
									<td>
										<select class="form-control country-input-width" name="tbk[crush_position2][second][]" id="tail_crush_position_<?php echo $j;?>">
											<option value="EKOR_EVEN">EKOR EVEN</option>
											<option value="EKOR_ODD">EKOR ODD</option>
											<option value="EKOR_BIG">EKOR BIG</option>
											<option value="EKOR_SMALL">EKOR SMALL</option>
										</select>
									</td>
									<td><input type="text" class="form-control  rdc-padding checkBetAmount2" name="tbk[betamount][second][]" id="betamount_<?php echo $j;?>" data-thousands=","></td>
								</tr>
							<?php }?>
							<tr>
								<td colspan="7" class="even-header">CAMPURAN</td>
							</tr>
							<tr>
								<th>NO</th>
								<th style="width:120px;" >CRUSH 1</th>
								<th style="width:120px;" >CRUSH 2</th>
								<th>BET</th>
							</tr>
							<?php for($k=0; $k<3; $k++) {?>
								<input type="hidden" name="tbk[gmCrushTyp][third][]" value="CAMPURAN">
								<tr>
									<td><?php echo $k+1;?></td>
									<td>
										<select class="form-control country-input-width" name="tbk[crush_position1][third][]" id="mixed_cursh1_pos_<?php echo $k;?>">
											<option value="AS_EVEN">AS EVEN</option>
											<option value="AS_ODD">AS ODD</option>
											<option value="AS_BIG">AS BIG</option>
											<option value="AS_SMALL">AS SMALL</option>
											<option value="KOP_EVEN">KOP EVEN</option>
											<option value="KOP_ODD">KOP ODD</option>
											<option value="KOP_BIG">KOP BIG</option>
											<option value="KOP_SMALL">KOP SMALL</option>
										</select>
									</td>
									<td>
										<select class="form-control country-input-width" name="tbk[crush_position2][third][]" id="mixed_crush2_pos_<?php echo $k;?>">
											<option value="KEPALA_EVEN">KEPALA EVEN</option>
											<option value="KEPALA_ODD">KEPALA ODD</option>
											<option value="KEPALA_BIG">KEPALA BIG</option>
											<option value="KEPALA_SMALL">KEPALA SMALL</option>
											<option value="EKOR_EVEN">EKOR EVEN</option>
											<option value="EKOR_ODD">EKOR ODD</option>
											<option value="EKOR_BIG">EKOR BIG</option>
											<option value="EKOR_SMALL">EKOR SMALL</option>
										</select>
									</td>
									<td><input type="text" class="form-control  rdc-padding checkBetAmount3" name="tbk[betamount][third][]" id="betamount_<?php echo $k;?>" data-thousands=","></td>
								</tr>
							<?php }?>
						</tbody>
					</table>
				</div>
				<div class="form-group"> 
					<div class="col-sm-12">
						<input type="submit" class="btn btn-warning form-control" name="submit" id="submit" value="BELI" onclick="return confirm('PROSES TARUHAN INI???')"/> 
					</div> 
				</div>
			</form>	
		</div>
		<hr/>			
		<?php include("includes/footer.php");?>		
	</body>
</html>