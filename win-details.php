<?php require_once("includes/head.php");
require_once("includes/check-authentication.php");
$mem_id = $_SESSION['lottery']['memberid'];
///////////////////// D  E  P  O  S  I   T    H  I  S  T  O  R  Y     BY    LOGGED    IN     PLAYER /////////////////////
$period = $_REQUEST['period'];
$date = $_REQUEST['date'];
$gametype = $_REQUEST['gametype'];
$qry = mysql_query("Select * from lottery_purchase where p_date = '".Date('Y-m-d', strtotime($date))."' and p_period = '".$period."' and p_gametype = '".$gametype."' and p_member_id = '".$mem_id."'");
//echo "(Select * from lottery_purchase where p_date = '".Date('Y-m-d', strtotime($date))."' and p_period = '".$period."' and p_gametype = '".$gametype."'";die;

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
	PERIOD: <?php echo $_REQUEST['period']; ?>
	<br><br>
	<table border="1" width="100%" cellpadding="4" cellspacing="0" bordercolor="#999999" id='tabeldata' class='table table-bordered table-hover center'>
		<tr class='bg-info'>
			<th STYLE="text-align:center">NO.</th>
			<th STYLE="text-align:center">Tanggal</th>
			<th STYLE="text-align:center">Game</th>
			<th STYLE="text-align:center">Tebak</th>
			<th STYLE="text-align:center">Posisi</th>
			<th STYLE="text-align:center">STATUS</th>
			<th STYLE="text-align:center">Taruhan</th>
			<th STYLE="text-align:center">Diskon</th>
			<th STYLE="text-align:center">Bayar</th>
			<th STYLE="text-align:center">X Menang</th>
		</tr>
		<tbody>
			<?php 
			$totalBetAmount = 0;
			$totalPayableAmount = 0;
			$totalWinAmount = 0;
			$i = 1;
			while($arrTransferHistory = mysql_fetch_array($qry)) {
				$totalBetAmount += $arrTransferHistory['p_bet_amount'];
				$totalPayableAmount += $arrTransferHistory['p_payble_amount'];
				
				$winAmount = 0;
				$pCategory = $arrTransferHistory['p_category'];
				$pGameType = $arrTransferHistory['p_gametype'];
				$pBetAmount = $arrTransferHistory['p_bet_amount'];
				$pPaybleAmount = $arrTransferHistory['p_payble_amount'];
				$pCrushType = $arrTransferHistory['p_crush_type'];
				$winCount = $arrTransferHistory['p_win_count'];
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
				
				$totalWinAmount += $winAmount;
		?>		
			<tr>
				<td class='history_detail_detail'><?php echo $i; ?></td>
				<td class='history_detail_detail' ><?php echo Date('d M Y - H:i:s', strtotime($arrTransferHistory['p_date'].$arrTransferHistory['p_time']));?></td>
				<td class='history_detail_detail' align='center'><?php echo $arrTransferHistory['p_gametype']; ?></td>
				<td class='history_detail_detail' align='center'><?php echo $arrTransferHistory['p_lottery_no']; ?></td>
				<td class='history_detail_detail' align='center'><?php echo $arrTransferHistory['p_position']; ?></td>
				<td class='history_detail_detail' align='center'><?php echo $arrTransferHistory['p_win_status'] == 'Y'?'Win':'Lost'; ?></td>
				<td class='history_detail_detail' align=right><?php echo $arrTransferHistory['p_bet_amount']; ?></td>
				<td class='history_detail_detail' align=right><?php echo $arrTransferHistory['p_discount']; ?></td>
				<td class='history_detail_detail' align=right><?php echo $arrTransferHistory['p_payble_amount']; ?></td>
				<td class='history_detail_detail' align=right><?php echo $winAmount; ?></td>
			</tr>
			<?php $i++;} ?>	
		</tbody>
		<tfoot>
			<tr class='bg-warning'>
				<td colspan="6">TOTAL</td>
				<td align="right"><?php echo $totalBetAmount; ?></td>
				<td align="right"></td>
				<td align="right"><?php echo $totalPayableAmount; ?></td>
				<td align="right"><?php echo $totalWinAmount; ?></td>
			</tr>
		</tfoot>	
	</table>
</div> 	
<br><br>
<hr/>
<?php include("includes/footer.php");?>
</body> 
</html>