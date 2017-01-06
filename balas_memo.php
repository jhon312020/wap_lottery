<?php include("includes/head.php");
require_once("includes/check-authentication.php");
$memo_id = $_REQUEST['memo_id'];

$arrmemoDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_memo WHERE m_id = '".$memo_id."'"));
$memoParentId = $arrmemoDetails['m_parent_id'];
if($memoParentId == 0) {
  $memoParentId = $_REQUEST['memo_id'];
} else {
  $memoParentId = $arrmemoDetails['m_parent_id'];
}
if($memoParentId == 0) {
  $originalMsg = mysql_fetch_array(mysql_query("SELECT * FROM lottery_memo WHERE m_id = '".$memo_id."'"));
} else {
   $originalMsg = mysql_fetch_array(mysql_query("SELECT * FROM lottery_memo WHERE m_id = '".$memoParentId."'"));
}
//echo "SELECT * FROM lottery_memo WHERE m_parent_id = '".$memo_id."' ORDER BY m_date_time DESC"; exit;
if($memoParentId == 0) {
  $resMemoReply = mysql_query("SELECT * FROM lottery_memo WHERE m_parent_id = '".$memo_id."' ORDER BY m_date_time DESC");
} else {
  $resMemoReply = mysql_query("SELECT * FROM lottery_memo WHERE m_parent_id = '".$memoParentId."' ORDER BY m_date_time DESC");
}
$countReply = mysql_fetch_array(mysql_query("SELECT count(*) FROM `lottery_memo` WHERE `m_from_uid`= 'Admin' and `m_parent_id`= '".$memo_id."'"));
date_default_timezone_set('Asia/Kuala_Lumpur');
$currentDate = date('Y-m-d h:i:s');
if(isset($_REQUEST['key']) && $_REQUEST['key'] == "balasMemo") {
  $m_parent_id = $_REQUEST['m_parent_id'];
  $from_u_id = $_REQUEST['from_u_id'];
  $m_subject = $_REQUEST['subject'];
  $comment = $_REQUEST['comment'];
$resReply = mysql_query("INSERT INTO lottery_memo(m_parent_id, m_from_uid, m_to_id, m_subject, m_message, m_date_time)VALUES('".$m_parent_id."','".$from_u_id."','Admin','".$m_subject."','".$comment."','".$currentDate."')");
header("Location:memo_kelur.php?msg=2");
exit();
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
    <div class="col-md-12 mrg-top-20 game-page-area">
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
    
    <div class="col-md-12  rdc-padding">
	<div  class="even-header">
     <p  class="pull-left" style="color:#fff;">BALAS MEMO</p>	
	<p class=" pull-right"  style="color:#fff;"><?php echo date('j F Y H:i:s',strtotime($currentDate));?></p>
	<div class="clear"></div>
	</div>
	<div class="col-md-12  mrg-top-20  rdc-padding  ">
	 <form class="form-horizontal" name="frm" id="replyForm" role="form">
   <input type="hidden" name="m_parent_id" value="<?php echo $memoParentId; ?>">
   <input type="hidden" name="from_u_id" value="<?php echo $_SESSION['lottery']['memberid'];?>">
   <input type="hidden" name="key" value="balasMemo">
    <div class="form-group">
      <label class="control-label col-sm-2" for="email">SUBJEK :</label>
      <div class="col-sm-5">
     <input class="form-control  rdc-padding" type="text" name="subject" readonly="readonly" value="<?php for($i=0;$i<$countReply['0'];$i++) {?>RE : <?php }?><?php echo $originalMsg['m_subject']; ?>">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="pwd">BALASAN :</label>
      <div class="col-sm-7">          
      <textarea class="form-control" rows="3" name="comment" id="comment"></textarea>
      </div>
    </div>
	
	<div style="border-bottom: 1px dotted #1B1C21; margin:10px 0px; "></div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="pwd">PESAN :</label>
      <div class="col-sm-7"> 
      <?php while($arrMemoReply = mysql_fetch_array($resMemoReply)) {?>         
        <p class="msg_reply_text"><?php echo $arrMemoReply['m_message']; ?></p>
       <?php }?>

        <p class="msg_reply_text" style="margin-top:30px;">Original Message</p>
        <p class="msg_reply_text"><?php echo $originalMsg['m_message']; ?></p>
      </div>
  </div>
    <div style="border-bottom: 1px dotted #1B1C21; margin:10px 0px; "></div>
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <input type="submit" name="submit" value="Submit" class="btn btn-default  game-more-btn">
      </div>
    </div>
  </form>
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
      <?php require_once("includes/footer.php");?>

      <!--end-of col-md-12--> 
    
  </div>
  <!--end-of container--> 
</div>
<!--end-of container-fluid main-body-area--> 
</body>
</html>