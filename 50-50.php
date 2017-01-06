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
	
	$arrBettingLimit = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = '50-50'"));
	$minbetAmount = $arrBettingLimit['bt_min_bet_amount'];
	if(!isset($minbetAmount)) {
		$minbetAmount = 0;
	}
	$maxbetAmount = $arrBettingLimit['bt_max_bet_amount'];
	if(!isset($maxbetAmount)) {
		$maxbetAmount = 0;
	}
	
	$CMSDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '11'"));
	$arr5050Genap = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'Genap' and g_position = 'AS'"));
	
	$arr5050Ganjil = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'Ganjil' and g_position = 'AS'"));
	$arr5050KOPGenap = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'Genap' and g_position = 'KOP'"));
	$arr5050KOPGanjil = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'Ganjil' and g_position = 'KOP'"));
	
	$arr5050KepalaGenap = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'Genap' and g_position = 'KEPALA'"));
	$arr5050KepalaGanjil = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'Ganjil' and g_position = 'KEPALA'"));
	
	
	
	$arr5050EkorGenap = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'Genap' and g_position = 'EKOR'"));
	$arr5050EkorGanjil = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'Ganjil' and g_position = 'EKOR'"));
	
	/**
		*
		* B   E   S   A    R           K    E   C    I     L
		*
	**/
	$arr5050ASBESAR = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'BESAR' and g_position = 'AS'"));
	$arr5050ASKECIL = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'KECIL' and g_position = 'AS'"));
	
	$arr5050KOPBESAR = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'BESAR' and g_position = 'KOP'"));
	$arr5050KOPKECIL = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'KECIL' and g_position = 'KOP'"));
	
	$arr5050KepalaBESAR = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'BESAR' and g_position = 'KEPALA'"));
	$arr5050KepalaKECIL = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'KECIL' and g_position = 'KEPALA'"));
	
	
	
	$arr5050EKORBESAR = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'BESAR' and g_position = 'EKOR'"));
	$arr5050EKORKECIL = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$marketName."' and g_type = '50-50' and g_name = 'KECIL' and g_position = 'EKOR'"));
	
	/**
		*
		* I   N    S   E    R    T 
		*
	**/
	if(isset($_REQUEST['key']) && $_REQUEST['key'] == "p50") {
		$totalPAmount = $_REQUEST['totalPay'];
		$modTotalPAmount = str_replace( ',', '', $totalPAmount);
		$availableBalace = $_REQUEST['avlbl_blnce'];
		
		
		$modTotalPAmount = $modTotalPAmount * 1 ;
		$availableBalace = $availableBalace * 1;
		
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$t=time();
		$arrCrushPosition = array();
		foreach($_REQUEST as $key=>$val) {
			$b = explode('_',$key);
			if($b[0]=='crush'&&$b[1]=='position') {
				$arrCrushPosition[$b[2]] = $val;
			}
		}
		if($modTotalPAmount<$availableBalace) {
			$c = count($arrCrushPosition);
			for($i=0;$i<$c;$i++) {
				$market = $_REQUEST['market'];
				$period = $_REQUEST['period'];
				$gameType = $_REQUEST['gametype'];
				$lotteryPosition = $_REQUEST['lotto_position'][$i];
				$crushType = $arrCrushPosition[$i];
				$betAmount = $_REQUEST['betamount'][$i];
				$modBetAmount = str_replace( ',', '', $betAmount);
				$discount = $_REQUEST['kei'][$i];
				$modDiscount = str_replace( ',', '', $discount);
				$paybleAmount = $_REQUEST['paybleAmount'][$i];
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
				'".$crushType."',
				'".$modBetAmount."',
				'".$modDiscount."',
				'".$modPaybleAmount."',
				'".$purchaseDate."',
				'".$purchaseTime."',
				'".$t."',
				'0',
				'0')");
			}
			
			$arrCrushType = array();
			foreach($_REQUEST as $key=>$val) {
				$g = explode('_',$key);
				if($g[0]=='crush'&&$g[1]=='type') {
					$arrCrushType[$g[2]] = $val;
				}
			}
			$d = count($arrCrushType);
			for($j=0;$j<$d;$j++) {
				$market2 = $_REQUEST['market'];
				$period2 = $_REQUEST['period'];
				$gameType2 = $_REQUEST['gametype'];
				$lotteryPosition2 = $_REQUEST['lotto_position2'][$j];
				$crushType2 = $arrCrushType[$j];
				$betAmount2 = $_REQUEST['betamount2'][$j];
				$modBetAmount2 = str_replace( ',', '', $betAmount2);
				$discount2 = $_REQUEST['kei2'][$j];
				$modDiscount2 = str_replace( ',', '', $discount2);
				$paybleAmount2 = $_REQUEST['payble_amount2'][$j];
				$modPaybleAmount2 = str_replace( ',', '', $paybleAmount2);
				$purchaseDateTime2 = explode(" ", date('Y-m-d H:i:s'));
				$purchaseDate2 = $purchaseDateTime['0'];
				$purchaseTime2 = $purchaseDateTime['1'];
				
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
				'".$lotteryPosition2."',
				'',
				'".$crushType2."',
				'".$modBetAmount2."',
				'".$modDiscount2."',
				'".$modPaybleAmount2."',
				'".$purchaseDate2."',
				'".$purchaseTime2."',
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
				$('.checkBetAmount2').maskNumber({integer: true});
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
					$("#totalAmount1").val(totalBedAmount);
					var finalAmount = parseInt($("#totalAmount1").val()) + parseInt($("#totalAmount2").val())
					$("#t_betamount").val(finalAmount);
					$('#t_betamount').number( true, 0 );
				}
				
				function calculateTotalBidAmountBigSmall(){
					var totalBedAmount = 0;
					$(".checkBetAmount2").each(function(key,elem) { 
						var inputBed = $(elem).val();
						//var modInputBed = inputBed.replace(",","");
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
						//var modInputPaybleAmount = inputPaybleAmount.replace(",",""); 
						
						var modInputPaybleAmount = inputPaybleAmount.replace(/,/g, '');
						
						if(modInputPaybleAmount != '') {
							totalPayble += parseInt(modInputPaybleAmount);
						}
					});
					$("#t_paybleamount").val(totalPayble);
					$("#t_paybleamount").number( true, 0 );
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
				
				$(".checkBetAmount").blur(function(){
					var betamount = $(this).val();
					var modbetamount = betamount.replace(/\,/g , "");
					var betField = $(this).parent().find('.checkBetAmount');
					//alert(betField);
					var cPosition = $(this).closest('td').prev('td').prev('td').prev('td').text();
					//alert(cPosition);
					var b = this.id.split('_');
					//alert(b);
					if(!$("input[name=crush_position_"+b[1]+"]:checked").val()) {
						alert('Please Check One Position!');
						betField.val('');
						return false;
						
					}
					var pos = $("input[name=crush_position_"+b[1]+"]:checked").val();
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
								url:"fetch_50_50_discount_price_ajax.php",
								data:"action=fetchdiscount&bet_amount="+modbetamount+"&pos="+pos+"&cPos="+cPosition+"&market="+$("#marketn").val()+"&gameType="+$("#gametypeC").val(),
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
							url:"fetch_50_50_discount_price_ajax.php",
							data:"action=fetchdiscount&bet_amount="+modbetamount+"&pos="+pos+"&cPos="+cPosition+"&market="+$("#marketn").val()+"&gameType="+$("#gametypeC").val(),
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
					<?php }?>
				});
				
				$(".checkBetAmount2").blur(function(){
					var betamount = $(this).val();
					var modbetamount = betamount.replace(/\,/g , "");
					var betField = $(this).parent().find('.checkBetAmount2');
					var cPosition = $(this).closest('td').prev('td').prev('td').prev('td').text();
					//alert(cPosition);
					var c = this.id.split('_');
					if(!$("input[name=crush_type_"+c[1]+"]:checked").val()) {
						alert('Please Check One Position!');
						betField.val('');
						return false;
						
					}
					var pos = $("input[name=crush_type_"+c[1]+"]:checked").val();
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
								url:"fetch_50_50_discount_price_ajax.php",
								data:"action=fetchdiscount&bet_amount="+modbetamount+"&pos="+pos+"&cPos="+cPosition+"&market="+$("#marketn").val()+"&gameType="+$("#gametypeC").val(),
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
							url:"fetch_50_50_discount_price_ajax.php",
							data:"action=fetchdiscount&bet_amount="+modbetamount+"&pos="+pos+"&cPos="+cPosition+"&market="+$("#marketn").val()+"&gameType="+$("#gametypeC").val(),
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
									<h1>50-50<br>
									<small style="font-size:14px;">Menebak ganjil/genap dan besar/kecil pada posisi tertentu dari 4D</small></h1>
									<div class="col-md-12 " style="border-bottom: 1px dotted #1B1C21;">
										<p class="pull-left" >Period : <?php echo $period;?>&nbsp; (<?php echo $marketName;?>)</p>
									</div>
									<!--end-of col-md-12-->
									
									<div class="col-md-12 mrg-top-20 ">
										<form role="form" name="50Form" id="50Form" action="" method="POST">
											<input type="button" name="simpan" class="game-more-btn pull-right" onclick = this.form.submit(); value="SAVE">
											<input type="hidden" name="totalAmount1" id="totalAmount1" value="0">
											<input type="hidden" name="totalAmount2" id="totalAmount2" value="0">
											<input type="hidden" name="market" id="marketn" value="<?php echo $marketName;?>">
											<input type="hidden" name="avlbl_blnce" value="<?php echo $availableBalance;?>">
											<input type="hidden" name="period" value="<?php echo $period; ?>">
											<input type="hidden" name="gametype" id="gametypeC" value="50-50">
											<input type="hidden" name="key" value="p50">
											<div class="table-responsive">
												<table class="table table-bordered">
													<tbody>
														<tr>
															<td colspan="7" class="even-header">GENAP - GANJIL</td>
														</tr>
														<tr>
															<th>POSITION</th>
															<th style="width:200px;">CRUSH</th>
															<th style="width:150px;">KEI</th>
															<th>BET</th>
															<th>DISCOUNT & KEI</th>
															<th>PAY</th>
														</tr>
														<?php 
															for($j=0; $j<4; $j++) {?>
															<?php if($j == "0") { 
																$lp = "AS"; 
																} elseif($j == "1") {
																$lp = "KOP";
																} elseif($j == "2") {
																$lp = "KEPALA";
																} else {
																$lp = "EKOR";
															}
															?>
															<input type="hidden" name="lotto_position[]" value="<?php echo $lp;?>">
															<tr>
																<td><?php if($j == "0") {?>AS<?php }?><?php if($j == "1") {?>KOP<?php }?><?php if($j == "2") {?>KEPALA<?php }?><?php if($j == "3") {?>EKOR<?php }?></td>
																<td><label class="radio-inline">
																<input type="radio"  name="crush_position_<?php echo $j;?>" id="crush_position_<?php echo $j;?>_E" value="Genap">Genap</label>
																<label class="radio-inline">
																<input type="radio"  name="crush_position_<?php echo $j;?>" id="crush_position_<?php echo $j;?>_O" value="Ganjil">Ganjil</label>
																</td>
																<td><span class="input-width"><?php if($j == "0") {?><?php echo $arr5050Genap['g_kei'];?><?php }?></span> <span class="input-width"><?php if($j == "0") {?><?php echo $arr5050Ganjil['g_kei'];?><?php }?><?php if($j == "1") {?><?php echo $arr5050KOPGenap['g_kei'];?><?php }?></span> <span class="input-width" ><?php if($j == "1") {?><?php echo $arr5050KOPGanjil['g_kei'];?><?php }?><?php if($j == "2") {?><?php echo $arr5050KepalaGenap['g_kei'];?><?php }?></span> <span class="input-width" ><?php if($j == "2") {?><?php echo $arr5050KepalaGanjil['g_kei'];?><?php }?><?php if($j == "3") {?><?php echo $arr5050EkorGenap['g_kei'];?><?php }?></span> <span class="input-width" ><?php if($j == "3") {?><?php echo $arr5050EkorGanjil['g_kei'];?><?php }?></span></td>
																<td><input type="text" class="form-control  rdc-padding checkBetAmount" name="betamount[]" id="betamount_<?php echo $j;?>" data-thousands=","></td>
																<td><input type="text" class="form-control rdc-padding checkDiscount" name="kei[]" id="kei_<?php echo $j;?>"readonly="readonly"></td>
																<td><input type="text" class="form-control rdc-padding checkPaybleAmount" name="paybleAmount[]" id="paybleAmount_<?php echo $j;?>"readonly="readonly"></td>
															</tr>
														<?php }?>
														<tr>
															<td colspan="7" class="even-header">BESAR - KECIL</td>
														</tr>
														<tr>
															<th>POSITION</th>
															<th style="width:200px;">CRUSH</th>
															<th style="width:150px;">KEI</th>
															<th>BET</th>
															<th>DISCOUNT & KEI</th>
															<th>PAY</th>
														</tr>
														<?php for($k=0; $k<4; $k++) {?>
															<?php if($k == "0") { 
																$lp = "AS"; 
																} elseif($k == "1") {
																$lp = "KOP";
																} elseif($k == "2") {
																$lp = "KEPALA";
																} else {
																$lp = "EKOR";
															}
															?>
															<input type="hidden" name="lotto_position2[]" value="<?php echo $lp;?>">
															<tr>
																<td><?php if($k == "0") {?>AS<?php }?><?php if($k == "1") {?>KOP<?php }?><?php if($k == "2") {?>KEPALA<?php }?><?php if($k == "3") {?>EKOR<?php }?></td>
																<td><label class="radio-inline">
																<input type="radio"  name="crush_type_<?php echo $k;?>" id="crush_type_<?php echo $k;?>_B" value="BESAR">Besar</label>
																<label class="radio-inline">
																<input type="radio"  name="crush_type_<?php echo $k;?>" id="crush_type_<?php echo $k;?>_S" value="KECIL">Kecil</label></td>
																<td><span class="input-width"><?php if($k == "0") {?><?php echo $arr5050ASBESAR['g_kei']; ?><?php }?><?php if($k == "1") {?><?php echo $arr5050KOPBESAR['g_kei']; ?><?php }?><?php if($k == "2") {?><?php echo $arr5050KepalaBESAR['g_kei']; ?><?php }?><?php if($k == "3") {?><?php echo $arr5050EKORBESAR['g_kei']; ?><?php }?></span> <span class="input-width"><?php if($k == "0") {?><?php echo $arr5050ASKECIL['g_kei']; ?><?php }?><?php if($k == "1") {?><?php echo $arr5050KOPKECIL['g_kei']; ?><?php }?><?php if($k == "2") {?><?php echo $arr5050KepalaKECIL['g_kei']; ?><?php }?><?php if($k == "3") {?><?php echo $arr5050EKORKECIL['g_kei']; ?><?php }?></span></td>
																<td><input type="text" class="form-control  rdc-padding checkBetAmount2" name="betamount2[]" id="betamount2_<?php echo $k;?>" data-thousands=","></td>
																<td><input type="text" class="form-control rdc-padding checkDiscount" name="kei2[]" id="kei2_<?php echo $k;?>" readonly="readonly"></td>
																<td><input type="text" class="form-control rdc-padding checkPaybleAmount" name="payble_amount2[]" id="payble_amount2_<?php echo $k;?>" readonly="readonly"></td>
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
										<?php echo $CMSDetails['cms_page_details']; ?>
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