<?php
require ('../../../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
require ('../products/functions.php');
require ('../params/functions.php');
require ('../tinydeal/functions.php');
require ('../../../include/functions.php');
$id_online_lang = 4;
if (check_user())
{
    $array_params_for_card = return_parafm_for_card ($_POST['id_card']);
    foreach ($array_params_for_card as $key => $v)
    {
        $position = $v['position'];
        switch ($v['db_type'])
        {
            case "select":
                $param_translate = return_name_for_id_select_param ($id_online_lang, $v['id_param']);
                $image_params = chek_values_select_text ($v['id_param'], '0');
                $text_params = chek_values_select_text ($v['id_param'], '1');
                $info_params = return_one_select_params($v['id_param']);
                //print_r ($info_params);
                if ($image_params)
                {
                    $html_select_param .= '<table border="0">';
                    $all_values_img = return_all_select_values_params($v['id_param']);
                    foreach ($all_values_img as $one_img)
                    {
                        $html_select_param .= '<tr>
                        <td>
                        ';
                        if ($info_params['multiselect'])
                        {
                            $html_select_param .= '<input type="checkbox" name="select[]" value="'.$one_img['id'].'|'.$v['id'].'">';
                        } else {
                            $html_select_param .= '<input type="radio" name="select[]" value="'.$one_img['id'].'|'.$v['id'].'">';
                        }
                        $html_select_param .= '</td>
                        <td>
                        <img src="'.$config ['site_url'].'upload/select_params/'.$one_img['img'].'" style="border: 1px solid black;">
                        </td>
                        </tr>';
                    }
                    $html_select_param .= '</table>';
                } else {
                    $results_parent = mysql_query("SELECT * FROM `ls_params_select` where `parent_id` = '".$v['id_param']."';");
                    $info_parent_id = mysql_num_rows ($results_parent);
                    if ($info_parent_id)
                    {
                            $info_child = mysql_fetch_array ($results_parent, MYSQL_ASSOC);
                            //print_r ($info_child);
                            $info_id_cardparam_child = mysql_fetch_array(mysql_query("SELECT `id` FROM `ls_cardparam` where `id_param` = '".$info_child['id']."';"));
                            $id_card_param = $info_id_cardparam_child['id'];
                            $child_functions = 'onchange="load_child_param(\''.$id_card_param.'\', \''.$info_child['id'].'\', \''.$v['id_param'].'\', \''.$id_online_lang.'\');";';
                    }
                    if ($text_params)
                    {
                        $all_values_text = return_all_text_values_params ($v['id_param']);
                        foreach ($all_values_text as $one_text_param)
                        {
                            if ($info_params['more_lang'])
                            {
                                $lang_select_params = return_name_for_id_select_param_value ($id_online_lang, $one_text_param['id']);
                            } else {
                                $lang_select_params = return_name_for_id_select_param_value_no_lang ($one_text_param['id']);
                            }
                            $select_html .= '<option value="'.$one_text_param['id'].'|'.$v['id'].'">'.$lang_select_params['text'].'</option>'."\r\n";
                        }
                        if ($info_params['multiselect'])
                        {
                            $html_select_param .= '<select name="select[]" multiple size=5 id="select_'.$v['id_param'].'" '.$child_functions.'><option></option>'.$select_html.'</select>';
                        } else {
                            $html_select_param .= '<select name="select[]" id="select_'.$v['id_param'].'" '.$child_functions.'><option></option>'.$select_html.'</select>';
                        }
                        unset ($child_functions);
                    } else {
                        $html_select_param .= $lang[178];
                    }
                }
                $body_admin .= '<tr>
                <td valign="top">'.$param_translate['text'].'
                </td>
                <td>'.$html_select_param.'</td>
                </tr>';
                unset ($html_select_param, $select_html);
            break;
        }
    }
    print $body_admin;
} else {
    print '<tr><td colspan="2"><span style="color:red;">Error</span></td></tr>';
}
?>