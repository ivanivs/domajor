<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 10/21/16
 * Time: 11:48 AM
 * To change this template use File | Settings | File Templates.
 */
$body .= '
<div class="row">
    <div class="col-lg-6 col-lg-offset-3">
        <div class="mainPageNameCategory"><h2>Восстановление пароля</h2></div>
        <table class="table table-bordered table-striped" style="margin-bottom: 200px;">
            <tr>
                <td>Номер телефона:</td>
                <td><input type="text" class="form-control" id="phoneNumber"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="alert alert-info" style="margin: 10px 0px;">
                        Ваш новый пароль будет отправлен на телефон при помощи СМС.
                    </div>
                    <div id="successGetNewPass"></div>
                    <button class="btn btn-success btn-lg" style="width: 100%;" onclick="getNewPassword();">Получить пароль</button>
                </td>
            </tr>
        </table>
    </div>
</div>
';