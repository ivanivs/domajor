<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 5/8/18
 * Time: 10:35 AM
 * To change this template use File | Settings | File Templates.
 */
//require ('../mysqlSwitch.php');
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
$dataRequest = Array();
if ($arrayOrder = getArray("SELECT * FROM `ls_orders` WHERE `number_declaration` != '' AND `status` = 2 ORDER by `id` DESC LIMIT 0,90;")){
    foreach ($arrayOrder as $oneOrder){
        $dataRequest[] = '
        {
            "DocumentNumber": "'.$oneOrder['number_declaration'].'",
                    "Phone":""
                }
        ';
    }
}
$tmp = '{
        "apiKey": "'.$config['user_params_38'].'",
        "modelName": "TrackingDocument",
        "calledMethod": "getStatusDocuments",
        "methodProperties": {
            "Documents": [
                '.implode(',',$dataRequest).'
            ]
        }
    }';
$ch = curl_init('https://api.novaposhta.ua/v2.0/json/');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $tmp);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
$infoArray = json_decode($result, true);
//print_r ($infoArray);
$arrayTrueStatusCode = Array();
$arrayTrueStatusCode[] = 9;
$arrayTrueStatusCode[] = 10;
$arrayTrueStatusCode[] = 11;
$arrayFalseStatusCode = Array();
$arrayFalseStatusCode[] = 102;
$arrayFalseStatusCode[] = 103;
$arrayFalseStatusCode[] = 108;
$arrayArrivedAtOffice = Array();
$arrayArrivedAtOffice[] = 7;
$arrayArrivedAtOffice[] = 8;
//go_sms('+380949091035', 'Старт трек!');
//print_r ($infoArray);
//print_r ($infoArray['data']);
//exit();
foreach ($infoArray['data'] as $oneTTN){
//    print_r ($oneTTN);
    if ($infoOrder = getOneString("SELECT * FROM `ls_orders` WHERE `number_declaration` = '".$oneTTN['Number']."' and `delete` = 0;")){
        if (in_array($oneTTN['StatusCode'], $arrayTrueStatusCode)){
            if (time()>strtotime($oneTTN['RecipientDateTime'])+3600){
                $msg = ' Пройшла година';


                $sql = "UPDATE `ls_orders` SET `status` = 3 WHERE `number_declaration` = '".$oneTTN['Number']."';";
                mysql_query($sql);


                //тут відправляємо смс про відгук
//                if ($infoOrder['site']!='Mamba24Ever')
                    go_sms($infoOrder['number_phone'], 'Дякуємо за покупку. Надіємось Ви оціните нашу роботу у нас на сайті!', $infoOrder['site']);
                if ($config['user_params_40']==1) {
//                    setBonus(intval($infoOrder['id']));
                }
            }
            echo $infoOrder['id'].' - Отримано!  - '.strtotime($oneTTN['RecipientDateTime']).' '.date('d-m-Y H:i', strtotime($oneTTN['RecipientDateTime'])).$msg.'<br>'."\r\n";
        } elseif(in_array($oneTTN['StatusCode'],$arrayFalseStatusCode)) {
            echo $infoOrder['id'].' - Відмова<br>'."\r\n";


//            mysql_query("UPDATE `ls_orders` SET `status` = 9 WHERE `number_declaration` = '".$oneTTN['Number']."';");



        } elseif (in_array($oneTTN['StatusCode'],$arrayArrivedAtOffice)) {
            if ($infoOrder['sendSmsAtOffice']==0){
                $dopTxt = ' - Відправляємо смс';
                go_sms($infoOrder['number_phone'], 'Доброго дня, Ваше замовлення вже чекає Вас у відділенні Нової Пошти.', $infoOrder['site']);


                mysql_query("UPDATE `ls_orders` SET `sendSmsAtOffice` = 1 WHERE `number_declaration` = '".$oneTTN['Number']."';");



            } else {
                $dopTxt = ' - Вже відправляли смс';
            }
//            echo $oneTTN['ScheduledDeliveryDate'].' - '.date('d-m-Y')."\r\n";
            if ($oneTTN['ScheduledDeliveryDate']<=date('d-m-Y')){
                $datetime1 = date_create($oneTTN['ScheduledDeliveryDate']);
                $datetime2 = date_create(date('d-m-Y'));
                $interval = date_diff($datetime1, $datetime2);
                $text = 'Посилка зберігається '.$interval->days.' дн.';
                if ($interval->days==4 and (date('G')>9 and date('G')<=18) AND $infoOrder['sms4Days']==0){


                    mysql_query("UPDATE `ls_orders` SET `sms4Days` = 1 WHERE `number_declaration` = '".$oneTTN['Number']."';");



                    go_sms($infoOrder['number_phone'],'Доброго дня, Ваше замовлення вже 4 день чекає Вас у відділенні НП, не забудьте про нього будь-ласка.', $infoOrder['site']);
                }


                mysql_query("UPDATE `ls_orders` SET `infoFromNp` = '".$text."' WHERE `number_declaration` = '".$oneTTN['Number']."';");


            }
            echo $infoOrder['id'].' - Прибув на відділення'.$dopTxt.'<br>'."\r\n";
        } else {
            echo $infoOrder['id'].' - Не отримано<br>'."\r\n";
        }
    }
}