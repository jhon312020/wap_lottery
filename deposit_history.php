<?php require_once("includes/head.php");
require_once("includes/check-authentication.php");
$mem_id = $_SESSION['lottery']['memberid'];
///////////////////// D  E  P  O  S  I   T    H  I  S  T  O  R  Y     BY    LOGGED    IN     PLAYER /////////////////////
$resDepositHistory = mysql_query("SELECT * FROM lottery_amount_deposit WHERE deposit_mem_id = '".$mem_id."' ORDER BY  deposit_date_time DESC");

///////////////////// D  E  P  O  S  I   T    H  I  S  T  O  R  Y     BY    LOGGED    IN     PLAYER  /////////////////////
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
        <p style="margin-top:6px; color:#fff;">Information</p>
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
              <h1>Deposit History</h1>
              <div class="col-md-12  rdc-padding">
                <div class="col-md-12">
                  <div class="table-responsive">
                    <table class="table table-bordered  data-number-table ">
                      <thead>
                        <tr>
                          <th>NO</th>
                          <th>Deposit To Bank</th>
                          <th>Deposit Amount</th>
                          <th>Deposit Date & Time</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php 
                      $i = 1;
                      while($arrDepositHistory = mysql_fetch_array($resDepositHistory)) {
                      ?>
                        <tr>
                          <td><?php echo $i;?></td>
                          <td><?php echo $arrDepositHistory['deposit_to_bank']; ?></td>
                          <td><?php echo number_format($arrDepositHistory['deposit_amount']); ?></td>
                          <td><?php echo $arrDepositHistory['deposit_date_time'];?></td>
                          <td>
                          <?php if($arrDepositHistory['deposit_status'] == 0) {?>Waiting for Approval<?php }?>
                          <?php if($arrDepositHistory['deposit_status'] == 1) {?>Approved<?php }?>
                          <?php if($arrDepositHistory['deposit_status'] == 2) {?>Rejected<?php }?>
                          </td>
                        </tr>
                        <?php $i++;}?>
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
    
  </div>
  <!--end-of container--> 
</div>
<!--end-of container-fluid main-body-area-->

</body>
</html>