<?php
require ('../../../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8'");
require ('../params/functions.php');
require ('../products/functions.php');
require ('../../../include/functions.php');
if (check_user())
{
    $sql = "SELECT * FROM `ls_params_select_values` where `id_params` = '".$_POST['id_child']."' and `parent_param_id` = '".$_POST['id_value_parent_id']."';";
    $results = mysql_query($sql);
    $number = @mysql_num_rows ($results);
    if ($number>0)
    {
        for ($i=0; $i<$number; $i++)
        {	
                $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
        }	
        foreach ($array as $v)
        {
            if ($v['more_lang'])
            {
                $lang_select_params = return_name_for_id_select_param_value ($_POST['id_online_lang'], $v['id']);
            } else {
                $lang_select_params = return_name_for_id_select_param_value_no_lang ($v['id']);
            }
            $option .= '<option value="'.$v['id'].'|'.$_POST['id_child'].'">'.$lang_select_params['text'].'</option>'."\r\n";
        }
        print $option;
    }
}
?>