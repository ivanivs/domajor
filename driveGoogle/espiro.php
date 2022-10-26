<?php
ini_set('memory_limit','2048M');
require ('../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8';");
require ('../include/functions.php');
require ('PHPExcel.php');
copy('https://docs.google.com/spreadsheets/d/10IrazbvvaNAlYZO_VzhWeAkLmh1YyXVyIMVtlgzLSBI/export?format=xlsx&id=10IrazbvvaNAlYZO_VzhWeAkLmh1YyXVyIMVtlgzLSBI', 'price.xlsx');
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
$arrayIds = Array();
mysql_query("UPDATE `ls_items` SET `text_12` = 0 WHERE `select_14` = 292");
mysql_query("UPDATE `ls_items` SET `select_10` = 205 WHERE `select_14` = 292 AND `text_7` < 1;");
//print_r ($arrayResult);
foreach ($arrayResult[0] as $key => $oneItem){
    if ($key>6 and !empty($oneItem['F'])) {
        if ($oneItem['C'] == 'в наявності') {
            $arrayStock[$oneItem['C']] = 1;
            $arraySumm[$oneItem['C']] = format_summ($oneItem['F']);
        } else {
            $arrayStock[$oneItem['C']] = 0;
            $arraySumm[$oneItem['C']] = format_summ($oneItem['F']);
        }
        if ($info = getOneString("SELECT * FROM `ls_items` WHERE `text_2` = '" . $oneItem['B'] . "' AND `select_14` = 292")) {
            echo '<div style="color: green;">' . $info['searchField'] . " - " . $oneItem['B'] . " - " . format_summ($oneItem['F']) . " - Код по сайту: " . $info['id'] . " Артикул: " . $oneItem['B'] . "</div>";
            if ($oneItem['C'] == 'в наявності') {
                mysql_query("UPDATE `ls_items` SET `text_12` = 1, `price_1` = '".format_summ($oneItem['F'])."',`select_10` = 204 WHERE `id` = '" . $info['id'] . "'");
            }
            $arrayIds[] = $info['id'];
        } else {
            echo '<div style="color: red;">' . $oneItem['B'] . ' - ' . $oneItem['A'] . ' - NOT FOUND' . " Артикул: " . $oneItem['B'] . "</div>";
        }
    }
}