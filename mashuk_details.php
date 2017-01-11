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
<?php include("includes/navigation.php");?>
	<div class="container-fluid"> 
		<a href="my-account.php" class="btn btn-danger btn-xs" style="margin-bottom: 5px;">HOME</a> 
		<br /><br />
		<div style="padding:3px 0px 10px; 0px;">
			<?php include('includes/memo_head.php'); ?>
		</div>
		<br /><br />
		<div style="padding:3px 0px 10px; 0px;">
			<a href="balas_memo.php?memo_id=<?php echo $memo_id; ?>" class="btn btn-danger btn-xs" style="margin-bottom: 5px;">BALAS MEMO</a> 
			<a href="javascript:;" class="btn btn-danger btn-xs" onclick="javascript:delMemo('<?php echo $memo_id; ?>')" style="margin-bottom: 5px;">HAPUS MEMO</a> 
		</div>
		<div class="form-group">
			<label for="inputEmail3" class="col-sm-2 control-label">Tanggal:</label>
			<div class="col-sm-11">
				<?php echo date('j F Y',strtotime($currentDate))." ".$currentTime;?>
			</div>
		</div>
		<div class="form-group">
			<label for="inputEmail3" class="col-sm-2 control-label">Subject:</label>
			<div class="col-sm-11">
				<?php echo $arrOriginalMemo['m_subject']; ?>
			</div>
		</div>
		<div class="form-group">
			<label for="inputEmail3" class="col-sm-2 control-label">PESAN:</label>
			<div class="col-sm-11">
				<?php while($arrMemoReplyDetails = mysql_fetch_array($resMemoReplyDetails)) {?>
			 <p class="msg_reply_text"><?php echo $arrMemoReplyDetails['m_message'];?></p>
			 <?php }?>
			</div>
		</div>
		<div class="form-group">
			<label for="inputEmail3" class="col-sm-2 control-label">Original Message:</label>
			<div class="col-sm-11">
				<?php echo $arrOriginalMemo['m_message']; ?>
			</div>
		</div>
	</div>
	<hr/>
	<?php include("includes/footer.php");?>
</body>
</html>