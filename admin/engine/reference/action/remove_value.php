<?php
$other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[68];
if (isset ($_GET['id']))
{
	if (isset ($_GET['del']))
	{
		$sql = "DELETE from ls_reference_values_translate where id_reference_value='".$_GET['del']."';";
		if (mysql_query($sql))
		{
			$query_good = 1;
		} else {
			$bad_query = 1;
		}
		$sql = "DELETE from ls_reference_values where id='".$_GET['del']."';";
		if (mysql_query($sql))
		{
			$query_good = 1;
		} else {
			$bad_query = 1;
		}
		if ($bad_query!=1)
		{
			$body_admin .= '<div align="center" style="font-size:16; color:green">'.$lang[71].'</div>';
		} else {
			$body_admin .= '<div align="center" style="font-size:16; color:red">'.$lang[72].'</div>';
		}
	}
	$info_reference = return_one_reference_for_id ($_GET['id']);
	$info_value_reference = return_all_values_for_reference ($_GET['id']);
	if (count ($info_value_reference)>0)
	{
		if ($info_value_reference)
		{
			$body_admin .= '
			<div align="left" style="font-size:16px; color:#227399">'.$info_reference['name'].'</div>
				<div align="center">
			<table cellspacing="1" class="tablesorter">
			 <thead>
			<tr> 
				<th>'.$lang[55].'</th> 
				<th><img src="'.$config ['site_url'].'images/admin/remove_16.png"></th> 
			</tr> 
			 </thead> 
			  <tbody> 
			';
			foreach($info_value_reference as $v)
			{
				$info_reference_translate = return_all_translate_for_reference_value ($v['id'], $id_online_lang);
				$body_admin .= '
				<tr>
				<td>'.$info_reference_translate[0]['value'].'</td>
				<td width="30" align="center">
				<a href="index.php?do=reference&action=remove_value&id='.$_GET['id'].'&del='.$v['id'].'" onclick="return confirm (\''.$lang[69].'\')"><img src="'.$config ['site_url'].'images/admin/remove_16.png">
				</td>
				</tr>
				';
			}
			$body_admin .= '
			</tbody>
			</table>
			</div>
			<div align="left" style="color:red">'.$lang[70].'</div>
			';
		} else {
			$body_admin .= '<span style="color:red;">'.$lang[500].'</span>';
		}
	}	
}
?>