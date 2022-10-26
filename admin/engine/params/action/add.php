<?php
$other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[41];
if (!isset ($_POST['type_params']))
{
	$body_admin .= '
	<style>
	.params_form {
		color:#000000; 
		font: 84% \'trebuchet ms\',helvetica,sans-serif; 
		background-color:#fed; 
		border: 1px solid; 
		font-size:12px;
		border-color: #696 #363 #363 #696; 
		filter:progid:DXImageTransform.Microsoft.Gradient 
		(GradientType=0,StartColorStr=\'#ffffffff\',EndColorStr=\'#ffeeddaa\'); 
	}
	</style>
	<div align="center">
	<h2 id="title">'.$lang[73].'</h2>
		<form action="" method="POST">
			<table border="0">
				<tr>
					<td>'.$lang[74].': </td>
					<td>
						<select name="type_params" class="params_form">
						<option value="0">'.$lang[75].'</option>
						<option value="1">'.$lang[77].'</option>
						<option value="3">'.$lang[79].'</option>
						<option value="4">'.$lang[80].'</option>
						<option value="6">'.$lang[82].'</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="submit" name="submit" value="'.$lang[84].'" class="params_form">
					</td>
				</tr>
			</table>
		</form>
	</div>
	';
	/*
	 При змозі доробити наступні параметри для зручності.
	 <option value="2">'.$lang[78].'</option>
	 <option value="5">'.$lang[81].'</option>
	 <option value="7">'.$lang[83].'</option>
	*/
} else {
	switch ($_POST['type_params'])
	{
		case "0":
			require ('engine/params/action/add_text.php');
		break;
		case "1":
			require ('engine/params/action/add_select.php');
		break;
		case "3":
			require ('engine/params/action/add_image.php');
		break;
		case "4":
			require ('engine/params/action/add_price.php');
		break;
		case "6":
			require ('engine/params/action/add_boolean.php');
		break;
	}
}
?>