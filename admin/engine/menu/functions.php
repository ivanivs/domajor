<?php
function return_all_menu()
{
    $sql = "SELECT * FROM `ls_menu` order by `id` DESC;";
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
function return_one_menu($id)
{
    return(mysql_fetch_array(mysql_query("SELECT * FROM `ls_menu` where `id` = '".$id."';"), MYSQL_ASSOC));
}
?>