<?php
require_once('includes/head.php');

///////////////////////////////////////////////////
$data = $_REQUEST;
//print_r($data); exit;

///////////////////////////////////////////////////
switch($data['action']) {
	case 'fetchGameType': {
	//print_r($data['number']); exit;	
	$number1 = $data['n1'];
	$number2 = $data['n2'];
	$number3 = $data['n3'];
	$number4 = $data['n4'];
	$market = $data['market'];
	
	if($number1 != '' && $number2 != '' && $number3 != '' && $number4 != '') {
		$playType = "4D";
		$discountByCategory = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$market."' and g_type = '".$playType."'"));
		$discount = $discountByCategory['g_discount']."%";
	}
	elseif($number1 == '' && $number2 != '' && $number3 != '' && $number4 != '') {
		$playType = "3D";
		$discountByCategory = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$market."' and g_type = '".$playType."'"));
		$discount = $discountByCategory['g_discount']."%";
	}
	
	/*elseif($number1 == '' && $number2 == '' && $number3 != '' && $number4 != '') {
		$playType = "2D";
		$discountByCategory = mysql_fetch_array(mysql_query("SELECT * FROM lottery_4d WHERE category = '2D'"));
		$discount = $discountByCategory['discount']."%";
	}*/
	
	elseif($number1 != '' && $number2 != '' && $number3 == '' && $number4 == '') {
		$playType = "2D D";
		//echo "SELECT * FROM lottery_game_setting WHERE g_market_name = '".$market."' and g_type = '".$playType."'"; exit;
		$discountByCategory = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$market."' and g_type = '".$playType."'"));
		$discount = $discountByCategory['g_discount']."%";
	}
	elseif($number1 == '' && $number2 == '' && $number3 != '' && $number4 != '') {
		$playType = "2D B";
		$discountByCategory = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$market."' and g_type = '".$playType."'"));
		$discount = $discountByCategory['g_discount']."%";
	}
	
	elseif($number1 == '' && $number2 != '' && $number3 != '' && $number4 == '') {
		$playType = "2D T";
		$discountByCategory = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$market."' and g_type = '".$playType."'"));
		$discount = $discountByCategory['g_discount']."%";
	}
	else {
		$playType = "N/A";
		$discount = 0;
	}
	
		echo $playType ."^". $discount;
?>

<?php
		break;
	}
?>
<?php 
	case 'fetchdiscountPrice': {
		 //echo $data['gameTpe']; exit; 
		//echo $data['betamount']; exit;
		$amount = $data['betamount'];
		$market = $data['market'];
		if($data['gameTpe'] == "2D D") {
			$category = "2D D";
		} elseif($data['gameTpe'] == "2D T") {
			$category = "2D T";
		} elseif($data['gameTpe'] == "2D B") {
			$category = "2D B";
		} else {
			$category = $data['gameTpe'];
		}
		$arrdiscountPercentage = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_type = '".$category."' and g_market_name = '".$market."'"));
		if($arrdiscountPercentage['g_kei'] == '0') {
		$discountPercentage = $arrdiscountPercentage['g_discount'];
		} else {
			$discountPercentage = $arrdiscountPercentage['g_kei'];
		}
		$discount = ($amount * $discountPercentage)/100;
		$modDiscount = number_format($discount , 0 , '.' , ',' );
		$paybleAmount = $amount - $discount;
		$modPaybleAmount = number_format($paybleAmount , 0 , '.' , ',' );
		echo $discount ."^". $paybleAmount ."^". $modDiscount."^".$modPaybleAmount;
	}
	default: {
		
	}
}
?>