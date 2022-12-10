<?php
$slider = '';
if ($arrayItem = getArray("SELECT `ls_items`.* FROM `ls_items` LEFT JOIN `ls_values_image` ON `ls_values_image`.`id_item` = `ls_items`.`id` WHERE `ls_items`.`text_4` > 0 ORDER BY RAND() LIMIT 0,3")){
    foreach ($arrayItem as $v){
        $priceArray = getPriceArray($v);
        $slider .= '
        <div class="swiper-slide slider-bg-2 slider-3">
            <div class="container">
                <div class="row p-relative justify-content-xl-end align-items-center">
                    <div class="col-xl-5 col-lg-6 col-md-6">
                        <div class="tpslidertwo__content slider-content-3">
                            <h3 class="tpslidertwo__title mb-10">'.$v['text_1'].'</h3>
                            <p>'.getOneValueText($v['select_3']).'</p>
                            <div class="tpslidertwo__slide-btn d-flex align-items-center ">
                                <a class="tp-btn banner-animation mr-25" href="'.getItemLink($v).'">Детальніше... <i class="fal fa-long-arrow-right"></i></a>
                                <span>Всього <br> <b>'.$priceArray['price'].'</b></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-6 col-md-6 d-none d-md-block">
                        <div class="tpsliderthree__img p-relative text-end pt-55">
                            <img src="{template}assets/img/slider/slider-04.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ';
    }
}
$htmlSlider = '<div class="swiper-slide slider-bg-2 slider-3">
                    <div class="container">
                        <div class="row p-relative justify-content-xl-end align-items-center">
                            <div class="col-xl-5 col-lg-6 col-md-6">
                                <div class="tpslidertwo__content slider-content-3">
                                    <h3 class="tpslidertwo__title mb-10">Wooden <br> Lounge Furniture</h3>
                                    <p>New Modern Stylist Fashionable Women\'s Wear holder</p>
                                    <div class="tpslidertwo__slide-btn d-flex align-items-center ">
                                        <a class="tp-btn banner-animation mr-25" href="shop.html">Shop Now <i
                                                class="fal fa-long-arrow-right"></i></a>
                                        <span>Start From <br> <b>$99.99</b></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-5 col-lg-6 col-md-6 d-none d-md-block">
                                <div class="tpsliderthree__img p-relative text-end pt-55">
                                    <img src="{template}assets/img/slider/slider-04.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';