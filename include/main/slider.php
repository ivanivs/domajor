<?php
$slider = '';
//if ($arrayItem = getArray("SELECT `ls_items`.* FROM `ls_items` LEFT JOIN `ls_values_image` ON `ls_values_image`.`id_item` = `ls_items`.`id` WHERE
//        `ls_items`.`text_4` > 0
//            AND
//        `ls_values_image`.`id_item` IS NOT NULL
//        ORDER BY RAND() LIMIT 0,3")){
//    foreach ($arrayItem as $v){
//        $priceArray = getPriceArray($v);
//        $slider .= '
//        <div class="swiper-slide slider-bg-2 slider-3">
//            <div class="container">
//                <div class="row p-relative justify-content-xl-end align-items-center">
//                    <div class="col-xl-5 col-lg-6 col-md-6">
//                        <div class="tpslidertwo__content slider-content-3">
//                            <h3 class="tpslidertwo__title mb-10">'.$v['text_1'].'</h3>
//                            <p>'.getOneValueText($v['select_3']).'</p>
//                            <div class="tpslidertwo__slide-btn d-flex align-items-center ">
//                                <a class="tp-btn banner-animation mr-25" href="'.getItemLink($v).'">Детальніше... <i class="fal fa-long-arrow-right"></i></a>
//                                <span>Всього <br> <b>'.$priceArray['price'].' грн.</b></span>
//                            </div>
//                        </div>
//                    </div>
//                    <div class="col-xl-5 col-lg-6 col-md-6 d-none d-md-block">
//                        <div class="tpsliderthree__img p-relative text-end pt-55">
//                            <img src="'.getImgSoroka($v, 605, 519).'" alt="">
//                        </div>
//                    </div>
//                </div>
//            </div>
//        </div>
//        ';
//    }
//}
if ($arrayBaner = getArray("SELECT * FROM `ls_baner` ORDER by `id` DESC LIMIT 0,5")){
    foreach ($arrayBaner as $v){
        $slider .= '
        <div class="swiper-slide">
            <div class="tp-slide-item">
                <div class="tp-slide-item__content">
                    <h3 class="tp-slide-item__title mb-25">'.$v['name'].'</h3>
                    <a class="tp-slide-item__slide-btn tp-btn" href="'.$v['link'].'">детальніше... <i class="fal fa-long-arrow-right"></i></a>
                </div>
                <div class="tp-slide-item__img">
                    <img src="'.$config['site_url'].$v['file'].'" alt="'.$v['name'].'">
                </div>
            </div>
        </div>
        ';
    }
}
$onlyMainPage = str_replace('{slider}', $slider, $onlyMainPage);