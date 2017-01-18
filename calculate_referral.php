<?php
$mem_id = $_SESSION['lottery']['memberid'];
$resReferrer = mysql_query("SELECT * FROM lottery_member_registration WHERE member_id = '".$mem_id."'");
$countReferrer = mysql_num_rows($resReferrer);
$arrReferrer = mysql_fetch_array($resReferrer);
$refererName = $arrReferrer['member_referrals'];
if(isset($refererName)) {
	$arrMemberInfo = mysql_fetch_array(mysql_query("SELECT * FROM lottery_member_registration WHERE member_username = '".$refererName."'"));
	$uid = $arrMemberInfo['member_id'];
}
/////////////////////////// Fetch Percentage //////////////////////////////////////////
$arrPercentage = mysql_fetch_array(mysql_query("SELECT * FROM lottery_ref_percentage WHERE lrp_id = '1'"));
$percentage = $arrPercentage['lrp_percentage'];

/////////////////////////// Fetch Percentage //////////////////////////////////////////

//////////////////////////////// CHECK PURCHASE /////////////////////////////////

if(isset($refererName) && $refererName!=''){
	$resPurchase = mysql_query("SELECT * FROM lottery_purchase WHERE p_member_id = '".$mem_id."' and p_status = '1'");
	while($arrPurchase = mysql_fetch_array($resPurchase)) {
		$purchaseID = $arrPurchase['p_id'];
		$betAmount = $arrPurchase['p_bet_amount'];
		$referalAmount = ($betAmount * $percentage)/100;
		$resReferralAmount = mysql_query("INSERT INTO lottery_referral_amount(r_amount_id, r_member_id, r_purchase_id, r_amount,r_date) VALUES('','".$uid."','".$purchaseID."','".$referalAmount."','".date('Y-m-d')."')");
	}
}
?>