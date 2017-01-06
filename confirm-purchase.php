<?php require_once("includes/head.php");
  //$total_amount = $_REQUEST['amount'];
  $marketName = $_SESSION['marketName'];
  $unique_key = $_REQUEST['unique_key'];
  $member_id = $_REQUEST['member_id'];
  $booked_lottery = mysql_query("SELECT * FROM `lottery_purchase` WHERE `p_uniq_time` = '".$unique_key."'");
  if(isset($_REQUEST)&& $_REQUEST['key'] == "confirmPurchase"){
    $sql_update_purchase_status = "UPDATE `lottery_purchase` SET `p_status` = '1' WHERE `p_uniq_time` = '".$unique_key."'";
    $res_purchase_status = mysql_query($sql_update_purchase_status);
    header("Location:success.php?member_id=".$member_id);
    exit();
  }
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
        
        <div class="col-md-12 mrg-top-20 game-page-area" >
          <?php require_once("includes/left_panel_game.php");?>

          <div class="col-md-9 rdc-padding">
            <div class="information-page-area">
              <?php require_once("includes/top_game_category.php"); ?>
              
              <div class="col-md-12 result-bg-area ">
                
                <br/><br/><br/>
                <form name="frm" id="frm" method="POST" action="">
                  <input type="hidden" name="unique_key" value="<?php echo $unique_key?>">
                  <input type="hidden" name="key" value="confirmPurchase">
                  <div class="table-responsive">          
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>lottery Number</th>
                          <th>Game Type</th>
                          <th>lottery Period</th>
                          <th>lottery Market</th>
                          <th>Bet Amount</th>
                          <th>Discount</th>
                          <th>Payble Amount</th>
                          <th>lottery date</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $toalPayAmount = 0;
                          while($row_booked_lottery = mysql_fetch_array($booked_lottery)){
                            $gameType = $row_booked_lottery['p_gametype'];
                            
                            $position = $row_booked_lottery['p_position'];
                            $position2 = $row_booked_lottery['p_crush_type'];
                            
                            $toalPayAmount += ( $row_booked_lottery['p_payble_amount'] * 1);
                          ?>
                          <tr>
                            
                            <td><?php if($gameType == 'Tengah') {?><?php echo $position;?><?php }?>
                              <?php if($gameType == 'Dasar') {?><?php echo $position;?><?php }?>
                              <?php if($gameType == '50-50') {?><?php echo $position;?> | <?php echo $position2;?><?php }?>
                              <?php if($gameType == 'SILANG') {?><?php echo $position2;?> | <?php echo $position;?><?php }?>
                              <?php if($gameType == 'HOMO') {?><?php echo $position2;?> | <?php echo $position;?><?php }?>
                              <?php if($gameType == 'Kembang') {?><?php echo $position2;?> | <?php echo $position;?><?php }?>
                              <?php if($gameType == 'Kombinasi') {?><?php echo $position2;?><?php }?>
                              <?php
                              if($gameType == "Shio") {
              
              if($currentDate>"2016-02-07" && $currentDate<"2017-01-28") {
                switch($row_booked_lottery['p_lottery_no']){
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
              
              echo $lotteryNumber;
              
            }
            else{
              echo $row_booked_lottery['p_lottery_no']; 
            }
            ?>
             </td>
                            <td><?php echo $row_booked_lottery['p_gametype'];?></td>
                            <td><?php echo $row_booked_lottery['p_period'];?></td>
                            <td><?php echo $row_booked_lottery['p_category'];?></td>
                            <td><?php echo $row_booked_lottery['p_bet_amount'];?></td>
                            <td><?php echo $row_booked_lottery['p_discount'];?></td>
                            <td><?php echo $row_booked_lottery['p_payble_amount'];?></td>
                            <td><?php echo $row_booked_lottery['p_date'];?></td>
                          </tr>
                        <?php }?>  
                        
                      </tbody>
                    </table>
                    
                    <?php
                      $user_balance = $remainingBalance + $winSum;
                      $toalPayAmount *= 1;
                      if($user_balance < $toalPayAmount) {
                        echo "<script>window.location='error_purchase.php';</script>";
                        exit();
                      }
                    ?>
                    <div class=" col-md-9 rdc-padding mrg-top-20"> 
                      <span class="pull-left">
                        <input type="submit" name="submit" value="Confirm" class="btn btn-default  add-more-btn">
                        <a href="dashboard.php" class="btn btn-default btn-info"> Cancel </a>
                      </span> 
                    </div>
                  </div>  
                </form>
                
              </div>  <!--end of result-bg-area-->
              
      
            <div class="col-md-12 margin50">
              <?php require_once("includes/bottom_box.php");?>
            </div>
            </div>
          </div>
        </div>
        <?php require_once("includes/footer.php");?>
      </div>
      <!--end-of container--> 
    </div>
    <!--end-of container-fluid main-body-area--> 
  </body>
</html>     
