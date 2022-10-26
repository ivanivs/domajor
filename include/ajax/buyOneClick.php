<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 1/12/14
 * Time: 9:17 PM
 * To change this template use File | Settings | File Templates.
 */

session_start();
require ('../../config.php');
require ('../../include/functions.php');
$uniq_id_in_base = $_COOKIE['PHPSESSID'];
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8';");
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
$sql = "
INSERT INTO `ls_cart` (`id_item`, `uniq_user`, `other_param`, `time`, `status`) VALUES
('".intval($_POST['id'])."', '".$uniq_id_in_base."', '".mysql_real_escape_string($_POST['param'])."', '".time()."', 1)
";
mysql_query($sql);
$paramOrder = '';
if ($infoUser = getOneString("SELECT * FROM `ls_users` WHERE `login` LIKE '%".mysql_real_escape_string($_POST['userPhone'])."%';")){
    $paramOrder = "
    `id_user` ,
    `pib` ,
    `email` ,
    `region` ,
    `city` ,
    `warehouse` ,
    ";
    $valueOrder = "
    '".$infoUser['id']."',
    '".$infoUser['name']." ".$infoUser['surName']."',
    '".$infoUser['email']."',
    '".$infoUser['region']."',
    '".$infoUser['city']."',
    '".$infoUser['warehouse']."',
    ";
} elseif($infoUser = getOneString("SELECT * FROM `ls_orders` WHERE `number_phone` LIKE '%".mysql_real_escape_string($_POST['userPhone'])."%';")) {
    $paramOrder = "
    `id_user` ,
    `pib` ,
    `email` ,
    `region` ,
    `city` ,
    `warehouse` ,
    ";
    $valueOrder = "
    '".$infoUser['id_user']."',
    '".$infoUser['pib']."',
    '".$infoUser['email']."',
    '".$infoUser['region']."',
    '".$infoUser['city']."',
    '".$infoUser['warehouse']."',
    ";
}
$sql = "
				INSERT INTO  `ls_orders` (
				".$paramOrder."
				`uniq_user` ,
				`number_phone` ,
				`status` ,
				`buyOneClick` ,
				`time` ,
				`mobile`,
				`site`
				)
				VALUES (
				".$valueOrder."
				'".$uniq_id_in_base."',
				'".mysql_real_escape_string($_POST['userPhone'])."',
				'0' ,
				1,
				'".time()."' ,
				'".MOBILEVER."',
				'Slam.city'
				);
				";
if (mysql_query ($sql))
{
    if ($config['user_params_5']==1){
        send_sms (1);
    }
    echo '
    <div class="alert alert-success">
      <b>Поздравляем!</b> Ваш заказ успешно принят. В ближайшее время с Вами свяжется наш менеджер, для уточнения деталей!
    </div>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/978665133/?value=1.00&amp;label=b6EOCOO_mwgQrf3U0gM&amp;guid=ON&amp;script=0"/>
    </div>
    ';
    session_regenerate_id();
} else {
    echo '
    <div class="alert alert-error">
       <b>Ошибка!</b> Извините, но произошла ошибка.
    </div>
    ';
    session_regenerate_id();
}