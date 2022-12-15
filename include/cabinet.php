<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/19/15
 * Time: 4:37 PM
 * To change this template use File | Settings | File Templates.
 */
if (isset ($infoUser['id'])){
if ($_GET['mode']!='cabinet'){
    switch ($_GET['mode']){
        case "userInfo":
            $activeUserInfo = ' class="active"';
            $title = 'Персональні дані';
            require ('include/cabinet/userInfo.php');
            break;
        case "orders":
            $activeOrders = ' class="active"';
            $title = 'Ваші замовлення';
            require ('include/cabinet/orders.php');
            break;
        case "bonus":
            $activeBonus = ' class="active"';
            $title = 'Ваші бонуси';
            require ('include/cabinet/bonus.php');
            break;
        case "changePassword":
            $activeChangePassword = ' class="active"';
            $title= 'Domajor.com.ua - змінити пароль';
            require ('include/cabinet/changePassword.php');
            break;
        case "favorite":
            $activeFavorite = ' class="active"';
            $title= 'Domajor.com.ua - обрані товари';
            require ('include/cabinet/favorite.php');
            break;
    }
}
$body = '
<div class="container">
    <h1>Ваш кабінет</h1>
    <div class="row">
        <div class="col-md-2">
            <div class="catagory_price_color">
            <div class="catagory_area">
              <div class="panel-heading">
                <h2>Кабінет<a class="anchorjs-link" href="#panel-title"><span class="anchorjs-icon"></span></a></h2>
              </div>
              <div class="sidebar__widget-content" style="padding: 0px;">
                <ul class="catagory">
                    <li'.$activeOrders.'><a href="{main_sait}ua/orders.html">Мої замовлення</a></li>
                    <li'.$activeUserInfo.'><a href="{main_sait}ua/userInfo.html">Мої дані</a></li>
                    <li'.$activeChangePassword.'><a href="{main_sait}ua/changePassword.html">Змінити пароль</a></li>
                    <li><a href="{main_sait}ua/index.html?logout" style="color: red;">Вихід</a></li>
                </ul>
              </div>
            </div>
            </div>
        </div>
        <div class="col-md-10">
            '.$bodyCabinet.'
        </div>
    </div>
</div>
';
} else {
    $body = '
    <div class="container" style="min-height: 500px;">
        <div class="row" style="margin-top: 20px;">
            <div class="col-lg-6 col-lg-offset-3">
                <div class="mainPageNameCategory"><h2>Вхід на сайт</h2></div>
                <table class="table table-bordered table-striped">
                    <tr>
                        <td>Телефон:</td>
                        <td><input type="text" id="loginInSite" class="form-control" name="login" placeholder="+380000000000"></td>
                    </tr>
                    <tr>
                        <td>Пароль:</td>
                        <td><input type="password" id="passwordInSite" class="form-control" name="password"></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div><a href="{main_sait}{lang}/register.html">Зареєструватись</a></div>
                            <div><a href="{main_sait}{lang}/recovery.html">Забули пароль?</a></div>
                            <div class="alert alert-danger" style="margin: 15px 0px; display: none;" id="errorLogin">
                                <strong>Помилка!</strong> Дані не вірні! Спробуйте ще раз, або скористайтесь відновленням паролю.
                            </div>
                            <button class="btn btn-success btn-lg" style="width: 100%;" onclick="loginInSite2();">Увійти</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    ';
}