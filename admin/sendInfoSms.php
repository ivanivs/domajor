<?php
/**
 * Created by PhpStorm.
 * User: ivashka
 * Date: 4/28/14
 * Time: 3:47 AM
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
    $info_order = mysql_fetch_array(mysql_query("SELECT * FROM `ls_orders` where `id` = '".intval($_POST['id'])."';"), MYSQL_ASSOC);
    $sms = $_POST['smsInfo'];
    //print "<br>";
    //print strlen($sms);
    go_sms (str_replace (' ', '', $info_order['number_phone']), $sms);
    echo '
    <div class="alert alert-success">
        <strong>Поздравляем!</strong> СМС успешно отправлено!
    </div>
    ';
}