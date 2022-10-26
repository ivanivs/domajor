<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 10/21/16
 * Time: 11:55 AM
 * To change this template use File | Settings | File Templates.
 */
if (isset ($_POST['phone'])){
    if ($infoUser = getOneString("SELECT * FROM `ls_users` where `login` = '".mysql_real_escape_string($_POST['phone'])."'")){
        $newPass = substr(md5(time()), 0, 6);
        mysql_query("UPDATE `ls_users` set `password` = '".md5($newPass)."' WHERE `id` = '".$infoUser['id']."';");
        go_sms($infoUser['login'], 'Ваш новый пароль: '.$newPass);
        echo '
        <div class="alert alert-success" style="margin: 10px 0px;">
            Ваш новый пароль отправлен на телефон! <a href="http://slam.city/ru/userInfo.html">Войти на сайт под новым паролем</a>
        </div>
        ';
    } else {
        echo '
        <div class="alert alert-danger" style="margin: 10px 0px;">
            <strong>Ошибка!</strong> Пользователь с введенным номером телефона не найден.
        </div>
        ';
    }
}