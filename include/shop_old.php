<?phprequire ('admin/engine/template/functions.php');$main_template = return_one_template($config['user_params_29']);$main_template_array = explode ('<--page-->', $main_template['template']);if (count($main_template_array)>1){	$main_template = stripslashes($main_template_array[1]);	} else {	$main_template = $main_template['template'];	unset ($main_template_array);}$array_template = returnSubstrings($main_template, '{template_', '}');$array_image = returnSubstrings($main_template, '{image_', '}');$array_text = returnSubstrings($main_template, '{text_', '}');$array_price = returnSubstrings($main_template, '{price_', '}');$array_select = returnSubstrings($main_template, '{select_', '}');if (isset ($_GET['a'])){	$array = $_GET['a'];	if (isset ($array['select']))	{		$limit_count = $config['user_params_7'];		if (!isset ($_GET['page']))		{			$limit_start = 0;		} else {			$limit_start = ($_GET['page']-1)*$limit_count;		}		if (count($array['select'])==1)		{			if (isset ($_GET['min_price']))			{				$price = $_GET['id_param_price'].'|'.$_GET['min_price'].'|'.$_GET['max_price'];				$array_items = return_all_items ($_GET['a']['select'], 5, '', $limit_start, $limit_count, $price);				$count_items = return_count_items ($_GET['a']['select'], 5 , '', $price);			} else {				$array_items = return_all_items ($array['select'][0], 4, '', $limit_start, $limit_count);				$count_items = return_count_items ($array['select'][0], 4 , '');				}		} else {			if (isset ($_GET['and_param']))			{				$array_items = return_all_items ($_GET['a']['select'], 7, '', $limit_start, $limit_count, '');				$count_items = return_count_items ($_GET['a']['select'], 7 , '', '');			} else {				if (isset ($_GET['more_param']))				{					$array_items = return_all_items ($_GET['a']['select'], 6, '', $limit_start, $limit_count, '');					$count_items = return_count_items ($_GET['a']['select'], 6 , '', '');				} else {					$price = $_GET['id_param_price'].'|'.$_GET['min_price'].'|'.$_GET['max_price'];										$array_items = return_all_items ($_GET['a']['select'], 5, '', $limit_start, $limit_count, $price);					$count_items = return_count_items ($_GET['a']['select'], 5 , '', $price);				}			}		}		if (!isset ($_GET['and_param']))		{			$sql = "select `parent_param_id` from ls_params_select_values where `id` = '".$array['select'][0]."';";			$info_tmp = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);			if ($info_tmp['parent_param_id'])			{				$js_script = '				$("#my_'.($info_tmp['parent_param_id']).'").next().slideToggle(1000);				';			} else {				$js_script = '				$("#my_'.($array['select'][0]).'").next().slideToggle(1000);				';			}		}	}}if (isset ($_COOKIE['login']) and $_COOKIE['login']=='admin'){	$body .= '<div style="color:white;">Знайдено '.$count_items.' товарів</div>';}if ($array_items)	{		foreach ($array_items as $key => $v)		{			$html_item = $main_template;			$info_item = return_item_info ($v['id'], $id_online_lang);			$sql = "select * from `ls_items` where `id` = '".$v['id']."';";			$results = mysql_query($sql);			$info_item_ls_item = mysql_fetch_array($results, MYSQL_ASSOC);			if (count($array_template))			{				foreach ($array_template as $v_template)				{				    $a_template = explode ('_', $v_template);				    if ($a_template[1]==$info_item_ls_item['id_card'])				    {					$add_template = return_one_template($a_template[0]);					$add_template = $add_template['template'];					$html_item = str_replace ('{template_'.$v_template.'}', $add_template, $html_item);					$array_image = returnSubstrings($add_template, '{image_', '}');					$array_text = returnSubstrings($add_template, '{text_', '}');					$array_price = returnSubstrings($add_template, '{price_', '}');					$array_select = returnSubstrings($add_template, '{select_', '}');				    } else {					$html_item = str_replace ('{template_'.$v_template.'}', '', $html_item);				    }				}			}			if (count($array_image))			{				foreach ($array_image as $image_id)				{					$a_img = explode ('_', $image_id);					if (isset ($info_item['image'.$a_img[0]]))					{						if (isset ($info_item['image'.$a_img[0]]))						$image = $info_item['image'.$a_img[0]];						if (isset ($image))						$image = $image[($a_img[1]-1)]['text'];						$html_item = str_replace ('{image_'.$image_id.'}', urlencode('upload/userparams/'.$image), $html_item);					} else {						$html_item = str_replace ('{image_'.$image_id.'}', urlencode('upload/no_image.png'), $html_item);					}				}			}			if (count($array_text))			{				foreach ($array_text as $text_id)				{					$a_text = explode ('_', $text_id);					$text = $info_item['text'.$a_text[0]];					$text = $text['text'];					if (isset ($a_text[1]) and $a_text[1])					{						if (strlen($text)>$a_text[1])						{							mb_internal_encoding("UTF-8");							$text = mb_substr ($text, 0, $a_text[1]).'...';							mb_internal_encoding("windows-1251");						}					}					$html_item = str_replace ('{text_'.$text_id.'}', $text, $html_item);				}			}			if (count($array_select))			{				foreach ($array_select as $select_id)				{					$select = $info_item['select'.$select_id];					if (count($select))					{						foreach ($select as $key => $v1)						{						    $html_select .= $v1['text'];						    if (isset ($select[$key+1]))						    {							$html_select .= ',';						    }						}					}					$html_item = str_replace ('{select_'.$select_id.'}', $html_select, $html_item);					unset ($html_select);				}			}			if (count($array_price))			{				if ($_SERVER['REMOTE_ADDR']=='193.107.168.237')				{					//print_r ($array_price);					//print_r ($info_item['price6']);					//print_r ($info_item);				}				foreach ($array_price as $price_id)				{					$results_many_price = mysql_query("SELECT * FROM `ls_values_ligament_price` where `id_item` = '".$v['id']."' order by `price` LIMIT 0,1;");					$maybe_many_price = mysql_num_rows($results_many_price);					if (!$maybe_many_price)					{						if ($info_item['select42'][0]['id_value']==3884)						{							$priceAction = $info_item['price6'];							$priceAction = $priceAction['text'];							$array_price_action = explode ('|', $priceAction);							$html_item = str_replace ('price_main', 'old_price_action', $html_item);							//$arrayActionPrice = returnSubstrings($html_item, '<!--price_', '_end-->');							//$html_item = str_replace ('<!--price_'.$arrayActionPrice[0].'_end-->', '', $html_item);						} else {							$price = $info_item['price3'];							$price = $price['text'];							$array_price_new = explode ('|', $price);							//$arrayActionPrice = returnSubstrings($html_item, '<!--price_', '_end-->');							$titre = preg_match_all("|<!--price_(.*)_end-->|isU",$html_item,$regs);							$arrayActionPrice = $regs[1];							$html_item = str_replace ('<!--price_'.$arrayActionPrice[0].'_end-->', '', $html_item);							$html_item = str_replace ('{valuta}', $array_price_new[1], $html_item);						}						$price = $info_item['price'.$price_id];						$price = $price['text'];						$array_price_new = explode ('|', $price);						if ($array_price_new[0]!=0)						{							$html_item = str_replace ('{price_'.$price_id.'}', $array_price_new[0], $html_item);							$html_item = str_replace ('{valuta}', $array_price_new[1], $html_item);						} else {							$html_item = str_replace ('{price_'.$price_id.'}', '', $html_item);							$html_item = str_replace ('{valuta}', '', $html_item);							//$html_item = str_replace ('<span>грн.</span>', 'знято з виробництва', $html_item);						}					} else {						$info_price_many = mysql_fetch_array($results_many_price, MYSQL_ASSOC);						$html_item = str_replace ('{price_'.$price_id.'}', $info_price_many['price'], $html_item);						$info_valuta_many_price = mysql_fetch_array(mysql_query("SELECT `value` FROM `ls_reference_values_translate` where `id_reference_value` = '".$info_price_many['id_reference_value']."' and `id_lang` = '".$id_online_lang."';"), MYSQL_ASSOC);						$html_item = str_replace ('{valuta}', $info_valuta_many_price['value'], $html_item);					}				}			}			$html_item = str_replace ('{link}', '{main_sait}{lang}/mode/item-'.$v['id'].'.html', $html_item);			if (isset ($_GET['admin']) or $_SESSION['admin'])			{				$html_item = str_replace ('<div id="prev_foto">', '<a href="http://intarsio.com.ua/admin/index.php?do=products&action=edit_item&id='.$v['id'].'">редагувати</a><div id="prev_foto">', $html_item);			}			if (!isset ($body_tmp))			$body_tmp = '';			$body_tmp .= $html_item;			if (isset ($array_price_new[0]) and (!isset ($max_price) or $array_price_new[0]>$max_price))			{				$max_price = $array_price_new[0];			}			if (isset ($array_price_new[0]) and (!isset ($min_price) or $array_price_new[0]<$min_price))			{				$min_price = $array_price_new[0];			}			$body_tmp = str_replace ('{id_item}', $v['id'], $body_tmp);		}		$top_filtr_template = return_one_template($config['user_params_21']);		$top_filtr_template = $top_filtr_template['template'];		require_once('admin/engine/filtr/functions.php');		$sql = "SELECT `id` FROM ls_filtr where id_select_values='".$array['select'][0]."';";		$results = mysql_query($sql);		$number = @mysql_num_rows ($results);		if ($number>0)		{			$info_filtr = mysql_fetch_array($results, MYSQL_ASSOC);			$array_filtr_param = return_param_for_filtr ($info_filtr['id']);			$sql = "select `id` from `ls_filtr_param` where id_filtr='".$info_filtr['id']."' and visible=0";			$results = mysql_query($sql);			$number = @mysql_num_rows ($results);			if($number)			{				if ($array_filtr_param)				{					$link_more_param = '<div id="link_more_param"><a href="#">'.$lang[377].'</a></div>';				}			}			$id_block = 0;			foreach ($array_filtr_param as $key => $v)			{				//print $array_filtr_param[$key]['id_param']."<br>";				//print $array_filtr_param[$key-1]['id_param']."<br>";				if ($array_filtr_param[$key]['id_param']!=$array_filtr_param[$key-1]['id_param'])				{					$tr_param = return_one_translate ($v['id_param'], $id_online_lang, 'select');					$filtr .= '<div style="float:left; text-align:left; margin:30px;" id="block_'.$id_block.'"><span style="font-size:14px;font-family:Verdana, Arial, Helvetica, sans-serif; font-weight:bold;">'.$tr_param['text'].'</span>';					$id_block++;				}				if ($v['visible'])				{					$tr_param = return_one_translate ($v['id_value_param'], $id_online_lang, 'select_value');					$filtr .= '<div id="param_'.$v['id'].'"><input type="checkbox" id="checkbox_'.$v['id'].'" onClick="change_filtr_param(\''.$id_block.'_'.$v['id'].'\', \''.$v['id'].'\');" value="'.$v['id'].'"> '.$tr_param['text'].'</div>';				} else {					$array_id_hidden[] = $v['id'];					$other_param = '<div id="other_param_'.$id_block.'" style="padding-left:15px; font-size:12px; text-decoration:underline;" onclick="visible_param(Array('.implode(',', $array_id_hidden).'), \''.$id_block.'\')">'.$lang[378].'</div>';					$tr_param = return_one_translate ($v['id_value_param'], $id_online_lang, 'select_value');					$filtr .= '<div id="param_'.$v['id'].'" style="display: none;"><input type="checkbox" id="checkbox_'.$v['id'].'" onClick="change_filtr_param(\''.$id_block.'_'.$v['id'].'\', \''.$v['id'].'\');" value="'.$v['id'].'"> '.$tr_param['text'].'</div>';									}				if ($array_filtr_param[$key]['id_param']!=$array_filtr_param[$key+1]['id_param'])				{					$filtr .= $other_param.'</div>';					unset ($other_param);					unset ($array_id_hidden);				}				$array_js_param .= $v['id'];				if (isset ($array_filtr_param[$key+1]))				{					$array_js_param .= ', ';				}			}		}		if (count($array['select'])>1)		{			$link_erase_search = '<a href="?a[select][]='.$array['select'][0].'">'.$lang[382].'</a>';		}		if (!isset ($min_price))		$min_price = '';		if (!isset ($max_price))		$max_price = '';		if (!isset ($array_js_param))		$array_js_param = '';		if (!isset ($array_price_new))		$array_price_new[1] = '';		if (!isset ($price_id))		$price_id = '';		if (!isset ($filtr))		$filtr = '';		if (!isset ($link_erase_search))		$link_erase_search = '';		$top_filtr = '		<div id="top_filtr">		<div id="filtr_price">'.$lang[376].'</div>		<script type="text/javascript">			var array_param = [ '.$array_js_param.' ];			var array = \'\';			var change_param_m = \''.$lang[383].'\';			var change_param_b = \''.$lang[378].'\';			var bolshe_parametrov = 0;			$(function(){				// Slider				$(\'#slider\').slider({					range: true,					min: '.($min_price*0.9).',					max: '.($max_price*1.1).',					values: ['.($min_price*0.9).', '.($max_price*1.1).'],					animate: true,					slide:  function(event, ui) {							var min = document.getElementById(\'min\');							var values = $( "#slider" ).slider( "option", "values" );							min.innerHTML = values[0] + " '.$array_price_new[1].'";							var max = document.getElementById(\'max\');							max.innerHTML = values[1] + " '.$array_price_new[1].'";						} ,					change: function ()						{							change_filtr();							var min = document.getElementById(\'min\');							var values = $( "#slider" ).slider( "option", "values" );							min.innerHTML = values[0] + " '.$array_price_new[1].'";							var max = document.getElementById(\'max\');							max.innerHTML = values[1] + " '.$array_price_new[1].'";						}				});								//hover states on the static widgets							});			function change_filtr_param1(param, id)			{				checkB=document.getElementById("checkbox_" + id);				if (checkB.checked)				{					var new_array = array;					array = new_array + "a[select][]=" + param + "&";					change_filtr();				}			}			function change_filtr_param(param, id)			{				array = \'\';				for (var key in array_param) {					var val = array_param [key];					checkB=document.getElementById("checkbox_" + val);					tmp=document.getElementById("apply_2");					var new_array = array;					if (checkB.checked)					{						array = new_array + "a[select][]=" + checkB.value + "&";					}				}				change_filtr();			}			function change_filtr()			{				var cont = document.getElementById(\'apply\');				var values_price = $( "#slider" ).slider("option", "values");				cont.innerHTML = loading;				var query = "a[select][]='.$array['select'][0].'&" + array + "id_param_price='.$price_id.'&min_price=" + values_price[0] + "&max_price=" + values_price[1];				link = main_sait_url + "include/check_number_item.php?" + query;				query = \'\';				var http = createRequestObject();  				if( http )   				{  				    http.open(\'get\', link);				    http.onreadystatechange = function ()   				    {  					if(http.readyState == 4)   					{					    cont.style.display = \'block\';					    cont.innerHTML = http.responseText;  					}  				    }  				    http.send(null);      				}			}			function visible_param(array, id_block)			{				var block_param = document.getElementById(\'other_param_\' + id_block);				for (var key in array) {				var val = array [key];				var cont = document.getElementById(\'param_\' + val);				toggle(cont);				block_param.innerHTML = change_param_m;				}							}			function toggle(el) {			el.style.display = (el.style.display == \'none\') ? \'block\' : \'none\';			}			function param_4()			{				var cont = document.getElementById(\'param_4\');				cont.style.display = \'block\';			}		</script>		<div id="top_filtr_slider">			<div id="slider"></div>		</div>		<div style="float:left;" id="min">'.($min_price*0.9).' '.$array_price_new[1].'</div>		<div style="float:right;" id="max">'.($max_price*1.1).' '.$array_price_new[1].'</div>		<div id="clear"></div>		<span id="hr_filtr"></span>		<div id="clear"></div>		<div id="filtrs">		'.$filtr.'		</div>		<div id="clear"></div>		<div id="apply2"></div>		<div id="clear"></div>		<div id="apply"></div>		<div id="link_erase_search">'.$link_erase_search.'</div>		</div>		';		$info_mark = mysql_fetch_array(mysql_query("SELECT `text` FROM `ls_translate` where `id_lang` = '".$id_online_lang."' and `type` = 'select_value' and `id_elements` = '".$_GET['a']['select'][0]."';"), MYSQL_ASSOC);		if (isset ($_GET['a']['select'][1]))		{			$info_model = mysql_fetch_array(mysql_query("SELECT `text` FROM `ls_translate` where `id_lang` = '".$id_online_lang."' and `type` = 'select_value' and `id_elements` = '".$_GET['a']['select'][1]."';"), MYSQL_ASSOC);		}		//$body .= '<h1>'.$lang[553].' '.$info_mark['text'].' '.$info_model['text'].'</h1><div style="font-weight:bold;">'.$lang[554].' '.$info_mark['text'].'</div>';		if (!isset ($info_model))		$info_model['text'] = '';		$title = $info_mark['text'].' '.$info_model['text'].' - Интернет магазин мебели "Intarsio"';		/*$results = mysql_query("SELECT * FROM `ls_params_select_values` where `parent_param_id` = '".$_GET['a']['select'][0]."';");		$number = mysql_num_rows ($results);		for ($i=0; $i<$number; $i++)		{				$array_model[$i] = mysql_fetch_array($results, MYSQL_ASSOC);			}		if ($number)		{			foreach ($array_model as $key_model => $one_model)			{				$tr_model = mysql_fetch_array(mysql_query("SELECT `text` from `ls_translate` where `id_elements` = '".$one_model['id']."' and `type` = 'select_value';"), MYSQL_ASSOC);				if ($_GET['a']['select'][1]==$one_model['id'])				{					$body .= '<div style="float:left; margin-right:30px; width:200px; font-size:12px;"><a href="?a[select][]='.$_GET['a']['select'][0].'&a[select][]='.$one_model['id'].'&more_param" style="color:red;">'.$tr_model['text'].'</a></div>';				} else {					$body .= '<div style="float:left; margin-right:30px; width:200px; font-size:12px;"><a href="?a[select][]='.$_GET['a']['select'][0].'&a[select][]='.$one_model['id'].'&more_param">'.$tr_model['text'].'</a></div>';				}			}			$body .= '<div style="clear:both;"></div>';		}*/		require ('include/admin_shop.php');		if ($config['user_params_30'])		{			$body .= str_replace ('{content}', $top_filtr, $top_filtr_template);		}		$body .= '//paginator';		if (!isset ($main_template_array))		{			$main_template_array[0] = '';			$main_template_array[2] = '';			$main_template_array[3] = '';		}		if (!isset ($body))		$body = '';		$body .= $main_template_array[0];		$body .= $body_tmp;		$body .= $main_template_array[2];		$number_page = ceil($count_items/$limit_count);		$array = $_GET['a'];		if (count($array['select'])>1)		{			$all_params = '?';			foreach ($array['select'] as $key => $a)			{				$all_params .= 'a[select][]='.$a;				if (isset ($array['select'][$key+1]))				{					$all_params .= '&';				}			}			if (isset ($_GET['and_param']))			{				$all_params .= '&and_param';			}		} else {			$all_params = '?a[select][]='.$array['select'][0];		}		if (isset ($_GET['param']) and $_GET['param']=='sale')		{			$a = $config ['site_url'].$alt_name_online_lang.'/'.$_GET['mode'].'/'.$_GET['trash'].'/'.$all_params;		} else {			$a = $config ['site_url'].$alt_name_online_lang.'/'.$_GET['mode'].'/'.$_GET['trash'].'/'.$all_params;		}		$body .= '</div><div id="clear"></div>';		$paginator = '<div id="paginator" align="center">';		//if (!isset ($_GET['page']))		//$_GET['page'] = '';		for ($i=1; $i<=$number_page; $i++)		{			if (!isset ($_GET['page']) and $i==1)			{				$paginator .= '<span class="page">'.$i.'</span>';			} else {				if ($_GET['page']==$i)				{					$paginator .= '<span class="page">'.$i.'</span>';				} else {					$paginator .= '<a href="'.$a.'&page='.$i.'">'.$i.'</a>';				}				if ($i==23)				{					$paginator .= '<br><br>';				}			}		}		$body .= $paginator;		$body = str_replace ('//paginator', $paginator.'</div>', $body);		$body .= $main_template_array[3];	} else {		if (!isset ($body))		$body = '';		$info_mark = mysql_fetch_array(mysql_query("SELECT `text` FROM `ls_translate` where `id_lang` = '".$id_online_lang."' and `type` = 'select_value' and `id_elements` = '".$_GET['a']['select'][0]."';"), MYSQL_ASSOC);		if (isset ($_GET['a']['select'][1]))		{			$info_model = mysql_fetch_array(mysql_query("SELECT `text` FROM `ls_translate` where `id_lang` = '".$id_online_lang."' and `type` = 'select_value' and `id_elements` = '".$_GET['a']['select'][1]."';"), MYSQL_ASSOC);		}		//$body .= '<h1>'.$lang[553].' '.$info_mark['text'].' '.$info_model['text'].'</h1><div style="font-weight:bold;">'.$lang[554].' '.$info_mark['text'].'</div>';		if (!isset ($info_model))		$info_model['text'] = '';		$title = $info_mark['text'].' '.$info_model['text'].' - Интернет магазин мебели "Intarsio"';		if (isset ($main_template_array))		$body .= $main_template_array[0];		//$body .= $main_template_array[2];		if (intval($_GET['a']['select'][0])==3884)		{			$main_template = return_one_template(26);			$main_template = $main_template['template'];		} else {			$main_template = return_one_template($config['user_params_20']);			$main_template = $main_template['template'];		}		$body .= $main_template;	}?>