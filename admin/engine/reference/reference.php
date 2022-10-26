<?php
$body_admin .= '
<div align="center">
<table border=0>
<tr>
<td align="center">
	<a href="index.php?do=reference" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/view.png"><br>
	['.$lang[51].']</a>
</td>
<td align="center">
	<a href="index.php?do=reference&action=add" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/add.png"><br>
	['.$lang[52].']</a>
</td>
</tr>
</table>
</div>
';
/*
значения и переводы
<td align="center">
	<a href="index.php?do=reference&action=translate" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/aktion.png"><br>
	['.$lang[56].']</a>
</td>
<td align="center">
	<a href="index.php?do=reference&action=translate" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/config-language.png"><br>
	['.$lang[53].']</a>
</td>
*/
if (!isset ($_GET['action']))
{
	if (isset ($_POST['edit']))
	{
		$sql = "
			UPDATE ls_reference set name='".$_POST['name']."' where id='".$_POST['id_edit']."';
		";
		if (mysql_query ($sql))
		{
			$body_admin .= '<div align="center" style="font-size:16; color:green">'.$lang[58].'</div>';
		} else {
			$body_admin .= '<div align="center" style="font-size:16; color:red">'.$lang[59].'</div>';
		}
	}
	if (isset ($_GET['del']))
	{
		$sql = "delete from ls_reference where id='".$_GET['del']."';";
		mysql_query ($sql);
		$results = mysql_query("SELECT * FROM ls_reference_values where id_reference='".$id_params."';");
		$number = @mysql_num_rows ($results);
		if ($number>0)
		{
			for ($i=0; $i<$number; $i++)
			{	
				$array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
			}	
		}
		if (count ($array))
		{
			foreach ($array as $v)
			{
				$sql = "delete from ls_reference_values_translate where id_reference_value='".$v['id']."';";
				mysql_query ($sql);
			}
		}
		$sql = "delete from ls_reference_values where id_reference='".$_GET['del']."';";
		mysql_query($sql);
	}
	if (isset ($_POST['add_values']))
	{
		$values = $_POST['values'];
		$id_referece = $_POST['id_reference'];
		$id_lang = $_POST['lang_id'];
		$array_values = explode ("\r\n", $values);
		foreach ($array_values as $v)
		{
			$v = str_replace ("\r\n", "", $v);
			$sql = "
			INSERT INTO  `ls_reference_values` (
			`id_reference` 
			)
			VALUES (
			'".$id_referece."' 
			);
			";
			$error = 0;
			if (mysql_query($sql))
			{
				$good = 1;
			} else {
				$error = 1;
			}
			$id_last_query = mysql_insert_id();
			$sql = "
			INSERT INTO  `ls_reference_values_translate` (
			`id_lang` ,
			`id_reference_value` ,
			`value`
			)
			VALUES (
			'".$id_lang."' ,
			'".$id_last_query."' , 
			'".$v."'
			);
			";
			if (mysql_query($sql))
			{
				$good = 1;
			} else {
				$error = 1;
			}
		}
		if ($error!=1)
		{
			$body_admin .= '<div align="center" style="font-size:16; color:green">'.$lang[63].'</div>';
		}
	}
	$array_reference = return_all_reference () ;
	if (count($array_reference)>0)
	{
		$body_admin .='
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
		foreach ($array_reference as $key=>$v)
		{
			unset ($values_reference);
			$array_values_for_reference = return_all_values_for_reference($v['id']);
			if ($array_values_for_reference != 0)
			{
				if (!isset ($values_reference))
				$values_reference = '';
				foreach ($array_values_for_reference as $key_value=>$value_one_reference)
				{
					$info_translate_value_ref = return_all_translate_for_reference_value($value_one_reference['id'], $id_online_lang);
					if (count($info_translate_value_ref[0])>0)
					{
						$values_reference .= $info_translate_value_ref[0]['value'];
						if (isset ($array_values_for_reference[$key_value+1]))
						{
							$values_reference .= ', ';
						} else {
							$values_reference .= ' <a href="index.php?do=reference&action=translate&id='.$v['id'].'" style="text-decoration:none;"><img src="'.$config ['site_url'].'images/admin/locale_add.png"></a>';
						}
					} else {
						$no_translate = 'Немає перекладу значень <a href="index.php?do=reference&action=translate&id='.$v['id'].'" style="text-decoration:none;"><img src="'.$config ['site_url'].'images/admin/locale_add.png"></a>';
					}
				}
				if (isset ($no_translate))
				$values_reference .= $no_translate;
			} else {
				$values_reference .= '
				Немає значень, або можливо немає перекладу значень на цю мову
				';
			}
			$values_reference .= ' <br>
			<a href="#add_values'.$key.'" style="text-decoration:none" name="modal"><img src="'.$config ['site_url'].'images/admin/add_16.png"></a>
			<a href="index.php?do=reference&action=remove_value&id='.$v['id'].'" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/remove_16.png"></a>
			';
			$body_admin .= '
			<tr>
			 <td align="center" style="vertical-align:middle">'.$v['id'].'</td> 
			 <td style="padding:0px 0px 0px 15px; vertical-align:middle">'.$v['name'].' <a href="#dialog'.$key.'" style="text-decoration:none" name="modal"><img src="'.$config ['site_url'].'images/admin/edit.png"></a>
			 <a href="index.php?do=reference&del='.$v['id'].'" onclick="return confirm (\''.$lang[69].'\')"><img src="'.$config ['site_url'].'images/admin/remove_16.png"></a></td> 
			 <td align="center" style="vertical-align:middle" width="400">'.$values_reference.'</td> 
			</tr>
			';
			$option_language = option_lang_on ();
			$modal .= '
			<div id="add_values'.$key.'" class="window" style="
				  background:url(./../images/admin/notice.png) no-repeat 0 0 transparent; 
			  width:326px; 
			  height:229px;
			  padding:30px 0 10px 15px;
				" align="center">
			 <form id="form_add_values_'.$key.'" action="" method="POST">
			 <input type="hidden" name="add_values" value="1">
			 <input type="hidden" name="id_reference" value="'.$v['id'].'">
			 <table border="0">
			 <tr>
			 <td colspan="2" align="center" style="font-size:14px; font-weight: bold; padding:0px 0px 0px 0px;">'.$lang[57].'</td>
			 </tr>
			   <tr>
			 <td style="padding:0px 0px 0px 0px;" align="right">'.$lang[61].':</td>
			 <td  style="padding:0px 0px 0px 0px;">
			 <select name="lang_id">
			 '.$option_language.'
			 </select>
			 </td>
			 </tr>
			 <tr>
			 <td colspan="2" align="center"  style="padding:0px 0px 0px 0px;"><textarea name="values" cols=35 rows=4 class="textform"></TEXTAREA><br>
			 <span style="color:red; font-size:10px" align="left">'.$lang[62].'</span>
			 </td>
			 </tr>
			 <tr>
			 <td align="center" colspan="2"  style="padding:0px 0px 0px 0px;"><input type="submit" name="submit" value="'.$lang[45].'" class="btn"></td>
			 </tr>
			 </table>
			 </form>
			</div>
			';			
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
			 <td colspan="2" align="center" style="font-size:16px; font-weight: bold;">'.$lang[57].'</td>
			 </tr>
			 <tr>
			 <td>'.$lang[44].':</td>
			 <td><input type="text" name="name" value="'.$v['name'].'" MAXLENGTH="32"  class="textform"><br>
			 <span style="color:red">'.$lang[47].'</span>
			 </td>
			 </tr>
			 <tr>
			 <td align="center" colspan="2"><input type="submit" name="submit" value="'.$lang[45].'" class="btn"></td>
			 </tr>
			 </table>
			 </form>
			</div>
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
	} else {
		$body_admin .= '
		<span style="color:red; font-weight:bold;">'.$lang[501].'</span>
		';
	}
} else {
	switch($_GET['action'])
	{
		case "add":
		require_once ('engine/reference/action/add.php');
		break;
		case "translate":
		require_once('engine/reference/action/translate.php');
		break;
		case "remove_value":
		require_once('engine/reference/action/remove_value.php');
		break;
	}
}
$body_admin = '
<div id="info_admin_page">
<img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[43].$other_way.'
</div>
'.$body_admin;
?>