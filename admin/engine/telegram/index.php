<?php
require ('../class/Telegram.php');
$configTelegram['token'] = $config['tlgrm']['token'];
$code = rand(1000, 9999);
mysql_query("UPDATE `ls_users` SET `uniqCode` = '".$code."' WHERE `id` = '".intval($_COOKIE['id_user_online'])."';");
$body_admin .= '
<h3>Управління телеграм ботом</h3>
<div>Токен: '.$configTelegram['token'].'</div>
<div style="color: red; font-weight: 600;">Секретний код: '.$code.'</div>
';
