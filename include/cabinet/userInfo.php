<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/19/15
 * Time: 6:53 PM
 * To change this template use File | Settings | File Templates.
 */
if ($infoUser['birthday']!=0 and strlen($infoUser['birthday'])>0){
    $birthday = date('d.m.Y', $infoUser['birthday']);
}
$bodyCabinet = '
<h1>Персональні дані</h1>
<table class="table table-bordered table-striped">
<tr>
    <td>Ім\'я:</td>
    <td><input type="text" id="name" class="form-control" value="'.$infoUser['name'].'"></td>
</tr>
<tr>
    <td>Прізвище:</td>
    <td><input type="text" id="surName" class="form-control" value="'.$infoUser['surName'].'"></td>
</tr>
<tr>
    <td>E-Mail:</td>
    <td><input type="text" id="email" class="form-control" value="'.$infoUser['email'].'"></td>
</tr>
<tr>
    <td colspan="2">
        <strong>Дані для відправки замовлень</strong> (можна змінити при оформленні замовлень)
    </td>
</tr>
<tr>
    <td>Область:</td>
    <td><select id="region" class="form-control" onchange="getCity();"><option value="0">Зробіть вибір</option>'.getRegion($infoUser['region']).'</select></td>
</tr>
<tr>
    <td>Місто</td>
    <td><select id="city" class="form-control" onclick="getWarehouse();"><option value="0">Зробіть вибір</option>'.getCity($infoUser['region'], $infoUser['city']).'</select></td>
</tr>
<tr>
    <td>Відділення Нової пошти:</td>
    <td><select id="warehouse" class="form-control"><option value="0">Зробіть вибір</option>'.getWarehouse($infoUser['city'], $infoUser['warehouse']).'</select></td>
</tr>
</table>
<div class="alert alert-success" style="margin-top: 15px; display: none;" id="successSave">
    <strong>Вітаємо!</strong> Дані успішно збережені!
</div>
<div class="alert alert-danger" style="margin-top: 15px; display: none;" id="errorSave">
    <strong>Помилка!</strong> Нажаль, дані зберегти не вдалось, спробуйте ще раз!
</div>
<div style="margin-bottom: 15px;"><button class="btn btn-success" onclick="saveUserInfo();">Зберегти</button></div>
';