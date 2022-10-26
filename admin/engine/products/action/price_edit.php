<?php
if (!isset ($other_way))
{
    $other_way = '';
}
$other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[521];
$body_admin .= '
<center><h2 id="title">'.$lang[521].'</h2></center>
';
$info_item = mysql_fetch_array(mysql_query("SELECT * FROM `ls_items` where `id` = '".$_GET['id']."';"), MYSQL_ASSOC);
$id_item = $_GET['id'];
if (check_many_price_for_card($info_item['id_card']))
{
    if (isset ($_POST['submit_2']))
    {
        $reference_value = $_POST['reference_value'];
        $price = $_POST['price'];
        $param = $_POST['param'];
        $param_to_add = $_POST['param_to_add'];
        //print_r ($reference_value);
        //print_r ($price);
        //print_r ($param);
        foreach ($price as $key => $v)
        {
            if (strlen ($param[$key])>0)
            {
                $sql = "
                UPDATE `ls_values_ligament_price` set `price` = '".$v."', `id_reference_value` = '".$reference_value[$key]."'  where `id` = '".$param[$key]."';
                ";
                mysql_query($sql);
            } else {
                $sql = "
		INSERT INTO  `ls_values_ligament_price` (
		`id_item` ,
		`price` ,
		`id_reference_value`
		)
		VALUES (
		'".$_GET['id']."' ,
		'".$v."' ,
		'".$reference_value[$key]."'
		);
		";
                if (mysql_query($sql))
                {
                    $id_values_ligament_price = mysql_insert_id();
                    $array_param = explode ('|', $param_to_add[$key]);
                    foreach ($array_param as $one_param)
                    {
                            $sql = "INSERT INTO  `ls_values_ligament_price_param` (
                            `id_ligament` ,
                            `id_item` ,
                            `id_param_value`
                            )
                            VALUES (
                            '".$id_values_ligament_price."' ,
                            '".$_GET['id']."' ,
                            '".$one_param."'
                            );";
                            mysql_query($sql);
                    }
                }
            }
        }
    }
    $array_ligament_for_card = return_all_ligament_of_id_card($info_item['id_card']);
    //print_r ($array_ligament_for_card);
    if ($array_ligament_for_card)
    {
            foreach ($array_ligament_for_card as $key => $v)
            {
                    $info_card_param = mysql_fetch_array(mysql_query("SELECT * FROM `ls_cardparam` where `id_card` = '".$info_item['id_card']."' and `db_type` = 'select' and `id_param` = '".$v['id_param']."';"), MYSQL_ASSOC);
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
	    //print_r ($array_ligament_param);
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
                            if (@$v[$key_of_param-1]['id_cardparam']!=$v[$key_of_param]['id_cardparam'])
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
	    //print $body_generate = generate_price_edit_form($array_ligament_param, '', 0);
	    //$body_admin .= $body_generate;
	    for ($i=0; $i<count($array_ligament_param); $i++)
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
			    if (isset ($array_ligament_param[$i+1]))
			    {
				foreach ($array_ligament_param[$i+1] as $key => $value_2)
				{
				    $info_param = mysql_fetch_array(mysql_query("SELECT * FROM `ls_params_select_values` where `id` = '".$value_2['value']."';"), MYSQL_ASSOC);
				    if (strlen($info_param['img'])>0)
				    {
					    $two['text'] = '<img src="'.$config ['site_url'].'upload/select_params/'.$info_param['img'].'" border="0">';
				    } else {
					    $sql = "SELECT `text` FROM `ls_translate` where `type` = 'select_value' and `id_elements` = '".$value_2['value']."' and `id_lang` = '".$id_online_lang."';";
					    $two = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
				    }
				    if (isset ($array_ligament_param[$i+2]))
				    {
					foreach ($array_ligament_param[$i+2] as $key => $value_3)
					{
					    $info_param = mysql_fetch_array(mysql_query("SELECT * FROM `ls_params_select_values` where `id` = '".$value_3['value']."';"), MYSQL_ASSOC);
					    if (strlen($info_param['img'])>0)
					    {
						    $tree['text'] = '<img src="'.$config ['site_url'].'upload/select_params/'.$info_param['img'].'" border="0">';
					    } else {
						    $sql = "SELECT `text` FROM `ls_translate` where `type` = 'select_value' and `id_elements` = '".$value_3['value']."' and `id_lang` = '".$id_online_lang."';";
						    $tree = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
					    }
					    $info_price_cardparam = mysql_fetch_array(mysql_query("SELECT * FROM `ls_cardparam` where `db_type` = 'price' and `id_card` = '".$info_item['id_card']."';"), MYSQL_ASSOC);
					    $info_price = mysql_fetch_array(mysql_query("SELECT * FROM `ls_params_price` where `id` = '".$info_price_cardparam['id_param']."';"), MYSQL_ASSOC);
					    $array_reference_value = return_reference_values_for_ref_id ($info_price['reference_id']);
					    $array_param[] = $value['value'];
					    $array_param[] = $value_2['value'];
					    $array_param[] = $value_3['value'];
					    $price_array = return_price_for_param($id_item, $array_param);
					    if (count ($array_reference_value))
					    {
						foreach ($array_reference_value as $value_ref)
						{
						    $info_ref_value_translate = return_translate_for_ref_value ($id_online_lang, $value_ref['id']);
						    if (!isset ($option_ref_value))
						    {
							$option_ref_value = '';
						    }
						    if ($value_ref['id']==$price_array[1])
						    {
							$option_ref_value .= '<option value="'.$value_ref['id'].'" selected>'.$info_ref_value_translate['value'].'</option>';
						    } else {
							$option_ref_value .= '<option value="'.$value_ref['id'].'">'.$info_ref_value_translate['value'].'</option>';
						    }
						}
					    }
					    $html_price_param = '<select name="reference_value[]" class="form_text">'.$option_ref_value.'</select>';
					    unset ($option_ref_value);
					    //print_r ($price_array);
					    $body_admin .= $one.'<td>'.$two['text'].'</td><td>'.$tree['text'].'</td>
						    <td>
							    <input type="text" name="price[]" class="form_text" value="'.$price_array[0].'"> '.$html_price_param.'
							    <input type="hidden" name="param[]" value="'.$price_array[2].'">
							    <input type="hidden" name="param_to_add[]" value="'.$value['value'].'|'.$value_2['value'].'|'.$value_3['value'].'">
						    </td>
						    </tr>
					    ';
					    unset ($array_param);
					}
				    }
				}
			    }
                    }
            }
            $body_admin .= '
            </table>
            <input type="hidden" name="id_item" value="'.$id_item.'">
            <input type="submit" name="submit_2" value="'.$lang[515].'" class="form_text">
            </form>';
    }
} else {
    $body_admin .= '<span style="color:red;">Быстрое редактирование цены работает только для мульти цен.</span>';
}
?>