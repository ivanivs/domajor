<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/19/15
 * Time: 6:06 PM
 * To change this template use File | Settings | File Templates.
 */
if ($infoUser['password']!=md5($_POST['oldPassword'])){
    echo 0;
} else {
    mysql_query("UPDATE `ls_users` SET `password` = '".md5($_POST['password'])."' where `id` = '".$infoUser['id']."';");
    echo 1;
}