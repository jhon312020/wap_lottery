<?php

//Allow cross site compatibility

header('Access-Control-Allow-Origin: *');

require_once('includes/config.php');

switch($_REQUEST['type']) {

	case 'username': {
		if( mysql_num_rows(mysql_query("SELECT member_id FROM lottery_member_registration WHERE member_username = '".$_REQUEST['username']."'")) == 0) {
			echo "true";
		}
		else {
			echo "false";
		}
		break;
	}

	case 'acc_number': {
		if( mysql_num_rows(mysql_query("SELECT member_id FROM lottery_member_registration WHERE member_account_no = '".$_REQUEST['acc_number']."'")) == 0) {
			echo "true";
		}
		else {
			echo "false";
		}
		break;
	}

	default: {

		echo "false";

	}

}

?>