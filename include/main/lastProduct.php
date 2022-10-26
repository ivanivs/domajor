<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/15/15
 * Time: 4:35 PM
 * To change this template use File | Settings | File Templates.
 */
$lastProduct = '';
if ($arrayItems = getArray("SELECT `ls_items`.* FROM `ls_items` JOIN `ls_values_image` ON `ls_items`.`id` = `ls_values_image`.`id_item` WHERE `ls_items`.`text_14` = 1 GROUP by `ls_items`.`id` ORDER by `ls_items`.`time` DESC LIMIT 0,12")){
    foreach ($arrayItems as $v){
        $producer = getOneValue($v['select_2']);
        $lastProduct .= '
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="tile">
                <div class="price-label">'.getPrice($v['price_1']).' грн.</div>
                <a href="'.getItemLink($v).'"><img src="http://picase.com.ua/resize_image.php?filename='.urlencode('upload/userparams/'.getMainImage($v['id'])).'&const=12&width=250&height=250&r=255&g=255&b=255" alt="'.$v['text_1'].'"></a>
                <div class="footer">
                    <a href="'.getItemLink($v).'" style="display: block; height: 75px;">'.$v['text_1'].'</a>
                    <span>Производитель: '.$producer['text'].'</span>
                    <div class="tools">
                        <!--<div class="rate">
                            <span class="active"></span>
                            <span class="active"></span>
                            <span class="active"></span>
                            <span></span>
                            <span></span>
                        </div>-->
                        <!--Add To Cart Button-->
                        <a class="add-cart-btn" href="#" onclick="buyItemShort('.$v['id'].'); return false;"><span>В корзину</span><i class="fa fa-shopping-cart"></i></a>
                        <!--Share Button-->
                        <div class="share-btn">
                            <div class="hover-state">
                                <a class="fa fa-vk" href="http://vk.com/share.php?url='.getItemLink($v).'" target="_blank"></a>
                            </div>
                            <i class="fa fa-share"></i>
                        </div>
                        <!--Add To Wishlist Button-->
                        <a class="wishlist-btn" href="#" onclick="addToFavorite('.$v['id'].'); return false;">
                            <div class="hover-state">Избранное</div>
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        ';
    }
}