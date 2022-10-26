<?php
require ('../../config.php');
require ('../../include/functions.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query("SET NAMES `UTF8`;");
$sql = "UPDATE `ls_calculator` set `adminComment` = '".mysql_real_escape_string($_POST['adminComment'])."' where `id` = '".intval($_POST['id'])."';";
if (mysql_query($sql))
{
    print $_POST['adminComment'];
} else {
    print 'error';
}
?>