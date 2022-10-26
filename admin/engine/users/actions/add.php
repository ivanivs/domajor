<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 3/6/13
 * Time: 8:02 PM
 * To change this template use File | Settings | File Templates.
 */

if (isset ($_POST['login']) and !isset ($_GET['id']))
{
    $sql = "
    INSERT INTO `ls_users`
    (
      `time_reg`,
      `login`,
      `password`,
      `accesslevel`) VALUES (
      CURRENT_TIMESTAMP,
      '".$_POST['login']."',
      '".md5($_POST['pass'])."',
      '".$_POST['accesslevel']."');
    ";
    if (mysql_query($sql))
    {
        $body_admin .= '<div style="color:green; font-weight: bold;">Новый пользователь успешно добавлен</div>';
    } else {
        $body_admin .= '<div style="color:red; font-weight: bold;">Ошибка добавления пользователя</div>';
    }
}
if (isset ($_POST['login']))
{
    if (($_GET['id']!=1 and $_GET['id']!=2) or $_COOKIE['id_user_online']==2)
    {
        $sql = "
        UPDATE `ls_users` set
        `login` = '".mysql_real_escape_string($_POST['login'])."',
        `password` = '".md5($_POST['pass'])."' where `id` = '".intval($_GET['id'])."';
        ";
        if (mysql_query($sql))
        {
            $body_admin .= '<div style="color:green; font-weight: bold;">Пользователь успешно изменен</div>';
        } else {
            $body_admin .= '<div style="color:red; font-weight: bold;">Ошибка изменения пользователя</div>';
        }
    } else {
        $body_admin .= '<div style="color:red; font-weight: bold;">Ошибка доступа</div>';
    }
}
if (isset ($_GET['id']))
{
    if (($_GET['id']!=1 and $_GET['id']!=2) or $_COOKIE['id_user_online']==2)
    {
        $infoUser = mysql_fetch_array(mysql_query("SELECT * FROM `ls_users` where `id` = '".intval($_GET['id'])."';"), MYSQL_ASSOC);
    } else {
        $body_admin .= '<div style="color:red; font-weight: bold;">Ошибка доступа</div>';
    }
}
$body_admin .= '
<form action="" method="POST">
<table border="0">
    <tr>
        <td>Логин:</td>
        <td><input type="text" name="login" style="border:1px solid grey;" value="'.$infoUser['login'].'"></td>
    </tr>
    <tr>
        <td>Пароль:</td>
        <td><input type="text" name="pass" style="border:1px solid grey;"></td>
    </tr>
    <tr>
        <td>Администратор?</td>
        <td><select name="accesslevel"><option value="0">Нет</option><option value="100">Да</option></select></td>
    </tr>
    <tr>
        <td colspan="2"><input type="submit" name="submit" value="Сохранить"></td>
    </tr>
</table>
</form>
';