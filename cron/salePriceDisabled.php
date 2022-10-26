<?php
require ('../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8';");
require ('../include/functions.php');
if ($arrayItems = getArray("SELECT * FROM `ls_items` WHERE `text_10` != ''")){
    foreach ($arrayItems as $v){
        if (date("Y-m-d", strtotime($v['text_10'])) == date("Y-m-d")){
            echo $v['id']." - isset now\r\n";
            mysql_query("UPDATE `ls_items` SET `select_9` = '185', `price_2` = '0' WHERE `id` = '".$v['id']."'");
        }
    }
}