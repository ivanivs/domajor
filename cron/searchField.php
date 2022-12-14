<?php
require ('../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8';");
require ('../include/functions.php');
if ($arrayItems = getArray("SELECT * FROM `ls_items`")){
    foreach ($arrayItems as $v){
        $searchField = getOneValueText($v['select_3']).' '.$v['text_1'].' '.getOneValueText($v['select_3']).' '.$v['text_1'].' '.getOneValueText($v['select_3']);
        mysql_query("UPDATE `ls_items` SET `searchField` = '".mysql_real_escape_string($searchField)."' WHERE `id` = '".$v['id']."';");
    }
}