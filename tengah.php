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
	
	$arrBettingLimit = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = 'Tengah'"));
	$minbetAmount = $arrBettingLimit['bt_min_bet_amount'];
	if(!isset($minbetAmount)) {
		$minbetAmount = 0;
	}
	$maxbetAmount = $arrBettingLimit['bt_max_bet_amount'];
	if(!isset($maxbetAmount)) {
		$maxbetAmount = 0;
	}
	
	$arrTengahDetails  = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '9'"));
	$arrTengahKei = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = 'Tengah' and g_name = 'Tengah'"));
	$arrTepiKei = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = 'Tengah' and g_name = 'Tepi'"));
	if(isset($_REQUEST['key']) && $_REQUEST['key'] == "pTengah") {
		$totalPAmount = $_REQUEST['totalPay'];
		$modTotalPAmount = str_replace( ',', '', $totalPAmount);
		$availableBalace = $_REQUEST['avlbl_blnce'];
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$t=time();
		$arrCrushPosition = array();
		foreach($_REQUEST as $key=>$val) {
			$b = explode('_',$key);
			if($b[0]=='crush'&&$b[1]=='position') {
				$arrCrushPosition[$b[2]] = $val;
			}
		}
		//print_r($arrCrushPosition);
		if($modTotalPAmount<$availableBalace) {
			$c = count($arrCrushPosition);
			for($i=0;$i<$c;$i++) {
				$market = $_REQUEST['market'];
				$period = $_REQUEST['period'];
				$gameType = $_REQUEST['gametype'];
				$lotteryPosition = $arrCrushPosition[$i];
				$betAmount = $_REQUEST['betamount'][$i];
				$modBetAmount = str_replace( ',', '', $betAmount);
				$discount = $_REQUEST['kei_discount'][$i];
				$modDiscount = str_replace( ',', '', $discount);
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
				'".$lotteryPosition."',
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
				function calculateTotalBidAmount(){
					var totalBedAmount = 0;
					$(".checkBetAmount").each(function(key,elem) { 
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
				$(".checkBetAmount").blur(function(){
					var betamount = $(this).val();
					var modbetamount = betamount.replace(/\,/g , "");
					var betField = $(this).parent().find('.checkBetAmount');
					var b = this.id.split('_');		
					if(!$("input[name=crush_position_"+b[1]+"]:checked").val()) {
						alert('Please Check One Position!');
						betField.val('');
						return false;
						
					}
					var pos = $("input[name=crush_position_"+b[1]+"]:checked").val();
					//alert(pos);
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
								url:"fetch_tengah_discount_price_ajax.php",
								data:"action=fetchdiscount&bet_amount="+modbetamount+"&pos="+pos+"&market="+$("#marketn").val()+"&gameType="+$("#gametypeC").val(),
								success:function(result) {
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
							url:"fetch_tengah_discount_price_ajax.php",
							data:"action=fetchdiscount&bet_amount="+modbetamount+"&pos="+pos+"&market="+$("#marketn").val()+"&gameType="+$("#gametypeC").val(),
							success:function(result) {
								var b = result.split('^');
								nextTextFieldId.val(b[2]);
								nextTextFieldId2.val(b[3]);
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
									<h1>TENGAH - TEPI<br>
									<small style="font-size:14px;">Central guess or edges of 2D</small></h1>
									<div class="col-md-12 " style="border-bottom: 1px dotted #1B1C21;">
										<p class="pull-left" >Period : <?php echo $period;?>&nbsp; (<?php echo $marketName;?>)</p>
									</div>
									<!--end-of col-md-12-->
									
									<div class="col-md-12 mrg-top-20 ">
										<form role="form" name="tengahForm" id="tengahForm" action="" method="POST">
											<input type="button" name="simpan" class="game-more-btn pull-right" onclick = this.form.submit(); value="SAVE">
											<input type="hidden" name="market" id="marketn" value="<?php echo $marketName;?>">
											<input type="hidden" name="avlbl_blnce" value="<?php echo $availableBalance;?>">
											<input type="hidden" name="period" value="<?php echo $period; ?>">
											<input type="hidden" name="gametype" id="gametypeC" value="Tengah">
											<input type="hidden" name="key" value="pTengah">
											
											<div class="table-responsive">
												<table class="table table-bordered">
													<thead>
														<tr>
															<th>No</th>
															<th style="width:200px;">CRUSH</th>
															<th style="width:200px;">KEI</th>
															<th>BET</th>
															<th>DISCOUNT & KEI</th>
															<th>PAY</th>
														</tr>
													</thead>
													<tbody>
														<?php for($j=0; $j<10; $j++) {
														?>
														<tr>
															<td><?php echo $j+1;?></td>
															<td>
																<label class="radio-inline">
																	<input type="radio" name="crush_position_<?php echo $j;?>" id="crush_position_<?php echo $j;?>_1" value="tengah">
																Tengah </label>
																<label class="radio-inline">
																	<input type="radio" name="crush_position_<?php echo $j;?>" id="crush_position_<?php echo $j;?>_2" value="tepi">
																Tepi </label>
															</td>
															<td><span class="input-width"><?php echo $arrTengahKei['g_kei']; ?></span> <span class="input-width"><?php echo $arrTepiKei['g_kei']; ?></span></td>
															<td><input type="text" class="form-control  rdc-padding checkBetAmount" name="betamount[]" id="betamount_<?php echo $j;?>" data-thousands=","></td>
															<td><input type="text" class="form-control rdc-padding checkDiscount" name="kei_discount[]" id="kei_discount_<?php echo $j;?>" readonly="readonly"></td>
															<td><input type="text" class="form-control rdc-padding checkPaybleAmount" name="payble_amount[]" id="payble_amount_<?php echo $j;?>" readonly="readonly"></td>
														</tr>
														<?php }?>  
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
										<?php echo $arrTengahDetails['cms_page_details']; ?>
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