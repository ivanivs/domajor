<?php
require ('../../../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8'");
require ('../params/functions.php');
require ('../../../include/functions.php');
if (check_user())
{
    $lang_file_array = return_my_language ();
    foreach ($lang_file_array as $v)
    {
            require_once($v);
    }
    $value = $_POST['value'];
    $array_value = explode ("\n", $value);
    if (count ($array_value))
    {
        foreach ($array_value as $v)
        {
            mysql_query ("START TRANSACTION;");
            mysql_query ("INSERT INTO  `ls_params_select_values` (
            `id_params` ,
            `type_value` ,
            `parent_param_id`
            )
            VALUES (
            '".$_POST['id']."' ,
            '1' ,
            '".$_POST['parent_values_id']."'
            );");
            $id_new_param = mysql_insert_id();
            mysql_query ("
            INSERT INTO  `ls_translate` (
            `id_lang` ,
            `id_elements` ,
            `text` ,
            `type`
            ) VALUES (
            '".$_COOKIE['id_online_lang']."' ,
            '".$id_new_param."' ,
            '".$v."' ,
            'select_value'
            );
            ");
            $id_new_param_translate = mysql_insert_id();
            mysql_query ("UPDATE `ls_params_select_values` set id_translate='".$id_new_param_translate."' where id='".$id_new_param."';");
            if (mysql_query ("COMMIT;"))
            {
                    print '<div align="center" style="font-size:16; color:green">'.$lang[120].' "'.$v.'" '.$lang[129].'</div><br>';
            } else {
                    print '<div align="center" style="font-size:16; color:red">'.$lang[130].' "'.$v.'"</div><br>';
            }
        }
    }
}
?>