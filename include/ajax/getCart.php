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
            <li>
                 <div class="tpcart__item">
                    <div class="tpcart__img">
                       <img src="' .getImageFile(getMainImage($info_item['id']), 100, 100, 556). '" alt="">
                       <div class="tpcart__del">
                          <a href="#" onclick="removeItemFromCart('.$v['id'].'); return false;"><i class="far fa-times-circle"></i></a>
                       </div>
                    </div>
                    <div class="tpcart__content">
                       <span class="tpcart__content-title"><a href="'.getItemLink($info_item).'">'.getNameItem($info_item).'</a>
                       </span>
                       <div class="tpcart__cart-price">
                          <span class="quantity">'.$v['number'].' x</span>
                          <span class="new-price">'.$info_item['price_1'].' грн.</span>
                       </div>
                    </div>
                 </div>
              </li>
        ';
    }
//    echo '<a href="'.$config['site_url'].'ua/mode/cart.html" style="color: #FFF;">'.$numberItem.' шт. на суму '.$allPrice.' грн.</a>';
    echo '
    <button class="tpcart__close"><i class="fal fa-times"></i></button>
    <div class="tpcart">
    <h4 class="tpcart__title">Ваша корзина</h4>
         <div class="tpcart__product">
            <div class="tpcart__product-list">
                <ul>
                '.$items.'
                </ul>
            </div>
            <div class="tpcart__checkout">
               <div class="tpcart__total-price d-flex justify-content-between align-items-center">
                  <span> Разом:</span>
                  <span class="heilight-price"> '.$allPrice.' грн.</span>
               </div>
               <div class="tpcart__checkout-btn">
                  <a class="tpcheck-btn" href="'.$config['site_url'].'ua/mode/cart.html">Оформити замовлення</a>
               </div>
            </div>
        </div>
    </div>
    ';
//       <a href="'.$config['site_url'].'ru/mode/cart.html">Моя корзина <i class="fa fa-shopping-cart" style="color: #FFF; font-size:22px;"></i></a>
//    </div>
//    <div class="col-sm-1 col-xs-1" style="line-height: 15px; color: #FFF;">'.$numberItem.' шт. <br>'.$allPrice.' грн.</div>
//    ';
} else {
    echo '
    <button class="tpcart__close"><i class="fal fa-times"></i></button><div class="tpcart">
    <h4 class="tpcart__title">Ваша корзина</h4>
         <div class="tpcart__product">
            <div class="tpcart__product-list">
                Корзина пуста
            </div>
        </div>
        ';
}
/*
 * <button class="tpcart__close"><i class="fal fa-times"></i></button><div class="tpcart">
         <h4 class="tpcart__title">Your Cart</h4>
         <div class="tpcart__product">
            <div class="tpcart__product-list">
               <ul>
                  <li>
                     <div class="tpcart__item">
                        <div class="tpcart__img">
                           <img src="{template}assets/img/product/home-one/product-3.jpg" alt="">
                           <div class="tpcart__del">
                              <a href="#"><i class="far fa-times-circle"></i></a>
                           </div>
                        </div>
                        <div class="tpcart__content">
                           <span class="tpcart__content-title"><a href="shop.html">Evo Lightweight Granite Shirt</a>
                           </span>
                           <div class="tpcart__cart-price">
                              <span class="quantity">1 x</span>
                              <span class="new-price">$138.00</span>
                           </div>
                        </div>
                     </div>
                  </li>
                  <li>
                     <div class="tpcart__item">
                        <div class="tpcart__img">
                           <img src="{template}assets/img/product/home-one/product-5.jpg" alt="">
                           <div class="tpcart__del">
                              <a href="#"><i class="far fa-times-circle"></i></a>
                           </div>
                        </div>
                        <div class="tpcart__content">
                           <span class="tpcart__content-title"><a href="shop.html">Purab Enormous Miranda Bottle</a>
                           </span>
                           <div class="tpcart__cart-price">
                              <span class="quantity">1 x</span>
                              <span class="new-price">$162.8</span>
                           </div>
                        </div>
                     </div>
                  </li>
               </ul>
            </div>
            <div class="tpcart__checkout">
               <div class="tpcart__total-price d-flex justify-content-between align-items-center">
                  <span> Subtotal:</span>
                  <span class="heilight-price"> $300.00</span>
               </div>
               <div class="tpcart__checkout-btn">
                  <a class="tpcart-btn mb-10" href="#">View Cart</a>
                  <a class="tpcheck-btn" href="#">Checkout</a>
               </div>
            </div>
         </div>
         <div class="tpcart__free-shipping text-center">
            <span>Free shipping for orders <b>under 10km</b></span>
         </div>
      </div>*/