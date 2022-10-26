<?php
if (isset ($_POST['id'])){
    mysql_query("INSERT INTO `ls_giftToItem` (
        `itemId`, 
        `giftId`, 
        `price`, 
        `dateFrom`, 
        `dateTo`) VALUES (
        '".intval($_POST['id'])."', 
        '".intval($_POST['giftId'])."', 
        '".mysql_real_escape_string($_POST['price'])."', 
        '".mysql_real_escape_string($_POST['dateFrom'])."', 
        '".mysql_real_escape_string($_POST['dateTo'])."'
        );");
    echo generateGiftToItemTable(intval($_POST['id']));
}