<?php
///**
// * Created by PhpStorm.
// * User: ivashka
// * Date: 19.04.17
// * Time: 23:33
// */
ini_set('memory_limit','2048M');
require ('../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8';");
require ('../include/functions.php');
if ($array = getArray("SELECT * FROM `ls_items` WHERE `select_6` != 0 ORDER BY `inStock` DESC")){
    foreach ($array as $v){
        $info = getOneString("SELECT * FROM `ls_params_select_values` WHERE `id` = ".$v['select_6']);
        mysql_query("UPDATE `ls_items` SET `select_1` = '".$info['parent_param_id']."' WHERE `id` = '".$v['id']."';");
    }
}
//require ('PHPExcel.php');
//copy('https://docs.google.com/spreadsheets/d/103U8ymSbbvHC7Wj--xQFyXXaTUIg1j5l7xatZnAU_Qc/export?format=xlsx&id=103U8ymSbbvHC7Wj--xQFyXXaTUIg1j5l7xatZnAU_Qc', 'price.xlsx');
//$filename = "price.xlsx";
//$type = PHPExcel_IOFactory::identify($filename);
//$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
//$cacheSettings = array( ' memoryCacheSize ' => '8MB');
//PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
//$objReader = PHPExcel_IOFactory::createReader($type);
//$objReader->setReadDataOnly(true);
//$objPHPExcel = $objReader->load($filename);
//$arrayResult = Array();
//foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
//    $oneCell = $worksheet->toArray(NULL, false, true, true);
//    $worksheets[$worksheet->getTitle()] = $oneCell;
//    $arrayResult[] = $oneCell;
//}
//$arrayStock = Array();
//$arraySumm = Array();
//foreach ($arrayResult[0] as $oneItem){
//    if ($infoItem = getOneString("SELECT * FROM `ls_items` WHERE `id` = '".$oneItem['A']."'")){
//        $addSql = '';
//        if ($infoItem['select_5']==78 AND $oneItem['H']>0){
//            $addSql = ", `select_5` = 76";
//        }
//        mysql_query("UPDATE `ls_items` SET `text_5` = '".$oneItem['H']."'".$addSql." WHERE `id` = '".$infoItem['id']."';");
//    }
//}