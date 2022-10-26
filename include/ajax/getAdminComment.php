<?php
require ('../../config.php');
require ('../../include/functions.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
$infoAdminComment = mysql_fetch_array(mysql_query ("SELECT * FROM `ls_calculator` where `id` = '".intval($_POST['id'])."';"), MYSQL_ASSOC);
print $infoAdminComment['adminComment'];
?>