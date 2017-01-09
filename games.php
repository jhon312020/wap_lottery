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
		<br /><br />
		<?php while($arrMarket = mysql_fetch_array($resMarket)) { ?>
			<?php if($arrMarket['market_status'] == 1) { ?>
			<a href='top_game_category.php?market=<?php echo $arrMarket['market_name'];?>' class='btn btn-default form-control' style='margin-bottom: 5px;'><?php echo $arrMarket['market_name'];?><small> (<font color='#009900'>Online</font>)</small> </a>
		<?php } else { ?>
			<a href='games-list.php?market' class='btn btn-default form-control' style='margin-bottom: 5px;'><?php echo $arrMarket['market_name'];?><small> (<font color='#990000'>Offline</font>)</small> </a>
		<?php } } ?>
			</div>		<br /><br />	
		<hr/>			
		<?php include("includes/footer.php");?>
	</body>
</html>
