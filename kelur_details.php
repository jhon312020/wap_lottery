<?php include("includes/head.php");
require_once("includes/check-authentication.php");
date_default_timezone_set('Asia/Kuala_Lumpur');
$currentDateTime = date('Y-m-d h:i:s');
$arrcurrentDate = explode(" ",$currentDateTime);
$currentDate = $arrcurrentDate['0'];
$currentTime = $arrcurrentDate['1'];
$memo_id = $_REQUEST['memo_id'];
$m_parent_id = $_REQUEST['m_parent_id'];


if($m_parent_id!=0) {
$arrOriginalMessage = mysql_fetch_array(mysql_query("SELECT * FROM lottery_memo WHERE m_id = '".$m_parent_id."'"));
} else {
$arrOriginalMessage = mysql_fetch_array(mysql_query("SELECT * FROM lottery_memo WHERE m_id = '".$memo_id."'"));
}
if($m_parent_id!=0) {
$resMemoReply = mysql_query("SELECT * FROM lottery_memo WHERE m_parent_id = '".$m_parent_id."' ORDER BY m_date_time DESC");
} else {
$resMemoReply = mysql_query("SELECT * FROM lottery_memo WHERE m_parent_id = '".$memo_id."' ORDER BY m_date_time DESC");

}

?>
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
    <div class="col-md-12 mrg-top-20 game-page-area" >
  <?php require_once("includes/left_panel_game.php");?>
    <!--end-of col-md-3--> 
    <div class="col-md-9 rdc-padding">
    <div class="information-page-area">
     <!--end-of header--> 
    <div  class="clear"></div>
    <div  class="col-md-12 mrg-top-20">
    <div class="game-body" >
    <h1>M E M O</h1>
    
    <?php include("includes/memo_head.php");?>
    <!--end-of col-md-12--> 
	
	<div class="col-md-12 rdc-padding">
	<div class="even-header">
     <!--<p class="pull-left" style="color:#fff;">TULIS MEMO</p>-->
	<p class=" pull-right" style="color:#fff;"><?php echo date('j F Y',strtotime($currentDate))." ".$currentTime;?></p>
	<div class="clear"></div>
	</div>
	<div class="col-md-12">
 <p style="color: #121217;">
  <span style="width:120px; float:left; ">SUBJECT	</span>
  <span style="width:15px; float:left;">:</span>
  <?php echo $arrOriginalMessage['m_subject']; ?>
 </p>
  <div class="clear"></div>
 <p style="color: #121217;">
   <div style="width:120px; float:left;">PESAN </div>
   <div style="width:15px; float:left;">:</div>
   <div style="float:left;">
   <?php while($arrMemoReply = mysql_fetch_array($resMemoReply)) {?>
     <p class="msg_reply_text"><?php echo $arrMemoReply['m_message']; ?></p>
   <?php }?>  
   </div>
 </p>
 <div class="clear"></div>
 <p style="color: #121217;">
   <span style="width:120px; float:left; ">Original Message </span>
   <span style="width:15px; float:left;">:</span><?php echo $arrOriginalMessage['m_message']; ?>
</p>

 </div>
	
	</div>
     <!--end-of col-md-12--> 
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