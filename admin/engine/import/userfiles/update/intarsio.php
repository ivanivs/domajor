<?php
$ignore = 1; //начиная с какого элемента массива будет начата обработка, если 1 - то 0 элемент будет игнорироваться
$uploaddir = '../upload/files/';
$file_db = file ($uploaddir.$_GET['name'].'.csv');
//print_r ($file_db);
$body_admin .= '<br>';
foreach ($file_db as $key => $v)
{
    $v = str_replace ('"', '', $v);
    $array_v = explode ('|', $v);
    $array_v[6] = str_replace (',', '', $array_v[6]);
    if (strlen($array_v[6])>1)
    {
        if (substr_count($array_v[6], 'знято з в-ва'))
        {
            $array_znyato_z_vstva[] = $key;
            $info_item = mysql_fetch_array(mysql_query("SELECT `id_item` FROM `ls_values_text` where `text`='".$array_v[1]."' or `text` = '".str_replace ('-', ' - ', $array_v[1])."';"), MYSQL_ASSOC);
            $info_price = mysql_fetch_array(mysql_query("SELECT `id`, `value` FROM `ls_values_prices` where `id_item` = '".$info_item['id_item']."' LIMIT 0,1;"), MYSQL_ASSOC);
            mysql_query("UPDATE `ls_values_prices` set `value` = '0' where `id` = '".$info_price['id']."';");
        } else {
            if (strlen($array_v[1])>0)
            {
                $info_item_count = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `ls_values_text` where `text`='".$array_v[1]."' or `text` = '".str_replace ('-', ' - ', $array_v[1])."';"), MYSQL_ASSOC);
                $info_item = mysql_fetch_array(mysql_query("SELECT `id_item` FROM `ls_values_text` where `text`='".$array_v[1]."' or `text` = '".str_replace ('-', ' - ', $array_v[1])."';"), MYSQL_ASSOC);
                if (strlen($info_item['id_item'])==0)
                {
                    $array_key[] = $key;
                } else {
                    $info_itemLSitems = mysql_fetch_array (mysql_query("SELECT * FROM `ls_items` where `id` = '".$info_item['id_item']."';"), MYSQL_ASSOC);
                    $info_cardPARAM = mysql_fetch_array (mysql_query ("SELECT * FROM `ls_cardparam` where `id_card` = '".$info_itemLSitems['id_card']."' and `id_param` =17 and `db_type` =  'select';"), MYSQL_ASSOC);
                    $infoThisProduce = mysql_fetch_array (mysql_query("SELECT COUNT(*) FROM `ls_values_select` where `id_item` = '".$info_item['id_item']."' and `id_cardparam` = '".$info_cardPARAM['id']."' and `value` = '147' and `id_item` = '".$info_item['id_item']."'"), MYSQL_ASSOC);
                    if ($infoThisProduce['COUNT(*)'])
                    {
                        if ($info_item_count['COUNT(*)']<3)
                        {
                            $info_price = mysql_fetch_array(mysql_query("SELECT `id`, `value` FROM `ls_values_prices` where `id_item` = '".$info_item['id_item']."' LIMIT 0,1;"), MYSQL_ASSOC);
                            if (strlen($info_item['id_item'])>0)
                            {
                                if ($info_price['value']!=trim($array_v[6]))
                                {
                                    mysql_query("UPDATE `ls_values_prices` set `value` = '".trim($array_v[6])."' where `id` = '".$info_price['id']."';");
                                    $body_admin .= $array_v[0].' - '.$info_item['id_item'].' - '.$info_price['value'].' - '.$array_v[6].' - <span style="color:green; font-weight:bold;">Оновлено</span><br>';
                                } else {
                                    $body_admin .= $array_v[0].' - '.$info_item['id_item'].' - '.$info_price['value'].' - '.$array_v[6].'<br>';
                                }
                            }
                        } else {
                            $array_key_notice[] = $key;
                        }
                    }
                }
            }
        }
    }
}
if (count($array_key))
{
    $body_admin .= '
    <h3>Новий товар - '.count($array_key).'</h3>
    <table border="1">
    ';
    foreach ($array_key as $v)
    {
        $v_tmp = str_replace ('"', '', $file_db[$v]);
        $array_v = explode ('|', $v_tmp);
        $body_admin .= '<tr>';
        foreach ($array_v as $v1)
        {
            $body_admin .= '<td>'.$v1.'</td>';
        }
        $body_admin .= '</tr>';
    }
    $body_admin .= '</table>';
}
$body_admin .= '
<h3>Товар по якому є декілька позицій - '.count($array_key_notice).'</h3>
<table border="1">
';
foreach ($array_key_notice as $v)
{
    $v_tmp = str_replace ('"', '', $file_db[$v]);
    $array_v = explode ('|', $v_tmp);
    $sql = "SELECT `id_item` FROM `ls_values_text` where `text`='".$array_v[1]."';";
    $results = mysql_query($sql);
    $number = mysql_num_rows ($results);
    if ($number)
    {
        for ($i=0; $i<$number; $i++)
        {
            $array_id_item[$i] = mysql_fetch_array($results, MYSQL_ASSOC);
        }
        foreach ($array_id_item as $v_id)
        {
            $new_array[] = $v_id['id_item'];
        }
        $new_array = array_unique($new_array);
        foreach ($new_array as $id_item_one)
        {
            $td .= '<a href="index.php?do=products&action=edit_item&id='.$id_item_one.'">'.$id_item_one.'</a><br>';
        }
    }
    $body_admin .= '<tr>';
    foreach ($array_v as $v1)
    {
        $body_admin .= '<td>'.$v1.'</td>';
    }
    $body_admin .= '<td>'.$td.'</td></tr>';
    unset ($td, $array_id_item, $new_array);
}
$body_admin .= '</table>';
if (count($array_znyato_z_vstva))
{
    $body_admin .= '
    <h3>Знято з виробництва - '.count($array_znyato_z_vstva).'</h3>
    <table border="1">
    ';
    foreach ($array_znyato_z_vstva as $v)
    {
        $v_tmp = str_replace ('"', '', $file_db[$v]);
        $array_v = explode ('|', $v_tmp);
        $body_admin .= '<tr>';
        foreach ($array_v as $v1)
        {
            $body_admin .= '<td>'.$v1.'</td>';
        }
        $body_admin .= '</tr>';
    }
    $body_admin .= '</table>';
}
?>