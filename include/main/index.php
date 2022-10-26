<?php
require ('admin/engine/template/functions.php');
$main_template = return_one_template($config['user_params_9']);
$main_template = $main_template['template'];
$array_template = returnSubstrings($main_template, '{template_', '}');
$array_image = returnSubstrings($main_template, '{image_', '}');
$array_text = returnSubstrings($main_template, '{text_', '}');
$array_price = returnSubstrings($main_template, '{price_', '}');
$array_select = returnSubstrings($main_template, '{select_', '}');
if (isset ($_POST) and isset ($_POST['search_string']))
{
	$results = mysql_query("SELECT `id_item` as id FROM `ls_values_text` where `text` LIKE '%".mysql_escape_string($_POST['search_string'])."%' group by `id_item`;");
	$number = mysql_num_rows ($results);
	for ($i=0; $i<$number; $i++)
	{	
		$array_items[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
	}
	if ($array_items)
	{
		foreach ($array_items as $key => $v)
		{
			$html_item = $main_template;
			$info_item = return_item_info ($v['id'], $id_online_lang);
			$sql = "select * from `ls_items` where `id` = '".$v['id']."';";
			$results = mysql_query($sql);
			$info_item_ls_item = mysql_fetch_array($results, MYSQL_ASSOC);
			if (count($array_template))
			{
				foreach ($array_template as $v_template)
				{
				    $a_template = explode ('_', $v_template);
				    if ($a_template[1]==$info_item_ls_item['id_card'])
				    {
					$add_template = return_one_template($a_template[0]);
					$add_template = $add_template['template'];
					$html_item = str_replace ('{template_'.$v_template.'}', $add_template, $html_item);
					$array_image = returnSubstrings($add_template, '{image_', '}');
					$array_text = returnSubstrings($add_template, '{text_', '}');
					$array_price = returnSubstrings($add_template, '{price_', '}');
					$array_select = returnSubstrings($add_template, '{select_', '}');
				    } else {
					$html_item = str_replace ('{template_'.$v_template.'}', '', $html_item);
				    }
				}
			}
			foreach ($array_image as $image_id)
			{
				$a_img = explode ('_', $image_id);
				if (isset ($info_item['image'.$a_img[0]]))
				{
					if (isset ($info_item['image'.$a_img[0]]))
					$image = $info_item['image'.$a_img[0]];
					if (isset ($image))
					$image = $image[($a_img[1]-1)]['text'];
					$html_item = str_replace ('{image_'.$image_id.'}', urlencode('upload/userparams/'.$image), $html_item);
				} else {
					$html_item = str_replace ('{image_'.$image_id.'}', urlencode('upload/no_image.png'), $html_item);
				}
			}
			foreach ($array_text as $text_id)
			{
				$a_text = explode ('_', $text_id);
				$text = $info_item['text'.$a_text[0]];
				$text = $text['text'];
				if ($a_text[1])
				{
					if (strlen($text)>$a_text[1])
					{
						mb_internal_encoding("UTF-8");
						$text = mb_substr ($text, 0, $a_text[1]).'...';
						mb_internal_encoding("windows-1251");
					}
				}
				$html_item = str_replace ('{text_'.$text_id.'}', $text, $html_item);
			}
			if (count($array_select))
			{
				foreach ($array_select as $select_id)
				{
					$select = $info_item['select'.$select_id];
					if (count($select))
					{
						foreach ($select as $key_1 => $v1)
						{
						    $html_select .= $v1['text'];
						    if (isset ($select[$key_1+1]))
						    {
							$html_select .= ',';
						    }
						}
					}
					$html_item = str_replace ('{select_'.$select_id.'}', $html_select, $html_item);
					unset ($html_select);
				}
			}
			foreach ($array_price as $price_id)
			{
				$results_many_price = mysql_query("SELECT * FROM `ls_values_ligament_price` where `id_item` = '".$v['id']."' order by `price` LIMIT 0,1;");
				$maybe_many_price = mysql_num_rows($results_many_price);
				if (!$maybe_many_price)
				{
					if ($info_item['select42'][0]['id_value']==3884)
					{
						$priceAction = $info_item['price6'];
						$priceAction = $priceAction['text'];
						$array_price_action = explode ('|', $priceAction);
						$html_item = str_replace ('price_main', 'old_price_action', $html_item);
						//$arrayActionPrice = returnSubstrings($html_item, '<!--price_', '_end-->');
						//$html_item = str_replace ('<!--price_'.$arrayActionPrice[0].'_end-->', '', $html_item);
					} else {
						$price = $info_item['price3'];
						$price = $price['text'];
						$array_price_new = explode ('|', $price);
						//$arrayActionPrice = returnSubstrings($html_item, '<!--price_', '_end-->');
						$titre = preg_match_all("|<!--price_(.*)_end-->|isU",$html_item,$regs);
						$arrayActionPrice = $regs[1];
						$html_item = str_replace ('<!--price_'.$arrayActionPrice[0].'_end-->', '', $html_item);
						$html_item = str_replace ('{valuta}', $array_price_new[1], $html_item);
					}
					$price = $info_item['price'.$price_id];
					$price = $price['text'];
					$array_price_new = explode ('|', $price);
					if ($array_price_new[0]!=0)
					{
						$html_item = str_replace ('{price_'.$price_id.'}', $array_price_new[0], $html_item);
						$html_item = str_replace ('{valuta}', $array_price_new[1], $html_item);
					} else {
						$html_item = str_replace ('{price_'.$price_id.'}', '', $html_item);
						$html_item = str_replace ('{valuta}', '', $html_item);
						//$html_item = str_replace ('<span>грн.</span>', 'знято з виробництва', $html_item);
					}
				} else {
					$info_price_many = mysql_fetch_array($results_many_price, MYSQL_ASSOC);
					$html_item = str_replace ('{price_'.$price_id.'}', $info_price_many['price'], $html_item);
					$info_valuta_many_price = mysql_fetch_array(mysql_query("SELECT `value` FROM `ls_reference_values_translate` where `id_reference_value` = '".$info_price_many['id_reference_value']."' and `id_lang` = '".$id_online_lang."';"), MYSQL_ASSOC);
					$html_item = str_replace ('{valuta}', $info_valuta_many_price['value'], $html_item);
				}
			}
			$html_item = str_replace ('{link}', '{main_sait}{lang}/mode/item-'.$v['id'].'.html', $html_item);
			if (!isset ($body))
			$body = '';
			if (isset ($_GET['admin']) or $_SESSION['admin'])
			{
				$html_item = str_replace ('<div id="prev_foto">', '<a href="http://intarsio.com.ua/admin/index.php?do=products&action=edit_item&id='.$v['id'].'">редагувати</a><div id="prev_foto">', $html_item);
			}
			$body .= $html_item;
		}
	} else {
		$body .= '
		<div id="page_cart">
			<h1>'.$lang[566].'</h1>
		</div>
		';
	}
} else {
	$body .= '
	<div id="top_filtr_body" style="text-align:center;">
		<div id="one_item_main">
			<a href="{main_sait}{lang}/shop/Shkafu/?a[select][]=120&a[select][]=115&a[select][]=129&a[select][]=126&a[select][]=3619&a[select][]=3715&a[select][]=3717&a[select][]=369&and_param">
				<img src="{template}/main_icon/1.png">
				<div>'.$lang[589].'</div>
			</a>
		</div>
		<div id="one_item_main">
			<a href="{main_sait}{lang}/shop/Shkafu/?a[select][]=106&a[select][]=109&a[select][]=111&a[select][]=3485&a[select][]=3563&a[select][]=3513&a[select][]=3525&a[select][]=3519&a[select][]=3739&a[select][]=3740&and_param">
				<img src="{template}/main_icon/2.png">
				<div>'.$lang[590].'</div>
			</a>
		</div>
		<div id="one_item_main">
			<a href="{main_sait}{lang}/shop/Shkafu/?a[select][]=117&a[select][]=130&a[select][]=3620&a[select][]=3707&and_param">
				<img src="{template}/main_icon/3.png">
				<div>'.$lang[591].'</div>
			</a>
		</div>
		<div id="one_item_main">
			<a href="{main_sait}{lang}/shop/Shkafu/?a[select][]=116&a[select][]=132&a[select][]=3617&and_param">
				<img src="{template}/main_icon/4.png">
				<div>'.$lang[592].'</div>
			</a>
		</div>
		<div id="one_item_main">
			<a href="{main_sait}{lang}/shop/Shkafu/?a[select][]=112&a[select][]=122&a[select][]=3528&a[select][]=3527&a[select][]=3618&a[select][]=3668&a[select][]=3678&a[select][]=128&a[select][]=140&a[select][]=3718&a[select][]=3588&and_param">
				<img src="{template}/main_icon/5.png">
				<div>'.$lang[593].'</div>
			</a>
		</div>
		<div id="one_item_main">
			<a href="{main_sait}{lang}/shop/Shkafu/?a[select][]=136&a[select][]=3669&and_param">
				<img src="{template}/main_icon/6.png">
				<div>'.$lang[594].'</div>
			</a>
		</div>
		<div id="one_item_main">
			<a href="{main_sait}{lang}/shop/Shkafu/?a[select][]=139&a[select][]=127&a[select][]=3569&and_param">
				<img src="{template}/main_icon/7.png">
				<div>'.$lang[595].'</div>
			</a>
		</div>
		<div id="one_item_main">
			<a href="{main_sait}{lang}/shop/Shkafu/?a[select][]=123&a[select][]=3622&and_param">
				<img src="{template}/main_icon/8.png">
				<div>'.$lang[596].'</div>
			</a>
		</div>
		<div id="one_item_main">
			<a href="{main_sait}{lang}/shop/Shkafu/?a[select][]=3472">
				<img src="{template}/main_icon/9.png">
				<div>'.$lang[597].'</div>
			</a>
		</div>
		<div id="one_item_main">
			<a href="{main_sait}{lang}/shop/Shkafu/?a[select][]=3743">
				<img src="{template}/main_icon/10.png">
				<div>'.$lang[598].'</div>
			</a>
		</div>
		<div id="one_item_main">
			<a href="{main_sait}{lang}/shop/Shkafu/?a[select][]=3738">
				<img src="{template}/main_icon/11.png">
				<div>'.$lang[599].'</div>
			</a>
		</div>
		<div id="one_item_main">
			<a href="{main_sait}{lang}/shop/Shkafu/?a[select][]=135">
				<img src="{template}/main_icon/12.png">
				<div>'.$lang[600].'</div>
			</a>
		</div>
	</div>
	';
	//switch ($config['user_params_11'])
	//{
	//	case "1":
	//		$array_items = return_all_items (0, 0 , '', 0, $config['user_params_10']);
	//	break;
	//	case "2":
	//		$array_items = return_all_items (3, 0 , '', 0, $config['user_params_10']);
	//	break;
	//}
}
?>