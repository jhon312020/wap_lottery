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
<?php include("includes/navigation.php");?>
	<div class="container-fluid"> 
		<a href="my-account.php" class="btn btn-danger btn-xs" style="margin-bottom: 5px;">HOME</a> 
		<br /><br />
		<div style="padding:3px 0px 10px; 0px;">
			<?php include('includes/memo_head.php'); ?>
		</div>
		<br /><br />
		<form class="form-horizontal" name="frm" id="replyForm" role="form">
			<input type="hidden" name="m_parent_id" value="<?php echo $memoParentId; ?>">
			<input type="hidden" name="from_u_id" value="<?php echo $_SESSION['lottery']['memberid'];?>">
			<input type="hidden" name="key" value="balasMemo">
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">Tanggal:</label>
				<div class="col-sm-10">
					<?php echo date('j F Y',strtotime($currentDate))." ".$currentTime;?>
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">SUBJEK:</label>
				<div class="col-sm-10">
					<input class="form-control  rdc-padding" type="text" name="subject" readonly="readonly" value="<?php for($i=0;$i<$countReply['0'];$i++) {?>RE : <?php }?><?php echo $originalMsg['m_subject']; ?>">
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">BALASAN:</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="3" name="comment" id="comment" required></textarea>
				</div>
			</div>
			<div style="border-bottom: 1px dotted #1B1C21; margin:10px 0px; "></div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="pwd">PESAN :</label>
      <div class="col-sm-7"> 
      <?php while($arrMemoReply = mysql_fetch_array($resMemoReply)) {?>         
        <p style="border-bottom: dotted 2px #4A4848;" class="msg_reply_text"><?php echo $arrMemoReply['m_message']; ?></p>
       <?php }?>

        <p style="border-bottom: dotted 2px #4A4848;" class="msg_reply_text" style="margin-top:30px;">Original Message</p>
        <p style="border-bottom: dotted 2px #4A4848;" class="msg_reply_text"><?php echo $originalMsg['m_message']; ?></p>
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
	<hr/>
	<?php include("includes/footer.php");?>
</body>
</html>