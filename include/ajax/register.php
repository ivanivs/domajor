<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 3/10/13
 * Time: 5:39 PM
 * To change this template use File | Settings | File Templates.
 */
$info = getOneString("SELECT COUNT(*) FROM `ls_users` WHERE `login` = '".mysql_real_escape_string($_POST['phone'])."'");
if ($info['COUNT(*)']==0){
    $sql = "
        INSERT INTO  `ls_users` (
        `time_reg` ,
        `password` ,
        `accesslevel` ,
        `login` ,
        `name` ,
        `surName` ,
        `region` ,
        `city` ,
        `warehouse` ,
        `email`
        )
        VALUES (
        CURRENT_TIMESTAMP ,
        '".md5($_POST['password'])."',
        '0' ,
        '".mysql_real_escape_string($_POST['phone'])."',
        '".mysql_real_escape_string($_POST['name'])."',
        '".mysql_real_escape_string($_POST['surNameReg'])."',
        '".mysql_real_escape_string($_POST['regionReg'])."' ,
        '".mysql_real_escape_string($_POST['city'])."' ,
        '".intval($_POST['warehouse'])."' ,
        '".mysql_real_escape_string($_POST['emailReg'])."'
        );
    ";
    if (mysql_query($sql))
    {
        echo 1;
    } else {
        echo 2;
    }
} else {
    echo 0;
}