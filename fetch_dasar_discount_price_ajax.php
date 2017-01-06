<?php
require_once('includes/head.php');
///////////////////////////////////////////////////////////////////////////////
$data = $_REQUEST;
//////////////////////////////////////////////////////////////////////////////
$betAmount = $data['bet_amount'];
//$position = $data['pos'];
//echo $betAmount; 
//echo  $position; exit;
switch($data['action']) {
	case 'fetchdiscount': {
		//echo "SELECT * FROM lottery_game_setting WHERE g_market_name = '".$data['market']."' and g_type = '".$data['gameType']."' and g_name = '".$data['pos']."'"; exit;
		$arrDiscount = mysql_fetch_array(mysql_query("SELECT * FROM lottery_game_setting WHERE g_market_name = '".$data['market']."' and g_type = '".$data['gameType']."' and g_name = '".$data['pos']."'"));
		$discountPercentage = $arrDiscount['g_kei'];
		if($data['pos'] == 'Genap'){
			if($discountPercentage < 0){
				$discount = ($betAmount * $discountPercentage)/100;
			}
			else{
				$discount = 0;
			}
		}
		else if($data['pos'] == 'KECIL'){
			if($discountPercentage < 0){
				$discount = ($betAmount * $discountPercentage)/100;
			}
			else{
				$discount = 0;
			}
		}
		else{
			$discount = ($betAmount * $discountPercentage)/100;
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