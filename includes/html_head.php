<?php $siteTitle = mysql_fetch_array(mysql_query("SELECT * FROM lottery_company_setting WHERE cs_id = '1'")); ?>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $siteTitle['cs_c_name']; ?></title>

    <!-- Bootstrap -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/validate.css" rel="stylesheet" type="text/css">
    
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
<script type="text/javascript" src="../js/jquery.validate.min.js"></script> 
<script type="text/javascript" src="../js/jquery.masknumber.js"></script> 
<script type="text/javascript" src="../js/jquery.number.js"></script>
<script type="text/javascript" src="../admin/plugins/tables/dataTables/jquery.dataTables.min.js"></script>


<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="../js/bootstrap.min.js"></script>
 <script type="text/javascript">
    function autotab(current,to){
        if (current.getAttribute && current.value.length==current.getAttribute("maxlength")) {
        to.focus() 
        }
    }
    </script>
