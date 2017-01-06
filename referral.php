<?php require_once("includes/head.php");
	require_once("includes/check-authentication.php");
	$mem_id = $_SESSION['lottery']['memberid'];
	$bank_list = mysql_query("SELECT * FROM `lottery_bank`");
	$arrMemberInfo = mysql_fetch_array(mysql_query("SELECT * FROM lottery_member_registration WHERE member_id = '".$mem_id."'"));
	$username = $arrMemberInfo['member_username'];
	$arrReferCount = mysql_fetch_array(mysql_query("SELECT count(*) as TotalReferCount FROM lottery_member_registration WHERE member_referrals = '".$username."'"));
	$referCountTotal = $arrReferCount['TotalReferCount'];
	$resAllReferrals = mysql_query("SELECT * FROM lottery_member_registration WHERE member_referrals = '".$username."'");
	
	$resReferDetails = mysql_query("SELECT * FROM lottery_referral_amount WHERE r_member_id = '".$mem_id."'");
	$mLink = "http://$_SERVER[HTTP_HOST]";
	if($mLink == "http://localhost") {
		$actual_link = "http://$_SERVER[HTTP_HOST]/lottery/ref.php";
		} else {
		$actual_link = "http://$_SERVER[HTTP_HOST]/ref.php";
	}
	$referalLink = $actual_link.'?ref='.$username;
	if(isset($_REQUEST['key']) && $_REQUEST['key'] == "referMemberReg") {
		$firstname = $_REQUEST['fname'];
		$lastname = $_REQUEST['lname'];
		$username = $_REQUEST['username'];
		$password = md5($_REQUEST['password']);
		$phoneno = $_REQUEST['phone_number'];
		$email = $_REQUEST['email'];
		$bankname = $_REQUEST['bank_name'];
		$accountname = $_REQUEST['account_name'];
		$accountnumber = $_REQUEST['acc_number'];
		$referrals = $_REQUEST['referral'];
		
		$sql_reg = "INSERT INTO `lottery_member_registration`(`member_fname`,`member_lname`,`member_username`,`member_password`,`member_phoneno`,`member_email`,`member_bank_name`,`member_account_name`,`member_account_no`,`member_referrals`)VALUES('".$firstname."','".$lastname."','".$username."','".$password."','".$phoneno."','".$email."','".$bankname."','".$accountname."','".$accountnumber."','".$referrals."')";
		$res_reg = mysql_query($sql_reg);
		header("Location:datareferral.php");
		exit();
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
					<?php require_once("includes/left_panel_game.php");?>
					<!--end-of col-md-3--> 
					<div class="col-md-9 rdc-padding">
						<div class="information-page-area">
							<!--end-of header--> 
							<div  class="clear"></div>
							<div  class="col-md-12 mrg-top-20">
								<div class="game-body" >
									<div class="col-md-12 game-panel-area "> 
										<ul class="nav nav-tabs game-tab">
											<li><a data-toggle="tab" href="#prefer-list">Daftar Referal</a></li>
											<li class="active"><a data-toggle="tab" href="#members-refrl">Anggota Referal</a></li>
											<li><a data-toggle="tab" href="#bonus-refrl">Bonus Referral</a></li>
											
										</ul>
										
										<div class="tab-content brd-btm">
											<div id="prefer-list" class="tab-pane fade">
												<h2>Daftar Referal</h2>
												
												<div class="col-md-12 ">
													<div class="col-md-8 ">
														<!--<h2>Member Registration</h2>-->
														<form name="frm" id="member-form" method="POST" action="" role="form">
															<input type="hidden" name="key" value="referMemberReg">
															<div class="form-group">
																<label for="email">First Name * :</label>
																<input type="text" class="form-control" name="fname" id="fname">
															</div>
															<div class="form-group">
																<label for="email">Last Name * :</label>
																<input type="text" class="form-control" name="lname" id="lname">
															</div>
															<div class="form-group">
																<label for="email">User Name * :</label>
																<input type="text" class="form-control" name="username" id="username">
															</div>
															<div class="form-group">
																<label for="email">Password * :</label>
																<input type="password" class="form-control" name="password" id="password">
															</div>
															<div class="form-group">
																<label for="email">Retype Password * :</label>
																<input type="password" class="form-control" name="retype_pass" id="retype_pass">
															</div>
															<div class="form-group">
																<label for="email">Mobile Phone Number *:</label>
																<input type="text" class="form-control" name="phone_number" id="phone_number">
															</div>
															<div class="form-group">
																<label for="email">Email *:</label>
																<input type="text" class="form-control" name="email" id="email">
															</div>
															<div class="form-group">
																<label for="sel1">Bank Name *:</label>
																<select class="form-control" name="bank_name" id="bank_name">
																	<option value="">Please Select</option>
																	<?php while($row_bank = mysql_fetch_array($bank_list)){?>
																		<option value="<?php echo $row_bank['bank_name'];?>"><?php echo $row_bank['bank_name'];?></option>
																	<?php }?>
																</select>
															</div>
															<div class="form-group">
																<label for="email">Account Name *:</label>
																<input type="text" class="form-control" name="account_name" id="account_name">
															</div>
															<div class="form-group">
																<label for="email">Account Number * :</label>
																<input type="text" class="form-control" name="acc_number" id="acc_number">
															</div>
															<div class="form-group">
																<label for="email">Referrals:</label>
																<input type="text" class="form-control" name="referral" id="referral" value="<?php echo $username; ?>" readonly>
															</div>
															<input type="submit" name="submit" value="Register" class="btn btn-default subbtn">
															<!--<button type="submit" class="btn btn-default subbtn">Register</button>-->
														</form>
													</div>
												</div>
												<div class="clear"></div>
												
											</div>
											<div id="members-refrl" class="tab-pane fade in active">
												<h2>Anggota Referal</h2>
												<div class="col-md-12  mrg-top-20  rdc-padding">
													<form class="form-horizontal" role="form">
														<div class="form-group">
															<label class="control-label col-sm-3" for="pwd">Referral link :</label>
															<a href="<?php echo $referalLink; ?>">
																<div class="col-sm-7" style="padding-top:7px;">          
																	<?php if(isset($referalLink)) {?><?php echo $referalLink; ?><?php }?>
																</div>
															</a>
														</div>
														<div style="border-bottom: 1px dotted #1B1C21; margin:10px 0px; "></div>
														<div class="form-group">
															<label class="control-label col-sm-3" for="pwd">Total Refferal:</label>
															<div class="col-sm-7" style="padding-top:7px;"><?php echo $referCountTotal;?></div>
														</div>
													</form>
													<div style="border-bottom: 1px dotted #1B1C21; margin:10px 0px; "></div>
													<table class="table table-bordered  data-number-table ">
														<thead>
															<tr>
																<th>No.</th>
																<th>User</th>
															</tr>
														</thead>
														<tbody>
															
															<?php 
																$i = 1;
																while($arrAllreferrals = mysql_fetch_array($resAllReferrals)) {?>
																<tr>
																	<td><?php echo $i;?></td>
																	<td><?php echo $arrAllreferrals['member_username']; ?></td>
																</tr>
															<?php $i++;}?>
														</tbody>
													</table>
												</div>
												
												<div class="clear"></div>
											</div>
											<div id="bonus-refrl" class="tab-pane fade">
												<h2>Daftar Bonus Referal</h2>
												<div class="col-md-12">              
													<div class="table-responsive">
														<table class="table table-bordered  data-number-table ">
															<thead>
																<tr>
																	<th>NO.</th>
																	<th>PERIODE</th>
																	<th>USER</th>
																	<th>BONUS</th>
																</tr>
															</thead>
															<tbody>
																<?php 
																	$k = 1;
																	while($arrReferDetails = mysql_fetch_array($resReferDetails)) {
																		$purchaseId = $arrReferDetails['r_purchase_id'];
																		$purchaseDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_purchase WHERE p_id = '".$purchaseId."'"));
																		$m_uid = $purchaseDetails['p_member_id'];
																		$memberDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_member_registration WHERE member_id = '".$m_uid."'"));
																	?>
																	<tr>
																		<td><?php echo $k;?></td>
																		<td><?php echo $purchaseDetails['p_period']; ?></td>
																		<td><?php echo $memberDetails['member_username']; ?></td>
																		<td><?php echo $arrReferDetails['r_amount']; ?></td>
																	</tr>
																<?php $k++;}?>
															</tbody>
														</table>
													</div>
												</div>
												<div class="clear"></div>
											</div>
										</div>
									</div> <!--end-of col-md-12--> 
									
									
									
									
									
									<div class="clear"></div>
								</div><!--end-of game-body--> 
							</div> <!----end of game body------>
							
							<!----end of game body------>
							<div class="clear"></div>
						</div>  <!--end-of information-page-area--> 
						
      
						<div class="col-md-12 margin50">
							<?php require_once("includes/bottom_box.php");?>
						</div>
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
		
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
		
	</body>
</html>