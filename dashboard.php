<?php require_once("includes/head.php"); 
	require_once("includes/check-authentication.php");
	$arrCMSDashboard = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '16'"));
	$arrCMSLimitBet = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '17'"));
	$arrCMSDiscount = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '18'"));
	$arrCMSRef = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '19'"));
	$arrCMSKemen = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '20'"));
	$arrCMSGames = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '21'"));
?>
<!DOCTYPE html>
<html lang="en">
	<?php require_once("includes/html_head.php");?>
</head>
<body>
	<?php require_once("includes/header.php");?>
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
			<div class="col-md-12 mrg-top-20 game-page-area">
				<?php require_once("includes/left_panel_game.php");?>
				<!--end-of col-md-3--> 
				<div class="col-md-9 rdc-padding">
					<div class="information-page-area">    
						<div  class="clear"></div>
						
						
						<div  class="col-md-12 mrg-top-20">
							<div class="game-body" >
								<div class="col-md-12 game-panel-area">
									<ul class="nav nav-tabs game-tab">
										<li class="active"><a data-toggle="tab" href="#dashbord">DASHBOARD</a></li>
										<li><a data-toggle="tab" href="#menu1">LIMIT BET</a></li>
										<li><a data-toggle="tab" href="#menu2">DISCOUNT</a></li>
										<li><a data-toggle="tab" href="#menu3">REFRENSI</a></li>
										<li><a data-toggle="tab" href="#menu4">KEMENANGAN</a></li>
										<li><a data-toggle="tab" href="#menu5">GAMES</a></li>    
									</ul>
									
									<div class="tab-content brd-btm">
										<?php echo $arrCMSDashboard['cms_page_details']; ?>
										<?php echo $arrCMSLimitBet['cms_page_details']; ?>
										<?php echo $arrCMSDiscount['cms_page_details']; ?>
										<?php echo $arrCMSRef['cms_page_details']; ?>
										<?php echo $arrCMSKemen['cms_page_details']; ?>
										<?php echo $arrCMSGames['cms_page_details']; ?>
										
									</div>
									
								</div> <!--end-of col-md-12--> 
								
								
								
								
								
								<div class="clear"></div>
							</div><!--end-of game-body--> 
						</div>
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
</body>
</html>