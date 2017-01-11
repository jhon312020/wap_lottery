<?php require_once("includes/head.php");
	require_once("includes/check-authentication.php");
	$market_name = $_REQUEST['market'];
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
		<a href="games.php" class="btn btn-danger btn-xs" style="margin-bottom: 5px;">GAMES</a> 
		<br /><br />
			<a href='4d.php?market=<?php echo $market_name; ?>' class='btn btn-default form-control' style='margin-bottom: 5px;'>4D / 3D / 2D</a>
			<a href='2d_posisi.php?market=<?php echo $market_name; ?>' class='btn btn-default form-control' style='margin-bottom: 5px;'>2D Posisi</a>
			<a href='colok_bebas.php?market=<?php echo $market_name; ?>' class='btn btn-default form-control' style='margin-bottom: 5px;'>Colok Bebas</a>
			<a href='macau.php?market=<?php echo $market_name; ?>' class='btn btn-default form-control' style='margin-bottom: 5px;'>Macau</a>
			<a href='colok_naga.php?market=<?php echo $market_name; ?>' class='btn btn-default form-control' style='margin-bottom: 5px;'>Colok Naga</a>
			<a href='colok_jitu.php?market=<?php echo $market_name; ?>' class='btn btn-default form-control' style='margin-bottom: 5px;'>Colok Jitu</a>
			<a href='tengah.php?market=<?php echo $market_name; ?>' class='btn btn-default form-control' style='margin-bottom: 5px;'>Tengah</a>
			<a href='dasar.php?market=<?php echo $market_name; ?>' class='btn btn-default form-control' style='margin-bottom: 5px;'>Dasar</a>
			<a href='50-50.php?market=<?php echo $market_name; ?>' class='btn btn-default form-control' style='margin-bottom: 5px;'>50-50</a>
			<a href='shio.php?market=<?php echo $market_name; ?>' class='btn btn-default form-control' style='margin-bottom: 5px;'>Shio</a>
			<a href='silang.php?market=<?php echo $market_name; ?>' class='btn btn-default form-control' style='margin-bottom: 5px;'>Silang</a>
			<a href='kembang.php?market=<?php echo $market_name; ?>' class='btn btn-default form-control' style='margin-bottom: 5px;'>Kembang</a>
			<a href='kombinasi.php?market=<?php echo $market_name; ?>' class='btn btn-default form-control' style='margin-bottom: 5px;'>Kombinasi</a>
			</div>		<br /><br />	
		<hr/>			
		<?php include("includes/footer.php");?>
	</body>
</html>