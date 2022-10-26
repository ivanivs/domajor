<?php
function return_mail_out_by_type($type)
{
    if (strlen($type)>0)
    {
        $sql = "SELECT * FROM `ls_mail_out` where type_mail='".$type."' order by `id` desc;";
    } else {
        $sql = "SELECT * FROM `ls_mail_out` order by `id` DESC;";
    }
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