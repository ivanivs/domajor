<?php
$other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[65];
if (!isset ($_GET['id']))
{
	
} else {
	if (isset ($_POST['info_translate']))
	{
		$info_translate = $_POST['info_translate'];
		$id_translate_value_reference = $_POST['id_translate_value_reference'];
		$id_lang_value_reference = $_POST['id_lang_value_reference'];
		$id_reference_value = $_POST['id_reference_value'];
		foreach ($info_translate as $key=>$v)
		{
			if (strlen($id_translate_value_reference[$key])>0)
			{
				$sql = "
				UPDATE ls_reference_values_translate set value='".$v."' where id='".$id_translate_value_reference[$key]."';
				";
				if (mysql_query($sql))
				{
					$query_good = 1;
				} else {
					$bad_query = 1;
				}
			}else {
				$sql = "
				INSERT INTO  `ls_reference_values_translate` (
				`id_lang` ,
				`id_reference_value` ,
				`value`
				)
				VALUES (
				'".$id_lang_value_reference[$key]."' ,
				'".$id_reference_value[$key]."' , 
				'".$v."'
				);
				";
				if (mysql_query($sql))
				{
					$query_good = 1;
				} else {
					$bad_query = 1;
				}
			}
		}
		if ($bad_query!=1)
		{
			$body_admin .= '<div align="center" style="font-size:16; color:green">'.$lang[66].'</div>';
		} else {
			$body_admin .= '<div align="center" style="font-size:16; color:red">'.$lang[67].'</div>';
		}
	}
	$array_true_lang = return_all_ok_lang ();
	$info_reference = return_one_reference_for_id ($_GET['id']);
	if (count ($array_true_lang)>0)
	{
		$body_admin .= '
		<style>
		.translate_form { 
		 font-size: 10px; 
		 background-color: #C2ECFF; 
		 border: 1px solid #666666; 
		}
		</style>
		<div align="left" style="font-size:16px; color:#227399">'.$info_reference['name'].'</div>
		<form action="" method="POST">
		<div align="center">
		<table cellspacing="1" class="tablesorter">
		<thead>
		<tr>';
		foreach ($array_true_lang as $v_lang)
		{
			$body_admin .= '<th>'.$v_lang['name'].'</th>';
		}
		$body_admin .= '
		</tr> 
		</thead> 
		<tbody> 
		';
		$true_values_reference = return_all_values_for_reference ($_GET['id']);
		if (count ($true_values_reference)>0)
		{
			foreach ($true_values_reference as $v_value)
			{
				$body_admin .= '
				<tr>
				';
				foreach ($array_true_lang as $v_lang)
				{
					$info_translate = return_all_translate_for_reference_value ($v_value['id'], $v_lang['id']);
					$body_admin .= '
					<td align="center"><input type="text" name="info_translate[]" value="'.$info_translate[0]['value'].'" class="translate_form">
					<input type="hidden" name="id_translate_value_reference[]" value="'.$info_translate[0]['id'].'">
					<input type="hidden" name="id_lang_value_reference[]" value="'.$v_lang['id'].'">
					<input type="hidden" name="id_reference_value[]" value="'.$v_value['id'].'">
					</td>
					';
				}
				$body_admin .= '
				</tr>
				';
			}
		}
		$body_admin .='
		</tbody>
		</table>
		</div>
		<input type="submit" name="submit" value="'.$lang[45].'" class="translate_form">
		</form>
		';
	}
}
?>