<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 1/12/14
 * Time: 11:39 PM
 * To change this template use File | Settings | File Templates.
 */
require ('./../config.php');
require ('./../include/functions.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8'");
$sql = "SELECT `id`,`value` FROM ls_settings;";
$results = mysql_query($sql);
$number = mysql_num_rows ($results);
for ($i=0; $i<$number; $i++)
{
    $array_config_param[] = mysql_fetch_array($results, MYSQL_ASSOC);
}
foreach ($array_config_param as $key => $v)
{
    $config['user_params_'.$v['id']] = $v['value'];
}
if (isset ($_COOKIE['lang']) or isset ($_GET['lang']))
{
    if (isset ($_COOKIE['lang']))
    {
        $alt_name_online_lang = $_COOKIE['lang'];
    } else {
        $alt_name_online_lang = $_GET['lang'];
    }
    $info_for_my_lang = return_info_for_lang_for_alt_name($alt_name_online_lang);
    $id_online_lang = $info_for_my_lang['id'];
} else {
    $info_for_my_lang = return_info_for_lang_for_alt_name($config ['default_language']);
    $id_online_lang = $info_for_my_lang['id'];
}
if (isset ($_COOKIE['accessLevel']) and $_COOKIE['accessLevel']==100){
    if (isset ($_POST['id'])){
        $sql = "UPDATE `ls_orders`
        SET
        `ship_date` = '".mysql_real_escape_string($_POST['ship_date'])."',
        `number_declaration` = '".mysql_real_escape_string($_POST['number_declaration'])."',
        `sendSms` = 1
        where `id` = '".$_POST['id']."';
        ";
        if (mysql_query($sql)){
            echo '
            <div class="alert alert-success">
                <b>Поздравляем! Данные успешно сохранены!</b>
            </div>
            ';
            $info_order = mysql_fetch_array(mysql_query("SELECT * FROM `ls_orders` where `id` = '".intval($_POST['id'])."';"), MYSQL_ASSOC);
            $results = mysql_query("SELECT `value` FROM `ls_reference_values_translate` where `id_reference_value` = '".$info_order['dostavka']."' and `id_lang` = '".$id_online_lang."' limit 0,1;");
            $info_dostavka = mysql_fetch_array($results, MYSQL_ASSOC);
            $sms = "Ваше замовлення вже в дорозі! ТТН ".$_POST['number_declaration']."%0Aотримувати ".$info_order['ship_date'];
            //print "<br>";
            //print strlen($sms);
            go_sms (str_replace (' ', '', $info_order['number_phone']), $sms);
        } else {
            echo '
            <div class="alert alert-error">
                <b>Ошибка!</b> Не смогли записать данные в базу!
            </div>
            ';
        }
    } else {
        echo '
        <div class="alert alert-error">
            <b>Внимание!</b> Ошибка, не смогли индефицировать заказ! Повторите попытку!
        </div>
        ';
    }
} else {
    echo '
    <div class="alert alert-error">
        <b>Внимание!</b> Ошибка доступа, Вы не обладаете правами администратора!
    </div>
    ';
}