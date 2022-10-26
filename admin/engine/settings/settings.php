<?php
if (isset ($_POST['id_setting']))
{
	$id_setting = $_POST['id_setting'];
	$values_settings = $_POST['setting'];
	foreach ($id_setting as $key => $v)
	{
		$sql = "update ls_settings set value='".$values_settings[$key]."' where id='".$v."';";
		mysql_query ($sql);
	}
}
$results = mysql_query("SELECT id, lang, value  FROM ls_settings;");
$number = mysql_num_rows ($results);
for ($i=0; $i<$number; $i++)
{	
	$array_settings[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
}
foreach ($array_settings as $v)
{
	$body_settings .= '
	<tr>
		<td width="25"><b><small>ID: '.$v['id'].'</small></b></td>
		<td align="left">'.$lang[$v['lang']].'</td>
		<td>
			<input type="text" name="setting[]" value="'.$v['value'].'" style="border:1px solid black;">
			<input type="hidden" name="id_setting[]" value="'.$v['id'].'">
		</td>
	</tr>
	';
}
$body_admin .= '
<div align="center"><h2 id="title">'.$lang[207].'</h2>
<div style="width:450px; text-align:center;" align="center">
<form action="" method="POST">
<table border="0">
	'.$body_settings.'
	<tr>
		<td align="center" colspan="3"><input type="submit" name="submit" value="'.$lang[45].'"></td>
	</tr>
</table>
</form>
</div></div>
';
?>