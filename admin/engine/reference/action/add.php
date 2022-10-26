<?php
$other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[60];
if (isset ($_POST['name_reference']))
{
	$sql = "
	INSERT INTO  `ls_reference` (
	`name`
	)
	VALUES (
	 '".$_POST['name_reference']."'
	);
	";
	if (mysql_query ($sql))
	{
		$body_admin .= '<div align="center" style="font-size:16; color:green">'.$lang[48].'</div>';
	} else {
		$body_admin .= '<div align="center" style="font-size:16; color:red">'.$lang[48].'</div>';
	}
}
$body_admin .= '
<div align="center">
<h2 id="title">'.$lang[46].'<h2>
	<form action="" method="POST">
		<table border=0>
			<tr>
				<td>'.$lang[44].':</td>
				<td><input type="text" name="name_reference" MAXLENGTH="32" class="textform"><br>
				<span style="color:red">'.$lang[47].'</span>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" name="submit" value="'.$lang[45].'"></td>
			</tr>
		</table>
	</form>
</div>
';
?>