<?php
  require_once("includes/head.php");
  require_once("includes/check-authentication.php");
  
  $mem_id = $_SESSION['lottery']['memberid'];
  $marketName = $_REQUEST['name'];
  date_default_timezone_set("Asia/Kuala_Lumpur");
  $currentDate = date('Y-m-d');
  $prevDate = date('Y-m-d', strtotime('-1 day'));
  //////////////////////////////////// C H E C K   I F   A N Y    R E S U L T    E X I S T   /////////////////////////
  $resCheckResult = mysql_query("SELECT * FROM lottery_win_number WHERE wn_market = '".$marketName."' and wn_date = '".$prevDate."'");
  $countResult = mysql_num_rows($resCheckResult);
  $i = 1;
  if($countResult == '') {
    $period =  str_pad($i, 3, '0', STR_PAD_LEFT);
  }
  if($countResult == '1') {
    $period =  str_pad($i+1, 3, '0', STR_PAD_LEFT);
  }
  //////////////////////////////////////  C H E C K   I F   A N Y    R E S U L T    E X I S T ///////////////////////////////////
  if($marketName == "Sandsmacao") {
    $shortCodeMarket = "SM";
  }
  else if($marketName == "Sydney") {
    $shortCodeMarket = "SD";
  }
  else if($marketName == "Sabang") {
    $shortCodeMarket = "SB";
  }
  else if($marketName == "Singapore") {
    $shortCodeMarket = "SG";
  }
  else if($marketName == "Johor") {
    $shortCodeMarket = "JH";
  }
  else{
    $shortCodeMarket = "HK";
  }
  ///////////////////// P  U R C H A S E    N  U  M  B E  R   B  O U G H T     BY    LOGGED    IN     PLAYER /////////////////////
  $resPurchaseLotteryNumber = mysql_query("SELECT * FROM lottery_purchase WHERE `p_status` = '1' AND p_member_id = '".$mem_id."'");
  
  ///////////////////// P  U R C H A S E    N  U  M  B E  R   B  O U G H T     BY    LOGGED    IN     PLAYER /////////////////////
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once("includes/html_head.php");?>
    <script type="text/javascript">
      $(document).ready(function() {
        
        // Setup - add a text input to each footer cell
        $('#numberList tfoot th').each( function () {
          var title = $(this).text();
          $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
        } );
        
        // DataTable
        var table = $('#numberList').DataTable({
          order: [ 8, 'desc' ],
          dom: 'Bfrtip',
          buttons: [
          'excel'
          ]
        });
        
        
        // Apply the search
        table.columns().every( function () {
          var that = this;
          
          $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
              that
              .search( this.value )
              .draw();
            }
          });
        });
      });
    </script>
    <style>
      .label {
      padding: 2px 10px 2px;
      }
      tfoot input {
      width: 100%;
      padding: 3px;
      box-sizing: border-box;
      }
      tfoot {
      display: table-header-group;
      }
      tfoot th input{
      height:30px !important;
      }
      
      .displayTotal span{
        margin-right: 20px;
        color: #000;
      }
      .displayTotal span span{
        font-size:22px;
      }
    </style>
    
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
<div class="information-page-area" style="min-height:1070px;">
<div  class="col-md-12 mrg-top-20">
<div class="game-body" >
<h1>Bought  Number Details</h1>
<div class="col-md-12  rdc-padding">
<div class="col-md-12">
  <div class="table-responsive">
    <p class="displayTotal">
      <span>Bet Amount: <span id="xBetAmount"></span></span>
      <span>Discount: <span id="xDiscount"></span></span>
      <span>Payble Amount: <span id="xPaybleAmount"></span></span>
    </p>
    <table class="table table-bordered  data-number-table" id="numberList">
      <thead>
        <tr>
          <th>NO</th>
          <th>MARKET</th>
          <th>Period</th>
          <th>GAME  CATEGORY</th>
          <th>LOTTERY NUMBER</th>
          <th>Bet Amount</th>
          <th>Discount</th>
          <th>Payble Amount</th>
          <th>PURCHASE DATE & Time</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th>NO</th>
          <th>MARKET</th>
          <th>Period</th>
          <th>GAME  CATEGORY</th>
          <th>LOTTERY NUMBER</th>
          <th>Bet Amount</th>
          <th>Discount</th>
          <th>Payble Amount</th>
          <th>PURCHASE DATE & Time</th>
        </tr>
      </tfoot>
      <tbody>
        <?php 
          
          $total_bet_amount = 0;
          $total_discount = 0;
          $total_payble_amount = 0;
          
          //print_r($_SESSION['lottery']['refusername']); exit;
          $i = 1;
          while($arrPurchaseLottery = mysql_fetch_array($resPurchaseLotteryNumber)) {
          $total_bet_amount += ($arrPurchaseLottery['p_bet_amount'] * 1);
          $total_discount += ($arrPurchaseLottery['p_discount'] * 1);
          $total_payble_amount += ($arrPurchaseLottery['p_payble_amount'] * 1);
          
            //echo '<pre>';
            //print_r($arrPurchaseLottery);
            if($arrPurchaseLottery['p_gametype'] == "Tengah") {
              $lotteryNumber = $arrPurchaseLottery['p_position'];
              } elseif($arrPurchaseLottery['p_gametype'] == "Dasar") {
              $lotteryNumber = $arrPurchaseLottery['p_position'];
              } elseif($arrPurchaseLottery['p_gametype'] == "50-50") {
              $lotteryNumber = $arrPurchaseLottery['p_position']." ".$arrPurchaseLottery['p_crush_type'];
              } elseif($arrPurchaseLottery['p_gametype'] == "SILANG") {
              $lotteryNumber = $arrPurchaseLottery['p_position']." ".$arrPurchaseLottery['p_crush_type'];
              } elseif($arrPurchaseLottery['p_gametype'] == "HOMO") {
              $lotteryNumber = $arrPurchaseLottery['p_position']." ".$arrPurchaseLottery['p_crush_type'];
              } elseif($arrPurchaseLottery['p_gametype'] == "Kembang") {
              $lotteryNumber = $arrPurchaseLottery['p_position']." ".$arrPurchaseLottery['p_crush_type'];
              } elseif($arrPurchaseLottery['p_gametype'] == "Kombinasi") {
              $lotteryNumber = str_replace("_", ' ', $arrPurchaseLottery['p_position'])." ".str_replace("_", ' ', $arrPurchaseLottery['p_position2']);
              } elseif($arrPurchaseLottery['p_gametype'] == "Shio") {
              
              if($currentDate>"2016-02-07" && $currentDate<"2017-01-28") {
                switch($arrPurchaseLottery['p_lottery_no']){
                  case 1:
                    $lotteryNumber = 'Monyet';
                    break;
                  case 2:
                    $lotteryNumber = 'Kambing';
                    break;
                  case 3:
                    $lotteryNumber = 'Kuda';
                    break;
                  case 4:
                    $lotteryNumber = 'Ular';
                    break;
                  case 5:
                    $lotteryNumber = 'Naga';
                    break;
                  case 6:
                    $lotteryNumber = 'Kelinci';
                    break;
                  case 7:
                    $lotteryNumber = 'Harimau';
                    break;
                  case 8:
                    $lotteryNumber = 'Kerbau';
                    break;
                  case 9:
                    $lotteryNumber = 'Tikus';
                    break;
                  case 10:
                    $lotteryNumber = 'Babi';
                    break;
                  case 11:
                    $lotteryNumber = 'Anjing';
                    break;
                  case 12:
                    $lotteryNumber = 'Ayam';
                    break;
                }
              }
              
            } else if ($arrPurchaseLottery['p_gametype'] == "Colok Jitu") {
              $lotteryNumber = $arrPurchaseLottery['p_lottery_no']." ".$arrPurchaseLottery['p_position'];
            }
            else {
              $lotteryNumber = $arrPurchaseLottery['p_lottery_no'];
            }
          ?>
          <tr>
            <td><?php echo $i;?></td>
            <td><?php echo $arrPurchaseLottery['p_category']; ?></td>
            <td><?php echo $arrPurchaseLottery['p_period']; ?></td>
            <td><?php echo $arrPurchaseLottery['p_gametype'];?></td>
            <td><?php echo $lotteryNumber; ?></td>
            <td><?php echo number_format($arrPurchaseLottery['p_bet_amount']);?></td>
            <td><?php echo number_format($arrPurchaseLottery['p_discount']);?></td>
            <td><?php echo number_format($arrPurchaseLottery['p_payble_amount']);?></td>
            <td><?php echo $arrPurchaseLottery['p_date'];?> <?php echo $arrPurchaseLottery['p_time'];?></td>
            
          </tr>
        <?php $i++;}
        
        
        ?>
      </tbody>
    </table>
  </div>
</div>
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


          
          
          <!--end-of col-md-9--> 
          
          <!--end of col-md-12-->
          <div class="clear"></div>
        </div>
        <!--end of game-area-->
        
        <?php require_once("includes/footer.php");?>
        <!--end-of col-md-12--> 
        
        <script type="text/javascript">
          $(window).load(function() {
          
          
            $('#xBetAmount').text("<?php echo $total_bet_amount; ?>");
             $('#xBetAmount').text( $('#xBetAmount').text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
             
            $('#xDiscount').text("<?php echo $total_discount; ?>");
             $('#xDiscount').text( $('#xDiscount').text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
             
            $('#xPaybleAmount').text("<?php echo $total_payble_amount; ?>");
             $('#xPaybleAmount').text( $('#xPaybleAmount').text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
            
          });
        </script>
        
      </div>
      <!--end-of container--> 
    </div>
    <!--end-of container-fluid main-body-area-->
    
  </body>
</html>
