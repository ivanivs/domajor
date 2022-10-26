<?php
require ('../../config.php');
require ('../../include/functions.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
$infoImage = mysql_fetch_array (mysql_query("SELECT * FROM `ls_values_image` where `id` = '".intval($_POST['id'])."';"), MYSQL_ASSOC);
print '<a href="'.$config ['site_url'].'upload/userparams/'.$infoImage['value'].'" rel="lightbox[plants]"><img src="'.$config ['site_url'].'resize_image.php?filename='.urlencode('upload/userparams/'.$infoImage['value']).'&const=129&width=630&height=420&r=255&g=255&b=255" width="630" height="420"></a>';