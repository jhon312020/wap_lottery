<?php
require_once('includes/head.php');
///////////////////////////////////////////////////////////////////////////////
$data = $_REQUEST;
//print_r($data); exit;
$betAmount = $data['bet_amount'];
$kei = $data['cKei'];
//////////////////////////////////////////////////////////////////////////////

switch($data['action']) {
	case 'fetchdiscount': {

		if($kei < 0){
			$discount = ($betAmount * $kei)/100;
		} else{
			$discount = 0;
		}

		$modDiscount = number_format(abs($discount) , 0 , '.' , ',' );

		/*$discount = ($data['bet_amount']*$kei)/100;
		$modDiscount = number_format($discount , 0 , '.' , ',' );*/
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