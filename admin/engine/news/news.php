<?php
require ('engine/template/functions.php');
$body_admin .= '
<div align="center">
<table border=0>
<tr>
<td align="center" valign="top">
	<a href="index.php?do=news" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/viewmag1.png"><br>
	['.$lang[89].']</a>
</td>
<td align="center" valign="top">
	<a href="index.php?do=news&action=add_news" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/add_24.png"><br>
	['.$lang[386].']</a>
</td>
<td align="center" valign="top">
	<a href="index.php?do=news&action=category" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/news_category.png"><br>
	['.$lang[385].']</a>
</td>
</tr>
</table>
</div>
';
if (isset ($_GET['action']))
{
    switch ($_GET['action'])
    {
        case "category":
            require ("engine/news/action/category.php");
            $other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[387];
        break;
	case "add_news":
	    require ("engine/news/action/add_news.php");
            $other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[424];
	break;
	case "del_news":
	    $sql = "delete from `ls_news` where `id` = '".$_GET['id']."';";
	    if (mysql_query($sql))
	    {
		$sql = "delete from `ls_translate` where `type` = 'news_name' and `id_elements` = '".$_GET['id']."';";
		mysql_query($sql);
		$sql = "delete from `ls_translate` where `type` = 'news_key' and `id_elements` = '".$_GET['id']."';";
		mysql_query($sql);
		$sql = "delete from `ls_translate` where `type` = 'news_descriptio' and `id_elements` = '".$_GET['id']."';";
		mysql_query($sql);
		$sql = "delete from `ls_translate` where `type` = 'news_short' and `id_elements` = '".$_GET['id']."';";
		mysql_query($sql);
		$sql = "delete from `ls_translate` where `type` = 'news_full' and `id_elements` = '".$_GET['id']."';";
		mysql_query($sql);
		$body_admin .= '<h2 style="color:red;">'.$lang[425].'</h2>';
	    }	    
	break;
	case "edit":
	    require ("engine/news/action/edit_and_translate.php");
            $other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[426];
	break;
    }
} else {
    $all_news = return_all_news();
    if ($all_news)
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
	<h1>'.$lang[431].'</h1>
	<table cellspacing="1" width="100%">
	<tr>
	    <th align="center" width="20"><b>ID</b></th>
	    <th align="center" width="200">'.$lang[427].'</th>
	    <th align="center">'.$lang[428].'</th>
	    <th align="center" width="100">'.$lang[429].'</th>
	    '.$lang_html.'
	    <th align="center" width="15">'.$lang[430].'</th>
	</tr>
	';
	foreach ($all_news as $v)
	{
	    $category_news = mysql_fetch_array(mysql_query("select `text` from `ls_translate` where `type` = 'news_category_n' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".$v['id_category']."';"), MYSQL_ASSOC);
	    $name_news = mysql_fetch_array(mysql_query("select `text` from `ls_translate` where `type` = 'news_name' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".$v['id']."';"), MYSQL_ASSOC);
	    if ($array_lang)
	    {
		if (!isset ($lang_news))
		$lang_news = '';
		foreach ($array_lang as $one_lang)
		{
		    $lang_news .= '<td width="50" align="center"><a href="index.php?do=news&action=edit&id_online_lang='.$one_lang['id'].'&id='.$v['id'].'"><img src="'.$config ['site_url'].'images/admin/edit.png"></td>'."\r\n";
		}
	    }
	    if (strlen($category_news['text'])==0)
	    {
		$category_news['text'] = $lang[432];
	    }
	    if (strlen($name_news['text'])==0)
	    {
		$name_news['text'] = $lang[433];
	    }
	    $body_admin .= '<tr>
		<td align="center"><b>'.$v['id'].'</b></td>
		<td>'.$category_news['text'].'</td>
		<td><b>'.$name_news['text'].'</b></td>
		<td align="center"><b style="color:green;">'.$v['view'].'</b></td>
		'.$lang_news.'
		<td align="center"><a href="index.php?do=news&action=del_news&id='.$v['id'].'" onclick="return confirm (\''.$lang['69'].'\')"><img src="'.$config ['site_url'].'images/admin/remove_16.png"></a></td>
	    </tr>';
	    unset ($lang_news);
	}
	$body_admin .= '</table>';
    } else {
	$body_admin .= '<h3>'.$lang[434].'</h3>';
    }
}
$body_admin = '
<div id="info_admin_page">
<img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[384].$other_way.'
</div>
'.$body_admin;
?>