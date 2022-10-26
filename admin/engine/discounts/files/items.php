<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 4/8/20
 * Time: 3:56 PM
 * To change this template use File | Settings | File Templates.
 */
$body_admin .= '
<h3>Управління товарами</h3>
';
$infoDiscount = getOneString("SELECT * FROM `ls_discounts` WHERE `id` = '".intval($_GET['id'])."';");
$body_admin .= '<h4>Блок кодів: '.$infoDiscount['name'].'</h4>';
if ($array = getArray("SELECT * FROM `ls_items` WHERE `searchField` != '' AND `price_1` != 0 ORDER by `searchField`")){
    $body_admin .= '
    <table class="table table-bordered table-striped">
    ';
    foreach ($array as $v){
        if (getOneString("SELECT * FROM `ls_discountsCode` WHERE `itemId` = '".$v['id']."' AND `discountId` = '".$infoDiscount['id']."'")){
            $buttons = '
            <button id="add_'.$v['id'].'" class="btn btn-success" style="display: none;" onclick="addRemoveItemToCodeDiscount('.$infoDiscount['id'].', '.$v['id'].', 1); return false;">Додати</button>
            <button id="remove_'.$v['id'].'" class="btn btn-danger" onclick="addRemoveItemToCodeDiscount('.$infoDiscount['id'].', '.$v['id'].', 0); return false;">Видалити</button>
            ';
        } else {
            $buttons = '
            <button id="add_'.$v['id'].'" class="btn btn-success" onclick="addRemoveItemToCodeDiscount('.$infoDiscount['id'].', '.$v['id'].', 1); return false;">Додати</button>
            <button id="remove_'.$v['id'].'" class="btn btn-danger" style="display: none;" onclick="addRemoveItemToCodeDiscount('.$infoDiscount['id'].', '.$v['id'].', 0); return false;">Видалити</button>
            ';
        }
        $body_admin .= '
        <tr>
            <td><a href="https://bobas.ua/ru/mode/item-'.$v['id'].'.html">'.getNameItem($v).'</a></td>
            <td>
                '.$buttons.'
            </td>
        </tr>
        ';
    }

    $body_admin .= '';
}