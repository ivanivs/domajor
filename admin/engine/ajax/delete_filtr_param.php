<?php
require ('../../../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
require ('../params/functions.php');
require ('../menu/functions.php');
require ('../filtr/functions.php');
require ('../../../include/functions.php');
if (check_user())
{
    $lang_file_array = return_my_language ();
    foreach ($lang_file_array as $v)
    {
            require_once($v);
    }
    $sql = "DELETE from ls_filtr_param where id='".$_POST['id']."';";
    if (mysql_query($sql))
    {
        print '<span style="color:green;">'.$lang[342].'</span>';
    } else {
        print '<span style="color:red;">'.$lang[343].'</span>';
    }
}
?>