<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 3/11/13
 * Time: 1:48 AM
 * To change this template use File | Settings | File Templates.
 */
require ('../../config.php');
require ('../../include/functions.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
$sql = "
update `ls_users` set `password` = '".md5($_POST['password'])."' where `id` = '".$_COOKIE['id_user_online']."';
";
if (mysql_query($sql))
{
    print '<div class="success">Пароль успешно изменен</div>';
} else {
    print '<div class="error">Ошибка изменения пароля</div>';
}