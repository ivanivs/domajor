<?php
require ('./../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
$sql = "UPDATE ls_orders set ship_date = '".$_GET['ship_date']."' where id = '".$_GET['id']."';";
mysql_query ($sql);
print "<span style=\"color:red;\"><b>Дату доставки збережено</span>";
?>