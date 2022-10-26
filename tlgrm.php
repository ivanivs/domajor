<?php
require ('config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']) or die ("ERROR");
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8';");
require ('include/functions.php');
require ('class/Telegram.php');
if (isset ($_GET['token']) and $_GET['token']=='320256419'){
    $datas = file_get_contents("php://input");
    file_put_contents('tmp.txt', $datas);
    $arrDatas = json_decode($datas, true);
    if (!isset ($arrDatas['callback_query'])) {
        $chatId = $arrDatas['message']['chat']['id'];
        $text = $arrDatas['message']['text'];
    } else {
        $chatId = $arrDatas['callback_query']['message']['chat']['id'];
        $text = $arrDatas['callback_query']['message']['text'];
    }
    if ($infoUser = getOneString("SELECT * FROM `ls_users` WHERE `chatId` = '".$chatId."'")){
        define('AUTH', 1);
        $infoUser['accesslevel']==100 ? define("ISADMIN", 1) : define("ISADMIN", 0);
        define('USERID', $infoUser['id']);
        $user = $infoUser;
    } else {
        define('AUTH', 0);
    }
//    $arrayFunction = array();
//    $arrayFunction[0]['method'] = 'orders';
//    $arrayFunction[0]['buttonText'] = 'Поточні замовлення';
//    $arrayFunction[1]['method'] = 'invoice';
//    $arrayFunction[1]['buttonText'] = 'Неоплачені рахунки';
//    if (ISADMIN) {
//        $arrayFunction[100]['method'] = 'newOrders';
//        $arrayFunction[100]['buttonText'] = 'Нові замовлення';
//    }
    $inlineKeyBoard = array();
    if (is_array($arrayFunction)) {
        foreach ($arrayFunction as $oneFunc) {
            if (isset ($oneFunc['method'])) {
                $inlineKeyBoard[] = array(array("text" => $oneFunc['buttonText'], "callback_data" => $oneFunc['method']));
            } elseif (isset ($oneFunc['url'])) {
                $inlineKeyBoard[] = array(array("text" => $oneFunc['buttonText'], "url" => $oneFunc['url']));
            }
        }
    }
    //                            $inlineKeyBoard[] = Array("text" => "Дай новий пароль", "callback_data" => "recoveryPassword");
    $keyb = array("inline_keyboard" => $inlineKeyBoard);
    if (AUTH==0){
        if ($text=='/start'){
            Telegram::SendTextToUser($chatId, 'Доброго дня! Давайте авторизуємо Вас, для цього мені потрібен код, котрий Ви бачите на екрані у системі ' . $config['site_name']);
        } else {
            if ($infoCode = getOneString("SELECT * FROM `ls_users` WHERE `uniqCode` = '" . mysql_real_escape_string($text) . "'")){
                mysql_query("UPDATE `ls_users` SET `chatId` = '".$chatId."' WHERE `id` = '".$infoCode['id']."'");
                Telegram::SendTextToUser($chatId, getUserName($infoCode['id'],1).' вітаємо Вас. Тепер сюди я буду надсилати корисну інформацію саме для Вас. Якщо я знадоблюсь, просто напишіть будь-що мені, і я спробую допомогти Вам.');
            } else {
                Telegram::SendTextToUser($chatId, 'Перепрошую, але нажаль код не вірний, можливо він вже встиг змінитись, нажаль він дійсний лише декілька хвилин. Натисніть оновити сторінку, та почніть спочатку. Дякую.');
            }
        }
    } else {
        if (isset ($arrDatas['callback_query'])){
            switch ($arrDatas['callback_query']['data']){
                case "newOrders":
//                    if (ISADMIN){
//                        if ($arrayOrders = getArray("SELECT * FROM `b2b_order` WHERE `status` = 0 ORDER by `id` DESC")){
//                            $nameOrder = '';
//                            foreach ($arrayOrders as $v){
//                                $userOrder = getOneUser($v['userId']);
//                                $nameOrder .= '#' . $v['id'] . ' від ' . date('d-m-Y', strtotime($v['date'])) . ' ['.$userOrder['name'].' '.$userOrder['surName'].']'." в статусі ".getOrderStatus($v['status'])."\r\n";
//                            }
//                            Telegram::SendTextToUser($chatId, "<b>Ваші замовлення:</b> \r\n".$nameOrder, $keyb);
//                        }
//                    } else {
//                        Telegram::SendTextToUser($chatId, getUserName($infoUser['id']) . ' вітаю Вас. Чим можу допомогти?', $keyb);
//                    }
                    break;
                case "orders":
//                    if ($arrayOrders = getArray("SELECT * FROM `b2b_order` WHERE `userId` = '".USERID."' AND `status` != -1")){
//                        $nameOrder = '';
//                        foreach ($arrayOrders as $v){
//                            $nameOrder .= '#' . $v['id'] . ' від ' . date('d-m-Y', strtotime($v['date'])) . ' ['.$user['name'].' '.$user['surName'].']'." в статусі ".getOrderStatus($v['status'])."\r\n";
//                        }
//                        Telegram::SendTextToUser($chatId, "<b>Ваші замовлення:</b> \r\n".$nameOrder, $keyb);
//                    }
                    break;
                case "invoice":
//                    if ($arrayInvoiceOut = getArray("SELECT * FROM `b2b_invoiceOut` WHERE `active` = 1 AND `status` = 'notPaid' AND `userId` = '".USERID."'")){
//                        $invoiceOut = '';
//                        foreach ($arrayInvoiceOut as $v){
//                            $invoiceOut .= getNameInvoiceOut($v).' від '.date("d.m.Y", strtotime($v['timeCreate'])).' на '.getInvoiceSumm($v['id'])."\r\n";
//                        }
//                        Telegram::SendTextToUser($chatId, "<b>Ваші неоплачені рахунки:</b> \r\n".$invoiceOut, $keyb);
//                    } else {
//                        Telegram::SendTextToUser($chatId, getUserName($infoUser['id'],true, true) . ', у Вас все добре, неоплачених рахунків немає :-)', $keyb);
//                    }
                    break;
                default:
                    Telegram::SendTextToUser($chatId, getUserName($infoUser['id'], true, true) . ', перепрошую, але я цього, ще не вмію :-(', $keyb);
                    break;
            }
        } else {
            Telegram::SendTextToUser($chatId, getUserName($infoUser['id']) . ' вітаю Вас. Чим можу допомогти?', $keyb);
        }
    }
}