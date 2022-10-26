<?php
$array_params_filtr = $config ['topland_5'];
//unset ($_SESSION);
if (isset ($_GET['clear']))
{
	@session_unset();
	@session_destroy();
	unset ($_POST);
}
if (isset ($_POST['status']))
{
	$session = $_SESSION;
	foreach ($session as $key => $v)
	{
		//print $key."<br>";
		unset ($_SESSION[$key]);
	}
	$post = $_POST;
	foreach ($post as $key => $v)
	{
		$_SESSION[$key] = $v;
	}
}
//print_r ($_SESSION);
//print_r ($_POST);
$left_block .= '<form action="" method="POST">
<input type="hidden" name="status" value=1>
<a href="?clear" style="color:#005A87;">Очистить параметры подбора</a>
';
foreach ($array_params_filtr as $v)
{
	$translate_select_param = return_one_translate ($v, $id_online_lang, 'select');
	$left_block .= '
	<div class="block_body_blue">
		<div class="ktg">
		<span>'.$translate_select_param['text'].'</span><br />
	';
	$array_params = return_values_select_params($v);
	//print_r ($array_params);
	foreach ($array_params as $key => $value)
	{
		$translate_select_value = return_one_translate ($value['id'], $id_online_lang, 'select_value');
		if (!$i)
		{
			$class = ' class="gr"';
			$i = 1;
		} else {
			$i = 0;
		}
		$image_params = chek_values_select_text ($value['id_params'], '0');
		$text_params = chek_values_select_text ($value['id_params'], '1');
		if ($image_params)
		{
			if ($_POST['params_'.$v.'_'.$value['id']] or $_SESSION['params_'.$v.'_'.$value['id']])
			{
				$checked = ' checked ';
			}
			$left_block .= '<div class="ktg_color"><input type="checkbox" name="params_'.$v.'_'.$value['id'].'" onclick="this.form.submit();" '.$checked.' value="1" style="border:1px solid black;"> <img src="'.$config ['site_url'].'upload/select_params/'.$value['img'].'" style=""/></div>'."\r\n";
		}
		unset ($checked);
		if ($text_params)
		{
			if ($key==0)
			{
				$left_block .= '<table border="0">';
			}
			if ($_POST['params_'.$v.'_'.$value['id']] or $_SESSION['params_'.$v.'_'.$value['id']])
			{
				$checked = ' checked ';
			}
			$left_block .= '
			  <tr>
				<td'.$class.'><input type="checkbox" name="params_'.$v.'_'.$value['id'].'" onclick="this.form.submit();" '.$checked.' value="1" style="border:1px solid black;"> '.$translate_select_value['text'].'</td>
			  </tr>
			';
			if (!isset ($array_params[$key+1]))
			{
				$left_block .= '</table>';
			}
			unset ($checked);
		}
		unset ($class);
	}
	$left_block .= '
		</div>
	</div>
	';
	//print $v;
}
$left_block .= '</form>'; 
?>