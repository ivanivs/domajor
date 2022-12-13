<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/22/15
 * Time: 8:11 PM
 * To change this template use File | Settings | File Templates.
 */
$uniq_id_in_base = $_COOKIE['PHPSESSID'];
if (mysql_query("
INSERT INTO  `ls_orders` (
`promocode`,
`site`,
`uniq_user` ,
`id_user` ,
`pib` ,
`number_phone` ,
`email` ,
`region` ,
`city` ,
`warehouse` ,
`dop_info` ,
`status` ,
`oplata` ,
`time` ,
`mobile`,
`dostavka`,
`adress`,
`notCallBack`
)
VALUES (
'".mysql_real_escape_string($_POST['promocode'])."',
'kombat.in.ua',
'".$uniq_id_in_base."',
'".$infoUser['id']."',
'".mysql_real_escape_string($_POST['pib'])."',
'".mysql_real_escape_string($_POST['phoneOrder'])."',
'".mysql_real_escape_string($_POST['email'])."',
'".mysql_real_escape_string($_POST['region'])."' ,
'".mysql_real_escape_string($_POST['city'])."' ,
'".mysql_real_escape_string($_POST['warehouse'])."' ,
'".mysql_real_escape_string($_POST['dop_info'])."' ,
'0' ,
'".intval($_POST['oplata'])."' ,
'".time()."' ,
'".MOBILEVER."',
'".intval($_POST['dostavka'])."',
'".mysql_real_escape_string($_POST['adress'])."',
'".intval($_POST['notCallBack'])."'
);
")){
    $idOrder = mysql_insert_id();
    require ('class/Telegram.php');
    Telegram::SendNewOrderSite($idOrder);
    $sql = "SELECT * FROM `ls_discounts` WHERE `code` = '".mysql_real_escape_string($_POST['promocode'])."' AND `status` = 1 AND `date` >= '".date("Y-m-d")."';";
    if ($infoCode = getOneString($sql)){
        $promocode = $infoCode['code'];
        if ($arrayCodeDb = getArray("SELECT * FROM `ls_discountsCode` WHERE `discountId` = '".$infoCode['id']."'")){
            $arrayCode = Array();
            foreach ($arrayCodeDb as $oneCode){
                $arrayCode[] = $oneCode['itemId'];
            }
        }
    }
    if ($arrayItems = getArray("SELECT * FROM `ls_cart` where `uniq_user` = '".$uniq_id_in_base."' and status <> 1;")){
        $allPrice = 0;
        $online = 1;
        $itemsToGTag = Array();
        $stopOnlinePay = 0;
        foreach ($arrayItems as $v){
            $infoItem = getOneString("SELECT * FROM `ls_items` WHERE `id` = '".$v['id_item']."';");
            if ($infoItem['select_6']!='30')
                $online = 0;
            mysql_query("UPDATE `ls_cart` SET `status` = 1 WHERE `id` = '".$v['id']."';");
            if ($infoItem['price_2']!=0){
                $infoItem['price_1'] = $infoItem['price_2'];
            }
            if ($v['price']!='0.00'){
                $infoItem['price_1'] = $v['price'];
            }
            if (in_array($infoItem['id'], $arrayCode) or (empty($arrayCode) and isset ($infoCode) and !empty($infoCode))){
                $infoItem['price_1'] = ceil($infoItem['price_1']-($infoItem['price_1']/100*$infoCode['percent']));
            }
            $allPrice += $infoItem['price_1']*$v['number'];
            $itemsToGTag[] = '{
                          \'id\': \''.$infoItem['id'].'\',
                          \'google_business_vertical\': \'retail\'
                        }';
            if ($infoItem['select_5']==77 or $infoItem['select_5']==78){
                $stopOnlinePay = 1;
            }
        }
        $infoRegion = getOneString("SELECT * FROM `ls_region` WHERE `id` = '".intval($_POST['region'])."';");
        $infoCity = getOneString("SELECT * FROM `ls_cities` WHERE `id` = '".intval($_POST['city'])."';");
        $infoWarehouse = getOneString("SELECT * FROM `ls_warehouses` WHERE `id` = '".intval($_POST['warehouse'])."';");
//        $oplata = '
//            <div class="alert alert-success">
//                <strong>Дякуємо!</strong> Ваше замовлення прийнято!
//            </div>
//            ';
        if ($arrayCertUse = getArray("SELECT * FROM `ls_cartCertificate` WHERE `uniq_user` = '".$_COOKIE['PHPSESSID']."' AND `used` = 0")){
            foreach ($arrayCertUse as $oneCert){
//                $useCert .= '<tr>
//                    <td>'.getOneString("SELECT * FROM `ls_certificate` WHERE `id` = '".$oneCert['codeId']."';")['code'].'</td>
//                    <td>-'.$oneCert['price'].' грн.</td>
//                </tr>';
                if ($infoGlobalCode = getOneString("SELECT * FROM `ls_certificate` WHERE `id` = '".$oneCert['codeId']."'")){
                    mysql_query("UPDATE `ls_cartCertificate` SET `used` = 1 WHERE `id` = '".$oneCert['id']."';");
                    if ($infoGlobalCode['used']==0){
                        mysql_query("UPDATE `ls_certificate` SET `used` = 1, `orderId` = '".$idOrder."' WHERE `id` = '".$infoGlobalCode['id']."';");
                        $allPrice = $allPrice - $oneCert['price'];
                    }
                }
            }
        }
        if (intval($_POST['dostavka'])==2){
            $onlyNP = 'Відділення: <strong>'.$infoWarehouse['nameRu'].'</strong>';
        } else {
            $onlyNP = 'Адреса: <strong>'.htmlspecialchars($_POST['adress']).'</strong>';
        }
//        if ($config['user_params_40']==1){
//            require ('liqpay/LiqPay.php');
//            $liqpay = new LiqPay('i81386738015', 'oT01zWTShzV9gwaZFE4EkuwQj3LyLELMO4g5e2oj');
//            $htmlPay = $liqpay->cnb_form(array(
//                'action' => 'pay',
//                'language' => 'uk',
//                'version' => '3',
//                'amount' => $allPrice,
//                'currency' => 'UAH',
//                'result_url' => $config['site_url'].'ru/success.html',
//                'server_url' => $config['site_url'].'index.php?mode=ajax&ajax=checkoutLiqpay',
//                'description' => 'Оплата замовлення №'.$idOrder.' на '.$config['user_params_28'],
//                'order_id' => $idOrder
//            ));
//            if ($stopOnlinePay==0) {
//                $onlinePayBlock = '
//            <div class="row">
//                <div class="col-md-6 offset-md-3">
//                    <h3 style="text-align: center;">Ви можете оплатити замовлення Online</h3>
//                    <div style="text-align: center;">' . $htmlPay . '</div>
//                </div>
//            </div>
//            ';
//            } else {
//                $onlinePayBlock = '';
//            }
//        }
        if (intval($_POST['notCallBack'])==1){
            $oplata = '
            <div class="alert alert-success mt-2 mb-2">
                Дякуємо! Ваше замовлення прийнято і в найближчий робочий час буде відправлено. Номер посилки скинемо в СМС на мобільний отримувача. Гарного дня!
            </div>
            ';
        } else {
            $oplata = '
            <div class="alert alert-success  mt-2 mb-2">
                Дякуємо! Ваше замовлення прийнято. В найближчий робочий час з Вами зв’яжеться наш менеджер для уточнення деталей замовлення. Гарного дня!
            </div>
            ';
        }
        echo '
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h3>Замовлення №'.$idOrder.'</h3>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td>Отримувач:</td>
                            <td>'.htmlspecialchars($_POST['pib']).'</td>
                        </tr>
                        <tr>
                            <td>Адреса доставки:</td>
                            <td>Область: <strong>'.$infoRegion['name'].'</strong><br>Місто: <strong>'.$infoCity['name'].'</strong><br>Відділення: <strong>'.$infoWarehouse['name'].'</strong></td>
                        </tr>
                        <tr>
                            <td>Сума замовлення:</td>
                            <td><strong>'.$allPrice.' грн.</strong> + послуги доставки</td>
                        </tr>
                    </table>
                </div>
            </div>
            '.$oplata.'
            '.$onlinePayBlock.'
            
            
            <!-- END GCR Language Code -->
        </div>
        
        ';
        /*
                <script>

                window.___gcfg = {
                    lang: "ru"
                };
                </script>
            <script>
                $( document ).ready(function() {
                    gtag(\'event\', \'purchase\', {
                        \'send_to\': \'AW-462132080\',
                        \'value\': \''.$allPrice.'\',
                        \'items\': ['.implode(',',$itemsToGTag).']
                      });
                });

            </script>
            <!-- BEGIN GCR Opt-in Module Code -->

            <script src="https://apis.google.com/js/platform.js?onload=renderOptIn"

            async defer>

            </script>

            <script>

            window.renderOptIn = function() {

            window.gapi.load(\'surveyoptin\', function() {

            window.gapi.surveyoptin.render(

            {

            "merchant_id": 292634070,

            "order_id": "'.$idOrder.'",

            "email": "'.mysql_real_escape_string($_POST['email']).'",

            "delivery_country": "UA",

            "estimated_delivery_date": "'.date("Y-m-d", strtotime("+2 days")).'",

            "opt_in_style": "BOTTOM_LEFT_DIALOG"

            });

            });

            }

            </script>

            <!-- END GCR Opt-in Module Code -->
            <!-- BEGIN GCR Language Code -->

                <!-- END GCR Language Code -->
            <!-- BEGIN GCR Language Code -->

            <script>

            window.___gcfg = {

            lang: \'en_US\'

            };

            </script>*/
        session_regenerate_id();
        /*
         * <div class="row">
            <div class="col-md-6 col-md-offset-3">
                '.$oplata.'
            </div>
        </div>
         */
        if ($config['user_params_5'])
        {
//            send_sms (0);
        }
        if (!empty($_POST['email']) and intval($_POST['oplata'])==2){
//            mail ($_POST['email'], 'Замовлення KOMBAT.IN.UA', "Дякуємо!\r\nВаше замовлення №".$idOrder." на суму ".$allPrice."грн.\r\nПриватБанк: ".$config['user_params_36']." ".$config['user_params_37']."\r\nВідправка щодня о 15 год.");
//            go_sms ($_POST['phoneOrder'], "Дякуємо! Ваше замовлення №".$idOrder." на суму ".$allPrice."грн ПриватБанк: ".$config['user_params_36']." ".$config['user_params_37']);
        } elseif (intval($_POST['oplata'])==2) {
//            go_sms ($_POST['phoneOrder'], "Дякуємо! Ваше замовлення №".$idOrder." на суму ".$allPrice."грн ПриватБанк: ".$config['user_params_36']." ".$config['user_params_37']);
        } elseif (!empty($_POST['email'])) {
//            mail ($_POST['email'], 'Замовлення KOMBAT.IN.UA', "Дякуємо!\r\nВаше замовлення №".$idOrder." на суму ".$allPrice."грн. на стадії обробки\r\nЙого буде реалізовано після його підтвердження менеджеру інетрнет-магазину. Очікуйте на телефонний дзвінок.\r\nwww.KOMBAT.IN.UA");
        }
        $notCallBack = intval($_POST['notCallBack']);
        sms_new_order(str_replace (' ', '', $_POST['phoneOrder']), $idOrder, $notCallBack);
        if (!empty($_POST['email'])){
            mail ($_POST['email'], 'Замовлення KOMBAT.IN.UA',"Дякуємо! Замовлення №".$idOrder." на суму ".$allPrice."грн. прийнято.\r\nВ найближчий робочий час, з Вами зв’яжеться наш менеджер. Слідкувати за статусом замовлення можна також в особистому кабінеті у нас на сайті. \r\nwww.kombat.in.ua");
        }
        mail($config['user_params_22'], 'New order', 'Link to order: '.$config['site_url'].'/admin/list_item.php?uniq='.$uniq_id_in_base.'&id='.$idOrder);
    }
} else {
    echo '
    <div class="alert alert-danger" style="margin: 20px 0px;">
        <strong>Ошибка!</strong> К сожалению заказ не удалось оформить. Попробуйте еще раз.
    </div>
    ';
}