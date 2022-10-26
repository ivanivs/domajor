<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 1/13/14
 * Time: 4:30 PM
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
if (isset ($_COOKIE['accessLevel']) and $_COOKIE['accessLevel']==100){
    go_sms ($_POST['number'], $_POST['message']);
    echo '<b style="color: green; font-weight: bold;">отправлено</b>';
}