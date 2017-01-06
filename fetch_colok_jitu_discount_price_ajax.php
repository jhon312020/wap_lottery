<?php
require_once('includes/head.php');
///////////////////////////////////////////////////////////////////////////////
$data = $_REQUEST;
//////////////////////////////////////////////////////////////////////////////

switch($data['action']) {
	case 'fetchdiscount': {
		$arrDiscount = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$data['market']."' and g_type = '".$data['gameType']."'"));
		$discountPercentage = $arrDiscount['g_discount'];
		$discount = ($data['bet_amount']*$discountPercentage)/100;
		$modDiscount = number_format($discount , 0 , '.' , ',' );
		$paybleAmount = $data['bet_amount'] - $discount;
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