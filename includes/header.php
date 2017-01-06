<?php
	$sitemail = mysql_fetch_array(mysql_query("SELECT * FROM `lottery_users` WHERE `u_id` = 1"));
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$local_date_time =  date('d M Y,h:i:s');
	$local_date = explode(",",$local_date_time);
	//print_r($local_date); exit;
	$member = mysql_fetch_array(mysql_query("SELECT * FROM `lottery_member_registration` WHERE `member_id` = '".$_SESSION['lottery']['memberid']."'"));
	$companyEmail = mysql_fetch_array(mysql_query("SELECT * FROM lottery_company_setting WHERE cs_id = '1'"));
?>
<div class="page-head">
	<div class="container-fluid head-bg">
		<div class="container">
			<div class="col-md-6 col-sm-6"><a href="index.php"><img src="images/logo.png" alt=""class="img-responsive "></a></div>
			<!-- end of col-md-6-->
			<div class="col-md-6 col-sm-6">
				<div class="col-md-12 pl-right-text">
					<?php if(!isset($_SESSION['lottery']['memberid'])){?>
						<p align="right">
							<!--span class="join-text"><a href="member-registration.php">Join in</a></span>
								<span  style="color:#ff0202"> |</span>
							<span class="join-text"><a href="login.php">Login</a></span-->
							<form method="POST" action="login.php">
								<input type="text" name="uname" placeholder="Username" class="input-box1">
								<input type="password" name="pass" placeholder="Password" class="input-box1">
								<input type="hidden" name="header_login" value="1">
								<input id="captcha_code" name="captcha_code_1" placeholder="Code" type="text" class="input-box2">
								
								<img src="captcha.php?type=head" id='captchaimg'>
								<input type="submit" name="submit" value="Login" class="input-btn">
							</form>
						</p>
						<?php }else{?>
						<p align="right">
							<span class="join-text"><a href="my-account.php">Hi ! <?php echo $member['member_fname'];?></a></span>
							<span  style="color:#ff0202"> |</span>
							<span class="join-text"><a href="logout.php">Logout</a></span>
						</p>
					<?php }?>
					
					<div class="col-md-6 col-sm-6">
						<p class="phone-number"><span><img src="images/email.png" height="16" width="30" alt="" style=	"padding-right:10px;"></span><?php echo $companyEmail['cs_c_email'];?></p>
						<p class="phone-number"><span><img src="images/clock.png" height="16" width="30" alt="" style=	"padding-right:10px;"></span>Indonesia, <?php echo $local_date['0'];?> | <?php echo $local_date['1'];?></p>
					</div>
					
					<?php if(!isset($_SESSION['lottery']['memberid'])){?>
						<div class="col-md-6 col-sm-6">
							<a href="member-registration.php">
								<img src="images/btn-register.png" class="img-responsive" style="width: 180px;" />
							</a>
						</div>
						<?php }
						else{
						?>
						<div class="col-md-6 col-sm-6">
							<a href="dashboard.php" id="playBtn">
								<img src="images/play-btn.png" class="img-responsive" style="width: 180px;" />
							</a>
						</div>
					<?php }?>
				</div>
			</div>
			<!-- end of col-md-6-->
		</div>
		<!-- end of container -->
	</div>
	<!-- end of container-fluid head-bg -->
	<div class="container-fluid  menu-area">
		<div class="navbar navbar-inverse my-navbar-inverse menu-area">
			
			<div class="container">
				<div class="col-md-12">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
					</div>
					<div class="navbar-collapse collapse">
						<ul class="nav navbar-nav" >
							<li><a href="index.php">HOME</a></li>
							<li class="mtop">|</li>
							<li><a href="member-registration.php">DAFTAR</a></li>
							<li class="mtop">|</li>
							<li><a href="cmc.php?Id=27">PERATURAN</a></li>
							<li class="mtop">|</li>
							<li><a href="cmc.php?Id=28">CARA BERMAIN</a></li>
							<li class="mtop">|</li>
							<li><a href="data_number_result.php">PASARAN</a></li>
							<li class="mtop">|</li>
							<li><a href="informations.php">INFORMASI</a></li>
							<li class="mtop">|</li>
							<li><a href="bukumimpi.php">BUKU MIMPI</a></li>
							<li class="mtop">|</li>
							<li><a href="cmc.php?Id=29">PROMOSI</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!--  end of navbar navbar-inverse my-navbar-inverse -->
	</div>
	<!--end-of container-fluid menu-->
</div>
<!--end of page head!-->
<div class="clear"></div>