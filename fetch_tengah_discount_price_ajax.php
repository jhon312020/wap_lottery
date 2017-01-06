<?php
require_once('includes/head.php');
///////////////////////////////////////////////////////////////////////////////
$data = $_REQUEST;
//////////////////////////////////////////////////////////////////////////////

switch($data['action']) {
	case 'fetchdiscount': {
		//echo "SELECT * FROM  lottery_game_setting WHERE g_market_name = '".$data['market']."' and g_type = '".$data['gameType']."' and g_name = '".$data['pos']."'"; exit;
		$arrDiscount = mysql_fetch_array(mysql_query("SELECT * FROM  lottery_game_setting WHERE g_market_name = '".$data['market']."' and g_type = '".$data['gameType']."' and g_name = '".$data['pos']."'"));
		$kei = $arrDiscount['g_kei'];
		//echo $kei; exit;
		//$discountPercentage = ltrim(ltrim($arrDiscount['g_kei'],"+"),"-");
		$discountPercentage = $arrDiscount['g_kei'];

		if($discountPercentage < 0){
			$discount = ($data['bet_amount'] * $discountPercentage)/100;
		} else{
			$discount = 0;
		}

		$modDiscount = number_format(abs($discount) , 0 , '.' , ',' );
		/*$discount = ($data['bet_amount']*$discountPercentage)/100;
		$modDiscount = number_format($discount , 0 , '.' , ',' );*/
		$paybleAmount = $data['bet_amount'] -($discount);
		$modPaybleAmount = number_format($paybleAmount , 0 , '.' , ',' );
		echo $discount.'^'. $paybleAmount.'^'.$modDiscount.'^'.$modPaybleAmount;
		
?>

<?php
		break;
	}
	default: {
		
	}
}
?>