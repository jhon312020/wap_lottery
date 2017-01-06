<?php
	$Id = 1;
	if(isset($_GET['Id'])){
		$Id = $_GET['Id'];
	}
	require_once("includes/head.php");
	$aboutus = mysql_fetch_array(mysql_query("SELECT * FROM `lottery_cms_pages` WHERE `cms_id` = $Id"));
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once("includes/html_head.php");?>
	</head>
	<body>
		<?php require_once("includes/header.php");?>
		
		<div class="container-fluid main-body-area  menu-padding">
			<div class="container">
				<div class="col-md-12  inner-header-area  rdc-padding">
					
					 <span class="text-atag"> <a href="index.php">Home</a> &nbsp;>&nbsp; <?php echo $aboutus['cms_page_title'];?></span>
				</div>
				
				
				<div class="clear"></div>
				<div class="col-md-12 abt-bg-area mrg-top-20">
					<?php echo $aboutus['cms_page_details'];?>
					
				</div> <!--end-of col-md-12-->
				
				<div class="col-md-12 online-service-area">
					<div class="col-md-4 content-box ">
						<div class="tl-header">Online Lottery Service </div>
						<div class="blk-text-box">
							<p class="bold-text">Lorem ipsum </p>
							<p class="whit-text">dolor sit amet, consecte tuera eget dolor. </p>
							
						</div> 
					</div>
					
					
					<div class="col-md-4 content-box ">
						<div class="tl-header">Online Support Center </div>
						<div class="blk-text-box">
							<div class="col-md-4"><img src="images/clint.png" alt=""></div>
							<div class="col-md-8"> <p class="bold-text">24/7 </p>
							<p class="whit-text">Online Support Center </p></div>
						</div> 
					</div>
					
					
					<div class="col-md-4 content-box ">
						<div class="tl-header">Online Bank Service </div>
						<div class="blk-text-box">
							<div class="col-md-4 "><img src="images/bank1.png " class="mrg-top-5" alt=""></div>
							<div class="col-md-4 "><img src="images/bank2.png" class="mrg-top-5" alt=""></div>
							<div class="col-md-4 "><img src="images/bank3.png"  class="mrg-top-5"alt=""></div>
							<div class="col-md-4 "><img src="images/bank4.png" class="mrg-top-5" alt=""></div>
							<div class="col-md-4 "><img src="images/bank1.png" class="mrg-top-5" alt=""></div>
							<div class="col-md-4 "><img src="images/bank2.png" class="mrg-top-5" alt=""></div>
							
						</div> 
					</div>
					
					<div class="col-md-12 rdc-padding mrg-top-20">
						<div class="col-md-3 "><img src="images/prd-1.png" alt="" ></div>
						<div class="col-md-3 "><img src="images/prd-2.png" alt="" ></div>
						<div class="col-md-3 "><img src="images/prd-3.png" alt="" ></div>
						<div class="col-md-3 "><img src="images/prd-4.png" alt=""></div>
					</div>
					
				</div> <!--end-of col-md-12-->
				
				<?php require_once("includes/footer.php");?>
				
				<!--end-of col-md-12-->
				
			</div> <!--end-of container-->
		</div>  <!--end-of container-fluid main-body-area-->
		
		
		
		
		
	</body>
</html>