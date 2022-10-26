<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 4/8/20
 * Time: 2:58 PM
 * To change this template use File | Settings | File Templates.
 */
if (isset ($_POST['name'])){
    if (mysql_query("INSERT INTO `ls_discounts` (`name`, `percent`, `date`, `code`) VALUES ('".mysql_real_escape_string($_POST['name'])."', '".mysql_real_escape_string($_POST['discount'])."', '".mysql_real_escape_string($_POST['date'])."', '".mysql_real_escape_string($_POST['code'])."');")){
        $idDiscount = mysql_insert_id();
        $success = '<div class="alert alert-success">Коди успішно згенеровано!</div>';
        $arrayCode = Array();
//        while (true){
//            $code = strtoupper(substr(md5(rand(0,1000000)).time(), 0,6));
//            if (!in_array($code, $arrayCode)){
//                if (!$infoCode = getOneString("SELECT * FROM `ls_discountsCode` WHERE `code` = '".$code."'")){
//                    $arrayCode[] = $code;
//                }
//            }
//            if (count($arrayCode)>=intval($_POST['number'])){
//                break;
//            }
//        }
//        foreach ($arrayCode as $v){
//            mysql_query("INSERT INTO `ls_discountsCode` (`code`, `discountId`) VALUES ('".$v."', '".$idDiscount."');");
//        }
    }
}
$body_admin .= '
<h4>Новий блок знижок</h4>
'.$success.'
<form action="" method="POST">
    <div class="row">
        <div class="span6">
            <table class="table table-bordered table-striped">
                <tr>
                    <td>Назва блоку кодів:</td>
                    <td><input type="text" name="name" class="form-control"></td>
                </tr>
                <tr>
                    <td>% знижки:</td>
                    <td><input type="number" name="discount" class="form-control" min="1"></td>
                </tr>
                <tr>
                    <td>Код на знижку:</td>
                    <td><input type="text" name="code" class="form-control"></td>
                </tr>
                <tr>
                    <td>Дата закінчення:</td>
                    <td><input type="text" name="date" class="form-control" placeholder="'.date("Y-m-d").'"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" class="btn btn-success" value="Додати">
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>
';