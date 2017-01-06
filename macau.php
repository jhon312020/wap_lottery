<?php require_once("includes/head.php");
	require_once("includes/check-authentication.php");
	$arrMacauDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '6'"));
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
	
	$arrBettingLimit = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = 'Macau'"));
	$minbetAmount = $arrBettingLimit['bt_min_bet_amount'];
	if(!isset($minbetAmount)) {
		$minbetAmount = 0;
	}
	$maxbetAmount = $arrBettingLimit['bt_max_bet_amount'];
	if(!isset($maxbetAmount)) {
		$maxbetAmount = 0;
	}
	if(isset($_REQUEST['key']) && $_REQUEST['key'] == "pmacau") {
		$totalPAmount = $_REQUEST['totalPay'];
		$modTotalPAmount = str_replace( ',', '', $totalPAmount);
		$availableBalace = $_REQUEST['avlbl_blnce'];
		
		$modTotalPAmount = $modTotalPAmount * 1 ;
		$availableBalace = $availableBalace * 1;
		
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$t=time();
		
		if($modTotalPAmount<$availableBalace) {
			$c = count($_REQUEST['lotto_first_no']);
			for($i=0; $i<$c;$i++) {
				$market = $_REQUEST['market'];
				$period = $_REQUEST['period'];
				$gameType = $_REQUEST['gametype'];
				$lotteryNumber = $_REQUEST['lotto_first_no'][$i].$_REQUEST['lotto_second_no'][$i];
				$betAmount = $_REQUEST['bet'][$i];
				$modBetAmount = str_replace( ',', '', $betAmount);
				$discount = $_REQUEST['discount'][$i];
				$modDiscount = str_replace( ',', '', $discount);
				$paybleAmount = $_REQUEST['payble_amount'][$i];
				$modPaybleAmount = str_replace( ',', '', $paybleAmount);
				$purchaseDateTime = explode(" ", date('Y-m-d H:i:s'));
				$purchaseDate = $purchaseDateTime['0'];
				$purchaseTime = $purchaseDateTime['1'];
				
				if($_REQUEST['lotto_first_no'][$i]!="" && $_REQUEST['lotto_second_no'][$i]!='' && $_REQUEST['bet'][$i]!='') {
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
				$('.checkBet').maskNumber({integer: true});
				
				function calculateTotalBidAmount(){
					var totalBedAmount = 0;
					$(".checkBet").each(function(key,elem) { 
						var inputBed = $(elem).val();
						//var modInputBed = inputBed.replace(",","");  
						var modInputBed = inputBed.replace(/,/g, '');
						if(modInputBed != '') {
							totalBedAmount += parseInt(modInputBed);
						}
					});
					$("#t_betamount").val(totalBedAmount);
					$('#t_betamount').number( true, 0 );
				}
				
				function calculateTotalDiscount() {
					var totalDiscount = 0;
					$(".checkDiscount").each(function(key,elem) {
						var inputDiscount = $(elem).val();
						//var modInputDiscount = inputDiscount.replace(",",""); 
						var modInputDiscount = inputDiscount.replace(/,/g, '');
						if(modInputDiscount != '') {
							totalDiscount += parseInt(modInputDiscount);
						}
					});
					$("#t_discount").val(totalDiscount);
					$("#t_discount").number( true, 0 );
				}
				
				function calculateTotalPaybleAmount() {
					var totalPayble = 0;
					$(".checkPaybleAmount").each(function(key,elem) {
						var inputPaybleAmount = $(elem).val();
						//var modInputPaybleAmount = inputPaybleAmount.replace(",",""); 
						var modInputPaybleAmount = inputPaybleAmount.replace(/,/g, '');
						if(modInputPaybleAmount != '') {
							totalPayble += parseInt(modInputPaybleAmount);
						}
					});
					$("#t_paybleamount").val(totalPayble);
					$("#t_paybleamount").number( true, 0 );
				}
				$(".Drop").change(function() {
					var n = this.id.split('_');
					var number = $("#lotto_first_no_"+n[3]).val();
					var opt = $('#lotto_second_no_'+n[3]+' option[disabled]').val();
					$('#lotto_second_no_'+n[3]+' option[value="'+opt+'"]').attr("disabled", false);
					$('#lotto_second_no_'+n[3]+' option[value="'+number+'"]').attr("disabled", true);
					
				})
				
				$(".Drop2").change(function() {
					var n = this.id.split('_');
					var number = $("#lotto_second_no_"+n[3]).val();
					var opt = $('#lotto_first_no_'+n[3]+' option[disabled]').val();
					$('#lotto_first_no_'+n[3]+' option[value="'+opt+'"]').attr("disabled", false);
					$('#lotto_first_no_'+n[3]+' option[value="'+number+'"]').attr("disabled", true);
					
				})
				
				$(".checkBet").blur(function(){
					var betamount = $(this).val();
					var modbetamount = betamount.replace(/\,/g , "");
					//alert(modbetamount);
					var betField = $(this).parent().find('.checkBet');
					var nextTextFieldId = $(this).parent().next().find(':text');
					var nextTextFieldId2 =  $(this).parent().next().next().find(':text');
					<?php if(isset($minbetAmount) && $minbetAmount!=0) {?>
						if(modbetamount < <?php echo $minbetAmount?>) {
							alert("Minimum Bet Amount is <?php echo $minbetAmount?>");
							betField.val('');
						}
					<?php }?>
					
					<?php if(isset($maxbetAmount) && $maxbetAmount!=0) {?>
						if(modbetamount > <?php echo $maxbetAmount?>) {
							alert("Maximum Bet Amount is <?php echo $maxbetAmount?>");
							betField.val('');
						}
					<?php }?>
					<?php if(isset($minbetAmount) && $maxbetAmount) {?>
						if(modbetamount >=<?php echo $minbetAmount?> && modbetamount<=<?php echo $maxbetAmount?>) {
							$.ajax({
								type:'POST',
								url:"fetch_macau_discount_price_ajax.php",
								data:"action=fetchdiscount&bet_amount="+modbetamount+"&market="+$("#marketn").val()+"&gameType="+$("#gametypeC").val(),
								success:function(result) {
									//alert(result);
									var b = result.split('^');
									nextTextFieldId.val(b[2]);
									nextTextFieldId2.val(b[3]);
									calculateTotalBidAmount();
									calculateTotalDiscount();
									calculateTotalPaybleAmount();
								}
							})
						}
						<?php } else {?>
						$.ajax({
							type:'POST',
							url:"fetch_macau_discount_price_ajax.php",
							data:"action=fetchdiscount&bet_amount="+modbetamount+"&market="+$("#marketn").val()+"&gameType="+$("#gametypeC").val(),
							success:function(result) {
								var b = result.split('^');
								nextTextFieldId.val(b[0]);
								nextTextFieldId2.val(b[1]);
								calculateTotalBidAmount();
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
		<?php require_once("includes/header.php");?>
		<!--end of page head!-->
		
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
									<h1>MACAU - Plug-FREE 2D<br>
									<small style="font-size:14px;">Guessing Free 2 digits of the 4 digits output</small></h1>
									<div class="col-md-12 " style="border-bottom: 1px dotted #1B1C21;">
										<p class="pull-left" >Period : <?php echo $period;?>&nbsp; (<?php echo $marketName;?>)</p>
									</div>
									<!--end-of col-md-12-->
									
									<div class="col-md-12 mrg-top-20">
										<form role="form" name="macauForm" id="macauForm" action="" method="POST">
											<input type="button" name="simpan" class="game-more-btn pull-right" onclick = this.form.submit(); value="SAVE">
											<input type="hidden" name="market" id="marketn" value="<?php echo $marketName;?>">
											<input type="hidden" name="avlbl_blnce" value="<?php echo $availableBalance;?>">
											<input type="hidden" name="period" value="<?php echo $period; ?>">
											<input type="hidden" name="gametype" id="gametypeC" value="Macau">
											<input type="hidden" name="key" value="pmacau">
											<div class="table-responsive">
												<table class="table table-bordered">
													<thead>
														<tr>
															<th>No</th>
															<th style="width:150px;">CRUSH</th>
															<th>BET</th>
															<th>DISCOUNT</th>
															<th>PAY</th>
														</tr>
													</thead>
													<tbody>
														<?php for($j=1; $j<11; $j++) {?>
															<tr>
																<td><?php echo $j;?></td>
																<td><select class="form-control input-width Drop" name="lotto_first_no[]" id="lotto_first_no_<?php echo $j?>">
																	<option value=""></option>
																	<option value="0">0</option>
																	<option value="1">1</option>
																	<option value="2">2</option>
																	<option value="3">3</option>
																	<option value="4">4</option>
																	<option value="5">5</option>
																	<option value="6">6</option>
																	<option value="7">7</option>
																	<option value="8">8</option>
																	<option value="9">9</option>
																</select>
																<select class="form-control input-width Drop2" name="lotto_second_no[]" id="lotto_second_no_<?php echo $j?>">
																	<option value=""></option>
																	<option value="0">0</option>
																	<option value="1">1</option>
																	<option value="2">2</option>
																	<option value="3">3</option>
																	<option value="4">4</option>
																	<option value="5">5</option>
																	<option value="6">6</option>
																	<option value="7">7</option>
																	<option value="8">8</option>
																	<option value="9">9</option>
																</select></td>
																<td><input type="text" class="form-control checkBet" name="bet[]" id="bet_<?php echo $j?>" data-thousands=","></td>
																<td><input type="text" class="form-control checkDiscount" name="discount[]" id="discount_<?php echo $j?>" readonly="readonly"></td>
																<td><input type="text" class="form-control checkPaybleAmount" name="payble_amount[]" id="payble_amount_<?php echo $j?>" readonly="readonly"></td>
															</tr>
														<?php }?>
														<tr style="background:#333333; color:#FFFFFF">
															<td colspan="2" align="center" style="border:none;">Total</td>
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
										<!-- <div class="col-md-12">
											<p style="color: #121217;"><span style="width:120px; float:left; ">Discount </span><span style="width:15px; float:left;">:</span> 0% + KEI</p>
											<p style="color: #121217;"><span style="width:120px; float:left;">Gift </span><span style="width:15px; float:left;">:</span>1x plus Capital.</p>
											<p style="color: #121217;"><span style="width:120px; float:left;">Min BET </span><span style="width:15px; float:left;">:</span>10,000</p>
											<p style="color: #121217;"><span style="width:120px; float:left; ">Max BET </span><span style="width:15px; float:left;">:</span>20,000,000</p>
											<p style="color: #121217;"><span style="width:120px; float:left; ">BET Multiples </span><span style="width:15px; float:left;">:</span>10,000</p>
											</div>
											<div class="col-md-12">
											<p>- Please check back BET, Discounts, and multiplication Your victory. </p>
											<p>- This bet is SAH and Irrevocable </p>
											<p>- Betting considered deviant, Hack, Inject, will be canceled and DELETE without Confirmation. </p>
											<p>- If an error occurs BET, please immediately confirm to CS duty </p>
											<p>- Errors were confirmed after PERIOD in the lot will not be served. </p>
										</div>-->
										<?php echo $arrMacauDetails['cms_page_details']; ?>
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