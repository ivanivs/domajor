<?php
$body_admin .= '<h3>Подарункові сертифікати</h3>
<a href="/admin/index.php?do=discounts&discounts=newCertificate" class="btn btn-success">Новий сертифікат</a>
';
if ($arrayCertificate = getArray("SELECT * FROM `ls_certificate`")){
    $body_admin .= '
    <table class="table table-bordered table-striped">
    <thead>
        <th>Code</th>
        <th>Дата до</th>
        <th>Сума</th>
        <th>Товар ІД</th>
        <th>Автоматичний</th>
        <th>Дата генерації</th>
        <th>Використаний?</th>
    </thead>
    <tbody>
    ';
    foreach ($arrayCertificate as $cert){
        $body_admin .= '
        <tr class="'.(($cert['used']) ? 'success' : '').'">
            <td>'.$cert['code'].'</td>
            <td>'.(($cert['dateTo'])=='1970-01-01' ? 'Без терміну' : $cert['dateTo']).'</td>
            <td>'.$cert['price'].' грн.</td>
            <td>'.(($cert['itemId']) ? '<a href="https://bobas.ua/ua/mode/item-'.$cert['itemId'].'.html">'.$cert['itemId'].'</a>' : '').'</td>
            <td>'.(($cert['automatic']) ? 'так' : 'ні').'</td>
            <td>'.$cert['date'].'</td>
            <td>
                '.(($cert['used']) ? 'так<div>Замовлення №'.$cert['orderId'].'</div>' : 'ні').'
                '.(($cert['fromOrder']) ? '<div>Замовлення №'.$cert['fromOrder'].'</div>' : '').'
            </td>
        </tr>
        ';
    }
    $body_admin .= '
    </tbody>
    </table>
    ';
}