<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/19/15
 * Time: 8:50 PM
 * To change this template use File | Settings | File Templates.
 */
$numberItem = 0;
$allPrice = 0;
$items = '';
$uniq_id_in_base = $_COOKIE['PHPSESSID'];
if ($infoUser['id']!=0){
    $sql = "SELECT * FROM `ls_cart` where (`uniq_user` = '".$uniq_id_in_base."') and status <> 1;";
} else {
    $sql = "SELECT * FROM `ls_cart` where (`uniq_user` = '".$uniq_id_in_base."') and status <> 1;";
}
if ($array_item_in_cart = getArray($sql)){
    foreach ($array_item_in_cart as $v)
    {
        $info_item = mysql_fetch_array(mysql_query("SELECT * FROM `ls_items` where `id` = '".$v['id_item']."';"), MYSQL_ASSOC);
        if ($info_item['price_2']!=0)
        {
            $info_item['price_1'] = $info_item['price_2'];
        }
        $allPrice += $info_item['price_1']*$v['number'];
        $numberItem += $v['number'];
        $allPriceOneItem = $info_item['price_1']*$v['number'];
//        $items .= '
//        <tr class="item" id="cart_'.$v['id'].'">
//            <td><div class="fa fa-times" style="cursor: pointer;" onclick="removeItemFromCart('.$v['id'].');"></div><a href="'.getItemLink($info_item).'">'.$info_item['text_1'].'</a></td>
//            <td>'.$v['number'].'</td>
//            <td class="price">'.$allPrice.' грн.</td>
//        </tr>
//        ';
        $items .= '
         <div class="cart_single">
            <a href="#"><img src="' .getImageFile(getMainImage($info_item['id']), 50, 70, 555). '" alt="" /></a>
            <h2><a href="'.getItemLink($info_item).'">'.$info_item['text_1'].'</a> <a href="#" onclick="removeItemFromCart('.$v['id'].'); return false;"><span><i class="fa fa-trash"></i></span></a></h2>
            <p>'.$v['number'].' x '.$info_item['price_1'].' грн.</p>
        </div>
        ';
    }
//    echo '<a href="'.$config['site_url'].'ua/mode/cart.html" style="color: #FFF;">'.$numberItem.' шт. на суму '.$allPrice.' грн.</a>';
    echo '
    <a class="list_cl" href="'.$config['site_url'].'ua/mode/cart.html"><i class="fa fa-shopping-cart"></i>Корзина <span class="cart_zero cart_zero1">'.$numberItem.'</span></a>
    <div class="cart_down_area">
        '.$items.'
        <div class="cart_shoptings">
            <a href="'.$config['site_url'].'ua/mode/cart.html">Оформити замовлення</a>
        </div>
    </div>
    ';
//       <a href="'.$config['site_url'].'ru/mode/cart.html">Моя корзина <i class="fa fa-shopping-cart" style="color: #FFF; font-size:22px;"></i></a>
//    </div>
//    <div class="col-sm-1 col-xs-1" style="line-height: 15px; color: #FFF;">'.$numberItem.' шт. <br>'.$allPrice.' грн.</div>
//    ';
} else {
    echo '<a class="list_cl" href="cart.html"><i class="fa fa-shopping-cart"></i>Корзина <span class="cart_zero cart_zero1">0</span></a>';
}