<?php
require_once ('./../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
require_once ('./../include/functions.php');
require ('engine/products/functions.php');
require ('engine/params/functions.php');
if (isset ($_GET['lang']))
{
	setcookie ("lang", $_GET['lang'], time() + $config['time_life_cookie']);
}
$lang_file_array = return_my_language ();
foreach ($lang_file_array as $v)
{
	require_once($v);
}
require_once ('./../include/cookie.php');
if (isset ($_COOKIE['lang']) or isset ($_GET['lang']))
{
	if (isset ($_COOKIE['lang']))
	{
		$alt_name_online_lang = $_COOKIE['lang'];
	} else {
		$alt_name_online_lang = $_GET['lang'];
	}
	$info_for_my_lang = return_info_for_lang_for_alt_name($alt_name_online_lang);
	$id_online_lang = $info_for_my_lang['id'];
} else {
	$info_for_my_lang = return_info_for_lang_for_alt_name($config ['default_language']);
	$id_online_lang = $info_for_my_lang['id'];
}
if (isset ($_COOKIE['id_user_online']))
{
	$body = '<title>'.$lang[182].'</title> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<script type="text/javascript" src="'.$config ['site_url'].'js/jquery_1.4.4.js"></script> 
<script type="text/javascript" src="'.$config ['site_url'].'js/tooltipjs.js"></script> 
<script type="text/javascript" src="'.$config ['site_url'].'js/jquery.tablesorter.min.js"></script> 
<script type="text/javascript" src="'.$config ['site_url'].'js/jquery.MultiFile.js"></script> 
<script type="text/javascript" src="'.$config ['site_url'].'js/jquery.form.js"></script> 
<script type="text/javascript" src="'.$config ['site_url'].'js/jquery.blockUI.js"></script> 
	';
		$info_cardparam = return_one_cardparam ($_GET['id_param']);
		$info_param_image = return_one_image_param ($info_cardparam['id_param']);
		$body .= '
                        <script type="text/javascript">
                        $(document).ready(function(){
                                  
                        $(\'.MultiFile\').MultiFile({ 
                                accept:\'JPG|GIF|PNG|jpg|gif|png\', max:'.$info_param_image['number_image'].', STRING: { 
                                        remove:\''.$lang[133].'\',
                                        file:\'$file\', 
                                        selected:\''.$lang[134].': $file\', 
                                        denied:\''.$lang[135].': $ext!\', 
                                        duplicate:\''.$lang[136].':\n$file!\' 
                                } 
                        });		  
                                  
                        $("#loading").ajaxStart(function(){
                                $(this).show();
                        })
                        .ajaxComplete(function(){
                                $(this).hide();
                        });
                                  
                        
                        $(\'#uploadForm\').ajaxForm({
                                beforeSubmit: function(a,f,o) {
                                        o.dataType = "html";
                                        $(\'#uploadOutput\').html(\''.$lang[138].'...\');
                                },
                                success: function(data) {
                                        var $out = $(\'#uploadOutput\');
                                        $out.html(\''.$lang[137].' <a href="add_image.php?id='.$_GET['id'].'&id_param='.$_GET['id_param'].'&id_item='.$_GET['id_item'].'">'.$lang[184].'</a>\');
                                        if (typeof data == \'object\' && data.nodeType)
                                                data = elementToString(data.documentElement, true);
                                        else if (typeof data == \'object\')
                                                data = objToString(data);
                                        $out.append(\'<div><pre>\'+ data +\'</pre></div>\');
                                }
                        });
                        });
                        </script>
                        <div align="center">
						<h2>'.$lang[190].'</h2>
                            <form id="uploadForm" action="engine/products/action/doajaxfileupload.php?id_card='.$_GET['id'].'&id_cardparam='.$_GET['id_param'].'&id_item='.$_GET['id_item'].'" method="post" enctype="multipart/form-data">
                            <input name="MAX_FILE_SIZE" value="10000000" type="hidden"/>
                            <input name="fileToUpload[]" id="fileToUpload" class="MultiFile" type="file"/>
                            <input name="id_select" type="hidden" value="'.$_GET['id'].'"/><br>
                            <input value="Submit" type="submit"/>
                            </form>
                            <img id="loading" src="'.$config ['site_url'].'images/admin/loading.gif" style="display:none;"/>   
                            <div id="uploadOutput"></div>
                        </div>
        ';
		if (isset ($_GET['up']))
		{
			$info_this_param = return_image_for_card_one_for_id ($_GET['up']);
			$info_next_param = return_image_for_card_one_for_position ($info_this_param['position']+1, $_GET['id_param']);
			if (count ($info_next_param)>1)
			{
				$sql = "update ls_values_image set position='".$info_next_param['position']."' where id='".$info_this_param['id']."';";
				mysql_query ($sql);
				$sql = "update ls_values_image set position='".$info_this_param['position']."' where id='".$info_next_param['id']."';";
				mysql_query ($sql);
			}
		}
		if (isset ($_GET['down']))
		{
			$info_this_param = return_image_for_card_one_for_id ($_GET['down']);
			$info_next_param = return_image_for_card_one_for_position ($info_this_param['position']-1, $_GET['id_param']);
			if (count ($info_next_param)>1)
			{
				$sql = "update ls_values_image set position='".$info_next_param['position']."' where id='".$info_this_param['id']."';";
				mysql_query ($sql);
				$sql = "update ls_values_image set position='".$info_this_param['position']."' where id='".$info_next_param['id']."';";
				mysql_query ($sql);
			}
		}
		if (isset($_GET['del']))
		{
			$sql = "DELETE from `ls_values_image` where `id` = '".$_GET['del']."';";
			mysql_query($sql);
		}
		$position = 1;
		$array_image = return_all_image_for_id_item ($_GET['id_item'], $_GET['id_param']);
		if ($array_image)
		{
			$body .= '
			<div align="center">
			<h2>'.$lang[191].'</h2>
			<table border=0>
			';
			foreach ($array_image as $v)
			{
				if ($v['position']==0)
				{
					$position = $v['position'];
				}
				$body .= '<tr>
				<td><a href="add_image.php?id='.$_GET['id'].'&id_param='.$_GET['id_param'].'&id_item='.$_GET['id_item'].'&up='.$v['id'].'"><img src="'.$config ['site_url'].'images/admin/1uparrow.png" border="0"></a>
				<a href="add_image.php?id='.$_GET['id'].'&id_param='.$_GET['id_param'].'&id_item='.$_GET['id_item'].'&down='.$v['id'].'"><img src="'.$config ['site_url'].'images/admin/1downarrow.png" border="0"></td>
				<td><img src="'.$config ['site_url'].'upload/userparams/'.$v['value'].'"></td>
				<td><a href="add_image.php?id='.$_GET['id'].'&id_param='.$_GET['id_param'].'&id_item='.$_GET['id_item'].'&del='.$v['id'].'" onclick="return confirm (\''.$lang[69].'\')"><img src="'.$config ['site_url'].'images/admin/remove_16.png" border="0"></a></td>
				</tr>';
			}
			$body .= '</table></div>';
		}
		if ($position==0)
		{
			foreach ($array_image as $key => $v)
			{
				$pos = count($array_image)-$key;
				$sql = "UPDATE ls_values_image set position='".$pos."' where id='".$v['id']."';";
				mysql_query ($sql);
			}
		}
	print $body;
}
?>