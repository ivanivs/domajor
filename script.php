<?php
session_start();
require ('config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
//$info_item = mysql_fetch_array(mysql_query("SELECT * FROM `ls_items` where `id` = 2992;"), MYSQL_ASSOC);
function return_id_value ($id_item, $id_cardparam)
{
    $sql = "SELECT * FROM `ls_values_select` where id_item='".$id_item."' and `id_cardparam` = '".$id_cardparam."';";
    $results = mysql_query($sql);
    $number = @mysql_num_rows ($results);
    if ($number>0)
    {
        for ($i=0; $i<$number; $i++)
        {	
                $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
        }
        return($array);
    }
}
function change ($info_item)
{
    $sql = "SELECT * FROM `ls_values_select` where id_item='".$info_item['id']."';";
    $results = mysql_query($sql);
    $number = @mysql_num_rows ($results);
    if ($number>0)
    {
        for ($i=0; $i<$number; $i++)
        {	
                $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
        }
        foreach ($array as $v)
        {
            $info_card_param_in_cart = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `ls_values_select` where `id_cardparam` = ".$v['id_cardparam']." and `id_item` = '".$info_item['id']."';"), MYSQL_ASSOC);
            if ($info_card_param_in_cart['COUNT(*)']>1)
            {
                $sql = "SELECT COUNT(*) FROM `ls_params_select_values` where `id` = ".$v['value']." and `parent_param_id` <> 0 and `id_params` = 14;";
                $info_isset_parent = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
                if ($info_isset_parent['COUNT(*)']>0)
                {
                    $array_param = return_id_value ($info_item['id'], $v['id_cardparam']);
                    foreach ($array_param as $v_p)
                    {
                        $tmp .= $v_p['value'].' - ';
                    } 
                    $info_last_value = mysql_fetch_array(mysql_query("SELECT * FROM `ls_values_select` where id_item='".$info_item['id']."' and `id_cardparam` = '".$v['id_cardparam']."' ORDER by `id` LIMIT 0,1;"), MYSQL_ASSOC);
                    //if (mysql_query("DELETE FROM `ls_values_select` where `id` = '".$info_last_value['id']."';"))
                    //{
                    //    $del = ' - DELETED';
                    //} else {
                    //    $del = '';
                    //}
                    print $info_item['id'].' '.$tmp.' '.$info_last_value['id'].' '.$del."\r\n";
                    unset ($tmp);
                }
            }
            /*$info_card_param_in_cart = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `ls_values_select` where `id_cardparam` = ".$v['id_cardparam']." and `id_item` = '".$info_item['id']."';"), MYSQL_ASSOC);
            $sql = "SELECT COUNT(*) FROM `ls_params_select_values` where `id` = ".$v['value']." and `parent_param_id` <> 0;";
            $info_isset_parent = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
            //print $info_isset_parent['COUNT(*)']."\r\n";
            if ($info_card_param_in_cart['COUNT(*)']>1 and $info_isset_parent['COUNT(*)']>0)
            {
                $info_parent_value = mysql_fetch_array(mysql_query("SELECT `parent_param_id` FROM `ls_params_select_values` where `id` = ".$v['value']." and `parent_param_id` <> 0;"), MYSQL_ASSOC);
                $info_parent_value_in_item = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `ls_values_select` where `value` = '".$info_parent_value['parent_param_id']."' and `id_item` = '".$info_item['id']."';"), MYSQL_ASSOC);
                if ($info_parent_value_in_item['COUNT(*)']==0)
                {
                    $sql = "DELETE FROM `ls_values_select` where `id` = '".$v['id']."';";
                    mysql_query($sql);
                    $del = ' - DELETED';
                } else {
                    $del = '';
                }
                print 'ID VALUES SELECT: '.$v['id'].' ID ITEM: '.$info_item['id'].' ID CARDPARAM: '.$v['id_cardparam'].' ID PARENT VALUE: '.$info_parent_value['parent_param_id'].' ISSET PARENT VALUE: '.$info_parent_value_in_item['COUNT(*)'].' '.$del.' '."\r\n";
                //$info_id_param_ns = mysql_fetch_array(mysql_query("SELECT `id_param` FROM `ls_cardparam` where `id` = '".$v['id_cardparam']."';"), MYSQL_ASSOC);
                //$info_true_card_param = mysql_fetch_array(mysql_query("SELECT `id` FROM `ls_cardparam` where `id_card` = '".$info_item['id_card']."' and `id_param` = '".$info_id_param_ns['id_param']."';"), MYSQL_ASSOC);
                /*if (strlen($info_true_card_param['id']!=0))
                {
                    mysql_query("UPDATE `ls_values_select` set `id_cardparam` = '".$info_true_card_param['id']."' where `id` = '".$v['id']."';");
                } else {
                    mysql_query("delete from `ls_values_select` where `id` = '".$v['id']."';");
                }*/
            //}
        }
    }
}
$sql = "SELECT * FROM `ls_items`;";
//$sql = "SELECT `id_item` as `id` FROM `ls_values_select` limit 0,10;";
$results = mysql_query($sql);
$number = @mysql_num_rows ($results);
if ($number>0)
{
    for ($i=0; $i<$number; $i++)
    {	
            $array_item[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
    }
    foreach ($array_item as $info_item)
    {
        change ($info_item);
    }
}
?>