<?php
function return_all_template()
{
    $sql = "SELECT * FROM ls_template order by id DESC;";
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
function return_one_template($id)
{
    $sql = "SELECT * FROM ls_template where id='".$id."' order by id DESC;";
    $results = mysql_query ($sql);
    return(mysql_fetch_array($results, MYSQL_ASSOC));
}
?>