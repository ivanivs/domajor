<?php
$body_admin .= '
<div align="center">
<table border=0>
<tr>
<td align="center" valign="top">
	<a href="index.php?do=payment&action=online" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/online.png"><br>
	['.$lang[461].']</a>
</td>
<td align="center" valign="top">
	<a href="index.php?do=payment&action=offline" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/offline.png"><br>
	['.$lang[462].']</a>
</td>
</tr>
</table>
</div>
';
if (isset ($_GET['action']))
{
    switch ($_GET['action'])
    {
        case "online":
            $body_admin .= '<strong>Розділ в розробці.</strong>';
        break;
        case "offline":
            $other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[463];
            require ('engine/payment/action/offline.php');
        break;
    }
} else {

}
$body_admin = '
<div id="info_admin_page">
<img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[460].$other_way.'
</div>
'.$body_admin;
?>