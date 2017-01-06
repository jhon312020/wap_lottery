<?php require_once("includes/head.php");
	require_once("includes/check-authentication.php");
	$arr4dDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '4'"));
	$marketName = $_REQUEST['market'];
	$_SESSION['marketName'] = $marketName;
	//date_default_timezone_set("Asia/Kuala_Lumpur");
	//$currentDate = date('Y-m-d');
	//$prevDate = date('Y-m-d', strtotime('-1 day'));
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
	
	
	$arrBettingLimit4d = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = '4D'"));
	$minbetAmount4d = $arrBettingLimit4d['bt_min_bet_amount'];
	$maxbetAmount4d = $arrBettingLimit4d['bt_max_bet_amount'];
	if(!isset($minbetAmount4d)) {
		$minbetAmount4d = 0;
	}
	if(!isset($maxbetAmount4d)) {
		$maxbetAmount4d = 0;
	}
	
	$arrBettingLimit3d = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = '3D'"));
	$minbetAmount3d = $arrBettingLimit3d['bt_min_bet_amount'];
	$maxbetAmount3d = $arrBettingLimit3d['bt_max_bet_amount'];
	if(!isset($minbetAmount3d)) {
		$minbetAmount3d = 0;
	}
	if(!isset($maxbetAmount3d)) {
		$maxbetAmount3d = 0;
	}
	
	
	
	$arrBettingLimit2d = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = '2D'"));
	$minbetAmount2d = $arrBettingLimit2d['bt_min_bet_amount'];
	$maxbetAmount2d = $arrBettingLimit2d['bt_max_bet_amount'];
	if(!isset($minbetAmount2d)) {
		$minbetAmount2d = 0;
	}
	if(!isset($maxbetAmount2d)) {
		$maxbetAmount2d = 0;
	}
	
	
	
	$arrBettingLimit2dd = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = '2D D'"));
	$minbetAmount2dd = $arrBettingLimit2dd['bt_min_bet_amount'];
	$maxbetAmount2dd = $arrBettingLimit2dd['bt_max_bet_amount'];
	if(!isset($minbetAmount2dd)) {
		$minbetAmount2dd = 0;
	}
	if(!isset($maxbetAmount2dd)) {
		$maxbetAmount2dd = 0;
	}
	
	$arrBettingLimit2dt = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = '2D T'"));
	$minbetAmount2dt = $arrBettingLimit2dt['bt_min_bet_amount'];
	$maxbetAmount2dt = $arrBettingLimit2dt['bt_max_bet_amount'];
	
	if(!isset($minbetAmount2dt)) {
		$minbetAmount2dt = 0;
	}
	if(!isset($maxbetAmount2dt)) {
		$maxbetAmount2dt = 0;
	}
	
	$arrBettingLimit2db = mysql_fetch_array(mysql_query("SELECT * FROM lottery_betting_limit WHERE bt_market = '".$marketName."' and bt_game_type = '2D B'"));
	$minbetAmount2db = $arrBettingLimit2db['bt_min_bet_amount'];
	$maxbetAmount2db = $arrBettingLimit2db['bt_max_bet_amount'];
	if(!isset($minbetAmount2db)) {
		$minbetAmount2db = 0;
	}
	if(!isset($maxbetAmount2db)) {
		$maxbetAmount2db = 0;
	}
	
	
	if(isset($_REQUEST['key']) && $_REQUEST['key'] == 'purchase4d') {
		//echo "<pre>"; print_r($_REQUEST); exit;
		
		$totalPAmount = $_REQUEST['totalPay'];
		$modTotalPAmount = str_replace( ',', '', $totalPAmount);
		//echo $modTotalPAmount;
		$availableBalace = $_REQUEST['avlbl_blnce'];
		
		
		$modTotalPAmount = $modTotalPAmount * 1 ;
		$availableBalace = $availableBalace * 1;
		//echo $availableBalace; exit;
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$t=time();
		if($modTotalPAmount<$availableBalace) {
			//echo "hellloooooo"; exit;
			$c = count($_REQUEST['d1']);
			for($i=0;$i<$c;$i++) {
				$category = $_REQUEST['market'];
				$period = $_REQUEST['period'];
				$gameType = $_REQUEST['gametype'][$i];
				$digit = $_REQUEST['d1'][$i].$_REQUEST['d2'][$i].$_REQUEST['d3'][$i].$_REQUEST['d4'][$i]; 
				$betAmount = $_REQUEST['bet_amount'][$i];
				//echo $betAmount; exit;
				$modBetAmount = str_replace( ',', '', $betAmount);
				//echo $modBetAmount; exit;
				$discount = $_REQUEST['discount_amount'][$i];
				$modDiscount = str_replace( ',', '', $discount);
				//echo $discount; 
				//echo $modDiscount; exit;
				$paybleAmount = $_REQUEST['pay_amount'][$i];
				
				$modPaybleAmount = str_replace( ',', '', $paybleAmount);
				//echo $modPaybleAmount; exit;
				$purchaseDateTime = explode(" ",date('Y-m-d h:i:s'));
				$purchaseDate = $purchaseDateTime['0'];
				$purchaseTime = $purchaseDateTime['1'];
				//echo $modBetAmount; exit;
				if($gameType!="N/A" && $_REQUEST['bet_amount'][$i]!=""){
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
				$('.checkBetAmount').maskNumber({integer: true});
				function nextTab(that) {
					var b = that.id.split('_');
					if($.trim($(that).val())!='') {
						switch(b[0]) {
							case 'd1': { $("#d2_"+b[1]).focus(); break; }
							case 'd2': { $("#d3_"+b[1]).focus(); break; }
							case 'd3': { $("#d4_"+b[1]).focus(); break; }
							case 'd4': { $("#betamount_"+b[1]).focus(); break; }
						}
					}
				}
				
				
				$(".input-width4d").keyup(function(){
					nextTab(this);
				});
				
				$(".checkLoto").blur(function(){
					collectNumber(this);
					autofillAmount(this);
				});
				
				function collectNumber(that) {
					var nextTextFieldId = $(that).parent().next().find('.GameT');
					var nextTextFieldId2 = $(that).parent().next().next().find('.DiscountP');
					var market = $("#marketn").val();
					//console.log(market);
					var loto_id = that.id;
					//console.log(loto_id);
					var lb = loto_id.split('_');
					//console.log(lb);
					//if($("#d1_"+lb[1]).val()!=""&&$("#d2_"+lb[1]).val()!=""&&$("#d3_"+lb[1]).val()!=""&$("#d4_"+lb[1]).val()!=""){
					$.ajax({
						type:'POST',
						url:"fetch_gametype_discount_percentage_ajax.php",
						data:"action=fetchGameType&market="+$("#marketn").val()+"&n1="+$("#d1_"+lb[1]).val()+"&n2="+$("#d2_"+lb[1]).val()+"&n3="+$("#d3_"+lb[1]).val()+"&n4="+$("#d4_"+lb[1]).val(),
						success: function(result) {
							var g = result.split('^');
							nextTextFieldId.val(g[0]);
							nextTextFieldId2.val(g[1]);
						}
					});
					//}
				}
				
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
				
				function autofillAmount(that) {
					var amountDefault = $("#bet_default").val();
					var bet_id = that.id;
					var bt = bet_id.split('_');
					$("#betamount_"+bt[1]).val(amountDefault);
				}
				
				$(".checkBetAmount").blur(function(){
					var betamount = $(this).val();
					//alert(betamount);
					var modbetamount = betamount.replace(/\,/g , "");
					//alert(modbetamount);
					
					
					var betField = $(this).parent().find('.checkbetamount');
					
					var market = $("#marketn").val();
					var prevTextFieldCat = $(this).parent().prev().prev().find('input').val();
					//alert(prevTextFieldCat);
					<?php if(isset($minbetAmount4d) && $minbetAmount4d!=0) {?>
						if(prevTextFieldCat == "4D" && modbetamount < <?php echo $minbetAmount4d?>) {
							alert("Minimum Bet Amount is <?php echo $minbetAmount4d?>");
						}
					<?php }?>
					<?php if(isset($maxbetAmount4d) && $maxbetAmount4d!=0) {?>
						if(prevTextFieldCat == "4D" && modbetamount > <?php echo $maxbetAmount4d?>) {
							alert("Maximum Bet Amount is <?php echo $maxbetAmount4d?>");
						}
					<?php }?>
					<?php if(isset($minbetAmount3d) && $minbetAmount3d!=0) {?>
						if(prevTextFieldCat == "3D" && modbetamount < <?php echo $minbetAmount3d?>) {
							alert("Minimum Bet Amount is <?php echo $minbetAmount3d?>");
						}
					<?php }?>
					<?php if(isset($maxbetAmount3d) && $maxbetAmount3d!=0) {?>
						if(prevTextFieldCat == "3D" && modbetamount > <?php echo $maxbetAmount3d?>) {
							alert("Maximum Bet Amount is <?php echo $maxbetAmount3d?>");
						}
					<?php }?>
					<?php if(isset($minbetAmount2d) && $minbetAmount2d!=0) {?>
						if(prevTextFieldCat == "2D" && modbetamount < <?php echo $minbetAmount2d?>) {
							alert("Minimum Bet Amount is <?php echo $minbetAmount2d?>");
						}
					<?php }?>
					<?php if(isset($maxbetAmount2d) && $maxbetAmount2d!=0) {?>
						if(prevTextFieldCat == "2D" && modbetamount > <?php echo $maxbetAmount2d?>) {
							alert("Maximum Bet Amount is <?php echo $maxbetAmount2d?>");
						}
					<?php }?>
					<?php if(isset($minbetAmount2dd) && $minbetAmount2dd!=0) {?>
						if(prevTextFieldCat == "2D D" && modbetamount < <?php echo $minbetAmount2dd?>) {
							alert("Minimum Bet Amount is <?php echo $minbetAmount2dd?>");
						}
					<?php }?>
					<?php if(isset($maxbetAmount2dd) && $maxbetAmount2dd!=0) {?>
						if(prevTextFieldCat == "2D D" && modbetamount > <?php echo $maxbetAmount2dd?>) {
							alert("Maximum Bet Amount is <?php echo $maxbetAmount2dd?>");
						}
					<?php }?>
					<?php if(isset($minbetAmount2dt) && $minbetAmount2dt!=0) {?>
						if(prevTextFieldCat == "2D T" && modbetamount < <?php echo $minbetAmount2dt?>) {
							alert("Minimum Bet Amount is <?php echo $minbetAmount2dt?>");
						}
					<?php }?>
					<?php if(isset($maxbetAmount2dt) && $maxbetAmount2dt!=0) {?>
						if(prevTextFieldCat == "2D T" && modbetamount > <?php echo $maxbetAmount2dt?>) {
							alert("Maximum Bet Amount is <?php echo $maxbetAmount2dt?>");
						}
					<?php }?>
					<?php if(isset($minbetAmount2db) && $minbetAmount2db!=0) {?>
						if(prevTextFieldCat == "2D B" && modbetamount < <?php echo $minbetAmount2db?>) {
							alert("Minimum Bet Amount is <?php echo $minbetAmount2db?>");
						}
					<?php }?>
					<?php if(isset($maxbetAmount2db) && $maxbetAmount2db!=0) {?>
						if(prevTextFieldCat == "2D B" && modbetamount > <?php echo $maxbetAmount2db?>) {
							alert("Maximum Bet Amount is <?php echo $maxbetAmount2db?>");
						}
					<?php }?>
					
					//var prevTextFieldDiscount = $(this).parent().prev().find('input').val();
					var nextTextDiscount = $(this).parent().next().find(':text');
					var nextTextPrice = $(this).parent().next().next().find(':text');
					//if(betamount>=<?php echo $minbetAmount?> && betamount<=<?php echo $maxbetAmount?>) {
					$.ajax({
						type:'POST',
						url:"fetch_gametype_discount_percentage_ajax.php",
						data:"action=fetchdiscountPrice&gameTpe="+prevTextFieldCat+"&betamount="+modbetamount+"&market="+market,
						success: function(result) {
							//alert(result);
							var p = result.split('^');
							nextTextDiscount.val(p[2]);
							nextTextPrice.val(p[3]);
							calculateTotalBidAmount();
							calculateTotalDiscount();
							calculateTotalPaybleAmount();
						}
					});
					
					//}
					
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
								<div class="game-body">
									<h1>4D, 3D, 2D Front / Middle / Rear<br>
									<small style="font-size:14px;">Guessing 4D, 3D, 2D Front / Middle / Rear.</small></h1>
									<div class="alternative-area">
										<p> 
											<span class="join-text">
												<a href="bolak_balik.php?market=<?php echo $marketName;?>">Bolak Balik</a>
											</span> 
											<span style="color:#ff0202"> |</span> 
											<span class="join-text"><a href="bbcampuran.php?market=<?php echo $marketName;?>">BB Campuran / 4D SET</a></span> 
											<span style="color:#ff0202"> |</span> 
											<span class="join-text"><a href="quick2d.php?market=<?php echo $marketName;?>">QUICK 2D </a></span> 
										</p>
									</div>
									<!--end-of alternative-area-->
									
									<div class="col-md-12 " style="border-bottom: 1px dotted #1B1C21;">
										<p class="pull-left">Period : <?php echo $period;?>&nbsp;(<?php echo $marketName;?>)</p>
										<p class="pull-right">Bet Default:<span style="margin-left:10px;">
											<input name="betdefault" type="text" id="bet_default">
										</span></p>
									</div>
									<!--end-of col-md-12-->
									
									<div class="col-md-12 mrg-top-20 ">
										<form role="form" name="form4d" id="form4d" action="" method="POST">
											<input type="button" name="simpan" class="game-more-btn pull-right" onclick = this.form.submit(); value="SAVE">
											<input type="hidden" name="market" id="marketn" value="<?php echo $marketName;?>">
											<input type="hidden" name="avlbl_blnce" value="<?php echo $availableBalance;?>">
											<input type="hidden" name="period" value="<?php echo $period; ?>">
											<input type="hidden" name="key" value="purchase4d">
											
											<div class="table-responsive">
												<table class="table table-bordered">
													<thead>
														<tr>
															<th>No</th>
															<th style="width:260px;">CRUSH</th>
															<th style="width:60px;" >GAME</th>
															<th>DISC%</th>
															<th>BET</th>
															<th>DISCOUNT</th>
															<th>PAY</th>
														</tr>
													</thead>
													<tbody>
														<?php for($j=1; $j<21; $j++) {?>
															<tr>
																<td><?php echo $j;?></td>
																<td>
																	<input type="text" class="form-control input-width4d checkLoto" name="d1[]" id="d1_<?php echo $j?>" maxlength="1">
																	<input type="text" class="form-control input-width4d checkLoto" name="d2[]" id="d2_<?php echo $j?>" maxlength="1">
																	<input type="text" class="form-control input-width4d checkLoto" name="d3[]" id="d3_<?php echo $j?>" maxlength="1">
																	<input type="text" class="form-control input-width4d checkLoto" name="d4[]" id="d4_<?php echo $j?>" maxlength="1">
																</td>
																<td><input type="text" class="form-control input-width GameT" name="gametype[]" id="gametype_<?php echo $j?>" readonly style="color:#F00;"></td>
																<td><input type="text" class="form-control input-width DiscountP" name="dispercentage[]" id="dispercent_<?php echo $j?>" readonly style="color:#F00;"></td>
																<td><input type="text" class="form-control checkBetAmount" name="bet_amount[]" id="betamount_<?php echo $j?>" data-thousands="," style="width:100px;"></td>
																<td><input type="text" class="form-control checkDiscount" name="discount_amount[]" id="discount_amount_<?php echo $j?>" readonly="readonly"></td>
																<td><input type="text" class="form-control checkPaybleAmount" name="pay_amount[]" id="pay_amount_<?php echo $j?>" readonly="readonly" style="width:90px;"></td>
															</tr>
														<?php }?>
														<tr style="background:#333333; color:#FFFFFF">
															<td colspan="4" align="center" style="border:none;">Total</td>
															<td style="border:none;"><input type="text" class="form-control" id="t_betamount"></td>
															<td style="border:none;"><input type="text" class="form-control" id="t_discount"></td>
															<td style="border:none;"><input type="text" class="form-control" name="totalPay"id="t_paybleamount"></td>
														</tr>
													</tbody>
												</table>
												<div class="col-md-12">
													<input type="button" name="simpan" class="game-more-btn pull-right" onclick = this.form.submit(); value="SAVE">
												</div>
											</div>
											<!-------end of table------>
										</form>
										<!--<div class="col-md-12">
											<p style="color: #121217;"><span style="width:120px; float:left; ">Discount </span><span style="width:15px; float:left;">:</span> 0% + KEI</p>
											<p style="color: #121217;"><span style="width:120px; float:left;">Gift </span><span style="width:15px; float:left;">:</span>1x plus Capital.</p>
											<p style="color: #121217;"><span style="width:120px; float:left;">Min BET </span><span style="width:15px; float:left;">:</span>10,000</p>
											<p style="color: #121217;"><span style="width:120px; float:left; ">Max BET </span><span style="width:15px; float:left;">:</span>20,000,000</p>
											<p style="color: #121217;"><span style="width:120px; float:left; ">BET Multiples </span><span style="width:15px; float:left;">:</span>10,000</p>
										</div>-->
										<!--<div class="col-md-12">
											<p>- Please check back BET, Discounts, and multiplication Your victory. </p>
											<p>- This bet is SAH and Irrevocable </p>
											<p>- Betting considered deviant, Hack, Inject, will be canceled and DELETE without Confirmation. </p>
											<p>- If an error occurs BET, please immediately confirm to CS duty </p>
											<p>- Errors were confirmed after PERIOD in the lot will not be served. </p>
										</div>-->
										<?php echo $arr4dDetails['cms_page_details']; ?>
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