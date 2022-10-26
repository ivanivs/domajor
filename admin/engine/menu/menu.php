<?php
require ('engine/params/functions.php');
if (isset ($_GET['action']))
{
    switch ($_GET['action'])
    {
	case "delete":
	    $sql = "DELETE from ls_menu where id = '".$_GET['id']."';";
	    mysql_query($sql);
	break;
    }
}
/*<td align="center">
	<a href="#" style="text-decoration:none" onclick="addNewMenuStatic();"><img src="'.$config ['site_url'].'images/admin/add.png"><br>
	['.$lang[662].']</a>
</td>
*/
$body_admin .= '
<div align="center">
<table border=0>
<tr>
<td align="center">
	<a href="index.php?do=menu" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/view.png"><br>
	['.$lang[89].']</a>
</td>
<td align="center">
	<a href="#" style="text-decoration:none" onclick="add_new_menu()"><img src="'.$config ['site_url'].'images/admin/add.png"><br>
	['.$lang[52].']</a>
</td>
</tr>
</table>
</div>
<div id="menu_main">';
$array_menu = return_all_menu();
if ($array_menu)
{
    $body_admin .= '<table cellspacing="1" class="tablesorter" width="100%">
		 <thead>
		<tr> 
			<th width="25" align="center">'.$lang[27].'</th> 
			<th>'.$lang[311].'</th>
                        <th width="100">'.$lang[312].'</th> 
		</tr> 
		 </thead> 
		  <tbody>';
    foreach ($array_menu as $v)
    {
        $body_admin .= '<tr>
        <td align="center"><i>{dynamic_menu_'.$v['id'].'}</i></td>
        <td>'.$v['name_menu'].'</td>
        <td align="center">
	    <img src="'.$config ['site_url'].'images/admin/edit.png" onclick="edit_menu(\''.$v['id'].'\')">
	    <a href="index.php?do=menu&action=delete&id='.$v['id'].'" onclick="return confirm(\''.$lang[69].'\')"><img src="'.$config ['site_url'].'images/admin/remove_16.png"></a>
	</td>
        </tr>';
    }
    $body_admin .= '</tbody></table>';
} else {
    $body_admin .= '<h2 id="title" align="center">'.$lang[313].'</h2>';
}
$body_admin .'</div>
';
$body_admin = '
<div id="info_admin_page">
<img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[307].$other_way.'
</div>
'.$body_admin;
?>