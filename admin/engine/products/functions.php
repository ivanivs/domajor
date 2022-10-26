<?php
function return_all_card ()
{
    $sql = "SELECT * FROM ls_card;";
    $results = mysql_query($sql);
    $number = @mysql_num_rows ($results);
    if ($number>0)
    {
            for ($i=0; $i<$number; $i++)
            {	
                    $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
            }	
            return ($array);
    } else {
            return(0);
    }
}
function check_image_param ($id_card)
{
	$sql = "SELECT * FROM ls_cardparam where id_card='".$id_card."' and db_type='image';";
	$results = mysql_query($sql);
	return(@mysql_num_rows ($results));
}
function return_cardparam_text_one_position ($id)
{
    $sql = "SELECT * FROM ls_cardparam where db_type='text' and id_card='$id' order by position DESC;";
    return (mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC));
} 
function return_name_item ($id, $id_cardparam)
{
    $sql = "SELECT * FROM ls_values_text where id_item='$id' and id_cardparam='$id_cardparam';";
    return (mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC));
}
function return_one_item ($id)
{
    $sql = "SELECT * FROM ls_items where id='".$id."';";
    return (mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC));
}
function return_items_price_param($id_cardparam, $id_item)
{
    $sql = "SELECT * FROM ls_values_prices where id_cardparam='".$id_cardparam."' and id_item='".$id_item."';";
	return (mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC));
}
function return_items_price_param_number($id_cardparam, $id_item)
{
    $sql = "SELECT * FROM ls_values_prices where id_cardparam='".$id_cardparam."' and id_item='".$id_item."';";
	$number = mysql_num_rows (mysql_query($sql));
	return ($number);
}
function return_items_text_param($id_cardparam, $id_item, $id_lang)
{
    $sql = "SELECT * FROM ls_values_text where id_cardparam='".$id_cardparam."' and id_item='".$id_item."' and id_lang='".$id_lang."';";
	$number = mysql_num_rows (mysql_query($sql));
	if ($number==0)
	{
		$sql = "SELECT * FROM ls_values_text where id_cardparam='".$id_cardparam."' and id_item='".$id_item."';";
	}
	return (mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC));
}
function return_items_text_param_number($id_cardparam, $id_item, $id_lang)
{
    $sql = "SELECT * FROM ls_values_text where id_cardparam='".$id_cardparam."' and id_item='".$id_item."' and id_lang='".$id_lang."';";
	$number = mysql_num_rows (mysql_query($sql));
	return ($number);
}
function return_items_boolean_param($id_cardparam, $id_item)
{
    $sql = "SELECT * FROM ls_values_boolean where id_cardparam='".$id_cardparam."' and id_item='".$id_item."';";
	$number = mysql_num_rows (mysql_query($sql));
	return ($number);
}
function return_items_boolean_param_value($id_cardparam, $id_item)
{
    $sql = "SELECT * FROM ls_values_boolean where id_cardparam='".$id_cardparam."' and id_item='".$id_item."';";
	return (mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC));
}
function return_items_select_param_value($id_cardparam, $id_item, $value)
{
    $sql = "SELECT * FROM ls_values_select where id_cardparam='".$id_cardparam."' and id_item='".$id_item."' and value='".$value."';";
    $info_count = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM ls_values_select where id_cardparam='".$id_cardparam."' and id_item='".$id_item."' and value='".$value."';"), MYSQL_ASSOC);
    if ($info_count['COUNT(*)']>0)
    {
	return (mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC));
    } else {
	$sql = "SELECT * FROM ls_values_select where id_item='".$id_item."' and value='".$value."';";
	return (mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC));
    }
}
function return_items_select_param($id_cardparam, $id_item, $value)
{
    $sql = "SELECT * FROM ls_values_select where id_cardparam='".$id_cardparam."' and id_item='".$id_item."' and value='".$value."';";
    $number = mysql_num_rows (mysql_query($sql));
    if ($number)
    {
	return ($number);
    } else {
	$sql = "SELECT * FROM ls_values_select where id_item='".$id_item."' and value='".$value."';";
	$number = mysql_num_rows (mysql_query($sql));
	return ($number);
    }
}
function return_items_by_card ($id)
{
    $sql = "SELECT * FROM ls_items where id_card='".$id."';";
    $results = mysql_query($sql);
    $number = @mysql_num_rows ($results);
    if ($number>0)
    {
            for ($i=0; $i<$number; $i++)
            {	
                    $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
            }	
            return ($array);
    } else {
            return(0);
    }
}
function return_items_by_card_and_limit ($id, $limit, $searchField = '')
{
    if (!empty($searchField))
        $searchSql = " AND (`searchField` LIKE '%".mysql_real_escape_string($searchField)."%' OR `text_4` LIKE '%".mysql_real_escape_string($searchField)."%' OR `text_1` LIKE '%".mysql_real_escape_string($searchField)."%' OR `text_9` LIKE '%".mysql_real_escape_string($searchField)."%' OR `text_11` LIKE '%".mysql_real_escape_string($searchField)."%')";
    $sql = "SELECT * FROM ls_items where id_card='".$id."' ".$searchSql." order by id DESC LIMIT ".$limit.", 50;";
    $results = mysql_query($sql);
    $number = @mysql_num_rows ($results);
    if ($number>0)
    {
            for ($i=0; $i<$number; $i++)
            {	
                    $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
            }	
            return ($array);
    } else {
            return(0);
    }
}
function return_one_card ($id)
{
    $sql = "SELECT * FROM ls_card where id='".$id."';";
    return (mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC));
}
function return_params_one_for_card ($id_card, $type_param, $id_param)
{
    $sql = "SELECT * FROM ls_cardparam where id_card='".$id_card."' and db_type='".$type_param."' and id_param='".$id_param."';";
    return (mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC));
}
function return_parafm_for_card ($id_card)
{
    $sql = "SELECT * FROM ls_cardparam where id_card='".$id_card."' order by position DESC;";
    $results = mysql_query($sql);
    $number = @mysql_num_rows ($results);
    if ($number>0)
    {
            for ($i=0; $i<$number; $i++)
            {	
                    $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
            }	
            return ($array);
    } else {
            return(0);
    }
}
function return_one_cardparam ($id)
{
	$results = mysql_query("SELECT * FROM ls_cardparam where id='$id';");
	return(mysql_fetch_array($results, MYSQL_ASSOC));
}
function return_all_image_for_id_item ($id_item, $id_cardparam)
{
    $sql = "SELECT * FROM ls_values_image where id_cardparam='".$id_cardparam."' and id_item='".$id_item."' order by position DESC;";
    $results = mysql_query($sql);
    $number = @mysql_num_rows ($results);
    if ($number>0)
    {
            for ($i=0; $i<$number; $i++)
            {	
                    $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
            }	
            return ($array);
    } else {
            return(0);
    }
}
function return_param_for_card_image ($id_card)
{
    $sql = "SELECT * FROM ls_cardparam where id_card='".$id_card."' and db_type='image' order by position DESC;";
    $results = mysql_query($sql);
    $number = @mysql_num_rows ($results);
    if ($number>0)
    {
            for ($i=0; $i<$number; $i++)
            {	
                    $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
            }	
            return ($array);
    } else {
            return(0);
    }
}
function return_image_for_card_one_for_id ($id)
{
    $sql = "SELECT * FROM ls_values_image where id='".$id."';";
    return (mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC));
}
function return_param_for_card_one_for_id ($id)
{
    $sql = "SELECT * FROM ls_cardparam where id='".$id."';";
    return (mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC));
}
function return_image_for_card_one_for_position ($position, $id_cardparam)
{
    $sql = "SELECT * FROM ls_values_image where position='".$position."' and id_cardparam='".$id_cardparam."';";
    return (mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC));
}
function return_param_for_card_one_for_position ($position, $id_card)
{
    $sql = "SELECT * FROM ls_cardparam where position='".$position."' and id_card='".$id_card."';";
    return (mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC));
}
function return_reference_values_for_ref_id ($id)
{
    $results = mysql_query("SELECT * FROM ls_reference_values where id_reference='".$id."';");
    $number = @mysql_num_rows ($results);
    if ($number>0)
    {
            for ($i=0; $i<$number; $i++)
            {	
                    $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
            }	
            return ($array);
    } else {
            return(0);
    }
}
function return_translate_for_ref_value ($id_lang, $id_ref_value)
{
    $sql = "SELECT * FROM ls_reference_values_translate where id_reference_value='".$id_ref_value."' and id_lang='".$id_lang."';";
    return (mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC));
}
function return_items_select_param_value_sait($id_cardparam, $id_item)
{
    $sql = "SELECT * FROM `ls_values_select` where id_cardparam='".$id_cardparam."' and id_item='".$id_item."';";
	$results = mysql_query($sql);
	$number = @mysql_num_rows ($results);
    if ($number>0)
    {
            for ($i=0; $i<$number; $i++)
            {	
                    $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
            }	
            return ($array);
    } else {
            return(0);
    }
}
function return_item_info ($id_item, $id_lang)
{
	$info_item = return_one_item ($id_item);
	$array_params_for_card = return_parafm_for_card ($info_item['id_card']);
	//print_r ($array_params_for_card);
	if ($array_params_for_card)
	{
	foreach ($array_params_for_card as $key => $v)
	{
		$id_cardparam = $v['id'];
		$id_param = $v['id_param'];
		$position = $v['position'];
		switch ($v['db_type'])
		{
			case "text":
				$values_text = return_items_text_param($v['id'], $id_item, $id_lang);
				$array_data['text'.$id_param]['id_cardparam'] = $v['id'];
				$array_data['text'.$id_param]['text'] = $values_text['text'];
				$array_data['text'.$id_param]['type'] = 'text';
			break;
			case "select":
				$image_params = chek_values_select_text ($v['id_param'], '0');
				$text_params = chek_values_select_text ($v['id_param'], '1');
				$info_params = return_one_select_params($v['id_param']);
				$info_in_base = return_items_select_param_value_sait($v['id'], $id_item);
				//print $v['id_param']."<br>";
				if ($info_in_base)
				{
					if ($text_params)
					{
						foreach ($info_in_base as $key_in_base => $values_in_base)
						{
							//$sql = "SELECT * FROM `ls_params_select_values` where id='".$values_in_base['value']."';";
							//$results = mysql_query($sql);
							//$info_select_param_value = mysql_fetch_array($results, MYSQL_ASSOC);
							$sql = "SELECT * FROM `ls_translate` where id_lang='".$id_lang."' and type='select_value' and id_elements='".$values_in_base['value']."';";
							$results = mysql_query($sql);
							$number = @mysql_num_rows ($results);
							if (!$number)
							{
								$sql = "SELECT * FROM `ls_translate` where type='select_value' and id_elements='".$values_in_base['value']."';";
								$results = mysql_query($sql);
							}
							$translate_info = mysql_fetch_array($results, MYSQL_ASSOC);
							$sql = "SELECT * FROM `ls_params_select_values` where id='".$values_in_base['value']."';";
							$results = mysql_query($sql);
							$info_select_param_value = mysql_fetch_array($results, MYSQL_ASSOC);
							$array_data['select'.$id_param][$key_in_base]['id_cardparam'] = $v['id'];
							$array_data['select'.$id_param][$key_in_base]['text'] = $translate_info['text'];
							$array_data['select'.$id_param][$key_in_base]['type'] = 'select';
							$array_data['select'.$id_param][$key_in_base]['position'] = $info_select_param_value['position'];
							$array_data['select'.$id_param][$key_in_base]['id_value'] = $info_select_param_value['id'];
						}
					}
				}
				if ($info_in_base)
				{
					if ($image_params)
					{
						foreach ($info_in_base as $key_in_base => $values_in_base)
						{
							$sql = "SELECT * FROM `ls_params_select_values` where id='".$values_in_base['value']."';";
							$results = mysql_query($sql);
							$info_select_param_value = mysql_fetch_array($results, MYSQL_ASSOC);
							$array_data['select'.$id_param][$key_in_base]['id_cardparam'] = $v['id'];
							$array_data['select'.$id_param][$key_in_base]['text'] = $info_select_param_value['img'];
							$array_data['select'.$id_param][$key_in_base]['type'] = 'select';
						}
					}
				}
			break;
			case "price":
				$info_price_in_base = return_items_price_param($v['id'], $id_item);
				$info_ref_value_translate = return_translate_for_ref_value ($id_lang, $info_price_in_base['convert_price']);
				$array_data['price'.$id_param]['id_cardparam'] = $v['id'];
				$array_data['price'.$id_param]['text'] = $info_price_in_base['value'].'|'.$info_ref_value_translate['value'];
				$array_data['price'.$id_param]['type'] = 'price';
			break;
			case "boolean":
				$info_translate_boolean = return_name_for_id_boolean_param ($id_online_lang, $v['id_param']);
				//print_r ($info_translate_boolean);
				$info_boolean = return_items_boolean_param($v['id'], $id_item);
				if ($info_boolean)
				{
					$array_data['boolean'.$id_param]['id_cardparam'] = $v['id'];
					$array_data['boolean'.$id_param]['text'] = 1;
					$array_data['boolean'.$id_param]['type'] = 'boolean';
				} else {
					$array_data['boolean'.$id_param]['id_cardparam'] = $v['id'];
					$array_data['boolean'.$id_param]['text'] = 0;
					$array_data['boolean'.$id_param]['type'] = 'boolean';
				}
			break;
			case "image":
				$array_image = return_all_image_for_id_item ($id_item, $v['id']);
				//print_r ($array_image);
				if ($array_image)
				{
					foreach ($array_image as $key_image => $one_image)
					{
						$array_data['image'.$id_param][$key_image]['id_cardparam'] = $v['id'];
						$array_data['image'.$id_param][$key_image]['text'] = $one_image['value'];
						$array_data['image'.$id_param][$key_image]['type'] = 'image';
					}
				}
			break;
		}
	}
	//print_r ($array_data);
	return ($array_data);
	}
}
function getmicrotime() 
{ 
    list($usec, $sec) = explode(" ", microtime()); 
    return ((float)$usec + (float)$sec); 
} 
function return_values_select_params($id_params)
{
    $sql = "SELECT * FROM `ls_params_select_values` where id_params='".$id_params."' order by `position` DESC;";
    $results = mysql_query($sql);
    $number = @mysql_num_rows ($results);
    if ($number>0)
    {
            for ($i=0; $i<$number; $i++)
            {	
                    $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
            }	
            return ($array);
    } else {
            return(0);
    }
}
function return_translate_by_id ($id)
{
	 $sql = "SELECT * FROM `ls_translate` where id='".$id."';";
	 $results = mysql_query($sql);
	 return (mysql_fetch_array($results, MYSQL_ASSOC));
}
function return_id_param_from_cardparam_where_id_card_only_select($id)
{
    $sql = "SELECT * FROM `ls_cardparam` where id_card='".$id."' and `db_type` = 'select';";
    $results = mysql_query($sql);
    $number = @mysql_num_rows ($results);
    if ($number>0)
    {
	for ($i=0; $i<$number; $i++)
	{	
		$array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
	}
	foreach ($array as $key => $v)
	{
	    $info_param = mysql_num_rows(mysql_query("SELECT `id` from `ls_params_select` where `id` = '".$v['id_param']."' and `multiselect` = '1';"));
	    if ($info_param)
	    {
		$n_array[] = $array[$key];
	    }
	}
        return($n_array);
    } else {
        return(0);
    }
}
function check_many_price_for_card($id)
{
    return(mysql_num_rows(mysql_query("SELECT `id` FROM `ls_card_param_ligament` where `id_card` = '".$id."';")));
}
function return_all_ligament_of_id_card($id_card)
{
    $sql = "SELECT * FROM `ls_card_param_ligament` where `id_card`='".$id_card."';";
    $results = mysql_query($sql);
    $number = @mysql_num_rows ($results);
    if ($number>0)
    {
            for ($i=0; $i<$number; $i++)
            {	
                    $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
            }	
            return ($array);
    } else {
            return(0);
    }  
}
function return_select_values_from_cardparam_and_item($id_card_param, $id_item)
{
    $sql = "SELECT * FROM `ls_values_select` where `id_item`='".$id_item."' and `id_cardparam` = '".$id_card_param."';";
    $results = mysql_query($sql);
    $number = @mysql_num_rows ($results);
    if ($number>0)
    {
            for ($i=0; $i<$number; $i++)
            {	
                    $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
            }	
            return ($array);
    } else {
            return(0);
    }  
}
function generate_price_edit_form($array, $body = '', $i)
{
    global $id_online_lang, $config;
    //for ($i=$j; $i<count($array); $i++)
    //{
	if (isset ($array[$i]))
	{
	    foreach ($array[$i] as $key => $value)
	    {
		print_r ($array[$i]);
		if ($i==0)
		{
		    $body .= '<tr>'."\n";
		}
		$info_param = mysql_fetch_array(mysql_query("SELECT * FROM `ls_params_select_values` where `id` = '".$value['value']."';"), MYSQL_ASSOC);
		if (strlen($info_param['img'])>0)
		{
		    $translate_param['text'] = '<img src="'.$config ['site_url'].'upload/select_params/'.$info_param['img'].'" border="0">';
		} else {
		    $sql = "SELECT `text` FROM `ls_translate` where `type` = 'select_value' and `id_elements` = '".$value['value']."' and `id_lang` = '".$id_online_lang."';";
		    $translate_param = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
		}
		$body .= '<td>'.$translate_param['text'].$i.'</td>'."\n";
		if (count($array)==$i+1)
		{
		    //print count($array)." - ".($i+1)."<br>";
		    $body .= '</tr>'."\n";
		}
		$i++;
		if (isset ($array[$i]))
		{
		    $body .= generate_price_edit_form($array, $body, $i);
		}
		return($body);
	    } 
	}
    //}
}
//function return_all_values_select_
?>