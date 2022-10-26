<?php
require ('engine/params/functions.php');
$other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[193];
$body_admin .= '
<h2 id="title" align="center">'.$lang[193].'</h2>
';
if (isset ($_POST['submit']))
{

    $textParam = $_POST['text'];
    foreach ($textParam as $key => $v)
    {
        $arrayTextParam[] = "`text_".$key."` = '".mysql_real_escape_string($v)."'";
    }
    $textParamSql = implode(",", $arrayTextParam);
    $priceParam = $_POST['price'];
    foreach ($priceParam as $key => $v)
    {
        $arrayPriceParam[] = "`price_".$key."` = '".mysql_real_escape_string($v)."'";
        if ($key==1) {
            $newPrice = $v;
        }
    }
    $priceParamSql = implode(",", $arrayPriceParam);
    $selectParam = $_POST['select'];
    foreach ($selectParam as $v)
    {
        if (strlen($v)>0)
        {
            $htmlArray = explode ('|', $v);
            $arraySelect[$htmlArray[1]][] = $htmlArray[0];
        }
    }
    if (!isset ($arraySelect[1]))
    {
        $arrayToSql[] = "`select_1` = ''";
    }
    if (!isset ($arraySelect[10]))
    {
        $arrayToSql[] = "`select_10` = ''";
    }
    if (!isset ($arraySelect[11]))
    {
        $arrayToSql[] = "`select_11` = ''";
    }
    if (!isset ($arraySelect[12]))
    {
        $arrayToSql[] = "`select_12` = ''";
    }
    if (!isset ($arraySelect[18]))
    {
        $arrayToSql[] = "`select_18` = ''";
    }
    if (!isset ($arraySelect[15]))
    {
        $arrayToSql[] = "`select_15` = ''";
    }
    foreach ($arraySelect as $key => $v)
    {
        if ($key==4 and $v[0]==42)
        {
//            print "true";
            $inStock = 1;
        }
        if (count($v)==1)
        {
            $arrayToSql[] = "`select_".$key."` = ".$v[0];
        } else {
            $arrayToSql[] = "`select_".$key."` = '".json_encode($v)."'";
        }
    }
    $infoItem = mysql_fetch_array(mysql_query("SELECT * FROM `ls_items` where `id` = '".intval($_GET['id'])."';"), MYSQL_ASSOC);
    $priceChange = $newPrice-$infoItem['price_1'];
    if ($priceChange!=0)
        mysql_query("UPDATE `ls_giftToItem` SET `price` = `price` + ".$priceChange." WHERE `giftId` = '".intval($_GET['id'])."' AND `price` != '0.01'");
    if ($infoItem['select_4']==49 and $inStock){
        $arrayToSql[] = "`time` = '".time()."'";
    }
    $selectParamSql = implode(",", $arrayToSql);
    $sql = "
    update `ls_items`
    set
    ".$textParamSql.",
    ".$priceParamSql.",
    ".$selectParamSql."
    where `id` = '".intval($_GET['id'])."';
    ";
    mysql_query($sql);
//    exit();
    /*
    $array_text = $_POST['text'];
    $id_cardparam_text = $_POST['id_cardparam_text'];
    $id_lang = $_POST['id_lang'];
    mysql_query ('START TRANSACTION;');
    $id_values_text = $_POST['id_values_text'];
    foreach ($array_text as $key => $v)
    {
        $number_text = return_items_text_param_number($id_cardparam_text[$key], $_GET['id'], $id_lang[$key]);
        if ($number_text)
        {
            $sql = "
			UPDATE `ls_values_text` set `text`='".$v."' where  `id` = '".$id_values_text[$key].";';
			";
        } else {
            $sql = "
			INSERT INTO  `ls_values_text` (
			`text` ,
			`id_lang` ,
			`id_item` ,
			`id_cardparam`
			)
			VALUES (
			'".$v."' ,
			'".$id_lang[$key]."' ,
			'".$_GET['id']."' ,
			'".$id_cardparam_text[$key]."'
			);
			";
        }
        mysql_query($sql);
        unset ($number_text);
    }
    unset ($sql);
    $select = $_POST['select'];
    foreach ($select as $key => $v)
    {
        if (strlen($v))
        {
            $array_select_v = explode ("|", $v);
            $in_base = return_items_select_param($array_select_v[1], $_GET['id'], $array_select_v[0]);
            //print "<br>".$array_select_v[0]."<br>";
            if (!$in_base)
            {
                $info_value = mysql_fetch_array(mysql_query("SELECT `id_params` from `ls_params_select_values` where `id` = '".$array_select_v[0]."';"), MYSQL_ASSOC);
                $info_params = mysql_fetch_array(mysql_query("SELECT `multiselect` from `ls_params_select` where `id` = '".$info_value['id_params']."';"), MYSQL_ASSOC);
                if (!$info_params['multiselect'])
                {
                    $sql = "DELETE from `ls_values_select` where `id_item` = '".$_GET['id']."' and `id_cardparam` = '".$array_select_v[1]."';";
                    mysql_query($sql);
                }
                $sql = "
				INSERT INTO  `ls_values_select` (
				`id_cardparam` ,
				`value` ,
				`id_item`
				)
				VALUES (
				'".$array_select_v[1]."' ,
				'".$array_select_v[0]."' ,
				'".$_GET['id']."'
				);
				";
                mysql_query($sql);
            } else {
                $array_true_id[] = $array_select_v[2];
            }
        }
    }
    if (isset ($array_true_id))
        if (count($array_true_id))
        {
            if (!isset ($sql))
                $sql = '';
            $sql .= "DELETE FROM `ls_values_select` where ";
            foreach ($array_true_id as $key => $v)
            {
                $sql .= " `id`<>'".$v."' ";
                if (isset ($array_true_id[$key+1]))
                {
                    $sql .= ' and ';
                } else {
                    $sql .= " and `id_item` = '".$_GET['id']."';";
                }
            }
            mysql_query($sql);
        }
    unset ($sql);
    $id_cardparam_price = $_POST['id_cardparam_price'];
    if ($id_cardparam_price)
    {
        foreach ($id_cardparam_price as $v)
        {
            if (isset ($_POST['price_'.$v]))
            {
                $in_base_price = return_items_price_param_number($v, $_GET['id']);
                $value_price = $_POST['price_'.$v];
                $convert_price = $_POST['convert_price_'.$v];
                $array_convert_price = explode ("|", $convert_price[0]);
                if (!$in_base_price)
                {
                    $sql = "
					INSERT INTO  `ls_values_prices` (
					`value` ,
					`convert_price` ,
					`id_item` ,
					`id_cardparam`
					)
					VALUES (
					'".$value_price[0]."' ,
					'".$array_convert_price[0]."' ,
					'".$_GET['id']."' ,
					'".$v."'
					);
					";
                } else {
                    $sql = "UPDATE `ls_values_prices` set `convert_price` = '".$array_convert_price[0]."', `value` = '".$value_price[0]."' where `id` = '".$array_convert_price[1]."';";
                }
                mysql_query($sql);
            }
        }
    }
    unset ($array_true_id);
    if (isset ($_POST['boolean']))
        $boolean = $_POST['boolean'];
    if (!isset ($boolean))
        $boolean = '';
    if (isset ($_POST['boolean']) and count ($boolean))
    {
        foreach ($boolean as $v)
        {
            $array_boolean = explode ("|", $v);
            $info_boolean = return_items_boolean_param($array_boolean[0], $_GET['id']);
            if (!$info_boolean)
            {
                $sql = "
			INSERT INTO  `ls_values_boolean` (
			`id_cardparam` ,
			`id_item`
			)
			VALUES (
			'".$array_boolean[0]."' ,
			'".$_GET['id']."'
			);
			";
                mysql_query($sql);
            } else {
                $array_true_id[] = $array_boolean[1];
            }
        }
        unset ($sql);
        if (count($array_true_id))
        {
            $sql .= "DELETE FROM `ls_values_boolean` where ";
            foreach ($array_true_id as $key => $v)
            {
                $sql .= " `id`<>'".$v."' ";
                if (isset ($array_true_id[$key+1]))
                {
                    $sql .= ' and ';
                } else {
                    $sql .= " and `id_item` = '".$_GET['id']."';";
                }
            }
            mysql_query($sql);
        }
    } else {
        $sql = "DELETE FROM `ls_values_boolean` where `id_item` = '".$_GET['id']."';";
        mysql_query($sql);
    }
    if (isset ($_GET['id_card']))
        if (check_image_param($_GET['id_card']))
        {
            $add_image = '<a href="#" onClick="window.open(\'add_image.php?id='.$id_item.'\', \'_blank\', \'Toolbar=0, Scrollbars=1, Resizable=0, Width=640, resize=no, Height=480\');">'.$lang[181].'</a>';
        }
    if (mysql_query ('COMMIT;'))
    {
        if (!isset ($add_image))
            $add_image = '';
        $body_admin .= '<h2 align="center" style="color:green">'.$lang[180].'<br>'.$add_image.'</h2>';
    }
    */
}
$info_item = return_one_item ($_GET['id']);
$array_params_for_card = return_parafm_for_card ($info_item['id_card']);
$all_lang = return_all_ok_lang();
$isset_prev_item = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `ls_items` where `id` < '".$info_item['id']."' order by `id` DESC LIMIT 0,1;"), MYSQL_ASSOC);
if ($isset_prev_item['COUNT(*)'])
{
    $id_prev_item = mysql_fetch_array(mysql_query("SELECT `id` FROM `ls_items` where `id` < '".$info_item['id']."' order by `id` DESC LIMIT 0,1;"), MYSQL_ASSOC);
    $go_prev_item = '<a href="index.php?do=products&action=edit_item&id='.$id_prev_item['id'].'"><img src="{site_url}images/admin/1leftarrow_32.png"></a>';
} else {
    $go_prev_item = '';
}
$isset_next_item = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `ls_items` where `id` > '".$info_item['id']."' LIMIT 0,1;"), MYSQL_ASSOC);
if ($isset_next_item['COUNT(*)'])
{
    $id_next_item = mysql_fetch_array(mysql_query("SELECT `id` FROM `ls_items` where `id` > '".$info_item['id']."' LIMIT 0,1;"), MYSQL_ASSOC);
    $go_next_item = '<a href="index.php?do=products&action=edit_item&id='.$id_next_item['id'].'"><img src="{site_url}images/admin/1rightarrow_32.png"></a>';
} else {
    $go_next_item = '';
}
$info_card = mysql_fetch_array(mysql_query("SELECT `name` FROM `ls_card` where `id` = '".$info_item['id_card']."';"), MYSQL_ASSOC);
$body_admin .= ' 
<b>'.$lang[567].': "'.$info_card['name'].'"</b> - <a href="index.php?do=products&action=view_item&id_card='.$info_item['id_card'].'">'.$lang[519].'</a>
<div align="center">
<form action="" method="POST">
<table border="0">
<tr>
	<td align="left">'.$go_prev_item.'</td>
	<td align="right">'.$go_next_item.'</td>
</tr>
';
$infoItem = mysql_fetch_array(mysql_query("SELECT * FROM `ls_items` where `id` = '".intval($_GET['id'])."';"), MYSQL_ASSOC);
foreach ($array_params_for_card as $key => $v)
{
    $position = $v['position'];
    switch ($v['db_type'])
    {
        case "text":
            $param_translate = return_name_for_id_text_param ($id_online_lang, $v['id_param']);
            $param_info = return_one_text_params($v['id_param']);
            if ($param_info['more_lang'])
            {
                foreach ($all_lang as $key_lang => $one_lang)
                {
                    if (!isset ($html_text_param))
                        $html_text_param = '';
//			$values_text = return_items_text_param($v['id'], $_GET['id'], $one_lang['id']);
                    if ($param_info['multiline'])
                    {
                        if ($param_info['wysiwyg'])
                        {
                            if (!isset ($wysiwyg))
                            {
                                $body_admin .= '
                                <!--<script type="text/javascript" src="../tiny_mce/tiny_mce.js"></script>
                                <script type="text/javascript"> 
                                        tinyMCE.init({
                                                // General options
                                                mode : "specific_textareas",
                                                editor_selector : "mceEditor",
                                                theme : "advanced",
                                                skin : "o2k7",
                                                skin_variant : "silver",
                                                language : "uk",
                                                plugins : "safari,pagebreak,style,layer,table,save,images,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
                                 
                                                // Theme options
                                                theme_advanced_buttons3 : "images,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,fullscreen",
                                        
                                                extended_valid_elements : "iframe[title|width|height|src|frameborder|allowfullscreen], object[width|height|param|embed],param[name|value],embed[src|type|width|height]" ,
                                                // Example content CSS (should be your site CSS)
                                                content_css : "css/content.css",
                                 
                                                // Drop lists for link/image/media/template dialogs
                                                template_external_list_url : "lists/template_list.js",
                                                external_link_list_url : "lists/link_list.js",
                                                external_image_list_url : "lists/image_list.js",
                                                media_external_list_url : "lists/media_list.js",
                                 
                                                // Replace values for the template plugin
                                                template_replace_values : {
                                                        username : "Some User",
                                                        staffid : "991234"
                                                }
                                        });
                                </script> -->
                                <script>
                                    $(document).ready(function() {
                                      $(\'.mceEditor\').summernote({
                                              height: 200,
                                              focus: true ,
                                              onImageUpload: function(files, editor, welEditable) {
                                                sendFile(files[0],editor,welEditable);
                                               }
                                            });
                                    });
                                </script>
                                ';
                                $wysiwyg = 1;
                            }
                            $html_text_param .= '
                            <b>'.$one_lang['name'].'</b><br>
                            <textarea name="text['.$v['id_param'].']" class="mceEditor">'.$values_text['text'].'</textarea>
							<input type="hidden" name="id_cardparam_text[]" value="'.$v['id'].'">
                            <input type="hidden" name="id_lang[]" value="'.$one_lang['id'].'"><br>
							<input type="hidden" name="id_values_text[]" value="'.$values_text['id'].'">
                            ';
                        } else {
                            $html_text_param .= '
                            <b>'.$one_lang['name'].'</b><br>
                            <textarea name="text['.$v['id_param'].']" cols="40" rows="6">'.$values_text['text'].'</textarea>
							<input type="hidden" name="id_cardparam_text[]" value="'.$v['id'].'">
                            <input type="hidden" name="id_lang[]" value="'.$one_lang['id'].'"><br>
							<input type="hidden" name="id_values_text[]" value="'.$values_text['id'].'">
                            ';
                        }
                    } else {
                        $html_text_param .= '
                        <b>'.$one_lang['name'].'</b><br>
                        <input type="text" name="text['.$v['id_param'].']" value="'.htmlspecialchars($values_text['text']).'">
						<input type="hidden" name="id_cardparam_text[]" value="'.$v['id'].'">
                        <input type="hidden" name="id_lang[]" value="'.$one_lang['id'].'"><br>
						<input type="hidden" name="id_values_text[]" value="'.$values_text['id'].'">
                        ';
                    }
                }
            } else {
//				$values_text = return_items_text_param($v['id'], $_GET['id'], 0);
                $values_text = $infoItem['text_'.$v['id_param']];
                if (!isset ($html_text_param))
                    $html_text_param = '';
                if ($param_info['multiline'])
                {
                    if ($param_info['wysiwyg'])
                    {
                        if ($wysiwyg==0)
                        {
                            $body_admin .= '
                            <!--<script type="text/javascript" src="../tiny_mce/tiny_mce.js"></script>
                            <script type="text/javascript"> 
                                    tinyMCE.init({
                                            // General options
                                            mode : "specific_textareas",
                                            editor_selector : "mceEditor",
                                            theme : "advanced",
                                            skin : "o2k7",
                                            skin_variant : "silver",
                                            language : "uk",
                                            plugins : "safari,pagebreak,style,layer,table,save,images,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
                             
                                            // Theme options
                                            theme_advanced_buttons3 : "images,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,fullscreen",
                                    
                                            extended_valid_elements : "iframe[title|width|height|src|frameborder|allowfullscreen], object[width|height|param|embed],param[name|value],embed[src|type|width|height]" ,
                                            // Example content CSS (should be your site CSS)
                                            content_css : "css/content.css",
                             
                                            // Drop lists for link/image/media/template dialogs
                                            template_external_list_url : "lists/template_list.js",
                                            external_link_list_url : "lists/link_list.js",
                                            external_image_list_url : "lists/image_list.js",
                                            media_external_list_url : "lists/media_list.js",
                             
                                            // Replace values for the template plugin
                                            template_replace_values : {
                                                    username : "Some User",
                                                    staffid : "991234"
                                            }
                                    });
                            </script> -->
                            <script>
                                    $(document).ready(function() {
                                      $(\'.mceEditor\').summernote({
                                              height: 200,
                                              width: 600,
                                              focus: false ,
                                              onImageUpload: function(files, editor, welEditable) {
                                                sendFile(files[0],editor,welEditable);
                                               }
                                            });
                                    });
                                </script>
                                <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" />
                            ';
                            $wysiwyg = 1;
                        }
                        $html_text_param .= '
                        <textarea name="text['.$v['id_param'].']" class="mceEditor">'.$values_text.'</textarea>
						<input type="hidden" name="id_cardparam_text[]" value="'.$v['id'].'">
                        <input type="hidden" name="id_lang[]" value="'.$one_lang['id'].'"><br>
                        ';
                    } else {
                        $html_text_param .= '
                        <textarea name="text['.$v['id_param'].']" cols="40" rows="6">'.$values_text.'</textarea>
						<input type="hidden" name="id_cardparam_text[]" value="'.$v['id'].'">
                        <input type="hidden" name="id_lang[]" value="'.$one_lang['id'].'"><br>
                        ';
                    }
                } else {
                    $html_text_param .= '
                            <input type="text" name="text['.$v['id_param'].']" value="'.htmlspecialchars($values_text).'">
							<input type="hidden" name="id_cardparam_text[]" value="'.$v['id'].'">
                            <input type="hidden" name="id_lang[]" value="'.$one_lang['id'].'"><br>
                    ';
                }
            }
            $body_admin .= '<tr>
            <td valign="top">'.$param_translate['text'].'
            </td>
            <td>'.$html_text_param.'
			</td>
            </tr>';
            unset ($html_text_param);
            break;
        case "select":
            $param_translate = return_name_for_id_select_param ($id_online_lang, $v['id_param']);
            $image_params = chek_values_select_text ($v['id_param'], '0');
            $text_params = chek_values_select_text ($v['id_param'], '1');
            $info_params = return_one_select_params($v['id_param']);
            //print_r ($info_params);
            if ($image_params)
            {
                $html_select_param .= '<table border="0">';
                $all_values_img = return_all_select_values_params($v['id_param']);
                foreach ($all_values_img as $one_img)
                {
                    $html_select_param .= '<tr>
			<td>
			';
                    $in_base = return_items_select_param($v['id'], $_GET['id'], $one_img['id']);
                    $info_in_base = return_items_select_param_value($v['id'], $_GET['id'], $one_img['id']);
                    if ($info_params['multiselect'])
                    {
                        if ($in_base)
                        {
                            $html_select_param .= '<input type="checkbox" name="select[]" value="'.$one_img['id'].'|'.$v['id'].'|'.$info_in_base['id'].'" checked>';
                        } else {
                            $html_select_param .= '<input type="checkbox" name="select[]" value="'.$one_img['id'].'|'.$v['id'].'|'.$info_in_base['id'].'">';
                        }
                    } else {
                        if ($in_base)
                        {
                            $html_select_param .= '<input type="radio" name="select[]" value="'.$one_img['id'].'|'.$v['id'].'|'.$info_in_base['id'].'" checked>';
                        } else {
                            $html_select_param .= '<input type="radio" name="select[]" value="'.$one_img['id'].'|'.$v['id'].'|'.$info_in_base['id'].'">';
                        }
                    }
                    $html_select_param .= '</td>
			<td>
			<img src="'.$config ['site_url'].'upload/select_params/'.$one_img['img'].'" style="border: 1px solid black;">
			</td>
			</tr>';
                }
                $html_select_param .= '</table>';
            } else {
                $info_item_only_ls_items = mysql_fetch_array(mysql_query("SELECT * FROM `ls_items` where `id` = '".$_GET['id']."';"), MYSQL_ASSOC);
                $results_parent = mysql_query("SELECT * FROM `ls_params_select` where `parent_id` = '".$v['id_param']."';");
                $info_parent_id = mysql_num_rows ($results_parent);
                if ($info_parent_id)
                {
                    $info_child = mysql_fetch_array ($results_parent, MYSQL_ASSOC);
                    //print_r ($info_child);
                    $info_id_cardparam_child = mysql_fetch_array(mysql_query("SELECT `id` FROM `ls_cardparam` where `id_param` = '".$info_child['id']."'  and `id_card` = '".$info_item_only_ls_items['id_card']."' and `db_type` = 'select';"), MYSQL_ASSOC);
                    $id_card_param = $info_id_cardparam_child['id'];
                    $child_functions = 'onchange="load_child_param(\''.$id_card_param.'\', \''.$info_child['id'].'\', \''.$v['id_param'].'\', \''.$id_online_lang.'\');" onclick="load_child_param(\''.$id_card_param.'\', \''.$info_child['id'].'\', \''.$v['id_param'].'\', \''.$id_online_lang.'\');";';
                    if (!isset ($script_before_edit))
                        $script_before_edit = '';
                    $script_before_edit .= 'load_child_param(\''.$id_card_param.'\', \''.$info_child['id'].'\', \''.$v['id_param'].'\', \''.$id_online_lang.'\');'."\r\n";
                }
                if ($text_params)
                {
                    $info_select_param = mysql_fetch_array (mysql_query("SELECT * FROM `ls_params_select` where `id` = '".$v['id_param']."';"), MYSQL_ASSOC);
                    if ($info_select_param['parent_id']!=0)
                    {
//                        $info_id_cardparam_parent = mysql_fetch_array(mysql_query("SELECT `id` FROM `ls_cardparam` where `id_param` = '".$info_select_param['parent_id']."' and `id_card` = '".$info_item_only_ls_items['id_card']."' and `db_type` = 'select';"), MYSQL_ASSOC);
//			$id_card_param = $info_id_cardparam_parent['id'];
//                        $sql = "SELECT `value` FROM `ls_values_select` where `id_item` = ".$_GET['id']." and `id_cardparam` = ".$id_card_param.";";
//                        $info_values_parent = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
//			print_r ($info_values_parent);
                        $all_values_text = return_all_text_values_params_with_parent($v['id_param'], $infoItem['select_'.$info_select_param['parent_id']]);
                    } else {
                        $all_values_text = return_all_text_values_params ($v['id_param']);
                    }
                    if (!isset ($select_html))
                        $select_html = '';
                    $inBaseArray = json_decode($infoItem['select_'.$v['id_param']], true);
//                    print $infoItem['select_'.$v['id_param']]."<br>";
                    foreach ($all_values_text as $one_text_param)
                    {
                        //$in_base = return_items_select_param($v['id'], $_GET['id'], $one_text_param['id']);
                        $info_in_base = return_items_select_param_value($v['id'], $_GET['id'], $one_text_param['id']);
                        if ($info_params['more_lang'])
                        {
                            $lang_select_params = return_name_for_id_select_param_value ($id_online_lang, $one_text_param['id']);
                        } else {
                            $lang_select_params = return_name_for_id_select_param_value_no_lang ($one_text_param['id']);
                        }
                        if (in_array($one_text_param['id'], $inBaseArray) or $one_text_param['id']==$inBaseArray)
                        {
                            $select_html .= '<option value="'.$one_text_param['id'].'|'.$v['id_param'].'|'.$info_in_base['id'].'" selected>'.$lang_select_params['text'].'</option>'."\r\n";
                        } else {
                            $select_html .= '<option value="'.$one_text_param['id'].'|'.$v['id_param'].'|'.$info_in_base['id'].'">'.$lang_select_params['text'].'</option>'."\r\n";
                        }
                    }
                    if (!isset ($child_functions))
                        $child_functions = '';
                    if (!isset ($html_select_param))
                        $html_select_param = '';
                    if ($info_params['multiselect'])
                    {
                        $sizeCss = count($all_values_text)+1;
                        $html_select_param .= '<select name="select[]" multiple size='.$sizeCss.' id="select_'.$v['id_param'].'" '.$child_functions.'><option></option>'.$select_html.'</select>';
                    } else {
                        $html_select_param .= '<select name="select[]" id="select_'.$v['id_param'].'" '.$child_functions.'><option></option>'.$select_html.'</select>';
                    }
                    unset ($child_functions);
                } else {
                    $html_select_param .= $lang[178];
                }
            }
            $body_admin .= '<tr>
		<td valign="top">'.$param_translate['text'].'
		</td>
		<td>'.$html_select_param.'</td>
		</tr>';
            unset ($html_select_param, $select_html);
            ///old



            /*

                $param_translate = return_name_for_id_select_param ($id_online_lang, $v['id_param']);
                $image_params = chek_values_select_text ($v['id_param'], '0');
                $text_params = chek_values_select_text ($v['id_param'], '1');
                $info_params = return_one_select_params($v['id_param']);
                //print_r ($info_params);
                if ($image_params)
                {
                    $html_select_param .= '<table border="0">';
                    $all_values_img = return_all_select_values_params($v['id_param']);
                    foreach ($all_values_img as $one_img)
                    {
                        $html_select_param .= '<tr>
                        <td>
                        ';
                        $in_base = return_items_select_param($v['id'], $_GET['id'], $one_img['id']);
                        $info_in_base = return_items_select_param_value($v['id'], $_GET['id'], $one_img['id']);
                        if ($info_params['multiselect'])
                        {
                if ($in_base)
                {
                    $html_select_param .= '<input type="checkbox" name="select[]" value="'.$one_img['id'].'|'.$v['id'].'|'.$info_in_base['id'].'" checked>';
                } else {
                    $html_select_param .= '<input type="checkbox" name="select[]" value="'.$one_img['id'].'|'.$v['id'].'|'.$info_in_base['id'].'">';
                }
                        } else {
                if ($in_base)
                {
                    $html_select_param .= '<input type="radio" name="select[]" value="'.$one_img['id'].'|'.$v['id'].'|'.$info_in_base['id'].'" checked>';
                } else {
                    $html_select_param .= '<input type="radio" name="select[]" value="'.$one_img['id'].'|'.$v['id'].'|'.$info_in_base['id'].'">';
                }
                        }
                        $html_select_param .= '</td>
                        <td>
                        <img src="'.$config ['site_url'].'upload/select_params/'.$one_img['img'].'" style="border: 1px solid black;">
                        </td>
                        </tr>';
                    }
                    $html_select_param .= '</table>';
                } else {
            if (!isset ($select_html))
            $select_html = '';
            if (!isset ($html_select_param))
            $html_select_param = '';
                    if ($text_params)
                    {
                        $all_values_text = return_all_text_values_params ($v['id_param']);
                        foreach ($all_values_text as $one_text_param)
                        {
                $in_base = return_items_select_param($v['id'], $_GET['id'], $one_text_param['id']);
                $info_in_base = return_items_select_param_value($v['id'], $_GET['id'], $one_text_param['id']);
                            if ($info_params['more_lang'])
                            {
                                $lang_select_params = return_name_for_id_select_param_value ($id_online_lang, $one_text_param['id']);
                            } else {
                                $lang_select_params = return_name_for_id_select_param_value_no_lang ($one_text_param['id']);
                            }
                if ($in_base)
                {
                    $select_html .= '<option value="'.$one_text_param['id'].'|'.$v['id'].'|'.$info_in_base['id'].'" selected>'.$lang_select_params['text'].'</option>'."\r\n";
                } else {
                    $select_html .= '<option value="'.$one_text_param['id'].'|'.$v['id'].'|'.$info_in_base['id'].'">'.$lang_select_params['text'].'</option>'."\r\n";
                }
                        }
                        if ($info_params['multiselect'])
                        {
                            $html_select_param .= '<select name="select[]" multiple size=5><option></option>'.$select_html.'</select>';
                        } else {
                            $html_select_param .= '<select name="select[]"><option></option>'.$select_html.'</select>';
                        }
                    } else {
                        $html_select_param .= $lang[178];
                    }
                }
                $body_admin .= '<tr>
                <td valign="top">'.$param_translate['text'].'
                </td>
                <td>'.$html_select_param.'</td>
                </tr>';
                unset ($html_select_param, $select_html);*/
            break;
        case "price":
            if (!check_many_price_for_card($info_item['id_card']))
            {
                if (isset ($number_price))
                {
                    $number_price++;
                } else {
                    $number_price = 0;
                }
                $info_price_param_translate = return_name_for_id_price_param ($id_online_lang, $v['id_param']);
                $info_price = return_one_price_param_for_id ($v['id_param']);
                $info_price_in_base = return_items_price_param($v['id'], $_GET['id']);
                $html_price_param = '<input type="text" name="price['.$v['id_param'].']" size="8" value="'.$infoItem['price_'.$v['id_param']].'">';
                if ($info_price['convert_price'] and $info_price['reference_id'])
                {
                    $array_reference_value = return_reference_values_for_ref_id ($info_price['reference_id']);
                    if (count ($array_reference_value))
                    {
                        if (!isset ($option_ref_value))
                            $option_ref_value = '';
                        foreach ($array_reference_value as $value_ref)
                        {
                            $info_ref_value_translate = return_translate_for_ref_value ($id_online_lang, $value_ref['id']);
                            if ($info_price_in_base['convert_price']==$value_ref['id'])
                            {
                                $option_ref_value .= '<option value="'.$value_ref['id'].'|'.$info_price_in_base['id'].'" selected>'.$info_ref_value_translate['value'].'</option>';
                            } else {
                                $option_ref_value .= '<option value="'.$value_ref['id'].'|'.$info_price_in_base['id'].'">'.$info_ref_value_translate['value'].'</option>';
                            }
                        }
                    }
                    $html_price_param .= '<select name="convert_price_'.$v['id'].'[]">'.$option_ref_value.'</select>';
                }
                $body_admin .= '<tr>
			<td valign="top">'.$info_price_param_translate['text'].'
			</td>
			<td>'.$html_price_param.'</td>
			</tr>
				    <input type="hidden" name="id_cardparam_price[]" value="'.$v['id'].'">
				    ';
                unset ($array_reference_value, $option_ref_value);
            }
            break;
        case "boolean":
            $info_translate_boolean = return_name_for_id_boolean_param ($id_online_lang, $v['id_param']);
            //print_r ($info_translate_boolean);
            $info_boolean = return_items_boolean_param($v['id'], $_GET['id']);
            $info_boolean_value = return_items_boolean_param_value($v['id'], $_GET['id']);
            if ($info_boolean)
            {
                $html_boolean_param = '<input type="checkbox" name="boolean[]" value="'.$v['id'].'|'.$info_boolean_value['id'].'" checked>';
            } else {
                $html_boolean_param = '<input type="checkbox" name="boolean[]" value="'.$v['id'].'|'.$info_boolean_value['id'].'">';
            }
            $body_admin .= '<tr>
            <td valign="top">'.$info_translate_boolean['text'].'
            </td>
            <td>'.$html_boolean_param.'</td>
            </tr>';
            break;
    }
}
$body_admin .= '
<tr>
    <td colspan="2" align="center">
	<div><input type="submit" name="submit" value="'.$lang[179].'"></div>
	<div><a href="'.$config ['site_url'].'mode/item-'.$_GET['id'].'.html" target="_blank">переглянути товар на сайті</a></div>
    </td>
</tr>
</table></form></div>';
?>