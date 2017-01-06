<?php include("includes/head.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include("includes/html_head.php");?>
</head>
<body>
<?php include("includes/header.php");?>
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
    <div  class="col-md-12 mrg-top-20">
    <div class="game-body" >
    <h1>M E M O</h1>
    
    <?php include("includes/memo_head.php");?>
     <!--end-of col-md-12--> 
    
    <div class="col-md-12 game-panel-area ">
	<div class="table-responsive">
     <table class="table table-bordered game-dashbrd-table" style="color: #111111; font-family: arial; font-size: 12px;" border="2" cellpadding="5" cellspacing="0">
	<tbody>
		<tr>
		<th></th>
			<th> 	DARI</th>
			<th>SUBJECT</th>
			<th>TANGGAL</th>
		
		</tr>
		<tr>
			<td><img src="images/delete.png" alt="DELETE" title="DELETE"></td>
			<td >MANAGEMENT</td>
			<td><a href="#">RE : MASALAH UMUM</a></td>
			<td>2016-01-11 18:53:40</td>
				</tr>
			<tr>
			<td><img src="images/delete.png" alt="DELETE" title="DELETE"></td>
			<td >MANAGEMENT</td>
			<td><a href="#">Deposit Approved</a></td>
			<td>	2015-05-19 14:59:15</td>
				</tr>
	
		<tr bgcolor="#666666">
			<td colspan="6" align="right">Halaman : 1 | 2 RECORD.</td>
		</tr>
	</tbody>
</table>
</div>
	
	</div> <!--end-of col-md-12--> 
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
    
    <?php require_once("includes/footer.php");?>   <!--end-of col-md-12--> 
    
  </div>
  <!--end-of container--> 
</div>
<!--end-of container-fluid main-body-area--> 
</body>
</html>