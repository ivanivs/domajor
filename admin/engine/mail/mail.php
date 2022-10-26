<?php
$body_admin .= '
<div align="center">
<table border=0>
<tr>
<td align="center" valign="top">
	<a href="index.php?do=mail" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/mail-reply-all.png"><br>
	['.$lang[496].']</a>
</td>
<td align="center" valign="top">
	<a href="index.php?do=mail&action=view_mail_for_payment" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/mail_order.png"><br>
	['.$lang[499].']</a>
</td>
<td align="center" valign="top">
	<a href="index.php?do=mail&action=view_mail_for_feedback" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/misc_06_8338.png" height="24"><br>
	['.$lang[651].']</a>
</td>
</tr>
</table>
</div>
';
if (isset ($_GET['action']))
{
    switch ($_GET['action'])
    {
        case "view_mail_for_payment":
            $other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[498];
            $type_view = 'payment';
            require ('engine/mail/action/view_mail_for_payment.php');
        break;
	case "view_mail_for_feedback":
            $other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[651];
            $type_view = 'feedback';
            require ('engine/mail/action/view_mail_for_payment.php');
        break;
    }
} else {
    require ('engine/mail/action/view_mail_for_payment.php');
}
$body_admin = '
<div id="info_admin_page">
<img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[497].$other_way.'
</div>
'.$body_admin;
?>