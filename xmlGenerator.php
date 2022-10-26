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
<yml_catalog date="'.date('Y-m-d H:i').'">
<shop>
<name>maximoto</name>
<company>maximoto</company>
<url>http://sorokavorona.eu/</url>
<currencies>
<currency id="UAH" rate="1"/>
</currencies>
';
$arrayCategoryIkea = Array();
if ($arrayCategory = getArray("SELECT `select_2` FROM `ls_items` WHERE (`select_1` = 396 OR `select_1` = 194) AND `select_15` = 12 GROUP by `select_2`")){
    foreach ($arrayCategory as $categoryIkea){
        $arrayCategoryIkea[] = $categoryIkea['select_2'];
    }
}
$optionCategory = '';
if ($array = getValuesSelectParam(25, 0)){
    foreach ($array as $v){
        if (in_array($v['id'], $arrayCategoryIkea)){
            unset ($arrayCategoryIkea[array_search($v['id'], $arrayCategoryIkea)]);
        }
        $optionCategory .= '<category id="'.$v['id'].'" parentId="505">'.$v['text'].'</category>';
    }
}
$arrayIssetParent = Array();
$arrayIssetParent[] = 482;
if ($array = getValuesSelectParam(3, 0)){
    $optionCategory.= '<category id="482">Коляски</category>';
    foreach ($array as $v){
        if (in_array($v['id'], $arrayCategoryIkea)){
            unset ($arrayCategoryIkea[array_search($v['id'], $arrayCategoryIkea)]);
        }
        $optionCategory .= '<category id="'.$v['id'].'" parentId="482">'.$v['text'].'</category>';
    }
}
$arrayIssetParent[] = 195;
$optionCategory.= '<category id="195">Уход и гигиена</category>';
$optionCategory .= '<category id="921" parentId="195">пустушки</category>';
$optionCategory .= '<category id="529" parentId="195">ванночки, аксессуары и косметика для купания</category>';
$arrayIssetParent[] = 458;
if ($array = getValuesSelectParam(21, 0)){
    $optionCategory.= '<category id="458">Детская комната</category>';
    foreach ($array as $v){
        if (in_array($v['id'], $arrayCategoryIkea)){
            unset ($arrayCategoryIkea[array_search($v['id'], $arrayCategoryIkea)]);
        }
        $optionCategory .= '<category id="'.$v['id'].'" parentId="458">'.$v['text'].'</category>';
    }
}
if (!empty($arrayCategoryIkea)){
    foreach ($arrayCategoryIkea as $v){
        $value = getOneValue($v);
        if (isset ($value['parent_param_id']) and !in_array($value['parent_param_id'], $arrayIssetParent)){
            $arrayIssetParent[] = $value['parent_param_id'];
            $optionCategory.= '<category id="'.$value['parent_param_id'].'">'.getOneValue($value['parent_param_id'])['text'].'</category>';
        }
        $optionCategory .= '<category id="'.$v.'" parentId="'.$value['parent_param_id'].'">'.getOneValue($v)['text'].'</category>';
    }
}
$xmlItems = '';
if ($arrayItems = getArray("
SELECT
    *
FROM
    `ls_items`
where
  (
            (`text_3` = '' or `text_3` = 0)
        and
            (`select_15` = 12 or `select_15` = 383)
        and
            ((`select_1` = '473' or `select_1` LIKE '%\"473\"%') OR (`select_1` = '871' or `select_1` LIKE '%\"871\"%')  OR `select_1` = 458 OR `select_1` = 745)
        and
            ((`select_2` = 510 OR `select_2` = 529 OR `select_2` = 921 OR `select_2` = '505' or `select_2` LIKE '%\"505\"%' OR `select_2` = '482' or `select_2` LIKE '%\"482\"%' OR `select_2` IN (500, 501,502,503,504,385,182,178,175,506,507,508,523,541,544,635,640,645,648,769,482,486,488,489,490,874,901,903,905,907,899)))
  )
  or
  (
    (`select_1` = 396 OR `select_1` = 194) AND `select_15` = 12
  )
")){
    foreach ($arrayItems as $v){
        $price = $v['price_1'];
        $priceOld = '';
        $pricePromo = '';
        if ($v['price_2']!=0 and $v['price_2']!=$v['price_1']){
            $priceOld = '<price_promo>'.$v['price_2'].'</price_promo>';
            $price = $v['price_1'];
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
                if ($v['select_1']!='194' and $v['select_1']!='396'){
                    if (!empty($v['select_25']) AND $v['select_25']!=0){
                        $categoryId = $v['select_25'];
                    } elseif (!empty($v['select_3']) AND $v['select_3']!=0){
                        $categoryId = $v['select_3'];
                    } elseif (!empty($v['select_21'])) {
                        $categoryId = $v['select_21'];
                    } else {
                        $categoryId = $v['select_2'];
                    }
                } else {
                    $categoryId = $v['select_2'];
                }

                $xmlItems .= '
                <offer id="'.$v['id'].'" available="'.$available.'">
                    <url>'.generateItemLink ($v['id']).'</url>
                    <price>'.$price.'</price>
                    '.$priceOld.'
                    <currencyId>UAH</currencyId>
                    <categoryId>'.$categoryId.'</categoryId>
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