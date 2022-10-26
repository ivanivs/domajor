<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/19/15
 * Time: 8:27 PM
 * To change this template use File | Settings | File Templates.
 */
$sql = "
UPDATE
    `ls_users`
SET
  `name` = '".mysql_real_escape_string($_POST['name'])."',
  `surName` = '".mysql_real_escape_string($_POST['surName'])."',
  `email` = '".mysql_real_escape_string($_POST['email'])."',
  `region` = '".intval($_POST['region'])."',
  `city` = '".intval($_POST['city'])."',
  `warehouse` = '".intval($_POST['warehouse'])."'
WHERE
    `id` = '".$infoUser['id']."';
";
if (mysql_query($sql)){
    echo 1;
} else {
    echo 0;
}