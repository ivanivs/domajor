<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 4/8/20
 * Time: 4:35 PM
 * To change this template use File | Settings | File Templates.
 */
if (intval($_POST['st'])==1){
    mysql_query("
    INSERT INTO `ls_discountsCode` (
    `discountId`,
    `itemId`
    ) VALUES (
    '".intval($_POST['id'])."',
    '".intval($_POST['itemId'])."');
    ");
} else {
    mysql_query("DELETE FROM `ls_discountsCode` WHERE `discountId` = '".intval($_POST['id'])."' AND `itemId` = '".intval($_POST['itemId'])."'");
}