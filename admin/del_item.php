<?php
require ('./../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
$sql = "delete from `ls_cart` where `id`='".$_GET['id']."';";
mysql_query ($sql);
?>