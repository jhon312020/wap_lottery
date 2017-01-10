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
<?php include("includes/navigation.php");?>
	<div class="container-fluid"> 
		<a href="user.php" class="btn btn-danger btn-xs" style="margin-bottom: 5px;">HOME</a> 
		<br /><br />
		<div style="padding:3px 0px 10px; 0px;">
			<?php include('includes/memo_head.php'); ?>
		</div>
		<br /><br />
		<div class="form-group">
			<label for="inputEmail3" class="col-sm-2 control-label">Tanggal:</label>
			<div class="col-sm-11">
				<?php echo date('j F Y',strtotime($currentDate))." ".$currentTime;?>
			</div>
		</div>
		<div class="form-group">
			<label for="inputEmail3" class="col-sm-2 control-label">Subject:</label>
			<div class="col-sm-11">
				<?php echo $arrOriginalMessage['m_subject']; ?>
			</div>
		</div>
		<div class="form-group">
			<label for="inputEmail3" class="col-sm-2 control-label">PESAN:</label>
			<div class="col-sm-11">
				<?php while($arrMemoReply = mysql_fetch_array($resMemoReply)) {?>
				 <p class="msg_reply_text"><?php echo $arrMemoReply['m_message']; ?></p>
			 <?php }?>  
			</div>
		</div>
		<div class="form-group">
			<label for="inputEmail3" class="col-sm-2 control-label">Original Message:</label>
			<div class="col-sm-11">
				<?php echo $arrOriginalMessage['m_message']; ?>
			</div>
		</div>
	</div>
</body>
</html>