<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 3/10/13
 * Time: 5:03 PM
 * To change this template use File | Settings | File Templates.
 */
$uid = 0;
$image = '';
if (isset($_GET['code'])) {
    $result = false;
    $params = array(
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'code' => $_GET['code'],
        'redirect_uri' => $redirect_uri
    );

    $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

    if (isset($token['access_token'])) {
        $params = array(
            'uids'         => $token['user_id'],
            'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
            'access_token' => $token['access_token']
        );

        $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
        if (isset($userInfo['response'][0]['uid'])) {
            $userInfo = $userInfo['response'][0];
            $result = true;
        }
    }
    if ($result){
        $infoCountInDb = getOneString("SELECT COUNT(*) FROM `ls_users` where `uid` = '".$userInfo['uid']."';");
        if ($infoCountInDb['COUNT(*)']>0){
            $infoCountInDb = getOneString("SELECT * FROM `ls_users` where `uid` = '".$userInfo['uid']."';");
            setcookie ("login", $infoCountInDb['login'], time() + $config['time_life_cookie'], '/');
            setcookie ("password", $infoCountInDb['password'], time() + $config['time_life_cookie'], '/');
            setcookie ("id_user_online", $infoCountInDb['id'], time() + $config['time_life_cookie'], '/');
            setcookie ("accessLevel", $infoCountInDb['accesslevel'], time() + $config['time_life_cookie'], '/');
            header('Location: http://og-shop.in.ua/');
            exit();
        }
        $uid = $userInfo['uid'];
        $image = $userInfo['photo_big'];
        $photo = '
        <tr>
            <td>Фото:</td>
            <td><img src="'.$userInfo['photo_big'].'"></td>
        </tr>
        ';
    }
}
$title = 'Реєстрація в Інтернет-маганизні '.$config['user_params_28'];
$optionRegion = getRegion();
$body .= '
<div class="container" style="min-height: 850px; margin-top: 80px; padding-bottom: 100px;">
    <div class="row">
        <div class="col-lg-7">
            <div class="headerBodyRotator">
                <h2>Реєстрація на сайті</h2>
            </div>
            <div id="successRegister" class="alert alert-success" style="display: none;">
                <strong>Вітаємо!</strong> Ви зареєструвались на сайті, тепер можете увійти, використовуючи телефон т пароль!
            </div>
            <div id="register">
                <input type="hidden" id="uid" value="'.$uid.'">
                <input type="hidden" id="image" value="'.$image.'">
                <table class="table table-bordered table-striped">
                    '.$photo.'
                    <tr id="phoneRegTr">
                        <td class="nameField">Ваш телефон:</td>
                        <td><input type="text" id="phoneReg" class="form-control" placeholder="+380000000000"></td>
                    </tr>
                    <tr id="passwordRegTr">
                        <td class="nameField">Пароль:</td>
                        <td><input type="password" id="passwordReg" class="form-control"></td>
                    </tr>
                    <tr id="passwordConfirmTr">
                        <td class="nameField">Підтвердження пароля:</td>
                        <td><input type="password" id="passwordConfirm" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <strong>Особиста інформація</strong>
                        </td>
                    </tr>
                    <tr id="nameRegTr">
                        <td>Ім\'я:</td>
                        <td><input type="text" id="nameReg" class="form-control" value="'.$userInfo['first_name'].'"></td>
                    </tr>
                    <tr id="surNameRegTr">
                        <td>Прізвище:</td>
                        <td><input type="text" id="surNameReg" class="form-control" value="'.$userInfo['last_name'].'"></td>
                    </tr>
                    <tr id="emailRegTr">
                        <td>E-Mail:</td>
                        <td><input type="text" id="emailReg" class="form-control"></td>
                    </tr>
                    <tr id="regionRegTr">
                        <td>Область:</td>
                        <td><select id="region" class="form-control" onchange="getCity();">'.$optionRegion.'</select></td>
                    </tr>
                    <tr id="cityRegTr">
                        <td>Місто:</td>
                        <td><select type="text" id="city" class="form-control" onchange="getWarehouse(0);"><option value="0">Выберите область</option></select></td>
                    </tr>
                    <tr id="cityRegTr">
                        <td>Відділення "Нової пошти":<br><small style="font-size: 12px;">можна змінювати при замовленні</small></td>
                        <td><select type="text" id="warehouse" class="form-control"><option value="0">Оберіть спочатку місто</option></select></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div id="phoneRegError" class="alert alert-danger" style="display: none;">
                                <strong>Помилка!</strong> Телефон не відповідає формату (+380000000000)
                            </div>
                            <div id="passwordRegTrErrorShort" class="alert alert-danger" style="display: none;">
                                <strong>Помилка!</strong> Пароль повинен бути <strong>6</strong> символів або більше
                            </div>
                            <div id="passwordNotConfirm" class="alert alert-danger" style="display: none;">
                                <strong>Помилка!</strong> Пароль повинен співпадати з підтвержденням!
                            </div>
                            <div id="nameRegError" class="alert alert-danger" style="display: none;">
                                <strong>Помилка!</strong> Введіть будь-ласка ім\'я!
                            </div>
                            <div id="surNameRegError" class="alert alert-danger" style="display: none;">
                                <strong>Помилка!</strong> Введіть будь-ласка прізвище!
                            </div>
                            <div id="regionRegError" class="alert alert-danger" style="display: none;">
                                <strong>Помилка!</strong> Вкажіть будь-ласка область
                            </div>
                            <div id="cityRegError" class="alert alert-danger" style="display: none;">
                                <strong>Помилка!</strong> Вкажіть будь-ласка місто!
                            </div>
                            <div id="errorUserExist" class="alert alert-danger" style="display: none;">
                                <strong>Помилка!</strong> На введений Вами номер телефону, вже зареєстровано користувача! Якщо це Ви, скористайтесь <a href="'.$config['site_url'].'/ua/recoveryPassword.html">формою відновлення</a> паролю!
                            </div>
                            <div id="errorRegister" class="alert alert-danger" style="display: none;">
                                <strong>Помилка!</strong> При реєстрації виникла помилка!
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <div class="btn btn-success" onclick="registration();">Зареєструватись</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="headerBodyRotator">
                <h2>Вхід на сайт</h2>
            </div>
        <a href="#" onclick="$(\'#modalLogin\').modal(\'show\');" class="btn btn-danger btn-xs" style="border-radius: 3px;"><i class="fa fa-user" aria-hidden="true"></i> Увійти</a></div>
    </div>
</div>
';