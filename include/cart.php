<?php$title = $lang[655];if (isset ($_GET['del'])){	$sql = "DELETE from `ls_cart` where `id` = '".$_GET['del']."';";	mysql_query ($sql);}if (!isset ($_GET['go'])){	$uniq_id_in_base = $_COOKIE['PHPSESSID'];	$sql = "SELECT * FROM `ls_cart` where `uniq_user` = '".$uniq_id_in_base."' and status <> 1;";	$results = mysql_query($sql);	$number = mysql_num_rows ($results);	for ($i=0; $i<$number; $i++)	{			$array_item_in_cart[$i] = mysql_fetch_array($results, MYSQL_ASSOC);		}	$price = 0;	if ($number)	{		require ('admin/engine/template/functions.php');		$item_template = return_one_template($config['user_params_13']);		$item_template = $item_template['template'];		if (!isset ($body))		$body = '';		$body .= $item_template;		$allprice = 0;		$valuta = '';		foreach ($array_item_in_cart as $v)		{			$item_template = return_one_template($config['user_params_14']);			$item_template = $item_template['template'];						$array_template = returnSubstrings($item_template, '{template_', '}');//			$info_item = return_item_info ($v['id_item'], $id_online_lang);			$info_item = mysql_fetch_array(mysql_query("SELECT * FROM `ls_items` where `id` = '".$v['id_item']."';"), MYSQL_ASSOC);			$html_item = $item_template;			$sql = "select * from `ls_items` where `id` = '".$v['id_item']."';";			$results = mysql_query($sql);			$info_item_ls_item = mysql_fetch_array($results, MYSQL_ASSOC);			foreach ($array_template as $v_2)			{			    $a_template = explode ('_', $v_2);			    if ($a_template[1]==$info_item_ls_item['id_card'])			    {				$add_template = return_one_template($a_template[0]);				$add_template = $add_template['template'];				$html_item = str_replace ('{template_'.$v_2.'}', $add_template, $html_item);			    } else {				$html_item = str_replace ('{template_'.$v_2.'}', '', $html_item);			    }			}			$array_image = returnSubstrings($html_item, '{image_', '}');			$array_text = returnSubstrings($html_item, '{text_', '}');			$array_price = returnSubstrings($html_item, '{price_', '}');			$array_select = returnSubstrings($html_item, '{select_', '}');			$array_name = returnSubstrings($html_item, '{name_', '}');			//print_r ($info_item);			foreach ($array_image as $image_id)			{				$a_img = explode ('_', $image_id);				$image = $info_item['image'.$a_img[0]];				$image = $image[($a_img[1]-1)]['text'];				$html_item = str_replace ('{image_'.$image_id.'}', urlencode('upload/userparams/'.getMainImage ($info_item['id'])), $html_item);			}			foreach ($array_text as $text_id)			{				$a_text = explode ('_', $text_id);				$text = $info_item['text'.$a_text[0]];				$text = $text['text'];				$html_item = str_replace ('{text_'.$text_id.'}', $info_item['text_'.$text_id].$size, $html_item);                unset ($size);			}			foreach ($array_price as $price_id)			{				if (strlen($v['other_param'])==0)				{                    if ($info_item['price_1']!=0)                    {                        $price = $info_item['price_1'];                    } else {					    $price = $info_item['price_'.$price_id];                    }					$price = $price['text'];					$array_price_new = explode ('|', $price);					$html_item = str_replace ('{price_'.$price_id.'}', getPrice($array_price_new[0]), $html_item);					$html_item = str_replace ('{valuta}', $array_price_new[1], $html_item);					$allprice = $allprice + $array_price_new[0]*$v['number'];					$valuta = $array_price_new[1];				} else {                    if ($info_item['price_1']!=0)                    {                        $info_item['price_'.$price_id] = $info_item['price_1'];                    }					$array_price_param = explode ('|', $v['other_param']);					$price_for_other_param = return_price_for_param($v['id_item'], $array_price_param);					$html_item = str_replace ('{price_'.$price_id.'}', getPrice($info_item['price_1']), $html_item);					$tr_ref_value = mysql_fetch_array(mysql_query("SELECT `value` FROM `ls_reference_values_translate` where `id_reference_value` = '".$price_for_other_param[1]."' and `id_lang` = '".$id_online_lang."';"), MYSQL_ASSOC);					$html_item = str_replace ('{valuta}', $tr_ref_value['value'], $html_item);					$allprice = $allprice + getPrice($info_item['price_'.$price_id])*$v['number'];					$valuta = $tr_ref_value['value'];				}			}			foreach ($array_select as $select_id)			{				$select = $info_item['select'.$select_id];				foreach ($select as $key => $v_3)				{				    $html_select .= $v_3['text'];				    if (isset ($select[$key+1]))				    {					$html_select .= ',';				    }				}				$html_item = str_replace ('{select_'.$select_id.'}', $html_select, $html_item);				unset ($html_select);			}			foreach ($array_name as $name_id)			{			    $a_name = explode ('_', $name_id);			    switch ($a_name[0])			    {				case "select":				    $one_name = return_one_translate ($a_name[1], $id_online_lang, 'select');    				break;			    }			    $html_item = str_replace ('{text_'.$name_id.'}', $v['text_'.$name_id.''], $html_item);			}			$html_item = str_replace ('{id_item}', $v['id'], $html_item);			$html_item = str_replace ('{link}', '{main_sait}{lang}/mode/item-'.$v['id_item'].'.html', $html_item);			$html_item = str_replace ('{number}', '<div class="numberItemHtml">                                        <input type="text" id="numberItem_'.$v['id'].'" value="'.$v['number'].'" class="numberItem" style="width: 25px; border:1px solid grey;">                                            <div style="display:none; vertical-align:middle; margin-left: 5px; cursor: pointer;" class="numberItem_'.$v['id'].'_image" onclick="getNumberItem(\'numberItem_'.$v['id'].'\');">                                                <img src="'.$config ['site_url'].'images/browser-go_5945.png">                                            </div>                                        </div>', $html_item);            switch ($info_item['select_4']){                case 49:                    $dostavka = '<b>5-21 дня</b>';                    break;                case 42:                    $dostavka = '<b>1-2 дня</b>';                    break;                case 43:                    $dostavka = '<b>20-25 дней</b>';                    break;            }            $html_item = str_replace ('{dostavka}', $dostavka, $html_item);			$body .= $html_item;		}		$allsuma = $allprice+$config['user_params_16'];		$info_name_page = return_one_translate ($config ['topland_15'], $id_online_lang, 'static_page_nam');		$info_text = return_one_translate ($config ['topland_15'], $id_online_lang, 'static_page_tex');		$item_template = return_one_template($config['user_params_15']);		$item_template = $item_template['template'];		$item_template = str_replace ('{allprice}', $allprice, $item_template);		$item_template = str_replace ('{valuta}', $valuta, $item_template);		$item_template = str_replace ('{dostavka}', $config['user_params_16'], $item_template);		if ($config['user_params_31'])		{			$item_template = str_replace ('{info_dostavka}', '', $item_template);			} else {			$item_template = str_replace ('{info_dostavka}', '<span style="color:red;">'.$lang[525].'</span>', $item_template);			}		$item_template = str_replace ('{allsuma}', $allsuma, $item_template);        $infoUser = '        <table class="table table-bordered table-striped">									<tr>										<td>'.$lang[232].'<span style="color:red;"><sup>*</sup></span></td>										<td><input type="text" name="pib" class="form-control"></td>									</tr>									<tr>										<td>'.$lang[233].'<span style="color:red;"><sup>*</sup></span></td>										<td><input type="text" name="tel" value="+380" onkeypress="return isNumberOrPlusKey(event)"  maxlength="13" class="form-control"></td>									</tr>									<tr>										<td>'.$lang[234].'</td>										<td><input type="text" name="email" class="form-control"></td>									</tr>									<tr>										<td>											'.$lang[235].'<span style="color:red;"><sup>*</sup></span><br>										</td>										<td><input type="text" name="adress" class="form-control"></td>									</tr>									<tr>										<td>Вариант оплаты:<span style="color:red;"><sup>*</sup></span></td>										<td>											<select name="oplata" class="form-control">												'.$select_optionsOplata.'											</select>										</td>									</tr>									<tr>										<td>'.$lang[236].'<span style="color:red;"><sup>*</sup></span></td>										<td>											<select name="dostavka" class="form-control">												'.$select_options.'											</select>										</td>									</tr>									<tr>									    <td>Номер или адрес склада:<span style="color:red;"><sup>*</sup></span></td>										<td>											<input type="text" name="numberSkl" class="form-control">										</td>									</tr>									<tr>										<td>'.$lang[237].'</td>										<td><textarea name="dop_info" rows="4" cols="25" class="form-control"></textarea></td>									</tr>									<tr>										<td colspan="2" align="center"><input type="submit" name="submit" value="'.$lang[238].'" class="btn btn-lg btn-success"></td>									</tr>								</table>        ';        $item_template = str_replace('{infoUser}', $infoUser, $item_template);		$body .= $item_template;		$body .= '		<script>            function getNumberItem (id)            {                $.ajax({                    type: \'POST\',                    dataType: \'html\',                    data: {                        id: id,                        value: $("#" + id).val(),                    },                    url: main_site + "include/changeNumberItemInCart.php",                    success: function(result) {                        window.location.reload();                    },                });            }            $(function() {                $(\'.numberItem\').click(function() {                    var id = this.id;                    $("." + id + "_image").css(\'display\', \'inline-block\');                });                $(\'.numberItem\').bind(\'keypress\', function(e) {                        if(e.keyCode==13){                            getNumberItem(this.id);                        }                });            });        </script>		';	} else {		$body .= '		<div class="container" style="min-height: 800px; margin-top: 50px;">		    <div class="alert alert-danger">		    <strong>Помилка!</strong> Кошик порожній!		    </div>		</div>		';	}} else {	switch ($_GET['go'])	{		case 1:			$array_values_for_reference = return_all_values_for_reference($config['user_params_32']);			if (!isset ($select_options))			$select_options = '';			foreach ($array_values_for_reference as $v)			{				$info_translate_value_ref = return_all_translate_for_reference_value($v['id'], $id_online_lang);				$select_options .= '<option value="'.$v['id'].'">'.$info_translate_value_ref[0]['value']."</option>\r\n";			}            $array_values_for_reference = return_all_values_for_reference(3);            if (!isset ($select_options))                $select_options = '';            $j = 2;            foreach ($array_values_for_reference as $v)            {                $info_translate_value_ref = return_all_translate_for_reference_value($v['id'], $id_online_lang);                $select_optionsOplata .= '<option value="'.$j.'">'.$info_translate_value_ref[0]['value']."</option>\r\n";                $j--;            }			if (!isset ($body))			$body = '';			$body .= '			<script language="javascript"> 				function validateFormOnSubmit(theForm) {				var reason = "";				 				  reason += validateAddress(form.adress);				  reason += validateName(form.pib);				  reason += validatePhone(form.tel);									  if (reason != "") {					alert("'.$lang[239].'\n" + reason);					return false;				  }				 				  return true;				}				 				function validateAddress(fld) {					var error = "";				  					if (fld.value == 0) {					setStyleError(fld)						error = "'.$lang[240].'\n"					} else {					setStyleOk(fld)					}					return error;   				}				 				function validateFlat(fld)				{					var error = "";				  					if (fld.value.length == 0) {						setStyleError(fld)						error = "'.$lang[241].'\n"					} else {						setStyleOk(fld)					}					return error;   				}				 				function validateName(fld)				{					var error = "";				  					if (fld.value.length == 0) {						setStyleError(fld)						error = "'.$lang[242].'\n"					} else {						setStyleOk(fld)					}					return error;   				}				 				function validatePhone(fld)				{					var error = "";				  					if (fld.value.length != 13) {						setStyleError(fld)						error = "'.$lang[243].'\n"					} else {						setStyleOk(fld)					}					return error; 				}				 				function setStyleError(fld)				{					fld.style.cssText = "border-color: #ffa500; border-width: 1px; border-style: solid;"					return true;				}				 				function setStyleOk(fld)				{					fld.style.cssText = "border-color: #cccccc; border-width: 1px; border-style: solid;"					return true;				}				 				function isNumberOrPlusKey(evt)				{					var charCode = (evt.which) ? evt.which : event.keyCode					if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 43 && charCode != 61)						return false;					return true;				}				 				function isNumberKey(evt)				{					var charCode = (evt.which) ? evt.which : event.keyCode					if (charCode > 31 && (charCode < 48 || charCode > 57))						return false;					return true;				}				 				</script><div class="container" style="min-height: 800px; margin-top: 50px;"><div class="row">	<div class="col-lg-6 col-lg-offset-3">		<div class="car">		<div class="headerBodyRotator">				<h2>'.$lang[231].'</h2>		</div>							<form action="'.$config ['site_url'].$alt_name_online_lang.'/mode/cart.html?go=2" method="POST" onsubmit="return validateFormOnSubmit(this)"  name="form">								<table class="table table-bordered table-striped">									<tr>										<td>'.$lang[232].'<span style="color:red;"><sup>*</sup></span></td>										<td><input type="text" name="pib" class="form-control"></td>									</tr>									<tr>										<td>'.$lang[233].'<span style="color:red;"><sup>*</sup></span></td>										<td><input type="text" name="tel" value="+380" onkeypress="return isNumberOrPlusKey(event)"  maxlength="13" class="form-control"></td>									</tr>									<tr>										<td>'.$lang[234].'</td>										<td><input type="text" name="email" class="form-control"></td>									</tr>									<tr>										<td>											'.$lang[235].'<span style="color:red;"><sup>*</sup></span><br>										</td>										<td><input type="text" name="adress" class="form-control"></td>									</tr>									<tr>										<td>Вариант оплаты:<span style="color:red;"><sup>*</sup></span></td>										<td>											<select name="oplata" class="form-control">												'.$select_optionsOplata.'											</select>										</td>									</tr>									<tr>										<td>'.$lang[236].'<span style="color:red;"><sup>*</sup></span></td>										<td>											<select name="dostavka" class="form-control">												'.$select_options.'											</select>										</td>									</tr>									<tr>									    <td>Номер или адрес склада:<span style="color:red;"><sup>*</sup></span></td>										<td>											<input type="text" name="numberSkl" class="form-control">										</td>									</tr>									<tr>										<td>'.$lang[237].'</td>										<td><textarea name="dop_info" rows="4" cols="25" class="form-control"></textarea></td>									</tr>									<tr>										<td colspan="2" align="center"><input type="submit" name="submit" value="'.$lang[238].'" class="btn btn-lg btn-success"></td>									</tr>								</table>							</form>				</div></div></div></div>				';		break;		case 2:			if (isset ($_POST['pib']))			{				$uniq_id_in_base = $_COOKIE['PHPSESSID'];				$sql = "				INSERT INTO  `ls_orders` (				`uniq_user` ,				`id_user` ,				`pib` ,				`number_phone` ,				`email` ,				`adress` ,				`dostavka` ,				`dop_info` ,				`status` ,				`numberSkl` ,				`oplata` ,				`time`				)				VALUES (				'".$uniq_id_in_base."',				'".intval($_COOKIE['id_user_online'])."',				'".mysql_real_escape_string($_POST['pib'])."',				'".mysql_real_escape_string($_POST['tel'])."',				'".mysql_real_escape_string($_POST['email'])."',				'".mysql_real_escape_string($_POST['adress'])."' ,				'".mysql_real_escape_string($_POST['dostavka'])."' ,				'".mysql_real_escape_string($_POST['dop_info'])."' ,				'0' ,				'".mysql_real_escape_string($_POST['numberSkl'])."' ,				'".intval($_POST['oplata'])."' ,				'".time()."'				);				";				if (mysql_query ($sql))				{					//$body_mail = $lang[246].$config ['site_url'].'/admin/list_item.php?uniq='.$uniq_id_in_base;					$body_mail = "                    Здравствуйте.<br>                    Ваш заказ принят в обработку.<br>                    В ближайшее время с Вами свяжется наш менеджер.<br>					<a href=\"".$config ['site_url']."status.php?uniq=".$uniq_id_in_base."\" style=\"color:#FF0000;\"><b>Посмотреть статус заказа</b><br>					<b>Спасибо за заказ</b>					";					if ($config['user_params_5'])					{						send_sms (0);					}					//mail ($_POST['email'], $lang[245], $body_mail);					send_message_for_email ($_POST['email'], $lang[245], $body_mail, 'new_order', '');					//send_message_for_email ('intarsio@ya.ru', 'Замовлення на сайті', 'Нове замовлення на сайті. Для перегляду: http://intarsio.com.ua/admin/', 'new_order', '');					//mail ('intarsio@ya.ru', 'Замовлення на сайті', 'Нове замовлення на сайті. Для перегляду: http://intarsio.com.ua/admin/');					$sql = "SELECT `id` FROM `ls_cart` where `uniq_user` = '".$uniq_id_in_base."';";					$results = mysql_query($sql);					$number = mysql_num_rows ($results);					for ($i=0; $i<$number; $i++)					{							$array_item_in_cart[$i] = mysql_fetch_array($results, MYSQL_ASSOC);						}					foreach ($array_item_in_cart as $v)					{						$sql = "update `ls_cart` set status = 1 where `id` = '".$v['id']."';";						mysql_query ($sql);					}					$body .= '					<div class="container" style="margin-top: 50px; min-height: 800px;">					       <div class="alert alert-success">					        <strong>Спасибо!</strong> Мы успешно приняли Ваш заказ - наш менеджер свяжется с Вами для уточнения деталей!					       </div>					</div>    				';				} else {					$body .= '					<div class="container" style="margin-top: 50px; min-height: 800px;">					       <div class="alert alert-danger">					        <strong>Ошибка!</strong> Возникла ошибка, перезвоните нам, и мы с удовольствием приймем заказ в ручном режиме!					       </div>					</div>					';				}				session_regenerate_id();			}		break;	}}$name_left_block = $lang[218];require ('include/popular_items.php');?>