<?php
require ('../../../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
require ('../params/functions.php');
require ('../filtr/functions.php');
require ('../../../include/functions.php');
if (check_user())
{
    $lang_file_array = return_my_language ();
    foreach ($lang_file_array as $v)
    {
            require_once($v);
    }
    print '
    <form action="" method="POST">
    <table border="0" align="center">
    <tr>
    <td>
    <h2 class="title">'.$lang[331].'</h2>
    <strong>'.$lang[332].'</strong>
    <div align="left">
    ';
    $array_all_select_params = return_all_select_params();
    if ($array_all_select_params)
    {
        foreach ($array_all_select_params as $v_param)
        {
            $info_select_param_tr = return_one_translate ($v_param['id'], $_COOKIE['id_online_lang'], 'select');
            print '<b>'.$info_select_param_tr['text'].'</b><br>';
            $all_values_of_select_param = return_all_select_values_params($v_param['id']);
            foreach ($all_values_of_select_param as $v_value)
            {
                if ($v_value['parent_param_id'])
                {
                    $info_select_parent_name = return_one_translate ($v_value['parent_param_id'], $_COOKIE['id_online_lang'], 'select_value');
                    $parent_name = $info_select_parent_name['text'].' <img src="'.$config ['site_url'].'images/admin/rightarrow.png"> ';
                }
                if (strlen($v_value['img']))
                {
                    $info_select_value_tr['text'] = '<img src="'.$config ['site_url'].'upload/select_params/'.$v_value['img'].'">';
                } else {
                    $info_select_value_tr = return_one_translate ($v_value['id'], $_COOKIE['id_online_lang'], 'select_value');
                }
                print '<p style="padding-left:30px;"><input type="radio" name="filtr" value="'.$v_value['id'].'">'.$parent_name.$info_select_value_tr['text'].'</p>';
                unset ($info_select_parent_name, $parent_name);
            }
        }
    }
    print '</div></td></tr>
    <tr>
        <td align="center"><input type="submit" name="submit" value="'.$lang[333].'"></td>
    </tr>
    </table></form>';
}
?>