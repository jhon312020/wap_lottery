<?php 
	require_once("includes/head.php");
	require_once("includes/check-authentication.php");
	require_once("includes/function.php");
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$marketName = $_REQUEST['market'];
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
	if(isset($_REQUEST['key']) && $_REQUEST['key'] == "quick2dForm") {
		
		$numberType = $_REQUEST['number_type'];
		$betPrice = $_REQUEST['bet_price']*1000;
		$modBetPrice = number_format($betPrice , 0 , '.' , ',' );
		$gamePosition = $_REQUEST['bet_position'];
		$combArr = getAllNumbers($numberType);
		
		$arrGameDiscountDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting 
		WHERE g_market_name = '".$marketName."' 
		AND g_type = '".$gamePosition."'"));
		
		if(isset($arrGameDiscountDetails) && $arrGameDiscountDetails != '') {
			$discountPercentage = $arrGameDiscountDetails['g_discount'];
			} else {
			$discountPercentage = 0;
		}
		
		$discountFinal = ($betPrice * $discountPercentage)/100;
		$modDiscountFinal = number_format( $discountFinal , 0 , '.' , ',' );
		$payAmount = $betPrice - $discountFinal;
		$modPayAmountFinal = number_format($payAmount , 0 , '.' , ',' );
		
	}
	
	if(isset($_REQUEST['key']) && $_REQUEST['key'] == "purchase2d") {
		//echo "<pre>"; print_r($_REQUEST); exit;
		$totalPAmount = $_REQUEST['totalPay'];
		$modTotalPAmount = str_replace( ',', '', $totalPAmount );
		$availableBalace = $_REQUEST['avlbl_blnce'];
		
		$modTotalPAmount = $modTotalPAmount * 1 ;
		$availableBalace = $availableBalace * 1;
		
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$t=time();
		if($modTotalPAmount<$availableBalace) {
			$c = count($_REQUEST['checkedDigit']);
			for($i=0;$i<$c;$i++) {
				$totalCheckedNumber = $_REQUEST['checkedDigit'][$i];
				if($_REQUEST['checkedDigit'][$i]!='') {
					$category = $_REQUEST['market'];
					$period = $_REQUEST['period'];
					$gameType = $_REQUEST['gametype'][$totalCheckedNumber];
					$digit = $_REQUEST['d1'][$totalCheckedNumber]; 
					
					$originalBetAmount = $_REQUEST['bet_amount']; 
					$originalDiscountAmount = $_REQUEST['discount_amount'][$totalCheckedNumber];
					$originalPayAmount = $_REQUEST['tot_pay_amount'][$totalCheckedNumber];
					
					$betAmount = $_REQUEST['show_bet_amount'][$totalCheckedNumber];
					$discount = $_REQUEST['show_discount_amount'][$totalCheckedNumber];
					$paybleAmount = $_REQUEST['show_pay_amount'][$totalCheckedNumber];
					
					$purchaseDateTime = explode(" ",date('Y-m-d h:i:s'));
					$purchaseDate = $purchaseDateTime['0'];
					$purchaseTime = $purchaseDateTime['1'];
					
					//echo $modBetAmount; exit;
					if($gameType!="N/A" && $_REQUEST['show_bet_amount'][$totalCheckedNumber]!=""){
						$resPurchaseLottery = mysql_query("INSERT INTO lottery_purchase(
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
						p_win_count) VALUES(
						'".$_SESSION['lottery']['memberid']."',
						'".$category."',
						'".$period."',
						'".$gameType."',
						'".$digit."',
						'',
						'',
						'',
						'".$originalBetAmount."', 
						'".$originalDiscountAmount."',
						'".$originalPayAmount."',
						'".$purchaseDate."', 
						'".$purchaseTime."',
						'".$t."',
						'0',
						'0')");
					}
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
			$(document).ready(function() {
				
				$("#member-form-update").validate({
					rules: {
						
						number_type: {
							
							required:true
						},
						
						bet_price: {
							
							required:true
						},
						
						bet_position: {
							
							required:true
							
						}
						
					},
					
					messages: {
						
						number_type: {
							
							required: "Please choose one of the number type"
							
						},
						
						bet_price: {
							
							required: "Please enter your betting price"
							
						},
						
						bet_position: {
							
							required: "Please enter your betting position"
						}
						
					},
					
				});
				//$('.checkBetAmount').maskNumber({integer: true});
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
				
				$(".cb").click(calculateCheckBetAmount);
				$(".cb").click(calculateCheckDiscount);
				$(".cb").click(calculateCheckPayment);
				
				function calculateCheckBetAmount() {
					var totalBedAmount = 0;
					$(".cb").each(function(key,elem) {
						if($(elem).is(':checked')) {
							var b = elem.id.split('_');
							var id = b[1];
							//console.log(id);
							var inputBed = $('#show_betamount_'+id).val();
							//var modInputBed = inputBed.replace(",",""); 
							var modInputBed = inputBed.replace(/,/g, '');
							if(modInputBed != '') {
								totalBedAmount += parseInt(modInputBed);
							}
						}
					})
					$("#t_betamount").val(totalBedAmount);
					$('#t_betamount').number( true, 0 );
				}
				
				
				function calculateCheckDiscount() {
					var totalDiscount = 0;
					$(".cb").each(function(key,elem) {
						if($(elem).is(':checked')) {
							var b = elem.id.split('_');
							var id = b[1];
							//console.log(id);
							var inputDiscount = $('#show_discount_amount_'+id).val();
							//var modInputDiscount = inputDiscount.replace(",",""); 
							var modInputDiscount = inputDiscount.replace(/,/g, '');
							if(modInputDiscount != '') {
								totalDiscount += parseInt(modInputDiscount);
							}
						}
					})
					$("#t_discount").val(totalDiscount);
					$('#t_discount').number( true, 0 );
				}
				
				function calculateCheckPayment() {
					var totalPayble = 0;
					$(".cb").each(function(key,elem) {
						if($(elem).is(':checked')) {
							var b = elem.id.split('_');
							var id = b[1];
							var inputPaybleAmount = $('#show_pay_amount_'+id).val();
							//var modPaybleAmount = inputPaybleAmount.replace(",",""); 
							var modPaybleAmount = inputPaybleAmount.replace(/,/g, '');
							if(modPaybleAmount != '') {
								totalPayble += parseInt(modPaybleAmount);
							}
						}
					})
					$("#t_paybleamount").val(totalPayble);
					$('#t_paybleamount').number( true, 0 );
				}
				
				function calculateTotalDiscount() {
					var totalDiscount = 0;
					$(".checkDiscount").each(function(key,elem) {
						var inputDiscount = $(elem).val();
						//var modDiscountBed = inputDiscount.replace(",","");
						var modDiscountBed = inputDiscount.replace(/,/g, '');
						if(modDiscountBed != '') {
							totalDiscount += parseInt(modDiscountBed);
						}
					});
					$("#t_discount").val(totalDiscount);
					$('#t_discount').number( true, 0 );
				}
				
				function calculateTotalPaybleAmount() {
					var totalPayble = 0;
					$(".checkPaybleAmount").each(function(key,elem) {
						var inputPaybleAmount = $(elem).val();
						//var modPayAmount = inputPaybleAmount.replace(",","");
						var modPayAmount = inputPaybleAmount.replace(/,/g, '');
						if(modPayAmount != '') {
							totalPayble += parseInt(modPayAmount);
						}
					});
					$("#t_paybleamount").val(totalPayble);
					$('#t_paybleamount').number( true, 0 );
				}
				calculateTotalBidAmount();
				calculateTotalDiscount();
				calculateTotalPaybleAmount();
				$("#bet_price").blur(function() {
					var bPrice = $("#bet_price").val();
					if(bPrice == 0) {
						alert("Please enter a valid digit greater than 0");
						$("#bet_price").val('');
					}
				})
			})
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
								<div class="game-body">
									
									<div class="alternative-area">
										<p> 
											<span class="join-text">Quick 2D</span> 
										</p>
									</div>
									<!--end-of alternative-area-->
									
									<div class="col-md-12 " style="border-bottom: 1px dotted #1B1C21;">
										<p class="pull-left">Period : <?php echo $period;?>&nbsp;(<?php echo $marketName;?>)</p>
									</div>
									
									<?php if(isset($_REQUEST['key']) && $_REQUEST['key'] == "quick2dForm") {?>
										<div class="col-md-12 showFormCombination4d" >
											<form role="form" name="formquick2D" id="formquick2D" action="" method="POST">
												<input type="hidden" name="market" id="marketn" value="<?php echo $marketName;?>">
												<input type="hidden" name="avlbl_blnce" value="<?php echo $availableBalance;?>">
												<input type="hidden" name="period" value="<?php echo $period; ?>">
												<input type="hidden" name="bet_amount" value="<?php echo $betPrice;?>">
												<input type="hidden" name="key" value="purchase2d">
												<div class="table-responsive">
													<table class="table table-bordered comboTable">
														<thead>
															<tr>
																<th>No</th>
																<th>Action</th>
																<th style="width:260px;">CRUSH</th>
																<th style="width:60px;">GAME</th>
																<th>DISC%</th>
																<th>BET</th>
																<th>DISCOUNT</th>
																<th>PAY</th>
															</tr>
														</thead>
														<tbody>
															<?php 
																$i = 0;
																foreach($combArr as $com) {
																?>
																<input type="hidden" name="discount_amount[]" id="org_discount_amount_<?php echo $i;?>" value="<?php echo $discountFinal;?>">
																<input type="hidden" name="tot_pay_amount[]" id="org_tot_payamount_<?php echo $i;?>" value="<?php echo $payAmount;?>">
																<tr>
																	<td><?php echo $i+1;?></td>
																	<td><input type="checkbox" name="checkedDigit[]" class="cb" id="checkedDigit_<?php echo $i;?>" checked=checked value="<?php echo $i;?>"></td>
																	<td>
																		<input type="text" class="form-control input-width checkLoto" name="d1[]" id="d1_<?php echo $i?>" style="width:110px;" readonly value="<?php echo $com;?>">
																	</td>
																	<td><input type="text" class="form-control input-width GameT" name="gametype[]" id="gametype_<?php echo $i?>" readonly style="color:#F00;" value="<?php echo $gamePosition;?>"></td>
																	<td>
																		<input type="text" class="form-control input-width DiscountP" name="dispercentage[]" id="dispercent_<?php echo $i?>" readonly style="color:#F00;" value="<?php echo $discountPercentage;?>%">
																	</td>
																	<td><input type="text" class="form-control checkBetAmount" name="show_bet_amount[]" id="show_betamount_<?php echo $i?>" data-thousands="." style="width:100px;" readonly value="<?php echo $modBetPrice;?>"></td>
																	<td><input type="text" class="form-control checkDiscount" name="show_discount_amount[]" id="show_discount_amount_<?php echo $i?>" readonly="readonly" value="<?php echo $modDiscountFinal;?>"></td>
																	<td><input type="text" class="form-control checkPaybleAmount" name="show_pay_amount[]" id="show_pay_amount_<?php echo $i?>" readonly="readonly" style="width:90px;" value="<?php echo $modPayAmountFinal;?>"></td>
																</tr>
															<?php $i++;}?>
															<tr style="background:#333333; color:#FFFFFF">
																<td colspan="5" align="center" style="border:none;">Total</td>
																<td style="border:none;"><input type="text" class="form-control" id="t_betamount"></td>
																<td style="border:none;"><input type="text" class="form-control" id="t_discount"></td>
																<td style="border:none;"><input type="text" class="form-control" name="totalPay"id="t_paybleamount"></td>
															</tr>
														</tbody>
													</table>
													<div class="col-md-12 saveSimpan">
														<input type="button" name="simpan" class="game-more-btn pull-right" onclick = this.form.submit(); value="SAVE">
													</div>
												</div>
												<!-------end of table------>
											</form>
										</div>
										<?php } else {?>
										<div class="col-md-9 rdc-padding">
											<div class="information-page-area" style="padding:25px 15px; min-height:0;">
												<div class="col-md-12 profileForm">
													<h2>Quick 2D</h2>
													<form name="frm" id="member-form-update" method="POST" action="" role="form" enctype="multipart/form-data">
														<input type="hidden" name="key" value="quick2dForm">
														<div class="form-group">
															<label for="email">Type of Nomor : </label>
															<label class="radio-inline"><input type="radio" name="number_type" value="genap" id="nt_genap">GENAP</label>
															<label class="radio-inline"><input type="radio" name="number_type" value="ganjil" id="nt_ganjil">GANJIL</label>
															<label class="radio-inline"><input type="radio" name="number_type" value="besar" id="nt_besar">BESAR</label>
															<label class="radio-inline"><input type="radio" name="number_type" value="kecil" id="nt_kecil">KECIL</label> 
														</div>
														<div class="form-group">
															<label for="email">Bayar :</label>
															<input type="text" name="bet_price" id="bet_price">*1000
														</div>
														
														<div class="form-group">
															<label for="email">Position :</label>
															<select name="bet_position" id="bet_position">
																<option value="2D D">2D Depan</option>
																<option value="2D T">2D Tengah</option>
																<option value="2D B">2D Belekang</option>
															</select>
														</div>
														<input type="submit" name="submit" value="Submit" class="btn btn-default subbtn" style="font-size:15px;">
													</form>
												</div>
												<!--end-of col-md-12-->
												
												<!--end-of col-md-12-->
												<div class="clear"></div>
											</div>
											<!--end-of game-body--> 
										</div>
									<?php }?>
									
									
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