<?php
$body_admin .= '
<div id="info_admin_page">
<img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[19].'
</div>
';
$array_languages_files = scandir ('languages');
foreach ($array_languages_files as $v)
{
	if ($v!='.' and $v!='..')
	{
		$array[] = str_replace ('.php', '', $v);
	}
}
$array_languages_files = $array;
if (count ($array)>0)
{
	if (!isset ($_POST['edit']))
	{
		if (isset ($_POST['name']))
		{
			$sql = "
			INSERT INTO  `ls_lang` (
			`alt_name` ,
			`name` ,
			`active`
			)
			VALUES (
			'".$_POST['alt_name']."',
			'".$_POST['name']."',
			1
			);
			";
			if (mysql_query ($sql))
			{
				$body_admin .= '<div align="center" style="font-size:16; color:green">'.$lang[48].'</div>';
			} else {
				$body_admin .= '<div align="center" style="font-size:16; color:red">'.$lang[48].'</div>';
			}
		}
	} else {
		$sql = "UPDATE ls_lang set name='".$_POST['name']."' where id='".$_POST['id']."';";
		if (mysql_query ($sql))
		{
			$body_admin .= '<div align="center" style="font-size:16; color:green">'.$lang[25].'</div>';
		} else {
			$body_admin .= '<div align="center" style="font-size:16; color:red">'.$lang[26].'</div>';
		}
	}
	if (isset ($_GET['action']))
	{
		switch ($_GET['action'])
		{
			case "off":
				$sql = "UPDATE ls_lang set active=0 where id='".$_GET['id']."';";
				mysql_query ($sql);
			break;
			case "on":
				$sql = "UPDATE ls_lang set active=1 where id='".$_GET['id']."';";
				mysql_query ($sql);
			break;
		}
	}
	$body_admin .= '
	<script>

$(document).ready(function() {	

	//select all the a tag with name equal to modal
	$(\'a[name=modal]\').click(function(e) {
		//Cancel the link behavior
		e.preventDefault();
		//Get the A tag
		var id = $(this).attr(\'href\');
	
		//Get the screen height and width
		var maskHeight = $(document).height();
		var maskWidth = $(window).width();
	
		//Set heigth and width to mask to fill up the whole screen
		$(\'#mask\').css({\'width\':maskWidth,\'height\':maskHeight});
		
		//transition effect		
		$(\'#mask\').fadeIn(1000);	
		$(\'#mask\').fadeTo("slow",0.8);	
	
		//Get the window height and width
		var winH = $(window).height();
		var winW = $(window).width();
              
		//Set the popup window to center
		$(id).css(\'top\',  winH/2-$(id).height()/2);
		$(id).css(\'left\', winW/2-$(id).width()/2);
	
		//transition effect
		$(id).fadeIn(2000); 
	
	});
	
	//if close button is clicked
	$(\'.window .close\').click(function (e) {
		//Cancel the link behavior
		e.preventDefault();
		$(\'#mask, .window\').hide();
	});		
	
	//if mask is clicked
	$(\'#mask\').click(function () {
		$(this).hide();
		$(\'.window\').hide();
	});			
	
});

</script>
<style>
#mask {
  position:absolute;
  left:0;
  top:0;
  z-index:9000;
  background-color:#000;
  display:none;
}
#boxes .window {
  position:absolute;
  left:0;
  top:0;
  width:440px;
  height:200px;
  display:none;
  z-index:9999;
  padding:20px;
}
input.btn { 
	  color:#050; 
	  font: bold 84% \'trebuchet ms\',helvetica,sans-serif; 
	  background-color:#fed; 
	  border: 1px solid; 
	  font-size:12px;
	  border-color: #696 #363 #363 #696; 
	  filter:progid:DXImageTransform.Microsoft.Gradient 
	  (GradientType=0,StartColorStr=\'#ffffffff\',EndColorStr=\'#ffeeddaa\'); 
} 
</style>

	<div align="center">
	<table cellspacing="1" class="tablesorter" width="100%">
	 <thead>
        <tr> 
            <th>'.$lang[27].'</th> 
            <th>'.$lang[28].'</th> 
            <th>'.$lang[29].'</th> 
            <th>'.$lang[30].'</th> 
            <th>'.$lang[31].'</th> 
            <th>'.$lang[32].'</th> 
        </tr> 
   	 </thead> 
  	  <tbody> 
	';
	$modal = '<div id="boxes">';
	foreach ($array_languages_files as $key => $v)
	{
		$info_lang_for_db = return_info_for_lang_for_alt_name ($v);
		if (count ($info_lang_for_db)>1)
		{
			$name_lang = '<span style="color:green; font-weight: bold; font-size:12px;">'.$info_lang_for_db['name'].' 
			<a href="#dialog'.$key.'" style="text-decoration:none;" name="modal"><img src="'.$config ['site_url'].'images/admin/edit.png"></a>
			</span>';
			$modal .= '
			<div id="dialog'.$key.'" class="window" style="
				  background:url(./../images/admin/notice.png) no-repeat 0 0 transparent; 
			  width:326px; 
			  height:229px;
			  padding:50px 0 20px 25px;
				" align="center">
			 <form id="form_'.$key.'" action="" method="POST">
			 <input type="hidden" name="edit" value="1">
			 <input type="hidden" name="id" value="'.$info_lang_for_db['id'].'">
			 <table border="0">
			 <tr>
			 <td colspan="2" align="center" style="font-size:16px; font-weight: bold;">'.$lang[33].' - <img src="'.$config ['site_url'].'images/languages/icon/'.$v.'.png"> '.$info_lang_for_db['name'].'</td>
			 </tr>
			 <tr>
			 <td>'.$lang[34].':</td>
			 <td><input type="text" name="name" value="'.$info_lang_for_db['name'].'"></td>
			 </tr>
			 <tr>
			 <td align="center" colspan="2"><input type="submit" name="submit" value="'.$lang[35].'" class="btn"></td>
			 </tr>
			 </table>
			 </form>
			</div>
			';
			$initialization = '<span style="color:green; font-weight: bold;">'.$lang[20].'</span>';
			if ($info_lang_for_db['active']==1)
			{
				$action = '
				<a href="index.php?do=language&action=off&id='.$info_lang_for_db['id'].'" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/red.png"><br>['.$lang[36].']</a>
				';
			} else {
				$action = '
				<a href="index.php?do=language&action=on&id='.$info_lang_for_db['id'].'" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/green.png"><br>['.$lang[37].']</a>
				';
			}
		} else {
			$modal .= '
			<div id="dialog'.$key.'" class="window" style="
				  background:url(./../images/admin/notice.png) no-repeat 0 0 transparent; 
			  width:326px; 
			  height:229px;
			  padding:50px 0 20px 25px;
				" align="center">
			 <form id="form_'.$key.'" action="" method="POST">
			 <input type="hidden" name="alt_name" value="'.$v.'">
			 <table border="0">
			 <tr>
			 <td colspan="2" align="center" style="font-size:16px; font-weight: bold;">'.$lang[38].' - <img src="'.$config ['site_url'].'images/languages/icon/'.$v.'.png"> '.$v.'</td>
			 </tr>
			 <tr>
			 <td>'.$lang[34].':</td>
			 <td><input type="text" name="name"></td>
			 </tr>
			 <tr>
			 <td align="center" colspan="2"><input type="submit" name="submit" value="'.$lang[38].'" class="btn"></td>
			 </tr>
			 </table>
			 </form>
			</div>
			';
			$initialization = '<span style="color:red">'.$lang[21].'</span><br><a href="#dialog'.$key.'" style="text-decoration:none" name="modal"><img src="'.$config ['site_url'].'images/admin/init.png">'.$lang[22].'</a>';
			$action = '
			<a href="#"><img src="'.$config ['site_url'].'images/admin/no_action.png"></a>
			';
		}
		$body_admin .= '
		<tr>
		 <td align="center" style="vertical-align:middle">'.($key+1).'</td> 
		 <td style="padding:0px 0px 0px 15px; vertical-align:middle">'.$name_lang.'</td> 
         <td align="center" style="vertical-align:middle"><img src="'.$config ['site_url'].'images/languages/icon/'.$v.'.png"></td> 
         <td align="center" style="font-weight: bold; vertical-align:middle">'.$v.'</td> 
         <td align="center" style="font-size:15px; vertical-align:middle">'.$initialization.'</td> 
         <td align="center" style="vertical-align:middle">'.$action.'</td> 
        </tr>
		';
		unset ($name_lang);
	}
	$modal .= '</div>';
	$modal = '<div id="boxes">
	'.$modal.'
	</div>
	<div id="mask"></div>
	';
	$body_admin .= '
	</tbody>
	</table>
	</div>
	';
}
//print_r ($array_languages_files);
?>
