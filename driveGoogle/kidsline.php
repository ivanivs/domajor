<?php
/**
 * Created by PhpStorm.
 * User: ivashka
 * Date: 19.04.17
 * Time: 23:33
 */
ini_set('memory_limit','2048M');
require ('../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8';");
require ('../include/functions.php');
require ('PHPExcel.php');
copy('https://docs.google.com/spreadsheets/d/1QCCSzMXZvf9GfwYdmChzxSCNeGH3RVXU6OCGj_xmIfc/export?format=xlsx&id=1QCCSzMXZvf9GfwYdmChzxSCNeGH3RVXU6OCGj_xmIfc', 'price.xlsx');
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
mysql_query("UPDATE `ls_items` SET `text_12` = 0 WHERE `select_14` = 288");
mysql_query("UPDATE `ls_items` SET `select_10` = 205 WHERE `select_14` = 288 AND `text_7` < 1;");
foreach ($arrayResult[0] as $key => $oneItem){
    if ($key>5 AND !empty($oneItem['C'])){
        if ($oneItem['B']=='в наявності' OR $oneItem['B']=='закінчується') {
            $arrayStock[$oneItem['C']] = 1;
            $arraySumm[$oneItem['C']] = format_summ($oneItem['F']);
        } else {
            $arrayStock[$oneItem['C']] = 0;
            $arraySumm[$oneItem['C']] = format_summ($oneItem['F']);
        }
        if ($info = getOneString("SELECT * FROM `ls_items` WHERE `text_2` = '" . $oneItem['C'] . "' AND `select_14` = 288")) {
            echo '<div style="color: green;">'.$info['searchField'] . " - ".$oneItem['B']." - ".format_summ($oneItem['F'])." - Код по сайту: ".$info['id']." Артикул: ".$oneItem['C']."</div>";
            if ($oneItem['B']=='в наявності' OR $oneItem['B']=='закінчується') {
                mysql_query("UPDATE `ls_items` SET `text_12` = 1, `price_1` = '".format_summ($oneItem['F'])."',`select_10` = 204 WHERE `id` = '" . $info['id'] . "'");
            }
            $arrayIds[] = $info['id'];
        } else {
            echo '<div style="color: red;">'.$oneItem['A'].' - '. $oneItem['B'] . ' - NOT FOUND' . " Артикул: ".$oneItem['C']."</div>";
        }
    }
}
echo '<div style="font-weight: bold;">Групові товари</div>';
if ($arrayItems = getArray("SELECT * FROM `ls_items` WHERE `select_14` = 288 AND `text_2` LIKE '%/%';")){
    foreach ($arrayItems as $oneItem){
        if (!in_array($oneItem['id'],$arrayIds)) {
            $arrCode = explode('/', $oneItem['text_2']);
            $stock = 1;
            $codeHtml = '';
            $summ = 0;
            foreach ($arrCode as $oneCode) {
                if (!empty($oneCode)) {
                    if (!isset ($arrayStock[$oneCode]) or $arrayStock[$oneCode] != 1) {
                        $stock = 0;
                        $codeHtml .= '<span style="color: red;">' . $oneCode . '</span> - ';
                    } else {
                        $codeHtml .= '<span style="color: green;">' . $oneCode . '</span> - ';
                        $summ += $arraySumm[$oneCode];
                    }
                }
            }
        if ($stock==1)
            mysql_query("UPDATE `ls_items` SET `text_12` = 1, `price_1` = '".$summ."',`select_10` = 204 WHERE `id` = '" . $oneItem['id'] . "'");
            echo $oneItem['id'] . ' - ' . $codeHtml . ' - ' . $stock . ' - ' . $summ . '<br>';
        }
    }
}