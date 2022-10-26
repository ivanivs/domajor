<?php
$start = microtime(true);
require ('./../config.php');
require ('./../include/functions.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8'");
$sql = "SELECT `id`,`value` FROM ls_settings;";
$results = mysql_query($sql);
$number = mysql_num_rows ($results);
for ($i=0; $i<$number; $i++)
{
    $array_config_param[] = mysql_fetch_array($results, MYSQL_ASSOC);
}
foreach ($array_config_param as $key => $v)
{
    $config['user_params_'.$v['id']] = $v['value'];
}
if (isset ($_COOKIE['accessLevel']) and $_COOKIE['accessLevel']==100){
    if ($_POST['registerUser']==1){
        if ($arrayRegister = getArray("SELECT `login` FROM `ls_users` GROUP by `login`;")){
            foreach ($arrayRegister as $v){
                if (strlen(intval($v['login']))>0 and intval($v['login'])!=0){
                    $v['login'] = str_replace('(', '', $v['login']);
                    $v['login'] = str_replace(')', '', $v['login']);
                    $v['login'] = str_replace(' ', '', $v['login']);
                    $v['login'] = str_replace('-', '', $v['login']);
                    if (substr_count($v['login'], '+30')>0)
                        $v['login'] = str_replace('+30', '+380', $v['login']);
                    if (substr_count($v['login'], '+3838')>0)
                        $v['login'] = str_replace('+3838', '+38', $v['login']);
                    $v['login'] = str_replace('+38', '', $v['login']);
//                    $v['login'] = str_replace(', '', $v['login']);
                    if (substr( $v['login'], 0, 2)=='38')
                    {
                        $v['login'] = substr( $v['login'], 2, 15);
//                        unset ($v['login'][0]);
//                        unset ($v['login'][1]);
                    }

                    if (!in_array($v['login'], $arrayTrueNumber)){
                        $arrayTrueNumber[] = $v['login'];
                        $arrayRegisterTrue[] = $v['login'];
                    }
                }
            }
        }
    }
    if ($_POST['orderUser']==1){
        if ($arrayRegister = getArray("SELECT `number_phone` FROM `ls_orders` GROUP by `number_phone`;")){
            foreach ($arrayRegister as $v){
                if (strlen(intval($v['number_phone']))>0 and intval($v['number_phone'])!=0){
                    $v['number_phone'] = str_replace('(', '', $v['number_phone']);
                    $v['number_phone'] = str_replace(')', '', $v['number_phone']);
                    $v['number_phone'] = str_replace(' ', '', $v['number_phone']);
                    $v['number_phone'] = str_replace('-', '', $v['number_phone']);
                    if (substr_count($v['number_phone'], '+30')>0)
                        $v['number_phone'] = str_replace('+30', '+380', $v['number_phone']);
                    if (substr_count($v['number_phone'], '+3838')>0)
                        $v['number_phone'] = str_replace('+3838', '+38', $v['number_phone']);
                    $v['number_phone'] = str_replace('+38', '', $v['number_phone']);
//                    $v['number_phone'] = str_replace('+37', '', $v['number_phone']);
                    if (substr( $v['number_phone'], 0, 2)=='38')
                    {
                        $v['number_phone'] = substr( $v['number_phone'], 2, 15);
//                        unset ($v['login'][0]);
//                        unset ($v['login'][1]);
                    }
                    if (!in_array($v['number_phone'], $arrayTrueNumber)){
                        $arrayTrueNumber[] = $v['number_phone'];
                        $arrayOrder[] = $v['number_phone'];
                    }
                }
            }
        }
    }
    if (is_array($arrayTrueNumber)){
        $body .= '
        <table class="table table-striped" style="margin-top: 10px;">
        <thead>
            <th style="width: 15px;">ID</th>
            <th>Номер</th>
            <th>Тип</th>
            <th>Стоимость</th>
            <th>Отправка</th>
        </thead>
        <tbody>
        ';
        $allPrice = 0;
        foreach ($arrayTrueNumber as $key => $v){
            if (in_array($v, $arrayRegisterTrue)){
                $type = '<b>Зарегестрированный</b>';
            }
            if (in_array($v, $arrayOrder)){
                $type = '<b>Заказ</b>';
            }
//            $price = getSmsPrice($v);
            $allPrice = $allPrice + $price;
            $body .= '
            <tr id="tr_number_'.$key.'">
                <td>'.($key+1).'</td>
                <td><div id="number_'.$key.'">'.$v.'</div><div id="del_number_'.$key.'"></div></td>
                <td>'.$type.'</td>
                <td><div id="price_'.$key.'"></div></td>
                <td><div id="sendSms_'.$key.'"></div></td>
            </tr>';
            $bodySms .= $v."\r\n";
        }
        $body .= '
        </tbody>
        </table>';
    }
    $time = microtime(true) - $start;
    $check_balance_alphasms = file_get_contents ('http://alphasms.com.ua/api/http.php?version=http&login='.$config['user_params_1'].'&password='.$config['user_params_2'].'&command=balance');
    $tmp = explode (':', $check_balance_alphasms);
    if ($tmp[1]>10)
    {
        $balance = '<span style="color:green;">На вашем счету: <b>'.round($tmp[1], 2).$lang[282].'</b></span>';
    } else {
        $balance = '<span style="color:red;">На вашем счету: <b>'.round($tmp[1], 2).$lang[282].'</b></span>';
    }
    $body = '
    <div style="float: right;">
        <input type="hidden" id="maxId" value="'.$key.'">
        <button class="btn btn-danger" onclick="getPrice();">Подсчитать стоимость</button>
        <button class="btn btn-danger" onclick="if (confirm(\'Подтвердите отправку СМС!\')) {sendSms();}">Отправить</button>
    </div>
    <div style="margin-top: 20px;">
        <div>'.$balance.'</div>
        <div><b>Номеров: '.count($arrayTrueNumber).'</b></div>
        <div><b>Стоимость: <span id="allPrice" style="color:red; font-weight: bold;">0</span> грн.</b></div>
        <div><b>Минимальная стоимость одного СМС: <span id="minimalPrice" style="color:red; font-weight: bold;">0</span> грн.</b></div>
        <div><b>Максимальная стоимость одного СМС: <span id="maximalPrice" style="color:red; font-weight: bold;">0</span> грн.</b></div>
        <div><b>Подсчитана стоимость для номеров (только валидные): <span id="priceNumberCount" style="color:red; font-weight: bold;">0</span> шт.</b></div>
        <div><b>Не валидных номеров: <span id="notValid" style="color:red; font-weight: bold;">0</span> шт.</b></div>
        <div><b>Отправлено смс для номеров: <span id="sendSmsCount" style="color:red; font-weight: bold;">0</span> шт.</b></div>
    </div>
    '.$body.'<br><textarea style="width: 400px; height: 200px;">'.$bodySms.'</textarea>';
    echo $body;

//    echo getSmsPrice('+380949091035');
} else {
    echo '
    <div class="alert alert-error">
        <b>Внимание!</b> Ошибка доступа, Вы не обладаете правами администратора!
    </div>
    ';
}