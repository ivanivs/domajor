<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 3/1/18
 * Time: 6:48 PM
 * To change this template use File | Settings | File Templates.
 */
require ('../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8';");
require ('../include/functions.php');
mysql_query("UPDATE `ls_items` SET `select_34` = 481;");
mysql_query("UPDATE `ls_items` SET `select_34` = 480 WHERE `price_2` <> 0 and `select_15` = 12;");
$infoCount = getOneString("SELECT COUNT(*) FROM `ls_items` WHERE `select_34` = 480");
echo "ACTIONS: ".$infoCount['COUNT(*)'];
//print_r ($_SERVER);