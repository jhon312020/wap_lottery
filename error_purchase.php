<?php include("includes/head.php");
require_once("includes/check-authentication.php");
?>
<!DOCTYPE html>
<html lang="en">
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
    <div class="col-md-12 mrg-top-20 game-page-area" >
     <?php require_once("includes/left_panel_game.php");?>
      <!--end-of col-md-3--> 
    <div class="col-md-9 rdc-padding">
    <div class="information-page-area">
    
     <!--end-of header--> 
    <div  class="clear"></div>
    <div class="delMsg" style="display:none; text-align:center; color:#059208; padding-top:4px; font-size:16px;"></div>
    <div  class="col-md-12 mrg-top-20">
    <div class="game-body" >
    <h1>Purchase Error</h1>
    
   
  <!--end-of col-md-12--> 
    
    <div class="col-md-12 game-panel-area">
    <p>You don't have enough balance for purchasing lottery. Please deposit some amount to you account.</p>
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
    <?php require_once("includes/footer.php");?>
      <!--end-of col-md-12--> 
    
  </div>
  <!--end-of container--> 
</div>
<!--end-of container-fluid main-body-area--> 

</body>
</html>