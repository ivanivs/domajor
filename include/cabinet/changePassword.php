<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/19/15
 * Time: 5:27 PM
 * To change this template use File | Settings | File Templates.
 */
$bodyCabinet = '
<h1>Змінити пароль</h1>
<div id="tableChangePassword">
    <table class="table table-bordered table-striped">
        <tr>
            <td>Старий пароль:</td>
            <td><input type="password" id="oldPassword" class="form-control"></td>
        </tr>
        <tr id="passwordTr">
            <td>Новий пароль:</td>
            <td><input type="password" id="password" class="form-control"></td>
        </tr>
        <tr id="confirmPasswordTr">
            <td>Повтор паролю:</td>
            <td><input type="password" id="confirmPassword" class="form-control"></td>
        </tr>
    </table>
    <div class="alert alert-danger" id="passwordLengthError" style="display: none;">
        <strong>Помилка!</strong> Довжина Вашого паролю повинна бути довша 6 символів!
    </div>
    <div class="alert alert-danger" id="passwordNotConfirmError" style="display: none;">
        <strong>Помилка!</strong> Новий пароль не співпадає з його підтвердженням!
    </div>
    <div class="alert alert-danger" id="errorOldPass" style="display: none;">
        <strong>Помилка!</strong> Старий пароль введено не вірно!
    </div>
    <button class="btn btn-success" style="margin-bottom: 15px;" onclick="savePassword();">Зберегти новий пароль</button>
</div>
<div id="successSavePassword" style="display: none;" class="alert alert-success">
    <strong>Вітаємо!</strong> Пароль успішно збережено!
</div>
';