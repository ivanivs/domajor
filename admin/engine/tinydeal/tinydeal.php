<?php
$body_admin .= '
<div align="center">
<table border=0>
<tr>
<td align="center" valign="top">
	<a href="index.php?do=tinydeal" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/view.png"><br>
	['.$lang[51].']</a>
</td>
<td align="center" valign="top">
	<a href="index.php?do=tinydeal&action=add_new_work" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/add.png"><br>
	['.$lang[543].']</a>
</td>
</tr>
</table>
</div>
';
if (isset ($_GET['action']))
{
    switch ($_GET['action'])
    {
        case "add_new_work":
            $other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[543];
            require ('engine/tinydeal/action/add_new_work.php');
        break;
        case "new":
            $array_param = get_one_item_tinydeal('http://www.tinydeal.com/ru/fashionable-quartz-round-wrist-watch-timepiece-with-alloy-band-for-men-male-golden-brim-w4-80231-p-36762.html');
            print_r ($array_param);
        break;
    }
} else {
    $all_work = return_all_work();
    if ($all_work)
    {
        $body_admin .= '
        <table width="100%">
        <tr>
            <th width="20" align="center">ID</th>
            <th width="300" align="center">'.$lang[547].'</th>
            <th width="300" align="center">URL</th>
            <th width="170" align="center">'.$lang[549].'</th>
            <th width="170" align="center">'.$lang[550].'</th>
        </tr>
        ';
        foreach ($all_work as $key => $v)
        {
            switch($v['status'])
            {
                case "0":
                    $status = '<a href="index.php?do=tinydeal&action=new&id='.$v['id'].'" style="color:red;">Новое</a>';
                break;
            }
            $body_admin .= '
            <tr>
            <td width="20" align="center">'.$v['id'].'</td>
            <td width="300" align="center">'.$v['name'].'</td>
            <td width="300" align="center"><a href="'.$v['url'].'" target="_blank"><img src="'.$config ['site_url'].'images/admin/world_link.png"></a></th>
            <td width="170" align="center">'.$v['time'].'</td>
            <td width="170" align="center">'.$status.'</td>
            </tr>
            ';
        }
        $body_admin .= '</table>';
    } else {
        $body_admin .= '<span style="color:red;">'.$lang[546].'</span>';
    }
}
$body_admin = '
<div id="info_admin_page">
<img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[542].$other_way.'
</div>
'.$body_admin;
?>