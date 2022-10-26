<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/12/14
 * Time: 1:41 PM
 * To change this template use File | Settings | File Templates.
 */
if (!isset ($_GET['page']) or $_GET['page']==1){
    $start = 0;
} else {
    $start = intval($_GET['page'])*20-20;
}
if ($arrayReviews = getArray("SELECT * FROM `ls_reviews` ORDER by `time` DESC LIMIT ".$start.", 20;")){
    foreach ($arrayReviews as $v){
        if (MOBILEVER==1){
            $reviewHtml .= getOneReview(str_replace('<img', '<img style="width: 280px"',$v));
        } else {
            $reviewHtml .= getOneReview($v);
        }
    }
}
$infoCount = getOneString("SELECT COUNT(*) FROM `ls_reviews`;");
$numberPage = ceil($infoCount['COUNT(*)']/20);
$link = $config['site_url'].'ru/mode/reviews.html';
if ($numberPage>1)
{
    for ($i=1; $i<=$numberPage; $i++)
    {
        if (($i>($_GET['page']-5) and $i<($_GET['page']+5)) or $i == 1 or $i==$numberPage) {
            if ($i == $_GET['page'] or ($i == 1 and !isset ($_GET['page']))) {
                $paginator .= '
                <li class="active"><a href="' . $link . '?page=' . $i . '">' . $i . '</a></li>
                ';
            } else {
                $paginator .= '
                <li><a href="' . $link . '?page=' . $i . '">' . $i . '</a></li>
                ';
            }
        }
        if ($i==2 and isset ($_GET['page']) and $_GET['page']>6){
            $paginator .= '<li class="disabled"><a href="#">...</a></li>';
        }
        if ($i==$numberPage-1 and $_GET['page']<$numberPage-5 and $numberPage>5){
            $paginator .= '<li class="disabled"><a href="#">...</a></li>';
        }
    }
}
if ($numberPage>1) {
    if ($numberPage == $_GET['page']) {
        $nextPassive = ' class="disabled"';
        $nextLink = '#';
    } else {
        $nextLink = $link . '?page=' . (intval($_GET['page']) + 1);
    }
    if (!isset ($_GET['page']) or $_GET['page'] == 1) {
        $prevPassive = ' class="disabled"';
    } else {
        $prevLink = $link . '?page=' . (intval($_GET['page']) - 1);
    }
}
$dopStyle = '';
if (MOBILEVER==1){
    $dopStyle = '
    <style>
        ul.pagination > li > a{
            font-size: 12px;
        }
    </style>
    ';
}
$paginator = $dopStyle.'
<div class="row">
<div class="col-lg-12">
<nav>
  <ul class="pagination">
    <li' . $prevPassive . '>
      <a href="'.$prevLink.'" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    ' . $paginator . '
    <li' . $nextPassive . '>
      <a href="' . $nextLink . '" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>
</div>
</div>
';
$body .= '
<link href="http://netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">
<link href="http://netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" href="http://og-shop.in.ua/css/summernote.css" media="all"/>
<script type="text/javascript" src="http://og-shop.in.ua/js/summernote.js"></script>
<script>
function sendFile(file, editor, welEditable) {
    data = new FormData();
    data.append("file", file);
    url = "{main_sait}uploadFile.php";
    $.ajax({
        data: data,
        type: "POST",
        url: url,
        cache: false,
        contentType: false,
        processData: false,
        success: function (url) {
            editor.insertImage(welEditable, url);
        }
    });
}
                    $(document).ready(function() {
                      $(\'#body\').summernote({
                          height: 150,
                          focus: true ,
                          toolbar: [
                                //[groupname, [button list]]
                                    [\'style\', [\'bold\', \'italic\', \'underline\']],
                                    [\'insert\', [\'picture\', \'video\']],
                              ],

                          onImageUpload: function(files, editor, welEditable) {
sendFile(files[0],editor,welEditable);
                           }
                        });
                    });
                    function sendReview(){
                        $.ajax({
                            type: \'POST\',
                            dataType: \'html\',
                            url: main_site + \'index.php?mode=ajax&ajax=getReviewPhone\',
                            data: {
                                phone: $("#phone").val(),
                            },
                            success: function(result) {
                                if (result==0){
                                    $("#errPhone").show(200);
                                } else {
                                    $("#errPhone").hide(200);
                                    $.ajax({
                                        type: \'POST\',
                                        dataType: \'html\',
                                        url: main_site + \'index.php?mode=ajax&ajax=saveReview\',
                                        data: {
                                            phone: $("#phone").val(),
                                            name: $("#name").val(),
                                            body: $("#body").code(),
                                        },
                                        success: function(result) {
                                            $("#tableForm").html("<div class=\'alert alert-success\'><strong>Спасибо!</strong> Ваш отзыв успешно добавлен!</div>");
                                            $("#reviews").prepend(result);
                                        },
                                    });
                                }
                            },
                        });
                    }
                    function removeReview(id){
                        $.ajax({
                                        type: \'POST\',
                                        dataType: \'html\',
                                        url: main_site + \'index.php?mode=ajax&ajax=removeReview\',
                                        data: {
                                            id: id,
                                        },
                                        success: function(result) {
                                            $("#review_" + id).remove();
                                        },
                                    });
                    }
                </script>
<div class="row">
    <div class="col-lg-12" style="background-color: #FFF;">
            <div class="row">
                <div class="col-lg-12">
                      <h1 style="font-size: 22px;">OG-SHOP Отзывы</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-danger">
                      <strong>Предупреждение!</strong> Оставить отзыв могут только клиенты которые сделали заказ!
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-xs-6">
                      <table class="table table-bordered">
                      <tr>
                        <td>Ваше имя:</td>
                        <td><input type="text" name="name" id="name" class="form-control"></td>
                      </tr>
                      <tr>
                        <td>Ваш телефон:</td>
                        <td>
                            <input type="text" name="phone" id="phone" class="form-control">
                            <div><span class="label label-important">Должен совпадать с телефоном в заказе</span></div>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2"><strong>Отзыв</strong></td>
                      </tr>
                      <tr>
                        <td colspan="2"><textarea style="width: 98%; height: 100px;" id="body"></textarea></td>
                      </tr>
                      <tr><td colspan="2">
                            <div style="margin: 5px 0px; display: none;" id="errPhone" class="label label-important">Номер телефона не найден в заказе!</div><br>

                            <div style="margin: 5px 0px; display: none;" id="errPhone" class="label label-important">Номер телефона не найден в заказе!</div><br>
                            <div id="tableForm" style="margin: 5px 0px;"></div>
                            <button class="btn btn-success" onclick="sendReview();">Оставить отзыв</button>
                        </td>
                      </tr>
                      </table>
                </div>
                <div class="col-lg-6 col-xs-12">
                    <div class="alert alert-info">
                        <div><strong>Обратите внимание!</strong></div>
                        <p>Оставить отзыв можно только после того как Вы сделали заказ.</p>
                        <p>Номер телефона в заказе - должен соответствовать номеру телефона который Вы вводите для отзыва</p>
                        <p>Мы будем очень благодарны, если к отзыву Вы прикрепите фото с нашим товаром, который уже получили.</p>
                        <p>Отзывы публикуются сразу, никакой модерации нет, Вы видете все отзывы которые оставили наши клиенты!</p>
                        <p>Оставить отзыв без заказа нельзя! Мы хотим что бы Вы видели отзывы только реальных покупателей!</p>
                        <p><strong>Для нас важен каждый Ваш отзыв. Спасибо!</strong></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12"><h3 style="font-size: 18px;">Отзывы наших клиентов</h3></div>
                '.$paginator.'
            </div>
            <div class="row">
                <div class="col-lg-12" id="reviews">'.$reviewHtml.'</div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    '.$paginator.'
                </div>
            </div>
    </div>
</div>
';