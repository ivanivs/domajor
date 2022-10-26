<?php
/**
 * Created by PhpStorm.
 * User: ivashka
 * Date: 29.07.17
 * Time: 0:26
 */
$mainPage_blog = '';
if ($arrayNews = getArray("SELECT * FROM `ls_news` ORDER by `id` DESC LIMIT 0, 6")){
    foreach ($arrayNews as $v){
        $sql = "SELECT `text` FROM `ls_translate` WHERE `type` = 'news_name' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".mysql_real_escape_string($v['id'])."';";
        $info_name_news = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
        $sql = "SELECT `text` FROM `ls_translate` WHERE `type` = 'news_category_n' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".$v['id_category']."';";
        $info_category_news = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
        $full_news_link = '{main_sait}{lang}/mode/news/'.translit($info_category_news['text']).'_'.$v['id_category'].'/'.translit($info_name_news['text']).'_'.$v['id'].'.html';
        $mainPage_blog .= '
        <div class="oneBlockRight oneBlock">
            <p>
                <a href="'.$full_news_link.'">'.$info_name_news['text'].'</a>
            </p>
            <div class="date">'.date('d.m.Y H:i', $v['time']).'</div>
        </div>
        ';
    }
    $mainPage_blog .= '<div class="oneBlockRight oneBlock">
            <a href="'.$config['site_url'].'ua/mode/news">читати більше...</a>
        </div>';
}