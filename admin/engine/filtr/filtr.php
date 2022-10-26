<?php
require ('engine/params/functions.php');
if (isset ($_GET['action']))
{
    switch ($_GET['action'])
    {
	case "delete":
	    $sql = "DELETE from ls_filtr where id = '".$_GET['id']."';";
	    mysql_query($sql);
            $sql = "DELETE from ls_filtr_param where id_filtr = '".$_POST['filtr']."';";
            mysql_query ($sql);
	break;
    }
}
if (isset ($_POST['id_filtr']))
{
    $array_param = $_POST['filtr_param'];
    foreach ($array_param as $v)
    {
        $array_filtr_param = explode ('_', $v);
        if (!check_filtr_param($_POST['id_filtr'], $array_filtr_param[0], $array_filtr_param[1]))
        {
            $sql = "
            INSERT INTO  `ls_filtr_param` (
            `id_filtr` ,
            `id_param` ,
            `id_value_param`
            )
            VALUES (
            '".$_POST['id_filtr']."',
            '".$array_filtr_param[0]."',
            '".$array_filtr_param[1]."'
            );
            ";
            if (mysql_query($sql))
            {
                $status_sql = '<span style="color:green;" align="center">'.$lang[338].'</span>';
            } else {
                $status_sql = '<span style="color:red;" align="center">'.$lang[339].'</span>';
            }
        }
    }
}
if (isset ($_POST['filtr']))
{
    $sql = "INSERT INTO  `ls_filtr` (
    `id_select_values`
    )
    VALUES (
    '".$_POST['filtr']."'
    );";
    if (mysql_query($sql))
    {
        $status_sql = '<span style="color:green;" align="center">'.$lang[334].'</span>';
    } else {
        $status_sql = '<span style="color:red;" align="center">'.$lang[335].'</span>';
    }
}
$body_admin .= '
<div align="center">
<table border=0>
<tr>
<td align="center">
	<a href="index.php?do=filtr" style="text-decoration:none"><img src="'.$config ['site_url'].'images/admin/view.png"><br>
	['.$lang[89].']</a>
</td>
<td align="center">
	<a href="#" style="text-decoration:none" onclick="add_new_filtr()"><img src="'.$config ['site_url'].'images/admin/add.png"><br>
	['.$lang[52].']</a>
</td>
</tr>
</table>
</div>
<div id="main_filtr">'.$status_sql.'<br>';
$body_admin .'</div>
';
if (isset ($_GET['action']))
{
    switch ($_GET['action'])
    {
        case "delete":
            $sql = "DELETE from ls_filtr where id = '".$_GET['id']."';";
            mysql_query($sql);
        break;
	case "visible":
	    if (isset($_GET['down']))
	    {
		$sql = "UPDATE ls_filtr_param set visible=0 where id='".$_GET['id']."';";
		mysql_query($sql);
	    }
	    if (isset($_GET['up']))
	    {
		$sql = "UPDATE ls_filtr_param set visible=1 where id='".$_GET['id']."';";
		mysql_query($sql);
	    }
	break;
    }
}
$array_filtr = return_all_filtr();
if ($array_filtr)
{
    $body_admin .= '<table cellspacing="1" class="tablesorter" width="100%">
    <thead>
    <tr> 
           <th width="25" align="center">'.$lang[27].'</th> 
           <th>'.$lang[336].'</th>
           <th>'.$lang[328].'</th>
           <th width="100">'.$lang[312].'</th> 
     </tr> 
    </thead> 
    <tbody>';
    foreach ($array_filtr as $v)
    {
        $info_param_value = return_one_values_params ($v['id_select_values']);
        $info_param_name = return_one_translate ($v['id_select_values'], $id_online_lang, 'select_value');
        //print $info_param_value['parent_param_id']."<br>";
        if ($info_param_value['parent_param_id'])
        {
            $info_param_value_parent = return_one_values_params ($info_param_value['parent_param_id']);
            $info_param_name_parent = return_one_translate ($info_param_value_parent['id'], $id_online_lang, 'select_value');
            $parent_name = $info_param_name_parent['text'].' <img src="'.$config ['site_url'].'images/admin/rightarrow.png"> ';
        }
        if (strlen($info_param_value['img']))
        {
            $info_param_name['text'] = '<img src="'.$config ['site_url'].'upload/select_params/'.$info_param_value['img'].'">';
        }
        $namr_filtr = $parent_name.' '.$info_param_name['text'];
        $array_param_filtr = return_param_filtr_order_by_id_param($v['id']);
        if ($array_param_filtr)
        {
            foreach ($array_param_filtr as $v_param_filtr)
            {
                $tr_param = return_one_translate ($v_param_filtr['id_param'], $id_online_lang, 'select');
                $info_param_value_select = return_one_values_params ($v_param_filtr['id_value_param']);
                if (strlen($info_param_value_select['img']))
                {
                    if ($info_param_value_select['parent_param_id'])
                    {
                        $info_param_value_select_parent = return_one_values_params ($info_param_value_select['parent_param_id']);
                        if (strlen($info_param_value_select_parent['img']))
                        {
                            $parent_param_name = '<img src="'.$config ['site_url'].'upload/select_params/'.$info_param_value_select['img'].'">';
                        } else {
                            $parent_param_name = return_one_translate ($v_param_filtr['id_param'], $id_online_lang, 'select');
                            $parent_param_name = $parent_param_name['text'];
                        }
                        $parent_param_name = $parent_param_name.' <img src="'.$config ['site_url'].'images/admin/rightarrow.png"> ';
                        $tr_param_value['text'] = $parent_param_name.'<img src="'.$config ['site_url'].'upload/select_params/'.$info_param_value_select['img'].'">';
                    } else {
                        $tr_param_value['text'] = '<img src="'.$config ['site_url'].'upload/select_params/'.$info_param_value_select['img'].'">';
                    }
                } else {
                    if ($info_param_value_select['parent_param_id'])
                    {
                        $info_param_value_select_parent = return_one_values_params ($info_param_value_select['parent_param_id']);
                        if (strlen($info_param_value_select_parent['img']))
                        {
                            $parent_param_name = ' <img src="'.$config ['site_url'].'upload/select_params/'.$info_param_value_select['img'].'"> ';
                        } else {
                            $parent_param_name = return_one_translate ($info_param_value_select_parent['id'], $id_online_lang, 'select_value');
                            $parent_param_name = $parent_param_name['text'];
                        }
                        $parent_param_name = $parent_param_name.' <img src="'.$config ['site_url'].'images/admin/rightarrow.png"> ';
                        $tr_param_value = return_one_translate ($v_param_filtr['id_value_param'], $id_online_lang, 'select_value');
                        $tr_param_value['text'] = $parent_param_name.$tr_param_value['text'];
                    } else {
                        $tr_param_value = return_one_translate ($v_param_filtr['id_value_param'], $id_online_lang, 'select_value');
                    }
                }
		if ($v_param_filtr['visible'])
		{
		    $visible = '<a href="index.php?do=filtr&action=visible&id='.$v_param_filtr['id'].'&down=1"><img src="'.$config ['site_url'].'images/admin/green.png"></a>';
		} else {
		    $visible = '<a href="index.php?do=filtr&action=visible&id='.$v_param_filtr['id'].'&up=1"><img src="'.$config ['site_url'].'images/admin/red.png"></a>';
		}
                $filtr_param_html .= '
                <div id="filtr_param_'.$v_param_filtr['id'].'">
                <img src="'.$config ['site_url'].'images/admin/remove_16.png" alt="'.$lang[105].'" title="'.$lang[105].'" onclick="if (confirm (\''.$lang[69].'\')) { delete_filtr_param (\''.$v_param_filtr['id'].'\');}">
                '.$tr_param['text'].' <img src="'.$config ['site_url'].'images/admin/rightarrow.png"> '.$tr_param_value['text'].' '.$visible.'
                </div>';
                unset ($tr_param_value['text']);
            }
        } else {
            $filtr_param_html = '<span style="color:red;">'.$lang[341].'</span>';
        }
        $body_admin .= '<tr>
        <td>'.$v['id'].'</td>
        <td>'.$namr_filtr.'</td>
        <td width="500">'.$filtr_param_html.'</td>
        <td align="center">
            <img src="'.$config ['site_url'].'images/admin/map_edit.png" alt="'.$lang[337].'" title="'.$lang[337].'" onclick="fitr_control(\''.$v['id'].'\');">
            <a href="index.php?do=filtr&action=delete&id='.$v['id'].'" onclick="return confirm(\''.$lang[69].'\');"><img src="'.$config ['site_url'].'images/admin/remove_16.png" alt="'.$lang[105].'" title="'.$lang[105].'"></a>
        </td>
        </tr>';
        unset($parent_name, $info_param_name_parent, $filtr_param_html);
    }
    $body_admin .= '</tbody></table>';
} else {
    $body_admin .= '<h2 class="title" align="center">'.$lang[340].'</h2>';
}
$body_admin = '
<div id="info_admin_page">
<img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[345].$other_way.'
</div>
'.$body_admin;
?>