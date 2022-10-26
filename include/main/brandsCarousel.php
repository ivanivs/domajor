<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 4/27/18
 * Time: 4:22 PM
 * To change this template use File | Settings | File Templates.
 */
$brandsCarousel = '';
if ($arrayBrands = getArray("SELECT *
FROM ls_params_select_values
WHERE id_params =  '1' AND `bodyText` <> ''
ORDER BY  `ls_params_select_values`.`text`;")){
    $countPage = ceil(count($array)/6);
    $i = 1;
    foreach ($arrayBrands as $key => $v){
        $active = '';
        if ($i==1){
            if ($key==0){
                $active = ' active';
            }
            $brandsCarousel .= '
            <div class="item'.$active.'">
                    <div class="row">
            ';
        }
        preg_match('|src="(.*)"|isU', $v['bodyText'], $imgTmp);
        $brandsCarousel .= '
        <div class="col-lg-2" style="text-align: center;">
            <a href="{main_sait}ru/shop/Brands/?param&select[1][]='.$v['id'].'"><img src="'.$imgTmp[1].'" class="brandsImg"></a>
        </div>
        ';
        if ($i==6 or !isset ($arrayBrands[$key+1])){
            $brandsCarousel .= '
            </div>
            </div>
            ';
            $i=0;
        }
        $i++;
    }
}