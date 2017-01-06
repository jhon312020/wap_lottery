<?php require_once("includes/head.php");
	//require_once("includes/check-authentication.php");
	$resMarket = mysql_query("SELECT * FROM lottery_market ORDER BY market_id ASC");
	if(isset($_REQUEST['market']))
	{
		$market = $_REQUEST['market'];
	}
	else{
		$market = "Sandsmacao";
	}
	if(isset($market) && $market!='') {
		$resWinNumber = mysql_query("SELECT * FROM lottery_win_number WHERE wn_market = '".$market."' ORDER BY wn_date DESC");
	}
	if(isset($market) && $market!='') {
		$countWin = mysql_num_rows(mysql_query("SELECT * FROM lottery_win_number WHERE wn_market = '".$market."'"));
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once("includes/html_head.php");?>
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
					<!--end-of col-md-3-->
					<div class="col-md-12 rdc-padding">
						<div class="information-page-area">
							
							<!--end-of header-->
							<div  class="clear"></div>
							<div  class="col-md-12 mrg-top-20">
								<div class="game-body" >
									<h1>DATA NUMBER OUTPUT <br>
									<small style="font-size:14px;">Data Output / Market of the results that have been in the lottery .</small> </h1>
									<div class="col-md-12 " style="border-bottom: 1px dotted #1B1C21;"> 
										<span style="float:left;"><p>MARKET</p></span> 
										<span style="float:left; margin: 10px;"> 
											<?php while($arrMarket = mysql_fetch_array($resMarket)) {?>
												<a href="data_number_result.php?market=<?php echo $arrMarket['market_name'];?>">
													<div class="btn_mini_red"><?php echo $arrMarket['market_name'];?></div>
												</a> 
											<?php }?>
										</span> </div>
										<!--end-of col-md-12-->
										<!--<div class="col-md-12" style="padding:10px ;">
											<p style="color: #121217; margin-top:5px;"><span style="width:120px; float:left; ">Pasar Aktif </span><span style="width:15px; float:left;">:</span> DENMARK</p>
											<p style="color: #121217;"><span style="width:120px; float:left;">Web Resmi</span><span style="width:15px;">:</span></p>
											<p style="color: #121217;"><span style="width:120px; float:left;">Keterangan</span><span style="width:15px; float:left;">:</span><span style="float:left;">Diundi: Minggu,Senin,Selasa, Rabu, Kamis,Jumat, Sabtu.<br>
											Pukul: 21:00 WIB</span></p>
										</div>-->
										<!--end-of col-md-12-->
										<!--<div class="clear"></div>
										<div style="border-bottom: 1px dotted #1B1C21; margin:10px 0px; "></div>-->
										<!--<div class="col-md-12 ">
											<p style="color: #121217; margin-top:5px;"><span style=" float:left; "> DATA NOMOR KELUARAN</span><span style="width:15px; float:left;">:</span> DENMARK</p>
											<p style="color: #121217;; margin-top:5px;"><span style=" float:left; width:120px "> Halaman</span><span style="width:15px; float:left;">:</span> 1</p>
										</div>-->
										<!--end-of col-md-12-->
										
										<div class="col-md-12">			  
											<div class="table-responsive">
												<table class="table table-bordered  data-number-table ">
													<thead>
														<tr>
															<th>Sl no.</th>
															<th>DATE</th>
															<th>DAY</th>
															<th>PERIOD</th>
															<th>RESULT</th>
														</tr>
													</thead>
													<tbody>
														<?php
															if($countWin == 0) {?>
															<tr><td colspan="5">No result found.</td></tr>
															<?php } else {?>
															<?php
																$i = 1; 
																while ($arrWinNumber = mysql_fetch_array($resWinNumber)) {?>
																<tr>
																	<td><?php echo $i;?></td>
																	<td><?php echo $arrWinNumber['wn_date'];?></td>
																	<td><?php echo $arrWinNumber['wn_day']; ?></td>
																	<td><?php echo $arrWinNumber['wn_period']; ?></td>
																	<td><?php echo $arrWinNumber['wn_number']; ?></td>
																</tr>
															<?php $i++;}}?>
													</tbody>
												</table>
											</div>
											
										</div><!-----end of col-md-12----------->
										<div class="clear"></div>
								</div>
								<!--end-of game-body-->
							</div>
							<div class="clear"></div>
						</div>
						<!--end-of information-page-area-->
						
      
						<div class="col-md-12 margin50">
							<?php require_once("includes/bottom_box.php");?>
						</div>
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
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	</body>
</html>
