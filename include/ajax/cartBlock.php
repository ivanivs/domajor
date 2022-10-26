<?php
if (!isset ($include))
{
    session_start();
    require ('../../config.php');
    $mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
    mysql_select_db($config ['database']);
    mysql_query ("SET NAMES 'utf-8'");
}
$uniq_id_in_base = $_COOKIE['PHPSESSID'];
$sql = "SELECT * FROM `ls_cart` where `uniq_user` = '".$uniq_id_in_base."' and status <> 1;";
$results = mysql_query($sql);
$number = mysql_num_rows ($results);
for ($i=0; $i<$number; $i++)
{
    $array_item_in_cart[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
}
foreach ($array_item_in_cart as $v)
{
    $info_item = mysql_fetch_array(mysql_query("SELECT * FROM `ls_items` where `id` = '".$v['id_item']."';"), MYSQL_ASSOC);
    if ($info_item['price_2']!=0){
        $info_item['price_1'] = $info_item['price_2'];
    }
    $allPrice += $info_item['price_1'];
    $numberItem += $v['number'];
}
if (!isset ($numberItem))
{
    $numberItem = 0;
    $allPrice = 0;
}
if (!isset ($include))
{
    print '
    <div class="topField" style="display: inline-block;">'.$numberItem.' шт.</div>
    <div class="bottomField" style="display: inline-block;">'.$allPrice.' грн.</div>
    ';
} else {
    $cartBlock = '
        <div class="topField" style="display: inline-block;">'.$numberItem.' шт.</div>
        <div class="bottomField" style="display: inline-block;">'.$allPrice.' грн.</div>
    ';
}
?>