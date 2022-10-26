<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/23/15
 * Time: 12:01 PM
 * To change this template use File | Settings | File Templates.
 */
$infoOrder = getOneString("SELECT * FROM `ls_orders` WHERE `id` = '".intval($_GET['id'])."';");
$result = json_decode($infoOrder['result'], true);
if ($result['status']=='success'){
    $text = '<h1>Ваш заказ успешно оплачен</h1>
    <div class="alert alert-success">
        <strong>Спасибо за оплату!</strong> Наши менеджеры приступают к испонению заказа. Статус заказа можно отследить на странице <a href="http://picase.com.ua/ru/orders.html">Мои заказы</a>
    </div>
    ';
} else {
    $text = '
    <h1>Платеж на стадии обработки</h1>
    <div class="alert alert-info">
        <strong>Спасибо за оплату!</strong> После автоматического подтверждения оплаты наши менеджеры приступят к испонению. Статус заказа можно отследить на странице <a href="http://picase.com.ua/ru/orders.html">Мои заказы</a>
    </div>
    ';
}
$body = '
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            '.$text.'
        </div>
    </div>
</div>
';