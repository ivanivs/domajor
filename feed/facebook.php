<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 4/9/19
 * Time: 8:49 AM
 * To change this template use File | Settings | File Templates.
 */
//require ('../mysqlSwitch.php');
require ('../config.php');
require ('../include/functions.php');
require ('../admin/engine/products/functions.php');
require ('../admin/engine/params/functions.php');
require ('../admin/engine/pages/functions.php');
require ('../admin/engine/reference/functions.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8';");
$sql = "SELECT `id`,`value` FROM ls_settings;";
$results = mysql_query($sql);
$number = mysql_num_rows ($results);
for ($i=0; $i<$number; $i++)
{
    $array_config_param[] = mysql_fetch_array($results, MYSQL_ASSOC);
}
foreach ($array_config_param as $key => $v)
{
    $config['user_params_'.$v['id']] = $v['value'];
}
$body = '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
<channel>
<title>Bobas.ua</title>
<link>https://bobas.ua</link>
<description>Bobas.ua - feed</description>
';
//
//if ($arrayCategory = return_all_select_values_params(2)){
//    foreach ($arrayCategory as $v){
//        $oneValue = getOneValue($v['id']);
//        $body .= '<category id="'.$v['id'].'">'.$oneValue['text'].'</category>'."\r\n";
//        if ($arrayType = return_all_select_values_params_by_parent_id($v['id'])){
//            foreach ($arrayType as $oneType){
//                $oneValueChild = getOneValue($oneType['id']);
//                $body .= '<category id="'.$v['id'].'/'.$oneType['id'].'" parentId="'.$v['id'].'">'.$oneValue['text'].'/'.$oneValueChild['text'].'</category>'."\r\n";
//            }
//        }
//    }
//}
//$body .= '</categories>';
//$body .= '<items>';
if ($arrayItems = getArray("SELECT * FROM `ls_items`;")){
    foreach ($arrayItems as $v){
        $infoItem = $v;
        $oldPrice = '';
        $salePrice = '';
        if ($infoItem['price_2']==0 or $infoItem['price_1']==$infoItem['price_2'])
        {
            $priceHtml = $infoItem['price_1'];
        } else {
            $oldPrice = '<oldprice>'.$infoItem['price_1'].'</oldprice>';
            $priceHtml = $infoItem['price_1'];
            $salePrice = '<g:sale_price>'.$infoItem['price_2'].'</g:sale_price>';
        }
        $vendor = getOneValue($infoItem['select_1']);
        $infoAllPhoto = getAllPhotoItem($infoItem['id']);
        $pictures = Array();
        foreach ($infoAllPhoto as $key => $oneP)
        {
            $pictures .= $config['site_url'] . 'upload/userparams/' . $oneP['value'];
        }
        //
        if ($infoItem['select_6']==46 OR $infoItem['select_6']==100 OR $infoItem['select_6']==126 OR $infoItem['select_6']==128 OR $infoItem['select_6']==204){
            $categoryId = $infoItem['select_6'];
        } else {
            $categoryId = $infoItem['select_7'];
        }
        $brandTmp = getOneValue($infoItem['select_1']);
        $brand = $brandTmp['text'];
        $idCard = '';
        if ($infoItem['id_card']==1)
            $idCard = '&id_card='.$infoItem['id_card'].'';
        if ($v['text_7']>0){
            $stock = 'available="true"';
            $stock2 = 'in stock';
        } else {
            $stock = 'available="false"';
            $stock2 = 'out of stock';
        }
        $body .= '
                <item id="'.$v['id'].'" '.$stock.' selling_type="r">
                    <g:id>'.$v['id'].'</g:id>
                    <g:item_group_id>'.$v['id'].'</g:item_group_id>
                    <g:condition>new</g:condition>
                    <g:title>'.htmlspecialchars(getNameItem($v)).'</g:title>
                    <g:link>https://bobas.ua/ua/mode/item-'.$infoItem['id'].'.html</g:link>
                    <g:availability>'.$stock2.'</g:availability>
                    <g:categoryId>'.$categoryId.'</g:categoryId>
                    <g:mpn>'.$infoItem['text_2'].'</g:mpn>
                    <g:price>'.$priceHtml.'</g:price>
                    '.$salePrice.'
                    <g:brand>'.htmlspecialchars($brand).'</g:brand>
                    <g:google_product_category>537</g:google_product_category>
                    <g:image_link>
                        <![CDATA['.$config ['site_url'].'resize_image.php?filename='.urlencode(getMainImage ($infoItem['id'])).'&const=129&r=255&g=255&b=255&width=480&height=400'.$idCard.']]>
                    </g:image_link>
                    <g:description><![CDATA[Bobas.ua '.$infoItem['text_8'].']]></g:description>
                </item>
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
$body .= "\r\n".'</channel>';
$body .= "\r\n".'</rss>';
header("Content-type: text/xml");
echo $body;