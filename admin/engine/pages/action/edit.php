<?php
$other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[206];
if (isset ($_POST['name_page']))
{
	$array_lang = $_POST['array_lang'];
	$name_page = $_POST['name_page'];
	$keywords = $_POST['keywords'];
	$description = $_POST['description'];
	$title = $_POST['title'];
	$text = $_POST['text'];
	$name_hidden = $_POST['name_hidden'];
	$keywords_hidden = $_POST['keywords_hidden'];
	$description_hidden = $_POST['description_hidden'];
	$title_hidden = $_POST['title_hidden'];
	$text_hidden = $_POST['text_hidden'];
	$id_static_page = $_GET['id'];
	foreach ($array_lang as $key => $v)
	{
		$sql = "
		update `ls_translate` set text='".mysql_escape_string($name_page[$key])."' where `id` = '".$name_hidden[$key]."';
		";
		mysql_query($sql);
		$sql = "
		update `ls_translate` set text='".mysql_escape_string($keywords[$key])."' where `id` = '".$keywords_hidden[$key]."';
		";
		mysql_query($sql);
		$sql = "
		update `ls_translate` set text='".mysql_escape_string($description[$key])."' where `id` = '".$description_hidden[$key]."';
		";
		mysql_query($sql);
		$sql = "
		update `ls_translate` set text='".mysql_escape_string($title[$key])."' where `id` = '".$title_hidden[$key]."';
		";
		mysql_query($sql);
		//print "<br>";
		$sql = "
		update `ls_translate` set text='".mysql_escape_string($text[$key])."' where `id` = '".$text_hidden[$key]."';
		";
		//print $text_hidden[$key]."<br>";
		mysql_query($sql);
		$true_html = '<span style="color:green; font-size:16px;">'.$lang[203].'</span>';
	}
}
$body_admin .= '
<h2 id="title">'.$lang[196].'</h2>
'.$true_html.'
<script>
        $(document).ready(function() {
          $(\'.textEditBody\').summernote({
                  height: 600,
                  focus: true ,
                  onImageUpload: function(files, editor, welEditable) {
                    sendFile(files[0],editor,welEditable);
                   }
                });
        });
    </script>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" />
<form action="" method="POST">
<div align="center">
<table border="0">
';
$all_lang = return_all_ok_lang();
foreach ($all_lang as $lang_page)
{
	$info_name_page = return_one_translate ($_GET['id'], $lang_page['id'], 'static_page_nam');
	if ($info_name_page['id_lang']!=$lang_page['id'])
	{
		unset ($info_name_page);
		$sql = "
		INSERT INTO  `ls_translate` (
		`type` ,
		`id_lang` ,
		`id_elements` ,
		`text`
		)
		VALUES (
		'static_page_nam',
		'".$lang_page['id']."' ,
		'".$_GET['id']."' ,
		''
		);
		";
		mysql_query($sql);
		$info_name_page['id'] = mysql_insert_id();
	}
	$html_name_page .= '
	<b>'.$lang_page['name'].'</b><br>
	<input type="text" name="name_page[]" style="border:1px black solid; background-color:#FFFAE8;" value="'.$info_name_page['text'].'"><br>
	<input type="hidden" name="name_hidden[]" value="'.$info_name_page['id'].'">
	';
	unset ($info_name_page);
	$info_key_words = return_one_translate ($_GET['id'], $lang_page['id'], 'static_page_key');
	if ($info_key_words['id_lang']!=$lang_page['id'])
	{
		unset ($info_key_words);
		$sql = "
		INSERT INTO  `ls_translate` (
		`type` ,
		`id_lang` ,
		`id_elements` ,
		`text`
		)
		VALUES (
		'static_page_key',
		'".$lang_page['id']."' ,
		'".$_GET['id']."' ,
		''
		);
		";
		mysql_query($sql);
		$info_key_words['id'] = mysql_insert_id();
	}
	$html_keywords .= '
	<b>'.$lang_page['name'].'</b><br>
	<input type="text" name="keywords[]" style="border:1px black solid; background-color:#FFFAE8" value="'.$info_key_words['text'].'"><br>
	<input type="hidden" name="keywords_hidden[]" value="'.$info_key_words['id'].'">
	';
	unset ($info_key_words);
	$info_description = return_one_translate ($_GET['id'], $lang_page['id'], 'static_page_des');
	if ($info_description['id_lang']!=$lang_page['id'])
	{
		unset ($info_description);
		$sql = "
		INSERT INTO  `ls_translate` (
		`type` ,
		`id_lang` ,
		`id_elements` ,
		`text`
		)
		VALUES (
		'static_page_des',
		'".$lang_page['id']."' ,
		'".$_GET['id']."' ,
		''
		);
		";
		mysql_query($sql);
		$info_description['id'] = mysql_insert_id();
	}
	$html_description .= '
	<b>'.$lang_page['name'].'</b><br>
	<input type="text" name="description[]" style="border:1px black solid; background-color:#FFFAE8" value="'.$info_description['text'].'"><br>
	<input type="hidden" name="description_hidden[]" value="'.$info_description['id'].'">
	';
	unset ($info_description);
	$info_title = return_one_translate ($_GET['id'], $lang_page['id'], 'static_page_tit');
	if ($info_title['id_lang']!=$lang_page['id'])
	{
		unset ($info_title);
		$sql = "
		INSERT INTO  `ls_translate` (
		`type` ,
		`id_lang` ,
		`id_elements` ,
		`text`
		)
		VALUES (
		'static_page_tit',
		'".$lang_page['id']."' ,
		'".$_GET['id']."' ,
		''
		);
		";
		mysql_query($sql);
		$info_title['id'] = mysql_insert_id();
	}
	$html_title .= '
	<b>'.$lang_page['name'].'</b><br>
	<input type="text" name="title[]" style="border:1px black solid; background-color:#FFFAE8" value="'.$info_title['text'].'"><br>
	<input type="hidden" name="title_hidden[]" value="'.$info_title['id'].'">
	';
	unset ($info_title);
	$info_text = return_one_translate ($_GET['id'], $lang_page['id'], 'static_page_tex');
	if ($info_text['id_lang']!=$lang_page['id'])
	{
		unset ($info_text);
		$sql = "
		INSERT INTO  `ls_translate` (
		`type` ,
		`id_lang` ,
		`id_elements` ,
		`text`
		)
		VALUES (
		'static_page_tex',
		'".$lang_page['id']."' ,
		'".$_GET['id']."' ,
		''
		);
		";
		mysql_query($sql);
		$info_text['id'] = mysql_insert_id();
	}
	$html_body .= '
	<b>'.$lang_page['name'].'</b><br>
	<textarea name="text[]" class="mceEditor textEditBody">'.$info_text['text'].'</textarea><br>
	<input type="hidden" name="text_hidden[]" value="'.$info_text['id'].'">
	';
	unset ($info_text);
	$html_lang_value .= '<input type="hidden" name="array_lang[]" value="'.$lang_page['id'].'">';
}
$body_admin .= '
	<tr>
		<td valign="top">'.$lang[197].'</td>
		<td>'.$html_name_page.'</td>
	</tr>
	<tr>
		<td valign="top">'.$lang[200].'</td>
		<td>'.$html_keywords.'</td>
	</tr>
	<tr>
		<td valign="top">'.$lang[198].'</td>
		<td>'.$html_description.'</td>
	</tr>
	<tr>
		<td valign="top">'.$lang[199].'</td>
		<td>'.$html_title.'</td>
	</tr>
	<tr>
		<td colspan="2" align="center"><h3>'.$lang[201].'</h3></td>
	</tr>
	<tr>
		<td colspan="2">'.$html_body.'</td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="'.$lang[202].'" style="border:1px black solid; background-color:#FFFAE8;"></td>
	</tr>
	</table>
'.$html_lang_value.'
</div>
</form>
';
?>