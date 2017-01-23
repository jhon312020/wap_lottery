<?php require_once("includes/head.php");
require_once("includes/check-authentication.php");
$mem_id = $_SESSION['lottery']['memberid'];
///////////////////// D  E  P  O  S  I   T    H  I  S  T  O  R  Y     BY    LOGGED    IN     PLAYER /////////////////////
$resDepositHistory = mysql_query("SELECT * FROM lottery_amount_deposit WHERE deposit_mem_id = '".$mem_id."' ORDER BY  deposit_date_time DESC");

///////////////////// D  E  P  O  S  I   T    H  I  S  T  O  R  Y     BY    LOGGED    IN     PLAYER  /////////////////////

///////////////////// D  E  P  O  S  I   T    H  I  S  T  O  R  Y     BY    LOGGED    IN     PLAYER /////////////////////
$resWithdrawHistory = mysql_query("SELECT * FROM lottery_amount_withdraw WHERE w_from_mem_id = '".$mem_id."' ORDER BY  w_date_time DESC");

///////////////////// D  E  P  O  S  I   T    H  I  S  T  O  R  Y     BY    LOGGED    IN     PLAYER  /////////////////////
$checkWin = mysql_query("SELECT * FROM lottery_purchase WHERE p_member_id = '".$mem_id."'");


	$from_date = Date('Y-m-d');
	$to_date = Date('Y-m-d', strtotime('-5 days'));
	/* $arrTotalDepositByDate = mysql_fetch_array(mysql_query("SELECT SUM(deposit_amount) AS TotalDeposit FROM lottery_amount_deposit 
	WHERE deposit_mem_id = '".$mem_id."' and deposit_status = '1' and DATE(deposit_date_time) < '".$to_date."'"));
	if($arrTotalDepositByDate['TotalDeposit'] == '') {
		$totalMemberDepositAmountByDate = 0;
		} else {
		$totalMemberDepositAmountByDate = $arrTotalDepositByDate['TotalDeposit'];
	}
	
	$arrTotalPaybleAmountByDate = mysql_fetch_array(mysql_query("SELECT SUM(p_payble_amount) AS TotalPay FROM lottery_purchase WHERE p_member_id = '".$mem_id."' AND p_status = '1' and DATE(p_date) < '".$to_date."'"));
	if($arrTotalPaybleAmountByDate['TotalPay'] == '') {
		$totalPayingAmountByDate = 0;
		} else {
		$totalPayingAmountByDate = $arrTotalPaybleAmountByDate['TotalPay'];
	}
	$arrTotalWithdrawlByDate = mysql_fetch_array(mysql_query("SELECT SUM(w_amount) AS TotalWithdrawl FROM lottery_amount_withdraw WHERE w_from_mem_id = '".$mem_id."' and w_status = '1' and DATE(w_date_time) < '".$to_date."'"));
	if($arrTotalWithdrawlByDate['TotalWithdrawl'] == '') {
		$totalMemberWithdrawByDate = 0;
		} else {
		$totalMemberWithdrawByDate = $arrTotalWithdrawlByDate['TotalWithdrawl'];
	}
	$arrInTotalTransferByDate = mysql_fetch_array(mysql_query("SELECT SUM(t_amount) AS TotalTransfer FROM lottery_amount_transfer WHERE t_member_id = '".$mem_id."' and t_status = '1' AND t_type='2' and DATE(t_datetime) < '".$to_date."'"));
	if($arrInTotalTransferByDate['TotalTransfer'] == '') {
		$totalInMemebrTransferAmountByDate = 0;
		} else{
		$totalInMemebrTransferAmountByDate = $arrInTotalTransferByDate['TotalTransfer'];
	}
	
	$arrOutTotalTransfer = mysql_fetch_array(mysql_query("SELECT SUM(t_amount) AS TotalTransfer FROM lottery_amount_transfer WHERE t_member_id = '".$mem_id."' and t_status = '1' AND t_type='1' and DATE(t_datetime) < '".$to_date."'"));
	if($arrOutTotalTransfer['TotalTransfer'] == '') {
		$totalOutMemebrTransferAmountByDate = 0;
		} else{
		$totalOutMemebrTransferAmountByDate = $arrOutTotalTransfer['TotalTransfer'];
	}
	$totalMemebrTransferAmountByDate = $totalInMemebrTransferAmountByDate - $totalOutMemebrTransferAmountByDate;
	
	
	$arrTotalRefAmountByDate = mysql_fetch_array(mysql_query("SELECT SUM(r_amount) AS TotalRefAmount FROM lottery_referral_amount WHERE r_member_id = '".$mem_id."' and DATE(r_date) < '".$to_date."'"));
	if(isset($arrTotalRefAmountByDate)) {
		$totalReferralAmountByDate = $arrTotalRefAmountByDate['TotalRefAmount'];
		} else{
		$totalReferralAmountByDate = 0;
	}
	//echo $totalPayingAmountByDate ."<br>". $totalMemberWithdrawByDate ."<br>". $totalMemebrTransferAmountByDate; exit;
	
	$totalDebitByDate = $totalPayingAmountByDate + $totalMemberWithdrawByDate + $totalOutMemebrTransferAmountByDate;
	$remainingBalanceByDate = ($totalMemberDepositAmountByDate+$totalReferralAmountByDate+$totalInMemebrTransferAmountByDate) - $totalDebitByDate ;
	
	
	$resWinByDate = mysql_query("SELECT * FROM lottery_purchase WHERE p_member_id = '".$mem_id."' and p_win_status = 'Y' and DATE(p_date) < '".$to_date."'");
	$totalWinAmountByDate = array();
	while($arrWinByDate = mysql_fetch_array($resWinByDate)) {
		//echo '<pre>';
		//print_r($arrWinByDate); 
		$pCategory = $arrWinByDate['p_category'];
		$pGameType = $arrWinByDate['p_gametype'];
		$pBetAmount = $arrWinByDate['p_bet_amount'];
		$pPaybleAmount = $arrWinByDate['p_payble_amount'];
		$pCrushType = $arrWinByDate['p_crush_type'];
		$winCount = $arrWinByDate['p_win_count'];
		$pPosition = $arrWinByDate['p_position'];
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
			$WinAmountByDate = $pBetAmount * $giftValue;
		}
		if($pGameType == "Colok Bebas") {
			$WinAmountByDate = $pPaybleAmount + (($pBetAmount * $winCount) * $giftValue);
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
			$WinAmountByDate = $pPaybleAmount + ($pBetAmount * $giftValue);
		}
		
		if($pGameType == "Colok Naga") {
			//Check Win count status
			if($winCount == "normal") {
				$giftValue = $arrGameDetails['g_gift'];
			}
			if($winCount == "special") {
				$giftValue = $arrGameDetails['g_gift_double'];
			}
			$WinAmountByDate = $pPaybleAmount + ($pBetAmount * $giftValue);
		}
		
		if($pGameType == "Colok Jitu") {
			//Check Win count status
			$giftValue = $arrGameDetails['g_discount_as'];
			$WinAmountByDate = ($pBetAmount * $giftValue) + $pPaybleAmount;
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
			//$WinAmountByDate = $pPaybleAmount + ($pBetAmount * $giftValue);
			// Added by JR
			$WinAmountByDate = $pPaybleAmount + ($pBetAmount * $giftValue)+$discount;
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
			// Added by JR
			$WinAmountByDate = $pPaybleAmount + ($pBetAmount * $giftValue)+ $discount;
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
			//$WinAmountByDate = $pBetAmount * $giftValue;
			// Added by JR
			$WinAmountByDate = $pPaybleAmount + ($pBetAmount * $giftValue)+$discount;
		}
		
		
		if($pGameType == "Shio") {
			//Check Win count status
			$giftValue = $arrGameDetails['g_gift'];
			$WinAmountByDate = $pPaybleAmount+($pBetAmount * $giftValue);
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
			//$WinAmountByDate = $pPaybleAmount+($pBetAmount * $giftValue);
			// Added by JR
			$WinAmountByDate = $pPaybleAmount + ($pBetAmount * $giftValue)+$discount;
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
			//$WinAmountByDate = $pPaybleAmount+($pBetAmount * $giftValue);
			// Added by JR
			$WinAmountByDate = $pPaybleAmount + ($pBetAmount * $giftValue)+$discount;
		}
		
		if($pGameType == "Kombinasi") {
			$WinAmountByDate = $pPaybleAmount+($pBetAmount * $giftValue);
		}
		
		array_push($totalWinAmountByDate,$WinAmountByDate);
		$winSumByDate = 0;
		foreach($totalWinAmountByDate as $val) {
			//echo '<pre>';
			//print_r($totalWinAmountByDate);
			$winSumByDate += (int)$val;
		}
	}
	$availableBalanceByDate = $remainingBalanceByDate + $winSumByDate; 
	
	echo $availableBalanceByDate;die;
	4650705
	*/
	

$qry = mysql_query("
			(Select GROUP_CONCAT(p_id) AS p_id, 
			p_date, 
			p_period, 
			'BELI' as status, 
			0 as credit, 
			SUM(p_payble_amount) as debit, 
			p_gametype, 
			'Beli' as p_information 
			from lottery_purchase 
			where p_date between '".$to_date."' and '".$from_date."' 
			and p_member_id = '".$mem_id."' 
			group by p_date, p_period, p_gametype)
			
			union all 
			
			(select 
			0 as p_id, 
			DATE(deposit_date_time) as p_date, 
			'' as p_period, 
			'DEPOSIT' as status, 
			deposit_amount as credit, 
			0 as debit, 
			'' as p_gametype, 
			'WAP Tarik Dana' as p_information 
			FROM lottery_amount_deposit 
			WHERE deposit_mem_id = '".$mem_id."' 
			and deposit_status = '1' 
			and DATE(deposit_date_time) between '".$to_date."' and '".$from_date."')
			
			union all 
			
			(select 
			0 as p_id, 
			DATE(w_date_time) as p_date, 
			'' as p_period, 
			'WITHDRAW' as status, 
			0 as credit, 
			w_amount as debit, 
			'' as p_gametype, 
			'WAP Dorong Dana' as p_information 
			FROM lottery_amount_withdraw 
			WHERE w_from_mem_id = '".$mem_id."' 
			and w_status = '1' 
			and DATE(w_date_time) between '".$to_date."' and '".$from_date."')

			union all 
			
			(SELECT 
			'' AS p_id, 
			t_datetime as p_date, 
			'' as p_period, 
			'Transfer In' as status, 
			0 as credit, 
			t_amount AS debit, 
			'' as p_gametype, 
			'WAP Transfer In' as p_information 
			FROM lottery_amount_transfer 
			WHERE t_member_id = '".$mem_id."' 
			and t_status = '1' 
			and t_type='2' 
			and DATE(t_datetime) between '".$to_date."' and '".$from_date."') 
			
			union all 
			
			(SELECT 
			'' AS p_id, 
			t_datetime as p_date, 
			'' as p_period, 
			'Transfer In' as status, 
			0 as credit, 
			t_amount AS debit, 
			'' as p_gametype, 
			'WAP Transfer In' as p_information 
			FROM lottery_amount_transfer 
			WHERE t_member_id = '".$mem_id."' 
			and t_status = '1' 
			and t_type='1' 
			and DATE(t_datetime) between '".$to_date."' and '".$from_date."') 
			
			union all 
			
			(SELECT 
			'' AS p_id, 
			r_date as p_date, 
			'' as p_period, 
			'Referral Amount' as status, 
			r_amount as credit, 
			0 AS debit, 
			'' as p_gametype, 
			'WAP Referral Amount' as p_information 
			FROM lottery_referral_amount 
			WHERE 
			r_purchase_id in (select p_id from lottery_purchase where p_date between '".$to_date."' and '".$from_date."' and p_member_id = '".$mem_id."') and DATE(r_date) between '".$to_date."' and '".$from_date."') 
			order by p_date asc");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once("includes/html_head.php");?>
</head> 
<body> 
<?php include("includes/navigation.php");
//echo $availableBalanceByDate.'----'.$availableBalance;die;
?>
<div class="container-fluid">
	<a href="my-account.php" class="btn btn-danger btn-xs" style="margin-bottom: 5px;">HOME</a>
	<br><br>
	TRANSAKSI 5 HARI TERAKHIR
	<br><br>
		<table width='100%' cellpadding='0' cellspacing='5' id='tabeldata' class='table table-bordered table-hover center'>
			<thead id='head1'>
				<tr class='bg-info'>
					<th style="text-align:center">TANGGAL</th>
					<th style="text-align:center">PERIODE</th>
					<th style="text-align:center">KETERANGAN</th>
					<th style="text-align:center">STATUS</th>
					<th style="text-align:center">DEBET</th>
					<th style="text-align:center">KREDIT</th>
					<th style="text-align:center">SALDO</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$i = 1;
			//echo $availableBalanceByDate;
			$availableBalanceByDate = $availableBalance;
			while($arrTransferHistory = mysql_fetch_array($qry)) {
				$winAmount = 0;
				$creditAmountByDate = $arrTransferHistory['credit'];
				if($arrTransferHistory['status'] == 'BELI') {
					$checkWin = mysql_query("SELECT * 
                                  FROM lottery_purchase
                                  WHERE p_id in (".$arrTransferHistory['p_id'].") and p_win_status = 'Y'");
						while($arrCheckWin = mysql_fetch_array($checkWin)) {
							//echo '<pre>';
							//print_r($arrCheckWin);
							$pCategory = $arrCheckWin['p_category'];
							$pGameType = $arrCheckWin['p_gametype'];
							$pBetAmount = $arrCheckWin['p_bet_amount'];
							$pPaybleAmount = $arrCheckWin['p_payble_amount'];
							$pCrushType = $arrCheckWin['p_crush_type'];
							$winCount = $arrCheckWin['p_win_count'];
							if($pGameType == "Kembang") {
								$arrGameDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$pCategory."' and g_type = '".$pCrushType."'"));
								$giftValue = $arrGameDetails['g_gift'];
							} else {
								$arrGameDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$pCategory."' and g_type = '".$pGameType."'"));
								$giftValue = $arrGameDetails['g_gift'];
							}
							if($pGameType == "4D") {
								$winAmount += $pBetAmount * $giftValue;
							}
							if($pGameType == "3D") {
								$winAmount += $pBetAmount * $giftValue;
							}
							if($pGameType == "2D D") {
								$winAmount += $pBetAmount * $giftValue;
							}
							if($pGameType == "2D T") {
								$winAmount += $pBetAmount * $giftValue;
							}
							if($pGameType == "2D B") {
								$winAmount += $pBetAmount * $giftValue;
							}
							if($pGameType == "Colok Bebas") {
								$winAmount += $pPaybleAmount + (($pBetAmount * $winCount) * $giftValue);
							}
							if($pGameType == "Macau") {
								if($winCount == "single") {
									$giftValue = $arrGameDetails['g_gift'];
								}
								if($winCount == "double") {
									$giftValue = $arrGameDetails['g_gift_double'];
								}
								if($winCount == "triple") {
									$giftValue = $arrGameDetails['g_gift_triple'];
								}
								$winAmount += $pPaybleAmount + ($pBetAmount * $giftValue);
							}

							if($pGameType == "Colok Naga") {
								if($winCount == "normal") {
									$giftValue = $arrGameDetails['g_gift'];
								}
								if($winCount == "special") {
									$giftValue = $arrGameDetails['g_gift_double'];
								}
								$winAmount += $pPaybleAmount + ($pBetAmount * $giftValue);
							}

							if($pGameType == "Colok Jitu") {
								$giftValue = $arrGameDetails['g_discount_as'];
								$winAmount += $pBetAmount * $giftValue;
							}

							if($pGameType == "Tengah") {
								$giftValue = $arrGameDetails['g_gift'];
								$winAmount += $pPaybleAmount + ($pBetAmount * $giftValue);
							}

							if($pGameType == "Dasar") {
								$giftValue = $arrGameDetails['g_gift'];
								$winPer = ($pBetAmount * $giftValue) / 100;
								$winAmount += $pPaybleAmount + ($pPaybleAmount + $winPer);
							}

							if($pGameType == "50-50") {
								$giftValue = $arrGameDetails['g_gift'];
								$winAmount += $pBetAmount * $giftValue;
							}

							if($pGameType == "Shio") {
								$giftValue = $arrGameDetails['g_gift'];
								$winAmount += $pPaybleAmount+($pBetAmount * $giftValue);
							}

							if($pGameType == "SILANG") {
								$giftValue = $arrGameDetails['g_gift'];
								$winAmount += $pPaybleAmount+($pBetAmount * $giftValue);
							}
							if($pGameType == "HOMO") {
								$giftValue = $arrGameDetails['g_gift'];
								$winAmount += $pPaybleAmount+($pBetAmount * $giftValue);
							}
							if($pGameType == "Kembang") {
								$giftValue = $arrGameDetails['g_gift'];
								$winAmount += $pPaybleAmount+($pBetAmount * $giftValue);
							}

							if($pGameType == "Kombinasi") {
								$winAmount += $pPaybleAmount+($pBetAmount * $giftValue);
							}
					}
					$creditAmountByDate = $winAmount;
				}
			?>
				<tr <?php echo $arrTransferHistory['status'] == 'BELI'?"onclick=\"document.location = 'win-details.php?period=".$arrTransferHistory['p_period']."&date=".$arrTransferHistory['p_date']."&gametype=".$arrTransferHistory['p_gametype']."'\"":""; ?> class="keluaran_detail" style="<?php echo $arrTransferHistory['status'] == 'BELI'?'cursor:pointer;':''; ?>">
					<td align='center'><?php echo Date('d M Y', strtotime($arrTransferHistory['p_date']));?></td>
					<td align='center'><?php echo $arrTransferHistory['p_period']; ?></td>
					<td align='center' style='<?php echo $arrTransferHistory['status'] == 'BELI'?'color:#3366FF;':'color:#FF00FF;'; ?>font-weight:bold;'><?php echo $arrTransferHistory['p_information'].' '.$arrTransferHistory['p_gametype']; ?></td>
					<td align='center' style='<?php echo $arrTransferHistory['status'] == 'BELI'?'color:#3366FF;':'color:#FF00FF;'; ?>font-weight:bold;'><?php echo strtoupper($arrTransferHistory['status']); ?></td>
					<td align='right'><?php echo $arrTransferHistory['debit']; ?></td>
					<td align='right'><?php echo $creditAmountByDate; ?></td>
					<td align='right'><?php echo $availableBalanceByDate; ?></td>
					<?php
						$availableBalanceByDate = $availableBalanceByDate - $creditAmountByDate - $arrTransferHistory['debit'];
					?>
					</td>
				</tr>
				<?php 
				
				}?>
			</tbody>
		</table>
</div> 	
<br><br>
<hr/>
<?php include("includes/footer.php");?>
<script>

</script>
</body> 
</html>