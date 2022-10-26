<?php
require ('../../config.php');
require ('../../include/functions.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
$sql = "UPDATE `ls_calculator` set `priceInUa` = '".mysql_real_escape_string($_POST['price'])."' where `id` = '".intval($_POST['id'])."';";
if (mysql_query($sql))
{
    print $_POST['price'];
} else {
    print 'error';
}
?>