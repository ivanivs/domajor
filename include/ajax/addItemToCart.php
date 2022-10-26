<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 3/18/13
 * Time: 9:33 PM
 * To change this template use File | Settings | File Templates.
 */

require ('../../config.php');
require ('../../include/functions.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
if (isset ($_COOKIE['lang']) or isset ($_GET['lang']))
{
    if (isset ($_GET['lang']))
    {
        $alt_name_online_lang = $_GET['lang'];
    } else {
        $alt_name_online_lang = $_COOKIE['lang'];
    }
    $info_for_my_lang = return_info_for_lang_for_alt_name($alt_name_online_lang);
    $id_online_lang = $info_for_my_lang['id'];
} else {
    $info_for_my_lang = return_info_for_lang_for_alt_name($config ['default_language']);
    $id_online_lang = $info_for_my_lang['id'];
    $alt_name_online_lang = $info_for_my_lang['alt_name'];
}
if (isset ($_COOKIE['id_user_online'])){
    $userID = $_COOKIE['id_user_online'];
}
//if ($_POST['array']==0)
//{
//    $_POST['array'] = 1;
//}
$infoItem = getOneString("SELECT * FROM `ls_items` WHERE `id` = '".intval($_POST['idItem'])."'");
$price = $infoItem['price_1'];
if ($infoItem['price_2']!=0)
{
    $price = $infoItem['price_2'];
}
$sql = "
    SELECT 
        `ls_giftToItem`.*,`ls_cart`.`number`
    FROM 
        `ls_giftToItem`
    LEFT JOIN
        `ls_cart`
    ON 
        `ls_cart`.`id_item` = `ls_giftToItem`.`itemId`
    WHERE 
        `ls_giftToItem`.`giftId` = '".intval($_POST['idItem'])."' 
    AND 
        `ls_giftToItem`.`price` != '0.01'
    AND
        `ls_giftToItem`.`dateFrom` <= '".date("Y-m-d")."'
    AND
        `ls_giftToItem`.`dateTo` >= '".date("Y-m-d")."'
    AND `ls_cart`.`uniq_user` = '".$_COOKIE['PHPSESSID']."'
    AND `ls_cart`.`status` = 0
        ";
//if ($_COOKIE['accessLevel']==100) {
    if ($infoGift = getOneString($sql)) {
        $infoCountGiftInCart = getOneString("
        SELECT 
            `ls_cart`.`number` 
        FROM 
            `ls_cart` 
        WHERE 
            `ls_cart`.`id_item` = '".intval($_POST['idItem'])."'
        AND `ls_cart`.`uniq_user` = '".$_COOKIE['PHPSESSID']."'
        AND `ls_cart`.`status` = 0    
        AND `ls_cart`.`price` = '".$infoGift['price']."'
        "
        );
//        print_r ($infoCountGiftInCart);
//        print_r ($infoCount);
        if ($infoCountGiftInCart['number']<$infoGift['number'])
            $price = $infoGift['price'];

//        echo "Price: ".$price;
    }
//}
if ($infoCount = getOneString("SELECT * FROM `ls_cart` WHERE `id_item` = '".intval($_POST['idItem'])."' and `uniq_user` = '".$_COOKIE['PHPSESSID']."' AND `price` = '".$price."' AND `idUser` = '".$userID."' AND `status` = 0;")){
    $sql = "UPDATE `ls_cart` SET `number` = `number` + 1 where `id` = '".$infoCount['id']."';";
} else {
    if (!isset ($_POST['number']) or $_POST['number']==0)
        $_POST['number'] = 1;
    $sql = "
    INSERT INTO `ls_cart`
    (
    `id_item`,
    `uniq_user`,
    `other_param`,
    `idUser`,
    `time` ,
    `number`,
    `price`
    ) VALUES (
    '".$_POST['idItem']."',
    '".$_COOKIE['PHPSESSID']."',
    '".$_POST['array']."',
    '".$userID."',
    '".time()."' ,
    '".$_POST['number']."',
    '".mysql_real_escape_string($price)."'
    );
    ";
}
if (mysql_query($sql))
{
//    if ($_COOKIE['accessLevel']==100) {
        if ($arrayGiftFree = getArray("SELECT * FROM `ls_giftToItem` WHERE `itemId` = '" . intval($_POST['idItem']) . "' AND `price` = '0.01'")) {
            foreach ($arrayGiftFree as $oneGiftFree) {
                if ($infoCount = getOneString("SELECT * FROM `ls_cart` WHERE `id_item` = '" . intval($oneGiftFree['giftId']) . "' AND `price` = '0.01' and `uniq_user` = '" . $_COOKIE['PHPSESSID'] . "' AND `idUser` = '" . $userID . "' AND `status` = 0;")) {
                    mysql_query("UPDATE `ls_cart` SET `number` = `number` + 1 where `id` = '" . $infoCount['id'] . "';");
                } else {
                    if (!isset ($_POST['number']) or $_POST['number'] == 0)
                        $_POST['number'] = 1;
                    mysql_query("
                        INSERT INTO `ls_cart`
                        (
                        `id_item`,
                        `uniq_user`,
                        `other_param`,
                        `idUser`,
                        `time` ,
                        `number`,
                        `price`
                        ) VALUES (
                        '" . $oneGiftFree['giftId'] . "',
                        '" . $_COOKIE['PHPSESSID'] . "',
                        '" . $_POST['array'] . "',
                        '" . $userID . "',
                        '" . time() . "' ,
                        '" . $_POST['number'] . "',
                        '0.01'
                        );
                        ");
                }
            }
        }
//    }
    print '
    <div class="alert alert-success">
        <strong>Вітаємо!</strong> Товар успішно додано до кошика
    </div>
    <div style="margin-top: 15px;"></div>
    <a href="'.$config['site_url'].$alt_name_online_lang.'/mode/cart.html" class="btn btn-success">Оформити замовлення</a> <button class="btn btn-default" data-dismiss="modal" aria-label="Close">Продовжити покупки</button>
    ';
} else {
    print '<div style="color:red; font-weight:bold;">Ошибка добавления товара в корзину</div>';
}