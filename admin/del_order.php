<?php
require ('./../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
$sql = "update `ls_orders` set `delete` = '1' where `id`='".$_GET['id']."';";
if (mysql_query ($sql))
{
	print '<span style="color:red; font-size:9px;">Видалений</span>';
} else {
	print '<span style="color:red; font-size:9px;">Помилка</span>';
}
?>