<?php
require ('../../../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
require ('../products/functions.php');
require ('../params/functions.php');
require ('../tinydeal/functions.php');
require ('../../../include/functions.php');
$id_online_lang = 2;
if (check_user())
{
    if ($_POST['id_card'])
    {
        $array_params_for_card = return_parafm_for_card ($_POST['id_card']);
        foreach ($array_params_for_card as $key => $v)
        {
            $position = $v['position'];
            switch ($v['db_type'])
            {
                case "text":
                    $param_translate = return_name_for_id_text_param ($id_online_lang, $v['id_param']);
                    $option .= '<option value="'.$v['id_param'].'">'.$param_translate['text'].'</option>';
                break;
            }
        }
        if (!isset ($_POST['param']))
        {
            print 'Выберете параметр для сравнения: <select name="id_param_main">'.$option.'</select>';
        } else {
            print 'Выберете параметр в который будем загружать значения: <select name="id_param_main">'.$option.'</select>';
        }
    } else {
        print '<span style="color:red;">Нет параметров в выбраной карточке, или вы не выбрали карточку</span>';
    }
} else {
    print '<tr><td colspan="2"><span style="color:red;">Error</span></td></tr>';
}
?>