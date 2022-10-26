<?php
function return_all_css_style ()
{
    $results = mysql_query("SELECT * FROM ls_css;");
    $number = mysql_num_rows ($results);
    for ($i=0; $i<$number; $i++)
    {	
            $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
    }	
    return ($array);
}
?>