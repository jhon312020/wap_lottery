<?php require_once("includes/head.php");
	require_once("includes/check-authentication.php");
	$marketName = $_REQUEST['market'];
	//date_default_timezone_set("Asia/Kuala_Lumpur");
	//$currentDate = date('Y-m-d');
	//$currentYear = date('Y');
	
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
	
	$arrShioDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '12'"));
	if(isset($_REQUEST['key']) && $_REQUEST['key'] == "pShio") {
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
				$category = $_REQUEST['market'];
				$period = $_REQUEST['period'];
				$gameType = $_REQUEST['gametype'];
				$lotteryNumber = $_REQUEST['crush'][$i];
				$betAmount = $_REQUEST['betamount'][$i];
				$modBetAmount = str_replace( ',', '', $betAmount);
				$discount = $_REQUEST['discount'][$i];
				$modDiscount = str_replace( ',', '', $discount);
				$paybleAmount = $_REQUEST['payble_amount'][$i];
				$modPaybleAmount = str_replace( ',', '', $paybleAmount);
				$purchaseDateTime = explode(" ",date('Y-m-d H:i:s'));
				$purchaseDate = $purchaseDateTime['0'];
				$purchaseTime = $purchaseDateTime['1'];
				
				if($_REQUEST['crush'][$i]!='' && $_REQUEST['betamount'][$i]!='') {
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
					'".$category."',
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
					$("#t_totalBetAmount").val(totalBedAmount);
					$('#t_totalBetAmount').number( true, 0 );
					
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
					$("#t_totalDiscount").val(totalDiscount);
					$('#t_totalDiscount').number( true, 0 );
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
					$("#t_totalPaybleAmount").val(totalPayble);
					$('#t_totalPaybleAmount').number( true, 0 );
				}
				$(".checkBetAmount").blur(function(){
					var betAmount = $(this).val();
					var modbetamount = betAmount.replace(/\,/g , "");
					var nextTextFieldId = $(this).parent().next().find(':text');
					var nextTextFieldId2 =  $(this).parent().next().next().find(':text');
					$.ajax({
						type:'POST',
						url:"fetch_shio_discount_price_ajax.php",
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
				});
			});
		</script>
		
		<style>
		.checkBetAmount { width: 130px !important; }
		</style>
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
		<h1>S H I O<br> <small style="font-size:14px;">Menebak SHIO dari 2D. Perhatikan Tabel SHIO</small></h1>
		
		
		<div class="col-md-12 " style="border-bottom: 1px dotted #1B1C21;">
			<p class="pull-left" >Period : <?php echo $period;?>&nbsp; (<?php echo $marketName;?>)</p>
			
			
		</div> <!--end-of col-md-12--> 
		<div class="clear"></div>
		
		<div class="col-md-8 mrg-top-20 ">
			<form role="form" name="shioForm" id="shioForm" action="" method="POST">
				
				<input type="hidden" name="market" id="marketn" value="<?php echo $marketName;?>">
				<input type="hidden" name="avlbl_blnce" value="<?php echo $availableBalance;?>">
				<input type="hidden" name="period" value="<?php echo $period; ?>">
				<input type="hidden" name="gametype" id="gametypeC" value="Shio">
				<input type="hidden" name="key" value="pShio">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th></th>
								<th style="width:130px;"></th>       
								<th></th>
								<th></th>
								<th><input type="button" name="simpan" class="game-more-btn pull-right" onclick = this.form.submit(); value="SAVE"></th>        
							</tr>
							<tr>
								<th>No</th>
								<th style="width:130px;">CRUSH</th>       
								<th style="width:150px;">BET</th>
								<th style="width:130px;">DISC.</th>
								<th style="width:150px;">PAY</th>     
							</tr>
						</thead> 
						<tbody> 
							<?php if($currentDate>"2016-02-07" && $currentDate<"2017-01-28") {?>
								<?php for($j=0; $j<10; $j++) {?>
									<tr>
										<td><?php echo $j+1;?></td>
										<td>
											<select class="form-control country-input-width" name="crush[]" id="crush_<?php echo $j;?>">
												<option></option>
												<option value="1">Monyet </option>
												<option value="2">Kambing</option>
												<option value="3">Kuda</option>
												<option value="4">Ular</option>
												<option value="5">Naga</option>
												<option value="6">Kelinci</option>
												<option value="7">Harimau</option>
												<option value="8">Kerbau</option>
												<option value="9">Tikus</option>
												<option value="10">Babi</option>
												<option value="11">Anjing</option>
												<option value="12">Ayam</option>
											</select>
											
											
										</td>     
										<td><input type="text" class="form-control checkBetAmount" name="betamount[]" id="betamount_<?php echo $j;?>" data-thousands=","></td>
										<td><input type="text" class="form-control checkDiscount" name="discount[]" id="discount_<?php echo $j;?>" readonly="readonly"></td>
										<td><input type="text" class="form-control checkPaybleAmount" name="payble_amount[]" id="payble_amount_<?php echo $j;?>"readonly="readonly"></td>
									</tr>
								<?php }?>
							<?php }?>
							
							
							
							<?php if($currentDate>"2017-01-27" && $currentDate<"2018-02-16") {?>
								<?php for($j=0; $j<10; $j++) {?>
									<tr>
										<td><?php echo $j+1;?></td>
										<td>
											<select class="form-control country-input-width" name="crush[]" id="crush_<?php echo $j;?>">
												<option></option>
												<option value="1">Ayam</option>
												<option value="2">Monyet </option>
												<option value="3">Kambing</option>
												<option value="4">Kuda</option>
												<option value="5">Ular</option>
												<option value="6">Naga</option>
												<option value="7">Kelinci</option>
												<option value="8">Harimau</option>
												<option value="9">Kerbau</option>
												<option value="10">Tikus</option>
												<option value="11">Babi</option>
												<option value="12">Anjing</option>
											</select>
											
											
										</td>     
										<td><input type="text" class="form-control checkBetAmount" name="betamount[]" id="betamount_<?php echo $j;?>" data-thousands=","></td>
										<td><input type="text" class="form-control checkDiscount" name="discount[]" id="discount_<?php echo $j;?>" readonly="readonly"></td>
										<td><input type="text" class="form-control checkPaybleAmount" name="payble_amount[]" id="payble_amount_<?php echo $j;?>"readonly="readonly"></td>
									</tr>
								<?php }?>
							<?php }?>
							
							<?php if($currentDate>"2018-02-15" && $currentDate<"2019-02-05") {?>
								<?php for($j=0; $j<10; $j++) {?>
									<tr>
										<td><?php echo $j+1;?></td>
										<td>
											<select class="form-control country-input-width" name="crush[]" id="crush_<?php echo $j;?>">
												<option></option>
												<option value="1">Anjing</option>
												<option value="2">Ayam</option>
												<option value="3">Monyet</option>
												<option value="4">Kambing</option>
												<option value="5">Kuda</option>
												<option value="6">Ular</option>
												<option value="7">Naga</option>
												<option value="8">Kelinci</option>
												<option value="9">Harimau</option>
												<option value="10">Kerbau</option>
												<option value="11">Tikus</option>
												<option value="12">Babi</option>
											</select>
											
											
										</td>     
										<td><input type="text" class="form-control checkBetAmount" name="betamount[]" id="betamount_<?php echo $j;?>" data-thousands=","></td>
										<td><input type="text" class="form-control checkDiscount" name="discount[]" id="discount_<?php echo $j;?>" readonly="readonly"></td>
										<td><input type="text" class="form-control checkPaybleAmount" name="payble_amount[]" id="payble_amount_<?php echo $j;?>"readonly="readonly"></td>
									</tr>
								<?php }?>
							<?php }?>
							
							
							<?php if($currentDate>"2019-02-04" && $currentDate<"2020-01-25") {?>
								<?php for($j=0; $j<10; $j++) {?>
									<tr>
										<td><?php echo $j+1;?></td>
										<td>
											<select class="form-control country-input-width" name="crush[]" id="crush_<?php echo $j;?>">
												<option></option>
												<option value="1">Babi</option>
												<option value="2">Anjing</option>
												<option value="3">Ayam</option>
												<option value="4">Monyet</option>
												<option value="5">Kambing</option>
												<option value="6">Kuda</option>
												<option value="7">Ular</option>
												<option value="8">Naga</option>
												<option value="9">Kelinci</option>
												<option value="10">Harimau</option>
												<option value="11">Kerbau</option>
												<option value="12">Tikus</option>
											</select>
											
											
										</td>     
										<td><input type="text" class="form-control checkBetAmount" name="betamount[]" id="betamount_<?php echo $j;?>" data-thousands=","></td>
										<td><input type="text" class="form-control checkDiscount" name="discount[]" id="discount_<?php echo $j;?>" readonly="readonly"></td>
										<td><input type="text" class="form-control checkPaybleAmount" name="payble_amount[]" id="payble_amount_<?php echo $j;?>"readonly="readonly"></td>
									</tr>
								<?php }?>
							<?php }?>
							
							
							<?php if($currentDate>"2020-01-24" && $currentDate<"2021-02-12") {?>
								<?php for($j=0; $j<10; $j++) {?>
									<tr>
										<td><?php echo $j+1;?></td>
										<td>
											<select class="form-control country-input-width" name="crush[]" id="crush_<?php echo $j;?>">
												<option></option>
												<option value="1">Tikus</option>
												<option value="2">Babi</option>
												<option value="3">Anjing</option>
												<option value="4">Ayam</option>
												<option value="5">Monyet</option>
												<option value="6">Kambing</option>
												<option value="7">Kuda</option>
												<option value="8">Ular</option>
												<option value="9">Naga</option>
												<option value="10">Kelinci</option>
												<option value="11">Harimau</option>
												<option value="12">Kerbau</option>
											</select>
											
											
										</td>     
										<td><input type="text" class="form-control checkBetAmount" name="betamount[]" id="betamount_<?php echo $j;?>" data-thousands=","></td>
										<td><input type="text" class="form-control checkDiscount" name="discount[]" id="discount_<?php echo $j;?>" readonly="readonly"></td>
										<td><input type="text" class="form-control checkPaybleAmount" name="payble_amount[]" id="payble_amount_<?php echo $j;?>"readonly="readonly"></td>
									</tr>
								<?php }?>
							<?php }?>
							
							
							<?php if($currentDate>"2021-02-11" && $currentDate<"2022-02-01") {?>
								<?php for($j=0; $j<10; $j++) {?>
									<tr>
										<td><?php echo $j+1;?></td>
										<td>
											<select class="form-control country-input-width" name="crush[]" id="crush_<?php echo $j;?>">
												<option></option>
												<option value="1">Kerbau</option>
												<option value="2">Tikus</option>
												<option value="3">Babi</option>
												<option value="4">Anjing</option>
												<option value="5">Ayam</option>
												<option value="6">Monyet</option>
												<option value="7">Kambing</option>
												<option value="8">Kuda</option>
												<option value="9">Ular</option>
												<option value="10">Naga</option>
												<option value="11">Kelinci</option>
												<option value="12">Harimau</option>
											</select>
											
											
										</td>     
										<td><input type="text" class="form-control checkBetAmount" name="betamount[]" id="betamount_<?php echo $j;?>" data-thousands=","></td>
										<td><input type="text" class="form-control checkDiscount" name="discount[]" id="discount_<?php echo $j;?>" readonly="readonly"></td>
										<td><input type="text" class="form-control checkPaybleAmount" name="payble_amount[]" id="payble_amount_<?php echo $j;?>"readonly="readonly"></td>
									</tr>
								<?php }?>
							<?php }?>
							
							<?php if($currentDate>"2022-01-31" && $currentDate<"2023-01-22") {?>
								<?php for($j=0; $j<10; $j++) {?>
									<tr>
										<td><?php echo $j+1;?></td>
										<td>
											<select class="form-control country-input-width" name="crush[]" id="crush_<?php echo $j;?>">
												<option></option>
												<option value="1">Harimau</option>
												<option value="2">Kerbau</option>
												<option value="3">Tikus</option>
												<option value="4">Babi</option>
												<option value="5">Anjing</option>
												<option value="6">Ayam</option>
												<option value="7">Monyet</option>
												<option value="8">Kambing</option>
												<option value="9">Kuda</option>
												<option value="10">Ular</option>
												<option value="11">Naga</option>
												<option value="12">Kelinci</option>
											</select>
										</td>     
										<td><input type="text" class="form-control checkBetAmount" name="betamount[]" id="betamount_<?php echo $j;?>" data-thousands=","></td>
										<td><input type="text" class="form-control checkDiscount" name="discount[]" id="discount_<?php echo $j;?>" readonly="readonly"></td>
										<td><input type="text" class="form-control checkPaybleAmount" name="payble_amount[]" id="payble_amount_<?php echo $j;?>"readonly="readonly"></td>
									</tr>
								<?php }?>
							<?php }?>
							
							<?php if($currentDate>"2023-01-21" && $currentDate<"2024-02-10") {?>
								<?php for($j=0; $j<10; $j++) {?>
									<tr>
										<td><?php echo $j+1;?></td>
										<td>
											<select class="form-control country-input-width" name="crush[]" id="crush_<?php echo $j;?>">
												<option></option>
												<option value="1">Kelinci</option>
												<option value="2">Harimau</option>
												<option value="3">Kerbau</option>
												<option value="4">Tikus</option>
												<option value="5">Babi</option>
												<option value="6">Anjing</option>
												<option value="7">Ayam</option>
												<option value="8">Monyet</option>
												<option value="9">Kambing</option>
												<option value="10">Kuda</option>
												<option value="11">Ular</option>
												<option value="12">Naga</option>
											</select>
										</td>     
										<td><input type="text" class="form-control checkBetAmount" name="betamount[]" id="betamount_<?php echo $j;?>" data-thousands=","></td>
										<td><input type="text" class="form-control checkDiscount" name="discount[]" id="discount_<?php echo $j;?>" readonly="readonly"></td>
										<td><input type="text" class="form-control checkPaybleAmount" name="payble_amount[]" id="payble_amount_<?php echo $j;?>"readonly="readonly"></td>
									</tr>
								<?php }?>
							<?php }?>
							
							
							
							<?php if($currentDate>"2024-02-09" && $currentDate<"2025-01-29") {?>
								<?php for($j=0; $j<10; $j++) {?>
									<tr>
										<td><?php echo $j+1;?></td>
										<td>
											<select class="form-control country-input-width" name="crush[]" id="crush_<?php echo $j;?>">
												<option></option>
												<option value="1">Naga</option>
												<option value="2">Kelinci</option>
												<option value="3">Harimau</option>
												<option value="4">Kerbau</option>
												<option value="5">Tikus</option>
												<option value="6">Babi</option>
												<option value="7">Anjing</option>
												<option value="8">Ayam</option>
												<option value="9">Monyet</option>
												<option value="10">Kambing</option>
												<option value="11">Kuda</option>
												<option value="12">Ular</option>
											</select>
										</td>     
										<td><input type="text" class="form-control checkBetAmount" name="betamount[]" id="betamount_<?php echo $j;?>" data-thousands=","></td>
										<td><input type="text" class="form-control checkDiscount" name="discount[]" id="discount_<?php echo $j;?>" readonly="readonly"></td>
										<td><input type="text" class="form-control checkPaybleAmount" name="payble_amount[]" id="payble_amount_<?php echo $j;?>"readonly="readonly"></td>
									</tr>
								<?php }?>
							<?php }?>
							
							
							
							<?php if($currentDate>"2025-01-28" && $currentDate<"2026-02-17") {?>
								<?php for($j=0; $j<10; $j++) {?>
									<tr>
										<td><?php echo $j+1;?></td>
										<td>
											<select class="form-control country-input-width" name="crush[]" id="crush_<?php echo $j;?>">
												<option></option>
												<option value="1">Ular</option>
												<option value="2">Naga</option>
												<option value="3">Kelinci</option>
												<option value="4">Harimau</option>
												<option value="5">Kerbau</option>
												<option value="6">Tikus</option>
												<option value="7">Babi</option>
												<option value="8">Anjing</option>
												<option value="9">Ayam</option>
												<option value="10">Monyet</option>
												<option value="11">Kambing</option>
												<option value="12">Kuda</option>
											</select>
										</td>     
										<td><input type="text" class="form-control checkBetAmount" name="betamount[]" id="betamount_<?php echo $j;?>" data-thousands=","></td>
										<td><input type="text" class="form-control checkDiscount" name="discount[]" id="discount_<?php echo $j;?>" readonly="readonly"></td>
										<td><input type="text" class="form-control checkPaybleAmount" name="payble_amount[]" id="payble_amount_<?php echo $j;?>"readonly="readonly"></td>
									</tr>
								<?php }?>
							<?php }?>
							
							
							<tr style="background:#333333; color:#FFFFFF">
								<td colspan="2" align="center" style="border:none;">Total</td>
								<td style="border:none;"><input type="text" class="form-control" id="t_totalBetAmount" readonly="readonly"></td>
								<td style="border:none;"><input type="text" class="form-control" id="t_totalDiscount" readonly="readonly"></td>
								<td style="border:none;"><input type="text" class="form-control" name="totalPay" id="t_totalPaybleAmount" readonly="readonly"></td>
							</tr>
						</tbody>
					</table>
					<div class="col-md-12">
						<input type="button" name="simpan" class="game-more-btn pull-right" onclick = this.form.submit(); value="SAVE">
					</div>
				</div>  <!-------end of table------>
			</form>
			<?php echo $arrShioDetails['cms_page_details']; ?>
			<!--end-of col-md-12--> 
		</div> <!--end-of col-md-8--> 
		<div class="col-md-4 rdc-padding">
			
			<?php if($currentDate>"2016-02-07" && $currentDate<"2017-01-28") {?>
				<table width="100%" style="border:1px solid #CCCCCC" cellpadding="0" cellspacing="0" border="1" class="sh">
					<tbody>
						<tr>
							<td class="shio_tabel" colspan="10">TABLE Shio</td>
						</tr>
						<tr>
							<td class="sh">Monyet</td>
							<td class="sh">1</td>
							<td class="sh">13</td>
							<td class="sh">25</td>
							<td class="sh">37</td>
							<td class="sh">49</td>
							<td class="sh">61</td>
							<td class="sh">73</td>
							<td class="sh">85</td>
							<td class="sh">97</td>
						</tr>
						<tr>
							<td class="sh">Kambing</td>
							<td class="sh">2</td>
							<td class="sh">14</td>
							<td class="sh">26</td>
							<td class="sh">38</td>
							<td class="sh">50</td>
							<td class="sh">62</td>
							<td class="sh">74</td>
							<td class="sh">86</td>
							<td class="sh">98</td>
						</tr>
						<tr>
							<td class="sh">Kuda</td>
							<td class="sh">3</td>
							<td class="sh">15</td>
							<td class="sh">27</td>
							<td class="sh">39</td>
							<td class="sh">51</td>
							<td class="sh">63</td>
							<td class="sh">75</td>
							<td class="sh">87</td>
							<td class="sh">99</td>
						</tr>
						<tr>
							<td class="sh">Ular</td>
							<td class="sh">4</td>
							<td class="sh">16</td>
							<td class="sh">28</td>
							<td class="sh">40</td>
							<td class="sh">52</td>
							<td class="sh">64</td>
							<td class="sh">76</td>
							<td class="sh">88</td>
							<td class="sh">00</td>
						</tr>
						<tr>
							<td class="sh">Naga</td>
							<td class="sh">5</td>
							<td class="sh">17</td>
							<td class="sh">29</td>
							<td class="sh">41</td>
							<td class="sh">53</td>
							<td class="sh">65</td>
							<td class="sh">77</td>
							<td class="sh">89</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kelinci</td>
							<td class="sh">6</td>
							<td class="sh">18</td>
							<td class="sh">30</td>
							<td class="sh">42</td>
							<td class="sh">54</td>
							<td class="sh">66</td>
							<td class="sh">78</td>
							<td class="sh">90</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Harimau</td>
							<td class="sh">7</td>
							<td class="sh">19</td>
							<td class="sh">31</td>
							<td class="sh">43</td>
							<td class="sh">55</td>
							<td class="sh">67</td>
							<td class="sh">79</td>
							<td class="sh">91</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kerbau</td>
							<td class="sh">8</td>
							<td class="sh">20</td>
							<td class="sh">32</td>
							<td class="sh">44</td>
							<td class="sh">56</td>
							<td class="sh">68</td>
							<td class="sh">80</td>
							<td class="sh">92</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Tikus</td>
							<td class="sh">9</td>
							<td class="sh">21</td>
							<td class="sh">33</td>
							<td class="sh">45</td>
							<td class="sh">57</td>
							<td class="sh">69</td>
							<td class="sh">81</td>
							<td class="sh">93</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Babi</td>
							<td class="sh">10</td>
							<td class="sh">22</td>
							<td class="sh">34</td>
							<td class="sh">46</td>
							<td class="sh">58</td>
							<td class="sh">70</td>
							<td class="sh">82</td>
							<td class="sh">94</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Anjing</td>
							<td class="sh">11</td>
							<td class="sh">23</td>
							<td class="sh">35</td>
							<td class="sh">47</td>
							<td class="sh">59</td>
							<td class="sh">71</td>
							<td class="sh">83</td>
							<td class="sh">95</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Ayam  </td>
							<td class="sh">12</td>
							<td class="sh">24</td>
							<td class="sh">36</td>
							<td class="sh">48</td>
							<td class="sh">60</td>
							<td class="sh">72</td>
							<td class="sh">84</td>
							<td class="sh">96</td>
							<td class="sh"></td>
						</tr>
						<tr>
						</tr>
					</tbody>
				</table>
			<?php }?>
			
			<?php if($currentDate>"2017-01-27" && $currentDate<"2018-02-16") {?>
				<table width="100%" style="border:1px solid #CCCCCC" cellpadding="0" cellspacing="0" border="1" class="sh">
					<tbody>
						<tr>
							<td class="shio_tabel" colspan="10">TABLE Shio</td>
						</tr>
						<tr>
							<td class="sh">Ayam</td>
							<td class="sh">1</td>
							<td class="sh">13</td>
							<td class="sh">25</td>
							<td class="sh">37</td>
							<td class="sh">49</td>
							<td class="sh">61</td>
							<td class="sh">73</td>
							<td class="sh">85</td>
							<td class="sh">97</td>
						</tr>
						<tr>
							<td class="sh">Monyet</td>
							<td class="sh">2</td>
							<td class="sh">14</td>
							<td class="sh">26</td>
							<td class="sh">38</td>
							<td class="sh">50</td>
							<td class="sh">62</td>
							<td class="sh">74</td>
							<td class="sh">86</td>
							<td class="sh">98</td>
						</tr>
						<tr>
							<td class="sh">Kambing</td>
							<td class="sh">3</td>
							<td class="sh">15</td>
							<td class="sh">27</td>
							<td class="sh">39</td>
							<td class="sh">51</td>
							<td class="sh">63</td>
							<td class="sh">75</td>
							<td class="sh">87</td>
							<td class="sh">99</td>
						</tr>
						<tr>
							<td class="sh">Kuda</td>
							<td class="sh">4</td>
							<td class="sh">16</td>
							<td class="sh">28</td>
							<td class="sh">40</td>
							<td class="sh">52</td>
							<td class="sh">64</td>
							<td class="sh">76</td>
							<td class="sh">88</td>
							<td class="sh">00</td>
						</tr>
						<tr>
							<td class="sh">Ular</td>
							<td class="sh">5</td>
							<td class="sh">17</td>
							<td class="sh">29</td>
							<td class="sh">41</td>
							<td class="sh">53</td>
							<td class="sh">65</td>
							<td class="sh">77</td>
							<td class="sh">89</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Naga</td>
							<td class="sh">6</td>
							<td class="sh">18</td>
							<td class="sh">30</td>
							<td class="sh">42</td>
							<td class="sh">54</td>
							<td class="sh">66</td>
							<td class="sh">78</td>
							<td class="sh">90</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kelinci</td>
							<td class="sh">7</td>
							<td class="sh">19</td>
							<td class="sh">31</td>
							<td class="sh">43</td>
							<td class="sh">55</td>
							<td class="sh">67</td>
							<td class="sh">79</td>
							<td class="sh">91</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Harimau</td>
							<td class="sh">8</td>
							<td class="sh">20</td>
							<td class="sh">32</td>
							<td class="sh">44</td>
							<td class="sh">56</td>
							<td class="sh">68</td>
							<td class="sh">80</td>
							<td class="sh">92</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kerbau</td>
							<td class="sh">9</td>
							<td class="sh">21</td>
							<td class="sh">33</td>
							<td class="sh">45</td>
							<td class="sh">57</td>
							<td class="sh">69</td>
							<td class="sh">81</td>
							<td class="sh">93</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Tikus</td>
							<td class="sh">10</td>
							<td class="sh">22</td>
							<td class="sh">34</td>
							<td class="sh">46</td>
							<td class="sh">58</td>
							<td class="sh">70</td>
							<td class="sh">82</td>
							<td class="sh">94</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Babi</td>
							<td class="sh">11</td>
							<td class="sh">23</td>
							<td class="sh">35</td>
							<td class="sh">47</td>
							<td class="sh">59</td>
							<td class="sh">71</td>
							<td class="sh">83</td>
							<td class="sh">95</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Anjing</td>
							<td class="sh">12</td>
							<td class="sh">24</td>
							<td class="sh">36</td>
							<td class="sh">48</td>
							<td class="sh">60</td>
							<td class="sh">72</td>
							<td class="sh">84</td>
							<td class="sh">96</td>
							<td class="sh"></td>
						</tr>
						<tr>
						</tr>
					</tbody>
				</table>
			<?php }?>
			
			<?php if($currentDate>"2018-02-15" && $currentDate<"2019-02-05") {?>
				<table width="100%" style="border:1px solid #CCCCCC" cellpadding="0" cellspacing="0" border="1" class="sh">
					<tbody>
						<tr>
							<td class="shio_tabel" colspan="10">TABLE Shio</td>
						</tr>
						<tr>
							<td class="sh">Anjing</td>
							<td class="sh">1</td>
							<td class="sh">13</td>
							<td class="sh">25</td>
							<td class="sh">37</td>
							<td class="sh">49</td>
							<td class="sh">61</td>
							<td class="sh">73</td>
							<td class="sh">85</td>
							<td class="sh">97</td>
						</tr>
						<tr>
							<td class="sh">Ayam</td>
							<td class="sh">2</td>
							<td class="sh">14</td>
							<td class="sh">26</td>
							<td class="sh">38</td>
							<td class="sh">50</td>
							<td class="sh">62</td>
							<td class="sh">74</td>
							<td class="sh">86</td>
							<td class="sh">98</td>
						</tr>
						<tr>
							<td class="sh">Monyet</td>
							<td class="sh">3</td>
							<td class="sh">15</td>
							<td class="sh">27</td>
							<td class="sh">39</td>
							<td class="sh">51</td>
							<td class="sh">63</td>
							<td class="sh">75</td>
							<td class="sh">87</td>
							<td class="sh">99</td>
						</tr>
						<tr>
							<td class="sh">Kambing</td>
							<td class="sh">4</td>
							<td class="sh">16</td>
							<td class="sh">28</td>
							<td class="sh">40</td>
							<td class="sh">52</td>
							<td class="sh">64</td>
							<td class="sh">76</td>
							<td class="sh">88</td>
							<td class="sh">00</td>
						</tr>
						<tr>
							<td class="sh">Kuda</td>
							<td class="sh">5</td>
							<td class="sh">17</td>
							<td class="sh">29</td>
							<td class="sh">41</td>
							<td class="sh">53</td>
							<td class="sh">65</td>
							<td class="sh">77</td>
							<td class="sh">89</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Ular</td>
							<td class="sh">6</td>
							<td class="sh">18</td>
							<td class="sh">30</td>
							<td class="sh">42</td>
							<td class="sh">54</td>
							<td class="sh">66</td>
							<td class="sh">78</td>
							<td class="sh">90</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Naga</td>
							<td class="sh">7</td>
							<td class="sh">19</td>
							<td class="sh">31</td>
							<td class="sh">43</td>
							<td class="sh">55</td>
							<td class="sh">67</td>
							<td class="sh">79</td>
							<td class="sh">91</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kelinci</td>
							<td class="sh">8</td>
							<td class="sh">20</td>
							<td class="sh">32</td>
							<td class="sh">44</td>
							<td class="sh">56</td>
							<td class="sh">68</td>
							<td class="sh">80</td>
							<td class="sh">92</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Harimau</td>
							<td class="sh">9</td>
							<td class="sh">21</td>
							<td class="sh">33</td>
							<td class="sh">45</td>
							<td class="sh">57</td>
							<td class="sh">69</td>
							<td class="sh">81</td>
							<td class="sh">93</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kerbau</td>
							<td class="sh">10</td>
							<td class="sh">22</td>
							<td class="sh">34</td>
							<td class="sh">46</td>
							<td class="sh">58</td>
							<td class="sh">70</td>
							<td class="sh">82</td>
							<td class="sh">94</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Tikus</td>
							<td class="sh">11</td>
							<td class="sh">23</td>
							<td class="sh">35</td>
							<td class="sh">47</td>
							<td class="sh">59</td>
							<td class="sh">71</td>
							<td class="sh">83</td>
							<td class="sh">95</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Babi</td>
							<td class="sh">12</td>
							<td class="sh">24</td>
							<td class="sh">36</td>
							<td class="sh">48</td>
							<td class="sh">60</td>
							<td class="sh">72</td>
							<td class="sh">84</td>
							<td class="sh">96</td>
							<td class="sh"></td>
						</tr>
						<tr>
						</tr>
					</tbody>
				</table>
			<?php }?>
			
			<?php if($currentDate>"2019-02-04" && $currentDate<"2020-01-25") {?>
				<table width="100%" style="border:1px solid #CCCCCC" cellpadding="0" cellspacing="0" border="1" class="sh">
					<tbody>
						<tr>
							<td class="shio_tabel" colspan="10">TABLE Shio</td>
						</tr>
						<tr>
							<td class="sh">Babi</td>
							<td class="sh">1</td>
							<td class="sh">13</td>
							<td class="sh">25</td>
							<td class="sh">37</td>
							<td class="sh">49</td>
							<td class="sh">61</td>
							<td class="sh">73</td>
							<td class="sh">85</td>
							<td class="sh">97</td>
						</tr>
						<tr>
							<td class="sh">Anjing</td>
							<td class="sh">2</td>
							<td class="sh">14</td>
							<td class="sh">26</td>
							<td class="sh">38</td>
							<td class="sh">50</td>
							<td class="sh">62</td>
							<td class="sh">74</td>
							<td class="sh">86</td>
							<td class="sh">98</td>
						</tr>
						<tr>
							<td class="sh">Ayam</td>
							<td class="sh">3</td>
							<td class="sh">15</td>
							<td class="sh">27</td>
							<td class="sh">39</td>
							<td class="sh">51</td>
							<td class="sh">63</td>
							<td class="sh">75</td>
							<td class="sh">87</td>
							<td class="sh">99</td>
						</tr>
						<tr>
							<td class="sh">Monyet</td>
							<td class="sh">4</td>
							<td class="sh">16</td>
							<td class="sh">28</td>
							<td class="sh">40</td>
							<td class="sh">52</td>
							<td class="sh">64</td>
							<td class="sh">76</td>
							<td class="sh">88</td>
							<td class="sh">00</td>
						</tr>
						<tr>
							<td class="sh">Kambing</td>
							<td class="sh">5</td>
							<td class="sh">17</td>
							<td class="sh">29</td>
							<td class="sh">41</td>
							<td class="sh">53</td>
							<td class="sh">65</td>
							<td class="sh">77</td>
							<td class="sh">89</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kuda</td>
							<td class="sh">6</td>
							<td class="sh">18</td>
							<td class="sh">30</td>
							<td class="sh">42</td>
							<td class="sh">54</td>
							<td class="sh">66</td>
							<td class="sh">78</td>
							<td class="sh">90</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Ular</td>
							<td class="sh">7</td>
							<td class="sh">19</td>
							<td class="sh">31</td>
							<td class="sh">43</td>
							<td class="sh">55</td>
							<td class="sh">67</td>
							<td class="sh">79</td>
							<td class="sh">91</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Naga</td>
							<td class="sh">8</td>
							<td class="sh">20</td>
							<td class="sh">32</td>
							<td class="sh">44</td>
							<td class="sh">56</td>
							<td class="sh">68</td>
							<td class="sh">80</td>
							<td class="sh">92</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kelinci</td>
							<td class="sh">9</td>
							<td class="sh">21</td>
							<td class="sh">33</td>
							<td class="sh">45</td>
							<td class="sh">57</td>
							<td class="sh">69</td>
							<td class="sh">81</td>
							<td class="sh">93</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Harimau</td>
							<td class="sh">10</td>
							<td class="sh">22</td>
							<td class="sh">34</td>
							<td class="sh">46</td>
							<td class="sh">58</td>
							<td class="sh">70</td>
							<td class="sh">82</td>
							<td class="sh">94</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kerbau</td>
							<td class="sh">11</td>
							<td class="sh">23</td>
							<td class="sh">35</td>
							<td class="sh">47</td>
							<td class="sh">59</td>
							<td class="sh">71</td>
							<td class="sh">83</td>
							<td class="sh">95</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Tikus</td>
							<td class="sh">12</td>
							<td class="sh">24</td>
							<td class="sh">36</td>
							<td class="sh">48</td>
							<td class="sh">60</td>
							<td class="sh">72</td>
							<td class="sh">84</td>
							<td class="sh">96</td>
							<td class="sh"></td>
						</tr>
						<tr>
						</tr>
					</tbody>
				</table>
			<?php }?>
			
			<?php if($currentDate>"2020-01-24" && $currentDate<"2021-02-12") {?>
				<table width="100%" style="border:1px solid #CCCCCC" cellpadding="0" cellspacing="0" border="1" class="sh">
					<tbody>
						<tr>
							<td class="shio_tabel" colspan="10">TABLE Shio</td>
						</tr>
						<tr>
							<td class="sh">Tikus</td>
							<td class="sh">1</td>
							<td class="sh">13</td>
							<td class="sh">25</td>
							<td class="sh">37</td>
							<td class="sh">49</td>
							<td class="sh">61</td>
							<td class="sh">73</td>
							<td class="sh">85</td>
							<td class="sh">97</td>
						</tr>
						<tr>
							<td class="sh">Babi</td>
							<td class="sh">2</td>
							<td class="sh">14</td>
							<td class="sh">26</td>
							<td class="sh">38</td>
							<td class="sh">50</td>
							<td class="sh">62</td>
							<td class="sh">74</td>
							<td class="sh">86</td>
							<td class="sh">98</td>
						</tr>
						<tr>
							<td class="sh">Anjing</td>
							<td class="sh">3</td>
							<td class="sh">15</td>
							<td class="sh">27</td>
							<td class="sh">39</td>
							<td class="sh">51</td>
							<td class="sh">63</td>
							<td class="sh">75</td>
							<td class="sh">87</td>
							<td class="sh">99</td>
						</tr>
						<tr>
							<td class="sh">Ayam</td>
							<td class="sh">4</td>
							<td class="sh">16</td>
							<td class="sh">28</td>
							<td class="sh">40</td>
							<td class="sh">52</td>
							<td class="sh">64</td>
							<td class="sh">76</td>
							<td class="sh">88</td>
							<td class="sh">00</td>
						</tr>
						<tr>
							<td class="sh">Monyet</td>
							<td class="sh">5</td>
							<td class="sh">17</td>
							<td class="sh">29</td>
							<td class="sh">41</td>
							<td class="sh">53</td>
							<td class="sh">65</td>
							<td class="sh">77</td>
							<td class="sh">89</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kambing</td>
							<td class="sh">6</td>
							<td class="sh">18</td>
							<td class="sh">30</td>
							<td class="sh">42</td>
							<td class="sh">54</td>
							<td class="sh">66</td>
							<td class="sh">78</td>
							<td class="sh">90</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kuda</td>
							<td class="sh">7</td>
							<td class="sh">19</td>
							<td class="sh">31</td>
							<td class="sh">43</td>
							<td class="sh">55</td>
							<td class="sh">67</td>
							<td class="sh">79</td>
							<td class="sh">91</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Ular</td>
							<td class="sh">8</td>
							<td class="sh">20</td>
							<td class="sh">32</td>
							<td class="sh">44</td>
							<td class="sh">56</td>
							<td class="sh">68</td>
							<td class="sh">80</td>
							<td class="sh">92</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Naga</td>
							<td class="sh">9</td>
							<td class="sh">21</td>
							<td class="sh">33</td>
							<td class="sh">45</td>
							<td class="sh">57</td>
							<td class="sh">69</td>
							<td class="sh">81</td>
							<td class="sh">93</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kelinci</td>
							<td class="sh">10</td>
							<td class="sh">22</td>
							<td class="sh">34</td>
							<td class="sh">46</td>
							<td class="sh">58</td>
							<td class="sh">70</td>
							<td class="sh">82</td>
							<td class="sh">94</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Harimau</td>
							<td class="sh">11</td>
							<td class="sh">23</td>
							<td class="sh">35</td>
							<td class="sh">47</td>
							<td class="sh">59</td>
							<td class="sh">71</td>
							<td class="sh">83</td>
							<td class="sh">95</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kerbau</td>
							<td class="sh">12</td>
							<td class="sh">24</td>
							<td class="sh">36</td>
							<td class="sh">48</td>
							<td class="sh">60</td>
							<td class="sh">72</td>
							<td class="sh">84</td>
							<td class="sh">96</td>
							<td class="sh"></td>
						</tr>
						<tr>
						</tr>
					</tbody>
				</table>
			<?php }?>
			
			<?php if($currentDate>"2021-02-11" && $currentDate<"2022-02-01") {?>
				<table width="100%" style="border:1px solid #CCCCCC" cellpadding="0" cellspacing="0" border="1" class="sh">
					<tbody>
						<tr>
							<td class="shio_tabel" colspan="10">TABLE Shio</td>
						</tr>
						<tr>
							<td class="sh">Kerbau</td>
							<td class="sh">1</td>
							<td class="sh">13</td>
							<td class="sh">25</td>
							<td class="sh">37</td>
							<td class="sh">49</td>
							<td class="sh">61</td>
							<td class="sh">73</td>
							<td class="sh">85</td>
							<td class="sh">97</td>
						</tr>
						<tr>
							<td class="sh">Tikus</td>
							<td class="sh">2</td>
							<td class="sh">14</td>
							<td class="sh">26</td>
							<td class="sh">38</td>
							<td class="sh">50</td>
							<td class="sh">62</td>
							<td class="sh">74</td>
							<td class="sh">86</td>
							<td class="sh">98</td>
						</tr>
						<tr>
							<td class="sh">Babi</td>
							<td class="sh">3</td>
							<td class="sh">15</td>
							<td class="sh">27</td>
							<td class="sh">39</td>
							<td class="sh">51</td>
							<td class="sh">63</td>
							<td class="sh">75</td>
							<td class="sh">87</td>
							<td class="sh">99</td>
						</tr>
						<tr>
							<td class="sh">Anjing</td>
							<td class="sh">4</td>
							<td class="sh">16</td>
							<td class="sh">28</td>
							<td class="sh">40</td>
							<td class="sh">52</td>
							<td class="sh">64</td>
							<td class="sh">76</td>
							<td class="sh">88</td>
							<td class="sh">00</td>
						</tr>
						<tr>
							<td class="sh">Ayam</td>
							<td class="sh">5</td>
							<td class="sh">17</td>
							<td class="sh">29</td>
							<td class="sh">41</td>
							<td class="sh">53</td>
							<td class="sh">65</td>
							<td class="sh">77</td>
							<td class="sh">89</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Monyet</td>
							<td class="sh">6</td>
							<td class="sh">18</td>
							<td class="sh">30</td>
							<td class="sh">42</td>
							<td class="sh">54</td>
							<td class="sh">66</td>
							<td class="sh">78</td>
							<td class="sh">90</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kambing</td>
							<td class="sh">7</td>
							<td class="sh">19</td>
							<td class="sh">31</td>
							<td class="sh">43</td>
							<td class="sh">55</td>
							<td class="sh">67</td>
							<td class="sh">79</td>
							<td class="sh">91</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kuda</td>
							<td class="sh">8</td>
							<td class="sh">20</td>
							<td class="sh">32</td>
							<td class="sh">44</td>
							<td class="sh">56</td>
							<td class="sh">68</td>
							<td class="sh">80</td>
							<td class="sh">92</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Ular</td>
							<td class="sh">9</td>
							<td class="sh">21</td>
							<td class="sh">33</td>
							<td class="sh">45</td>
							<td class="sh">57</td>
							<td class="sh">69</td>
							<td class="sh">81</td>
							<td class="sh">93</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Naga</td>
							<td class="sh">10</td>
							<td class="sh">22</td>
							<td class="sh">34</td>
							<td class="sh">46</td>
							<td class="sh">58</td>
							<td class="sh">70</td>
							<td class="sh">82</td>
							<td class="sh">94</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kelinci</td>
							<td class="sh">11</td>
							<td class="sh">23</td>
							<td class="sh">35</td>
							<td class="sh">47</td>
							<td class="sh">59</td>
							<td class="sh">71</td>
							<td class="sh">83</td>
							<td class="sh">95</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Harimau</td>
							<td class="sh">12</td>
							<td class="sh">24</td>
							<td class="sh">36</td>
							<td class="sh">48</td>
							<td class="sh">60</td>
							<td class="sh">72</td>
							<td class="sh">84</td>
							<td class="sh">96</td>
							<td class="sh"></td>
						</tr>
						<tr>
						</tr>
					</tbody>
				</table>
			<?php }?>
			
			<?php if($currentDate>"2022-01-31" && $currentDate<"2023-01-22") {?>
				<table width="100%" style="border:1px solid #CCCCCC" cellpadding="0" cellspacing="0" border="1" class="sh">
					<tbody>
						<tr>
							<td class="shio_tabel" colspan="10">TABLE Shio</td>
						</tr>
						<tr>
							<td class="sh">Harimau</td>
							<td class="sh">1</td>
							<td class="sh">13</td>
							<td class="sh">25</td>
							<td class="sh">37</td>
							<td class="sh">49</td>
							<td class="sh">61</td>
							<td class="sh">73</td>
							<td class="sh">85</td>
							<td class="sh">97</td>
						</tr>
						<tr>
							<td class="sh">Kerbau</td>
							<td class="sh">2</td>
							<td class="sh">14</td>
							<td class="sh">26</td>
							<td class="sh">38</td>
							<td class="sh">50</td>
							<td class="sh">62</td>
							<td class="sh">74</td>
							<td class="sh">86</td>
							<td class="sh">98</td>
						</tr>
						<tr>
							<td class="sh">Tikus</td>
							<td class="sh">3</td>
							<td class="sh">15</td>
							<td class="sh">27</td>
							<td class="sh">39</td>
							<td class="sh">51</td>
							<td class="sh">63</td>
							<td class="sh">75</td>
							<td class="sh">87</td>
							<td class="sh">99</td>
						</tr>
						<tr>
							<td class="sh">Babi</td>
							<td class="sh">4</td>
							<td class="sh">16</td>
							<td class="sh">28</td>
							<td class="sh">40</td>
							<td class="sh">52</td>
							<td class="sh">64</td>
							<td class="sh">76</td>
							<td class="sh">88</td>
							<td class="sh">00</td>
						</tr>
						<tr>
							<td class="sh">Anjing</td>
							<td class="sh">5</td>
							<td class="sh">17</td>
							<td class="sh">29</td>
							<td class="sh">41</td>
							<td class="sh">53</td>
							<td class="sh">65</td>
							<td class="sh">77</td>
							<td class="sh">89</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Ayam</td>
							<td class="sh">6</td>
							<td class="sh">18</td>
							<td class="sh">30</td>
							<td class="sh">42</td>
							<td class="sh">54</td>
							<td class="sh">66</td>
							<td class="sh">78</td>
							<td class="sh">90</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Monyet</td>
							<td class="sh">7</td>
							<td class="sh">19</td>
							<td class="sh">31</td>
							<td class="sh">43</td>
							<td class="sh">55</td>
							<td class="sh">67</td>
							<td class="sh">79</td>
							<td class="sh">91</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kambing</td>
							<td class="sh">8</td>
							<td class="sh">20</td>
							<td class="sh">32</td>
							<td class="sh">44</td>
							<td class="sh">56</td>
							<td class="sh">68</td>
							<td class="sh">80</td>
							<td class="sh">92</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kuda</td>
							<td class="sh">9</td>
							<td class="sh">21</td>
							<td class="sh">33</td>
							<td class="sh">45</td>
							<td class="sh">57</td>
							<td class="sh">69</td>
							<td class="sh">81</td>
							<td class="sh">93</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Ular</td>
							<td class="sh">10</td>
							<td class="sh">22</td>
							<td class="sh">34</td>
							<td class="sh">46</td>
							<td class="sh">58</td>
							<td class="sh">70</td>
							<td class="sh">82</td>
							<td class="sh">94</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Naga</td>
							<td class="sh">11</td>
							<td class="sh">23</td>
							<td class="sh">35</td>
							<td class="sh">47</td>
							<td class="sh">59</td>
							<td class="sh">71</td>
							<td class="sh">83</td>
							<td class="sh">95</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kelinci</td>
							<td class="sh">12</td>
							<td class="sh">24</td>
							<td class="sh">36</td>
							<td class="sh">48</td>
							<td class="sh">60</td>
							<td class="sh">72</td>
							<td class="sh">84</td>
							<td class="sh">96</td>
							<td class="sh"></td>
						</tr>
						<tr>
						</tr>
					</tbody>
				</table>
			<?php }?>
			
			<?php if($currentDate>"2023-01-21" && $currentDate<"2024-02-10") {?>
				<table width="100%" style="border:1px solid #CCCCCC" cellpadding="0" cellspacing="0" border="1" class="sh">
					<tbody>
						<tr>
							<td class="shio_tabel" colspan="10">TABLE Shio</td>
						</tr>
						<tr>
							<td class="sh">Kelinci</td>
							<td class="sh">1</td>
							<td class="sh">13</td>
							<td class="sh">25</td>
							<td class="sh">37</td>
							<td class="sh">49</td>
							<td class="sh">61</td>
							<td class="sh">73</td>
							<td class="sh">85</td>
							<td class="sh">97</td>
						</tr>
						<tr>
							<td class="sh">Harimau</td>
							<td class="sh">2</td>
							<td class="sh">14</td>
							<td class="sh">26</td>
							<td class="sh">38</td>
							<td class="sh">50</td>
							<td class="sh">62</td>
							<td class="sh">74</td>
							<td class="sh">86</td>
							<td class="sh">98</td>
						</tr>
						<tr>
							<td class="sh">Kerbau</td>
							<td class="sh">3</td>
							<td class="sh">15</td>
							<td class="sh">27</td>
							<td class="sh">39</td>
							<td class="sh">51</td>
							<td class="sh">63</td>
							<td class="sh">75</td>
							<td class="sh">87</td>
							<td class="sh">99</td>
						</tr>
						<tr>
							<td class="sh">Tikus</td>
							<td class="sh">4</td>
							<td class="sh">16</td>
							<td class="sh">28</td>
							<td class="sh">40</td>
							<td class="sh">52</td>
							<td class="sh">64</td>
							<td class="sh">76</td>
							<td class="sh">88</td>
							<td class="sh">00</td>
						</tr>
						<tr>
							<td class="sh">Babi</td>
							<td class="sh">5</td>
							<td class="sh">17</td>
							<td class="sh">29</td>
							<td class="sh">41</td>
							<td class="sh">53</td>
							<td class="sh">65</td>
							<td class="sh">77</td>
							<td class="sh">89</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Anjing</td>
							<td class="sh">6</td>
							<td class="sh">18</td>
							<td class="sh">30</td>
							<td class="sh">42</td>
							<td class="sh">54</td>
							<td class="sh">66</td>
							<td class="sh">78</td>
							<td class="sh">90</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Ayam</td>
							<td class="sh">7</td>
							<td class="sh">19</td>
							<td class="sh">31</td>
							<td class="sh">43</td>
							<td class="sh">55</td>
							<td class="sh">67</td>
							<td class="sh">79</td>
							<td class="sh">91</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Monyet</td>
							<td class="sh">8</td>
							<td class="sh">20</td>
							<td class="sh">32</td>
							<td class="sh">44</td>
							<td class="sh">56</td>
							<td class="sh">68</td>
							<td class="sh">80</td>
							<td class="sh">92</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kambing</td>
							<td class="sh">9</td>
							<td class="sh">21</td>
							<td class="sh">33</td>
							<td class="sh">45</td>
							<td class="sh">57</td>
							<td class="sh">69</td>
							<td class="sh">81</td>
							<td class="sh">93</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kuda</td>
							<td class="sh">10</td>
							<td class="sh">22</td>
							<td class="sh">34</td>
							<td class="sh">46</td>
							<td class="sh">58</td>
							<td class="sh">70</td>
							<td class="sh">82</td>
							<td class="sh">94</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Ular</td>
							<td class="sh">11</td>
							<td class="sh">23</td>
							<td class="sh">35</td>
							<td class="sh">47</td>
							<td class="sh">59</td>
							<td class="sh">71</td>
							<td class="sh">83</td>
							<td class="sh">95</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Naga</td>
							<td class="sh">12</td>
							<td class="sh">24</td>
							<td class="sh">36</td>
							<td class="sh">48</td>
							<td class="sh">60</td>
							<td class="sh">72</td>
							<td class="sh">84</td>
							<td class="sh">96</td>
							<td class="sh"></td>
						</tr>
						<tr>
						</tr>
					</tbody>
				</table>
			<?php }?>
			
			<?php if($currentDate>"2024-02-09" && $currentDate<"2025-01-29") {?>
				<table width="100%" style="border:1px solid #CCCCCC" cellpadding="0" cellspacing="0" border="1" class="sh">
					<tbody>
						<tr>
							<td class="shio_tabel" colspan="10">TABLE Shio</td>
						</tr>
						<tr>
							<td class="sh">Naga</td>
							<td class="sh">1</td>
							<td class="sh">13</td>
							<td class="sh">25</td>
							<td class="sh">37</td>
							<td class="sh">49</td>
							<td class="sh">61</td>
							<td class="sh">73</td>
							<td class="sh">85</td>
							<td class="sh">97</td>
						</tr>
						<tr>
							<td class="sh">Kelinci</td>
							<td class="sh">2</td>
							<td class="sh">14</td>
							<td class="sh">26</td>
							<td class="sh">38</td>
							<td class="sh">50</td>
							<td class="sh">62</td>
							<td class="sh">74</td>
							<td class="sh">86</td>
							<td class="sh">98</td>
						</tr>
						<tr>
							<td class="sh">Harimau</td>
							<td class="sh">3</td>
							<td class="sh">15</td>
							<td class="sh">27</td>
							<td class="sh">39</td>
							<td class="sh">51</td>
							<td class="sh">63</td>
							<td class="sh">75</td>
							<td class="sh">87</td>
							<td class="sh">99</td>
						</tr>
						<tr>
							<td class="sh">Kerbau</td>
							<td class="sh">4</td>
							<td class="sh">16</td>
							<td class="sh">28</td>
							<td class="sh">40</td>
							<td class="sh">52</td>
							<td class="sh">64</td>
							<td class="sh">76</td>
							<td class="sh">88</td>
							<td class="sh">00</td>
						</tr>
						<tr>
							<td class="sh">Tikus</td>
							<td class="sh">5</td>
							<td class="sh">17</td>
							<td class="sh">29</td>
							<td class="sh">41</td>
							<td class="sh">53</td>
							<td class="sh">65</td>
							<td class="sh">77</td>
							<td class="sh">89</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Babi</td>
							<td class="sh">6</td>
							<td class="sh">18</td>
							<td class="sh">30</td>
							<td class="sh">42</td>
							<td class="sh">54</td>
							<td class="sh">66</td>
							<td class="sh">78</td>
							<td class="sh">90</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Anjing</td>
							<td class="sh">7</td>
							<td class="sh">19</td>
							<td class="sh">31</td>
							<td class="sh">43</td>
							<td class="sh">55</td>
							<td class="sh">67</td>
							<td class="sh">79</td>
							<td class="sh">91</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Ayam</td>
							<td class="sh">8</td>
							<td class="sh">20</td>
							<td class="sh">32</td>
							<td class="sh">44</td>
							<td class="sh">56</td>
							<td class="sh">68</td>
							<td class="sh">80</td>
							<td class="sh">92</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Monyet</td>
							<td class="sh">9</td>
							<td class="sh">21</td>
							<td class="sh">33</td>
							<td class="sh">45</td>
							<td class="sh">57</td>
							<td class="sh">69</td>
							<td class="sh">81</td>
							<td class="sh">93</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kambing</td>
							<td class="sh">10</td>
							<td class="sh">22</td>
							<td class="sh">34</td>
							<td class="sh">46</td>
							<td class="sh">58</td>
							<td class="sh">70</td>
							<td class="sh">82</td>
							<td class="sh">94</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kuda</td>
							<td class="sh">11</td>
							<td class="sh">23</td>
							<td class="sh">35</td>
							<td class="sh">47</td>
							<td class="sh">59</td>
							<td class="sh">71</td>
							<td class="sh">83</td>
							<td class="sh">95</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Ular</td>
							<td class="sh">12</td>
							<td class="sh">24</td>
							<td class="sh">36</td>
							<td class="sh">48</td>
							<td class="sh">60</td>
							<td class="sh">72</td>
							<td class="sh">84</td>
							<td class="sh">96</td>
							<td class="sh"></td>
						</tr>
						<tr>
						</tr>
					</tbody>
				</table>
			<?php }?>
			
			<?php if($currentDate>"2025-01-28" && $currentDate<"2026-02-17") {?>
				<table width="100%" style="border:1px solid #CCCCCC" cellpadding="0" cellspacing="0" border="1" class="sh">
					<tbody>
						<tr>
							<td class="shio_tabel" colspan="10">TABLE Shio</td>
						</tr>
						<tr>
							<td class="sh">Ular</td>
							<td class="sh">1</td>
							<td class="sh">13</td>
							<td class="sh">25</td>
							<td class="sh">37</td>
							<td class="sh">49</td>
							<td class="sh">61</td>
							<td class="sh">73</td>
							<td class="sh">85</td>
							<td class="sh">97</td>
						</tr>
						<tr>
							<td class="sh">Naga</td>
							<td class="sh">2</td>
							<td class="sh">14</td>
							<td class="sh">26</td>
							<td class="sh">38</td>
							<td class="sh">50</td>
							<td class="sh">62</td>
							<td class="sh">74</td>
							<td class="sh">86</td>
							<td class="sh">98</td>
						</tr>
						<tr>
							<td class="sh">Kelinci</td>
							<td class="sh">3</td>
							<td class="sh">15</td>
							<td class="sh">27</td>
							<td class="sh">39</td>
							<td class="sh">51</td>
							<td class="sh">63</td>
							<td class="sh">75</td>
							<td class="sh">87</td>
							<td class="sh">99</td>
						</tr>
						<tr>
							<td class="sh">Harimau</td>
							<td class="sh">4</td>
							<td class="sh">16</td>
							<td class="sh">28</td>
							<td class="sh">40</td>
							<td class="sh">52</td>
							<td class="sh">64</td>
							<td class="sh">76</td>
							<td class="sh">88</td>
							<td class="sh">00</td>
						</tr>
						<tr>
							<td class="sh">Kerbau</td>
							<td class="sh">5</td>
							<td class="sh">17</td>
							<td class="sh">29</td>
							<td class="sh">41</td>
							<td class="sh">53</td>
							<td class="sh">65</td>
							<td class="sh">77</td>
							<td class="sh">89</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Tikus</td>
							<td class="sh">6</td>
							<td class="sh">18</td>
							<td class="sh">30</td>
							<td class="sh">42</td>
							<td class="sh">54</td>
							<td class="sh">66</td>
							<td class="sh">78</td>
							<td class="sh">90</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Babi</td>
							<td class="sh">7</td>
							<td class="sh">19</td>
							<td class="sh">31</td>
							<td class="sh">43</td>
							<td class="sh">55</td>
							<td class="sh">67</td>
							<td class="sh">79</td>
							<td class="sh">91</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Anjing</td>
							<td class="sh">8</td>
							<td class="sh">20</td>
							<td class="sh">32</td>
							<td class="sh">44</td>
							<td class="sh">56</td>
							<td class="sh">68</td>
							<td class="sh">80</td>
							<td class="sh">92</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Ayam</td>
							<td class="sh">9</td>
							<td class="sh">21</td>
							<td class="sh">33</td>
							<td class="sh">45</td>
							<td class="sh">57</td>
							<td class="sh">69</td>
							<td class="sh">81</td>
							<td class="sh">93</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Monyet</td>
							<td class="sh">10</td>
							<td class="sh">22</td>
							<td class="sh">34</td>
							<td class="sh">46</td>
							<td class="sh">58</td>
							<td class="sh">70</td>
							<td class="sh">82</td>
							<td class="sh">94</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kambing</td>
							<td class="sh">11</td>
							<td class="sh">23</td>
							<td class="sh">35</td>
							<td class="sh">47</td>
							<td class="sh">59</td>
							<td class="sh">71</td>
							<td class="sh">83</td>
							<td class="sh">95</td>
							<td class="sh"></td>
						</tr>
						<tr>
							<td class="sh">Kuda</td>
							<td class="sh">12</td>
							<td class="sh">24</td>
							<td class="sh">36</td>
							<td class="sh">48</td>
							<td class="sh">60</td>
							<td class="sh">72</td>
							<td class="sh">84</td>
							<td class="sh">96</td>
							<td class="sh"></td>
						</tr>
						<tr>
						</tr>
					</tbody>
				</table>
			<?php }?>
			
			
		</div> <!--end-of col-md-4--> 
		<div class="clear"></div>
	</div><!--end-of game-body--> 
</div>


						
							<div class="clear"></div>
						</div>  <!--end-of information-page-area--> 
					</div> <!--end-of col-md-9--> 
					
					
					
					
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