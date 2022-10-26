<?php
/**
 * Created by PhpStorm.
 * User: ivashka
 * Date: 25.06.17
 * Time: 1:16
 */
if (!substr_count($_POST['body'], 'http')){
    $dopField = '';
    $dopFieldValue = '';
    if (isset ($_POST['answer'])){
        $_POST['name'] = 'Администратор';
        $dopField = "`idQuestion`,";
        $dopFieldValue = "'".intval($_POST['idQuestion'])."',";
    }
    if (mysql_query("INSERT INTO  `ls_reviews` (
    ".$dopField."
    `idItem` ,
    `name` ,
    `body` ,
    `time`,
    `rating`
    )
    VALUES (
    ".$dopFieldValue."
    '".intval($_POST['id'])."', 
    '".mysql_real_escape_string($_POST['name'])."' , 
    '".mysql_real_escape_string($_POST['body'])."' , 
    '".time()."',
    '".intval($_POST['rating'])."'
    );
    ")){
        echo getOneReview(getOneString("SELECT * FROM `ls_reviews` WHERE `id` = '".mysql_insert_id()."';"), 'newReview');
    } else {
        echo '
        <div class="errorReview alert alert-danger">
            <strong>Извините!</strong> Возникла ошибка при добавлении отзыва. Ваш отзыв не был добавлен!
        </div>
        ';
    }
} else {
    echo '
    <div class="errorReview alert alert-danger">
        <strong>Размещение ссылок в отзывах - запрещено!</strong>
    </div>
    ';
}