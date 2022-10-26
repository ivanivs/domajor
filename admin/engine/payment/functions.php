<?php
function return_all_offline_method()
{
    $sql = "SELECT * FROM ls_payment;";
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
?>