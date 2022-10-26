<?php
require ('../../../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8'");
require ('../params/functions.php');
require ('../../../include/functions.php');
if (check_user())
{
    if ($_GET['parent_param_id'])
    {
        $lang_file_array = return_my_language ();
        foreach ($lang_file_array as $v)
        {
                require_once($v);
        }
        if (!isset ($id_params))
        $id_params = '';
        $results = mysql_query("
        SELECT * FROM ls_params_select_values
        WHERE id_params =  '".$id_params."' and parent_param_id = '".$_GET['parent_param_id']."'
        ORDER BY  `ls_params_select_values`.`position` DESC;");
        $number = @mysql_num_rows ($results);
        if ($number>0)
        {
                for ($i=0; $i<$number; $i++)
                {	
                        $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
                }
                foreach ($array as $v)
                {
                    $info_translate = return_translate_for_id_elements_values_select ($v['id'], $_COOKIE['id_online_lang']);
                    $textarea_value .= $info_translate['text']."\n";
                }
        }
        $info_translate = return_translate_for_id_elements_values_select ($_GET['parent_param_id'], $_COOKIE['id_online_lang']);
        if (!isset ($textarea_value))
        $textarea_value = '';
        print '<span style="font-size:14px;">'.$info_translate['text'].'</span><br>
        <textarea id="textarea_param_'.$_GET['id'].'" rows="30" cols="50">'.$textarea_value.'</textarea><br>
        <img src="'.$config ['site_url'].'images/admin/save_32.png" onclick="save_values(\''.$_GET['id'].'\', \''.$_GET['parent_param_id'].'\')">
        ';
    } else {
        $lang_file_array = return_my_language ();
        foreach ($lang_file_array as $v)
        {
                require_once($v);
        }
        print '<span style="color:red;">'.$lang[305].'</span>';
    }
}
?>