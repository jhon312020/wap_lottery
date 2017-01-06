<?php require_once("includes/head.php");
  require_once("includes/check-authentication.php");
  $_SESSION['marketName'] = $marketName = $_REQUEST['name'];
  //print_r($_SESSION['lottery']['memberid']); exit;
  date_default_timezone_set("Asia/Kuala_Lumpur");
  $currentDate = date('Y-m-d');
  $prevDate = date('Y-m-d', strtotime('-1 day'));
  //////////////////////////////////// C H E C K   I F   A N Y    R E S U L T    E X I S T   /////////////////////////
  $resCheckResult = mysql_query("SELECT * FROM lottery_win_number WHERE wn_market = '".$marketName."'");
  $countResult = mysql_num_rows($resCheckResult);
  $i = 1;
  if($countResult == 0) {
    $period =  str_pad($i, 3, '0', STR_PAD_LEFT);
  }
  if($countResult == 1 || $countResult > 1) {
    $period =  str_pad($i+$countResult, 3, '0', STR_PAD_LEFT);
  }
  //////////////////////////////////////  C H E C K   I F   A N Y    R E S U L T    E X I S T ///////////////////////////////////
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
  //echo "SELECT * FROM lottery_win_number WHERE wn_market = '".$marketName."' and wn_date = '".$prevDate."'"; exit;
  ///////////////////////////////////////// Check Member Status ///////////////////////////////////////////////////////
  
  $arrCheckMemberStatus = mysql_fetch_array(mysql_query("SELECT * FROM lottery_member_registration WHERE member_id = '".$_SESSION['lottery']['memberid']."'"));
  $memberStatus = $arrCheckMemberStatus['member_status'];
  $notes = $arrCheckMemberStatus['member_notes'];
  //echo $memberStatus; exit;
  ///////////////////////////////////////// Check Member Status ///////////////////////////////////////////////////////
  
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
          <?php if($memberStatus == '2') {?>
            <div class="col-md-9 rdc-padding">
              <div class="information-page-area">
                
                <!--end-of header-->
                <div  class="clear"></div>
                
                <div  class="col-md-12 mrg-top-20">
                  <div class="game-body">
                    <h1 style="background-color:#1A1B1F">You can't place bet as you have been suspended from Admin End !<br></h1>
                    <p style="color:#000; padding:10px;">Reason : <?php echo $notes;?></p>
                    <!--<div class="alternative-area">
                      <p> 
                      <span class="join-text"><a href="#">ALTERNATING</a></span> 
                      <span style="color:#ff0202"> |</span> 
                      <span class="join-text"><a href="#">BB Mix </a></span> 
                      <span style="color:#ff0202"> |</span> 
                      <span class="join-text"><a href="#">4D SET </a></span> 
                      <span style="color:#ff0202"> |</span> 
                      <span class="join-text"><a href="#">QUICK 2D </a></span> 
                      </p>
                    </div>-->
                    <!--end-of alternative-area-->
                    
                    <div class="col-md-12 " style="border-bottom: 1px dotted #1B1C21;">
                      <!--<p class="pull-left" >Period: <?php //echo $shortCodeMarket;?> - <?php //echo $period;?> (<?php //echo $marketName;?>)</p>-->
                      
                    </div>
                    <!--end-of col-md-12-->
                    
                    <div class="col-md-12 mrg-top-20 ">
                      
                      
                      
                      <!--end-of col-md-12--> 
                    </div>
                    <!--end-of col-md-12-->
                    <div class="clear"></div>
                  </div>
                  <!--end-of game-body--> 
                </div>
                <div class="clear"></div>
              </div>
              <!--end-of information-page-area--> 
            </div>
            <?php } else {?>
            
            <div class="col-md-9 rdc-padding">
              <div class="information-page-area">
                <?php if(isset($marketName)) {?>
                  <?php require_once("includes/top_game_category.php"); ?>
                <?php }?>
                <!--end-of header-->
                <div  class="clear"></div>
                
                <div  class="col-md-12 mrg-top-20">
                  <div class="game-body">
                    <h1>SELAMAT DATANG<br>
                    <small style="font-size:14px;">Guessing 4D, 3D, 2D Front / Middle / Rear.</small></h1>
                    <!--<div class="alternative-area">
                      <p> 
                      <span class="join-text"><a href="#">ALTERNATING</a></span> 
                      <span style="color:#ff0202"> |</span> 
                      <span class="join-text"><a href="#">BB Mix </a></span> 
                      <span style="color:#ff0202"> |</span> 
                      <span class="join-text"><a href="#">4D SET </a></span> 
                      <span style="color:#ff0202"> |</span> 
                      <span class="join-text"><a href="#">QUICK 2D </a></span> 
                      </p>
                    </div>-->
                    <!--end-of alternative-area-->
                    
                    <div class="col-md-12 " style="border-bottom: 1px dotted #1B1C21;">
                      <p class="pull-left" >Period: <?php echo $shortCodeMarket;?> - <?php echo $period;?> (<?php echo $marketName;?>)</p>
                      
                    </div>
                    <!--end-of col-md-12-->
                    
                    <div class="col-md-12 mrg-top-20 ">
                      
                      
                      
                      <!--end-of col-md-12--> 
                    </div>
                    <!--end-of col-md-12-->
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
          <?php }?>
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
    
  </body>
</html>
