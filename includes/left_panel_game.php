<?php 
	$mem_id = $_SESSION['lottery']['memberid'];
	/////////////////////////////////////////////////////////////
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$currentDateTime = date('Y-m-d h:i:s');
	$arrC = explode(" ", $currentDateTime);
	$currentDate = $arrC['0'];
	$currentTime = $arrC['1'];
	
	/////////////////////////////////////////////////////////////
	$member_info = mysql_fetch_array(mysql_query("SELECT * FROM `lottery_member_registration` WHERE `member_id` = '".$mem_id."'"));
	$resAllMarket = mysql_query("SELECT * FROM lottery_market order by market_id ASC");
	$arrTotalDeposit = mysql_fetch_array(mysql_query("SELECT SUM(deposit_amount) AS TotalDeposit FROM lottery_amount_deposit 
	WHERE deposit_mem_id = '".$mem_id."' and deposit_status = '1'"));
	if($arrTotalDeposit['TotalDeposit'] == '') {
		$totalMemberDepositAmount = 0;
		} else {
		$totalMemberDepositAmount = $arrTotalDeposit['TotalDeposit'];
	}
	
	$arrTotalPaybleAmount = mysql_fetch_array(mysql_query("SELECT SUM(p_payble_amount) AS TotalPay FROM lottery_purchase WHERE p_member_id = '".$mem_id."' AND p_status = '1'"));
	if($arrTotalPaybleAmount['TotalPay'] == '') {
		$totalPayingAmount = 0;
		} else {
		$totalPayingAmount = $arrTotalPaybleAmount['TotalPay'];
	}
	$arrTotalWithdrawl = mysql_fetch_array(mysql_query("SELECT SUM(w_amount) AS TotalWithdrawl FROM lottery_amount_withdraw WHERE w_from_mem_id = '".$mem_id."' and w_status = '1'"));
	if($arrTotalWithdrawl['TotalWithdrawl'] == '') {
		$totalMemberWithdraw = 0;
		} else {
		$totalMemberWithdraw = $arrTotalWithdrawl['TotalWithdrawl'];
	}
	$arrInTotalTransfer = mysql_fetch_array(mysql_query("SELECT SUM(t_amount) AS TotalTransfer FROM lottery_amount_transfer WHERE t_member_id = '".$mem_id."' and t_status = '1' AND t_type='2'"));
	if($arrInTotalTransfer['TotalTransfer'] == '') {
		$totalInMemebrTransferAmount = 0;
		} else{
		$totalInMemebrTransferAmount = $arrInTotalTransfer['TotalTransfer'];
	}
	
	$arrOutTotalTransfer = mysql_fetch_array(mysql_query("SELECT SUM(t_amount) AS TotalTransfer FROM lottery_amount_transfer WHERE t_member_id = '".$mem_id."' and t_status = '1' AND t_type='1'"));
	if($arrOutTotalTransfer['TotalTransfer'] == '') {
		$totalInMemebrTransferAmount = 0;
		} else{
		$totalOutMemebrTransferAmount = $arrOutTotalTransfer['TotalTransfer'];
	}
	
	
	
	$totalMemebrTransferAmount = $totalInMemebrTransferAmount - $totalOutMemebrTransferAmount;
	
	
	$arrTotalRefAmount = mysql_fetch_array(mysql_query("SELECT SUM(r_amount) AS TotalRefAmount FROM lottery_referral_amount WHERE r_member_id = '".$mem_id."'"));
	if(isset($arrTotalRefAmount)) {
		$totalReferralAmount = $arrTotalRefAmount['TotalRefAmount'];
		} else{
		$totalReferralAmount = 0;
	}
	//echo $totalPayingAmount ."<br>". $totalMemberWithdraw ."<br>". $totalMemebrTransferAmount; exit;
	
	$totalDebit = $totalPayingAmount + $totalMemberWithdraw + $totalOutMemebrTransferAmount;
	$remainingBalance = ($totalMemberDepositAmount+$totalReferralAmount+$totalInMemebrTransferAmount) - $totalDebit ;
	
	
	$resWin = mysql_query("SELECT * FROM lottery_purchase WHERE p_member_id = '".$mem_id."' and p_win_status = 'Y'");
	$totalWinAmount = array();
	while($arrWin = mysql_fetch_array($resWin)) {
		//echo '<pre>';
		//print_r($arrWin); 
		$pCategory = $arrWin['p_category'];
		$pGameType = $arrWin['p_gametype'];
		$pBetAmount = $arrWin['p_bet_amount'];
		$pPaybleAmount = $arrWin['p_payble_amount'];
		$pCrushType = $arrWin['p_crush_type'];
		$winCount = $arrWin['p_win_count'];
		$pPosition = $arrWin['p_position'];
		/*if($pGameType == "Kembang") {
			$arrGameDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$pCategory."' and g_type = '".$pCrushType."'"));
			$giftValue = $arrGameDetails['g_gift'];
			} else {
			$arrGameDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$pCategory."' and g_type = '".$pGameType."'"));
			$giftValue = $arrGameDetails['g_gift'];
		}*/

		switch ($pGameType) {
			case "Kembang":
				$arrGameDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$pCategory."' and g_type = '".$pCrushType."'"));
				$giftValue = $arrGameDetails['g_gift'];
				break;
			case "Dasar":
			case "Silang":
			case "Kembang":
			case "Tengah":
				$arrGameDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$pCategory."' and g_type = '".$pGameType."' and g_name= '".$pPosition."'"));
				$giftValue = $arrGameDetails['g_gift'];
				break;
			case "50-50":
				$arrGameDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$pCategory."' and g_type = '".$pGameType."' and g_name= '".$pCrushType."' and g_position = '".$pPosition."'"));
				break;
			default:
				$arrGameDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$pCategory."' and g_type = '".$pGameType."'"));
			$giftValue = $arrGameDetails['g_gift'];
				break;
		}
		
		if($pGameType == "4D" || $pGameType == "3D" || $pGameType == "2D D" || $pGameType == "2D T" || $pGameType == "2D B") {
			$WinAmount = $pBetAmount * $giftValue;
		}
		if($pGameType == "Colok Bebas") {
			$WinAmount = $pPaybleAmount + (($pBetAmount * $winCount) * $giftValue);
		}
		if($pGameType == "Macau") {
			//Check Win count status
			if($winCount == "single") {
				$giftValue = $arrGameDetails['g_gift'];
			}
			if($winCount == "double") {
				$giftValue = $arrGameDetails['g_gift_double'];
			}
			if($winCount == "triple") {
				$giftValue = $arrGameDetails['g_gift_triple'];
			}
			$WinAmount = $pPaybleAmount + ($pBetAmount * $giftValue);
		}
		
		if($pGameType == "Colok Naga") {
			//Check Win count status
			if($winCount == "normal") {
				$giftValue = $arrGameDetails['g_gift'];
			}
			if($winCount == "special") {
				$giftValue = $arrGameDetails['g_gift_double'];
			}
			$WinAmount = $pPaybleAmount + ($pBetAmount * $giftValue);
		}
		
		if($pGameType == "Colok Jitu") {
			//Check Win count status
			$giftValue = $arrGameDetails['g_discount_as'];
			$WinAmount = ($pBetAmount * $giftValue) + $pPaybleAmount;
		}
		
		if($pGameType == "Tengah") {
			//Check Win count status
			$giftValue = $arrGameDetails['g_gift'];
			$keiValue = $arrGameDetails['g_kei'];

			if ($keiValue > 0 ) {
				$discount = $pBetAmount * ($keiValue/100);
			} else {
				$discount = 0;
			}
			
			// Previous Calculation
			//$WinAmount = $pPaybleAmount + ($pBetAmount * $giftValue);
			// Added by JR
			$WinAmount = $pPaybleAmount + ($pBetAmount * $giftValue)+$discount;
		}
		
		if($pGameType == "Dasar") {
			//Check Win count status
			$giftValue = $arrGameDetails['g_gift'];
			$keiValue = $arrGameDetails['g_kei'];

			if ($keiValue > 0 ) {
				$discount = $pBetAmount * ($keiValue/100);
			} else {
				$discount = 0;
			}
			// Previous calculation
			/*$winPer = ($pBetAmount * $giftValue) / 100;
			$WinAmount = $pPaybleAmount + ($pPaybleAmount + $winPer);*/
			// Added by JR
			$WinAmount = $pPaybleAmount + ($pBetAmount * $giftValue)+ $discount;
		}
		if($pGameType == "50-50") {
			//Check Win count status

			$giftValue = $arrGameDetails['g_gift'];
			$keiValue = $arrGameDetails['g_kei'];

			if ($keiValue > 0 ) {
				$discount = $pBetAmount * ($keiValue/100);
			} else {
				$discount = 0;
			}
			// Previous calculation
			//$WinAmount = $pBetAmount * $giftValue;
			// Added by JR
			$WinAmount = $pPaybleAmount + ($pBetAmount * $giftValue)+$discount;
		}
		
		
		if($pGameType == "Shio") {
			//Check Win count status
			$giftValue = $arrGameDetails['g_gift'];
			$WinAmount = $pPaybleAmount+($pBetAmount * $giftValue);
		}
		
		if($pGameType == "SILANG") {
			//Check Win count status

			$giftValue = $arrGameDetails['g_gift'];
			$keiValue = $arrGameDetails['g_kei'];

			if ($keiValue > 0 ) {
				$discount = $pBetAmount * ($keiValue/100);
			} else {
				$discount = 0;
			}

			// Previous Calculation
			//$WinAmount = $pPaybleAmount+($pBetAmount * $giftValue);
			// Added by JR
			$WinAmount = $pPaybleAmount + ($pBetAmount * $giftValue)+$discount;
		}
		
		if($pGameType == "Kembang") {

			$giftValue = $arrGameDetails['g_gift'];
			$keiValue = $arrGameDetails['g_kei'];

			if ($keiValue > 0 ) {
				$discount = $pBetAmount * ($keiValue/100);
			} else {
				$discount = 0;
			}
			// Previous calculation
			//$WinAmount = $pPaybleAmount+($pBetAmount * $giftValue);
			// Added by JR
			$WinAmount = $pPaybleAmount + ($pBetAmount * $giftValue)+$discount;
		}
		
		if($pGameType == "Kombinasi") {
			$WinAmount = $pPaybleAmount+($pBetAmount * $giftValue);
		}
		
		array_push($totalWinAmount,$WinAmount);
		$winSum = 0;
		foreach($totalWinAmount as $val) {
			//echo '<pre>';
			//print_r($totalWinAmount);
			$winSum += (int)$val;
		}
	}
?>
<div class="col-md-3 rdc-padding">
	<div class="col-md-12">
		<div class="side-bord-area">
			<div class="side-box">
				<table class="table " style="margin-bottom:5px;">
					<tbody>
						<tr>
							<td>Hello </td>
							<td>:</td>
							<td><?php echo $member_info['member_username']; ?></td>
						</tr>
						<tr>
							<td>Balance </td>
							<td>:</td>
							<td>Rs. <?php echo number_format($remainingBalance + $winSum); ?></td>
						</tr>
						<tr>
							<td>Win Amount </td>
							<td>:</td>
							<td>Rs. <?php echo number_format($winSum); ?></td>
						</tr>
						
						<tr>
							<td>Referral Amount </td>
							<td>:</td>
							<td>Rs. <?php echo number_format($totalReferralAmount,2); ?></td>
						</tr>
						<!--<tr>
							<td>Level </td>
							<td>:</td>
							<td>5</td>
						</tr>-->
					</tbody>
				</table>
				<div class="dotted-border"></div>
				<span>
					<a href="deposit_amount.php"><button type="button" class="btn game-more-btn mrg-top-5">Deposit</button></a>
				</span> 
				<span>
					<a href="withdraw_amount.php"><button type="button" class="btn game-more-btn mrg-top-5">Withdraw</button></a>
				</span>
				<span>
					<a href="transfer_amount.php"><button type="button" class="btn game-more-btn mrg-top-5">Transfer</button></a>
				</span>
			</div>
			<!-----------end of side box----------> 
			
		</div>
		<!------end of side-bord-area---------> 
	</div>
	<!--end-of col-md-12-->
	<div class="col-md-12">
		<div class="side-bord-area">
			<div class="side-box">
				<table class="table " style="margin-bottom:5px;">
					<tbody>
						<tr>
							<td colspan="7" style="color: red; font-size: 24px;">PASARAN </td>
						</tr>
						<tr class="dotted-border">
							<td style="color:#AFAFAF; font-size:16px;"></td>
							<td style="color: #AFAFAF; font-size: 16px;"></td>
						</tr>
						<?php while($arrMarket = mysql_fetch_array($resAllMarket)) {
							$marketCloseTime = $arrMarket['market_close_time'];
							//echo $marketCloseTime;
							$winLotoNumber = mysql_query("SELECT * FROM lottery_win_number WHERE wn_market = '".$arrMarket['market_name']."' and wn_date = '".$currentDate."'");
							$checkLotoNumber = mysql_num_rows($winLotoNumber);
							$arrWinLottery = mysql_fetch_array($winLotoNumber);
							
						?>
						<tr>
							<td class="pasar_ganti1">
								<?php if($currentTime > $marketCloseTime || $arrMarket['market_status'] == "0") {?>
									<a href="close_market.php?name=<?php echo $arrMarket['market_name']; ?>"><?php echo $arrMarket['market_name']; ?></a><?php } else{?>
									<a href="main_game.php?name=<?php echo $arrMarket['market_name']; ?>"><?php echo $arrMarket['market_name']; ?></a>
								<?php }?>
							</td>
							<td class="pasar_ganti1">
								<?php if($currentTime < $marketCloseTime && $arrMarket['market_status'] == "1") {?>
								<img src="images/online5.png" width="60"><?php } else{?><img src="images/offline5.png" width="60"><?php }?>
							</td>
							<td align="right"><?php if($checkLotoNumber == '0') {?><?php echo '';?><?php } else {?><?php echo $arrWinLottery['wn_number']; ?><?php }?></td>
						</tr>
						<?php }?>
						<!-- <tr>
							<td class="pasar_ganti1"><a href="#">HAINAN</a></td>
							<td class="pasar_ganti1"><img src="images/offline5.png" width="30"></td>
							<td align="right">6991</td>
							</tr>
							<tr>
							<td class="pasar_ganti1"><a href="#">SYDNEY</a></td>
							<td class="pasar_ganti1"><img src="images/online5.png" width="30"></td>
							<td align="right">5555</td>
						</tr>-->
					</tbody>
				</table>
			</div>
			<!-----------end of side box----------> 
			
		</div>
		<!------end of side-bord-area---------> 
	</div>
	<!--end-of col-md-12-->
	<!--div class="col-md-12" style="margin-bottom: 15px; margin-top:10px">
		<button type="button" class="btn game-more-btn mrg-top-5" style="width:100%">CASINO & GAMES</button>
		</div>
		<div class="col-md-12 "  style="margin-bottom: 15px; margin-top:10px">
		<button type="button" class="btn game-more-btn mrg-top-5" style="width:100%">HOT PROMO !!!</button>
	</div-->
	<div class="col-md-12 "  >
		<a href="dashboard.php"><button type="button" class="btn dashbord-btn mrg-top-5" style="width:100%">DASHBOARD</button></a>
	</div>
	<div class="col-md-12">
		<a href="data_number_output.php"><button type="button" class="btn dashbord-btn mrg-top-5" style="width:100%">DATA NUMBER OUTPUT</button></a>
	</div>
	<div class="col-md-12">
		<a href="bought_lottery_number.php"><button type="button" class="btn dashbord-btn mrg-top-5" style="width:100%">LOTTERY NUMBER BOUGHT</button></a>
	</div>
	<div class="col-md-12">
		<a href="deposit_history.php"><button type="button" class="btn dashbord-btn mrg-top-5" style="width:100%">DEPOSIT HISTORY</button></a>
	</div>
	<div class="col-md-12">
		<a href="withdraw_history.php"><button type="button" class="btn dashbord-btn mrg-top-5" style="width:100%">Withdrawl HISTORY</button></a>
	</div>
	<div class="col-md-12">
		<a href="transfer_history.php"><button type="button" class="btn dashbord-btn mrg-top-5" style="width:100%">Transfer HISTORY</button></a>
	</div>
	<div class="col-md-12">
		<a href="win-lose.php"><button type="button" class="btn dashbord-btn mrg-top-5" style="width:100%">Win / Lose</button></a>
	</div>
	<!--<div class="col-md-12">
		<button type="button" class="btn dashbord-btn mrg-top-5" style="width:100%">DATA TRANSACTION HISTORY</button>
	</div>-->
	<!--<div class="col-md-12">
		<button type="button" class="btn dashbord-btn mrg-top-5" style="width:100%">INVOICE</button>
	</div>-->
	<div class="col-md-12">
		<a href="memo_mashuk.php"><button type="button" class="btn dashbord-btn mrg-top-5" style="width:100%">MEMO</button></a>
	</div>
	<div class="col-md-12 ">
		<a href="referral.php"><button type="button" class="btn dashbord-btn mrg-top-5" style="width:100%">DATA Refferal</button></a>
	</div>
</div>
