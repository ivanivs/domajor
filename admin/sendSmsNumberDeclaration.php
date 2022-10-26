<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 1/13/14
 * Time: 5:31 PM
 * To change this template use File | Settings | File Templates.
 */
require ('./../config.php');
require ('./../include/functions.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8'");
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
if (isset ($_COOKIE['accessLevel']) and $_COOKIE['accessLevel']==100){
    $sms = "Ваше замовлення уже в дорозі! ТТН: ".$_POST['declarationNumber']."%0Aотримувати: ".$_POST['declarationShipDate'];
    go_sms ($_POST['declarationPhone'], $sms);
    $tmp = '{
            "apiKey": "'.$config['user_params_38'].'",
            "modelName": "TrackingDocument",
            "calledMethod": "getStatusDocuments",
            "methodProperties": {
                "Documents": [
                    {
                        "DocumentNumber": "'.$_POST['declarationNumber'].'",
                        "Phone":"'.$_POST['declarationPhone'].'"
                    }
                ]
            }
        }';
    $ch = curl_init('https://api.novaposhta.ua/v2.0/json/');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $tmp);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    $infoArray = json_decode($result, true);
    $infoWarehouse = getOneString("SELECT * FROM `ls_warehouses` WHERE `Ref` = '".$infoArray['data'][0]['WarehouseRecipientInternetAddressRef']."'");
//        print_r ($infoWarehouse);
    $infoCity = getOneString("SELECT * FROM `ls_cities` WHERE `id` = '".$infoWarehouse['cityId']."';");
//        print_r ($infoCity);
    $sql = "
            INSERT INTO
                `ls_orders`
            (
                `pib`,
                `site`,
                `uniq_user`,
                `number_phone`,
                `ship_date`,
                `number_declaration`,
                `status`,
                `time`,
                `warehouse`,
                `region`,
                `city`
                ) VALUES (
                '".mysql_real_escape_string($infoArray['data'][0]['RecipientFullName'])."',
                '".mysql_real_escape_string($_POST['site'])."',
                '".md5(time())."',
                '".mysql_real_escape_string($_POST['declarationPhone'])."',
                '".mysql_real_escape_string($_POST['declarationShipDate'])."',
                '".mysql_real_escape_string($_POST['declarationNumber'])."',
                '2',
                '".time()."',
                '".$infoWarehouse['id']."',
                '".$infoCity['regionId']."',
                '".$infoCity['id']."'
                 )";
    if (mysql_query($sql)){
        $addText = '<div class="alert alert-success" style="margin-top: 10px;">
            Замовлення успішно створено №'.mysql_insert_id().'! Статус відправки: '.$infoArray['data'][0]['Status'].', Очікувана дата доставки: '.$infoArray['data'][0]['ScheduledDeliveryDate'].'
            </div>';
    }
    echo '<div class="alert alert-success"><b>Поздравляем!</b> СМС успешно отправлено!</div>'.$addText;
}