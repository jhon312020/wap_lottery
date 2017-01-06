<?php require_once("includes/head.php"); ?>
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
									<h2>Daftar Referal </h2>
									<p>You have successfully added your referral. Thank you for adding member to our portal.</p>
									<div class="clear">&nbsp;</div>
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