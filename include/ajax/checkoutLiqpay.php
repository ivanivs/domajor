<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 3/6/19
 * Time: 8:53 AM
 * To change this template use File | Settings | File Templates.
 */
if (isset ($_POST['data'])){
    $data = json_decode(base64_decode($_POST['data']), true);
    $infoOrder = getOneString("SELECT * FROM `ls_orders` WHERE `id` = '".$data['order_id']."';");
    if ($data['status']=='success'){
//        send_sms();
//        sms_new_order_pay($infoOrder['number_phone'], $infoOrder['id']);
        mysql_query("UPDATE `ls_orders` SET `status` = '4', `onlinePaySuccess` = 1, `liqpayData` = '".mysql_real_escape_string(json_encode($data))."' WHERE `id` = '".$infoOrder['id']."';");
        if ($arrayItem = getArray("SELECT * FROM `ls_cart` where `uniq_user` = '".$infoOrder['uniq_user']."'")) {
            $arrayCode = array();
            $arrayCodeText = array();
            foreach ($arrayItem as $v) {
                if (intval($_GET['status']) == 4) {
                    if ($infoCert = getOneString("SELECT * FROM `ls_certificate` WHERE `itemId` = '" . $v['id_item'] . "'")) {
                        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRSTUVWXYZ';
// Output: 54esmdr0qf
                        $i = 1;
                        while ($i <= $v['number']) {
                            $code = substr(str_shuffle($permitted_chars), 0, 8);
                            if (!getOneString("SELECT * FROM `ls_certificate` WHERE `code` = '" . $code . "'")) {
                                $arrayCode[$i]['code'] = $code;
                                $arrayCode[$i]['price'] = $infoCert['price'];
                                $arrayCodeText[] = $code . ' - ' . $infoCert['price'].'грн.';
                                $i++;
                            }
                        }
                    }
                }
            }
        }
        if (!$info = getOneString("SELECT * FROM `ls_certificate` WHERE `fromOrder` = '".$infoOrder['id']."'")) {
            if (isset ($arrayCode) and !empty($arrayCode)) {
                foreach ($arrayCode as $code) {
                    mysql_query("INSERT INTO `ls_certificate` (
                `dateTo`,
                `price`, 
                `code`, 
                `date`,
                `fromOrder`,
                `automatic`
                ) VALUES (
                '1970-01-01',
                '" . $code['price'] . "', 
                '" . $code['code'] . "', 
                '" . date("Y-m-d") . "',
                '" . $infoOrder['id'] . "',
                1
                );");
                }
                $codeToSms = 'Ваш подарунковий сертифікат на покупку у www.bobas.ua: ' . implode(', ', $arrayCodeText);
                go_sms($infoOrder['number_phone'], $codeToSms);
            }
        }
    } else {
        mysql_query("UPDATE `ls_orders` SET `status` = '4', `liqpayData` = '".mysql_real_escape_string(json_encode($data))."' WHERE `id` = '".$infoOrder['id']."';");
    }
    mysql_query("
    INSERT INTO `ls_liqpayStatus` (
      `orderId`,
      `status`,
      `data`,
      `time`) VALUES (
      '".$infoOrder['id']."',
      '".$data['status']."',
      '".mysql_real_escape_string(json_encode($data))."',
      '".time()."');
    ");
}