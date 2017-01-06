<?php

require_once('includes/head.php');

///////////////////////////////////////////////////////////////////////////////

$data = $_REQUEST;

//print_r($data); exit;

//////////////////////////////////////////////////////////////////////////////

$betAmount = $data['bet_amount'];

switch($data['action']) {

	case 'fetchdiscount': {

		$arrDiscount = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$data['market']."' and g_type = '".$data['gameType']."' and g_name = '".$data['pos']."' and g_position = '".$data['cPos']."'"));

		$discountPercentage = $arrDiscount['g_kei'];

		if($discountPercentage < 0){
			$discount = ($betAmount * $discountPercentage)/100;
		} else{
			$discount = 0;
		}

		$modDiscount = number_format(abs($discount) , 0 , '.' , ',' );

		$paybleAmount = $betAmount - ($discount);

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