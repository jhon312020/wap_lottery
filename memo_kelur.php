<?php include("includes/head.php");
	require_once("includes/check-authentication.php");
	//echo "SELECT * FROM lottery_memo WHERE m_from_uid = '".$_SESSION['lottery']['memberid']."' and m_parent_id = '0'"; exit;
	$resMessageOutbox = mysql_query("SELECT * FROM lottery_memo WHERE m_from_uid = '".$_SESSION['lottery']['memberid']."'");
?>
<!DOCTYPE html>
<html lang="en">
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
	<div class="firstMsg alert alert-success" style="display:none;"></div>
	<div class="replyMsg alert alert-success" style="display:none;"></div>
	<div style="padding:3px 0px 10px; 0px;">
	<?php include('includes/memo_head.php'); ?>
	</div>
	<table width='100%' cellpadding='0' cellspacing='5' id='tabeldata' class='table table-bordered table-hover center'>
		<thead id='head1'>
			<tr class='bg-info'>
				<th>PENGIRIM</th>
				<th>SUBJECT</th>
				<th>TANGGAL</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				while($arrMessageOutbox = mysql_fetch_array($resMessageOutbox)) {
				$arrUsername = mysql_fetch_array(mysql_query("SELECT * FROM lottery_member_registration WHERE member_id = '".$arrMessageOutbox['m_from_uid']."'"));
				if($arrMessageOutbox['m_parent_id']!=0) {
					$countReplyByUser = mysql_fetch_array(mysql_query("SELECT count(*) FROM lottery_memo WHERE m_from_uid = '".$_SESSION['lottery']['memberid']."' and m_parent_id = '".$arrMessageOutbox['m_parent_id']."'"));
				}
			?>
			<tr>
				<td><?php echo $arrUsername['member_username']; ?></td>
				<td><a href="kelur_details.php?memo_id=<?php echo $arrMessageOutbox['m_id']; ?>&m_parent_id=<?php echo $arrMessageOutbox['m_parent_id'];?>"><?php if(isset($countReplyByUser)) {?><?php for($i=0;$i<$countReplyByUser['0'];$i++) {?>RE : <?php }?><?php }?><?php echo $arrMessageOutbox['m_subject']; ?></a></td>
				<td><?php echo $arrMessageOutbox['m_date_time']; ?></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
</div>
	<hr/>
	<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == '2') {?>
		<script type="text/javascript">
			$(".replyMsg").show();
			$(".replyMsg").html("You have successfully sent your reply.");
		</script>
	<?php }?>
	<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == '1') {?>
		<script type="text/javascript">
			$(".firstMsg").show();
			$(".firstMsg").html("Your message has been successfully sent to admin.");
		</script>
	<?php }?>
	<?php include("includes/footer.php");?>
</body>
</html>