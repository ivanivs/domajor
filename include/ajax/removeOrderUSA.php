<?php
require ('../../config.php');
require ('../../include/functions.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query("DELETE FROM `ls_calculator` where `id` = '".intval($_POST['id'])."';");
?>