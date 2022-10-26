<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 4/9/19
 * Time: 8:49 AM
 * To change this template use File | Settings | File Templates.
 */
//require ('mysqlSwitch.php');
require('../config.php');
require('../include/functions.php');
require('../admin/engine/products/functions.php');
require('../admin/engine/params/functions.php');
require('../admin/engine/pages/functions.php');
require('../admin/engine/reference/functions.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query("SET NAMES 'UTF8';");
$sql = "SELECT `id`,`value` FROM ls_settings;";
$results = mysql_query($sql);
$number = mysql_num_rows($results);
for ($i = 0; $i < $number; $i++) {
    $array_config_param[] = mysql_fetch_array($results, MYSQL_ASSOC);
}
foreach ($array_config_param as $key => $v) {
    $config['user_params_' . $v['id']] = $v['value'];
}
$category = '';
if ($arrayCategory = return_all_select_values_params(1)){
    foreach ($arrayCategory as $v){
        $oneValue = getOneValue($v['id']);
        $category .= '<category id="'.$v['id'].'">'.$oneValue['text'].'</category>'."\r\n";
    }
}
$body = '<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="'.date('Y-m-d H:i').'">
<shop>
<currencies>
<currency id="UAH" rate="1"/>
</currencies>
<categories>
'.$category.'
</categories>
<offers>
';
//$body .= '<items>';
if ($arrayItems = getArray("SELECT * FROM `ls_items` WHERE `inStock` = 1 AND `select_1` != 0;")){
    foreach ($arrayItems as $v){
        $infoItem = $v;
        $infoItem['price_1'] = ceil($infoItem['price_1']*1.2);
        $infoItem['price_2'] = ceil($infoItem['price_2']*1.2);
            $infoAllPhoto = getAllPhotoItem($infoItem['id']);
//            $pictures = array();
            $pictures = '';
            foreach ($infoAllPhoto as $key => $oneP) {
                $pictures .= '<picture>'.$config['site_url'] . 'upload/userparams/' . $oneP['value'].'</picture>';
            }
            //
            if ($infoItem['select_6'] == 46 or $infoItem['select_6'] == 100 or $infoItem['select_6'] == 126 or $infoItem['select_6'] == 128 or $infoItem['select_6'] == 204) {
                $categoryId = $infoItem['select_6'];
            } else {
                $categoryId = $infoItem['select_7'];
            }
            $brandTmp = getOneValue($infoItem['select_3']);
            $brand = $brandTmp['text'];
            $idCard = '';
            if ($infoItem['id_card'] == 1)
                $idCard = '&id_card=' . $infoItem['id_card'] . '';
            $oldPrice = '';
            if ($infoItem['price_2']==0 or $infoItem['price_1']==$infoItem['price_2'])
            {
                $priceHtml = $infoItem['price_1'];
            } else {
                $oldPrice = '<oldprice>'.$infoItem['price_1'].'</oldprice>';
                $priceHtml = $infoItem['price_2'];
            }
//            $body .= '
//            <item id="' . $v['id'] . '" available="true" selling_type="r">
//                <g:id>' . $v['id'] . '</g:id>
//                <title>' . htmlspecialchars($infoItem['text_1']) . '</title>
//                <link>https://kombat.in.ua/ru/mode/item-' . $infoItem['id'] . '.html</link>
//                <g:availability>in stock</g:availability>
//                <g:mpn>' . $infoItem['text_2'] . '</g:mpn>
//                <g:price>' . $priceHtml . '</g:price>
//                <g:size>' . getOneValueText($infoItem['select_3']) . '</g:size>
//                <g:brand>'.getOneValueText($infoItem['select_4']).'</g:brand>
//                <g:image_link>
//                    <![CDATA[' . $config ['site_url'] . 'resize_image.php?filename=' . urlencode('upload/userparams/' . getMainImage($infoItem['id'])) . '&const=129&r=255&g=255&b=255' . $idCard . ']]>
//                </g:image_link>
//                <description><![CDATA[' . $infoItem['text_3'] . ']]></description>
//            </item>
//            ';
            $body .= '
            <offer id="'.$v['id'].'" available="true" selling_type="r" group_id="'.$v['id'].'">
                <name>'.str_replace('&', '&amp;', $infoItem['text_1']).'</name>
                <price>'.$priceHtml.'</price>
                <categoryId>'.$infoItem['select_1'].'</categoryId>
                '.$oldPrice.'
                <quantity_in_stock>'.($infoItem['text_5']+$infoItem['text_4']).'</quantity_in_stock>
                <currencyId>UAH</currencyId>
                '.$pictures.'
                <vendor>'.getOneValueText($infoItem['select_4']).'</vendor>
                <vendorCode>'.$infoItem['text_2'].'</vendorCode>
                <description><![CDATA['.$infoItem['text_3'].']]></description>
                <available>true</available>
                <param name="Размер" unit="Размер">'.getOneValueText($infoItem['select_3']).'</param>
                <param name="Состояние" unit="Состояние">Новое</param>
            </offer>
            ';
            /*
            <vendor>'.$vendor['text'].'</vendor>
                <vendorCode>'.$infoItem['text_2'].'</vendorCode>
                <store>false</store>
                <pickup>false</pickup>
                <delivery>true</delivery>
                <description><![CDATA['.$infoItem['text_4'].']]></description>
                <available>true</available>
                <param name="Размер" unit="Размер">'.$oneSize['text'].'</param>
                <param name="Состояние" unit="Состояние">Новое</param>*/
            //<keywords>женские кроссовки, купить кроссовки, кроссовки рибок, кроссовки reebok, кроссовки найк, кроссовки nike, кроссовки для фитнеса, все для фитнеса, кроссовки оригинал, лосины nike, леггинсы nike, лосины найк, леггинсы найк, кроссовки киев, кроссовки харьков, кроссовки одеса, кроссовки в наличии, кроссовки jordan, купить женские кроссовки, кроссовки женские рибок, кроссовки женские reebok, кроссовки женские найк, кроссовки женские nike, кроссовки для фитнеса, все для фитнеса, кроссовки оригинал, женская одежда nike</keywords>
    }
}
//$body .= "\r\n".'</items>';
$body .= "\r\n".'</offers>';
$body .= "\r\n".'</shop>';
$body .= "\r\n".'</yml_catalog>';
header("Content-type: text/xml");
echo $body;