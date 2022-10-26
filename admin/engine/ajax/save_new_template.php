<?php
require ('../../../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
require ('../../../include/functions.php');
if (check_user())
{
    $lang_file_array = return_my_language ();
    foreach ($lang_file_array as $v)
    {
            require_once($v);
    }
    if (isset ($_POST['value']))
    {
        $sql = "INSERT INTO  `ls_template` (
        `name_template`
        )
        VALUES (
        '".$_POST['value']."'
        );
        ";
        if(mysql_query($sql))
        {
            print '<span style="color:green;">'.$lang[351].'</span>';
        } else {
            print '<span style="color:red;">'.$lang[352].'</span>';
        }
    }
}
?>