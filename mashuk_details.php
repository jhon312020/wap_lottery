<?php include("includes/head.php");
require_once("includes/check-authentication.php");
$memo_id = $_REQUEST['memo_id'];
//echo "SELECT * FROM lottery_memo WHERE m_id = '".$memo_id."'"; exit;

$arrMemoDetails = mysql_fetch_array(mysql_query("SELECT * FROM lottery_memo WHERE m_id = '".$memo_id."'"));
$memo_parent_id = $arrMemoDetails['m_parent_id'];
//echo $memo_parent_id; exit;
if($memo_parent_id == 0) {
$arrOriginalMemo = mysql_fetch_array(mysql_query("SELECT * FROM lottery_memo WHERE m_id = '".$memo_id."'"));
} else {
$arrOriginalMemo = mysql_fetch_array(mysql_query("SELECT * FROM lottery_memo WHERE m_id = '".$memo_parent_id."'"));
  
}
if($memo_parent_id == 0) {
$resMemoReplyDetails = mysql_query("SELECT * FROM lottery_memo WHERE m_parent_id = '".$memo_id."' ORDER BY m_date_time DESC");
} else {
  $resMemoReplyDetails = mysql_query("SELECT * FROM lottery_memo WHERE m_parent_id = '".$memo_parent_id."' ORDER BY m_date_time DESC");
}


date_default_timezone_set('Asia/Kuala_Lumpur');
$currentDateTime = date('Y-m-d h:i:s');
$arrcurrentDate = explode(" ",$currentDateTime);
$currentDate = $arrcurrentDate['0'];
$currentTime = $arrcurrentDate['1'];
if(isset($_REQUEST['action']) && $_REQUEST['action'] == "delete") {
  $memoId = $_REQUEST['memo_id'];
  $resAllMemoThread = mysql_query("SELECT * FROM lottery_memo WHERE m_parent_id = '".$memoId."'");
  $resMemoDelete = mysql_query("DELETE FROM lottery_memo WHERE m_id = '".$memoId."'");
  while($arrAllMemoThread = mysql_fetch_array($resAllMemoThread)) {
    $mid = $arrAllMemoThread['m_id'];
    $resAllMemoThreadDel = mysql_query("DELETE FROM lottery_memo WHERE m_id = '".$mid."'");
  }
header("Location:memo_mashuk.php?msg=3");
exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include("includes/html_head.php");?>
<script>
function delMemo(mid) {
    var a=confirm("Are you sure,you want to delete this memo?")
      if (a)
      {
  location.href="memo_mashuk.php?memo_id="+ mid +"&action=delete"
      } 
}
</script>
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
     <span class="pull-left" style="color:#fff;">	 
	<a href="balas_memo.php?memo_id=<?php echo $memo_id; ?>"><div class="btn_mini_red">Balas Memo</div></a>
	
	<a href="javascript:void(0)" onclick="javascript:delMemo('<?php echo $memo_id; ?>')"><div class="btn_mini_red">Hapus Memo</div></a>
	
	 </span>	
	<span class=" pull-right" style="color:#fff;"><?php echo date('j F Y',strtotime($currentDate))." ".$currentTime; ?></span>
	<div class="clear"></div>
	</div>
  <div class="col-md-12">
 <p style="color: #121217;">
  <span style="width:120px; float:left; ">SUBJECT </span>
  <span style="width:15px; float:left;">:</span>
  <?php echo $arrOriginalMemo['m_subject']; ?>
 </p>
  <div class="clear"></div>
 <p style="color: #121217;">
   <div style="width:120px; float:left;">PESAN </div>
   <div style="width:15px; float:left;">:</div>
   <div style="float:left;">
   <?php while($arrMemoReplyDetails = mysql_fetch_array($resMemoReplyDetails)) {?>
     <p class="msg_reply_text"><?php echo $arrMemoReplyDetails['m_message'];?></p>
     <?php }?>
   </div>
 </p>
 <div class="clear"></div>
 <p style="color: #121217;">
   <span style="width:120px; float:left;">Original Message </span>
   <span style="width:15px; float:left;">:</span><?php echo $arrOriginalMemo['m_message']; ?>
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