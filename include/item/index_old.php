<?php
require ('admin/engine/template/functions.php');
$item_template = return_one_template($config['user_params_12']);
$item_template = $item_template['template'];
$array_template = returnSubstrings($item_template, '{template_', '}');
$info_item = return_item_info (intval($_GET['id']), $id_online_lang);
//print_r ($info_item);
$html_item = $item_template;
$sql = "select * from `ls_items` where `id` = '".$_GET['id']."';";
$results = mysql_query($sql);
$info_item_ls_item = mysql_fetch_array($results, MYSQL_ASSOC);
foreach ($array_template as $v)
{
    $a_template = explode ('_', $v);
    if ($a_template[1]==$info_item_ls_item['id_card'])
    {
        $add_template = return_one_template($a_template[0]);
        $add_template = $add_template['template'];
        $html_item = str_replace ('{template_'.$v.'}', $add_template, $html_item);
    } else {
        $html_item = str_replace ('{template_'.$v.'}', '', $html_item);
    }
}
$array_image = returnSubstrings($html_item, '{image_', '}');
$array_text = returnSubstrings($html_item, '{text_', '}');
$array_price = returnSubstrings($html_item, '{price_', '}');
$array_select = returnSubstrings($html_item, '{select_', '}');
$array_name = returnSubstrings($html_item, '{name_', '}');
$array_link_category = returnSubstrings($html_item, '{link_category_', '}');
//print_r ($info_item);
foreach ($array_image as $image_id)
{
        $a_img = explode ('_', $image_id);
        if (isset ($info_item['image'.$a_img[0]]))
        {
                if (isset ($info_item['image'.$a_img[0]]))
                $image = $info_item['image'.$a_img[0]];
                if (isset ($image))
                $image = $image[($a_img[1]-1)]['text'];
                $html_item = str_replace ('{image_'.$image_id.'}', urlencode('upload/userparams/').$image, $html_item);
        } else {
                $html_item = str_replace ('{image_'.$image_id.'}', urlencode('upload/no_image.png'), $html_item);
        }
}
if (isset ($info_item['image'.$a_img[0]]))
$array_image = $info_item['image'.$a_img[0]];
//otheritemphoto_560_383
$image_many_photo = returnSubstrings ($html_item, '{otheritemphoto_', '}');
if (count($image_many_photo))
{
    $array_image_many_photo = explode ('_', $image_many_photo[0]);
    $to_raplace_many_photo = '{otheritemphoto_'.$array_image_many_photo[0].'_'.$array_image_many_photo[1].'}';
}
if (count($array_image)>1)
{
    foreach ($array_image as $key => $v)
    {
        if ($key!=0)
        {
            $to_change = $config ['site_url'].'resize_image.php?filename='.urlencode('upload/userparams/'.$v['text']).'&const=310&width='.$array_image_many_photo[0].'&height='.$array_image_many_photo[1].'&r=79&b=1&g=1';
            $image_rotator .= '<div id="image_rotator"><a href="{main_sait}upload/userparams/'.$v['text'].'" rel="lightbox[roadtrip]"><img src="'.$config ['site_url'].'resize_image.php?filename='.urlencode('upload/userparams/'.$v['text']).'&const=129&width=138&r=79&b=1&g=1" onclick="change_main_photo(\''.$to_change.'\')"></a></div>';
        }
    }
}
foreach ($array_text as $text_id)
{
        $a_text = explode ('_', $text_id);
        $text = $info_item['text'.$a_text[0]];
        $text = $text['text'];
        $html_item = str_replace ('{text_'.$text_id.'}', str_replace ("\r\n", "<br>", $text), $html_item);
}
$title = $info_item['text11']['text'].' купить, '.$info_item['select13'][0]['text'].' - интернет магазин мебели "Intarsio"';
$keywords = $info_item['text11']['text'].', '.$info_item['select13'][0]['text'].', купить '.$info_item['text11']['text'].', продажа '.$info_item['text11']['text'];
$description = 'Интернет магазин мебели, купить '.$info_item['text11']['text'].', продажа '.$info_item['select13'][0]['text'].' в Украине, самые дешевые цены';
foreach ($array_price as $price_id)
{
    $results_many_price = mysql_query("SELECT * FROM `ls_values_ligament_price` where `id_item` = '".$_GET['id']."' order by `price` LIMIT 0,1;");
    $maybe_many_price = mysql_num_rows($results_many_price);
    if (!$maybe_many_price)
    {
        if (isset ($info_item['select42'][0]) and $info_item['select42'][0]['id_value']==3884)
        {
            $price = $info_item['price6'];
            $price = $price['text'];
            $array_price_action = explode ('|', $price);
            $action = 1;
        }
        $price = $info_item['price'.$price_id];
        $price = $price['text'];
        $array_price_new = explode ('|', $price);
        if ($array_price_new[0]!=0)
        {
            if ($action==1)
            {
                $html_item = str_replace ('<div class="priceBlockFullView">', '', $html_item);
                $html_item = str_replace ('addToCartFullView', 'addToCartFullView dopMargin20', $html_item);
                $html_item = str_replace ('{price_'.$price_id.'}', '<div class="priceBlockFullViewAction positionNewPrice"><div class="new_price">'.$array_price_action[0].'</div><span>{valuta}</span></div><div class="priceBlockFullView ActionPrice"><div class="oldPriceBlock">'.$array_price_new[0].'</div>', $html_item);
            } else {
                $html_item = str_replace ('{price_'.$price_id.'}', $array_price_new[0], $html_item);
            }
            $html_item = str_replace ('{valuta}', $array_price_new[1], $html_item);
        } else {
            $html_item_prices_block = returnSubstrings ($html_item, '<!--prices-->', '<!--/prices-->');
            $html_item = str_replace ($html_item_prices_block[0], '<div style="font-size:22px;">знято з виробництва</div>', $html_item);
            //$html_item = str_replace ('{valuta}', '', $html_item);
        }
    } else {
            $info_price_many = mysql_fetch_array($results_many_price, MYSQL_ASSOC);
            $html_item = str_replace ('{price_'.$price_id.'}', $info_price_many['price'], $html_item);
            $info_valuta_many_price = mysql_fetch_array(mysql_query("SELECT `value` FROM `ls_reference_values_translate` where `id_reference_value` = '".$info_price_many['id_reference_value']."' and `id_lang` = '".$id_online_lang."';"), MYSQL_ASSOC);
            $html_item = str_replace ('{valuta}', $info_valuta_many_price['value'], $html_item);
    }
}
if (count($array_select))
{
    foreach ($array_select as $select_id)
    {
        //print substr_count($select_id, '_br');
        if (substr_count($select_id, '_br'))
        {
            $select_id_n = str_replace ('_br', '', $select_id);
            if (isset ($info_item['select'.$select_id_n]))
            $br = '<br>';
        } else {
            $select_id_n = $select_id;
        }
        //print $select_id."<br>";
        $select = $info_item['select'.$select_id_n];
        if (count($array_link_category))
        {
            foreach ($array_link_category as $link_category)
            {
                if ($link_category==$select_id_n)
                {
                    $html_item = str_replace ('{link_category_'.$link_category.'}', '{main_sait}{lang}/shop/'.translit($select[0]['text']).'/?a[select][]='.$select[0]['id_value'], $html_item);
                }
            }
        }
        if (count($select))
        {
            foreach ($select as $key => $v)
            {
                $html_select .= stripslashes($v['text']);
                if (isset ($select[$key+1]))
                {
                    $html_select .= ', ';
                }
            }
        }
        $html_item = str_replace ('{select_'.$select_id.'}', $html_select.$br, $html_item);
        unset ($html_select, $br);
    }
}
foreach ($array_name as $name_id)
{
    if (strlen($info_item[str_replace ('_', '', $name_id)][0]['text']) or (substr_count($name_id, 'text') and strlen($info_item[str_replace ('_', '', $name_id)]['text'])))
    {
        $a_name = explode ('_', $name_id);
        switch ($a_name[0])
        {
            case "select":
                $one_name = return_one_translate ($a_name[1], $id_online_lang, 'select');
            break;
            case "text":
                $one_name = return_one_translate ($a_name[1], $id_online_lang, 'text');
            break;
        }
        $html_item = str_replace ('{name_'.$name_id.'}', $one_name['text'].':', $html_item);
    } else {
       $html_item = str_replace ('{name_'.$name_id.'}', '', $html_item);
    }
}
if (!isset ($maybe_many_price))
{
    $results_many_price = mysql_query("SELECT * FROM `ls_values_ligament_price` where `id_item` = '".$_GET['id']."' order by `price` LIMIT 0,1;");
    $maybe_many_price = mysql_num_rows($results_many_price);
}
if ($maybe_many_price)
{
    $info_tovar = mysql_fetch_array(mysql_query("SELECT * FROM `ls_items` where `id` = '".$_GET['id']."';"), MYSQL_ASSOC);
    $info_card_ligament = return_all_ligament_of_id_card($info_tovar['id_card']);
    foreach ($info_card_ligament as $key => $v)
    {
        $tr_name_param = mysql_fetch_array (mysql_query("SELECT `text` FROM `ls_translate` where `type` = 'select' and `id_elements` = '".$v['id_param']."' and `id_lang` = '".$id_online_lang."';"), MYSQL_ASSOC);
        $id_card_param_ligament_param = mysql_fetch_array(mysql_query("SELECT `id` FROM `ls_cardparam` where `id_param` = '".$v['id_param']."' and `db_type` = 'select' and `id_card` = '".$info_tovar['id_card']."';"), MYSQL_ASSOC);
        $array_values_select = return_select_values_from_cardparam_and_item($id_card_param_ligament_param['id'], $_GET['id']);
        //print_r ($array_values_select);
        if ($array_values_select)
        {
            foreach ($array_values_select as $v_value)
            {
                $info_param = mysql_fetch_array(mysql_query("SELECT * FROM `ls_params_select_values` where `id` = '".$v_value['value']."' LIMIT 0,1;"), MYSQL_ASSOC);
                if (strlen($info_param['img'])==0)
                {
                    $tr_name_value_select = mysql_fetch_array(mysql_query("SELECT `text` FROM `ls_translate` where `id_elements` = '".$v_value['value']."' and `type` = 'select_value' and `id_lang` = '".$id_online_lang."';"), MYSQL_ASSOC);
                    $option .= '<option value="'.$v_value['value'].'">'.$tr_name_value_select['text'].'</option>';
                    $type_js = 'onchange';
                } else {
                    $script_dropdown .= '
                        $(document).ready(function() {

                        try {
                                        oHandler = $("#param_'.$key.'").msDropDown({mainCSS:\'dd2\'}).data("dd");
                                        //alert($.msDropDown.version);
                                        //$.msDropDown.create("body select");
                                        $("#ver").html($.msDropDown.version);
                                        } catch(e) {
                                                alert("Error: "+e.message);
                                        }
                        })
                    ';
                    $option .= '
                    <option value="'.$v_value['value'].'" title="'.$config ['site_url'].'upload/select_params/'.$info_param['img'].'"></option>';
                    $type_js = 'onclick';
                    //<option value="'.$v_value['value'].'" class="imagebacked" style="width:100px; background-image: url('.$config ['site_url'].'upload/select_params/'.$info_param['img'].');" selected></option>';
                }
            }
        }
        $ligament .= '<b>'.$tr_name_param['text'].'</b> <select id="param_'.$key.'" '.$type_js.'="change_price();" tabindex="'.$key.'" style="width:105px;">'.$option.'</select><br>';
        $script_param .= '
        var param_'.$key.' = document.getElementById(\'param_'.$key.'\').value;
        ';
        if (isset ($info_card_ligament[$key-1]))
        {
            $script_query .= '"&param[]=" + param_'.$key;
        } else {
            $script_query .= '"param[]=" + param_'.$key;
        }
        if (isset ($info_card_ligament[$key+1]))
        {
            $script_query .= ' + ';
        }
        unset ($option);
    }
    $script .= '
    <script>
    '.$script_dropdown.'
    function change_price()
    {
        var cont = document.getElementById(\'price_item\');
        '.$script_param.'
        cont.innerHTML = loading;
        link = main_sait_url + "include/change_price.php";
        var query = '.$script_query.' + "&id_item='.$_GET['id'].'&id_online_lang='.$id_online_lang.'";
        var http = createRequestObject();
        if( http )
        {
            http.open(\'post\', link);
            http.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            http.send(query);
            http.onreadystatechange = function ()
            {
                if(http.readyState == 4)
                {
                    cont.innerHTML = http.responseText;
                }
            }
            http.send(null);
        }
        else
        {
            document.location = link;
        }
    }
        </script>
    ';
}
if (!isset ($ligament))
$ligament = '';
if (!isset ($script))
$script = '';
$ligament = $script.$ligament.'<br>';
if (isset ($to_raplace_many_photo))
$html_item = str_replace ($to_raplace_many_photo, $image_rotator, $html_item);
$html_item = str_replace ('{id_item}', $_GET['id'], $html_item);
$html_item = str_replace ('{ligament}', $ligament, $html_item);
$html_item = str_replace ('{link}', '{main_sait}{lang}/mode/item-'.$_GET['id'].'.html', $html_item);
$sql = "SELECT COUNT(*) FROM `ls_items` where `id` > ".intval($_GET['id'])." LIMIT 0,1;";
$info_next_item = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
if ($info_next_item['COUNT(*)'])
{
    $info_next_item = mysql_fetch_array(mysql_query("SELECT `id` FROM `ls_items` where `id` > ".intval($_GET['id'])." LIMIT 0,1;"), MYSQL_ASSOC);
    $next = '<div style="float:right;"><a href="{main_sait}{lang}/mode/item-'.$info_next_item['id'].'.html"><img src="http://intarsio.com.ua/images/right.png"></a></div>';
}
$sql = "SELECT COUNT(*) FROM `ls_items` where `id` < ".intval($_GET['id'])." LIMIT 0,1;";
$info_prev_item = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
if ($info_prev_item['COUNT(*)'])
{
    $info_prev_item = mysql_fetch_array(mysql_query("SELECT `id` FROM `ls_items` where `id` < ".intval($_GET['id'])." order by `id` DESC LIMIT 0,1;"), MYSQL_ASSOC);
    $prev = '<div style="float:left;"><a href="{main_sait}{lang}/mode/item-'.$info_prev_item['id'].'.html"><img src="http://intarsio.com.ua/images/left.png"></a></div>';
}
$body .= $prev.$next;
if (isset ($body))
{
    $body .= $html_item;
} else {
    $body = $html_item;
}
if (isset ($_GET['admin']) or $_COOKIE['id_user_online'])
{
        $body = '
        <a href="http://intarsio.com.ua/admin/index.php?do=products&action=edit_item&id='.$_GET['id'].'" style="color:white;" target="_blank">редагувати</a> |
        <a href="http://intarsio.com.ua/admin/add_image.php?id='.$info_item_ls_item['id_card'].'&id_item='.$_GET['id'].'" style="color:white;" target="_blank">управління фото</a> |
        <a href="http://intarsio.com.ua/admin/index.php?do=products&action=view_item&id_card='.$info_item_ls_item['id_card'].'&method=del&id_item='.$_GET['id'].'&limit=0" onclick="return confirm (\'Подтвердить удаление?\')"><img src="http://intarsio.com.ua/images/admin/remove_16.png" border="0" target="_blank"></a>
        <br>'.$body;
}
?>