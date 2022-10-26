<?php
require ('../config.php');
require ('../admin/engine/params/functions.php');
require ('../admin/engine/products/functions.php');
require ('functions.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
$uniq_id_in_base = $_COOKIE['PHPSESSID'];
$price = return_price_for_param($_POST['id_item'], $_POST['param']);
$tr_ref_value = mysql_fetch_array(mysql_query("SELECT `value` FROM `ls_reference_values_translate` where `id_reference_value` = '".$price[1]."' and `id_lang` = '".$_POST['id_online_lang']."';"), MYSQL_ASSOC);
print $price[0].' '.$tr_ref_value['value'];
?>