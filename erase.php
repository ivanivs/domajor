<?php
//require ('config.php');
//$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
//mysql_select_db($config ['database']);
//mysql_query ("SET NAMES 'utf-8'");
//$sql = "SELECT `id`, `id_item` FROM `ls_values_text`;";
//$results = mysql_query($sql);
//$number = @mysql_num_rows ($results);
//if ($number>0)
//{
//    for ($i=0; $i<$number; $i++)
//    {	
//        $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
//    }
//    foreach ($array as $v)
//    {
//        $infoCountIdItem = mysql_fetch_array (mysql_query("SELECT COUNT(*) FROM `ls_items` where `id` = '".$v['id_item']."';"), MYSQL_ASSOC);
//        if (!$infoCountIdItem['COUNT(*)'])
//        {
//            print $v['id_item']." - DELETED\r\n";
//            mysql_query ("DELETE FROM `ls_values_text` WHERE `id_item` = '".$v['id_item']."';");
//            mysql_query ("DELETE FROM `ls_values_select` WHERE `id_item` = '".$v['id_item']."';");
//            mysql_query ("DELETE FROM `ls_values_price` WHERE `id_item` = '".$v['id_item']."';");
//        } else {
//            print $v['id_item']."\r\n";
//        }
//    }
//}
?>