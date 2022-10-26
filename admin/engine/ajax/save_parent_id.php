<?php
require ('../../../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
require ('../params/functions.php');
require ('../../../include/functions.php');
if (check_user())
{
    $lang_file_array = return_my_language ();
    foreach ($lang_file_array as $v)
    {
            require_once($v);
    }
    if (!isset ($_GET['canceled']))
    {
        $sql = "UPDATE `ls_params_select` set `parent_id` = '".$_GET['value']."' where `id` = '".$_GET['id']."';";
        if (mysql_query($sql))
        {
            print '<span style="color:green;">'.$lang[180].'</span><br>';
            $translate_parent_param = return_one_translate ($_GET['value'], $_COOKIE['id_online_lang'], 'select');
            $parent = '<b>'.$translate_parent_param['text'].'</b>';
            print '<div id="parent_div_'.$_GET['id'].'">'.$parent.'<img src="'.$config ['site_url'].'images/admin/edit.png" onclick="change_parent(\''.$_GET['id'].'\')"><br></div>';
        }
    } else {
        $info_select_param = return_one_select_params ($_GET['id']);
        $translate_parent_param = return_one_translate ($info_select_param['parent_id'], $_COOKIE['id_online_lang'], 'select');
        if (strlen($translate_parent_param['text'])==0)
        {
            $translate_parent_param['text'] = '- ';
        }
        $parent = '<b>'.$translate_parent_param['text'].'</b>';
        print '<div id="parent_div_'.$_GET['id'].'">'.$parent.'<img src="'.$config ['site_url'].'images/admin/edit.png" onclick="change_parent(\''.$_GET['id'].'\')"><br></div>';
    }
}
?>