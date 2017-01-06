<?php require_once("includes/head.php");
	require_once("includes/check-authentication.php");
	$terms = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '2'"));
	$announcement = mysql_fetch_array(mysql_query("SELECT * FROM lottery_cms_pages WHERE cms_id = '22'"));
?>  
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once("includes/html_head.php");?>
		<style type="text/css">
			.modal {
			display: block;
			position: absolute;
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#modalClose").click(function() {
					$("#my-modal").hide();
					$(".modal-backdrop").hide();
					
				})
			})
		</script>
	</head>
	<body>
		<?php require_once("includes/header.php");?>
		
		<div class="container-fluid main-body-area  menu-padding">
			<div class="container">
				<div class="col-md-12  inner-header-area  rdc-padding">
					
					Agreement &nbsp;>&nbsp;<span class="text-atag"> <a href="index.php">Home</a></span>
				</div>
				
				
				<div class="clear"></div>
				<div class="col-md-12 abt-bg-area mrg-top-20">
					<h2><?php echo $terms['cms_page_title']; ?></h2>
					<?php echo $terms['cms_page_details']; ?>
					
					<!-- Modal -->
					
					<div style="float:right;">
						<a href="my-account.php?action=agree"><input type="submit" class="btn_green" value="Agree" name="agree"></a>
						<a href="logout.php"><input type="submit" class="btn_red" value="DISAGREE" name="disagree"></a>
					</div>
					
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
		
					<div id="my-modal" class="modal"  >
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" id="modalClose">×</button>
									<h4 class="modal-title"><?php echo $announcement['cms_page_title']; ?></h4>
								</div>
								<div class="modal-body">
									<div class="informasi">
										<?php echo $announcement['cms_page_details']; ?>
									</div>
								</div>
							</div>
						</div> 
					</div>
		
		
		<script>
			$(document).ready(function(){
				$("#my-modal").modal({backdrop: true});
				
				
				$("#playBtn").attr('href','#');
				
				$("#playBtn").click(function(){
					alert('Press "Agree" button at below the agreement page.');
					return false;
				});
			});
		</script>
		
	</body>
</html>