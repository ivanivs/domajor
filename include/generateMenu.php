<?php
preg_match_all('|{select_(.*)}|isU', $html, $arraySelectTmp);
foreach ($arraySelectTmp[1] as $oneSelectOption){
    $tmp = explode('_', $oneSelectOption);
    $selectId = $tmp[0];
    $selectValue = $tmp[1];
    $dataSelect = getValuesSelectParamWithParent($selectId, $selectValue);
    $value = getOneValueText($selectValue);
    $link = '';
    foreach ($dataSelect as $oneValue){
        $link .= '<li><a href="'.$config['site_url'].'ua/shop/'.translit($value).'/?p='.$oneValue['id'].','.$selectValue.'">'.$oneValue['text'].'</a></li>';
    }
    $html = str_replace('{select_'.$selectId.'_'.$selectValue.'}', $link, $html);
}

/*
 * <li><a href="shop-2.html"><i class="fal fa-chair"></i> Furniture</a></li>
                           <li class="menu-item-has-children"><a href="shop.html"><i class="far fa-campfire"></i>
                               Cooking </a>
                               <ul class="submenu">
                                 <li><a href="shop-2.html">Chair</a></li>
                                 <li><a href="shop-2.html">Table</a></li>
                                 <li><a href="shop.html">Wooden</a></li>
                                 <li><a href="shop.html">furniture</a></li>
                                 <li><a href="shop.html">Clock</a></li>
                                 <li><a href="shop.html">Gifts</a></li>
                                 <li><a href="shop.html">Crafts</a></li>
                              </ul>
                           </li>
                           <li><a href="shop-2.html"><i class="fal fa-shoe-prints"></i>Accessories</a></li>
                           <li><a href="shop-2.html"><i class="fal fa-tshirt"></i>Fashion</a></li>
                           <li><a href="shop-2.html"><i class="fal fa-clock"></i>Clocks</a></li>
                           <li><a href="shop-2.html"><i class="fal fa-light-ceiling"></i>Lighting</a></li>
                           <li><a href="shop-2.html"><i class="fal fa-gift"></i>Toys</a></li>
                           <li><a href="shop-2.html"><i class="fal fa-basketball-ball"></i>Hand Made</a></li>
                           <li><a href="shop-2.html"><i class="fal fa-gift"></i>Minimalism</a></li>
                           <li><a href="shop-2.html"><i class="fal fa-lightbulb-dollar"></i>Electronics</a></li>
                           <li><a href="shop-2.html"><i class="fal fa-car-alt"></i>Cars</a></li>
 */
//$html = str_replace('{select_3_6}', '', $html);