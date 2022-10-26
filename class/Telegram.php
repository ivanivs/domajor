<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 2/22/20
 * Time: 2:05 PM
 * To change this template use File | Settings | File Templates.
 */
class Telegram{
    public function GetToken (){
        global $config;
        return($config['tlgrm']['token']);
    }
    public function SendNewOrderSite($orderId, $oneClick = 0){
        $v = getOneString("SELECT * FROM `ls_orders` WHERE `id` = '".$orderId."'");
//        $userOrder = getOneUser($v['userId']);
        $nameOrder = "<b>Нове замолення ".(($oneClick==1) ? 'в один клік ' : '')."з сайту ".$v['site'].":</b>\r\n".'#' . $v['id'] . ' від ' . date('d-m-Y', $v['time']) . ' ['.$v['pib'].' '.$v['number_phone'].']';
        if ($arrayUser = getArray("SELECT * FROM `ls_users` WHERE `accesslevel` = 100 AND `chatId` != ''")){
            foreach ($arrayUser as $v){
                Telegram::SendTextToUser($v['chatId'], $nameOrder);
            }
        }
    }
    public function SendTextToAdmin($text){
//        $userOrder = getOneUser($v['userId']);
        if ($arrayUser = getArray("SELECT * FROM `ls_users` WHERE `accesslevel` = 100 AND `chatId` != ''")){
            foreach ($arrayUser as $v){
                Telegram::SendTextToUser($v['chatId'], $text);
            }
        }
    }
    public function SendNewOrder($orderId){
        $v = getOneString("SELECT * FROM `b2b_order` WHERE `id` = '".$orderId."'");
        $userOrder = getOneUser($v['userId']);
        $nameOrder = "<b>Нове замолення:</b>\r\n".'#' . $v['id'] . ' від ' . date('d-m-Y', strtotime($v['date'])) . ' ['.$userOrder['name'].' '.$userOrder['surName'].']'." на суму ".getSummOrder($v['id'])."\r\n".((!empty($v['comment'])) ? "<b>Коментар від клієнта</b>:\r\n".$v['comment'] : '');
        if ($arrayUser = getArray("SELECT * FROM `ls_users` WHERE `accesslevel` = 100 AND `chatId` != ''")){
            foreach ($arrayUser as $v){
                Telegram::SendTextToUser($v['chatId'], $nameOrder);
            }
        }
    }
    public function SendTextToUserByUserId($userId, $text){
        $infoUser = getOneString("SELECT * FROM `ls_users` WHERE `id` = '".$userId."';");
        if (!empty($infoUser['chatId'])){
            Telegram::SendTextToUser($infoUser['chatId'], $text);
        }
    }
    public function SendTextToUser($chat_id, $text, $reply_markup = ''){
        $arrayPost['chat_id'] = $chat_id;
        $arrayPost['parse_mode'] = 'html';
        $arrayPost['text'] = $text;
        if (!empty($reply_markup)){
            $arrayPost['reply_markup'] = $reply_markup;
        }
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL,
            'https://api.telegram.org/bot'.Telegram::GetToken().'/sendMessage');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayPost));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $result=curl_exec($ch);
        $resultArray = json_decode($result, true);
        curl_close($ch);
    }
}
function getOneUser($id){
    return (getOneString("SELECT * FROM `ls_users` where `id` = '".$id."';"));
}
function getUserName($id, $empty=0, $onlyName = false){
    $user = getOneUser($id);
    if ($empty==0) {
        $return = '#' . $user['id'] . ' ' . $user['surName'] . ' ' . $user['name'];
        if (ISADMIN)
            $return = '<a href="/user/edit/' . $id . '" target="_blank">' . $return . '</a>';
        return ($return);
    } else {
        if ($onlyName==false){
            return ($user['surName'] . ' ' . $user['name']);
        } else {
            return ($user['name']);
        }
    }
}