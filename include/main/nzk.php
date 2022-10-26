<?php
require ('admin/engine/template/functions.php');
$main_template = return_one_template($config['user_params_9']);
$main_template = $main_template['template'];
$array_image = returnSubstrings($main_template, '{image_', '}');
$array_text = returnSubstrings($main_template, '{text_', '}');
$array_price = returnSubstrings($main_template, '{price_', '}');
switch ($config['user_params_11'])
{
	case "1":
		$array_items = return_all_items (0, 0 , '', 0, $config['user_params_10']);
	break;
	case "2":
		$array_items = return_all_items (3, 0 , '', 0, $config['user_params_10']);
	break;
}
if ($array_items)
    {
            $nzk[] = '
               
            <div id="main_1">
                    <a href="{link}"><img src="{main_sait}resize_image.php?filename={image_1_1}&const=128&width=240&height=182"></a>
                    <div id="price" style="margin:-180px 0px 0px -25px;">{price_1} {valuta}.</div>
            </div>';
            $nzk[] = '
            <div id="main_2">
                    <a href="{link}"><img src="{main_sait}resize_image.php?filename={image_1_1}&const=128&width=142&height=142"></a>
                    <div id="price" style="margin:-110px 0px 0px -40px;">{price_1} {valuta}.</div>
            </div>
            <div id="clear"></div>
            ';
            $nzk[] = '<div id="main_3">
                    <a href="{link}"><img src="{main_sait}resize_image.php?filename={image_1_1}&const=128&width=93&height=70"></a>
                    <div id="price" style="margin:-10px 0px 0px -20px;">{price_1} {valuta}.</div>
            </div>';
            $nzk[] = '<div id="main_3" style="margin-top:-15px; margin-left:20px;">
                    <a href="{link}"><img src="{main_sait}resize_image.php?filename={image_1_1}&const=128&width=93&height=70"></a>
                    <div id="price" style="margin:-10px 0px 0px -20px;">{price_1} {valuta}.</div>
            </div>';
            $nzk[] = '
            <div id="main_4">
                    <a href="{link}"><img src="{main_sait}resize_image.php?filename={image_1_1}&const=128&width=151&height=102"></a>
                    <div id="price" style="margin:-10px 0px 0px -20px;">{price_1} {valuta}.</div>
            </div>
            <div id="clear"></div>';
            $nzk[] = '<div id="main_5">
                    <a href="{link}"><img src="{main_sait}resize_image.php?filename={image_1_1}&const=128&width=234&height=141"></a>
                    <div id="price" style="margin:-30px 0px 0px 200px;">{price_1} {valuta}.</div>
            </div>';
            $nzk[] = '<div id="main_6">
                    <a href="{link}"><img src="{main_sait}resize_image.php?filename={image_1_1}&const=128&width=151&height=102"></a>
                    <div id="price" style="margin:-10px 0px 0px -20px;">{price_1} {valuta}.</div>
            </div>
            ';
            foreach ($array_items as $key => $v)
            {
                   
                    $html_item = $nzk[$key];
                    $array_image = returnSubstrings($html_item, '{image_', '}');
                    $array_text = returnSubstrings($html_item, '{text_', '}');
                    $array_price = returnSubstrings($html_item, '{price_', '}');
                    $info_item = return_item_info ($v['id'], $id_online_lang);
                    foreach ($array_image as $image_id)
                    {
                            $a_img = explode ('_', $image_id);
                            $image = $info_item['image'.$a_img[0]];
                            $image = $image[($a_img[1]-1)]['text'];
                            $html_item = str_replace ('{image_'.$image_id.'}', urlencode('upload/userparams/'.$image), $html_item);
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
                    foreach ($array_price as $price_id)
                    {
                        $results_many_price = mysql_query("SELECT * FROM `ls_values_ligament_price` where `id_item` = '".$v['id']."' order by `price` LIMIT 0,1;");
                        $maybe_many_price = mysql_num_rows($results_many_price);
                        if (!$maybe_many_price)
                        {
                            $price = $info_item['price'.$price_id];
                            $price = $price['text'];
                            $array_price_new = explode ('|', $price);
                            $html_item = str_replace ('{price_'.$price_id.'}', $array_price_new[0], $html_item);
                            $html_item = str_replace ('{valuta}', $array_price_new[1], $html_item);
                        } else {
                            $info_price_many = mysql_fetch_array($results_many_price, MYSQL_ASSOC);
                            $html_item = str_replace ('{price_'.$price_id.'}', $info_price_many['price'], $html_item);
                            $info_valuta_many_price = mysql_fetch_array(mysql_query("SELECT `value` FROM `ls_reference_values_translate` where `id_reference_value` = '".$info_price_many['id_reference_value']."' and `id_lang` = '".$id_online_lang."';"), MYSQL_ASSOC);
                            $html_item = str_replace ('{valuta}', $info_valuta_many_price['value'], $html_item);
                        }
                    }
                    $html_item = str_replace ('{link}', '{main_sait}{lang}/mode/item-'.$v['id'].'.html', $html_item);
                    $body .= $html_item;
            }
            $title = $lang[532].' - НЗК';
            $body = '
             <div id="right_2_menu">
		<div id="hr_right"></div>
		Підприємство з іноземними інвестиціями засновано у 2000 році. Основний вид діяльності підприємства  - деревообробка. Для виробництва використовується фансировина вільхи, берези,сосни, ясеня, тобто порід, що ростуть на Волині.
<div id="hr_right"></div>
		<div style="text-align:center; margin:25px 0px 25px 0px;"><img src="{template}images/map.png"></div>
		<div id="hr_right"></div>
		На території підприємства, що складає 1.5 га розташовано два деревообробних цеха, три сушильних камери на 120 м? сировини, приміщення пилорами з естакадою, приміщення
		<div id="hr_right"></div>
	</div>
	<div id="body_content">
		<h2>'.$lang[540].'</h2>
            '.$body.'</div>';
    }
?>