<?php require_once("includes/head.php");
	require_once("includes/check-authentication.php");
	$arrColokNagaDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '7'"));
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
	
	$arrBettingLimit = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = 'Colok Naga'"));
	//print_r($arrBettingLimit); exit;
	$minbetAmount = $arrBettingLimit['bt_min_bet_amount'];
	if(!isset($minbetAmount)) {
		$minbetAmount = 0;
	}
	$maxbetAmount = $arrBettingLimit['bt_max_bet_amount'];
	if(!isset($maxbetAmount)) {
		$maxbetAmount = 0;
	}
	
	
	if(isset($_REQUEST['key']) && $_REQUEST['key'] == "pColokNaga") {
		
		$error_code = $_REQUEST['error_code'];
		if($error_code == '0')
		{
		$totalPAmount = $_REQUEST['totalPay'];
		$modTotalPAmount = str_replace( ',', '', $totalPAmount);
		$availableBalace = $_REQUEST['avlbl_blnce'];
		
		$modTotalPAmount = $modTotalPAmount * 1 ;
		$availableBalace = $availableBalace * 1;
		
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$t=time();
		if($modTotalPAmount<$availableBalace) {
			$c = count($_REQUEST['digit1']);
			for($i=0;$i<$c;$i++) {
				$market = $_REQUEST['market'];
				$period = $_REQUEST['period'];
				$gameType = $_REQUEST['gametype'];
				$lotteryNumber = $_REQUEST['digit1'][$i].$_REQUEST['digit2'][$i].$_REQUEST['digit3'][$i];
				$betAmount = $_REQUEST['bet'][$i];
				$modBetAmount = str_replace( ',', '', $betAmount);
				$discount = $_REQUEST['discount'][$i];
				$modDiscount = str_replace( ',', '', $discount);
				$paybleAmount = $_REQUEST['pay_amount'][$i];
				$modPaybleAmount = str_replace( ',', '', $paybleAmount);
				$purchaseDateTime = explode(" ", date('Y-m-d H:i:s'));
				$purchaseDate = $purchaseDateTime['0'];
				$purchaseTime = $purchaseDateTime['1'];
				
				if($lotteryNumber != '' && $betAmount!='') {
					
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
				
				/*
				$(".Drop").change(function() {
					var n = this.id.split('_');
					var number = $("#digit1_"+n[1]).val();
					$('#digit2_'+n[1]).val('');
					$('#digit3_'+n[1]).val('');
					var opt = $('#digit2_'+n[1]+' option[disabled]').val();
					$('#digit2_'+n[1]+' option[value="'+opt+'"]').attr("disabled", false);
					$('#digit2_'+n[1]+' option[value="'+number+'"]').attr("disabled", true);
					var opt1 = $('#digit3_'+n[1]+' option[disabled]').val();
					$('#digit3_'+n[1]+' option[value="'+opt+'"]').attr("disabled", false);
					$('#digit3_'+n[1]+' option[value="'+number+'"]').attr("disabled", true);
					
				})
				
				$(".Drop2").change(function() {
					var n = this.id.split('_');
					var number = $("#digit2_"+n[1]).val();
					var opt = $('#digit1_'+n[1]+' option[disabled]').val();
					$('#digit1_'+n[1]+' option[value="'+opt+'"]').attr("disabled", false);
					$('#digit1_'+n[1]+' option[value="'+number+'"]').attr("disabled", true);
					var opt1 = $('#digit3_'+n[1]+' option[disabled]').val();
					$('#digit3_'+n[1]+' option[value="'+opt1+'"]').attr("disabled", false);
					$('#digit3_'+n[1]+' option[value="'+number+'"]').attr("disabled", true);
					
				})
				
				$(".Drop3").change(function() {
					var n = this.id.split('_');
					var number = $("#digit3_"+n[1]).val();
					var opt = $('#digit2_'+n[1]+' option[disabled]').val();
					$('#digit2_'+n[1]+' option[value="'+opt+'"]').attr("disabled", false);
					$('#digit2_'+n[1]+' option[value="'+number+'"]').attr("disabled", true);
					var opt1 = $('#digit1_'+n[1]+' option[disabled]').val();
					$('#digit1_'+n[1]+' option[value="'+opt1+'"]').attr("disabled", false);
					$('#digit1_'+n[1]+' option[value="'+number+'"]').attr("disabled", true);
					
				});
				*/
				
				$('.xDrop').change(function(){
				
					var n = this.id.split('_');
					
					
					offset = n[0];
					index = n[1];
					
					$('#digit1_'+index+' option').attr('disabled',false);
					$('#digit2_'+index+' option').attr('disabled',false);
					$('#digit3_'+index+' option').attr('disabled',false);
					
					var number1 = $("#digit1_"+index).val();
					var number2 = $("#digit2_"+index).val();
					var number3 = $("#digit3_"+index).val();
					
					var offset = offset.replace('digit','');
					
					//alert(offset+" "+number1+" "+number2+" "+number3+" "+index);
					
					if(offset == '1'){
						
						$('#digit1_'+index).find('option').each(function(){
							var val = $(this).attr('value');
							if(val == number2 || val == number3)
							$(this).attr('disabled',true);
						});
						$('#digit2_'+index).find('option').each(function(){
							var val = $(this).attr('value');
							if(val == number1 || val == number2 || val == number3)
							$(this).attr('disabled',true);
						});
						$('#digit3_'+index).find('option').each(function(){
							var val = $(this).attr('value');
							if(val == number1 || val == number2 || val == number3)
							$(this).attr('disabled',true);
						}); 
					}
					if(offset == '2'){
					
						$('#digit1_'+index).find('option').each(function(){
							var val = $(this).attr('value');
							if(val == number1 || val == number2 || val == number3)
							$(this).attr('disabled',true);
						});
						$('#digit2_'+index).find('option').each(function(){
							var val = $(this).attr('value');
							if(val == number1 || val == number3)
							$(this).attr('disabled',true);
						});
						$('#digit3_'+index).find('option').each(function(){
							var val = $(this).attr('value');
							if(val == number1 || val == number2 || val == number3)
							$(this).attr('disabled',true);
						}); 

					}
					if(offset == '3'){
						
						$('#digit1_'+index).find('option').each(function(){
							var val = $(this).attr('value');
							if(val == number1 || val == number2 || val == number3)
							$(this).attr('disabled',true);
						});
						$('#digit2_'+index).find('option').each(function(){
							var val = $(this).attr('value');
							if(val == number1 || val == number2 || val == number3)
							$(this).attr('disabled',true);
						});
						$('#digit3_'+index).find('option').each(function(){
							var val = $(this).attr('value');
							if(val == number1 || val == number2)
							$(this).attr('disabled',true);
						});
					} 
					
				});
				
				
				
				$("#colokNForm").submit(function(){
					var error_code = 0;
					 
					$(".checkBetAmount").each(function(key,elem) { 
						$('#xError').val('0');
						
						var val = $(elem).val();
						
						if(val != ''){
							var drop1 =  $(elem).parents('tr').find('.Drop').val();
							var drop2 =  $(elem).parents('tr').find('.Drop2').val();
							var drop3 =  $(elem).parents('tr').find('.Drop3').val();
							
							if(drop1 == '' || drop2 == '' || drop3 == '' || drop1 == null || drop2 == null || drop3 == null ){
								alert("Please Select 3 digit first");
								$('#xError').val('1');
								error_code++;
								return;
							}
						}
					});
					
					if(error_code > 0){ 
						return false;
					}
					
						$('.xDrop').find('option').each(function(){
							$(this).attr('disabled',false);
						});
						
				});
				
				
				$(".checkBetAmount").blur(function(){
					var betamount = $(this).val();
					var modbetamount = betamount.replace(/\,/g , "");
					var betField = $(this).parent().find('.checkBetAmount');
					var nextTextFieldId = $(this).parent().next().find(':text');
					var nextTextFieldId2 =  $(this).parent().next().next().find(':text');
					
					
					var drop1 =  $(this).parents('tr').find('.Drop').val();
					var drop2 =  $(this).parents('tr').find('.Drop2').val();
					var drop3 =  $(this).parents('tr').find('.Drop3').val();
					
					
					//alert(nextTextFieldId);
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
								url:"fetch_colk_naga_discount_price_ajax.php",
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
							url:"fetch_colk_naga_discount_price_ajax.php",
							data:"action=fetchdiscount&bet_amount="+modbetamount+"&market="+$("#marketn").val()+"&gameType="+$("#gametypeC").val(),
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
									<h1>COLOK NAGA - COLOK BEBAS 3D<br>
									<small style="font-size:14px;">Non guess 3 numbers between 4 digit output</small></h1>
									<div class="col-md-12 " style="border-bottom: 1px dotted #1B1C21;">
										<p class="pull-left" >Period : <?php echo $period;?>&nbsp; (<?php echo $marketName;?>)</p>
									</div>
									<!--end-of col-md-12-->
									
									<div class="col-md-12 mrg-top-20">
										<form role="form" name="colokNForm" id="colokNForm">
											<input type="submit" name="simpan" class="game-more-btn pull-right" value="SAVE">
											
											<input type="hidden" name="error_code" id="xError" value="0">
											
											<input type="hidden" name="market" id="marketn" value="<?php echo $marketName;?>">
											<input type="hidden" name="avlbl_blnce" value="<?php echo $availableBalance;?>">
											<input type="hidden" name="period" value="<?php echo $period; ?>">
											<input type="hidden" name="gametype" id="gametypeC" value="Colok Naga">
											<input type="hidden" name="key" value="pColokNaga">
											<div class="table-responsive">
												<table class="table table-bordered">
													<thead>
														<tr>
															<th>No</th>
															<th style="width:210px;">CRUSH</th>
															<th>BET</th>
															<th>DISCOUNT</th>
															<th>PAY</th>
														</tr>
													</thead>
													<tbody>
														<?php for($j=1; $j<11; $j++) {?>
															<tr>
																<td><?php echo $j;?></td>
																<td><select class="form-control input-width Drop xDrop" name="digit1[]" id="digit1_<?php echo $j?>">
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
																<select class="form-control input-width Drop2 xDrop" name="digit2[]" id="digit2_<?php echo $j?>">
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
																<select class="form-control input-width Drop3 xDrop" name="digit3[]" id="digit3_<?php echo $j?>">
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
																<td><input type="text" class="form-control checkBetAmount" name="bet[]" id="bet_<?php echo $j?>" data-thousands=","></td>
																<td><input type="text" class="form-control checkDiscount" name="discount[]" id="discount_<?php echo $j?>" readonly="readonly"></td>
																<td><input type="text" class="form-control checkPaybleAmount" name="pay_amount[]" id="pay_amount_<?php echo $j?>" readonly="readonly"></td>
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
													<input type="submit" name="simpan" class="game-more-btn pull-right" value="SAVE">
												</div>
											</div>
											<!-------end of table------>
										</form>
										<?php echo $arrColokNagaDetails['cms_page_details']; ?>
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