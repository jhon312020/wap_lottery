<?php require_once("includes/head.php");
require_once("includes/check-authentication.php");
$mem_id = $_SESSION['lottery']['memberid'];
$resgameCategory = mysql_query("SELECT * FROM lottery_game_category ORDER BY id ASC");
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
          <div  class="col-md-12 mrg-top-20">
            <div class="game-body" >
              <h1>WIN LOSE GAMES <br>
                <small style="font-size:14px;">Data Output / Market of the results that have been in the lottery .</small> </h1>
                <div class="clear"></div>
              <div style="border-bottom: 1px dotted #1B1C21; margin:10px 0px; "></div>
              <div class="col-md-12">
               <form>
                <!--<div class="col-md-2 rdc-padding"> 
                <p style="color: #121217;; margin-top:5px;"> Periode transaksi</p>
                </div>-->
                
                 <!--<div class="col-md-4"> 
              		<select class="form-control" id="sel1">
               	 <option>Pilih Periode</option>
                	<option>SG-565 - 03-02-2016</option>
               	 <option>SG-564 - 01-02-2016</option>
               	 <option>SG-563 - 31-01-2016</option>
             	 </select>
                </div>-->
                 <!--<div class="col-md-2 rdc-padding"> 
              		<input name="" type="submit" value="Go" style="padding: 4px 12px; margin-top: 1px;">
                </div>-->
				</form>
              </div>
              <!--end-of col-md-12-->
			  
			  <div class="col-md-12">			  
			  <div class="table-responsive">
        <table class="table table-bordered  data-number-table ">
        <thead>
          <tr>
            <th>NO</th>
            <th>Game</th>
            <th>Beli</th>
    		    <th>Bayar</th>
            <th>Menang</th>
          </tr>
        </thead>
    <tbody>
     <?php 
     $i =1;
     $tot4D = array();
     $tot3D = array();
     $tot2DD = array();
     $tot2DT = array();
     $tot2DB = array();
     $totCB = array();
     $totMacau = array();
     $totCN = array();
     $totCJ = array();
     $totTengah = array();
     $totDasar = array();
     $tot50 = array();
     $totShio = array();
     $totSilang = array();
     $totHomo = array();
     $totKembang = array();
     $totKombinasi = array();
     $totalWinAmountGain = array();
      while ($arrGameCategory = mysql_fetch_array($resgameCategory)) {
        $gCat = $arrGameCategory['game_category'];
        $arrTotalBetAmount = mysql_fetch_array(mysql_query("SELECT SUM(`p_bet_amount`) as TotalBetAmount 
                                                            FROM lottery_purchase 
                                                            WHERE `p_gametype` = '".$gCat."'
                                                            AND `p_member_id`= '".$mem_id."'"));
        $arrTotalPaybleAmount = mysql_fetch_array(mysql_query("SELECT SUM(`p_payble_amount`) as TotalPayAmount 
                                                            FROM lottery_purchase 
                                                            WHERE `p_gametype` = '".$gCat."'
                                                            AND `p_member_id`= '".$mem_id."'"));
        
        $checkWin = mysql_query("SELECT * 
                                  FROM lottery_purchase
                                  WHERE p_member_id = '".$mem_id."'
                                  AND p_win_status = 'Y' AND p_gametype = '".$gCat."'");
        while($arrCheckWin = mysql_fetch_array($checkWin)) {
          //echo '<pre>';
          //print_r($arrCheckWin);
                $pCategory = $arrCheckWin['p_category'];
                $pGameType = $arrCheckWin['p_gametype'];
                $pBetAmount = $arrCheckWin['p_bet_amount'];
				$pPaybleAmount = $arrCheckWin['p_payble_amount'];
                $pCrushType = $arrCheckWin['p_crush_type'];
                $winCount = $arrCheckWin['p_win_count'];

        if($pGameType == "Kembang") {
            $arrGameDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$pCategory."' and g_type = '".$pCrushType."'"));
            $giftValue = $arrGameDetails['g_gift'];
          } else {
              $arrGameDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$pCategory."' and g_type = '".$pGameType."'"));
              $giftValue = $arrGameDetails['g_gift'];
        }
        if($pGameType == "4D") {
          $WinAmount4D = $pBetAmount * $giftValue;
        }
        if($pGameType == "3D") {
          $WinAmount3D = $pBetAmount * $giftValue;
        }
        if($pGameType == "2D D") {
          $WinAmount2DD = $pBetAmount * $giftValue;
        }
        if($pGameType == "2D T") {
          $WinAmount2DT = $pBetAmount * $giftValue;
        }
        if($pGameType == "2D B") {
          $WinAmount2DB = $pBetAmount * $giftValue;
        }
        if($pGameType == "Colok Bebas") {
          $WinAmountCB = $pPaybleAmount + (($pBetAmount * $winCount) * $giftValue);
        }
        if($pGameType == "Macau") {
          if($winCount == "single") {
            $giftValue = $arrGameDetails['g_gift'];
          }
          if($winCount == "double") {
            $giftValue = $arrGameDetails['g_gift_double'];
          }
          if($winCount == "triple") {
            $giftValue = $arrGameDetails['g_gift_triple'];
          }
          $WinAmountMacau = $pPaybleAmount + ($pBetAmount * $giftValue);
        }

        if($pGameType == "Colok Naga") {
          if($winCount == "normal") {
            $giftValue = $arrGameDetails['g_gift'];
          }
          if($winCount == "special") {
            $giftValue = $arrGameDetails['g_gift_double'];
          }
          $WinAmountCN = $pPaybleAmount + ($pBetAmount * $giftValue);
        }

        if($pGameType == "Colok Jitu") {
          $giftValue = $arrGameDetails['g_discount_as'];
          $WinAmountCJ = $pBetAmount * $giftValue;
        }

        if($pGameType == "Tengah") {
          $giftValue = $arrGameDetails['g_gift'];
          $WinAmountTengah = $pPaybleAmount + ($pBetAmount * $giftValue);
        }

        if($pGameType == "Dasar") {
          $giftValue = $arrGameDetails['g_gift'];
			$winPer = ($pBetAmount * $giftValue) / 100;
			$WinAmount = $pPaybleAmount + ($pPaybleAmount + $winPer);
        }

        if($pGameType == "50-50") {
          $giftValue = $arrGameDetails['g_gift'];
          $WinAmount50 = $pBetAmount * $giftValue;
        }

        if($pGameType == "Shio") {
          $giftValue = $arrGameDetails['g_gift'];
          $WinAmountShio = $pPaybleAmount+($pBetAmount * $giftValue);
        }

        if($pGameType == "SILANG") {
          $giftValue = $arrGameDetails['g_gift'];
          $WinAmountSilang = $pPaybleAmount+($pBetAmount * $giftValue);
        }
        if($pGameType == "HOMO") {
          $giftValue = $arrGameDetails['g_gift'];
          $WinAmountHomo = $pPaybleAmount+($pBetAmount * $giftValue);
        }
        if($pGameType == "Kembang") {
          $giftValue = $arrGameDetails['g_gift'];
          $WinAmountKembang = $pPaybleAmount+($pBetAmount * $giftValue);
        }

        if($pGameType == "Kombinasi") {
          $WinAmountKombinasi = $pPaybleAmount+($pBetAmount * $giftValue);
        }
        ///////////////////////// 4D //////////////////////////
        array_push($tot4D,$WinAmount4D);
        $winSum4D = 0;
        foreach($tot4D as $val) {
          $winSum4D += (int)$val;
        }
        //array_push($totalWinAmountGain, $winSum4D);
        ///////////////////////// 4D //////////////////////////

        ///////////////////////// 3D //////////////////////////
        array_push($tot3D,$WinAmount3D);
        $winSum3D = 0;
        foreach($tot3D as $val) {
          $winSum3D += (int)$val;
        }
        //array_push($totalWinAmountGain, $winSum3D);
       ///////////////////////// 3D //////////////////////////

      ///////////////////////// 2D D//////////////////////////  
        array_push($tot2DD,$WinAmount2DD);
        $winSum2DD = 0;
        foreach($tot2DD as $val) {
          $winSum2DD += (int)$val;
        } 
      ///////////////////////// 2D D////////////////////////// 

      ///////////////////////// 2D T////////////////////////// 
        array_push($tot2DT,$WinAmount2DT);
        $winSum2DT = 0;
        foreach($tot2DT as $val) {
          $winSum2DT += (int)$val;
        } 
      ///////////////////////// 2D T////////////////////////// 

      ///////////////////////// 2D B////////////////////////// 
      array_push($tot2DB,$WinAmount2DB);
      $winSum2DB = 0;
      foreach($tot2DB as $val) {
        $winSum2DB += (int)$val;
      }
      ///////////////////////// 2D B//////////////////////////  

      ///////////////////////// COLOK BEBAS//////////////////////////
        array_push($totCB,$WinAmountCB);
        $winSumCB = 0;
        foreach($totCB as $val) {
          $winSumCB += (int)$val;
        }

       ///////////////////////// COLOK BEBAS//////////////////////////   

       ///////////////////////// COLOK BEBAS 2D / Macau //////////////////////////  
        array_push($totMacau,$WinAmountMacau);
        $winSumMacau = 0;
        foreach($totMacau as $val) {
          $winSumMacau += (int)$val;
        }
       ///////////////////////// COLOK BEBAS 2D / Macau ////////////////////////// 

      ///////////////////////// COLOK NAGA////////////////////////// 
        array_push($totCN,$WinAmountCN);
        $winSumCN = 0;
        foreach($totCN as $val) {
          $winSumCN += (int)$val;
        }

      ///////////////////////// COLOK NAGA////////////////////////// 

      ///////////////////////// COLOK Jitu////////////////////////// 
        array_push($totCJ,$WinAmountCJ);
        $winSumCJ = 0;
        foreach($totCJ as $val) {
          $winSumCJ += (int)$val;
        }
      ///////////////////////// COLOK Jitu////////////////////////// 
       ///////////////////////// Tengah////////////////////////// 
        array_push($totTengah,$WinAmountTengah);
        $winSumTengah = 0;
        foreach($totTengah as $val) {
          $winSumTengah += (int)$val;
        }
      ///////////////////////// Tengah////////////////////////// 

      ///////////////////////// DASAR////////////////////////// 
        array_push($totDasar,$WinAmountDasar);
        $winSumDasar = 0;
        foreach($totDasar as $val) {
          $winSumDasar += (int)$val;
        }
      ///////////////////////// DASAR////////////////////////// 

      ///////////////////////// 50 50 //////////////////////////
          array_push($tot50,$WinAmount50);
          $winSum5050 = 0;
          foreach($tot50 as $val) {
            $winSum5050 += (int)$val;
          }
        ///////////////////////// 50 50 //////////////////////////

         ///////////////////////// Shio //////////////////////////
          array_push($totShio,$WinAmountShio);
          $winSumShio = 0;
          foreach($totShio as $val) {
            $winSumShio += (int)$val;
          }
        ///////////////////////// Shio //////////////////////////

        ///////////////////////// SILANG //////////////////////////
          array_push($totSilang,$WinAmountSilang);
          $winSumSilang = 0;
          foreach($totSilang as $val) {
            $winSumSilang += (int)$val;
          }
        ///////////////////////// SILANG //////////////////////////

        ///////////////////////// HOMO //////////////////////////
          array_push($totHomo,$WinAmountHomo);
          $winSumHomo = 0;
          foreach($totHomo as $val) {
            $winSumHomo += (int)$val;
          }
        ///////////////////////// HOMO //////////////////////////

        ///////////////////////// KEMBANG //////////////////////////
          array_push($totKembang,$WinAmountKembang);
          $winSumKembang = 0;
          foreach($totKembang as $val) {
            $winSumKembang += (int)$val;
          }
        ///////////////////////// KEMBANG //////////////////////////
        ///////////////////////// KOMBINASI //////////////////////////
          array_push($totKombinasi,$WinAmountKombinasi);
          $winSumKombinasi = 0;
          foreach($totKombinasi as $val) {
            $winSumKombinasi += (int)$val;
          }
        ///////////////////////// KOMBINASI //////////////////////////
      
        //$totAmntW = 0;
        //foreach($totalWinAmountGain as $val) {

         // $totAmntW += (int)$val;
        //}
        //echo '<pre>';
        //print_r($totalWinAmountGain);
      }
      ?>
    <tr>
      <td><?php echo $i;?></td>
      <td><a href="win-lose-details.php?type=<?php echo $arrGameCategory['game_category'];?>" style="text-decoration:none"><?php echo $arrGameCategory['game_category'];?></a></td>
      <td><?php echo number_format($arrTotalBetAmount['TotalBetAmount']);?></td>
      <td><?php echo number_format($arrTotalPaybleAmount['TotalPayAmount']); ?></td>
      <td>
      <?php if($arrGameCategory['game_category'] == "4D") {?><?php echo $winSum4D; ?><?php }?>
      <?php if($arrGameCategory['game_category'] == "3D") {?><?php echo $winSum3D; ?><?php }?>
      <?php if($arrGameCategory['game_category'] == "2D D") {?><?php echo $winSum2DD; ?><?php }?>
      <?php if($arrGameCategory['game_category'] == "2D T") {?><?php echo $winSum2DT; ?><?php }?>
      <?php if($arrGameCategory['game_category'] == "2D B") {?><?php echo $winSum2DB; ?><?php }?>
      <?php if($arrGameCategory['game_category'] == "Colok Bebas") {?><?php echo $winSumCB; ?><?php }?>
      <?php if($arrGameCategory['game_category'] == "Macau") {?><?php echo $winSumMacau; ?><?php }?>
      <?php if($arrGameCategory['game_category'] == "Colok Naga") {?><?php echo $winSumCN; ?><?php }?>
      <?php if($arrGameCategory['game_category'] == "Colok Jitu") {?><?php echo $winSumCJ; ?><?php }?>
      <?php if($arrGameCategory['game_category'] == "Tengah") {?><?php echo $winSumTengah; ?><?php }?>
      <?php if($arrGameCategory['game_category'] == "Dasar") {?><?php echo $winSumDasar; ?><?php }?>
      <?php if($arrGameCategory['game_category'] == "50-50") {?><?php echo $winSum5050; ?><?php }?>
      <?php if($arrGameCategory['game_category'] == "Shio") {?><?php echo $winSumShio; ?><?php }?>
      <?php if($arrGameCategory['game_category'] == "SILANG") {?><?php echo $winSumSilang; ?><?php }?>
      <?php if($arrGameCategory['game_category'] == "HOMO") {?><?php echo $winSumHomo; ?><?php }?>
      <?php if($arrGameCategory['game_category'] == "Kembang") {?><?php echo $winSumKembang; ?><?php }?>
      <?php if($arrGameCategory['game_category'] == "Kombinasi") {?><?php echo $winSumKombinasi; ?><?php }?>
      </td>
    </tr>
    <?php $i++;}?>
       <!--<tr style="background-color: #000; color: #fff; border:none;">
       <td colspan="3" style="border:none;">TOTAL</td>
       <td colspan="4" style="border:none;">Rp.</td>
        
      </tr>-->
     
      
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
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
</body>
</html>
