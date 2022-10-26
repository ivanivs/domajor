<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 1/29/19
 * Time: 10:04 AM
 * To change this template use File | Settings | File Templates.
 */
session_start();
require ('config.php');
$alt_name_online_lang = 'ru';
require ('include/functions.php');
require ('admin/engine/params/functions.php');
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
$out = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="2011-07-20 14:58'.date('Y-m-d H:i').'">
<shop>
<name>maximoto</name>
<company>maximoto</company>
<url>http://sorokavorona.eu/</url>
<currencies>
<currency id="UAH" rate="1"/>
</currencies>
';
$optionCategory = '';
if ($array = getValuesSelectParam(25, 0)){
    foreach ($array as $v){
        $optionCategory .= '<category id="'.$v['id'].'" parentId="505">'.$v['text'].'</category>';
    }
}
$xmlItems = '';
if ($arrayItems = getArray("SELECT * FROM `ls_items` where (`text_3` = '' or `text_3` = 0) and (`select_15` = 12 or `select_15` = 383) and ((`select_1` = '231' or `select_1` LIKE '%\"231\"%')) and ((`select_2` = '505' or `select_2` LIKE '%\"505\"%' OR `select_2` IN (506,507,508,523,541,544,635,640,645,648,769))) AND `select_25` != 649")){
    foreach ($arrayItems as $v){
        $price = $v['price_1'];
        if ($v['price_2']!=0 and $v['price_2']!=$v['price_1']){
            $price = $v['price_2'];
        }
        $blockParam = Array();
        $blockParam[] = 'text_1';
        $blockParam[] = 'text_3';
        $blockParam[] = 'text_6';
        $blockParam[] = 'text_7';
        $blockParam[] = 'text_8';
        $blockParam[] = 'text_11';
        $blockParam[] = 'text_13';
        $blockParam[] = 'select_1';
        $blockParam[] = 'select_3';
        $blockParam[] = 'select_8';
        $blockParam[] = 'select_15';
        $blockParam[] = 'select_18';
        $blockParam[] = 'select_20';
        $blockParam[] = 'select_21';
        $blockParam[] = 'select_22';
        $blockParam[] = 'select_23';
        $blockParam[] = 'select_25';
        $blockParam[] = 'select_26';
        $blockParam[] = 'select_27';
        $blockParam[] = 'select_30';
//        $blockParam[] = 'select_31';
        $blockParam[] = 'select_32';
        $blockParam[] = 'select_34';
        $params = '';
        foreach ($v as $key => $oneP){
            if (!in_array($key, $blockParam) and $oneP!=0 and !empty($oneP)){
                $type = '';
                if (substr_count($key,'select')>0){
                    $type = 'select';
                }
                if (substr_count($key,'text')>0){
                    $type = 'text';
                }
                $trueKey = str_replace('select_', '', $key);
                $trueKey = str_replace('select_', '', $trueKey);
                $stop = 0;
                switch($type){
                    case "select":
                        $tmp = return_name_for_id_select_param(1, $trueKey);
                        $tmpArr = explode('/', $tmp['text']);
                        $nameParam = $tmpArr[0];
                        if ($trueKey==31){
                            $valueParam = getOneValueText($oneP);
                            $valueParam = str_replace('weeks', 'недель', $valueParam);
                            $valueParam = str_replace('days', 'дней', $valueParam);
                            $valueParam = str_replace('h', ' години', $valueParam);
                        } elseif ($trueKey==15){
                            $valueParam = getOneValueText($oneP);
                            $valueParam = str_replace('instock', 'в наличии', $valueParam);
                            $valueParam = str_replace('outofstock', 'нет в наличии', $valueParam);
                            $valueParam = str_replace('available for order', 'доступен для заказа', $valueParam);
                        } else {
                            $valueParam = getOneValueText($oneP);
                        }
                        break;
                    case "text":
                        $tmp = return_name_for_id_text_param(1, $trueKey);
                        $nameParam = $tmp['text'];
                        $valueParam = $oneP;
                        break;
                    default:
                        $stop = 1;
                }
                switch ($trueKey){
                    case "24":
                        $nameParam = 'Возраст ребенка';
                        break;
                    case "16":
                        $nameParam = 'Страна-производитель товара';
                        break;
                    case "2":
                        $nameParam = 'Вид';
                        break;
                    case "31":
                        $nameParam = 'Доставка/Оплата';
                        break;
                }
                if ($stop==0) {
                    $params .= '<param name="'.$nameParam.'">'.$valueParam.'</param>'."\r\n";
                }
            }
        }
        if ($v['text_13']>0){
            $available = 'true';
        } else {
            $available = 'false';
        }
//        if ($available=='true'){
        if (file_exists('upload/select_params/'.str_replace(' ','_', getOneValueText($v['select_1'])).'/'.$v['text_11'])){
            $available = 'true';
            $xmlItems .= '
                <offer id="'.$v['id'].'" available="'.$available.'">
                    <url>'.generateItemLink ($v['id']).'</url>
                    <price>'.$price.'</price>
                    <currencyId>UAH</currencyId>
                    <categoryId>'.$v['select_25'].'</categoryId>
                    <picture>'.$config['site_url'].'upload/select_params/'.str_replace(' ','_', getOneValueText($v['select_1'])).'/'.$v['text_11'].'</picture>
                    <vendor>'.getOneValueText($v['select_1']).'</vendor>
                    <stock_quantity>'.$v['text_13'].'</stock_quantity>
                    <name>'.$v['text_1'].' '.getOneValueText($v['select_1']).' '.$v['text_5'].'</name>
                    <description><![CDATA['.getDescriptionSoroka($v['text_7']).']]></description>
                    '.$params.'
                    <param name="Гарантия">5 лет</param>
                </offer>
                ';
//            }
        }
    }
}
header("Content-type: text/xml");
$out .= '
<categories>
<category id="505">Транспорт</category>
'.$optionCategory.'
</categories>
<offers>
    '.$xmlItems.'
</offers>
</shop>
</yml_catalog>
';
echo $out;