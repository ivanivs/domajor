<?php
function return_all_filtr()
{
    $sql = "select * from ls_filtr order by id DESC";
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
function return_param_filtr_order_by_id_param($id_filtr)
{
    $sql = "select * from ls_filtr_param where id_filtr = '".$id_filtr."' order by id_param DESC";
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
function check_filtr_param($id_filtr, $id_param, $id_value_param)
{
    $sql = "select * from ls_filtr_param where id_filtr = '".$id_filtr."' and id_param = '".$id_param."' and id_value_param = '".$id_value_param."' limit 0,1";
    return (@mysql_num_rows (mysql_query($sql)));
}
function return_param_for_filtr ($id)
{
    $sql = "SELECT * FROM ls_filtr_param where id_filtr='".$id."' order by id_param DESC;";
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