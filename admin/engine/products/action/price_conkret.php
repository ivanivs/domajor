<?php
if (!isset ($_GET['price']))
{
        if (isset ($_GET['del']))
        {
                $sql = "DELETE FROM `ls_card_param_ligament` where `id` = '".$_GET['del']."';";
                mysql_query($sql);
        }
        $other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[504];
        $body_admin .= '<h2 id="title">'.$lang[504].'</h2>';
        $array_card = return_all_card();
        if ($array_card)
        {
                $body_admin .= '<table border="0">
                <tr>
                    <th width="15">ID</th>
                    <th align="center">'.$lang[506].'</th>
                    <th align="center">'.$lang[507].'</th>
                    <th align="center">'.$lang[508].'</th>
                </tr>
                ';
                foreach ($array_card as $v)
                {
                        $sql = "SELECT * FROM `ls_card_param_ligament` where id_card='".$v['id']."';";
                        $results = mysql_query($sql);
                        $number = @mysql_num_rows ($results);
                        if ($number>0)
                        {
                                for ($i=0; $i<$number; $i++)
                                {	
                                        $array_ligament[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
                                }	
                                foreach ($array_ligament as $key => $ligament_one)
                                {
                                        $sql = "SELECT `text` FROM `ls_translate` where `id_elements` = '".$ligament_one['id_param']."' and `id_lang` = '".$id_online_lang."' and `type` = 'select';";
                                        $info_lang = mysql_fetch_array (mysql_query($sql), MYSQL_ASSOC);
                                        $html_ligament .= '<b>'.$info_lang['text'].' <a href="index.php?do=products&action=price&del='.$ligament_one['id'].'" onclick="return confirm (\''.$lang[512].'\')"><img src="'.$config ['site_url'].'images/admin/remove_16.png"></a></b>';
                                        if (isset ($array_ligament[$key+1]))
                                        {
                                                $html_ligament .= '<br>';
                                        }
                                }
                        } else {
                                $html_ligament = '<span style="color:red;">'.$lang[513].'</span>';
                        }
                        $body_admin .= '
                        <tr>
                            <td>'.$v['id'].'</td>
                            <td><b>'.$v['name'].'</b></td>
                            <td>'.$html_ligament.'</td>
                            <td width="150" align="center"><a href="index.php?do=products&action=price&price=add_param&id='.$v['id'].'"><img src="'.$config ['site_url'].'images/admin/add.png"></a></td>
                        </tr>';
                        unset ($array_ligament, $html_ligament, $info_lang);
                }
                $body_admin .= '</table>';
        } else {
            $body_admin .= '<h2 id="title" style="color:red;">'.$lang[297].'</h2>';
        }
} else {
        switch($_GET['price'])
        {
                case "add_param":
                        if (isset ($_POST['z_param']))
                        {
                                $array_param = $_POST['z_param'];
                                foreach ($array_param as $v)
                                {
                                        $sql = "
                                        INSERT INTO  `ls_card_param_ligament` (
                                        `id_card` ,
                                        `id_param`
                                        )
                                        VALUES (
                                        '".$_GET['id']."' ,
                                        '".$v."'
                                        );
                                        ";
                                        mysql_query($sql);
                                }
                                $body_admin .= '<span style="color:green;">'.$lang[511].'</span>';
                        } else {
                                $other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[505];
                                $body_admin .= '<h2 id="title">'.$lang[509].'</h2>';
                                $array_all_select_param_from_card = return_id_param_from_cardparam_where_id_card_only_select($_GET['id']);
                                if ($array_all_select_param_from_card)
                                {
                                        $body_admin .= '<form action="" method="POST">';
                                        foreach ($array_all_select_param_from_card as $v)
                                        {
                                                $sql = "SELECT `id` from `ls_card_param_ligament` where `id_card` = '".$_GET['id']."' and `id_param` = '".$v['id_param']."';";
                                                mysql_numrows(mysql_query($sql));
                                                if (!mysql_numrows(mysql_query($sql)))
                                                {
                                                        $sql = "SELECT `text` FROM `ls_translate` where `id_elements` = '".$v['id_param']."' and `id_lang` = '".$id_online_lang."' and `type` = 'select';";
                                                        $info_lang = mysql_fetch_array (mysql_query($sql), MYSQL_ASSOC);
                                                        $body_admin .= '<input type="checkbox" name="z_param[]" value="'.$v['id_param'].'"> '.$info_lang['text']."<br>\r\n";
                                                }
                                        }
                                        $body_admin .= '
                                        <input type="submit" name="submit" value="'.$lang[510].'">
                                        </form>';
                                } else {
                                        $body_admin .= '<span style="color:red;">'.$lang[514].'</span>';
                                }
                        }
                break;
        }
}
?>