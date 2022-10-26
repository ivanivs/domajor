<?php
$body_admin .= '
<div align="center">
<table border=0>
<tr>
<td align="center" valign="top">
	<a href="index.php?do=contact" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/contact.png"><br>
	['.$lang[646].']</a>
</td>
<td align="center" valign="top">
	<a href="index.php?do=contact&action=add_new_contact" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/contact-new_1307.png"><br>
	['.$lang[647].']</a>
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
            if (mysql_query("DELETE from `ls_contact_card` where `id` = ".intval($_GET['id']).";") and mysql_query("DELETE from `ls_translate` where `type` = 'contact_card_te' and  `id_elements` = ".intval($_GET['id']).";"))
            {
                $delete_success = '<div style="font-size:16px; color:green;">'.$lang[617].'</div>';
            } else {
                $delete_success = '<div style="font-size:16px; color:red;">'.$lang[618].'</div>';
            }
        break;
    }
}
if (!isset ($_GET['action']))
{
    if (!isset ($delete_success))
    $delete_success = '';
    $body_admin .= '<b>'.$lang[619].'
    <br>
    '.$lang[620].'
    </b>'.$delete_success;
    $results = mysql_query("SELECT * FROM `ls_contact_card` order by `id` DESC;");
    $number = mysql_num_rows ($results);
    if ($number)
    {
        for ($i=0; $i<$number; $i++)
        {	
            $array_contact_card[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
        }
        $array_lang = return_all_ok_lang();
        if (!isset ($lang_html))
        $lang_html = '';
	if ($array_lang)
	{
	    foreach ($array_lang as $one_lang)
	    {
		$lang_html .= '<th width="50" align="center"><img src="'.$config ['site_url'].'images/languages/icon/'.$one_lang['alt_name'].'.png"></th>'."\r\n";
	    }
	}
        $body_admin .= '
        <table width="100%" class="new_table">
        <tr>
            <th width="20" align="center">ID</th>
            <th width="400" align="center">'.$lang[621].'</th>
            '.$lang_html.'
            <th width="150">'.$lang[622].'</th>
            <th width="50">'.$lang[623].'</th>
            <th width="50">'.$lang[624].'</th>
        </tr>
        ';
        foreach ($array_contact_card as $v)
        {
            if ($array_lang)
	    {
		if (!isset ($lang_news))
		$lang_news = '';
		foreach ($array_lang as $one_lang)
		{
		    $lang_news .= '<td width="50" align="center"><a href="index.php?do=contact&action=edit&id_online_lang='.$one_lang['id'].'&id='.$v['id'].'"><img src="'.$config ['site_url'].'images/admin/edit.png"></td>'."\r\n";
		}
	    }
            switch ($v['feedback'])
            {
                case 0:
                    $feedback = '<img src="'.$config ['site_url'].'images/admin/red.png">';
                break;
                case 1:
                    $feedback = '<img src="'.$config ['site_url'].'images/admin/green.png">';
                break;
            }
            $link_to_contact_page = '<a href="'.$config ['site_url'].$alt_name_online_lang.'/mode/news/contact_'.$v['id'].'.html" target="_blank"><img src="'.$config ['site_url'].'images/admin/world_link_6018.png"></a>';
            $body_admin .= '<tr>
            <td align="center">'.$v['id'].'</td>
            <td>'.$v['name_card'].'</td>
            '.$lang_news.'
            <td align="center">'.$feedback.'</td>
            <td align="center">'.$link_to_contact_page.'</td>
            <td align="center"><a href="index.php?do=contact&no_action=del&id='.$v['id'].'" onclick="return confirm(\''.$lang[625].'\');"><img src="'.$config ['site_url'].'images/admin/remove_16.png"></a></td>
            </tr>';
        }
        $body_admin .= '</table>';
    }
} else {
    switch ($_GET['action'])
    {
        case "add_new_contact":
            require ('engine/contact/action/add_new_contact.php');
        break;
        case "edit":
            require ('engine/contact/action/edit.php');
        break;
    }
}
$body_admin = '
<div id="info_admin_page">
<img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[648].$other_way.'
</div>
'.$body_admin;
?>