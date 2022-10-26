<?php
require ('../../config.php');
require ('../../include/functions.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query('SET CHARACTER SET utf8');
mysql_query("SET NAMES `UTF8`;");
$infoOrder = mysql_fetch_array(mysql_query("SELECT * FROM `ls_calculator` where `id` = '".intval($_POST['id'])."';"), MYSQL_ASSOC);
$body_admin .= '<div align="center">
		<div id="temporary"></div>
		<h2 id="title">Заказ товаров из США</h2>
		<form>
		<table cellspacing="1" class="tablesorter" width="100%">
		 <thead>
		<tr> 
			<th>Ссилка</th> 
			<th>Комментарий</th>
		</tr> 
		 </thead> 
		  <tbody> ';
    $array = json_decode($infoOrder['links']);
    $comments = json_decode($infoOrder['comment']);
    foreach ($array as $key => $v)
    {
        if (strlen($v)>0)
        {
            $body_admin .= '
                <tr>
                    <td align="center"><a href="'.$v.'" target="_blank">'.substr($v, 0, 30).'...</a></td>
                    <td align="center">'.$comments[$key].'</td>
                </tr>
            ';
        }
    }
$body_admin .= '
</tbody>
</table>
</form>
</div>';
print $body_admin;
?>