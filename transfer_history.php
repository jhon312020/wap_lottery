<?php require_once("includes/head.php");
require_once("includes/check-authentication.php");
$mem_id = $_SESSION['lottery']['memberid'];
///////////////////// D  E  P  O  S  I   T    H  I  S  T  O  R  Y     BY    LOGGED    IN     PLAYER /////////////////////
$resTransferHistory = mysql_query("SELECT * FROM lottery_amount_transfer WHERE t_member_id = '".$mem_id."' ORDER BY  t_datetime DESC limit 0,5");

///////////////////// D  E  P  O  S  I   T    H  I  S  T  O  R  Y     BY    LOGGED    IN     PLAYER  /////////////////////
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once("includes/html_head.php");?>
</head> 
<body> 
<?php include("includes/navigation.php");?>
<div class="container-fluid">
	<a href="my-account.php" class="btn btn-danger btn-xs" style="margin-bottom: 5px;">HOME</a>
	<br><br>
	TRANSAKSI 5 HARI TERAKHIR
	<br><br>
		<table width='100%' cellpadding='0' cellspacing='5' id='tabeldata' class='table table-bordered table-hover center'>
			<thead id='head1'>
				<tr class='bg-info'>
					<th>No.</th>
					<th>Transfer Type</th>
					<th>Transfer From</th>
					<th>Transfer To</th>
					<th>Transfer Amount</th>
					<th>Transfer Date & Time</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$i = 1;
			while($arrTransferHistory = mysql_fetch_array($resTransferHistory)) {
			?>
				<tr>
					<td><?php echo $i;?></td>
					<td><?php if($arrTransferHistory['t_type'] == '1') {?>Transfer Out<?php }?>
					<?php if($arrTransferHistory['t_type'] == '2') {?>Transfer In<?php }?></td>
					<td><?php echo $arrTransferHistory['t_from_game']; ?></td>
					<td><?php echo $arrTransferHistory['t_to_game'];?></td>
					<td><?php echo number_format($arrTransferHistory['t_amount']);?></td>
					<td><?php echo $arrTransferHistory['t_datetime'];?></td>
					<td>
					<?php if($arrTransferHistory['t_status'] == 0) {?>Waiting for Approval<?php }?>
					<?php if($arrTransferHistory['t_status'] == 1) {?>Approved<?php }?>
					<?php if($arrTransferHistory['t_status'] == 2) {?>Rejected<?php }?>
					</td>
				</tr>
				<?php $i++;}?>
			</tbody>
		</table>
</div> 	
<br><br>
<hr/>
<?php include("includes/footer.php");?>
</body> 
</html>