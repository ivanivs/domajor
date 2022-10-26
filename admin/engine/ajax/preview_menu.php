<?php
require ('../../../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
require ('../params/functions.php');
require ('../../../include/functions.php');
if (check_user())
{
    print '<div style="border:1px solid black;">';
    $lang_file_array = return_my_language ();
    foreach ($lang_file_array as $v)
    {
            require_once($v);
    }
    print "<h3>".$lang[308]." ".$_POST['name_menu']."</h3>";
    $true_values_select_params = return_all_select_values_params($_POST['id_select_params']);
    if ($true_values_select_params)
    {
        //print count($true_values_select_params);
        foreach ($true_values_select_params as $v_value)
        {
                $info_translate = return_translate_for_id_elements_values_select ($v_value['id'], $_COOKIE['id_online_lang']);
                //$html_option .= '<option value="'.$v_value['id'].'">'.$info_translate['text'].'</option>';
                $father_link = (str_replace ('{link}', '<a href="{main_sait}{lang}/shop/'.translit($info_translate['text']).'/?a[select][]='.$v_value['id'].'">'.$info_translate['text'].'</a>', $_POST['class_parent_link'])); //отримали батьківське посилання з стильом
                if ($_POST['use_parent'])
                {
                    $podlink = return_all_select_values_params_by_parent_id($v_value['id']);
                    if ($podlink)
                    {
                        foreach ($podlink as $v)
                        {
                            $info_translate = return_translate_for_id_elements_values_select ($v['id'], $_COOKIE['id_online_lang']);
                            $blok_podlink .= str_replace ('{link}', '<a href="{main_sait}{lang}/shop/'.translit($info_translate['text']).'/?a[select][]='.$v['id'].'">'.$info_translate['text'].'</a>', $_POST['class_link']);
                        }
                    $true_one_blok = (str_replace ('{block}', $blok_podlink, $_POST['class_blok_link']));
                    }
                }
                $true_one_blok = $father_link."\n".$true_one_blok;
                $return_block .= str_replace ('{block}', $true_one_blok, $_POST['class_parent_blok']);
                unset ($blok_podlink, $true_one_blok, $father_link, $podlink);
        }
        $return_block = htmlspecialchars(str_replace ('{block}', $return_block, $_POST['main_menu']));
    }
    print ($return_block);
    print '</div>';
}
?>