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
        $body_admin .= '
        <form action="" method="POST">
        <div align="center">
        <span style="font-size:12px;">'.$lang[171].'</span>
        <table border="0">
        <tr>
        <td>'.$lang[173].'</td>
        <td><input type="text" name="name_card" size="10"></td>
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
            $body_admin .= '
            <tr>
            <td>'.$array_info['text'].'</td>
            <td>
            <input type="checkbox" name="checked[]" value="'.$key.'">
            <input type="hidden" name="id_params[]" value="'.$v['id'].'">
            <input type="hidden" name="db_type[]" value="'.$v['db_type'].'">
            </td>
            </tr>';
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
    $sql = "
        INSERT INTO  `ls_card` (
        `name`
        )
        VALUES (
        '".$_POST['name_card']."'
        );
        ";
    mysql_query ($sql);
    $id_card = mysql_insert_id();
    foreach ($checked as $key=>$v)
    {
        $sql = "
        INSERT INTO  `ls_cardparam` (
        `id_card` ,
        `id_param` ,
        `db_type`
        )
        VALUES (
        '".$id_card."' ,
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
    $body_admin .= '<span style="font-size:14; color:green;">'.$lang[174].'</span>';
}
?>