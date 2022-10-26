<?php
$other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[169];
$body_admin .= '<h2 id="title">'.$lang[170].'<h2>';
if (!isset ($_POST['name_card']))
{
    $sql = "
    (SELECT id,db_type FROM ls_params_boolean)
    UNION ALL
    (SELECT id,db_type FROM ls_params_image)
    UNION ALL
    (SELECT id,db_type FROM ls_params_price)
    UNION ALL
    (SELECT id,db_type FROM ls_params_select)
    UNION ALL
    (SELECT id,db_type FROM ls_params_text)
    ;";
    $results = mysql_query($sql);
    $number = @mysql_num_rows ($results);
    if ($number>0)
    {
        $info_card = return_one_card ($_GET['id']);
        $body_admin .= '
        <form action="" method="POST">
        <div align="center">
        <span style="font-size:12px;">'.$lang[171].'</span>
        <table border="0">
        <tr>
		<td><small>ID</small></td>
        <td>'.$lang[173].'</td>
        <td><input type="text" name="name_card" size="10" value="'.$info_card['name'].'"></td>
        </tr>
        ';
        for ($i=0; $i<$number; $i++)
        {	
                $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
        }
        foreach ($array as $key=>$v)
        {
            $results = mysql_query("select `text` from ls_translate where id_lang='".$id_online_lang."' and id_elements='".$v['id']."' and type='".$v['db_type']."';");
            $array_info = mysql_fetch_array($results, MYSQL_ASSOC);
            $info_cardparam = return_params_one_for_card ($info_card['id'], $v['db_type'], $v['id']);
            if (count ($info_cardparam)>1)
            {
                $checked_html = 'checked';
            }
            $body_admin .= '
            <tr>
			<td>'.$info_cardparam['id'].'</td>
            <td>'.$array_info['text'].'</td>
            <td>
            <input type="checkbox" name="checked[]" value="'.$key.'" '.$checked_html.'>
            <input type="hidden" name="id_params[]" value="'.$v['id'].'">
            <input type="hidden" name="db_type[]" value="'.$v['db_type'].'">
            <input type="hidden" name="id_card_param[]" value="'.$info_cardparam['id'].'">
            </td>
            </tr>';
            unset ($checked_html);
        }
        $body_admin .= '
        <tr>
        <td colspan="2" align="center"><input type="submit" name="submit" value="'.$lang[172].'"></td>
        </tr>
        </table></div>
        </form>
        ';
    }
} else {
    $checked = $_POST['checked'];
    $id_params = $_POST['id_params'];
    $db_type = $_POST['db_type'];
    $id_card_param = $_POST['id_card_param'];
    $sql = "UPDATE  `ls_card` SET  `name` = '".$_POST['name_card']."' WHERE  `id` ='".$_GET['id']."';";
    mysql_query ($sql);
    //print_r ($id_card_param);
    //print_r ($id_params);
    //print_r ($checked);
    for ($i=0; $i<count($id_card_param); $i++)
    {
        $new_array[] = $i;
    }
   // print_r ($checked);
   // print_r ($new_array);
    $result = array_diff($new_array, $checked);
   // print_r ($result);
    foreach ($result as $key => $v)
    {
        $sql = "DELETE from `ls_cardparam` where id = '".$id_card_param[$v]."' ;";
        mysql_query($sql);
    }
    foreach ($checked as $key => $v)
    {
        if (strlen($id_card_param[$v])==0)
        {
            $sql = "
            INSERT INTO  `ls_cardparam` (
            `id_card` ,
            `id_param` ,
            `db_type`
            )
            VALUES (
            '".$_GET['id']."' ,
            '".$id_params[$v]."' ,
            '".$db_type[$v]."'
            );
            ";
            mysql_query ($sql);
            switch ($db_type[$v]){
                case "text":
                    $infoParam = mysql_fetch_array(mysql_query("SELECT * FROM `ls_params_text` where `id` = '".$id_params[$v]."';"), MYSQL_ASSOC);
                    $r = mysql_query("SELECT `text_".$infoParam['id']."` FROM `ls_items` WHERE 0");
                    if (!$r) {
                        if ($infoParam['multiline']){
                            mysql_query ("ALTER TABLE  `ls_items` ADD  `text_".$infoParam['id']."` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL AFTER  `status`");
                        } else {
                            mysql_query ("ALTER TABLE  `ls_items` ADD  `text_".$infoParam['id']."` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL AFTER  `status`");
                        }
                    }
                    break;
                case "select":
                    $infoParam = mysql_fetch_array(mysql_query("SELECT * FROM `ls_params_select` where `id` = '".$id_params[$v]."';"), MYSQL_ASSOC);
                    if ($infoParam['multiselect'])
                    {
                        mysql_query ("ALTER TABLE  `ls_items` ADD  `select_".$id_params[$v]."` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL AFTER `status`");
                    } else {
                        mysql_query ("ALTER TABLE  `ls_items` ADD  `select_".$id_params[$v]."` int(4) unsigned AFTER  `status`");
                    }
                    break;
                case "image":
                    mysql_query ("ALTER TABLE  `ls_items` ADD  `image_".$id_params[$v]."` TEXT unsigned AFTER  `status`");
                    break;
                case "price":
                    mysql_query ("ALTER TABLE  `ls_items` ADD  `price_".$id_params[$v]."` DOUBLE NOT NULL AFTER  `status`");
                    break;
                case "boolean":
                    mysql_query ("ALTER TABLE  `ls_items` ADD  `boolean_".$id_params[$v]."` BOOLEAN NOT NULL AFTER  `status`");
                    break;
            }
        }
    }
    /*
    $sql = "DELETE from `ls_cardparam` where id_card='".$_GET['id']."';";
    mysql_query($sql);
    foreach ($checked as $key=>$v)
    {
        $sql = "
        INSERT INTO  `ls_cardparam` (
        `id_card` ,
        `id_param` ,
        `db_type`
        )
        VALUES (
        '".$_GET['id']."' ,
        '".$id_params[$v]."' ,
        '".$db_type[$v]."'
        );
        ";
        mysql_query ($sql);
    }
    */
    $body_admin .= '<span style="font-size:14; color:green;">'.$lang[176].'</span>';
}
?>