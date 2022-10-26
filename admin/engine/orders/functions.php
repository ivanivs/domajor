<?php
function return_all_orders($type = '', $time_start = '', $time_end = '', $start = 0)
{
    global $config;
	if ($type === '')
	{
		$time = time()-2592000;
		$sql = "SELECT * FROM ls_orders where time > '".$time."' and `delete` = 0 order by id DESC LIMIT ".$start.", ".$config['user_params_35'].";";
		$results = mysql_query($sql);
	} else {
		switch ($type)
		{
			case 0:
				$results = mysql_query("SELECT * FROM ls_orders where `delete` = 0 order by id DESC LIMIT ".$start.", ".$config['user_params_35'].";");
			break;
			case 1:
				$sql = "SELECT * FROM ls_orders where time > '".$time_start."' and time < '".$time_end."' and `delete` = 0 order by id DESC;";
				$results = mysql_query($sql);
			break;
			case 2:
				$sql = "SELECT * FROM  `ls_orders` WHERE  `delete` = 1 order by id DESC;";
				$results = mysql_query($sql);
			break;
		}
	}
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
function return_all_orders_count($type = '', $time_start = '', $time_end = '')
{
    global $config;
    if ($type === '')
    {
        $time = time()-2592000;
        $sql = "SELECT COUNT(*) FROM ls_orders where time > '".$time."' and `delete` = 0;";
        $results = mysql_query($sql);
    } else {
        switch ($type)
        {
            case 0:
                $sql = "SELECT COUNT(*) FROM ls_orders where `delete` = 0;";
                break;
            case 1:
                $sql = "SELECT COUNT(*) FROM ls_orders where time > '".$time_start."' and time < '".$time_end."' and `delete` = 0;";
                break;
            case 2:
                $sql = "SELECT COUNT(*) FROM  `ls_orders` WHERE  `delete` = 1 order by id DESC;";
                break;
        }
    }
    return (mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC));
}
?>