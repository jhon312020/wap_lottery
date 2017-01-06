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
	
	$arrBettingLimit = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = 'Silang'"));
	$minbetAmount = $arrBettingLimit['bt_min_bet_amount'];
	if(!isset($minbetAmount)) {
		$minbetAmount = 0;
	}
	$maxbetAmount = $arrBettingLimit['bt_max_bet_amount'];
	if(!isset($maxbetAmount)) {
		$maxbetAmount = 0;
	}
	$arrSilangDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '13'"));
	$ressilang = mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = 'SILANG'");
	$resHomo = mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = 'HOMO'");
	
	if(isset($_REQUEST['key']) && $_REQUEST['key'] == "pSilang") {
		$totalPAmount = $_REQUEST['totalPay'];
		$modTotalPAmount = str_replace( ',', '', $totalPAmount);
		$availableBalace = $_REQUEST['avlbl_blnce'];
		
		$modTotalPAmount = $modTotalPAmount * 1 ;
		$availableBalace = $availableBalace * 1;
		
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$t=time();
		if($modTotalPAmount<$availableBalace) {
			$c = count($_REQUEST['betamount']);
			for($i=0;$i<$c;$i++) {
				$market = $_REQUEST['market'];
				$period = $_REQUEST['period'];
				$gameType = $_REQUEST['gametypeS'];
				$gamePosition = $_REQUEST['gmPos'][$i];
				$crushType = $_REQUEST['gmType'][$i];
				$betAmount = $_REQUEST['betamount'][$i];
				$modBetAmount = str_replace( ',', '', $betAmount);
				$kei = $_REQUEST['kei'][$i];
				$modKei = str_replace( ',', '', $kei);
				$paybleAmount = $_REQUEST['paybleamount'][$i];
				$modPaybleAmount = str_replace( ',', '', $paybleAmount);
				$purchaseDateTime = explode(" ", date('Y-m-d H:i:s'));
				$purchaseDate = $purchaseDateTime['0'];
				$purchaseTime = $purchaseDateTime['1'];
				
				if($_REQUEST['betamount'][$i]!='') {
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
					'".$modKei."',
					'".$modPaybleAmount."',
					'".$purchaseDate."',
					'".$purchaseTime."',
					'".$t."',
					'0',
					'0')");
				}
			}
			
			$d = count($_REQUEST['betamount2']);
			for($j=0;$j<$d;$j++) {
				$market2 = $_REQUEST['market'];
				$period2 = $_REQUEST['period'];
				$gameType2 = $_REQUEST['gametypeH'];
				$crushType2 = $_REQUEST['gmType2'][$j];
				$gamePosition2 = $_REQUEST['gmPos2'][$j];
				$betAmount2 = $_REQUEST['betamount2'][$j];
				$modBetAmount2 = str_replace( ',', '', $betAmount2);
				$kei2 = $_REQUEST['kei2'][$j];
				$modKei2 = str_replace( ',', '', $kei2);
				$paybleAmount2 = $_REQUEST['paybleamount2'][$j];
				$modPaybleAmount2 = str_replace( ',', '', $paybleAmount2);
				$purchaseDateTime2 = explode(" ", date('Y-m-d H:i:s'));
				$purchaseDate2 = $purchaseDateTime['0'];
				$purchaseTime2 = $purchaseDateTime['1'];
				
				if($_REQUEST['betamount2'][$j]!='') {
					$resPurchase2 = mysql_query("INSERT INTO lottery_purchase(
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
					'".$market2."',
					'".$period2."',
					'".$gameType2."',
					'',
					'".$gamePosition2."',
					'',
					'".$crushType2."',
					'".$modBetAmount2."',
					'".$modKei2."',
					'".$modPaybleAmount2."',
					'".$purchaseDate2."',
					'".$purchaseTime2."',
					'".$t."',
					'0',
					'0')");
				}
			}
			header("Location:confirm-purchase.php?market=".$marketName."&unique_key=".$t."&member_id=".$_SESSION['lottery']['memberid']);
			exit();
			} else {
			header("Location:error_purchase.php");
			exit();
		}
		
		
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once("includes/html_head.php");?>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.checkBetAmount').maskNumber({integer: true});
				$('.checkBetAmount2').maskNumber({integer: true});
				function calculateTotalBidAmount(){
					var totalBedAmount = 0;
					$(".checkBetAmount").each(function(key,elem) { 
						var inputBed = $(elem).val();
						var modInputBed = inputBed.replace(/,/g, '');
						if(modInputBed != '') {
							totalBedAmount += parseInt(modInputBed);
						}
					});
					$("#totalAmount1").val(totalBedAmount);
					var finalAmount = parseInt($("#totalAmount1").val()) + parseInt($("#totalAmount2").val())
					$("#t_betamount").val(finalAmount);
					$('#t_betamount').number( true, 0 );
				}
				
				function calculateTotalBidAmountBigSmall(){
					var totalBedAmount = 0;
					$(".checkBetAmount2").each(function(key,elem) { 
						var inputBed = $(elem).val();
						var modInputBed = inputBed.replace(/,/g, '');
						if(modInputBed != '') {
							totalBedAmount += parseInt(modInputBed);
						}
					});
					
					$("#totalAmount2").val(totalBedAmount);
					var finalAmount = parseInt($("#totalAmount1").val()) + parseInt($("#totalAmount2").val())
					$("#t_betamount").val(finalAmount);
					$('#t_betamount').number( true, 0 );
				}
				
				function calculateTotalPaybleAmount() {
					var totalPayble = 0;
					$(".checkPaybleAmount").each(function(key,elem) {
						var inputPaybleAmount = $(elem).val();
						var modInputPaybleAmount = inputPaybleAmount.replace(/,/g, ''); 
						if(modInputPaybleAmount != '') {
							totalPayble += parseInt(modInputPaybleAmount);
						}
					});
					$("#t_paybleamount").val(totalPayble);
					$('#t_paybleamount').number( true, 0 );
				}
				
				function calculateTotalDiscount() {
					var totalDiscount = 0;
					$(".checkDiscount").each(function(key,elem) {
						var inputDiscount = $(elem).val();
						var modInputDiscount = inputDiscount.replace(/,/g, ''); 
						if(modInputDiscount != '') {
							totalDiscount += parseInt(modInputDiscount);
						}
					});
					$("#t_discount").val(totalDiscount);
					$("#t_discount").number( true, 0 );
				}
				
				$(".checkBetAmount").blur(function(){
					var betamount = $(this).val();
					var modbetamount = betamount.replace(/\,/g , "");
					var cPosition = $(this).closest('td').prev('td').prev('td').text();
					var betField = $(this).parent().find('.checkBetAmount');
					var nextTextFieldId = $(this).parent().next().find(':text');
					var nextTextFieldId2 =  $(this).parent().next().next().find(':text');
					
					<?php if(isset($minbetAmount) && $minbetAmount!=0) {?>
						if(modbetamount < <?php echo $minbetAmount?>) {
							alert("Minimum Bet Amount is : <?php echo $minbetAmount?>");
							betField.val('');
						}
					<?php }?>
					
					<?php if(isset($maxbetAmount) && $maxbetAmount!=0) {?>
						if(modbetamount > <?php echo $maxbetAmount?>) {
							alert("Maximum Bet Amount is :<?php echo $maxbetAmount?>");
							betField.val('');
						}
					<?php }?>
					<?php if(isset($minbetAmount) && $maxbetAmount) {?>
						if(modbetamount >= <?php echo $minbetAmount?> && modbetamount <= <?php echo $maxbetAmount?>) {
							$.ajax({
								type:'POST',
								url:"fetch_silang_discount_price_ajax.php",
								data:"action=fetchdiscount&bet_amount="+modbetamount+"&cPosition="+cPosition+"&market="+$("#marketn").val()+"&gameType="+$("#gametypeC").val(),
								success:function(result) {
									//alert(result);
									var g = result.split('^');
									nextTextFieldId.val(g[2]);
									nextTextFieldId2.val(g[3]);
									calculateTotalBidAmount();
									calculateTotalDiscount();
									calculateTotalPaybleAmount();
								}
							})
						}
						<?php } else {?>
						$.ajax({
							type:'POST',
							url:"fetch_silang_discount_price_ajax.php",
							data:"action=fetchdiscount&bet_amount="+modbetamount+"&cPosition="+cPosition+"&market="+$("#marketn").val()+"&gameType="+$("#gametypeC").val(),
							success:function(result) {
								var g = result.split('^');
								nextTextFieldId.val(g[2]);
								nextTextFieldId2.val(g[3]);
								calculateTotalBidAmount();
								calculateTotalDiscount();
								calculateTotalPaybleAmount();
							}
						})
					<?php }?>
				});
				$(".checkBetAmount2").blur(function(){
					var betamount = $(this).val();
					var modbetamount = betamount.replace(/\,/g , "");
					//var ckei = $(this).closest('td').prev('td').text();
					var cPosition = $(this).closest('td').prev('td').prev('td').text();
					//alert(cPosition);
					var betField = $(this).parent().find('.checkBetAmount2');
					var nextTextFieldId = $(this).parent().next().find(':text');
					var nextTextFieldId2 =  $(this).parent().next().next().find(':text');
					<?php if(isset($minbetAmount) && $minbetAmount!=0) {?>
						if(modbetamount < <?php echo $minbetAmount?>) {
							alert("Minimum Bet Amount is :<?php echo $minbetAmount?>");
							betField.val('');
						}
					<?php }?>
					<?php if(isset($maxbetAmount) && $maxbetAmount!=0) {?>
						if(modbetamount > <?php echo $maxbetAmount?>) {
							alert("Maximum Bet Amount is :<?php echo $maxbetAmount?>");
							betField.val('');
						}
					<?php }?>
					<?php if(isset($minbetAmount) && $maxbetAmount) {?>
						if(modbetamount >= <?php echo $minbetAmount?> && modbetamount <= <?php echo $maxbetAmount?>) {
							$.ajax({
								type:'POST',
								url:"fetch_silang_discount_price_ajax.php",
								data:"action=fetchdiscount&bet_amount="+modbetamount+"&cPosition="+cPosition+"&market="+$("#marketn").val()+"&gameType="+$("#gametypeH").val(),
								success:function(result) {
									var g = result.split('^');
									nextTextFieldId.val(g[2]);
									nextTextFieldId2.val(g[3]);
									calculateTotalBidAmountBigSmall();
									calculateTotalDiscount();
									calculateTotalPaybleAmount();
								}
							})
						}
						<?php } else {?>
						$.ajax({
							type:'POST',
							url:"fetch_silang_discount_price_ajax.php",
							data:"action=fetchdiscount&bet_amount="+modbetamount+"&cPosition="+cPosition+"&market="+$("#marketn").val()+"&gameType="+$("#gametypeH").val(),
							success:function(result) {
								var g = result.split('^');
								nextTextFieldId.val(g[2]);
								nextTextFieldId2.val(g[3]);
								calculateTotalBidAmountBigSmall();
								calculateTotalDiscount();
								calculateTotalPaybleAmount();
							}
						})
					<?php }?>
				});
			});
		</script>
	</head>
	<body>
		
		<!--end of page head!-->
		<?php require_once("includes/header.php");?>
		<div class="container-fluid main-body-area  menu-padding">
			<div class="container">
				<div class="col-md-12  scroll-text-area  rdc-padding">
					<div class="col-md-2 arrow-right">
						<p style="margin-top:25px; color:#fff;">Information</p>
					</div>
					<div class="col-md-10 scr-pd-left">
						<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor Aenean massa.</p>
					</div>
				</div>
				<!--Start of game Page-->
				<div class="col-md-12 mrg-top-20 game-page-area" >
					<?php require_once("includes/left_panel_game.php");?>
					<?php $availableBalance = $remainingBalance + $winSum; ?>
					<!--end-of col-md-3-->
					<div class="col-md-9 rdc-padding">
						<div class="information-page-area">
							<?php require_once("includes/top_game_category.php"); ?>
							<!--end-of header-->
							<div  class="clear"></div>
							<div  class="col-md-12 mrg-top-20">
								<div class="game-body" >
									<h1>SILANG : HOMO<br>
									<small style="font-size:14px;">Menebak silang atau homo</small></h1>
									<div class="col-md-12 " style="border-bottom: 1px dotted #1B1C21;">
										<p class="pull-left" >Period : <?php echo $period;?>&nbsp; (<?php echo $marketName;?>)</p>
									</div>
									<!--end-of col-md-12-->
									
									<div class="col-md-12 mrg-top-20 ">
										<form role="form" name="silangForm" id="silangForm" action="" method="POST">
											<input type="button" name="simpan" class="game-more-btn pull-right" onclick = this.form.submit(); value="SAVE">
											<input type="hidden" name="totalAmount1" id="totalAmount1" value="0">
											<input type="hidden" name="totalAmount2" id="totalAmount2" value="0">
											<input type="hidden" name="market" id="marketn" value="<?php echo $marketName;?>">
											<input type="hidden" name="avlbl_blnce" value="<?php echo $availableBalance;?>">
											<input type="hidden" name="period" value="<?php echo $period; ?>">
											<input type="hidden" name="gametypeS" id="gametypeC" value="SILANG">
											<input type="hidden" name="gametypeH" id="gametypeH" value="HOMO">
											<input type="hidden" name="key" value="pSilang">
											<div class="table-responsive">
												<table class="table table-bordered">
													<tbody>
														<tr>
															<td colspan="7" class="even-header">SILANG</td>
														</tr>
														<tr>
															<th>NO</th>
															<th style="width:100px;" >CRUSH</th>
															<th style="width:100px;" >KEI</th>
															<th>BET</th>
															<th>DISCOUNT & KEI</th>
															<th>PAY</th>
														</tr>
														<?php 
															$i = 1;
															while($rowAllSilang = mysql_fetch_array($ressilang)) {
															?>
															<input type="hidden" name="gmType[]" value="SILANG">
															<input type="hidden" name="gmPos[]" value="<?php echo $rowAllSilang['g_name']; ?>">
															<tr>
																<td><?php echo $i;?></td>
																<td><?php echo $rowAllSilang['g_name']; ?></td>
																<td><?php echo $rowAllSilang['g_kei']; ?></td>
																<td><input type="text" class="form-control  rdc-padding checkBetAmount" name="betamount[]" id="betamount_<?php echo $i;?>" data-thousands=","></td>
																<td><input type="text" class="form-control rdc-padding checkDiscount" name="kei[]" id="kei_<?php echo $i;?>" readonly="readonly"></td>
																<td><input type="text" class="form-control rdc-padding checkPaybleAmount" name="paybleamount[]" id="paybleamount_<?php echo $i;?>" readonly="readonly"></td>
															</tr>
														<?php $i++; }?>
														<tr>
															<td colspan="7" class="even-header">HOMO</td>
														</tr>
														<tr>
															<th>NO</th>
															<th >CRUSH</th>
															<th >KEI</th>
															<th>BET</th>
															<th>DISCOUNT & KEI</th>
															<th>PAY</th>
														</tr>
														<?php 
															$k = 1;
															while($rowAllHomoSilang = mysql_fetch_array($resHomo)) {	
															?>
															<input type="hidden" name="gmType2[]" value="HOMO">
															<input type="hidden" name="gmPos2[]" value="<?php echo $rowAllHomoSilang['g_name']; ?>">
															<tr>
																<td><?php echo $k;?></td>
																<td><?php echo $rowAllHomoSilang['g_name']; ?></td>
																<td><?php echo $rowAllHomoSilang['g_kei']; ?></td>
																<td><input type="text" class="form-control  rdc-padding checkBetAmount2" name="betamount2[]" id="betamount_<?php echo $k;?>" data-thousands=","></td>
																<td><input type="text" class="form-control rdc-padding checkDiscount" readonly="readonly" name="kei2[]" id="kei_<?php echo $k;?>"></td>
																<td><input type="text" class="form-control rdc-padding checkPaybleAmount" readonly="readonly" name="paybleamount2[]" id="paybleamount_<?php echo $k;?>"></td>
															</tr>
														<?php $k++; }?>
														<tr style="background:#333333; color:#FFFFFF">
															<td colspan="3" align="center" style="border:none;">Total</td>
															<td style="border:none;"><input type="text" class="form-control" id="t_betamount"></td>
															<td style="border:none;"><input type="text" class="form-control" id="t_discount"></td>
															<td style="border:none;"><input type="text" class="form-control" name="totalPay" id="t_paybleamount"></td>
														</tr>
													</tbody>
												</table>
												<div class="col-md-12">
													<input type="button" name="simpan" class="game-more-btn pull-right" onclick = this.form.submit(); value="SAVE">
												</div>
											</div>
											<!-------end of table------>
										</form>
										<?php echo $arrSilangDetails['cms_page_details']; ?>
										<!--end-of col-md-12--> 
									</div>
									<!--end-of col-md-12-->
									<div class="clear"></div>
								</div>
								<!--end-of game-body--> 
							</div>
							<div class="clear"></div>
						</div>
						<!--end-of information-page-area--> 
					</div>
					<!--end-of col-md-9--> 
					
					<!--end of col-md-12-->
					<div class="clear"></div>
				</div>
				<!--end of game-area-->
				<?php require_once("includes/footer.php");?>
				<!--end-of col-md-12--> 
				
			</div>
			<!--end-of container--> 
		</div>
		<!--end-of container-fluid main-body-area-->
	</body>
</html>