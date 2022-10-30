<?php
session_start();
require('../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query("SET NAMES 'UTF8';");
require('../include/functions.php');
//$data = file_get_contents('https://new.domajor.com.ua/srv/jamParcer.php?id=24565');
$srv = '193.193.194.28';
$port = '5839';
$ftp_user_name = 'export';
$ftp_user_pass = 'pSnko92m';
//echo "ftp://export:pSnko92m@193.193.194.28:5839/export/export.xml';
$page = file_get_contents("ftp://" . $ftp_user_name . ':' . $ftp_user_pass . '@' . $srv . ':5839' . '/export/export.xml');
$fp = fopen('files/jam.xml', 'w+');
if (fwrite($fp, $page)) {
    echo "Файл успешно скачан и сохранен.\r\n";
    $dataArray = file_get_contents('files/jam.xml');
    $data = new SimpleXMLElement($dataArray);
    $dataJson = json_encode($data);
    $dataArrayContent = json_decode($dataJson, true);
//print_r ($dataArrayContent);
    $new = 0;
    $update = 0;
    mysql_query("UPDATE `ls_items` SET `text_7` = 0 WHERE `select_4` = '1'");
    foreach ($dataArrayContent['item'] as $oneItem) {
        $stock = 0;
        switch ($oneItem['availability']) {
            case "уточняйте":
                $stock = 1;
                break;
            case "в магазине":
                $stock = 1;
                break;
            case "на складе":
                $stock = 1;
                break;
            case "нет":
                $stock = 0;
                break;
            default:
                $stock = 0;
                break;
        }

        if ($infoItem = getOneString("SELECT * FROM `ls_items` WHERE `select_4` = '1' AND `text_3` = '" . $oneItem['code'] . "'")) {
            mysql_query("UPDATE `ls_items` SET `price_1` = '" . mysql_real_escape_string($oneItem['price']) . "', `price_2` = '" . mysql_real_escape_string($oneItem['acc']) . "', `text_4` = '" . $stock . "' WHERE `id` = '" . $infoItem['id'] . "';");
            $update++;
        } else {
            $body = '';
            $data = file_get_contents('https://new.domajor.com.ua/srv/jamParcer.php?id='.$oneItem['code']);
            if ($data!='error'){
                $dataArray = json_decode($data,true);
                $body = $dataArray['body'];
            }
            mysql_query("
                INSERT INTO `ls_items` (
                `id_card`,
                `time`,
                `searchField`,
                `status`,
                `price_1`,
                `text_4`,
                `select_4`,
                `text_3`,
                `text_1`,
                `text_5`,
                `text_2`
                ) VALUES (
                '1',
                '" . time() . "',
                '" . mysql_real_escape_string($oneItem['article']) . " " . mysql_real_escape_string($oneItem['manufacturer']) . "',
                '1',
                '" . mysql_real_escape_string($oneItem['price']) . "',
                '" . $stock . "',
                '1',
                '" . mysql_real_escape_string($oneItem['code']) . "',
                '" . mysql_real_escape_string($oneItem['manufacturer']) . " " . mysql_real_escape_string($oneItem['article']) . "',
                '".mysql_real_escape_string($oneItem['article'])."',
                '".mysql_real_escape_string($body)."'
                )
                ");
            $idItem = mysql_insert_id();
            if ($data!='error'){
                $dataArray = json_decode($data,true);
                if (!empty($dataArray['img'])){
                    foreach ($dataArray as $v){
                        $imgNameTmp = explode('/', $v);
                        $imgName = $imgNameTmp[count($imgNameTmp)-1];
                        $file_name = time().rand(0,100).'_'.$imgName;
                        copy($v, '../upload/userparams/'.$file_name);
                        mysql_query ("
                            INSERT INTO  `ls_values_image` (
                            `id_item` ,
                            `id_cardparam` ,
                            `value`
                            )
                            VALUES (
                            '".$idItem."',
                            '1' ,
                            '".$file_name."'
                            );
                            ");
                    }
                }
            }
            $new++;
            exit();
        }
    }
    echo "\r\nОновили: " . $update . "\r\nНових:" . $new;
} else {
    echo "ERROR";
}