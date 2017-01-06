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
	<a href="tulis_memo.php">
	<div class="btn btn-primary btn-xs" style="border:1px solid #000000">Write MEMO</div>
	</a>
	</div>
	<table width='100%' cellpadding='0' cellspacing='5' id='tabeldata' class='table table-bordered table-hover center'>
		<thead id='head1'>
			<tr class='bg-info'>
				<th width='30%'>Date</th>
				<th>Title</th>
			</tr>
		</thead>
		<tbody>
			<?php while($arrMessageOutbox = mysql_fetch_array($resMessageOutbox)) {
			$arrUsername = mysql_fetch_array(mysql_query("SELECT * FROM lottery_member_registration WHERE member_id = '".$arrMessageOutbox['m_from_uid']."'"));
			if($arrMessageOutbox['m_parent_id']!=0) {
			$countReplyByUser = mysql_fetch_array(mysql_query("SELECT count(*) FROM lottery_memo WHERE m_from_uid = '".$_SESSION['lottery']['memberid']."' and m_parent_id = '".$arrMessageOutbox['m_parent_id']."'"));
			}
		?>
			<tr>
				<td><?php echo Date('l', strtotime($arrMessageOutbox['m_date_time'])).' - '.Date('F d, Y H:i:s', strtotime($arrMessageOutbox['m_date_time'])); ?></td>
				<td><a href='user.php?xpage=memo&go=read&mid=134197'><?php echo $arrMessageOutbox['m_subject']; ?></a></td>
			</tr>
			<?php } ?>
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