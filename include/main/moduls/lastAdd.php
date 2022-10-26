<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 3/10/13
 * Time: 4:03 PM
 * To change this template use File | Settings | File Templates.
 */
unset ($array);
$htmlTemplate = file_get_contents('templates/'.$config ['default_template'].'/singleProduct.html');
$count = 12;
if ($array = getArray("SELECT * FROM `ls_items` where `select_5` = 76 GROUP by `text_1` ORDER by `id` DESC LIMIT 0, ".$count.";")){
    foreach ($array as $key => $v){
        $active = '';
        $oneItem = $htmlTemplate;
        $oneItem = getOneItem($v, $oneItem);
        $lastAdd .= $oneItem;
    }
    unset ($array);
    $onlyMainPage = str_replace('{lastAdd}', $lastAdd, $onlyMainPage);
}
