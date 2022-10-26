<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 3/11/13
 * Time: 1:40 AM
 * To change this template use File | Settings | File Templates.
 */
require ('../../config.php');
require ('../../include/functions.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
$infoUser = mysql_fetch_array(mysql_query("SELECT * FROM `ls_users` where `id` = '".$_COOKIE['id_user_online']."';"), MYSQL_ASSOC);
if ($infoUser['password']==md5($_POST['password']))
{
    print 1;
} else {
    print 0;
}