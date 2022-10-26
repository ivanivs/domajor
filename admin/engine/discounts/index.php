<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 4/8/20
 * Time: 2:10 PM
 * To change this template use File | Settings | File Templates.
 */
if (isset ($_GET['disable'])){
    mysql_query("UPDATE `ls_discounts` SET `status` = 0 where `id` = '".intval($_GET['disable'])."';");
}
if (isset ($_GET['enable'])){
    mysql_query("UPDATE `ls_discounts` SET `status` = 1 where `id` = '".intval($_GET['enable'])."';");
}
$body_admin .= '
<h3>Генерація кодів на знижки</h3>
'.((!isset ($_GET['discounts'])) ? '<a href="index.php?do=discounts&discounts=new" class="btn btn-success">Новий код на знижки</a>' : '').'
<div style="text-align: center;">
    <a href="index.php?do=discounts">Знижки</a> 
    <a href="index.php?do=discounts&discounts=certificate">Подарункові сертифікати</a>
</div>
';
if (!isset ($_GET['discounts'])){
    $body_admin .= '
    <h3>Список знижок</h3>
    <table class="table table-bordered table-stiped">
    <thead>
        <th>Назва</th>
        <th>КОД</th>
        <th>Використаних</th>
        <th>Дійні до</th>
        <th>Товари</th>
        <th>Список товарів</th>
        <th>Статус</th>
    </thead>
    ';
    if ($array = getArray("SELECT * FROM `ls_discounts` ORDER by `id` DESC")){
        foreach ($array as $v){
            $infoCodeUse = getOneString("SELECT COUNT(id) FROM `ls_discountsCode` WHERE `discountId` = '".$v['id']."'");
            if ($v['status']==1){
                $status = '<a href="index.php?do=discounts&disable='.$v['id'].'" style="color: red;">Вимкнути</a>';
            } else {
                $dopClass = ' error';
                $status = '<a href="index.php?do=discounts&enable='.$v['id'].'" style="color: green;">Увімкнути</a>';
            }
            $body_admin .= '
            <tr class="'.$dopClass.'">
                <td>'.$v['name'].'</td>
                <td><strong>'.$v['code'].'</strong></td>
                <td>0</td>
                <td>'.$v['date'].'</td>
                <td>'.$infoCodeUse['COUNT(id)'].'</td>
                <td><a href="index.php?do=discounts&discounts=items&id='.$v['id'].'">Список товарів</a></td>
                <td>'.$status.'</td>
            </tr>
            ';
        }
    }
    $body_admin .= '
    </table>
    ';
} else {
    switch ($_GET['discounts']){
        case "items":
            require ('engine/discounts/files/items.php');
            break;
        case "new":
            require ('engine/discounts/files/new.php');
            break;
        case "certificate":
            require ('engine/discounts/files/certificate.php');
            break;
        case "newCertificate":
            require ('engine/discounts/files/newCertificate.php');
            break;
    }
}