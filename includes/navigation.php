<?php
	session_start();
	require_once("includes/check-authentication.php");
	$sitemail = mysql_fetch_array(mysql_query("SELECT * FROM `lottery_users` WHERE `u_id` = 1"));
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$local_date_time =  date('d M Y,h:i:s');
	$local_date = explode(",",$local_date_time);
	//print_r($local_date); exit;
	$member = mysql_fetch_array(mysql_query("SELECT * FROM `lottery_member_registration` WHERE `member_id` = '".$_SESSION['lottery']['memberid']."'"));
	$companyEmail = mysql_fetch_array(mysql_query("SELECT * FROM lottery_company_setting WHERE cs_id = '1'"));
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
<style>body { padding-top: 40px; }</style> 
<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="height: 30px; min-height: 25px; padding-top: 7px;"> 
	<div class="container-fluid"> 
		<a class="pull-left" href="my-account.php"><span class="glyphicon glyphicon-home"></span></a>
		<a href="my-account.php?xpage=profil">
		<font color="#000099" style="padding-left:10px;"><?php echo $member['member_fname'];?></font>
		</a>
		<label class="label label-success pull-right">
			$ : Rp 0 |
			Lvl : 0 |
		</label> 
	</div> 
</nav> 
	<div class="container-fluid"> 
	<div class="row"> 
		<div class="col-xs-12" align="center"> 
			<a href="my-account.php"><img style="max-width: 70%" src="../images/logo.png" alt="Pangerantoto" ></a> 
		</div> 
	</div> 
	</div> 	
<hr/>
<div class="container-fluid"> 
	<div class="row"> 
		<div class="col-xs-12" align="center" style="font-size:0.9em"> 
			Untuk member BRI, kami umumkan rekening lama a/n MARCEL KOPING telah diganti dengan rekening baru a/n IDA FARIDAH, terima kasih. <br> MGM www.mgmpools.com Minggu, Senin, Selasa, Rabu, Kamis , Jumat, Sabtu Tutup 12:30 result 13.00 WIB <br> DENMARK www.denmarkpools.com Minggu, Senin, Selasa, Rabu, Kamis , Jumat, Sabtu Tutup 20.30 WIB result 21.00 WIB <br> SINGAPORE www.singaporepools.com.sg Minggu, Senin, Rabu, Kamis,Sabtu tutup 17.25 result 17.45 WIB  <br> HAINAN www.hainanpools.com Minggu, Senin, Selasa, Rabu, Kamis , Jumat, Sabtu Tutup 14:30 result 15.00 WIB <br>  <hr>
		</div> 
	</div> 
	</div> 	
<br><br>

