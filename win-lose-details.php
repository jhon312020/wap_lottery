<?php require_once("includes/head.php");
require_once("includes/check-authentication.php");
$mem_id = $_SESSION['lottery']['memberid'];
$gameType = $_REQUEST['type'];
$res4d = mysql_query("SELECT * FROM lottery_purchase WHERE p_gametype = '".$gameType."' and p_member_id = '".$mem_id."'");
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
        <div class="information-page-area" style="min-height:1202px;">
         
          <!--end-of header-->
          <div  class="clear"></div>
          <div  class="col-md-12 mrg-top-20">
            <div class="game-body" >
              <h1>WIN LOSE 	DETAILS <br>
                </h1>
                <div class="clear"></div>
              <div style="border-bottom: 1px dotted #1B1C21; margin:10px 0px;"></div>
              
              <div class="col-md-12">			  
                <div class="table-responsive">
                  <table class="table table-bordered  data-number-table">
                    <thead>
                      <tr>
                        <th>SL No.</th>
                        <th>Bought</th>
                        <th>Period</th>
                        <th>Buy Amount</th>
                        <th>Discount</th>
                        <th>Pay value</th>
                        <th>win amount</th>       
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $i = 1;
                    while($arr4d = mysql_fetch_array($res4d)) {
                    $market = $arr4d['p_category'];
                    $crushType = $arr4d['p_crush_type'];
                    $betAmount = $arr4d['p_bet_amount'];
                    $winStatus = $arr4d['p_win_status'];
                    $paybleAmount = $arr4d['p_payble_amount'];
                    $winCount = $arr4d['p_win_count'];
                    if($gameType == "Kembang") {
                      $arrGamePercentage = mysql_fetch_array(mysql_query("SELECT * 
                                                                        FROM lottery_game_setting 
                                                                        WHERE g_market_name = '".$market."' 
                                                                        AND g_type = '".$crushType."'"));
                      $giftValue = $arrGamePercentage['g_gift'];

                    } else {
                    $arrGamePercentage = mysql_fetch_array(mysql_query("SELECT * 
                                                                        FROM lottery_game_setting 
                                                                        WHERE g_market_name = '".$market."' 
                                                                        AND g_type = '".$gameType."'"));
                    $giftValue = $arrGamePercentage['g_gift'];
                  }
                  if($winStatus == "Y" && $gameType == "4D") {
                    $winAmount = $betAmount * $giftValue;
                  }
                  if($winStatus == "Y" && $gameType == "3D") {
                    $winAmount = $betAmount * $giftValue;
                  }
                  if($winStatus == "Y" && $gameType == "2D D") {
                    $winAmount = $betAmount * $giftValue;
                  }
                  if($winStatus == "Y" && $gameType == "2D T") {
                    $winAmount = $betAmount * $giftValue;
                  }
                  if($winStatus == "Y" && $gameType == "2D B") {
                    $winAmount = $betAmount * $giftValue;
                  }
                  if($winStatus == "Y" && $gameType == "Colok Bebas") {
                    $winAmount = $paybleAmount + (($betAmount * $winCount) * $giftValue);
                  }
                  if($winStatus == "Y" && $gameType == "Macau") {

                    if($winCount == "single") {
                      $giftValue = $arrGamePercentage['g_gift'];
                    }
                    if($winCount == "double") {
                      $giftValue = $arrGamePercentage['g_gift_double'];
                    }
                    if($winCount == "triple") {
                      $giftValue = $arrGamePercentage['g_gift_triple'];
                    }
                    $winAmount = $paybleAmount + ($betAmount * $giftValue);
                  }

                  if($winStatus == "Y" && $gameType == "Colok Naga") {
                    if($winCount == "normal") {
                      $giftValue = $arrGamePercentage['g_gift'];
                    }
                    if($winCount == "special") {
                      $giftValue = $arrGamePercentage['g_gift_double'];
                    }
                  $winAmount = $paybleAmount + ($betAmount * $giftValue);
                  }

                  if($winStatus == "Y" && $gameType == "Colok Jitu") {
                    $giftValue = $arrGamePercentage['g_discount_as'];
                    $winAmount = $betAmount * $giftValue;
                  }

                  if($winStatus == "Y" && $gameType == "Tengah") {
                    $giftValue = $arrGamePercentage['g_gift'];
                  $winAmount = $paybleAmount + ($betAmount * $giftValue);
                  }
                  if($winStatus == "Y" && $gameType == "Dasar") {
                    $giftValue = $arrGamePercentage['g_gift'];
				  
					$winPer = ($betAmount * $giftValue) / 100;
					$WinAmount = $paybleAmount + ($paybleAmount + $winPer);
                  }
                  if($winStatus == "Y" && $gameType == "50-50") {
                    $giftValue = $arrGamePercentage['g_gift'];
                  $winAmount = $betAmount * $giftValue;
                  }

                  if($winStatus == "Y" && $gameType == "Shio") {
                    $giftValue = $arrGamePercentage['g_gift'];
                  $winAmount = $paybleAmount+($betAmount * $giftValue);
                  }
                  if($winStatus == "Y" && $gameType == "SILANG") {
                    $giftValue = $arrGamePercentage['g_gift'];
                  $winAmount = $paybleAmount+($betAmount * $giftValue);
                  }
                  if($winStatus == "Y" && $gameType == "HOMO") {
                    $giftValue = $arrGamePercentage['g_gift'];
                  $winAmount = $paybleAmount+($betAmount * $giftValue);
                  }
                  if($winStatus == "Y" && $gameType == "Kembang") {
                    $giftValue = $arrGamePercentage['g_gift'];
                  $winAmount = $paybleAmount+($betAmount * $giftValue);
                  }
                  if($winStatus == "Y" && $gameType == "Kombinasi") {
                  $winAmount = $paybleAmount+($betAmount * $giftValue);
                  }


                    ?>
                      <tr>
                        <td><?php echo $i;?></td>
                        <td><?php if($gameType == "Tengah") {?><?php echo $arr4d['p_position']; ?><?php }?>
                        <?php if($gameType == "Dasar") {?><?php echo $arr4d['p_position']; ?><?php }?>
                        <?php if($gameType == "50-50") {?><?php echo $arr4d['p_position']; ?> | <?php echo $arr4d['p_crush_type']; ?><?php }?>
                        <?php if($gameType == "SILANG") {?><?php echo $arr4d['p_position']; ?> | <?php echo $arr4d['p_crush_type']; ?><?php }?>
                        <?php if($gameType == "HOMO") {?><?php echo $arr4d['p_position']; ?> | <?php echo $arr4d['p_crush_type']; ?><?php }?>
                        <?php if($gameType == "Kembang") {?><?php echo $arr4d['p_crush_type']; ?> | <?php echo $arr4d['p_position']; ?><?php }?>
                        <?php if($gameType == "Kombinasi") {?><?php echo $arr4d['p_crush_type']; ?> | <?php echo $arr4d['p_position']; ?> | <?php echo $arr4d['p_position2']; ?><?php }?>
                         <?php if($arr4d['p_lottery_no']!='') {?><?php echo $arr4d['p_lottery_no'];?><?php }?>
                        </td>
                        <td><?php echo $arr4d['p_period'];?></td>
                        <td><?php echo $arr4d['p_bet_amount'];?></td>
                        <td><?php echo $arr4d['p_discount'];?></td>
                        <td><?php echo $arr4d['p_payble_amount'];?></td>
                        <td><?php if($winStatus == "Y") {?>Win <?php echo $winAmount;?><?php } else {?>Lost <?php echo $paybleAmount;?><?php }?></td>
                      </tr>
                      <?php $i++;}?>
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
      </div>
      <!--end-of col-md-9-->     
      <div class="clear"></div>
    </div>    
    <!--end of  col-md-12  game-area-->
   <?php require_once("includes/footer.php");?>
    <!--end-of col-md-12-->
  </div>
  <!--end-of container-->
</div>
<!--end-of container-fluid main-body-area-->
</body>
</html>
