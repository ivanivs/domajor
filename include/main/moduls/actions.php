<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 3/10/13
 * Time: 4:03 PM
 * To change this template use File | Settings | File Templates.
 */
if (MOBILEVER==0){
    $count = 9;
} else {
    $count = 6;
}
$results = mysql_query("SELECT * FROM `ls_items` where (`text_7` > 0 OR `text_12` > 0) and `price_2` != 0 ORDER by RAND() LIMIT 0, ".$count.";");
$number = mysql_num_rows ($results);
if ($number)
{
    $actionsBlock = file_get_contents('templates/'.$config ['default_template'].'/actionsBlock.html');
    for ($i=0; $i<$number; $i++)
    {
        $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);
    }
    $htmlTemplate = file_get_contents('templates/'.$config ['default_template'].'/mainOneItem.html');
    $htmlTemplate = str_replace ('oneItem', 'oneItemActions', $htmlTemplate);
    $i = 0;
    if (MOBILEVER==0){
        $keyMobile = 3;
    } else {
        $keyMobile = 1;
    }
    foreach ($array as $key => $v){
        $active = '';
        $oneItem = $htmlTemplate;
        if ($key==0)
            $active = ' active';
//        if ($i==0)
//            $oneItem = '<div class="row'.$active.'" style="margin-top: 10px;">'.$oneItem;
        $oneItem = getOneItem($v, $oneItem);
        $i++;
        if (!isset ($array[$key+1]) or $i==$keyMobile) {
//            $oneItem .= '</div>';
            $i = 0;
        }
        $actions .= $oneItem;
    }
    unset ($array);
    $actionsBlock = str_replace('{actions}', $actions, $actionsBlock);
    $onlyMainPage = str_replace('{actionsBlock}', $actionsBlock, $onlyMainPage);
} else {
    $html = str_replace('{actionsBlock}', '', $html);
}
