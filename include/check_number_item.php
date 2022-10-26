<?php
require ('../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
require ('../admin/engine/products/functions.php');
require ('../admin/engine/params/functions.php');
require ('../admin/engine/pages/functions.php');
require ('../admin/engine/reference/functions.php');
require ('functions.php');
$array = $_GET['a']['select'];
if (isset ($_GET['a']))
{
    $lang_file_array = return_my_language ();
    foreach ($lang_file_array as $v)
    {
            require_once($v);
    }
    if (isset ($array))
    {
            $price = $_GET['id_param_price'].'|'.$_GET['min_price'].'|'.$_GET['max_price'];
            foreach ($array as $v)
            {
                $link .= 'a[select][]='.$v.'&';
            }
            $count_items = return_count_items ($array, 5 , '', $price);
            if ($count_items)
            {
                print $lang[379].' '.$count_items.' '.$lang[380];
                print ' <a href="?'.$link.'id_param_price='.$_GET['id_param_price'].'&min_price='.$_GET['min_price'].'&max_price='.$_GET['max_price'].'">Показать</a>';
            } else {
                print $lang[381];
            }
    }
}
?>