<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/22/15
 * Time: 6:44 PM
 * To change this template use File | Settings | File Templates.
 */
if (isset ($_POST['id'])){
    if (mysql_query("INSERT INTO  `ls_favorite` (
    `idUser` ,
    `uniq` ,
    `time` ,
    `idItem`
    )
    VALUES (
    '".$infoUser['id']."' ,
    '".$_COOKIE['PHPSESSID']."' ,
    '".time()."' ,
    '".intval($_POST['id'])."'
    );")){
        print '
        <div class="alert alert-success">
            <strong>Поздравляем!</strong> Товар успешно добавлен в избранные
        </div>
        <div style="margin-top: 15px;"></div>
        <a href="'.$config['site_url'].$alt_name_online_lang.'/favorite.html" class="btn btn-success">Все избранные</a> <button class="btn btn-default" data-dismiss="modal" aria-label="Close">Продолжить покупки</button>
        ';
    } else {
        echo '
        <div class="alert alert-error">
            <strong>Ошибка!</strong> Попробуйте еще раз, или обратитесь к администрации сайта!
        </div>
        ';
    }
}