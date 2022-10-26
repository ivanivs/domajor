<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/17/15
 * Time: 9:20 PM
 * To change this template use File | Settings | File Templates.
 */
$apiKey = $config['user_params_38'];
$infoCity = getOneString("SELECT * FROM `ls_cities` WHERE `id` = '".intval($_POST['city'])."';");
$array["apiKey"] = $apiKey;
$array["modelName"] = 'InternetDocument';
$array["calledMethod"] = 'getDocumentPrice';
$array["methodProperties"]["Weight"] = 0.5;
$array["methodProperties"]["ServiceType"] = "WarehouseWarehouse";
$array["methodProperties"]["Cost"] = intval($_POST['allSuma']);
$array["methodProperties"]["CitySender"] = "db5c891e-391c-11dd-90d9-001a92567626";
$array["methodProperties"]["CityRecipient"] = $infoCity['Ref'];
$arrayQuery = json_encode($array);
$ch = curl_init('https://api.novaposhta.ua/v2.0/json/');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $arrayQuery);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
$arrayExp = json_decode($result,true);
echo $arrayExp['data'][0]['Cost'].' грн.';