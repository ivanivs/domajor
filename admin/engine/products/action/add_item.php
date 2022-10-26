<?php
require ('engine/params/functions.php');
$other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[177];
if (isset ($_POST['submit_2']))
{
	$reference_value = $_POST['reference_value'];
	$price = $_POST['price'];
	$param = $_POST['param'];
	//print_r ($reference_value);
	//print_r ($price);
	//print_r ($param);
	foreach ($price as $key => $v)
	{
		$sql = "
		INSERT INTO  `ls_values_ligament_price` (
		`id_item` ,
		`price` ,
		`id_reference_value`
		)
		VALUES (
		'".$_POST['id_item']."' ,
		'".$v."' ,
		'".$reference_value[$key]."'
		);
		";
		if (mysql_query($sql))
		{
			$id_values_ligament_price = mysql_insert_id();
			$array_param = explode ('|', $param[$key]);
			foreach ($array_param as $one_param)
			{
				$sql = "INSERT INTO  `ls_values_ligament_price_param` (
				`id_ligament` ,
				`id_item` ,
				`id_param_value`
				)
				VALUES (
				'".$id_values_ligament_price."' ,
				'".$_POST['id_item']."' ,
				'".$one_param."'
				);";
				mysql_query($sql);
			}
		}
	}
	$body_admin .= '<span style="color:green">'.$lang[517].'</span><br>
	<a href="index.php?do=products&action=add_item&id_card='.$_GET['id_card'].'">'.$lang[518].'</a>
	';
} else {
	if (isset ($_POST['submit']))
	{
		$array_text = $_POST['text'];
		$id_cardparam_text = $_POST['id_cardparam_text'];
		$id_lang = $_POST['id_lang'];
		mysql_query ('START TRANSACTION;');
		$sql = "
		INSERT INTO  `ls_items` (
		`time` ,
		`id_card`
		)
		VALUES (
		'".time()."' ,
		'".$_GET['id_card']."'
		);
		";
		mysql_query ($sql);
		$id_item = mysql_insert_id();
		foreach ($array_text as $key => $v)
		{
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
			'".$id_item."' ,
			'".$id_cardparam_text[$key]."'
			);
			";
			//mysql_query($sql);
            $infoIDparam = mysql_fetch_array(mysql_query("SELECT * FROM `ls_cardparam` where `id` = '".$id_cardparam_text[$key]."';"), MYSQL_ASSOC);
            mysql_query ("UPDATE `ls_items` set `text_".$infoIDparam['id_param']."` = '".mysql_real_escape_string($v)."' where `id` = '".$id_item."';");
		}
		$select = $_POST['select'];
        print_r ($select);
		foreach ($select as $v)
		{
			$array_select_v = explode ("|", $v);
			$sql = "
			INSERT INTO  `ls_values_select` (
			`id_cardparam` ,
			`value` ,
			`id_item`
			)
			VALUES (
			'".$array_select_v[1]."' ,
			'".$array_select_v[0]."' ,
			'".$id_item."'
			);
			";
			//mysql_query($sql);
            $infoIDparam = mysql_fetch_array(mysql_query("SELECT * FROM `ls_cardparam` where `id` = '".$array_select_v[1]."';"), MYSQL_ASSOC);
            $arrayToItems[$array_select_v[1]][] = $array_select_v[0];
		}
        print_r ($arrayToItems);
        foreach ($arrayToItems as $key => $v)
        {
            $infoParam = mysql_fetch_array(mysql_query("SELECT * FROM `ls_params_select` where `id` = '".$key."';"), MYSQL_ASSOC);
            if ($infoParam['multiselect'])
            {
                $sql = "UPDATE `ls_items` set `select_".$key."` = '".mysql_real_escape_string(json_encode($v))."' where `id` = '".$id_item."';";
            } else {
                echo $sql = "UPDATE `ls_items` set `select_".$key."` = '".$v[0]."' where `id` = '".$id_item."';";
            }
            mysql_query ($sql);
        }
		$id_cardparam_price = $_POST['id_cardparam_price'];
		if (isset ($_POST['id_cardparam_price']))
		{
			foreach ($id_cardparam_price as $v)
			{
				if (isset ($_POST['price_'.$v]))
				{
					$value_price = $_POST['price_'.$v];
					$convert_price = $_POST['convert_price_'.$v];
					$sql = "
					INSERT INTO  `ls_values_prices` (
					`value` ,
					`convert_price` ,
					`id_item` ,
					`id_cardparam`
					)
					VALUES (
					'".$value_price[0]."' ,
					'".$convert_price[0]."' ,
					'".$id_item."' ,
					'".$v."'
					);
					";
					//mysql_query($sql);
                    $infoIDparam = mysql_fetch_array(mysql_query("SELECT * FROM `ls_cardparam` where `id` = '".$v."';"), MYSQL_ASSOC);
                    mysql_query ("UPDATE `ls_items` set `price_".$infoIDparam['id_param']."` = '".mysql_real_escape_string($value_price[0])."' where `id` = '".$id_item."';");
				}
			}
		}
		$boolean = $_POST['boolean'];
		if ($boolean)
		{
			foreach ($boolean as $v)
			{
				$sql = "
				INSERT INTO  `ls_values_boolean` (
				`id_cardparam` ,
				`id_item`
				)
				VALUES (
				'".$v."' ,
				'".$id_item."'
				);
				";
				//mysql_query($sql);
                $infoIDparam = mysql_fetch_array(mysql_query("SELECT * FROM `ls_cardparam` where `id` = '".$v."';"), MYSQL_ASSOC);
                mysql_query ("UPDATE `ls_items` set `boolean_".$infoIDparam['id_param']."` = '1' where `id` = '".$id_item."';");
			}
		}
		if (check_image_param($_GET['id_card']))
		{
			$add_image = '
			<script language="JavaScript"> 
			 function erase()
				{
					var cont = document.getElementById(\'add_image\');
					cont.innerHTML = "";
				}
			</script>
			<a href="#" id="add_image" onClick="erase(); window.open(\'add_image.php?id='.$_GET['id_card'].'&id_item='.$id_item.'\', \'_blank\', \'Toolbar=0, Scrollbars=1, Resizable=0, Width=640, resize=no, Height=480\');">'.$lang[181].'</a>';
		}
		if (mysql_query ('COMMIT;'))
		{
			$body_admin .= '<h2 align="center" style="color:green">'.$lang[180].'<br>'.$add_image.'</h2>';
		}
		if (check_many_price_for_card($_GET['id_card']))
		{
			$array_ligament_for_card = return_all_ligament_of_id_card($_GET['id_card']);
			if ($array_ligament_for_card)
			{
				foreach ($array_ligament_for_card as $key => $v)
				{
					$info_card_param = mysql_fetch_array(mysql_query("SELECT * FROM `ls_cardparam` where `id_card` = '".$_GET['id_card']."' and `db_type` = 'select' and `id_param` = '".$v['id_param']."';"), MYSQL_ASSOC);
					$sql = "SELECT * FROM `ls_values_select` where `id_cardparam` = '".$info_card_param['id']."' and `id_item` = '".$id_item."';";
					$results = mysql_query($sql);
					$info_values_select = mysql_numrows($results);
					if ($info_values_select>=1)
					{
						for ($i=0; $i<$info_values_select; $i++)
						{	
							$array_ls_values_select[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
						}
						foreach ($array_ls_values_select as $v)
						{
							$array_ligament_param[$key][] = $v;
							$array_param_info[$key][] = $v['value'];
						}
					}
					unset ($array_ls_values_select);
				}
				//print_r ($array_param_info);
				$body_admin .= '
				<h2>'.$lang[516].'</h2>
				<form action="" method="POST">
				<table border="0">
				<tr>
				';
				foreach ($array_ligament_param as $v)
				{
					foreach ($v as $key_of_param => $one_v)
					{
						if ($v[$key_of_param-1]['id_cardparam']!=$v[$key_of_param]['id_cardparam'])
						{
							//print $v[$key_of_param]['value'];
							$info_param = mysql_fetch_array(mysql_query("SELECT `id_params` FROM `ls_params_select_values` where `id` = '".$one_v['value']."';"), MYSQL_ASSOC);
							$transalte_param = mysql_fetch_array(mysql_query("SELECT `text` FROM `ls_translate` where `type` = 'select' and `id_elements` = '".$info_param['id_params']."' and `id_lang` = '".$id_online_lang."';"), MYSQL_ASSOC);
							$body_admin .= '<th style="font-weight:bold;">'.$transalte_param['text'].'</th>';
						}
					}
				}
				//print_r ($array_ligament_param);
				$body_admin .= '
				<th>Цена</th>
				</tr>
				';
				for ($i=0; $i<count($array_ligament_param)-1; $i++)
				{
					foreach ($array_ligament_param[$i] as $value)
					{
						//print_r ($value);
						$info_param = mysql_fetch_array(mysql_query("SELECT * FROM `ls_params_select_values` where `id` = '".$value['value']."';"), MYSQL_ASSOC);
						if (strlen($info_param['img'])>0)
						{
							$translate_param['text'] = '<img src="'.$config ['site_url'].'upload/select_params/'.$info_param['img'].'" border="0">';
						} else {
							$sql = "SELECT `text` FROM `ls_translate` where `type` = 'select_value' and `id_elements` = '".$value['value']."' and `id_lang` = '".$id_online_lang."';";
							$translate_param = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
						}
						$one = '<tr><td>'.$translate_param['text'].'</td>';
						foreach ($array_ligament_param[$i+1] as $key => $value_2)
						{
							$info_param = mysql_fetch_array(mysql_query("SELECT * FROM `ls_params_select_values` where `id` = '".$value_2['value']."';"), MYSQL_ASSOC);
							if (strlen($info_param['img'])>0)
							{
								$translate_param['text'] = '<img src="'.$config ['site_url'].'upload/select_params/'.$info_param['img'].'" border="0">';
							} else {
								$sql = "SELECT `text` FROM `ls_translate` where `type` = 'select_value' and `id_elements` = '".$value_2['value']."' and `id_lang` = '".$id_online_lang."';";
								$translate_param = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
							}
							$info_price_cardparam = mysql_fetch_array(mysql_query("SELECT * FROM `ls_cardparam` where `db_type` = 'price' and `id_card` = '".$_GET['id_card']."';"), MYSQL_ASSOC);
							$info_price = mysql_fetch_array(mysql_query("SELECT * FROM `ls_params_price` where `id` = '".$info_price_cardparam['id_param']."';"), MYSQL_ASSOC);
							$array_reference_value = return_reference_values_for_ref_id ($info_price['reference_id']);
							if (count ($array_reference_value))
							{
							    foreach ($array_reference_value as $value_ref)
							    {
								$info_ref_value_translate = return_translate_for_ref_value ($id_online_lang, $value_ref['id']);
								$option_ref_value .= '<option value="'.$value_ref['id'].'">'.$info_ref_value_translate['value'].'</option>';
							    }
							}
							$html_price_param = '<select name="reference_value[]" class="form_text">'.$option_ref_value.'</select>';
							unset ($option_ref_value);
							$body_admin .= $one.'<td>'.$translate_param['text'].'</td>
								<td>
									<input type="text" name="price[]" class="form_text"> '.$html_price_param.'
									<input type="hidden" name="param[]" value="'.$value['value'].'|'.$value_2['value'].'"> 
								</td>
								</tr>
							';
						}
					}
				}
				$body_admin .= '
				</table>
				<input type="hidden" name="id_item" value="'.$id_item.'">
				<input type="submit" name="submit_2" value="'.$lang[515].'" class="form_text">
				</form>';
			}
		}
	} else {
		$body_admin .= '<h2 id="title" align="center">'.$lang[177].'<h2>';
		if (isset ($_GET['up']) or isset ($_GET['down']))
		{
		    if (isset ($_GET['up']))
		    {
			$info_this_param = return_param_for_card_one_for_id ($_GET['up']);
			$info_next_param = return_param_for_card_one_for_position ($info_this_param['position']+1, $_GET['id_card']);
			if (count ($info_next_param)>1)
			{
			    $sql = "update ls_cardparam set position='".$info_next_param['position']."' where id='".$info_this_param['id']."';";
			    mysql_query ($sql);
			    $sql = "update ls_cardparam set position='".$info_this_param['position']."' where id='".$info_next_param['id']."';";
			    mysql_query ($sql);
			}
		    }
		    if (isset ($_GET['down']))
		    {
			$info_this_param = return_param_for_card_one_for_id ($_GET['down']);
			$info_next_param = return_param_for_card_one_for_position ($info_this_param['position']-1, $_GET['id_card']);
			if (count ($info_next_param)>1)
			{
			    $sql = "update ls_cardparam set position='".$info_next_param['position']."' where id='".$info_this_param['id']."';";
			    mysql_query ($sql);
			    $sql = "update ls_cardparam set position='".$info_this_param['position']."' where id='".$info_next_param['id']."';";
			    mysql_query ($sql);
			}
		    }
		}
		$array_params_for_card = return_parafm_for_card ($_GET['id_card']);
		$all_lang = return_all_ok_lang();
		$body_admin .= '<div align="center">
		<form action="" method="POST">
		<table border="0">';
		if (!isset ($html_text_param))
		$html_text_param = '';
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
				foreach ($all_lang as $one_lang)
				{
				    if ($param_info['multiline'])
				    {
					if ($param_info['wysiwyg'])
					{
					    if (!isset ($wysiwyg) or !$wysiwyg)
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
					    if (!isset ($html_text_param))
					    $html_text_param = '';
					    $html_text_param .= '
					    <b>'.$one_lang['name'].'</b><br>
					    <textarea name="text[]" class="mceEditor"></textarea>
									<input type="hidden" name="id_cardparam_text[]" value="'.$v['id'].'">
					    <input type="hidden" name="id_lang[]" value="'.$one_lang['id'].'"><br>
					    ';
					} else {
					    $html_text_param .= '
					    <b>'.$one_lang['name'].'</b><br>
					    <textarea name="text[]" cols="40" rows="6"></textarea>
									<input type="hidden" name="id_cardparam_text[]" value="'.$v['id'].'">
					    <input type="hidden" name="id_lang[]" value="'.$one_lang['id'].'"><br>
					    ';
					}
				    } else {
					$html_text_param .= '
					<b>'.$one_lang['name'].'</b><br>
					<input type="text" name="text[]">
								<input type="hidden" name="id_cardparam_text[]" value="'.$v['id'].'">
					<input type="hidden" name="id_lang[]" value="'.$one_lang['id'].'"><br>
					';
				    }
				}
			    } else {
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
					<textarea name="text[]" class="mceEditor"></textarea>
								<input type="hidden" name="id_cardparam_text[]" value="'.$v['id'].'">
					<input type="hidden" name="id_lang[]" value="'.$one_lang['id'].'"><br>
					';
				    } else {
					$html_text_param .= '
					<textarea name="text[]" cols="40" rows="6"></textarea>
								<input type="hidden" name="id_cardparam_text[]" value="'.$v['id'].'">
					<input type="hidden" name="id_lang[]" value="'.$one_lang['id'].'"><br>
					';
				    }
				} else {
					if (!isset ($html_text_param))
					$html_text_param = '';
					$html_text_param .= '
						<input type="text" name="text[]">
									    <input type="hidden" name="id_cardparam_text[]" value="'.$v['id'].'">
						<input type="hidden" name="id_lang[]" value="'.$one_lang['id'].'"><br>
					';
				}
			    }
			    $body_admin .= '<tr>
			    <td valign="top">'.$param_translate['text'].'
			    <a href="index.php?do=products&action=add_item&id_card='.$_GET['id_card'].'&up='.$v['id'].'"><img src="'.$config ['site_url'].'images/admin/1uparrow.png" border="0"></a>
			    <a href="index.php?do=products&action=add_item&id_card='.$_GET['id_card'].'&down='.$v['id'].'"><img src="'.$config ['site_url'].'images/admin/1downarrow.png" border="0"></a>
			    </td>
			    <td>'.$html_text_param.'</td>
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
				    if ($info_params['multiselect'])
				    {
					$html_select_param .= '<input type="checkbox" name="select[]" value="'.$one_img['id'].'|'.$v['id'].'">';
				    } else {
					$html_select_param .= '<input type="radio" name="select[]" value="'.$one_img['id'].'|'.$v['id'].'">';
				    }
				    $html_select_param .= '</td>
				    <td>
				    <img src="'.$config ['site_url'].'upload/select_params/'.$one_img['img'].'" style="border: 1px solid black;">
				    </td>
				    </tr>';
				}
				$html_select_param .= '</table>';
			    } else {
				$results_parent = mysql_query("SELECT * FROM `ls_params_select` where `parent_id` = '".$v['id_param']."';");
				$info_parent_id = mysql_num_rows ($results_parent);
				if ($info_parent_id)
				{
					$info_child = mysql_fetch_array ($results_parent, MYSQL_ASSOC);
					//print_r ($info_child);
					$info_id_cardparam_child = mysql_fetch_array(mysql_query("SELECT `id` FROM `ls_cardparam` where `id_param` = '".$info_child['id']."' and `db_type` = 'select' and `id_card` = '".intval($_GET['id_card'])."';"));
					$id_card_param = $info_id_cardparam_child['id'];
					$child_functions = 'onchange="load_child_param(\''.$id_card_param.'\', \''.$info_child['id'].'\', \''.$v['id_param'].'\', \''.$id_online_lang.'\');";';
				}
				if ($text_params)
				{
				    $all_values_text = return_all_text_values_params ($v['id_param']);
				    if (!isset ($select_html))
				    $select_html = '';
				    foreach ($all_values_text as $one_text_param)
				    {
					if ($info_params['more_lang'])
					{
					    $lang_select_params = return_name_for_id_select_param_value ($id_online_lang, $one_text_param['id']);
					} else {
					    $lang_select_params = return_name_for_id_select_param_value_no_lang ($one_text_param['id']);
					}
					$select_html .= '<option value="'.$one_text_param['id'].'|'.$v['id_param'].'">'.$lang_select_params['text'].'</option>'."\r\n";
				    }
				    if (!isset ($child_functions))
				    $child_functions = '';
				    if (!isset ($html_select_param))
				    $html_select_param = '';
				    if ($info_params['multiselect'])
				    {
					$html_select_param .= '<select name="select[]" multiple size=10 id="select_'.$v['id_param'].'" '.$child_functions.'><option></option>'.$select_html.'</select>';
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
			    <a href="index.php?do=products&action=add_item&id_card='.$_GET['id_card'].'&up='.$v['id'].'"><img src="'.$config ['site_url'].'images/admin/1uparrow.png" border="0"></a>
			    <a href="index.php?do=products&action=add_item&id_card='.$_GET['id_card'].'&down='.$v['id'].'"><img src="'.$config ['site_url'].'images/admin/1downarrow.png" border="0">
			    </td>
			    <td>'.$html_select_param.'</td>
			    </tr>';
			    unset ($html_select_param, $select_html);
			break;
			case "price":
				if (!check_many_price_for_card($_GET['id_card']))
				{
					if (isset ($number_price))
					{
						$number_price++;
					} else {
						$number_price = 0;
					}
					$info_price_param_translate = return_name_for_id_price_param ($id_online_lang, $v['id_param']);
					$info_price = return_one_price_param_for_id ($v['id_param']);
					$html_price_param = '<input type="text" name="price_'.$v['id'].'[]" size="8">';
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
						    $option_ref_value .= '<option value="'.$value_ref['id'].'">'.$info_ref_value_translate['value'].'</option>';
						}
					    }
					    $html_price_param .= '<select name="convert_price_'.$v['id'].'[]">'.$option_ref_value.'</select>';
					}
					$body_admin .= '<tr>
					<td valign="top">'.$info_price_param_translate['text'].'
					<a href="index.php?do=products&action=add_item&id_card='.$_GET['id_card'].'&up='.$v['id'].'"><img src="'.$config ['site_url'].'images/admin/1uparrow.png" border="0"></a>
					<a href="index.php?do=products&action=add_item&id_card='.$_GET['id_card'].'&down='.$v['id'].'"><img src="'.$config ['site_url'].'images/admin/1downarrow.png" border="0">
					</td>
					<td>'.$html_price_param.'</td>
					</tr>
						    <input type="hidden" name="id_cardparam_price[]" value="'.$v['id'].'">
					';
				}
			break;
			case "boolean":
			    $info_translate_boolean = return_name_for_id_boolean_param ($id_online_lang, $v['id_param']);
			    //print_r ($info_translate_boolean);
			    $html_boolean_param = '<input type="checkbox" name="boolean[]" value="'.$v['id'].'">';
			    $body_admin .= '<tr>
			    <td valign="top">'.$info_translate_boolean['text'].'
			    <a href="index.php?do=products&action=add_item&id_card='.$_GET['id_card'].'&up='.$v['id'].'"><img src="'.$config ['site_url'].'images/admin/1uparrow.png" border="0"></a>
			    <a href="index.php?do=products&action=add_item&id_card='.$_GET['id_card'].'&down='.$v['id'].'"><img src="'.$config ['site_url'].'images/admin/1downarrow.png" border="0"></a>
			    </td>
			    <td>'.$html_boolean_param.'</td>
			    </tr>';
			break;
		    }
		}
		$body_admin .= '
		<tr>
		    <td colspan="2" align="center"><input type="submit" name="submit" value="'.$lang[179].'"></td>
		</tr>
		</table></form></div>';
		//$position = 0;
		if ($position==0)
		{
		    foreach ($array_params_for_card as $key => $v)
		    {
			$pos = count($array_params_for_card)-$key;
			$sql = "UPDATE ls_cardparam set position='".$pos."' where id='".$v['id']."';";
			mysql_query ($sql);
		    }
		}
	}
}
?>