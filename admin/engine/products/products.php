<?php
if (!isset ($body_admin))
{
    $body_admin = '';
}
$body_admin .= '
<div align="center">
<table border=0>
<tr>
<td align="center" valign="top">
	<a href="index.php?do=products" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/view.png"><br>
	['.$lang[89].']</a>
</td>
<td align="center" valign="top">
	<a href="index.php?do=products&action=add_card" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/card.png"><br>
	['.$lang[168].']</a>
</td>
<td align="center" valign="top">
	<a href="index.php?do=products&action=price" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/zav.png"><br>
	['.$lang[503].']</a>
</td>
</tr>
</table>
</div>
';
if (isset ($_GET['no_action']))
{
    switch ($_GET['no_action'])
    {
	case "del_card":
	    $sql = "delete from `ls_card` where `id` = '".$_GET['id_card']."';";
	    mysql_query ($sql);
	    $sql = "delete from `ls_cardparam` where `id_card` = '".$_GET['id_card']."';";
	    mysql_query ($sql);
	break;
    }
}
if (!isset ($_GET['action']))
{
    $array_card = return_all_card();
	if ($array_card)
	{
		$body_admin .= '
		<h2 id="title">'.$lang[175].'</h2>
		';
		$body_admin .= '<table border="0">';
		foreach ($array_card as $v)
		{
			$results = mysql_query("SELECT * FROM `ls_items` where `id_card` = '".$v['id']."';");
			$number = @mysql_num_rows ($results);
			if (!$number)
			{
			    $del_card = '<td><a href="index.php?do=products&no_action=del_card&id_card='.$v['id'].'" onclick="return confirm (\''.$lang[69].'\')" style="padding:0px 0px 0px 5px;"><img src="'.$config ['site_url'].'images/admin/remove_24.png"></a></td>';
			}
			$body_admin .= '
			<tr>
				<td><a href="index.php?do=products&action=edit_card&id='.$v['id'].'" style="font-size:14px; text-decoration:none;">'.$v['name'].'</a></td> 
				<td><a href="index.php?do=products&action=add_item&id_card='.$v['id'].'" style="padding:0px 0px 0px 5px;"><img src="'.$config ['site_url'].'images/admin/add.png"></a></td>
				<td><a href="index.php?do=products&action=view_item&id_card='.$v['id'].'&limit=0" style="padding:0px 0px 0px 5px;"><img src="'.$config ['site_url'].'images/admin/view_text_24.png"></a></td>
				<td><a href="index.php?do=products&action=fastEdit&id_card='.$v['id'].'&limit=0" style="padding:0px 0px 0px 5px;"><i class="icon-edit"></i></a></td>
				'.$del_card.'
			</tr>';
			unset ($del_card);
		}
		$body_admin .= '</table>';
	} else {
	    $body_admin .= '<h2 id="title" style="color:red;">'.$lang[297].'</h2>';
	}
} else {
    switch ($_GET['action'])
    {
        case "fastEdit":
            require ('engine/products/action/fastEdit.php');
            break;
        case "add_card":
            require ('engine/products/action/add_card.php');
        break;
	case "edit_card":
		require ('engine/products/action/edit_card.php');
	    break;
	case "add_item":
		require ('engine/products/action/add_item.php');
	    break;
	case "view_item":
	       require ('engine/products/action/view_item.php');
        break;
	case "edit_item":
            require ('engine/products/action/edit_item.php');
        break;
	case "price":
            require ('engine/products/action/price_conkret.php');
        break;
	case "price_edit":
            require ('engine/products/action/price_edit.php');
        break;
    }
}
$body_admin = '
<div id="info_admin_page">
<img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[167].$other_way.'
</div>
'.$body_admin;
?>