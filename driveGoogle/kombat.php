<?php
ini_set('memory_limit','2048M');
require ('../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8';");
require ('../include/functions.php');
$url = 'https://niala.com.ua/xml/price_prom.xml';
$data = new SimpleXMLElement(file_get_contents($url), LIBXML_NOCDATA);
$dataJson = json_encode($data);
$arrayAll = json_decode($dataJson, true);
$data = $arrayAll['shop']['offers']['offer'];
$update = 0;
$create = 0;
$changePrice = 0;
require ('../class/Telegram.php');
mysql_query("UPDATE `ls_items` SET `text_4` = 0, `select_5` = 78");
foreach ($data as $oneOffer){
    if ($oneOffer['vendorCode']=='kb-omlst-olgr-l'){
//        print_r ($oneOffer);
//        exit();
    }
    if ($oneOffer['vendor']=='Kombat' or $oneOffer['vendor']=='KOMBAT UK'){
        if ($oneOffer['@attributes']['available']=='false' AND $oneOffer['quantity_in_stock']!=0){
            $stock = 77;
        } elseif ($oneOffer['@attributes']['available']=='true'){
            $stock = 76;
        } elseif ($oneOffer['@attributes']['available']=='false' AND $oneOffer['quantity_in_stock']==0){
            $stock = 78;
        }
//        if (isset ($oneOffer['date']) and !empty($oneOffer['date'])){
//            $stock = 77;
//        }
        $size = addSelectParamValue(3, $oneOffer['param'][0]);
        $color = addSelectParamValue(2, $oneOffer['param'][1]);
        if ($infoItem = getOneString("SELECT * FROM `ls_items` WHERE `text_2` = '".mysql_real_escape_string($oneOffer['vendorCode'])."'")){
            $idItem = $infoItem['id'];
//            echo "Update: ".$idItem."\r\n";
            if ($infoItem['price_1']!=format_summ($oneOffer['price'])){
                $changePrice++;
            }
            mysql_query("UPDATE `ls_items` SET 
            `price_1` = '".format_summ($oneOffer['price'])."', 
            `select_5` = '".$stock."', 
            `text_4` = '".intval($oneOffer['quantity_in_stock'])."',
            `text_3` = '".mysql_real_escape_string($oneOffer['description'])."'
            WHERE `id` = '".$infoItem['id']."';");
            $update++;
        } else {
            mysql_query("
            INSERT INTO `ls_items` (
            `id_card`,
            `text_1`,
            `text_2`,
            `text_3`,
            `select_2`,
            `select_3`,
            `select_4`,
            `text_4`,
            `price_1`,
            `select_5`
            ) VALUES (
            '1',
            '".mysql_real_escape_string($oneOffer['name'])."',
            '".mysql_real_escape_string($oneOffer['vendorCode'])."',
            '".mysql_real_escape_string($oneOffer['description'])."',
            '".$color."',
            '".$size."',
            '1',
            '".intval($oneOffer['quantity_in_stock'])."',
            '".format_summ($oneOffer['price'])."',
            '".$stock."'
            );
            ");
            $idItem = mysql_insert_id();
            $create++;
        }
        $arrayP = Array();
        if (!is_array($oneOffer['picture'])){
            $arrayP[] = $oneOffer['picture'];
        } else {
            $arrayP = $oneOffer['picture'];
        }
        if (!empty($arrayP) and !getOneString("SELECT * FROM `ls_values_image` WHERE `id_item` = '".$idItem."'")){
            foreach ($arrayP as $key => $v){
                $ar = explode('/', $v);
                $fileName = $ar[count($ar)-1];
//                echo $fileName."\r\n";
                $fileName = $idItem.'_'.$fileName;
                copy($v, '../upload/userparams/'.$fileName);
                mysql_query("INSERT INTO `ls_values_image` (
                    `id_item`, 
                    `id_cardparam`, 
                    `value`, 
                    `position`) VALUES (
                    '".$idItem."', 
                    '1', 
                    '".mysql_real_escape_string($fileName)."',
                    '".($key+1)."');");
            }
        }
    }
}
//Telegram::SendTextToAdmin("Обробка файлу постачальника:
//Файл постачальника на дату: ".$arrayAll['@attributes']['date']."
//Нові товари: ".$create."
//Оновили товарів: ".$update."
//Змінилась ціна: ".$changePrice."
//");
require ('PHPExcel.php');
copy('https://docs.google.com/spreadsheets/d/103U8ymSbbvHC7Wj--xQFyXXaTUIg1j5l7xatZnAU_Qc/export?format=xlsx&id=103U8ymSbbvHC7Wj--xQFyXXaTUIg1j5l7xatZnAU_Qc', 'price.xlsx');
$filename = "price.xlsx";
$type = PHPExcel_IOFactory::identify($filename);
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
$objReader = PHPExcel_IOFactory::createReader($type);
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load($filename);
$arrayResult = Array();
foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
    $oneCell = $worksheet->toArray(NULL, false, true, true);
    $worksheets[$worksheet->getTitle()] = $oneCell;
    $arrayResult[] = $oneCell;
}
$arrayStock = Array();
$arraySumm = Array();
foreach ($arrayResult[0] as $oneItem){
    if ($infoItem = getOneString("SELECT * FROM `ls_items` WHERE `id` = '".$oneItem['A']."'")){
        $addSql = '';
        if (($infoItem['select_5']==78 OR $infoItem['select_5']==77) AND $oneItem['H']>0){
            $addSql = ", `select_5` = 76";
        }
        mysql_query("UPDATE `ls_items` SET `text_5` = '".$oneItem['H']."'".$addSql." WHERE `id` = '".$infoItem['id']."';");
    }
}
mysql_query("UPDATE `ls_items` SET `inStock` = 0;");
mysql_query("UPDATE `ls_items` SET `select_5` = 78 WHERE `text_4` = 0 and `text_5` = 0 AND `select_5` != 77;");
mysql_query("UPDATE `ls_items` SET `inStock` = 1 WHERE `select_5` = 77 or `select_5` = 76");
mysql_query("UPDATE `ls_items` SET `inStock` = 1,`select_5` = 76 WHERE `text_5` > 0;");
echo 'Success';