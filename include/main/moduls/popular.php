<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 3/10/13
 * Time: 4:03 PM
 * To change this template use File | Settings | File Templates.
 */
$sql = "SELECT * FROM `ls_items` where ((`select_1` <> '' or `select_10` <> '' or `select_11` <> '' or `select_12` <> '' or `select_15` <> '' or `select_18` <> '') and `select_4` = 42) AND (`select_20` = 162 OR `select_20` = 161) order by RAND() DESC LIMIT 0,28;";
$results = mysql_query($sql);
$number = mysql_num_rows ($results);
if ($number)
{
    $popularBlock = file_get_contents('templates/'.$config ['default_template'].'/popularBlock.html');
    for ($i=0; $i<$number; $i++)
    {
        $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);
    }
    $htmlTemplate = file_get_contents('templates/'.$config ['default_template'].'/mainOneItem.html');
    $i = 0;
    if (MOBILEVER==0){
        $keyMobile = 3;
    } else {
        $keyMobile = 1;
    }
    foreach ($array as $key => $v)
    {
        $active = '';
        $oneItem = $htmlTemplate;
        if ($key==0)
            $active = ' active';
        if ($i==0)
            $oneItem = '<div class="item'.$active.'">'.$oneItem;
        $oneItem = str_replace('{name}', $v['text_1'], $oneItem);
        $oneItem = str_replace('{price}', $v['price_1'], $oneItem);
        $oneItem = str_replace('{link}', generateItemLink ($v['id']), $oneItem);
        if ($v['price_2']!=0 and $v['price_2']!=$v['price_1'])
        {
            $oneItem = str_replace('{strike}', '<span style="text-decoration: line-through; color:#7c7c7c; font-size: 16px;">', $oneItem);
            $oneItem = str_replace('{/strike}', '</span>', $oneItem);
            $oneItem = str_replace('{price_discount}', '<span>'.$v['price_2'].'</span>', $oneItem);
        } else {
            $oneItem = str_replace('{strike}', '', $oneItem);
            $oneItem = str_replace('{/strike}', '', $oneItem);
            $oneItem = str_replace('{price_discount}', '', $oneItem);
        }
        if (time()-$v['time']<2678400){
            $oneItem = str_replace('{new}', '<div class="new"><img src="http://og-shop.in.ua/images/new.png"></div>', $oneItem);
        } else {
            $oneItem = str_replace('{new}', '', $oneItem);
        }
        $oneItem = str_replace('{image}', $config ['site_url'].'resize_image.php?filename='.urlencode('upload/userparams/'.getMainImage ($v['id'])).'&const=128&width=172&height=172&r=255&g=255&b=255', $oneItem);
        $i++;
        if (!isset ($array[$key+1]) or $i==$keyMobile) {
            $oneItem .= '</div>';
            $i = 0;
        }
        $popular .= $oneItem;
    }
    unset ($array);
    $popularBlock = str_replace('{popular}', $popular, $popularBlock);
    $onlyMainPage = str_replace('{popularBlock}', $popularBlock, $onlyMainPage);
}
