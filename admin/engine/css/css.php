<?php
$body_admin .= '
<div align="center">
<table border=0>
<tr>
<td align="center">
	<a href="index.php?do=css" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/view.png"><br>
	['.$lang[89].']</a>
</td>
<td align="center">
	<a href="index.php?do=css&action=add" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/add.png"><br>
	['.$lang[52].']</a>
</td>
</tr>
</table>
</div>
';
if (!isset ($_GET['action']))
{
	if (isset ($_POST['edit_style']))
	{
		$sql = "
			UPDATE ls_css set style='".$_POST['css_body']."' where id='".$_POST['id_edit']."';
		";
		if (mysql_query ($sql))
		{
			$body_admin .= '<div align="center" style="font-size:16; color:green">'.$lang[100].'</div>';
		} else {
			$body_admin .= '<div align="center" style="font-size:16; color:red">'.$lang[100].'</div>';
		}
	}
	if (isset ($_POST['edit']))
	{
		$sql = "
			UPDATE ls_css set name='".$_POST['name']."' where id='".$_POST['id_edit']."';
		";
		if (mysql_query ($sql))
		{
			$body_admin .= '<div align="center" style="font-size:16; color:green">'.$lang[100].'</div>';
		} else {
			$body_admin .= '<div align="center" style="font-size:16; color:red">'.$lang[100].'</div>';
		}
	}
	$all_css_style = return_all_css_style();
	if (count ($all_css_style)>0)
	{
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
			<th width="25">'.$lang[49].'</th> 
			<th>'.$lang[50].'</th> 
			<th>'.$lang[55].'</th> 
		</tr> 
		 </thead> 
		  <tbody> 
		';
		foreach ($all_css_style as $key=>$v)
		{
			$modal .= '
			<div id="dialog'.$key.'" class="window" style="
				  background:url(./../images/admin/notice.png) no-repeat 0 0 transparent; 
			  width:326px; 
			  height:229px;
			  padding:50px 0 20px 25px;
				" align="center">
			 <form id="form_'.$key.'" action="" method="POST">
			 <input type="hidden" name="edit" value="1">
			 <input type="hidden" name="id_edit" value="'.$v['id'].'">
			 <table border="0">
			 <tr>
			 <td colspan="2" align="center" style="font-size:16px; font-weight: bold;">'.$lang[98].'</td>
			 </tr>
			 <tr>
			 <td>'.$lang[99].':</td>
			 <td><input type="text" name="name" value="'.$v['name'].'" MAXLENGTH="32"  class="textform"><br>
			 </td>
			 </tr>
			 <tr>
			 <td align="center" colspan="2"><input type="submit" name="submit" value="'.$lang[45].'" class="btn"></td>
			 </tr>
			 </table>
			 </form>
			</div>
			<div id="edit_style_'.$key.'" class="window" style="
				  background:url(./../images/admin/notice.png) no-repeat 0 0 transparent; 
			  width:326px; 
			  height:229px;
			  padding:50px 0 20px 25px;
				" align="center">
			 <form id="form_'.$key.'" action="" method="POST">
			 <input type="hidden" name="edit_style" value="1">
			 <input type="hidden" name="id_edit" value="'.$v['id'].'">
			 <table border="0">
			 <tr>
			 <td colspan="2" align="center" style="font-size:16px; font-weight: bold;">'.$lang[98].'</td>
			 </tr>
			 <tr>
			 <td>'.$lang[101].':</td>
			 <td><textarea rows="4" cols="40" name="css_body" class="textform">'.$v['style'].'</textarea><br>
			 </td>
			 </tr>
			 <tr>
			 <td align="center" colspan="2"><input type="submit" name="submit" value="'.$lang[45].'" class="btn"></td>
			 </tr>
			 </table>
			 </form>
			</div>
			';
			$v['style'] = str_replace ("\r\n", "<br>", $v['style']);
			$body_admin .= '
			<tr>
			 <td align="center" style="vertical-align:middle">'.($key+1).'</td> 
			 <td style="padding:0px 0px 0px 15px; vertical-align:middle">'.$v['name'].' <a href="#dialog'.$key.'" style="text-decoration:none" name="modal"><img src="'.$config ['site_url'].'images/admin/edit.png"></a></td> 
			 <td align="left" style="vertical-align:middle" width="400">'.$v['style'].' <br><a href="#edit_style_'.$key.'" style="text-decoration:none" name="modal"><img src="'.$config ['site_url'].'images/admin/edit.png"></a></td> 
			</tr>
			';
		}
		$body_admin .='
		</tbody>
		</table>
		</div>
		';
		$modal .= '</div>';
		$modal = '<div id="boxes">
		'.$modal.'
		</div>
		<div id="mask"></div>
		';
	}
} else {
	switch ($_GET['action'])
	{
		case "add":
			require ('engine/css/action/add.php');
		break;
	}
}
$body_admin = '
<div id="info_admin_page">
<img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[90].$other_way.'
</div>
'.$body_admin;
?>