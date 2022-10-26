<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 3/16/13
 * Time: 3:16 PM
 * To change this template use File | Settings | File Templates.
 */
require ('../../config.php');
require ('../../include/functions.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
$array = $_POST['array'];
foreach ($array as $v)
{
    $arr = explode ('|', $v);
    $arraySelect[$arr[1]][] = $arr[2];
}
$arrayParam['select'] = $arraySelect;
getCountAllItemsWithParam($arrayParam, $_POST['category']);