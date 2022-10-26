<?php
if (isset ($_POST['price'])){
    $arrayCode = Array();
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRSTUVWXYZ';
// Output: 54esmdr0qf
    $i = 1;
    if (intval($_POST['itemId'])==0) {
        while ($i <= intval($_POST['count'])) {
            $code = substr(str_shuffle($permitted_chars), 0, 8);
            if (!getOneString("SELECT * FROM `ls_certificate` WHERE `code` = '" . $code . "'")) {
                $arrayCode[] = $code;
                $i++;
            }
        }
        $success = '<h3>Згенеровані коди</h3>';
        foreach ($arrayCode as $code) {
            mysql_query("INSERT INTO `ls_certificate` (
            `dateTo`, 
            `price`, 
            `code`, 
            `itemId`,
            `date`
            ) VALUES (
            '" . date("Y-m-d", strtotime($_POST['dateTo'])) . "',
            '" . mysql_real_escape_string($_POST['price']) . "', 
            '" . $code . "', 
            '" . intval($_POST['itemId']) . "',
            '" . date("Y-m-d") . "'
            );");
            $success .= '<div>' . $code . '</div>';
        }
    } else {
        mysql_query("INSERT INTO `ls_certificate` (
            `dateTo`,
            `price`, 
            `itemId`,
            `date`,
            `automatic`
            ) VALUES (
            '1970-01-01',
            '" . mysql_real_escape_string($_POST['price']) . "', 
            '" . intval($_POST['itemId']) . "',
            '" . date("Y-m-d") . "',
            1" .
            "
            );");
        $success = '<h3>Згенеровані коди</h3>
        <div class="alert alert-success">Згенерований код для товару №'.intval($_POST['itemId']).'</div>';
    }
}
$body_admin .= '
<h3>Генерування сертифікатів</h3>
<div class="row">
    <div class="span6">
        <form action="" method="POST">
            <table class="table table-bordered">
            <tr>
                <td>Сума:</td>    
                <td><input type="number" class="form-control" name="price"></td>    
            </tr>
            <tr>
                <td>Кількість:</td>
                <td><input type="number" class="form-control" name="count" max="30"></td>
            </tr>
            <tr>
                <td>Дата до: (використання)</td>
                <td><input type="date" class="form-control" name="dateTo"></td>    
            </tr>
            <tr>
                <td>ІД товару:</td>
                <td><input type="number" class="form-control" name="itemId"></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" class="btn btn-success" value="Генерувати"></td>    
            </tr>
        </table>        
        </form>    
        '.$success.'
    </div>
    <div class="span6">
        <h4>Інструкція:</h4>
        <p>Для генерації кодів сертифікатів вводимо суму сертифікату, кількість, і дату до якої дійсний сертифікат, для безстрокового - дату не вводимо</p>
        <p>Для автоматичної генерації коду при купівлі - вводимо все крім кількості, ід товару - це ІД сертифікату на сайту, при купівлі після оплати: виставлення статусу оплачено в замовленні згенерується код і надішлеться в смс покупцеві</p>    
    </div>
</div>
';