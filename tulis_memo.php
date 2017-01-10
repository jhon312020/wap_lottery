<?php include("includes/head.php");
	require_once("includes/check-authentication.php");
	date_default_timezone_set('Asia/Kuala_Lumpur');
	$currentDateTime = date('Y-m-d h:i:s');
	$arrcurrentDate = explode(" ",$currentDateTime);
	$currentDate = $arrcurrentDate['0'];
	$currentTime = $arrcurrentDate['1'];
	$resMemoSubject = mysql_query("SELECT * FROM lottery_memo_subject ORDER BY ms_id ASC");
	if(isset($_REQUEST['key']) && $_REQUEST['key'] == "sendMemo") {
		$from_userid = $_SESSION['lottery']['memberid'];
		$subject = $_REQUEST['subject'];
		$message = $_REQUEST['message'];
		$parentId = $_REQUEST['parent_id'];
		$resTulisMemo = mysql_query("INSERT INTO lottery_memo(m_parent_id, m_from_uid, m_to_id, m_subject, m_message, m_date_time) VALUES('".$parentId."','".$from_userid."','Admin','".$subject."','".$message."','".$currentDateTime."')");
		header("Location:memo_kelur.php?msg=1");
		exit();
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include("includes/html_head.php");?>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#memo-form').submit(function(){
					if(!$('#message').val().trim()) {
						alert('Please fill this field');
						$('#message').focus();
						return false
					}
				})
			});
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
<form role="form" method="post" action="" enctype="multipart/form-data" class="form-horizontal" id="memo-form">
<input type="hidden" name="key" value="sendMemo">
<input type="hidden" name="parent_id" value="0">
	<div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">SUBJEK: </label>
    <div class="col-sm-10">
      <select  class= 'form-control' name='subject' id='subject' >
			<?php while($arrMemoSubject = mysql_fetch_array($resMemoSubject)) {?>
				<option value="<?php echo $arrMemoSubject['ms_name']; ?>"><?php echo $arrMemoSubject['ms_name']; ?></option>
			<?php }?>
	  </select>
    </div>
</div>
	<div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">PESAN:</label>
    <div class="col-sm-10">
      <textarea class="form-control" rows="3" name="message" id="message" maxlength="250"></textarea>
    </div>
  	</div>
	<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default" name="submit" value="Submit">SEND MEMO</button><br>
    </div>
  	</div>
</form>
</div>
	</body>
</html>