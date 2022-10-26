<?php
require ('config.php');
require ('admin/engine/params/functions.php');
require ('admin/engine/products/functions.php');
require ('admin/engine/reference/functions.php');
require ('admin/engine/template/functions.php');
require ('include/functions.php');
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
$sql = "select id, pib, number_phone, email, adress, dostavka, time, discount, ship_date, number_declaration, admin_note, dop_info, `status` from ls_orders where uniq_user='".$_GET['uniq']."';";
$results = mysql_query($sql);
$info_order = mysql_fetch_array($results, MYSQL_ASSOC);

if ($number)
{
	$item_template = return_one_template(27);
	$item_template = $item_template['template'];
        $body .= '
        <head> 
        <title>Статус заказа</title> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        </head>
        <div style="padding:10px; border: 1px solid black; width: 600px;" align="center">
        <b>'.$lang[263].' №'.$info_order['id'].'</b>
        <h2 id="title">'.$lang[262].' '.$info_order['pib'].'</h2>
	<table style="width:100%;">
	<tr>
		<td align="center" style="background-color:#E5E5E5;"></td>
		<td align="center" style="background-color:#E5E5E5;">Название</td>
		<td align="center" style="background-color:#E5E5E5;">Цена</td>
		<td align="center" style="background-color:#E5E5E5;">Количество</td>
	</tr>
        ';
        foreach ($array as $v)
	{
		$infoItem = mysql_fetch_array (mysql_query("SELECT * FROM `ls_items` where `id` = '".$v['id_item']."';"), MYSQL_ASSOC);
        if ($infoItem['price_2']!=0)
            $infoItem['price_1'] = $infoItem['price_2'];
		if ($infoItem['select_6']==46)
		{
		    $idElements = str_replace('select|', '', $v['other_param']);
		    $infoSize = mysql_fetch_array(mysql_query("SELECT * FROM `ls_translate` where `id_elements` = '".$idElements."' and `type` = 'select_value';"), MYSQL_ASSOC);
		    $size = ' <b>Ваш размер: '.$infoSize['text'].'</b>';
		}
		$body .= '
		<tr>
			<td align="center"><img src="'.$config ['site_url'].'resize_image.php?filename='.urlencode('upload/userparams/'.getMainImage ($infoItem['id'])).'&const=130&width=106&height=106&r=255&g=255&b=255" width="106" height="106" alt="small"></td>
			<td align="center">'.$infoItem['text_1'].' <div>'.$size.'</div></td>
			<td align="center">'.$infoItem['price_1'].' грн.</td>
			<td align="center">'.$v['number'].'</td>
		</tr>
		';
		$allprice += $infoItem['price_1']*$v['number'];
	}
	$body .= '</table>';
	$body .= $item_template;
	$item_template = return_one_template($config['user_params_19']);
	$item_template = $item_template['template'];
	$body .= $item_template;
	$body .= '<table>';
	$allsuma = $allprice+$config['user_params_16'];
	$results = mysql_query("SELECT `value` FROM `ls_reference_values_translate` where `id_reference_value` = '".$info_order['dostavka']."' and `id_lang` = '".$id_online_lang."' limit 0,1;");
	$info_dostavka = mysql_fetch_array($results, MYSQL_ASSOC);
	if ($info_order['discount'])
	{
		$discount = $lang[265].': '.$info_order['discount'].' '.$valuta.'. <br>';
		$allsuma = $allsuma - $info_order['discount'];
	}
	
		if (strlen($info_order['ship_date'])>0)
		{
			$ship_date = $lang[266].' '.$info_order['ship_date'].'<br>';
		}
//        print_r ($info_order);
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
		$dostavka = $info_dostavka['value'];
	$valuta = 'грн';
	$body .= '
	<tr>
		<td>
			<div style="font-weight:bold;">'.$lang[237].'</div>
			<b>'.$info_order['dop_info'].'</b>
		</td>
	</tr>
	<tr>
		<td align="right">
			<span style="color:red">
				<!--'.$lang[227].' '.$allprice.' '.$valuta.'. <br>-->
				<!--'.$lang[228].' '.$config['user_params_16'].' '.$valuta.'. <br>-->
				'.$discount.'
				'.$lang[229].' '.$allsuma.' '.$valuta.'. <br>
				'.$discount_form.'
				<div id="tmp"></div>
			</span>
		</td>
	</tr>
	<tr>
	<td>
	<div id="save_successful" align="center"></div>
	<b>'.$lang[232].': '.$pib_save.'<br>
	'.$lang[233].' '.$number_phone.'
	'.$lang[234].' '.$email_save.'
	'.$lang[235].' '.$adress.'<br>
	'.$lang[78].': '.date("d.m.Y H:i:s", $info_order['time']).'<br>
	'.$ship_date.'
	'.$number_declaration.'
	'.$admin_textarea.'
	</b>
	'.$cl_form.'
	</td>
	</tr>
	</table>
	</div>
	<h1>'.$status.'</h1>
	<div id="test"></div>
	';
	$body = str_replace ('{lang}', $alt_name_online_lang.'/', $body);
	$body = language ($body, ''); 
	print $body;
}
?>
