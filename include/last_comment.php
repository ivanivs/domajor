<?php
/**
 * Created by PhpStorm.
 * User: ivashka
 * Date: 29.07.17
 * Time: 0:50
 */
$last_comment = '';
if ($arrayComment = getArray("SELECT * FROM `ls_reviews` ORDER by `time` DESC LIMIT 0, 6")){
    foreach ($arrayComment as $v){
        $infoItem = getOneString("SELECT * FROM `ls_items` WHERE `id` = '".$v['idItem']."';");
        $last_comment .= '
        <div class="oneBlockRight oneBlock">
            <a href="'.$config['site_url'].'ua/mode/item-'.$v['idItem'].'.html" class="itemCommentSm">'.$infoItem['text_1'].' '.getOneValueText($infoItem['select_1']).' '.$infoItem['text_4'].'</a>
            <p style="padding-left: 10px;">
                '.substr($v['body'], 0, 100).'
            </p>
            <div class="date">'.date('d.m.Y H:i', $v['time']).'</div>
        </div>
        ';
    }
}