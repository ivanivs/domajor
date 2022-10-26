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
    $array_all_select_params = return_all_select_params();
    if ($array_all_select_params)
    {
            $html_inheritance = '<select name="id_select_params" id="id_select_params">';
            foreach ($array_all_select_params as $v)
            {
                $info_select_param = return_one_select_params ($v['id']);
                if ($info_select_param['only_text']==1)
                {
                        $info_select_param_tr = return_one_translate ($v['id'], $_COOKIE['id_online_lang'], 'select');
                        $html_inheritance .= '<option value="'.$v['id'].'">'.$info_select_param_tr['text'].'</option>';
                }
            }
            $html_inheritance .= '</select>';
    }
    print '<div align="center">
    <h2 class="title">Добавление меню</h2>
    <table border="0">
    <tr>
        <td>'.$lang[315].'</td>
        <td><input type="text" name="name_menu" id="name_menu" style="border:1px solid black;"></td>
    </tr>
    <tr>
        <td>'.$lang[316].'</td>
        <td>
            '.$html_inheritance.'
        </td>
    </tr>
    <tr>
        <td>'.$lang[317].'</td>
        <td>
            <select id="use_parent" style="border:1px solid black;">
                <option value="0">'.$lang[330].'</option>
                <option value="1">'.$lang[329].'</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>'.$lang[318].'<br>
        <span style="color:red;">'.$lang[325].' '.htmlspecialchars('<div id="menu">{block}</div>').'</span>
        </td>
        <td><input type="text" name="main_menu_class" id="main_menu_class" style="border:1px solid black;" value=\'{block}\'></td>
    </tr>
    <tr>
        <td>'.$lang[319].'<br>
        <span style="color:red;">'.$lang[325].' '.htmlspecialchars('<ul>{block}</ul>').'</span>
        </td>
        <td><input type="text" name="class_parent_link" id="class_parent_blok" style="border:1px solid black;" value=\'{block}\'></td>
    </tr>
    <tr>
        <td>'.$lang[320].'<br>
        <span style="color:red;">'.$lang[325].' '.htmlspecialchars('<p class="menu_head">{link}</p>').'</span>
        </td>
        <td><input type="text" name="class_parent_link" id="class_parent_link" style="border:1px solid black;" value=\'{link}\'></td>
    </tr>
    <tr>
        <td>'.$lang[321].'<br>
        <span style="color:red;">'.$lang[325].' '.htmlspecialchars('<div class="menu_body">{block}</div>').'</span></td>
        <td><input type="text" name="class_blok_link" id="class_blok_link" style="border:1px solid black;" value=\'{block}\'></td>
    </tr>
    <tr>
        <td>'.$lang[322].'<br>
        <span style="color:red;">'.$lang[325].' '.htmlspecialchars('<li>{link}</li>').'</span></td>
        <td><input type="text" name="class_link" id="class_link" style="border:1px solid black;" value=\'{link}\'></td>
    </tr>
    <tr>
        <td><a href="#" style="text-decoration:none" onclick="save_new_menu();">'.$lang[323].'</a></td>
        <td align="right"><a href="#" style="text-decoration:none" onclick="preview_menu();">'.$lang[324].'</a></td>
    </tr>
    </table>
    <div id="preview_menu"></div>
    </div>';
}
?>