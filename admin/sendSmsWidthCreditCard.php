<?php
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
    if (isset ($_POST['idOrder']))
        $infoOrder = getOneString("SELECT * FROM `ls_orders` WHERE `id` = '".intval($_POST['idOrder'])."';");
    if (strlen($_POST['suma'])>0){
        $suma = ' Сума за товар: '.intval($_POST['suma']).' грн.';
    }
    if (isset ($_POST['onlyCard']) and $_POST['onlyCard']==1){
        $infoOrder['site'] = htmlspecialchars($_POST['smsSite']);
    }
    go_sms ($_POST['phone'], 'Приватбанк: '.$config['user_params_36'].' '.$config['user_params_37'].$suma);
    echo '
        <div class="alert alert-success">
            <b>Поздравляем!</b> Успешно отправлено
        </div>
        ';
} else {
    echo '
    <div class="alert alert-error">
        <b>Внимание!</b> Ошибка доступа, Вы не обладаете правами администратора!
    </div>
    ';
}