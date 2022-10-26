<?php
ini_set('memory_limit', '-1');
require_once ('./../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8';");
require_once ('./../include/functions.php');
require_once ('./../include/cookie.php');
if (isset ($_GET['lang']))
{
	setcookie ("lang", $_GET['lang'], time() + $config['time_life_cookie']);
}
$lang_file_array = return_my_language ();
foreach ($lang_file_array as $v)
{
	require_once($v);
}
$menu_language = return_select_lang ('index.php', '90px', '#F5FAF8', '');
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
	setcookie ("id_online_lang", $id_online_lang, time() + $config['time_life_cookie']);
} else {
	$info_for_my_lang = return_info_for_lang_for_alt_name($config ['default_language']);
	$id_online_lang = $info_for_my_lang['id'];
	setcookie ("id_online_lang", $id_online_lang, time() + $config['time_life_cookie']);
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
if (!isset ($_COOKIE['id_user_online']) or $_COOKIE['accessLevel']!=100)
{
	$admin_page = file_get_contents ('template/admin.tpl');
	$admin_page = language ($admin_page, '');  
} else {
    /*$arrayUser = getArray("SELECT `number_phone` FROM `ls_orders` GROUP by `number_phone`;");
    foreach ($arrayUser as $v){
        $arrayPhone[] = $v['number_phone'];
    }
    $arrayUser = getArray("SELECT `login` FROM `ls_users` GROUP by `login`;");
    foreach ($arrayUser as $v){
        if (in_array($v['login'], $arrayPhone)){
            $arrayPhone[] = $v['login'];
        }
    }
    print count ($arrayPhone);
    foreach ($arrayPhone as $v){
        print $v.'<br>';
    }*/
	require ('menu.php');
	$admin_page = file_get_contents ('template/admin_full.tpl');
	$admin_page = language ($admin_page, '');  
	$admin_page = str_replace ("{menu}", $menu, $admin_page);
	if (!isset ($_GET['do']))
	{
		$check_balance_alphasms = file_get_contents ('http://alphasms.com.ua/api/http.php?version=http&login='.$config['user_params_1'].'&password='.$config['user_params_2'].'&command=balance');
		$tmp = explode (':', $check_balance_alphasms);
		if ($tmp[1]>10)
		{
			$balance = '<span style="color:green;"><b>'.$tmp[1].$lang[282].'</b></span>';
		} else {
			$balance = '<span style="color:red;"><b>'.$tmp[1].$lang[282].'</b></span>';
		}
		$results = mysql_query("SELECT count(*) FROM ls_items;");
		$count_item = mysql_fetch_array($results, MYSQL_ASSOC);
		$count_item = $count_item['count(*)'];
		if (!isset($body_admin))
		$body_admin = '';
		$body_admin .= $lang[281].$balance.'<br>
		'.$lang[283].' <b>'.$count_item.'</b>
		<div><a href="https://kombat.in.ua/driveGoogle/kombat.php" target="_blank">Запустити обробку Комбат прайс та таблицю googleDrive</a></div>
		';
        if (isset ($_POST['startGoogleDrive'])){
            mysql_query("UPDATE `ls_settings` SET `value` = 1 WHERE `id` = '39';");
            $successStart = '<div class="alert alert-success">Задача на оновлення поставлена!</div>';
        }
        $body_admin .= '
        <hr>
        '.$successStart.'
        ';
	} else {
		if (!isset ($body_admin))
		$body_admin = '';
		if (!isset ($other_way))
		$other_way = '';
		if (!isset ($modal))
		$modal = '';
		switch ($_GET['do'])
		{
			case "telegram":
				require ('engine/telegram/index.php');
				break;
			case "search":
				require ('engine/search/index.php');
				break;
            case "discounts":
                require ('engine/discounts/index.php');
                break;
			case "language":
				require ('engine/language/language.php');
				$info = file_get_contents ('engine/language/info.inf');
			break;
			case "params":
				if (!isset ($select_reference))
				$select_reference = '';
				if (!isset ($reference_id))
				$reference_id = '';
				if (!isset ($up))
				$up = '';
				if (!isset ($html_image_radio))
				$html_image_radio = '';
				require ('engine/params/functions.php');
				require ('engine/params/params.php');
				$info = file_get_contents ('engine/params/info.inf');
				break;
			case "reference":
				require ('engine/reference/functions.php');
				require ('engine/reference/reference.php');
				$info = file_get_contents ('engine/reference/info.inf');
			break;
			case "css":
				require ('engine/css/functions.php');
				require ('engine/css/css.php');
				$info = file_get_contents ('engine/css/info.inf');
			break;
			case "products":
				if (!isset ($del_card))
				$del_card = '';
				if (!isset ($array_ligament))
				$array_ligament = '';
				if (!isset ($info_reference))
				$info_reference = '';
				require ('engine/products/functions.php');
				require ('engine/products/products.php');
				$info = file_get_contents ('engine/products/info.inf');
			break;
			case "pages":
				require ('engine/pages/functions.php');
				require ('engine/pages/pages.php');
				$info = file_get_contents ('engine/pages/info.inf');
			break;
			case "settings":
				if (!isset ($body_settings))
				$body_settings = '';
				require ('engine/settings/functions.php');
				require ('engine/settings/settings.php');
				$info = file_get_contents ('engine/settings/info.inf');
			break;
			case "orders":
				require ('engine/orders/functions.php');
				require ('engine/orders/orders.php');
				$info = file_get_contents ('engine/orders/info.inf');
			break;
			case "languages_file":
				require ('engine/languages_file/functions.php');
				require ('engine/languages_file/languages_file.php');
				$info = file_get_contents ('engine/languages_file/info.inf');
			break;
			case "menu":
				require ('engine/menu/functions.php');
				require ('engine/menu/menu.php');
				$info = file_get_contents ('engine/menu/info.inf');
			break;
			case "filtr":
				if (!isset ($status_sql))
				$status_sql = '';
				if (!isset ($parent_name))
				$parent_name = '';
				if (!isset ($filtr_param_html))
				$filtr_param_html = '';
				require ('engine/filtr/functions.php');
				require ('engine/filtr/filtr.php');
				$info = file_get_contents ('engine/filtr/info.inf');
			break;
			case "template":
				if (!isset ($status_sql))
				$status_sql = '';
				require ('engine/template/functions.php');
				require ('engine/template/template.php');
				$info = file_get_contents ('engine/template/info.inf');
			break;
			case "news":
				if (!isset ($lang_html))
				$lang_html = '';
				if (!isset ($lang_news))
				$lang_news = '';
				require ('engine/news/functions.php');
				require ('engine/news/news.php');
				$info = file_get_contents ('engine/news/info.inf');
			break;
			case "payment":
				require ('engine/payment/functions.php');
				require ('engine/payment/payment.php');
				$info = file_get_contents ('engine/payment/info.inf');
			break;
			case "mail":
				require ('engine/mail/functions.php');
				require ('engine/mail/mail.php');
				$info = file_get_contents ('engine/mail/info.inf');
			break;
			case "contact":
				require ('engine/contact/functions.php');
				require ('engine/contact/contact.php');
				$info = file_get_contents ('engine/contact/info.inf');
			break;
			case "import":
				if (!isset ($option_settings))
				$option_settings = '';
				if (!isset ($option_update))
				$option_update = '';
				if (!isset ($option_import))
				$option_import = '';
				if (!isset ($body_file))
				$body_file = '';
				require ('engine/products/functions.php');
				require ('engine/import/functions.php');
				require ('engine/import/import.php');
			break;
			case "price":
				if (!isset ($option_price))
				$option_price = '';
				if (!isset ($option_text))
				$option_text = '';
				if (!isset ($option_card))
				$option_card = '';
				require ('engine/prices/functions.php');
				require ('engine/prices/prices.php');
			break;
			case "ordersUSA":
				require ('engine/ordersUSA/functions.php');
				require ('engine/ordersUSA/ordersUSA.php');
				break;
            case "erase":
                require ('engine/erase/functions.php');
                require ('engine/erase/erase.php');
            break;
            case "users":
                require ('engine/users/functions.php');
                require ('engine/users/index.php');
            break;
            case "sms":
                require ('engine/sms/index.php');
                break;
            case "baner":
                require ('engine/baner/index.php');
                break;
            case "ajax":
                switch ($_GET['ajax']){
					case "removeGiftToItem":
						mysql_query("DELETE FROM `ls_giftToItem` WHERE `id` = '".intval($_POST['id'])."'");
						break;
					case "addGiftToItemId":
						require ('engine/ajax/addGiftToItemId.php');
						break;
					case "addGiftToItem":
						require ('engine/ajax/addGiftToItem.php');
						break;
					case "getModalLoadingHeader":
						require ('engine/ajax/getModalLoadingHeader.php');
						break;
					case "savePhotoPosition":
						$data = json_decode($_POST['data'], true);
						$dataTrue = array_reverse($data);
						foreach ($dataTrue as $key => $oneImg){
							$photoId = str_replace('image_', '', $oneImg);
							$sql = "UPDATE `ls_values_image` SET `position` = '".$key."' WHERE `id` = '".$photoId."'";
							mysql_query($sql);
						}
						break;
					case "removeItemToItem":
						mysql_query("DELETE FROM `ls_itemToItem` WHERE `id` = '".intval($_POST['id'])."';");
						break;
					case "removeItemToItemOtherColor":
						mysql_query("DELETE FROM `ls_itemToItemOtherColor` WHERE `id` = '".intval($_POST['id'])."';");
						break;
					case "addItemToItem":
						require ('engine/ajax/addItemToItem.php');
						break;
					case "addItemToItemOtherColor":
						require ('engine/ajax/addItemToItemOtherColor.php');
						break;
                    case "addRemoveItemToCodeDiscount":
                        require ('engine/ajax/addRemoveItemToCodeDiscount.php');
                        break;
                    case "addMobilePhone":
                        require ('engine/ajax/addMobilePhone.php');
                        break;
                    case "addOtherColor":
                        require ('engine/ajax/addOtherColor.php');
                        break;
                    case "getModelPhone":
                        require ('engine/ajax/getModelPhone.php');
                        break;
                    case "addMobilePhoneToDb":
                        require ('engine/ajax/addMobilePhoneToDb.php');
                        break;
                    case "addMobilePhoneAuto":
                        require ('engine/ajax/addMobilePhoneAuto.php');
                        break;
                    case "removeMarkModel":
                        mysql_query("DELETE FROM `ls_markModel` where `id` = '".intval($_POST['id'])."';");
                        break;
                }
                exit();
                break;
		}
	}
}
if (isset ($info))
{
	$info = '
	<strong
	onmouseover="document.all[\'info\'].style.display=\'block\';"
	onmouseout="document.all[\'info\'].style.display=\'none\';"> 
	<img src="'.$config ['site_url'].'images/admin/help.png"></strong> 
	<div id="info" style="display: none; " align="left"> 
	'.$info.'
	</div>
	';
	$admin_page = str_replace ("{info}", $info, $admin_page);
} else {
	$admin_page = str_replace ("{info}", "", $admin_page);
}
if (isset ($error_message))
{
	$admin_page = str_replace ("{errmsg}", "<div id='errmsg'>".$error_message."</div>", $admin_page);
} else {
	$admin_page = str_replace ("{errmsg}", "", $admin_page);
}
if (!isset ($body_admin))
$body_admin = '';
$admin_page = str_replace ("{body_admin}", $body_admin, $admin_page);
if (isset ($modal))
{
	$admin_page = str_replace ("{modal}", $modal, $admin_page);
} else {
	$admin_page = str_replace ("{modal}", '', $admin_page);
}
$admin_page = str_replace("{style}", $styleOSMS, $admin_page);
$admin_page = str_replace ("{site_url}", $config ['site_url'], $admin_page);
print $admin_page;
mysql_close($mysql_connect_id);
?>
