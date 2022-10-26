<?php
function return_all_text_params()
{
	$results = mysql_query("SELECT * FROM ls_params_text;");
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
function return_all_boolean_params()
{
	$results = mysql_query("SELECT * FROM ls_params_boolean;");
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
function return_one_image_param ($id)
{
	$results = mysql_query("SELECT * FROM ls_params_image where id='$id';");
	return(mysql_fetch_array($results, MYSQL_ASSOC));
}
function return_all_image_params()
{
	$results = mysql_query("SELECT * FROM ls_params_image;");
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
function return_all_price_params()
{
	$results = mysql_query("SELECT * FROM ls_params_price;");
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
function return_all_select_params()
{
	$results = mysql_query("SELECT * FROM ls_params_select order by `position`;");
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
function return_all_text_values_params ($id)
{
	$sql = "SELECT * FROM ls_params_select_values where id_params='".$id."' order by `position` DESC;";
	$results = mysql_query ($sql);
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
function return_all_text_values_params_with_parent ($id, $parent_value)
{
	$sql = "SELECT * FROM ls_params_select_values where id_params='".$id."' and `parent_param_id` = ".$parent_value." order by `position`;";
	$results = mysql_query ($sql);
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
function return_one_values_params ($id)
{
	$sql = "SELECT * FROM ls_params_select_values where id='".$id."';";
	$results = mysql_query ($sql);
	$number = @mysql_num_rows ($results);
	if ($number>0)
	{
		return (mysql_fetch_array($results, MYSQL_ASSOC));
	} else {
		return(0);
	}
}
function return_all_select_values_params($id_params)
{
	$results = mysql_query("
	SELECT * 
FROM ls_params_select_values
WHERE id_params =  '".$id_params."'
ORDER BY  `ls_params_select_values`.`position` DESC;");
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
function return_all_select_value_from_param_id($id)
{
	$results = mysql_query("SELECT * FROM ls_params_select_values where id_params='$id';");
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
function return_all_select_values_params_by_parent_id($id_params)
{
	$results = mysql_query("
	SELECT * 
FROM ls_params_select_values
WHERE parent_param_id =  '".$id_params."'
ORDER BY  `ls_params_select_values`.`position` DESC;");
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
function return_one_price_param_for_id ($id)
{
	$sql = "SELECT * FROM ls_params_price where id='".$id."';";
	$results = mysql_query($sql);
	return (mysql_fetch_array($results, MYSQL_ASSOC));
}
function return_name_for_id_text_param ($my_lang_id, $id)
{
    return (getOneString("SELECT * FROM ls_translate where id_elements='".$id."' and type='text' and id_lang='".$my_lang_id."';"));
}
function return_name_for_id_boolean_param ($my_lang_id, $id)
{
	$sql = "SELECT * FROM ls_translate where id_elements='".$id."' and type='boolean' and id_lang='".$my_lang_id."';";
	$results = mysql_query($sql);
	return (mysql_fetch_array($results, MYSQL_ASSOC));
}
function return_name_for_id_image_param ($my_lang_id, $id)
{
	$sql = "SELECT * FROM ls_translate where id_elements='".$id."' and type='image' and id_lang='".$my_lang_id."';";
	$results = mysql_query($sql);
	return (mysql_fetch_array($results, MYSQL_ASSOC));
}
function return_name_for_id_price_param ($my_lang_id, $id)
{
	$sql = "SELECT * FROM ls_translate where id_elements='".$id."' and type='price' and id_lang='".$my_lang_id."';";
	$results = mysql_query($sql);
	return (mysql_fetch_array($results, MYSQL_ASSOC));
}
function return_name_for_id_select_param ($my_lang_id, $id)
{
	$sql = "SELECT * FROM ls_translate where id_elements='".$id."' and type='select' and id_lang='".$my_lang_id."';";
	$results = mysql_query($sql);
	return (mysql_fetch_array($results, MYSQL_ASSOC));
}
function return_name_for_id_select_param_value ($my_lang_id, $id)
{
	$sql = "SELECT * FROM ls_translate where id_elements='".$id."' and type='select_value' and id_lang='".$my_lang_id."';";
	$results = mysql_query($sql);
	return (mysql_fetch_array($results, MYSQL_ASSOC));
}
function return_name_for_id_select_param_value_no_lang ($id)
{
	$sql = "SELECT * FROM ls_translate where id_elements='".$id."' and type='select_value';";
	$results = mysql_query($sql);
	return (mysql_fetch_array($results, MYSQL_ASSOC));
}
function return_one_text_params ($id)
{
	$results = mysql_query("SELECT * FROM ls_params_text where id='$id';");
	return (mysql_fetch_array($results, MYSQL_ASSOC));
}
function return_one_select_params ($id)
{
	$results = mysql_query("SELECT * FROM ls_params_select where id='$id';");
	return (mysql_fetch_array($results, MYSQL_ASSOC));
}
function return_translate_for_id_elements_values_select ($id, $id_lang)
{
	$results = mysql_query("SELECT * FROM ls_translate where id_lang='".$id_lang."' and id_elements='".$id."' and type='select_value';");
	return (mysql_fetch_array($results, MYSQL_ASSOC));
}
function chek_values_select_text ($id, $type)
{
	$results = mysql_query("SELECT * FROM ls_params_select_values where id_params='$id' and type_value=".$type.";");
	return (@mysql_num_rows ($results));
}

?>