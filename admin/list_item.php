<?php
require ('./../config.php');
require ('engine/params/functions.php');
require ('engine/products/functions.php');
require ('engine/reference/functions.php');
require ('engine/template/functions.php');
require ('./../include/functions.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8'");
$lang_file_array = return_my_language ();
foreach ($lang_file_array as $v)
{
    require_once($v);
}
if (isset ($_COOKIE['lang']) or isset ($_GET['lang']))
{
    if (isset ($_COOKIE['lang']))
    {
        $alt_name_online_lang = $_COOKIE['lang'];
    } else {
        $alt_name_online_lang = $_GET['lang'];
    }
    $info_for_my_lang = return_info_for_lang_for_alt_name($alt_name_online_lang);
    $id_online_lang = $info_for_my_lang['id'];
} else {
    $info_for_my_lang = return_info_for_lang_for_alt_name($config ['default_language']);
    $id_online_lang = $info_for_my_lang['id'];
}
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
if (strlen($alt_name_online_lang)<2)
{
    $alt_name_online_lang = $info_for_my_lang['alt_name'];
}
$sql = "select * from ls_cart where uniq_user='".$_GET['uniq']."' and status = '1';";
$results = mysql_query($sql);
$number = @mysql_num_rows ($results);
if ($number>0)
{
    for ($i=0; $i<$number; $i++)
    {
        $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);
    }
}

$sql = "select * from ls_orders where id='".intval($_GET['id'])."';";
$results = mysql_query($sql);
$info_order = mysql_fetch_array($results, MYSQL_ASSOC);
$sql = "SELECT * FROM `ls_discounts` WHERE `code` = '".$info_order['promocode']."' AND `status` = 1 AND `date` >= '".date("Y-m-d")."';";
if ($infoCode = getOneString($sql)){
    $promocode = $infoCode['code'];
    $promocodeHtml = '<div style="font-weight: bold; color: red;">Промокод: '.$promocode.'</div>';
    if ($arrayCodeDb = getArray("SELECT * FROM `ls_discountsCode` WHERE `discountId` = '".$infoCode['id']."'")){
        $arrayCode = Array();
        foreach ($arrayCodeDb as $oneCode){
            $arrayCode[] = $oneCode['itemId'];
        }
    }
}

$item_template = return_one_template($config['user_params_17']);
$item_template = $item_template['template'];
if ($_COOKIE['id_user_online']!=1 or isset ($_GET['print']))
{
    $body .= '
		<head> 
		<title>Статус заказа</title> 
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		</head>
		<div style="padding:10px; border: 1px solid black; width: 600px;" align="center">
		<b>'.$lang[263].' №'.$info_order['id'].'</b>
		<h2 id="title">'.$lang[262].' '.$info_order['pib'].'</h2>
		'.$promocodeHtml.'
		';
    $body .= $item_template;
} else {
    $body .= '
		<div style="padding:10px; border: 1px solid black; width: 600px;" align="center">
		<b>'.$lang[263].' №'.$info_order['id'].'</b>
		<h2 id="title">'.$lang[262].' '.$info_order['pib'].'</h2>
		'.$promocodeHtml.'
		';
    $body .= $item_template;
}
foreach ($array as $v)
{
    /*$info_item = return_item_info ($v['id_item'], $id_online_lang);
    $image = $info_item[$config ['topland_2']];
    $image = $image[0]['text'];
    $image_small = $config ['site_url'].'resize_image.php?filename='.urlencode('upload/userparams/'.$image).'&const=100&width=100';
    $price = $info_item[$config ['topland_4']];
    $price = $price['text'];
    $array_price = explode ('|', $price);
    $price = $array_price[0].' '.$array_price[1];
    $allprice = $allprice + $array_price[0];
    $valuta = $array_price[1];
    $name_item = $info_item[$config ['topland_3']];
    $name_item = $name_item['text'];
    $other_param = $v['other_param'];
    $array_other_param = explode ('||', $other_param);
    foreach ($array_other_param as $v_other)
    {
        $array_v_other = explode ('|', $v_other);
        switch ($array_v_other[0])
        {
            case "color_radio":
                $color_html = '<img src="'.$config ['site_url'].'/upload/select_params/'.$array_v_other[1].'" style="border: 1px solid black;">';
            break;
            case "rozm":
                $rozm_html = $array_v_other[1];
            break;
        }
    }
    $articul = $info_item[$config ['topland_7']];
    $articul = $articul['text'];*/
    $item_template = return_one_template($config['user_params_18']);
    $item_template = $item_template['template'];
    //$info_item = return_item_info ($v['id_item'], $id_online_lang);
    $array_template = returnSubstrings($item_template, '{template_', '}');
//		$info_item = return_item_info ($v['id_item'], $id_online_lang);
    $info_item = mysql_fetch_array(mysql_query("SELECT * FROM `ls_items` where `id` = '".$v['id_item']."';"), MYSQL_ASSOC);
    $html_item = $item_template;
    $sql = "select * from `ls_items` where `id` = '".$v['id_item']."';";
    $results = mysql_query($sql);
    $info_item_ls_item = mysql_fetch_array($results, MYSQL_ASSOC);
    foreach ($array_template as $v_template)
    {
        $a_template = explode ('_', $v_template);
        if ($a_template[1]==$info_item_ls_item['id_card'])
        {
            $add_template = return_one_template($a_template[0]);
            $add_template = $add_template['template'];
            $html_item = str_replace ('{template_'.$v_template.'}', $add_template, $html_item);
        } else {
            $html_item = str_replace ('{template_'.$v_template.'}', '', $html_item);
        }
    }
    $array_image = returnSubstrings($html_item, '{image_', '}');
    $array_text = returnSubstrings($html_item, '{text_', '}');
    $array_price = returnSubstrings($html_item, '{price_', '}');
    $array_select = returnSubstrings($html_item, '{select_', '}');
    $array_name = returnSubstrings($html_item, '{name_', '}');
    //print_r ($info_item);
    foreach ($array_image as $image_id)
    {
        $a_img = explode ('_', $image_id);
        $image = $info_item['image'.$a_img[0]];
        $image = $image[($a_img[1]-1)]['text'];
        $html_item = str_replace ('{image_'.$image_id.'}', urlencode(getMainImage($v['id_item'])), $html_item);
    }
    foreach ($array_text as $text_id)
    {
        $a_text = explode ('_', $text_id);
        $text = $info_item['text'.$a_text[0]];
        $text = $text['text'];
        if ($text_id == 1)
        {
            if ($info_item['select_6']==46)
            {
                $idElements = str_replace('select|', '', $v['other_param']);
                $infoSize = mysql_fetch_array(mysql_query("SELECT * FROM `ls_translate` where `id_elements` = '".$idElements."' and `type` = 'select_value';"), MYSQL_ASSOC);
                $size = ' <br><b>'.$infoSize['text'].'</b>';
            } else {
                if ($info_item['select_6']==48){
                    $idElements = str_replace('select|', '', $v['other_param']);
                    $infoSize = mysql_fetch_array(mysql_query("SELECT * FROM `ls_translate` where `id_elements` = '".$idElements."' and `type` = 'select_value';"), MYSQL_ASSOC);
                    $size = '<br><b>'.$infoSize['text'].'</b>';
                } else {
                    $idElements = str_replace('select|', '', $v['other_param']);
                    $infoSize = mysql_fetch_array(mysql_query("SELECT * FROM `ls_translate` where `id_elements` = '".$idElements."' and `type` = 'select_value';"), MYSQL_ASSOC);
                    $size = '<br><b>'.$infoSize['text'].'</b>';
                }
            }
        }
        $html_item = str_replace ('{text_'.$text_id.'}', $info_item['text_'.$text_id].$size, $html_item);
        unset ($size);
    }
//        $allprice = 0;
    foreach ($array_price as $price_id)
    {
        if ($info_item['price_2']!=0)
        {
            $info_item['price_'.$price_id] = $info_item['price_2'];
        }
        $price = $info_item['price'.$price_id];
        if ($v['price']!='0.00'){
            $info_item['price_1'] = $v['price'];
        }
        if (in_array($info_item['id'], $arrayCode) or (empty($arrayCode) and isset ($infoCode) and !empty($infoCode))){
            $info_item['price_1'] = ceil($info_item['price_1']-($info_item['price_1']/100*$infoCode['percent']));
        }
        $price = $price['text'];
        $array_price_new = explode ('|', $price);
        $html_item = str_replace ('{price_'.$price_id.'}', $info_item['price_'.$price_id], $html_item);
        $html_item = str_replace ('{valuta}', $array_price_new[1], $html_item);
        $allprice = $allprice + ($info_item['price_'.$price_id]*$v['number']);
        $valuta = $array_price_new[1];
    }
    foreach ($array_select as $select_id)
    {
        $select = $info_item['select'.$select_id];
        foreach ($select as $key => $vSel)
        {
            $html_select .= $vSel['text'];
            if (isset ($select[$key+1]))
            {
                $html_select .= ',';
            }
        }
        $html_item = str_replace ('{select_'.$select_id.'}', getOneValueText($info_item['select_'.$select_id]), $html_item);
        unset ($html_select);
    }
    foreach ($array_name as $name_id)
    {
        $a_name = explode ('_', $name_id);
        switch ($a_name[0])
        {
            case "select":
                $one_name = return_one_translate ($a_name[1], $id_online_lang, 'select');
                break;
        }
        $html_item = str_replace ('{name_'.$name_id.'}', $one_name['text'], $html_item);
    }
    $html_item = str_replace ('{id_item}', $v['id_item'], $html_item);
    $html_item = str_replace ('{number}', $v['number'], $html_item);
    $html_item = str_replace ('{link}', '{main_sait}{lang}/mode/item-'.$v['id_item'].'.html', $html_item);
    $html_item = str_replace ('{main_sait}', $config['site_url'], $html_item);
    $body .= $html_item;
}
$item_template = return_one_template($config['user_params_19']);
$item_template = $item_template['template'];
$body .= $item_template;
$body .= '<table style="width: 600px;">';
$allsuma = $allprice+$config['user_params_16'];
$results = mysql_query("SELECT `value` FROM `ls_reference_values_translate` where `id_reference_value` = '".$info_order['dostavka']."' and `id_lang` = '".$id_online_lang."' limit 0,1;");
$info_dostavka = mysql_fetch_array($results, MYSQL_ASSOC);
if ($info_order['discount'])
{
    $discount = $lang[265].': '.$info_order['discount'].' грн. <br>';
    $allsuma = $allsuma - $info_order['discount'];
}
if (!isset ($_GET['print']))
{
    $discount_form = ''.$lang[264].' <input type="text" name="discount" id="discount" size="3" style="border: 1px solid black;" value="'.$info_order['discount'].'"> грн.
		<img src="'.$config ['site_url'].'images/admin/save.png" onclick="save_discount(\''.$info_order['id'].'\');"><br>
		';
    $cl_form = '<div align="right"><img src="'.$config ['site_url'].'images/admin/cancel__red.png" onclick="
	var temporary = document.getElementById(\'temporary\');  
	temporary.innerHTML = \'\';"></div>';
    $email_save = '<input type="text" name="email" id="email_'.$info_order['id'].'" value="'.$info_order['email'].'">
		';
    $pib_save = '<input type="text" name="pib" id="pib_'.$info_order['id'].'" value="'.$info_order['pib'].'" size="30">
		';
    $number_phone = '<input type="text" name="number_phone" id="number_phone_'.$info_order['id'].'" value="'.$info_order['number_phone'].'" size="13">
		';
    $adress = '
		<input type="text" name="adress" id="adress_'.$info_order['id'].'" value="'.$info_order['adress'].'" size="13">
		';
    $array_values_for_reference = return_all_values_for_reference($config['user_params_32']);
//        $oplata = return_all_translate_for_reference_value($info_order['oplata'], $id_online_lang);
    switch ($info_order['oplata']){
        case 0:
            $oplata = 'LiqPay';
            break;
        case 2:
            $oplata = 'На карту приват банка';
            break;
        case 1:
            $oplata = 'Наложеный платеж';
            break;
    }
    foreach ($array_values_for_reference as $v)
    {
        $info_translate_value_ref = return_all_translate_for_reference_value($v['id'], $id_online_lang);
        if ($info_translate_value_ref[0]['value']==$info_dostavka['value'])
        {
            $select_options .= '<option value="'.$v['id'].'" selected>'.$info_translate_value_ref[0]['value']."</option>\r\n";
        } else {
            $select_options .= '<option value="'.$v['id'].'">'.$info_translate_value_ref[0]['value']."</option>\r\n";
        }
    }
    $dostavka = '<select name="dostavka" id="dostavka_'.$info_order['id'].'" onchange="user_info_save(\''.$info_order['id'].'\', \'dostavka\');">
												'.$select_options.'
		</select>';

    $admin_textarea = '
		<br><div align="center">
		<b>'.$lang[272].'</b><br>
		    <textarea name="admin_note" id="admin_note_'.$info_order['id'].'" style="width: 100%; height: 50px;">'.$info_order['admin_note'].'</textarea><br>
		</div>
		<div id="successSaveChangeOrder_'.$info_order['id'].'"></div>
		<a class="btn btn-success"  style="cursor: pointer;" onclick="saveChangeOrder('.$info_order['id'].');">Сохранить изменения</a>
		';
    if ($info_order['sendSms']==1){
        $sendSmsSuccess = '
            <div class="row" style="border-botton: 1px solid #cccccc;">
                <div class="span5" style="text-align: center;">
                    <div class="alert alert-success">
                        <b>Внимание!!! Вы уже отправляли номер декларации по этому заказу.</b>
                    </div>
                </div>
            </div>
            ';
    }
    $infoRegion = getOneString("SELECT * FROM `ls_region` WHERE `id` = '".intval($info_order['region'])."';");
    $infoCity = getOneString("SELECT * FROM `ls_cities` WHERE `id` = '".intval($info_order['city'])."';");
    $infoWarehouse = getOneString("SELECT * FROM `ls_warehouses` WHERE `id` = '".intval($info_order['warehouse'])."';");
    if ($info_order['dostavka']==2){
        $onlyNP = '<div class="row">
            <div class="span2">Номер склада:</div>
            <div class="span3"><select id="warehouse_'.$info_order['id'].'" class="form-control"><option value="0">Сделайте выбор</option>'.getWarehouseOld($infoCity['id'], $infoWarehouse['id']).'</select></div>
        </div>';
    } else {
        $onlyNP = '
            <div class="row">
                <div class="span2">Адреса:</div>
                <div class="span3">'.$info_order['adress'].'</div>
            </div>
            ';
    }
    $infoOrderHtml = '
        <div class="row">
            <div class="span2">'.$lang[232].':</div>
            <div class="span3">'.$pib_save.'</div>
        </div>
        <div class="row">
            <div class="span2">'.$lang[233].':</div>
            <div class="span3">'.$number_phone.'</div>
        </div>
        <div class="row">
            <div class="span5" id="successCreditCardSend_'.$info_order['id'].'"></div>
        </div>
        <div class="row">
            <div class="span5" style="margin-bottom: 5px; text-align: center;">
                <div class="input-group">
                  <input type="text" class="form-control" id="suma_'.$info_order['id'].'" placeholder="Сума">
                  <span class="input-group-btn">
                    <a style="cursor: pointer;" class="btn btn-success" onclick="if (confirm(\'Вы точно хотите отправить номер карточки этому клиенту?\')) { sendSmsWidthCreditCard(\'number_phone_'.$info_order['id'].'\', \'successCreditCardSend_'.$info_order['id'].'\', \''.$info_order['id'].'\')}">отправить номер карточки приват банка</a>
                  </span>
                </div><!-- /input-group -->
            </div>
        </div>
        <div class="row">
            <div class="span2">'.$lang[234].':</div>
            <div class="span3">'.$email_save.'</div>
        </div>
        <div class="row">
            <div class="span2">Область:</div>
            <div class="span3"><select id="region_'.$info_order['id'].'" class="form-control" onchange="getCityAdmin('.$info_order['id'].');"><option value="0">Сделайте выбор</option>'.getRegion($infoRegion['id']).'</select></div>
        </div>
        <div class="row">
            <div class="span2">'.$lang[235].':</div>
            <div class="span3"><select id="city_'.$info_order['id'].'" class="form-control" onclick="getWarehouseAdmin('.$info_order['id'].');"><option value="0">Сделайте выбор</option>'.getCity($infoRegion['id'], $infoCity['id']).'</select></div>
        </div>
        '.$onlyNP.'
        <div class="row">
            <div class="span2">'.$lang[78].':</div>
            <div class="span3">'.date("d.m.Y H:i:s", $info_order['time']).'</div>
        </div>
        <div class="row">
            <div class="span2" style="font-weight: bold; color: red;">Оплата:</div>
            <div class="span3" style="font-weight: bold; color: red;">'.$oplata.'</div>
        </div>
        <div class="row">
            <div class="span2" style="font-weight: bold; color: red;">Номер склада:</div>
            <div class="span3"><input type="text" id="numbSkl_'.$info_order['id'].'" value="'.$info_order['numberSkl'].'" style="border: 1px solid red;""></div>
        </div>
        <div class="row" style="border-top: 1px solid #cccccc; margin-top: 10px;">
            <div class="span2">
                <div style="font-weight: bold;">Дата получения</div>
                <input type="text" name="ship_date" class="input-small" id="ship_date_'.$info_order['id'].'" size="8" value="'.$info_order['ship_date'].'" placeholder="Дата получения">
            </div>
            <div class="span3">
                <div style="font-weight: bold;">Номер декларации</div>
                <input type="text" name="number_declaration" id="number_declaration_'.$info_order['id'].'" size="14" value="'.$info_order['number_declaration'].'" placeholder="Номер декларации">
            </div>
        </div>
        <div id="successSaveDeclarationAndSendSms_'.$info_order['id'].'"></div>
        '.$sendSmsSuccess.'
        <div class="row" style="border-botton: 1px solid #cccccc;">
            <div class="span5" style="text-align: center;"><a class="btn btn-inverse btn-mini" onclick="saveDeclarationAndSendSms('.$info_order['id'].');" style="cursor: pointer;">сохранить и отправить смс</a></div>
        </div>
            ';
} else {
    if (strlen($info_order['ship_date'])>0)
    {
        $ship_date = $lang[266].' '.$info_order['ship_date'].'<br>';
    }
    switch ($info_order['status'])
    {
        case 0:
            $status_of_order = $lang[252];
            break;
        case 1:
            $status_of_order = $lang[253];
            break;
        case 2:
            $status_of_order = $lang[254];
            break;
        case 3:
            $status_of_order = $lang[255];
            break;
    }
    $status = '<srtong style="color:red;">'.$lang[450].' '.$status_of_order.'</strong>';
    $email_save = $info_order['email'];
    $pib_save = $info_order['pib'];
    $number_phone = '<span style="color:#045CC7;">'.$info_order['number_phone'].'</span><br>';
    $adress = $info_order['adress'];
    $infoRegion = getOneString("SELECT * FROM `ls_region` WHERE `id` = '".intval($info_order['region'])."';");
    $infoCity = getOneString("SELECT * FROM `ls_cities` WHERE `id` = '".intval($info_order['city'])."';");
    $infoWarehouse = getOneString("SELECT * FROM `ls_warehouses` WHERE `id` = '".intval($info_order['warehouse'])."';");
//        $dostavka = getRegion($infoRegion['id']).', '.getCity($infoRegion['id'], $infoCity['id']).', '.getWarehouse($infoCity['id'], $infoWarehouse['id']);
    $infoOrderHtml = '
        <b>'.$lang[232].': '.$pib_save.'<br>
	'.$lang[233].' '.$number_phone.'
	'.$lang[234].' '.$email_save.'
	'.$lang[235].' '.$adress.'<br>
	'.$lang[236].' '.$dostavka.'<br>
	'.$lang[78].': '.date("d.m.Y H:i:s", $info_order['time']).'<br>
	'.$ship_date.'
	'.$number_declaration.'
	Номер склада: '.$info_order['numberSkl'].'<br>
        ';
}
$certPrice = 0;
if ($arrayCertUse = getArray("SELECT * FROM `ls_certificate` WHERE `orderId` = '".$info_order['id']."'")){
    foreach ($arrayCertUse as $oneCert){
//                $useCert .= '<tr>
//                    <td>'.getOneString("SELECT * FROM `ls_certificate` WHERE `id` = '".$oneCert['codeId']."';")['code'].'</td>
//                    <td>-'.$oneCert['price'].' грн.</td>
//                </tr>';
//            if ($infoGlobalCode = getOneString("SELECT * FROM `ls_certificate` WHERE `id` = '".$oneCert['codeId']."'")){
//                mysql_query("UPDATE `ls_cartCertificate` SET `used` = 1 WHERE `id` = '".$oneCert['id']."';");
//                if ($infoGlobalCode['used']==0){
//                    mysql_query("UPDATE `ls_certificate` SET `used` = 1, `orderId` = '".$idOrder."' WHERE `id` = '".$infoGlobalCode['id']."';");
        $allsuma = $allsuma - $oneCert['price'];
        $certPrice = $certPrice+$oneCert['price'];
//                }
//            }
    }
}
$body .= '
	<tr>
	    <td>
	    Отправить СМС:
	    <div class="input-append">
          <select id="smsInfo_'.$info_order['id'].'">
            <option value="Ваш заказ принят и  в ближайшее время будет отправлен">Ваш заказ принят и  в ближайшее время будет отправлен</option>
            <option value="Ваш заказ принят, с Вами свяжется наш менеджер">Ваш заказ принят, с Вами свяжется наш менеджер</option>
          </select>
          <a class="btn" onclick="sendInfoSms('.$info_order['id'].');">Отправить</a>
        </div>
        <div id="successSendSmsInfo_'.$info_order['id'].'" style="margin-top: 5px;"></div>
	    </td>
	</tr>
	<tr>
		<td>
			<div style="font-weight:bold;">'.$lang[237].'</div>
			<b>'.$info_order['dop_info'].'</b>
		</td>
	</tr>
	<tr>
		<td align="right">
			<span style="color:red">
				'.$lang[227].' '.$allprice.' грн. <br>
				<div>Використані сертифікати: '.$certPrice.' грн.</div>
				Скидка: <input type="text" value="'.$info_order['discount'].'" id="discount_'.$info_order['id'].'" class="span1"> грн. <br>
				'.$lang[229].' '.$allsuma.' грн. <br>
				<div id="tmp"></div>
			</span>
		</td>
	</tr>
	<tr>
	<td>
	<div id="save_successful" align="center"></div>
	'.$infoOrderHtml.'
	'.$admin_textarea.'
	'.$cl_form.'
	</td>
	</tr>
	</table>
	</div>
	'.$status.'
	<div id="test"></div>
	';
if ($_COOKIE['id_user_online']!=1 or !isset ($_GET['print']))
{
    $body .= '
		<a href="'.$config['site_url'].'/admin/list_item.php?uniq='.$_GET['uniq'].'&print" target="_blank">[печать бланка заказа]</a>
		';
}
$body = str_replace ('{lang}', $alt_name_online_lang.'/', $body);
$body = language ($body, '');
print $body;

?>