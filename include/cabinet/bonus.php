<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/19/15
 * Time: 6:19 PM
 * To change this template use File | Settings | File Templates.
 */
$notActive = '';
$infoSumm = getOneString("SELECT SUM(suma) FROM `ls_orders` WHERE `id_user` = '".$infoUser['id']."'");
$sum = 500-$infoSumm['SUM(suma)'];
if ($infoUser['bonus']==0){
    $notActive = '
    <div class="alert alert-danger">
        <strong>Извините!</strong> Но пока для Вас бонусная программа не доступна! Для активации нужно совершить покупок на 500 грн. Вам осталось купить товара на '.$sum.' грн.
    </div>
    ';
}
$infoInBonus = getOneString("SELECT SUM(bonus) FROM `ls_bonus` where `idUser` = '".$infoUser['id']."' and `type` = 'in'");
$infoOutBonus = getOneString("SELECT SUM(bonus) FROM `ls_bonus` where `idUser` = '".$infoUser['id']."' and `type` = 'out'");
$bonus = $infoInBonus['SUM(bonus)']-$infoOutBonus['SUM(bonus)'];
$bodyCabinet = '
<div class="row">
    <div class="col-md-10">
        <h1>Бонусная програма - PICASE.COM.UA</h1>
    </div>
    <div class="col-md-2">
        <a href="http://picase.com.ua/ru/mode/content-5.html">подробнее...</a>
    </div>
</div>
'.$notActive.'
<hr>
Накоплено бонусов: '.$bonus.' грн.
';