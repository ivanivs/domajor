<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 3/10/13
 * Time: 10:31 PM
 * To change this template use File | Settings | File Templates.
 */
$info_for_user = get_user_info_by_login($_POST['login']);
if (count ($info_for_user)>1)
{
    if ($_POST['login']==$info_for_user['login'] and md5($_POST['password'])==$info_for_user['password'])
    {
        setcookie ("login", $info_for_user['login'], time() + $config['time_life_cookie'], '/');
        setcookie ("password", $info_for_user['password'], time() + $config['time_life_cookie'], '/');
        setcookie ("id_user_online", $info_for_user['id'], time() + $config['time_life_cookie'], '/');
        setcookie ("accessLevel", $info_for_user['accesslevel'], time() + $config['time_life_cookie'], '/');
        mysql_query ("update ls_users set time_login='".time()."';");
        print 1;
    } else {
        print 0;
    }
} else {
    print 0;
}