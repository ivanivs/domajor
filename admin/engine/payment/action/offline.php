<?php
if (isset ($_GET['action_offline']))
{
    switch ($_GET['action_offline'])
    {
        case "add":
            require ('engine/payment/action/offline/add_new_offline.php');
            $other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[466];
        break;
        case "edit":
            require ('engine/payment/action/offline/edit_or_translate.php');
            $other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[474];
        break;
    }
} else {
    if (isset ($_GET['active']))
    {
        $sql = "update `ls_payment` set `active` = '".$_GET['active']."' where `id` = '".$_GET['id']."';";
        mysql_query($sql);
    }
    if (isset ($_GET['del']))
    {
        $sql = "delete from `ls_translate` where `type` = 'pay_name' and `id_elements` = '".$_GET['id']."';";
        mysql_query($sql);
        $sql = "delete from `ls_translate` where `type` = 'pay_template' and `id_elements` = '".$_GET['id']."';";
        mysql_query($sql);
        $sql = "delete from `ls_payment` where `id` = '".$_GET['id']."';";
        if (mysql_query($sql))
        {
            $body_admin .= '<strong style="color:red;">'.$lang[477].'</strong>';
        }
    }
    $body_admin .= '
    <h1>'.$lang[465].'</h1>
    <a href="index.php?do=payment&action=offline&action_offline=add" style="text-decoration:none;"><img src="'.$config ['site_url'].'images/admin/edit_add.png">'.$lang[464].'</a>';
    $all_payment_offline = return_all_offline_method();
    if ($all_payment_offline)
    {
        $array_lang = return_all_ok_lang();
	if ($array_lang)
	{
	    foreach ($array_lang as $one_lang)
	    {
		$lang_html .= '<th width="50" align="center"><img src="'.$config ['site_url'].'images/languages/icon/'.$one_lang['alt_name'].'.png"></th>'."\r\n";
	    }
	}
        $body_admin .= '
            <table cellspacing="1" width="100%">
	<tr>
	    <th align="center" width="20"><b>ID</b></th>
	    <th align="center">'.$lang[469].'</th>
	    '.$lang_html.'
            <th align="center" width="20">'.$lang[478].'</th>
	    <th align="center" width="15">'.$lang[430].'</th>
	</tr>
        ';
        foreach ($all_payment_offline as $v)
        {
            if ($array_lang)
	    {
		foreach ($array_lang as $one_lang)
		{
		    $lang_payment .= '<td width="50" align="center"><a href="index.php?do=payment&action=offline&action_offline=edit&id_online_lang='.$one_lang['id'].'&id='.$v['id'].'"><img src="'.$config ['site_url'].'images/admin/edit.png"></td>'."\r\n";
		}
	    }
            $name_payment = mysql_fetch_array(mysql_query("select `text` from `ls_translate` where `type` = 'pay_name' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".$v['id']."';"), MYSQL_ASSOC);
            if ($v['active'])
            {
                $status_payment_type = '<a href="index.php?do=payment&action=offline&active=0&id='.$v['id'].'"><img src="'.$config ['site_url'].'images/admin/green.png"></a>';
            } else {
                $status_payment_type = '<a href="index.php?do=payment&action=offline&active=1&id='.$v['id'].'"><img src="'.$config ['site_url'].'images/admin/red.png"></a>';
            }
            $body_admin .= '
            <tr>
                <td align="center">'.$v['id'].'</td>
                <td>'.$name_payment['text'].'</td>
                '.$lang_payment.'
                <td align="center">'.$status_payment_type.'</td>
                <td align="center"><a href="index.php?do=payment&action=offline&del&id='.$v['id'].'" onclick="return confirm (\''.$lang['69'].'\')"><img src="'.$config ['site_url'].'images/admin/remove_16.png"></a></td>
            </tr>
            ';
            unset ($lang_payment);
        }
        $body_admin .= '</table>';
    } else {
        $body_admin .= '<h2 style="color:red;">'.$lang[473].'</h2>';
    }
}
?>