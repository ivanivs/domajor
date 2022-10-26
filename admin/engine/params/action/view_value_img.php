<?php
if (isset ($_GET['up']))
{
	$sql = "update `ls_params_select_values` set `position` = `position`-1 where `position` = '".($_GET['postion']+1)."';";
	mysql_query($sql);
	$sql = "update `ls_params_select_values` set `position` = '".($_GET['postion']+1)."' where `id` = '".$_GET['up']."';";
	mysql_query($sql);
}
if (isset ($_GET['down']))
{
	$sql = "update `ls_params_select_values` set `position` = `position`+1 where `position` = '".($_GET['postion']-1)."';";
	mysql_query($sql);
	$sql = "update `ls_params_select_values` set `position` = '".($_GET['postion']-1)."' where `id` = '".$_GET['down']."';";
	mysql_query($sql);
}
$array_value = return_all_select_values_params($_GET['id']);
if (count ($array_value))
{
    $body_admin .= '<center>
	<table border="0">
	';
	$position = 1;
    foreach ($array_value as $k => $v)
    {
		if ($k!=0)
		{
			$up = '<a href="index.php?do=params&action=view_value&type=select&type_2=value&id='.$_GET['id'].'&up='.$v['id'].'&postion='.$v['position'].'"><img src="'.$config ['site_url'].'images/admin/1uparrow.png" border="0"></a>';
		}
		if ($k!=count($array_value)-1)
		{
			$down = '<a href="index.php?do=params&action=view_value&type=select&type_2=value&id='.$_GET['id'].'&down='.$v['id'].'&postion='.$v['position'].'"><img src="'.$config ['site_url'].'images/admin/1downarrow.png" border="0"></a>';
		}
		if (!isset ($down))
		$down = '';
        $body_admin .= '
		<tr>
			<td><img src="../upload/select_params/'.$v['img'].'"></td>
			<td> '.$up.$down.'</td>
		</tr>';
		unset ($up, $down);
		if ($v['position']==0)
		{
			$position = 0;
		}
    }
	if (isset ($_GET['position_erase']))
	$position = 0;
	if ($position==0)
	{
		foreach ($array_value as $key_select_param => $v_select_param)
		{
			$pos = count($array_value)-$key_select_param;
			$sql = "UPDATE `ls_params_select_values` set `position` = '".$pos."' where `id` = '".$v_select_param['id']."';";
			mysql_query ($sql);
		}
	}
    $body_admin .= '
	</table>
    <a href="index.php?do=params">'.$lang[139].'</a>
    </center>
    ';
}
?>