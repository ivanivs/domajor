<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/15/15
 * Time: 4:35 PM
 * To change this template use File | Settings | File Templates.
 */
$lastProduct = '';
$htmlTemplate = file_get_contents('templates/'.$config ['default_template'].'/mainOneItem.html');
if ($arrayItems = getArray("SELECT `ls_items`.* FROM `ls_items` JOIN `ls_values_image` ON `ls_items`.`id` = `ls_values_image`.`id_item` WHERE `ls_items`.`text_4` > 0 AND `ls_values_image`.`id_item` IS NOT NULL GROUP by `ls_items`.`id` ORDER by `ls_items`.`id` DESC LIMIT 0,10")){
    foreach ($arrayItems as $key => $v){
        $active = '';
        $oneItem = $htmlTemplate;
        $oneItem = getOneItem($v, $oneItem);
        $lastAdd .= $oneItem;
    }
    unset ($array);
    $onlyMainPage = str_replace('{lastAdd}', $lastAdd, $onlyMainPage);
}
$lastAdd = '';
$htmlTemplate = file_get_contents('templates/'.$config ['default_template'].'/mainOneItem.html');
if ($arrayItems = getArray("SELECT `ls_items`.* FROM `ls_items` JOIN `ls_values_image` ON `ls_items`.`id` = `ls_values_image`.`id_item` WHERE `ls_items`.`text_4` > 0 AND `ls_values_image`.`id_item` IS NOT NULL GROUP by `ls_items`.`id` ORDER by `ls_items`.`visit` DESC LIMIT 0,10")){
    foreach ($arrayItems as $key => $v){
        $active = '';
        $oneItem = $htmlTemplate;
        $oneItem = getOneItem($v, $oneItem);
        $lastAdd .= $oneItem;
    }
    unset ($array);
    $onlyMainPage = str_replace('{popular}', $lastAdd, $onlyMainPage);
}
$lastAdd = '';
$htmlTemplate = file_get_contents('templates/'.$config ['default_template'].'/mainOneItem.html');
echo $sql = "SELECT `ls_items`.* FROM `ls_items` JOIN `ls_values_image` ON `ls_items`.`id` = `ls_values_image`.`id_item` WHERE `ls_items`.`price_2` != 0 AND `ls_items`.`price_1` != `ls_items`.`price_2` `ls_items`.`text_4` > 0 AND `ls_values_image`.`id_item` IS NOT NULL GROUP by `ls_items`.`id` ORDER by `ls_items`.`visit` DESC LIMIT 0,10";
if ($arrayItems = getArray($sql)){
    foreach ($arrayItems as $key => $v){
        $active = '';
        $oneItem = $htmlTemplate;
        $oneItem = getOneItem($v, $oneItem);
        $lastAdd .= $oneItem;
    }
    unset ($array);
    $onlyMainPage = str_replace('{actions}', $lastAdd, $onlyMainPage);
}