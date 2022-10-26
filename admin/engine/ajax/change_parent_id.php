<?php
require ('../../../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8'");
require ('../params/functions.php');
require ('../../../include/functions.php');
if (check_user())
{
    $array_all_select_params = return_all_select_params();
    if ($array_all_select_params)
    {
        $info_select_param = return_one_select_params ($_GET['id']);
        if ($info_select_param['only_text']==1)
        {
            $html_inheritance = '<select name="parent_id" id="parent_id_'.$_GET['id'].'">';
            foreach ($array_all_select_params as $v)
            {
                if ($info_select_param['id']!=$v['id'])
                {
                    $info_select_param_tr = return_one_translate ($v['id'], $_COOKIE['id_online_lang'], 'select');
                    if ($info_select_param['parent_id']==$v['id'])
                    {
                        $html_inheritance .= '<option value="'.$v['id'].'" select>'.$info_select_param_tr['text'].'</option>';
                    } else {
                        $html_inheritance .= '<option value="'.$v['id'].'">'.$info_select_param_tr['text'].'</option>';
                    }
                }
            }
            $html_inheritance .= '</select>
            <img src="'.$config ['site_url'].'images/admin/save.png" onclick="save_parent(\''.$_GET['id'].'\')">
            <img src="'.$config ['site_url'].'images/admin/button_cancel.png" onclick="cancel_parent(\''.$_GET['id'].'\')">
            ';
            print $html_inheritance;
        } else {
            $lang_file_array = return_my_language ();
            foreach ($lang_file_array as $v)
            {
                    require_once($v);
            }
            print '<span style="color:red;">'.$lang[303].'</span> <img src="'.$config ['site_url'].'images/admin/button_cancel.png" onclick="cancel_parent(\''.$_GET['id'].'\')">';
        }
    }
}
?>