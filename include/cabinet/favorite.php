<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/19/15
 * Time: 9:32 PM
 * To change this template use File | Settings | File Templates.
 */
$items = '';
if ($arrayFavorite = getArray("SELECT `ls_items`.*, `ls_favorite`.`id` as `idFavorite` FROM `ls_favorite` JOIN `ls_items` ON `ls_items`.`id` = `ls_favorite`.`idItem` WHERE (`idUser` = '".$infoUser['id']."' or `uniq` = '".$_COOKIE['PHPSESSID']."') ORDER by `time` DESC")){
    foreach ($arrayFavorite as $v){
        if ($v['text_14']==1){
            $button = '<td class="button"><a class="btn btn-primary btn-sm" href="#" onclick="buyItemShort('.$v['id'].'); return false;"><i class="icon-shopping-cart"></i>В корзину</a></td>';
        } else {
            $button = '<td class="button"><a class="btn btn-primary btn-sm disabled" href="#">Закончился</a></td>';
        }
        $items .= '
         <!--Item-->
            <tr class="item first" id="favirite_'.$v['idFavorite'].'">
                <td class="thumb"><a href="'.getItemLink($v).'"><img src="http://picase.com.ua/resize_image.php?filename=upload%2Fuserparams%2F'.getMainImage($v['id']).'&const=12&width=250&height=250&r=255&g=255&b=255" alt="'.$v['text_1'].'"></a></td>
              <td class="name"><a href="'.getItemLink($v).'" style="font-size: 0.7em;">'.$v['text_1'].'</a></td>
              <td class="price">'.getPrice($v['price_1']).' грн.</td>
              '.$button.'
              <td class="delete"><i class="icon-delete" onclick="rmFavorite('.$v['idFavorite'].');"></i></td>
            </tr>
        ';
    }
}
$bodyCabinet = '
<section class="wishlist">
<h1>Избранные товары</h1>
<table class="items-list">
        <tbody><tr>
            <th>&nbsp;</th>
          <th>Найменование</th>
          <th>Цена</th>
        </tr>
       '.$items.'
      </tbody></table>
  </section>
';