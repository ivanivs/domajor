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
if ($arrayCategory = getArray("SELECT * FROM `ls_params_select_values` WHERE `id_params` = 1 order by `position` DESC ")){
    $nav = '';
    foreach ($arrayCategory as $oneCategory){
        $nav .= '<li class="menu-item-has-children">
										<a href="'.getCategoryLink($oneCategory).'" title="'.$oneCategory['text'].'" class="dropdown">
											<span class="menu-title">
												'.getOneValue($oneCategory['id'])['text'].'
											</span>
										</a>
        ';
        if ($arrayChildCategory = getArray("SELECT * FROM `ls_params_select_values` WHERE `parent_param_id` = '".$oneCategory['id']."' ORDER by `position` DESC")){
            $nav .= '<ul class="submenu">';
            foreach ($arrayChildCategory as $child){
                $text = getOneValue($child['id'])['text'];
                if (empty($child['text']))
                    $child['text'] = $text;
                $nav .= '<li><a href="'.getCategoryLink($child).'">'.$text.'</a></li>';
//                if ($arrayChildChildCategory = getArray("SELECT * FROM `ls_params_select_values` WHERE `parent_param_id` = '".$child['id']."'")){
//                    $nav .= '<ul>';
//                    foreach ($arrayChildChildCategory as $childChild){
//                        $nav .= '<li>
//                                    <a href="'.getCategoryLink($childChild).'" title="">'.$childChild['text'].'</a>
//                                </li>';
//                    }
//                    $nav .= '</ul>';
//                }
//                $nav .= '</ul>';
            }
            $nav .= '</ul>';
        }
        $nav .= '</li>';
    }
}
$html = str_replace ('{allCategory}', $nav, $html);
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