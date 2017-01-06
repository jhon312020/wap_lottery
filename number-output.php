<?php require_once("includes/head.php");
	require_once("includes/check-authentication.php");
	$resMarket = mysql_query("SELECT * FROM lottery_market ORDER BY market_id ASC");
	$market = $_REQUEST['market'];
	if(isset($market) && $market!='') {
		$resWinNumber = mysql_query("SELECT * FROM lottery_win_number WHERE wn_market = '".$market."' ORDER BY wn_date DESC");
	}
	if(isset($market) && $market!='') {
		$countWin = mysql_num_rows(mysql_query("SELECT * FROM lottery_win_number WHERE wn_market = '".$market."'"));
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once("includes/html_head.php");?>
	</head>
	<body>
		<?php require_once("includes/navigation.php");?>
		<div class="container-fluid">
		<a href="my-account.php" class="btn btn-danger btn-xs" style="margin-bottom: 5px;">HOME</a> 
		---
		<a href="data_number_output.php" class="btn btn-danger btn-xs" style="margin-bottom: 5px;">OUTPUT</a> 
		<br /><br />
		Market: <?php echo $_REQUEST['market']; ?>
		<table width='100%' cellpadding='0' cellspacing='5' id='tabeldata' class='table table-bordered table-hover center'>
			<thead id='head1'>
				<tr class='bg-info'>
					<th>Date&nbsp;</th>
					<th>Day&nbsp;</th>
					<th>Period&nbsp;</th>
					<th>Number&nbsp;</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if($countWin == 0) {?>
					<tr><td colspan="4">No result found.</td></tr>
				<?php } else {?>
				<?php
						$i = 1; 
						while ($arrWinNumber = mysql_fetch_array($resWinNumber)) {?>
						<tr>
							<td><?php echo Date('F d, Y', strtotime($arrWinNumber['wn_date'])); ?></td>
							<td><?php echo $arrWinNumber['wn_day']; ?></td>
							<td><?php echo $arrWinNumber['wn_period']; ?></td>
							<td><?php echo $arrWinNumber['wn_number']; ?></td>
						</tr>
				<?php $i++;}}?>
				</tbody>
			</table>
		<br/>
		<hr>
		<?php include("includes/footer.php");?>
	</body>
</html>
