<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 3/11/13
 * Time: 12:47 AM
 * To change this template use File | Settings | File Templates.
 */
$body .= '
<div class="page">
    <div class="headerBodyRotator">
        <h2>Изменение пароля</h2>
    </div>
    <div class="register">
        <table class="registerTable">
            <tr>
                <td class="nameField">Старый пароль:</td>
                <td><input type="password" id="oldPassword"><span class="tooltip" id="incorrectPassword">Не правельный пароль!</span></td>
            </tr>
            <tr>
                <td class="nameField">Новый пароль:</td>
                <td><input type="password" id="password"><span class="tooltip" id="emptyPassword">Не может быть пустым!</span></td>
            </tr>
            <tr>
                <td class="nameField">Подтвердите пароль:</td>
                <td><input type="password" id="passwordConfirm"><span class="tooltip" id="incorrectPass">Пароли должны совпадать!</span></td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="loginButton" id="doChangePass">Зарегестрироваться</div>
                </td>
            </tr>
        </table>
    </div>
</div>
';