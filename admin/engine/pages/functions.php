<?php
function return_all_static_page () //Возвращаем все статичные страницы
{
	$results = mysql_query("SELECT * FROM ls_static_pages order by `id` DESC;");
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