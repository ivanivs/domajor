<?php
$other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[111];
switch ($_GET['type'])
{
    case "text":
        if (isset ($_POST['text_translate']))
        {
            $id_translate_text_param = $_POST['id_translate_text_param'];
            $id_lang = $_POST['id_lang'];
            $id_params = $_POST['id_params'];
            foreach ($_POST['text_translate'] as $key=>$v)
            {
                if (strlen($id_translate_text_param[$key])>0)
                {
                    $sql = "UPDATE ls_translate set text='".$v."' where id='".$id_translate_text_param[$key]."';";
                    mysql_query ($sql);
                } else {
                    mysql_query ("
                        INSERT INTO  `ls_translate` (
                       `id_lang` ,
                       `id_elements` ,
                       `text` ,
                       `type`
                       )
                       VALUES (
                       '".$id_lang[$key]."' ,
                       '".$id_params[$key]."' ,
                       '".$v."' ,
                       'text'
                       );
                    ");
                }
            }
        }
        $body_admin .= '<h2>'.$lang[112].'</h2>';
        $array_true_lang = return_all_ok_lang ();
        if (count ($array_true_lang)>0)
	{
            $body_admin .= '
            <style>
            .translate_form { 
             font-size: 10px; 
             background-color: #C2ECFF; 
             border: 1px solid #666666; 
            }
            </style>
            <div align="left" style="font-size:16px; color:#227399">'.$info_reference['name'].'</div>
            <form action="" method="POST">
            <div align="center">
            <table cellspacing="1" class="tablesorter">
            <thead>
            <tr>';
            foreach ($array_true_lang as $v_lang)
            {
                    $body_admin .= '<th>'.$v_lang['name'].'</th>';
            }
            $body_admin .= '
            </tr> 
            </thead> 
            <tbody>
            <tr>
            ';
            foreach ($array_true_lang as $v_lang)
            {
                    $array_info_lang_text_param = return_name_for_id_text_param ($v_lang['id'], $_GET['id']);
                    $body_admin .= '
                    <td align="center"><input type="text" name="text_translate[]" value="'.$array_info_lang_text_param['text'].'" class="translate_form">
                    <input type="hidden" name="id_translate_text_param[]" value="'.$array_info_lang_text_param['id'].'">
                    <input type="hidden" name="id_lang[]" value="'.$v_lang['id'].'">
                    <input type="hidden" name="id_params[]" value="'.$_GET['id'].'">
                    </td>
                    ';
            }
            $body_admin .='
             </tr>
            </tbody>
            </table>
            </div>
            <center><input type="submit" name="submit" value="'.$lang[45].'" class="translate_form"></center>
            </form>
            ';
	}
    break;
    case "price":
	 if (isset ($_POST['text_translate']))
        {
            $id_translate_text_param = $_POST['id_translate_text_param'];
            $id_lang = $_POST['id_lang'];
            $id_params = $_POST['id_params'];
            foreach ($_POST['text_translate'] as $key=>$v)
            {
                if (strlen($id_translate_text_param[$key])>0)
                {
                    $sql = "UPDATE ls_translate set text='".$v."' where id='".$id_translate_text_param[$key]."';";
                    mysql_query ($sql);
                } else {
                    mysql_query ("
                        INSERT INTO  `ls_translate` (
                       `id_lang` ,
                       `id_elements` ,
                       `text` ,
                       `type`
                       )
                       VALUES (
                       '".$id_lang[$key]."' ,
                       '".$id_params[$key]."' ,
                       '".$v."' ,
                       'price'
                       );
                    ");
                }
            }
        }
        $body_admin .= '<h2>'.$lang[150].'</h2>';
        $array_true_lang = return_all_ok_lang ();
        if (count ($array_true_lang)>0)
	{
            $body_admin .= '
            <style>
            .translate_form { 
             font-size: 10px; 
             background-color: #C2ECFF; 
             border: 1px solid #666666; 
            }
            </style>
            <div align="left" style="font-size:16px; color:#227399">'.$info_reference['name'].'</div>
            <form action="" method="POST">
            <div align="center">
            <table cellspacing="1" class="tablesorter">
            <thead>
            <tr>';
            foreach ($array_true_lang as $v_lang)
            {
                    $body_admin .= '<th>'.$v_lang['name'].'</th>';
            }
            $body_admin .= '
            </tr> 
            </thead> 
            <tbody>
            <tr>
            ';
            foreach ($array_true_lang as $v_lang)
            {
                    $array_info_lang_text_param = return_name_for_id_price_param ($v_lang['id'], $_GET['id']);
                    $body_admin .= '
                    <td align="center"><input type="text" name="text_translate[]" value="'.$array_info_lang_text_param['text'].'" class="translate_form">
                    <input type="hidden" name="id_translate_text_param[]" value="'.$array_info_lang_text_param['id'].'">
                    <input type="hidden" name="id_lang[]" value="'.$v_lang['id'].'">
                    <input type="hidden" name="id_params[]" value="'.$_GET['id'].'">
                    </td>
                    ';
            }
            $body_admin .='
             </tr>
            </tbody>
            </table>
            </div>
            <center><input type="submit" name="submit" value="'.$lang[45].'" class="translate_form"></center>
            </form>
            ';
	}
    break;
    case "boolean":
	 if (isset ($_POST['text_translate']))
        {
            $id_translate_text_param = $_POST['id_translate_text_param'];
            $id_lang = $_POST['id_lang'];
            $id_params = $_POST['id_params'];
            foreach ($_POST['text_translate'] as $key=>$v)
            {
                if (strlen($id_translate_text_param[$key])>0)
                {
                    $sql = "UPDATE ls_translate set text='".$v."' where id='".$id_translate_text_param[$key]."';";
                    mysql_query ($sql);
                } else {
                    mysql_query ("
                        INSERT INTO  `ls_translate` (
                       `id_lang` ,
                       `id_elements` ,
                       `text` ,
                       `type`
                       )
                       VALUES (
                       '".$id_lang[$key]."' ,
                       '".$id_params[$key]."' ,
                       '".$v."' ,
                       'boolean'
                       );
                    ");
                }
            }
        }
        $body_admin .= '<h2>'.$lang[166].'</h2>';
        $array_true_lang = return_all_ok_lang ();
        if (count ($array_true_lang)>0)
	{
            $body_admin .= '
            <style>
            .translate_form { 
             font-size: 10px; 
             background-color: #C2ECFF; 
             border: 1px solid #666666; 
            }
            </style>
            <div align="left" style="font-size:16px; color:#227399">'.$info_reference['name'].'</div>
            <form action="" method="POST">
            <div align="center">
            <table cellspacing="1" class="tablesorter">
            <thead>
            <tr>';
            foreach ($array_true_lang as $v_lang)
            {
                    $body_admin .= '<th>'.$v_lang['name'].'</th>';
            }
            $body_admin .= '
            </tr> 
            </thead> 
            <tbody>
            <tr>
            ';
            foreach ($array_true_lang as $v_lang)
            {
                    $array_info_lang_text_param = return_name_for_id_boolean_param ($v_lang['id'], $_GET['id']);
                    $body_admin .= '
                    <td align="center"><input type="text" name="text_translate[]" value="'.$array_info_lang_text_param['text'].'" class="translate_form">
                    <input type="hidden" name="id_translate_text_param[]" value="'.$array_info_lang_text_param['id'].'">
                    <input type="hidden" name="id_lang[]" value="'.$v_lang['id'].'">
                    <input type="hidden" name="id_params[]" value="'.$_GET['id'].'">
                    </td>
                    ';
            }
            $body_admin .='
             </tr>
            </tbody>
            </table>
            </div>
            <center><input type="submit" name="submit" value="'.$lang[45].'" class="translate_form"></center>
            </form>
            ';
	}
    break;
    case "image":
	 if (isset ($_POST['text_translate']))
        {
            $id_translate_text_param = $_POST['id_translate_text_param'];
            $id_lang = $_POST['id_lang'];
            $id_params = $_POST['id_params'];
            foreach ($_POST['text_translate'] as $key=>$v)
            {
                if (strlen($id_translate_text_param[$key])>0)
                {
                    $sql = "UPDATE ls_translate set text='".$v."' where id='".$id_translate_text_param[$key]."';";
                    mysql_query ($sql);
                } else {
                    mysql_query ("
                        INSERT INTO  `ls_translate` (
                       `id_lang` ,
                       `id_elements` ,
                       `text` ,
                       `type`
                       )
                       VALUES (
                       '".$id_lang[$key]."' ,
                       '".$id_params[$key]."' ,
                       '".$v."' ,
                       'image'
                       );
                    ");
                }
            }
        }
        $body_admin .= '<h2>'.$lang[163].'</h2>';
        $array_true_lang = return_all_ok_lang ();
        if (count ($array_true_lang)>0)
	{
            $body_admin .= '
            <style>
            .translate_form { 
             font-size: 10px; 
             background-color: #C2ECFF; 
             border: 1px solid #666666; 
            }
            </style>
            <div align="left" style="font-size:16px; color:#227399">'.$info_reference['name'].'</div>
            <form action="" method="POST">
            <div align="center">
            <table cellspacing="1" class="tablesorter">
            <thead>
            <tr>';
            foreach ($array_true_lang as $v_lang)
            {
                    $body_admin .= '<th>'.$v_lang['name'].'</th>';
            }
            $body_admin .= '
            </tr> 
            </thead> 
            <tbody>
            <tr>
            ';
            foreach ($array_true_lang as $v_lang)
            {
                    $array_info_lang_text_param = return_name_for_id_image_param ($v_lang['id'], $_GET['id']);
                    $body_admin .= '
                    <td align="center"><input type="text" name="text_translate[]" value="'.$array_info_lang_text_param['text'].'" class="translate_form">
                    <input type="hidden" name="id_translate_text_param[]" value="'.$array_info_lang_text_param['id'].'">
                    <input type="hidden" name="id_lang[]" value="'.$v_lang['id'].'">
                    <input type="hidden" name="id_params[]" value="'.$_GET['id'].'">
                    </td>
                    ';
            }
            $body_admin .='
             </tr>
            </tbody>
            </table>
            </div>
            <center><input type="submit" name="submit" value="'.$lang[45].'" class="translate_form"></center>
            </form>
            ';
	}
    break;
    case "select":
	switch ($_GET['type_2'])
	{
	    case "param":
		$body_admin .= '<h2>'.$lang[122].'</h2>';
		$array_true_lang = return_all_ok_lang ();
		if (isset ($_POST['text_translate']))
		{
		    $id_translate_select_param = $_POST['id_translate_select_param'];
		    $id_lang = $_POST['id_lang'];
		    $id_params = $_POST['id_params'];
			//print_r ($_POST['text_translate']);
		    foreach ($_POST['text_translate'] as $key=>$v)
		    {
				if (strlen($id_translate_select_param[$key])>0)
				{
					$sql = "UPDATE ls_translate set text='".$v."' where id='".$id_translate_select_param[$key]."';";
					mysql_query ($sql);
				} else {
					mysql_query ("
					INSERT INTO  `ls_translate` (
					   `id_lang` ,
					   `id_elements` ,
					   `text` ,
					   `type`
					   )
					   VALUES (
					   '".$id_lang[$key]."' ,
					   '".$id_params[$key]."' ,
					   '".$v."' ,
					   'select'
					   );
					");
				}
		    }
		}
		if (count ($array_true_lang)>0)
		{
		    $body_admin .= '
		    <style>
		    .translate_form { 
		     font-size: 10px; 
		     background-color: #C2ECFF; 
		     border: 1px solid #666666; 
		    }
		    </style>
		    <div align="left" style="font-size:16px; color:#227399">'.$info_reference['name'].'</div>
		    <form action="" method="POST">
		    <div align="center">
		    <table cellspacing="1" class="tablesorter">
		    <thead>
		    <tr>';
		    foreach ($array_true_lang as $v_lang)
		    {
			    $body_admin .= '<th>'.$v_lang['name'].'</th>';
		    }
		    $body_admin .= '
		    </tr> 
		    </thead> 
		    <tbody>
		    <tr>
		    ';
		    foreach ($array_true_lang as $v_lang)
		    {
			    $array_info_lang_select_param = return_name_for_id_select_param ($v_lang['id'], $_GET['id']);
			    $body_admin .= '
			    <td align="center"><input type="text" name="text_translate[]" value="'.$array_info_lang_select_param['text'].'" class="translate_form">
			    <input type="hidden" name="id_translate_select_param[]" value="'.$array_info_lang_select_param['id'].'">
			    <input type="hidden" name="id_lang[]" value="'.$v_lang['id'].'">
			    <input type="hidden" name="id_params[]" value="'.$_GET['id'].'">
			    </td>
			    ';
		    }
		    $body_admin .='
		     </tr>
		    </tbody>
		    </table>
		    </div>
		    <center><input type="submit" name="submit" value="'.$lang[45].'" class="translate_form"></center>
		    </form>
		    ';
		}
	    break;
	    case "value":
		$body_admin .= '<h2>'.$lang[131].'</h2>';
		if (isset ($_POST['info_translate']))
		{
		    $info_translate = $_POST['info_translate'];
		    $id_translate_value_select_value = $_POST['id_translate_value_select_value'];
		    $id_lang_value_select_value = $_POST['id_lang_value_select_value'];
		    $id_select_value = $_POST['id_select_value'];
		    foreach ($info_translate as $key=>$v)
		    {
			    if (strlen($id_translate_value_select_value[$key])>0)
			    {
				    $sql = "
				    UPDATE ls_translate set text='".$v."' where id='".$id_translate_value_select_value[$key]."';";
				    if (mysql_query($sql))
				    {
					    $query_good = 1;
				    } else {
					   $bad_query = 1;
				    }
			    }else {
				    $sql = "
				    INSERT INTO  `ls_translate` (
				    `id_lang` ,
				    `id_elements` ,
				    `text` ,
				    `type`
				    )
				    VALUES (
				    '".$id_lang_value_select_value[$key]."' ,
				    '".$id_select_value[$key]."' , 
				    '".$v."' ,
				    'select_value'
				    );
				    ";
				    if (mysql_query($sql))
				    {
					    $query_good = 1;
				    } else {
					    $bad_query = 1;
				    }
			    }
		    }
		    if (!isset ($bad_query))
		    $bad_query = '';
		    if ($bad_query!=1)
		    {
			    $body_admin .= '<div align="center" style="font-size:16; color:green">'.$lang[66].'</div>';
		    } else {
			    $body_admin .= '<div align="center" style="font-size:16; color:red">'.$lang[67].'</div>';
		    }
		}
		$array_true_lang = return_all_ok_lang ();
		if (count ($array_true_lang)>0)
		{
		    $true_values_select_params = return_all_select_values_params($_GET['id']);
		    if ($true_values_select_params)
		    {
				$body_admin .= '
				<style>
				.translate_form { 
				 font-size: 10px; 
				 background-color: #C2ECFF; 
				 border: 1px solid #666666; 
				}
				</style>
				<form action="" method="POST">
				<div align="center">
				<table cellspacing="1" class="tablesorter">
				<thead>
				<tr>';
				foreach ($array_true_lang as $v_lang)
				{
					$body_admin .= '<th>'.$v_lang['name'].'</th>';
				}
				$body_admin .= '
				</tr> 
				</thead> 
				<tbody>
				';
				foreach ($true_values_select_params as $v_value)
				{
					$body_admin .= '
					<tr>
					';
					foreach ($array_true_lang as $v_lang)
					{
						$info_translate = return_translate_for_id_elements_values_select ($v_value['id'], $v_lang['id']);
						$body_admin .= '
						<td align="center"><input type="text" name="info_translate[]" value="'.$info_translate['text'].'" class="translate_form">
						<input type="hidden" name="id_translate_value_select_value[]" value="'.$info_translate['id'].'">
						<input type="hidden" name="id_lang_value_select_value[]" value="'.$v_lang['id'].'">
						<input type="hidden" name="id_select_value[]" value="'.$v_value['id'].'">
						</td>
						';
					}
					$body_admin .= '
					</tr>
					';
				}
				$body_admin .='
				</tbody>
				</table>
				</div>
				<center><input type="submit" name="submit" value="'.$lang[45].'" class="translate_form"></center>
				</form>
				';
		    } else {
				$body_admin .= '<span style="color:red">'.$lang[214].'</span>';
			}
		}
	    break;
	}
    break;
}
?>