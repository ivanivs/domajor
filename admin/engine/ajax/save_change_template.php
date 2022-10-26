<?php
require ('../../../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
require ('../template/functions.php');
require ('../../../include/functions.php');
if (check_user())
{
    $lang_file_array = return_my_language ();
    foreach ($lang_file_array as $v)
    {
            require_once($v);
    }
    $info_template = return_one_template($_POST['id']);
    if (isset ($_POST['id']))
    {
        $sql = "
        update ls_template set name_template = '".$_POST['name_template']."', template = '".$_POST['template']."' where id = '".$_POST['id']."';
        ";
        if (mysql_query($sql))
        {
            print '<span style="color:green">'.$lang[355].'</span>';
        } else {
            print '<span style="color:red">'.$lang[356].'</span>';
        }
    }
}
?>