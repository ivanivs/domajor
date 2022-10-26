<?php
$other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[111];
if (isset ($_GET['change']))
{
    $sql = "UPDATE `ls_params_select_values` set `visible` = '".intval($_GET['change'])."' where `id` = '".intval($_GET['idValue'])."';";
    mysql_query($sql);
}
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
		    <tr>
		    ';
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
                $body_admin .= '<h2>'.$lang[140].'</h2>';
                if (!isset ($_GET['edit'])){
                    if (isset ($_GET['del']))
                    {
                        $sql = "delete from `ls_params_select_values` where `id` = '".$_GET['del']."';";
                        mysql_query ($sql);
                    }
                    if (isset ($_GET['up']))
                    {
                        $sql = "update `ls_params_select_values` set `position` = `position`-1 where `position` = '".($_GET['postion']+1)."';";
                        mysql_query($sql);
                        $sql = "update `ls_params_select_values` set `position` = '".($_GET['postion']+1)."' where `id` = '".$_GET['up']."';";
                        mysql_query($sql);
                    }
                    if (isset ($_GET['down']))
                    {
                        $sql = "update `ls_params_select_values` set `position` = `position`+1 where `position` = '".($_GET['postion']-1)."';";
                        mysql_query($sql);
                        $sql = "update `ls_params_select_values` set `position` = '".($_GET['postion']-1)."' where `id` = '".$_GET['down']."';";
                        mysql_query($sql);
                    }
                    unset ($position);
                    $position = 1;
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
                <form action="" method="POST">
                <div align="center">
                <table cellspacing="1">
                <tr>
                <td align="center"><b>ID</b></td>
                <td>IMG</td>
                ';
                        foreach ($array_true_lang as $v_lang)
                        {
                            $body_admin .= '<td><b>'.$v_lang['name'].'</b></td>';
                        }
                        $body_admin .= '
                            <td></td>
                            <td></td>
                            <td>Видимый</td>
                            <td align="center"><img src="'.$config ['site_url'].'images/admin/remove_16.png"></td>
                        </tr>
                        ';
                        $true_values_select_params = return_all_select_values_params($_GET['id']);
                        if ($true_values_select_params)
                        {
                            foreach ($true_values_select_params as $k => $v_value)
                            {
                                $img = '';
                                if (strlen($v_value['text'])>0){
                                    preg_match('|src="(.*)"|isU', $v_value['bodyText'], $imgTmp);
                                    $img = '<img src="'.$config['site_url'].'resize_image.php?filename=upload%2Freviews%2F'.str_replace($config ['site_url'].'upload/reviews/', '', $imgTmp[1]).'&const=128&width=100&height=100&r=255&g=255&b=255">';
                                }
                                $body_admin .= '
                                <tr style="border-bottom: 1px solid grey;">
                                <td align="center">'.$v_value['id'].'</td>
                                <td>'.$img.'</td>
                                ';
                                foreach ($array_true_lang as $v_lang)
                                {
                                    $info_translate = return_translate_for_id_elements_values_select ($v_value['id'], $v_lang['id']);
                                    $infoParent = getOneValue($v_value['parent_param_id']);
                                    $body_admin .= '
                                    <td align="center">'.$infoParent['text'].' - '.$info_translate['text'].'</td>
                                    ';
                                }
                                $sql = "select * from `ls_values_select` where `value` = '".$v_value['id']."' LIMIT 0,1;";
                                $results = mysql_query ($sql);
                                $number = @mysql_num_rows ($results);
                                if (!$number)
                                {
                                    $delete_html = '<a href="index.php?do=params&action=view_value_text&type=select&type_2=value&id='.$_GET['id'].'&del='.$v_value['id'].'" onclick="return confirm (\''.$lang[69].'\')"><img src="'.$config ['site_url'].'images/admin/remove_16.png"></a>';
                                } else {
                                    $delete_html = '<b>used</b>';
                                }
                                if ($k!=0)
                                {
                                    $up = '<a href="index.php?do=params&action=view_value_text&type=select&type_2=value&id='.$_GET['id'].'&up='.$v_value['id'].'&postion='.$v_value['position'].'"><img src="'.$config ['site_url'].'images/admin/1uparrow.png" border="0"></a>';
                                }
                                if ($k!=count($true_values_select_params)-1)
                                {
                                    $down = '<a href="index.php?do=params&action=view_value_text&type=select&type_2=value&id='.$_GET['id'].'&down='.$v_value['id'].'&postion='.$v_value['position'].'"><img src="'.$config ['site_url'].'images/admin/1downarrow.png" border="0"></a>';
                                }
                                if ($v_value['visible'])
                                {
                                    $vissibleOrHide = '<a href="index.php?do=params&action=view_value_text&type=select&type_2=value&id='.$_GET['id'].'&change=0&idValue='.$v_value['id'].'"><img src="'.$config ['site_url'].'images/green.png"></a>';
                                } else {
                                    $vissibleOrHide = '<a href="index.php?do=params&action=view_value_text&type=select&type_2=value&id='.$_GET['id'].'&change=1&idValue='.$v_value['id'].'"><img src="'.$config ['site_url'].'images/red.png"></a>';
                                }
                                if (!isset ($down))
                                    $down = '';
                                $body_admin .= '
                            <td align="center">
                                '.$up.'
                                '.$down.'
                            </td>
                            <td><a href="index.php?do=params&action=view_value_text&type=select&type_2=value&id='.intval($_GET['id']).'&idValue='.$v_value['id'].'&edit=1">изменить</a></td>
                            <td align="center">'.$vissibleOrHide.'</td>
                            <td align="center">'.$delete_html.'</td>
                        </tr>
                        ';
                                unset ($up, $down);
                                if ($v_value['position']==0)
                                {
                                    $position = $v_value['position'];
                                }
                            }
                            if (isset ($_GET['position_erase']))
                                $position = 0;
                            if ($position==0)
                            {
                                foreach ($true_values_select_params as $key_select_param => $v_select_param)
                                {
                                    $pos = count($true_values_select_params)-$key_select_param;
                                    $sql = "UPDATE `ls_params_select_values` set `position` = '".$pos."' where `id` = '".$v_select_param['id']."';";
                                    mysql_query ($sql);
                                }
                            }
                        }
                        $body_admin .='
                </table>
                </div>
                ';
                    }
                } else {
                    if (isset ($_POST['text'])){
                        $sql = "UPDATE `ls_params_select_values` SET `bodyText` = '".mysql_real_escape_string($_POST['text'])."' WHERE `id` = '".intval($_GET['idValue'])."';";
                        mysql_query($sql);
                    }
                    $infoSelectValueParams = mysql_fetch_array(mysql_query("SELECT * FROM `ls_params_select_values` WHERE `id` = '".intval($_GET['idValue'])."';"), MYSQL_ASSOC);
                    $body_admin .= '
                    <script>
                        $(document).ready(function() {
                          $(\'#text\').summernote({
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
                        <textarea name="text" id="text" class="mceEditor">'.$infoSelectValueParams['bodyText'].'</textarea>
                        <div style="margin-top: 15px;"><input type="submit" class="btn btn-success" value="Сохранить"></div>
                    </form>
                    ';
                }
                break;
        }
        break;
}
?>