<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 8/5/16
 * Time: 6:36 PM
 * To change this template use File | Settings | File Templates.
 */
$sizeMan = '
<div class="nameCategoryTopMenu"><a href="#">Обувь по размерам</a></div>
';
if ($array = getValuesSelectParam(1)){
    foreach ($array as $v){
        $sizeMan .= '<a href="{main_sait}{lang}/shop/Obyv/?param&select[16][]=124&select[1][]='.$v['id'].'&sort=popular" class="sizeTopMenu">'.$v['text'].'</a>';
    }
}
$sizeWoman = '
<div class="nameCategoryTopMenu"><a href="#">Обувь по размерам</a></div>
';
if ($array = getValuesSelectParam(18)){
    foreach ($array as $v){
        $sizeWoman .= '<a href="{main_sait}{lang}/shop/Obyv/?param&select[16][]=125&select[18][]='.$v['id'].'&sort=popular" class="sizeTopMenu">'.$v['text'].'</a>';
    }
}
$sizeChild = '
<div class="nameCategoryTopMenu"><a href="#">Обувь по размерам</a></div>
';
if ($array = getValuesSelectParam(15)){
    foreach ($array as $v){
        $sizeChild .= '<a href="{main_sait}{lang}/shop/Obyv/?param&select[6][]=126&select[15][]='.$v['id'].'&sort=popular" class="sizeTopMenu">'.$v['text'].'</a>';
    }
}