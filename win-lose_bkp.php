<?php require_once("includes/head.php");
$mem_id = $_SESSION['lottery']['memberid'];
$resgameCategory = mysql_query("SELECT * FROM lottery_game_category ORDER BY id ASC");
while ($arrGameCategory = mysql_fetch_array($resgameCategory)) {
  $gCat = $arrGameCategory['game_category'];
  //echo "SELECT SUM(`p_bet_amount`) as TotalBetAmount FROM lottery_purchase WHERE `p_gametype` = '".$gCat."'and `p_member_id`= '".$mem_id."'"; 
  $arrTotalBetAmount = mysql_fetch_array(mysql_query("SELECT SUM(`p_bet_amount`) as TotalBetAmount FROM lottery_purchase WHERE `p_gametype` = '".$gCat."'and `p_member_id`= '".$mem_id."'"));

}
//$arrTotal3DBetAmount = mysql_fetch_array(mysql_query("SELECT SUM(`p_bet_amount`) as Total4DAmount FROM lottery_purchase WHERE `p_gametype` = '4D' and `p_member_id`= '".$mem_id."'"));
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
          <tr >
            <th>NO</th>
            <th>Game</th>
            <th>Beli</th>
    		    <th>Bayar</th>
            <th>Menang</th>
          </tr>
        </thead>
    <tbody>
    <tr>
      <td>1</td>
      <td><a href="#" style="text-decoration:none">4D</a></td>
      <td><?php echo $arrTotalBetAmount['TotalBetAmount'];?></td>
      <td></td>
      <td></td>
    </tr>
     
      <tr>
        <td>2</td>
        <td><a href="#"  style="text-decoration:none">3D</a></td>
        <td><?php echo $arrTotalBetAmount['TotalBetAmount'];?></td>
        <td></td>
        <td></td>
      </tr>

      <tr>
        <td>3</td>
        <td><a href="#"  style="text-decoration:none">2D D</a></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>

      <tr>
        <td>4</td>
        <td><a href="#"  style="text-decoration:none">2D T</a></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>

      <tr>
        <td>4</td>
        <td><a href="#"  style="text-decoration:none">2D B</a></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>

      <tr>
        <td>5</td>
        <td><a href="#"  style="text-decoration:none">Colok Bebas</a></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>

      <tr>
        <td>6</td>
        <td><a href="#"  style="text-decoration:none">Colok Bebas 2D / Macau</a></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>


       <tr>
        <td>7</td>
        <td><a href="#"  style="text-decoration:none">Colok Naga</a></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>

      <tr>
        <td>8</td>
        <td><a href="#"  style="text-decoration:none">Colok Jitu</a></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>

       <tr>
        <td>9</td>
        <td><a href="#"  style="text-decoration:none">Tengah</a></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>

       <tr>
        <td>10</td>
        <td><a href="#"  style="text-decoration:none">Dasar</a></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>

       <tr>
        <td>11</td>
        <td><a href="#"  style="text-decoration:none">50-50</a></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>

       <tr>
        <td>12</td>
        <td><a href="#"  style="text-decoration:none">Shio</a></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>

      <tr>
        <td>13</td>
        <td><a href="#"  style="text-decoration:none">Silang</a></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>

      <tr>
        <td>14</td>
        <td><a href="#"  style="text-decoration:none">Kembang</a></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>

      <tr>
        <td>15</td>
        <td><a href="#"  style="text-decoration:none">Kombinasi</a></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
       <tr style="background-color: #000; color: #fff; border:none;">
       <td colspan="3" style="border:none;">TOTAL</td>
       <td colspan="2" style="border:none;">Rp. 0</td>
        
      </tr>
     
      
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
