<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/23/15
 * Time: 12:21 PM
 * To change this template use File | Settings | File Templates.
 */
if ($arrayOrders = getArray("SELECT * FROM `ls_orders` WHERE `id_user` = '".$infoUser['id']."'")){
    $bodyCabinet = '
    <h1>Ваші замовлення</h1>
    <table class="table table-bordered table-striped">
    <thead>
        <th>ID</th>
        <th>Найменування</th>
        <th>Сума</th>
        <th>Дата</th>
        <th>Статус</th>
        <th>Номер декларації</th>
    </thead>
    <tbody>
    ';
    foreach ($arrayOrders as $v){
        $infoBonus = getOneString("SELECT * FROM `ls_bonus` WHERE `idOrder` = '".$v['id']."'");
//        $htmlItem = '';
//        $suma = 0;
//        if ($arrayItem = getArray("SELECT `ls_cart`.`price` as `cartPrice`, `ls_cart`.`number` as `numberItem`, `ls_items`.* FROM `ls_cart` JOIN `ls_items` ON `ls_items`.`id` = `ls_cart`.`id_item` WHERE `ls_cart`.`uniq_user` = '".$v['uniq_user']."';")){
//            foreach ($arrayItem as $oneItem){
//                $htmlItem .= '
//                <div class="row">
//                    <div class="col-md-10">
//                        <small><a href="'.getItemLink($oneItem).'" target="_blank">'.$oneItem['text_1'].'</a></small>
//                    </div>
//                    <div class="col-md-2">
//                        <small>'.$oneItem['numberItem'].'шт.</small>
//                    </div>
//                </div>
//                ';
//                $suma += $oneItem['cartPrice']*$oneItem['numberItem'];
//            }
//        }
        if ($infoBonus['bonus']==0){
            $bonus = '<span class="label label-info">Ще не зараховані</span>';
        } else {
            $bonus = '<strong>'.$infoBonus['bonus'].' грн.</strong>';
        }
        switch ($v['status']){
            case 0:
                $status = '<span class="label label-success">Нове замовлення</span>';
                break;
            case 1:
                $status = '<span class="label label-success">Підтвердження</span>';
                break;
            case 2:
                $status = '<span class="label label-success">Відправлено</span>';
                break;
            case 3:
                $status = '<span class="label label-default">Виконано</span>';
                break;
            case 4:
                $status = '<span class="label label-success">Оплаченоspan>';
                break;
        }
        if (strlen($v['number_declaration'])==0 and ($v['status']==0 or $v['status']==4 or $v['status']==2)){
            $number_declaration = '<span class="label label-default">Ще не відправлено</span>';
        } else {
            $number_declaration = $v['number_declaration'];
        }
        $allPrice = 0;
        $arrayItems = getArray("SELECT * FROM `ls_cart` where `uniq_user` = '".$v['uniq_user']."';");
        foreach ($arrayItems as $oneItem)
        {
            $infoItem = mysql_fetch_array (mysql_query("SELECT * FROM `ls_items` where `id` = '".$oneItem['id_item']."';"), MYSQL_ASSOC);
            if ($infoItem['price_2']!=0){
                $allPrice += $infoItem['price_2']*$oneItem['number'];
            } else {
                $allPrice += $infoItem['price_1']*$oneItem['number'];
            }
        }
        $bodyCabinet .= '
        <tr>
            <td>'.$v['id'].'</td>
            <td style="text-align: center;"><a href="'.$config ['site_url'].'status.php?uniq='.$v['uniq_user'].'" target="_blank">дивитись</a></td>
            <td>'.$allPrice.' грн.</td>
            <td>'.date('d.m.Y H:i', $v['time']).'</td>
            <td>'.$status.'</td>
            <td>'.$number_declaration.'</td>
        </tr>
        ';
    }
    $bodyCabinet .= '
    </tbody>
    </table>
    ';

} else {
    $bodyCabinet = '
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-danger">
                <strong>Нажаль у Вас, ще немає замовлень</strong>
            </div>
        </div>
    </div>
    ';
}