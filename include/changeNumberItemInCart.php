<?php
session_start();
require ('../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
require ('functions.php');
$id = str_replace('numberItem_', '', $_POST['id']);
$infoItemInCart = getOneString("SELECT * FROM `ls_cart` WHERE `id` = '".intval($id)."'");
if ($giftArray = getArray("SELECT * FROM `ls_giftToItem` WHERE `itemId` = '".$infoItemInCart['id_item']."' AND `price` = '0.01'")){
    foreach ($giftArray as $v){
        $sql = "update `ls_cart` set `number` = '".intval($_POST['value'])."' where `uniq_user` = '".$infoItemInCart['uniq_user']."'
           AND `status` = 0 AND `id_item` = '".$v['giftId']."' AND `price` = '0.01';
        ";
        mysql_query ($sql);
    }
}
echo $sql = "
    SELECT 
        `ls_giftToItem`.*,`ls_cart`.`number`, `ls_cart`.`id` as `idInCart`
    FROM 
        `ls_giftToItem`
    LEFT JOIN
        `ls_cart`
    ON 
        `ls_cart`.`id_item` = `ls_giftToItem`.`giftId`
    WHERE 
        `ls_giftToItem`.`itemId` = '".$infoItemInCart['id_item']."' 
    AND 
        `ls_giftToItem`.`price` != '0.01'
    AND
        `ls_giftToItem`.`dateFrom` <= '".date("Y-m-d")."'
    AND
        `ls_giftToItem`.`dateTo` >= '".date("Y-m-d")."'
    AND `ls_cart`.`price` = `ls_giftToItem`.`price`
    AND `ls_cart`.`uniq_user` = '".$_COOKIE['PHPSESSID']."'
    AND `ls_cart`.`status` = 0
        ";
if ($_COOKIE['accessLevel']==100) {
    if ($arrayGiftInCart = getArray($sql)) {
        foreach ($arrayGiftInCart as $infoGift) {
            if ($infoGift['number'] > intval($_POST['value'])){
                mysql_query("update `ls_cart` set `number` = '".intval($_POST['value'])."' where `id` = '".$infoGift['idInCart']."';");
            }
        }
    }
}
mysql_query ("update `ls_cart` set `number` = '".intval($_POST['value'])."' where `id` = '".intval($id)."';");
?>