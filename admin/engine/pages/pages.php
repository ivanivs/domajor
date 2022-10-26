<?php
$body_admin .= '
<div align="center">
<table border=0>
<tr>
<td align="center" valign="top">
	<a href="index.php?do=pages" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/viewmag1.png"><br>
	['.$lang[89].']</a>
</td>
<td align="center" valign="top">
	<a href="index.php?do=pages&action=add_page" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/add_24.png"><br>
	['.$lang[195].']</a>
</td>
</tr>
</table>
</div>
';
if (isset ($_GET['no_action']))
{
	switch ($_GET['no_action'])
	{
		case "del":
			$sql = "
			DELETE from `ls_translate` where (`type` = 'static_page_nam' or `type` = 'static_page_key' or 
			`type` = 'static_page_des' or `type` = 'static_page_tit' or `type` = 'static_page_tex') and `id_elements` = '".$_GET['id']."';
			";
			mysql_query ($sql);
			$sql = "DELETE from `ls_static_pages` where `id` = '".$_GET['id']."';";
			mysql_query ($sql);
		break;
	}
}
if (!isset ($_GET['action']))
{
	$array_static_page = return_all_static_page ();
	if ($array_static_page)
	{
		$body_admin .= '
		<div align="center">
			<h2 id="title">'.$lang[205].'</h2>
			<table cellspacing="1" class="tablesorter" width="100%">
			 <thead>
			<tr> 
				<th width="25">ID</th> 
				<th>Название страницы</th> 
				<th>Дата</th> 
				<th>Действие</th> 
			</tr> 
			 </thead> 
			  <tbody> 
		';
		foreach ($array_static_page as $v)
		{
			$info_static_page = return_one_translate ($v['id'], $id_online_lang, 'static_page_nam');
			$body_admin .= '
			<tr>
				<td align="center">'.$v['id'].'</td>
				<td>'.$info_static_page['text'].'</td>
				<td align="center" width="200">'.date("d.m.Y H:m:s", $v['time']).'</td>
				<td align="center" width="150">
					<a href="index.php?do=pages&action=edit&id='.$v['id'].'"><img src="'.$config ['site_url'].'images/admin/edit.png"></a>
					<a href="index.php?do=pages&no_action=del&id='.$v['id'].'" onclick="return confirm (\''.$lang[69].'\')"><img src="'.$config ['site_url'].'images/admin/remove_16.png"></a>
				</td>
			</tr>';
		}
		$body_admin .= '
			</tbody>
			</table>
		';
	} else {
		$body_admin .= '<h2 id="title">'.$lang[204].'</h2>';
	}
} else {
	switch ($_GET['action'])
	{
		case "add_page":
			require ('engine/pages/action/add_page.php');
		break;
		case "edit":
			require ('engine/pages/action/edit.php');
		break;
	}
}
$body_admin = '
<div id="info_admin_page">
<img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[205].$other_way.'
</div>
'.$body_admin;
?>