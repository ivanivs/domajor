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
    foreach ($array as $key => $v){
        $active = '';
        $oneItem = $htmlTemplate;
        $oneItem = getOneItem($v, $oneItem);
        $lastAdd .= $oneItem;
    }
    unset ($array);
    $onlyMainPage = str_replace('{lastAdd}', $lastAdd, $onlyMainPage);
}