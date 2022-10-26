<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/15/15
 * Time: 1:54 PM
 * To change this template use File | Settings | File Templates.
 */
require ('../config.php');
require ('../include/functions.php');
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
$apiKey = $config['user_params_38'];
$arrayQuery = json_encode($array);
$array["apiKey"] = $apiKey;
$array["modelName"] = 'Address';
$array["calledMethod"] = 'getWarehouseTypes';
//$array["methodProperties"]["CityRef"] = $v['Ref'];
$arrayQuery = json_encode($array);
$ch = curl_init('https://api.novaposhta.ua/v2.0/json/');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $arrayQuery);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
$arrayResult = json_decode($result, true);
if ($arrayResult['success'] == true AND is_array($arrayResult['data'])){
    foreach ($arrayResult['data'] as $v){
        if (!$info = getOneString("SELECT * FROM `ls_warehouseTypes` WHERE `Ref` = '".$v['Ref']."'")){
            mysql_query("INSERT INTO `ls_warehouseTypes` (`Ref`, `Description`) VALUES ('".$v['Ref']."', '".mysql_real_escape_string($v['Description'])."');");
        }
    }
}
$array["apiKey"] = $apiKey;
$array["modelName"] = 'Address';
$array["calledMethod"] = 'getAreas';
$arrayQuery = json_encode($array);
$ch = curl_init('https://api.novaposhta.ua/v2.0/json/');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $arrayQuery);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
$info = json_decode($result, true);
foreach ($info['data'] as $v){
    $infoCount = getOneString("SELECT COUNT(*) FROM `ls_region` where `ref` = '".mysql_real_escape_string($v['Ref'])."';");
    if ($infoCount['COUNT(*)']==0){
        mysql_query("
        INSERT INTO  `ls_region` (
        `name` ,
        `ref` ,
        `AreasCenter`
        )
        VALUES (
        '".mysql_real_escape_string($v['DescriptionRu'])."' ,
        '".mysql_real_escape_string($v['Ref'])."' ,
        '".mysql_real_escape_string($v['AreasCenter'])."'
        );
        ");
    }
}
$array["apiKey"] = $apiKey;
$array["modelName"] = 'Address';
$array["calledMethod"] = 'getCities';
$arrayQuery = json_encode($array);
$ch = curl_init('https://api.novaposhta.ua/v2.0/json/');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $arrayQuery);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
$info = json_decode($result, true);
foreach ($info['data'] as $v){
    $infoCount = getOneString("SELECT COUNT(*) FROM `ls_cities` where `Ref` = '".mysql_real_escape_string($v['Ref'])."';");
    if ($infoCount['COUNT(*)']==0){
        $infoRegion = getOneString("SELECT * FROM `ls_region` WHERE `ref` = '".mysql_real_escape_string($v['Area'])."';");
        mysql_query("
        INSERT INTO  `ls_cities` (
        `name` ,
        `nameRu` ,
        `Ref` ,
        `regionId`
        )
        VALUES (
        '".mysql_real_escape_string($v['Description'])."' ,
        '".mysql_real_escape_string($v['DescriptionRu'])."' ,
        '".mysql_real_escape_string($v['Ref'])."' ,
        '".$infoRegion['id']."'
        );
        ");
        $idCity = mysql_insert_id();
    } else {
        mysql_query("UPDATE `ls_cities` SET `name` = '".mysql_real_escape_string($v['Description'])."', `nameRu` = '".mysql_real_escape_string($v['DescriptionRu'])."' WHERE  `Ref` = '".mysql_real_escape_string($v['Ref'])."';");
        $infoCount = getOneString("SELECT * FROM `ls_cities` where `Ref` = '".mysql_real_escape_string($v['Ref'])."';");
        $idCity = $infoCount['id'];
    }
    $arrayQuery = json_encode($array);
    $array["apiKey"] = $apiKey;
    $array["modelName"] = 'Address';
    $array["calledMethod"] = 'getWarehouses';
    $array["methodProperties"]["CityRef"] = $v['Ref'];
    $arrayQuery = json_encode($array);
    $ch = curl_init('https://api.novaposhta.ua/v2.0/json/');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $arrayQuery);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    $infoWar = json_decode($result, true);
    foreach ($infoWar['data'] as $oneWar){
        $infoCount = getOneString("SELECT COUNT(*) FROM `ls_warehouses` WHERE `Ref` = '".$oneWar['Ref']."';");
        if ($infoCount['COUNT(*)']==0){
            mysql_query("INSERT INTO  `ls_warehouses` (
                `Ref` ,
                `name` ,
                `nameRu` ,
                `number` ,
                `Longitude` ,
                `Latitude` ,
                `cityId` ,
                `MaxWeightAllowed`,
                `TypeOfWarehouse`
                )
                VALUES (
                '".mysql_real_escape_string($oneWar['Ref'])."' ,
                '".mysql_real_escape_string($oneWar['Description'])."' ,
                '".mysql_real_escape_string($oneWar['DescriptionRu'])."' ,
                '".mysql_real_escape_string($oneWar['Number'])."' ,
                '".mysql_real_escape_string($oneWar['Longitude'])."' ,
                '".mysql_real_escape_string($oneWar['Latitude'])."' ,
                '".$idCity."',
                '".mysql_real_escape_string($oneWar['MaxWeightAllowed'])."',
                '".mysql_real_escape_string($oneWar['TypeOfWarehouse'])."'
                );");
            echo $v['Description'].' - '.$oneWar['Description']."\r\n";
        } else {
            mysql_query("UPDATE `ls_warehouses` SET 
                `name` = '".mysql_real_escape_string($oneWar['Description'])."' , 
                `nameRu` = '".mysql_real_escape_string($oneWar['DescriptionRu'])."',
                `TypeOfWarehouse` = '".mysql_real_escape_string($oneWar['TypeOfWarehouse'])."'
                 WHERE `Ref` = '".$oneWar['Ref']."';");
            echo $v['Description'].' - '.$oneWar['Description']."\r\n";
        }
    }
}
curl_close($ch);

