<?php
$other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[124];
$info_select_params = return_one_select_params ($_GET['id']);
$body_admin .= '<h2 id="title" align="center">'.$lang[125].'</h2>';
if (!isset ($_POST['values_type']))
{
	if ($info_select_params['only_text']!=1)
	{
		$html_image_radio = '
		<tr>
			<td>'.$lang[127].'</td>
			<td><input type="radio" name="values_type" value="0"></td>
		</tr>
		';
	}
	$body_admin .= '
	<style>
		.params_form {
			color:#000000; 
			font: 84% \'trebuchet ms\',helvetica,sans-serif; 
			background-color:#fed; 
			border: 1px solid; 
			font-size:12px;
			border-color: #696 #363 #363 #696; 
			filter:progid:DXImageTransform.Microsoft.Gradient 
			(GradientType=0,StartColorStr=\'#ffffffff\',EndColorStr=\'#ffeeddaa\'); 
		}
		</style>
	<form action="" method="POST">
		<div align="center">
			<table border="0">
				<tr>
					<td>'.$lang[126].'</td>
					<td><input type="radio" name="values_type" value="1" checked></td>
				</tr>
				'.$html_image_radio.'
				<tr>
					<td colspan="2" align="center"><input type="submit" name="submit" value="'.$lang[84].'" class="params_form"></td>
				</tr>
			</table>
		</div>
	</form>
	';
} else {
	switch ($_POST['values_type'])
	{
                case 0:
                    if (!$number_text_params = chek_values_select_text ($_GET['id'], 1))
                    {
                        $body_admin .= '
                        <script type="text/javascript">
                        $(document).ready(function(){
                                  
                        $(\'.MultiFile\').MultiFile({ 
                                accept:\'JPG|GIF|PNG|jpg|gif|png\', max:15, STRING: { 
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
                                        $out.html(\''.$lang[137].' <a href="index.php?do=params">'.$lang[139].'</a>\');
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
                            <form id="uploadForm" action="engine/params/action/doajaxfileupload.php" method="post" enctype="multipart/form-data">
                            <input name="MAX_FILE_SIZE" value="10000000" type="hidden"/>
                            <input name="fileToUpload[]" id="fileToUpload" class="MultiFile" type="file"/>
                            <input name="id_select" type="hidden" value="'.$_GET['id'].'"/>
                            <input value="Submit" type="submit"/>
                            </form>
                            <img id="loading" src="'.$config ['site_url'].'images/admin/loading.gif" style="display:none;"/>   
                            <div id="uploadOutput"></div>
                        </div>
                        ';
                    } else {
                        $body_admin .= $lang[132];
                    }
                break;
		case 1:
                    if (!$number_text_params = chek_values_select_text ($_GET['id'], 0))
                    {
			if (!isset ($_POST['values']))
			{
				$info_select_param = return_one_select_params ($_GET['id']);
				if ($info_select_param['parent_id'])
				{
					$true_values_select_params = return_all_select_values_params($info_select_param['parent_id']);
					if ($true_values_select_params)
					{
						if (!isset ($html_option))
						$html_option = '';
						foreach ($true_values_select_params as $v_value)
						{
							$info_translate = return_translate_for_id_elements_values_select ($v_value['id'], $id_online_lang);
							$html_option .= '<option value="'.$v_value['id'].'">'.$info_translate['text'].'</option>';
						}
						$body_admin .= '<table border="0"><tr><td valign="top">'.$lang[304]."
						<select id=\"parent_param_id\" onchange=\"go_params('".$_GET['id']."')\">
						<option value=\"0\">".$lang[305]."</option>
						".$html_option."
						</select>
						<br>
						<div id=\"status_save\"></div>
						</td>
						<td>
							<div id=\"textarea_param\"></div>
						</td>
						</tr>
						</table>";
					}
				} else {
					$body_admin .= '
					<form action="" method="POST">
					<input type="hidden" name="values_type" value="'.$_POST['values_type'].'">
					<div align="center">
						<table border="0">
							<tr>
								<td valign="top" align="center"><b>'.$lang[120].'</b></td>
							</tr>
							<tr>
								<td>
									<textarea name="values" cols="50" rows="10"></textarea><br>
									<span style="color:red">'.$lang[128].'</span>
								</td>
							</tr>
							<tr>
								<td align="center"><input type="submit" name="submit" value="'.$lang[45].'" class="params_form"></td>
							</tr>
						</table>
					</div>
					</form>
					';
				}
			} else {
				$values = $_POST['values'];
				$array_values = explode ("\r\n", $values);
				foreach ($array_values as $v)
				{
				    mysql_query ("START TRANSACTION;");
                                    mysql_query ("INSERT INTO  `ls_params_select_values` (
                                    `id_params` ,
                                    `type_value`
                                    )
                                    VALUES (
                                    '".$_GET['id']."' ,
                                    '".$_POST['values_type']."'
                                    );");
                                    $id_new_param = mysql_insert_id();
                                    mysql_query ("
                                    INSERT INTO  `ls_translate` (
                                    `id_lang` ,
                                    `id_elements` ,
                                    `text` ,
                                    `type`
                                    ) VALUES (
                                    '".$id_online_lang."' ,
                                    '".$id_new_param."' ,
                                    '".$v."' ,
                                    'select_value'
                                    );
                                    ");
                                    $id_new_param_translate = mysql_insert_id();
                                    mysql_query ("UPDATE `ls_params_select_values` set id_translate='".$id_new_param_translate."' where id='".$id_new_param."';");
                                    if (mysql_query ("COMMIT;"))
                                    {
                                            $body_admin .= '<div align="center" style="font-size:16; color:green">'.$lang[120].' "'.$v.'" '.$lang[129].'</div><br>';
                                    } else {
                                            $body_admin .= '<div align="center" style="font-size:16; color:red">'.$lang[130].' "'.$v.'"</div><br>';
                                    }
    				}
			}
                    } else {
                        $body_admin .= $lang[132];
                    }
		break;
	}
}
?>