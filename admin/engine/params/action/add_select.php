<?php
$other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[114];
if (isset ($_POST['name_params']))
{
    mysql_query ("START TRANSACTION;");
    if (!isset ($_POST['more_lang']))
    $_POST['more_lang'] = 0;
    mysql_query ("
                 INSERT INTO  `ls_params_select` (
                `more_lang` ,
                `multiselect` ,
		`only_text`
                )
                VALUES (
                '".$_POST['more_lang']."',
                '".$_POST['multiselect']."' ,
		'".$_POST['only_text']."'
                );
                 ");
    $id_new_param = mysql_insert_id();
    mysql_query ("
                 INSERT INTO  `ls_translate` (
                `id_lang` ,
                `id_elements` ,
                `text` ,
		`type`
                )
                VALUES (
                '".$id_online_lang."' ,
                '".$id_new_param."' ,
                '".$_POST['name_params']."' ,
		'select'
                );
                ");
    $id_new_param_translate = mysql_insert_id();
    mysql_query ("UPDATE `ls_params_select` set id_translate='".$id_new_param_translate."' where id='".$id_new_param."';");
    if (mysql_query ("COMMIT;"))
	{
		$body_admin .= '<div align="center" style="font-size:16; color:green">'.$lang[117].'</div>';
	} else {
		$body_admin .= '<div align="center" style="font-size:16; color:red">'.$lang[103].'</div>';
	}
}
$body_admin .= '
<style>
.form_params {
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
	<h2 id="title">'.$lang[73].' - "'.$lang[114].'"</h2>
	<form action="" method="POST">
        <input type="hidden" name="type_params" value="'.$_POST['type_params'].'">
		<table border="0">
			<tr>
				<td>'.$lang[85].':</td>
				<td><input type="text" name="name_params" class="form_params"></td>
			</tr>
			<tr>
				<td>'.$lang[86].':</td>
				<td>
                                    <input type="checkbox" name="more_lang" value="1" class="form_params">
                                    <a href="#"  title="'.$lang[87].'" displayclass="notice" class="tooltip" style="text-decoration:none;">
                                        <img src="'.$config ['site_url'].'images/admin/help_16.png">
                                    </a>
                                </td>
			</tr>
                        <tr>
				<td>'.$lang[115].':</td>
				<td>
                                    <input type="checkbox" name="multiselect" value="1" class="form_params">
                                    <a href="#"  title="'.$lang[116].'" displayclass="notice" class="tooltip" style="text-decoration:none;">
                                        <img src="'.$config ['site_url'].'images/admin/help_16.png">
                                    </a>
                                </td>
			</tr>
			<tr>
				<td>'.$lang[118].':</td>
				<td>
                                    <input type="checkbox" name="only_text" value="1" class="form_params">
                                    <a href="#"  title="'.$lang[119].'" displayclass="notice" class="tooltip" style="text-decoration:none;">
                                        <img src="'.$config ['site_url'].'images/admin/help_16.png">
                                    </a>
                                </td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" name="submit" value="'.$lang[45].'" class="form_params"></td>
			</tr>
		</table>
	</form>
</div>
';
?>