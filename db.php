<?php
require ('config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
$sql = "select * from `ls_values_text`;";
$results = mysql_query($sql);
$number = @mysql_num_rows ($results);
if ($number)
{
    for ($i=0; $i<$number; $i++)
    {	
        $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
    }
    foreach ($array as $v)
    {
        mysql_query("update `ls_values_text` set `text` = '".trim($v['text'])."' where `id` = '".$v['id']."';");
        print $v['id']."\r\n";
    }
}
?>