<?php
function return_all_reference () //Возвращаем все все созданные справочники
{
	$results = mysql_query("SELECT * FROM ls_reference;");
	$number = mysql_num_rows ($results);
	for ($i=0; $i<$number; $i++)
	{	
		$array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
	}	
	return ($array);
}
function return_one_reference_for_id ($id) //Возвращаем все все созданные справочники
{
	$results = mysql_query("SELECT * FROM ls_reference where id='".$id."';");
	return (mysql_fetch_array($results, MYSQL_ASSOC));
}
function return_all_values_for_reference ($id_reference) //Возвращаем все значения по данному справочнику
{
	$results = mysql_query("SELECT * FROM ls_reference_values where id_reference='".$id_reference."';");
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
function return_all_translate_for_reference_value ($id_ref_value, $id_lang)
{
	$results = mysql_query("SELECT * FROM ls_reference_values_translate where id_reference_value='".$id_ref_value."' and id_lang='".$id_lang."';");
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
?>