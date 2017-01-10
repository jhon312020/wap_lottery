<?php include("includes/head.php");
	require_once("includes/check-authentication.php");
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == "delete") {
		$memoId = $_REQUEST['memo_id'];
		//echo "SELECT * FROM lottery_memo WHERE m_parent_id = '".$memoId."'"; exit;
		$resAllMemoThread = mysql_query("SELECT * FROM lottery_memo WHERE m_parent_id = '".$memoId."'");
		$resMemoDelete = mysql_query("DELETE FROM lottery_memo WHERE m_id = '".$memoId."'");
		while($arrAllMemoThread = mysql_fetch_array($resAllMemoThread)) {
			$mid = $arrAllMemoThread['m_id'];
			$resAllMemoThreadDel = mysql_query("DELETE FROM lottery_memo WHERE m_id = '".$mid."'");
		}
		header("Location:memo_mashuk.php?msg=3");
		exit();
	}
	//echo "SELECT m_subject FROM lottery_memo WHERE m_to_id = '".$_SESSION['lottery']['memberid']."' GROUP BY m_subject ORDER BY m_date_time DESC"; exit;
	$resReplyMemo = mysql_query("SELECT m_id,m_from_uid,m_parent_id, m_subject, MAX(m_date_time) AS m_date_time FROM lottery_memo 
	WHERE m_to_id = '".$_SESSION['lottery']['memberid']."' 
	GROUP BY m_id, m_from_uid,m_parent_id, m_subject ORDER BY m_date_time DESC");
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
	<a href="user.php" class="btn btn-danger btn-xs" style="margin-bottom: 5px;">HOME</a> 
	<br /><br />
	<div style="padding:3px 0px 10px; 0px;">
		<?php include('includes/memo_head.php'); ?>
	</div>
	<table width='100%' cellpadding='0' cellspacing='5' id='tabeldata' class='table table-bordered table-hover center'>
		<thead id='head1'>
			<tr class='bg-info'>
				<th></th>
				<th>DARI</th>
				<th>SUBJECT</th>
				<th>TANGGAL</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			while($arrReplyMemo = mysql_fetch_array($resReplyMemo)) {
			?>
			<tr>
				<td><a href="javascript:void(0)" onclick="javascript:delMemo('<?php echo $arrReplyMemo['m_parent_id']; ?>')"><img src="images/delete.png" alt="DELETE" title="DELETE"></a></td>
				<td><?php echo $arrReplyMemo['m_from_uid'];?></td>
				<td><a href="mashuk_details.php?memo_id=<?php echo $arrReplyMemo['m_id']; ?>"><?php //for($i=0;$i<$countReply['0'];$i++) {?><!--RE : --><?php //}?><?php echo $arrReplyMemo['m_subject'];?></a></td>
				<td><?php echo $arrReplyMemo['m_date_time']; ?></td>
			</tr>
		<?php }?>
		</tbody>
	</table>
</div>
	<hr/>
	</div>
	<hr/>
	<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == '3') {?>
		<script type="text/javascript">
			$(".delMsg").show();
			$(".delMsg").html("Memo successfully deleted");
		</script>
	<?php }?>
	<?php include("includes/footer.php");?>
</body>
</html>