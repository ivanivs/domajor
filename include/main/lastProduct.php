<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/15/15
 * Time: 4:35 PM
 * To change this template use File | Settings | File Templates.
 */
$lastProduct = '';
$htmlTemplate = file_get_contents('templates/'.$config ['default_template'].'/mainOneItem.html');
if ($arrayItems = getArray("SELECT `ls_items`.* FROM `ls_items` JOIN `ls_values_image` ON `ls_items`.`id` = `ls_values_image`.`id_item` WHERE `ls_items`.`text_4` > 0 AND `ls_values_image`.`id_item` IS NOT NULL GROUP by `ls_items`.`id` ORDER by `ls_items`.`id` DESC LIMIT 0,10")){
    foreach ($arrayItems as $key => $v){
        $active = '';
        $oneItem = $htmlTemplate;
        $oneItem = getOneItem($v, $oneItem);
        $lastAdd .= $oneItem;
    }
    unset ($array);
    $onlyMainPage = str_replace('{lastAdd}', $lastAdd, $onlyMainPage);
}
$lastAdd = '';
$htmlTemplate = file_get_contents('templates/'.$config ['default_template'].'/mainOneItem.html');
if ($arrayItems = getArray("SELECT `ls_items`.* FROM `ls_items` JOIN `ls_values_image` ON `ls_items`.`id` = `ls_values_image`.`id_item` WHERE `ls_items`.`text_4` > 0 AND `ls_values_image`.`id_item` IS NOT NULL GROUP by `ls_items`.`id` ORDER by `ls_items`.`visit` DESC LIMIT 0,10")){
    foreach ($arrayItems as $key => $v){
        $active = '';
        $oneItem = $htmlTemplate;
        $oneItem = getOneItem($v, $oneItem);
        $lastAdd .= $oneItem;
    }
    unset ($array);
    $onlyMainPage = str_replace('{popular}', $lastAdd, $onlyMainPage);
}
$lastAdd = '';
$htmlTemplate = file_get_contents('templates/'.$config ['default_template'].'/mainOneItem.html');
$sql = "SELECT `ls_items`.* FROM `ls_items` JOIN `ls_values_image` ON `ls_items`.`id` = `ls_values_image`.`id_item` WHERE `ls_items`.`price_2` != 0 AND `ls_items`.`price_1` != `ls_items`.`price_2` AND `ls_items`.`text_4` > 0 AND `ls_values_image`.`id_item` IS NOT NULL GROUP by `ls_items`.`id` ORDER by `ls_items`.`visit` DESC LIMIT 0,10";
if ($arrayItems = getArray($sql)){
    foreach ($arrayItems as $key => $v){
        $active = '';
        $oneItem = $htmlTemplate;
        $oneItem = getOneItem($v, $oneItem);
        $lastAdd .= $oneItem;
    }
    unset ($array);
    $onlyMainPage = str_replace('{actions}', $lastAdd, $onlyMainPage);
}
$lastAdd = '';
$htmlTemplate = file_get_contents('templates/'.$config ['default_template'].'/itemTopBuy.html');
$sql = "SELECT `ls_items`.* FROM `ls_items` JOIN `ls_values_image` ON `ls_items`.`id` = `ls_values_image`.`id_item` WHERE `ls_items`.`select_7` = 33 AND `ls_items`.`text_4` > 0 AND `ls_values_image`.`id_item` IS NOT NULL GROUP by `ls_items`.`id` ORDER by `ls_items`.`visit` DESC LIMIT 0,10";
if ($arrayItems = getArray($sql)){
    foreach ($arrayItems as $key => $v){
        $active = '';
        $oneItem = $htmlTemplate;
        $oneItem = getOneItem($v, $oneItem);
        $lastAdd .= $oneItem;
    }
    unset ($array);
    $onlyMainPage = str_replace('{topBuy}', $lastAdd, $onlyMainPage);
}
$lastAdd = '';
if ($arrayValue = getValuesSelectParam(3)){
    foreach ($arrayValue as $v){
        $brands .= '
        <div class="swiper-slide" style="font-size:1.5em; color:#FFF; font-weight: bold;">
            <a href="'.$config['site_url'].'ua/shop/Brands/?p='.$v['id'].'">'.$v['text'].'</a>
        </div>
        ';
    }
    $onlyMainPage = str_replace('{brands}', $brands, $onlyMainPage);
}
$blog = '';
if ($array = getArray("SELECT * FROM `ls_news` ORDER BY `id` DESC LIMIT 0,4")){
    foreach ($array as $v){
        $sql = "SELECT `text` FROM `ls_translate` WHERE `type` = 'news_name' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".$v['id']."';";
        $info_name_news = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
        $sql = "SELECT `text` FROM `ls_translate` WHERE `type` = 'news_short' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".$v['id']."';";
        $info_short_news = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
        $sql = "SELECT `text` FROM `ls_translate` WHERE `type` = 'news_category_n' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".$v['id_category']."';";
        $info_category_news = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
        $full_news_link = '{main_sait}{lang}/mode/news/'.translit($info_category_news['text']).'_'.$v['id_category'].'/'.translit($info_name_news['text']).'_'.$v['id'].'.html';
        preg_match('~(?<=src=")[^"]+(?=")~', $info_short_news['text'], $arr);
        $img = $arr[0] ?? '';
        if (!empty($img)){
            $imgArr = explode('/', $img);
            $img = $imgArr[count($imgArr)-1];
        }
        $imgHtml = $config['site_url'].'resize_image.php?filename='.$img.'&const=128&width=300&height=200&r=255&g=255&b=255&path=upload/reviews/';
        $blog .= '<div class="col-lg-3 col-md-6 col-sm-6 tpblogborder mb-30">
            <div class="blogitem">
                <div class="blogitem__thumb fix mb-20">
                    <a href="'.$full_news_link.'"><img src="'.$imgHtml.'" alt="blog-bg"></a>
                </div>
                <div class="blogitem__content">
                    <div class="blogitem__contetn-date mb-10">
                        <ul>
                            <li>
                                <a class="date-color" href="#">'.date("d.m.Y H:i", $v['time']).'</a>
                            </li>
                        </ul>
                    </div>
                    <h4 class="blogitem__title mb-15">
                        <a href="'.$full_news_link.'">'.$info_name_news['text'].'</a>
                    </h4>
                    <div class="blogitem__btn">
                        <a href="'.$full_news_link.'">Детальніше...</a>
                    </div>
                </div>
            </div>
        </div>';
    }
    $onlyMainPage = str_replace('{blog}', $blog, $onlyMainPage);
}