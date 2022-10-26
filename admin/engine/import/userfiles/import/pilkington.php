<?php
$ignore = 1; //начиная с какого элемента массива будет начата обработка, если 1 - то 0 элемент будет игнорироваться
$uploaddir = '../upload/files/';
$file_db = file ($uploaddir.$_GET['name'].'.csv');
/*$array_values = return_all_select_value_from_param_id(23);
foreach ($array_values as $key => $v)
{
    $array_values_with_translate[$key] = $v;
    $translate_value_param = mysql_fetch_array(mysql_query("SELECT `text` FROM `ls_translate` WHERE `type` = 'select_value' and `id_elements` = '".$v['id']."';"), MYSQL_ASSOC);
    $array_values_with_translate[$key]['text'] = $translate_value_param['text'];
}
//print_r ($array_values_with_translate);
$i = 0;
foreach ($file_db as $key => $v)
{
    if ($key>=$ignore)
    {
        $one_string = explode (trim($info[1]), $v);
        foreach ($array_values_with_translate as $text)
        {
            if (substr_count($one_string[2], $text['text']))
            {
                $one_string[2] = str_replace($text['text'], '', $one_string[2]);
                $one_string[2] = trim($one_string[2]);
               // print $text['id']."\n";
                $array[$text['id']][] = $key;
            }
        }
        foreach ($one_string as $k => $n_v)
        {
            $n_string .= $n_v;
            if (isset ($one_string[$k+1]))
            {
                $n_string .= trim($info[1]);
            }
        }
        $file_db_new[$key] = $n_string;
        unset ($n_string);
        //print_r ($one_string);
    }
}
foreach ($array as $key => $v)
{
    $translate_value_param = mysql_fetch_array(mysql_query("SELECT `text` FROM `ls_translate` WHERE `type` = 'select_value' and `id_elements` = '".$key."';"), MYSQL_ASSOC);
    print $translate_value_param['text']."\n";
    foreach ($v as $item)
    {
        $one_string = explode (trim($info[1]), $file_db_new[$item]);
        $string = $one_string[2];
        $string = str_replace (', , ', ', ', $string);
        if ($string[0]==',' or $string[0]=='/')
        {
            $string[0] = '';
            $string = trim($string);
        }
        if ($string[strlen($string)-1]=='-')
        {
            $string[strlen($string)-1] = '';
        }
        $array_tmp[] = $string;
    }
    $array_tmp = array_unique($array_tmp);
    foreach ($array_tmp as $one)
    {
        print $one."\n";
    }
    unset ($array_tmp);
    print "\n\n\n\n\n";
    
}*/
$array_values = return_all_select_value_from_param_id(23);
foreach ($array_values as $key => $v)
{
    $array_values_with_translate[$key] = $v;
    $translate_value_param = mysql_fetch_array(mysql_query("SELECT `text` FROM `ls_translate` WHERE `type` = 'select_value' and `id_elements` = '".$v['id']."';"), MYSQL_ASSOC);
    $array_values_with_translate[$key]['text'] = $translate_value_param['text'];
}
foreach ($file_db as $key => $v)
{
    if ($key>=$ignore)
    {
        $one_string = explode (trim($info[1]), $v);
        foreach ($array_values_with_translate as $text)
        {
            if (substr_count($one_string[2], $text['text']))
            {
                $one_string[2] = str_replace($text['text'], '', $one_string[2]);
                $one_string[2] = trim($one_string[2]);
                //print $text['text']."\n";
                $array_values_parent = return_all_select_values_params_by_parent_id($text['id']);
                $one_string[2] = str_replace ('+', ' ', $one_string[2]);
                $info_count_in_base = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `ls_values_text` where `text` = '".$one_string[1]."';"), MYSQL_ASSOC);
                if (!$info_count_in_base['COUNT(*)'])
                {
                    $body_admin .= $one_string[1]."<br>\n";
                }
                foreach ($array_values_parent as $parent)
                {
                    $translate_value_param_parent = mysql_fetch_array(mysql_query("SELECT `text` FROM `ls_translate` WHERE `type` = 'select_value' and `id_elements` = '".$parent['id']."';"), MYSQL_ASSOC);
                    if (substr_count($one_string[2], $translate_value_param_parent['text']))
                    {
                        if (!$info_count_in_base['COUNT(*)'])
                        {
                            //print $translate_value_param_parent['text']."\n";
                            mysql_query("INSERT INTO  `ls_items` (
                            `id_card` ,
                            `time`
                            )
                            VALUES (
                            '1',
                            '".time()."'
                            );");
                            $id_item = mysql_insert_id();
                            $sql = "
                            INSERT INTO  `ls_values_text` (
                            `text` ,
                            `id_lang` ,
                            `id_item` ,
                            `id_cardparam`
                            )
                            VALUES (
                            '".$one_string[1]."' ,
                            '2' ,
                            '".$id_item."' ,
                            '3'
                            );
                            ";
                            mysql_query($sql);
                            $sql = "
                            INSERT INTO  `ls_values_text` (
                            `text` ,
                            `id_lang` ,
                            `id_item` ,
                            `id_cardparam`
                            )
                            VALUES (
                            '".$one_string[0]."' ,
                            '2' ,
                            '".$id_item."' ,
                            '4'
                            );
                            ";
                            mysql_query($sql);
                            $sql = "
                            INSERT INTO  `ls_values_select` (
                            `id_item` ,
                            `id_cardparam` ,
                            `value`
                            )
                            VALUES (
                            '".$id_item."',
                            '2' ,
                            '".$text['id']."'
                            );
                            ";
                            mysql_query($sql);
                            $sql = "
                            INSERT INTO  `ls_values_select` (
                            `id_item` ,
                            `id_cardparam` ,
                            `value`
                            )
                            VALUES (
                            '".$id_item."',
                            '5' ,
                            '".$parent['id']."'
                            );
                            ";
                            mysql_query($sql);
                            $sql = "
                            INSERT INTO  `ls_values_prices` (
                            `value` ,
                            `convert_price` ,
                            `id_item` ,
                            `id_cardparam`
                            )
                            VALUES (
                            '".$one_string[3]."',
                            '1',
                            '".$id_item."',
                            '1'
                            );
                            ";
                            mysql_query($sql);
                            $eurocode = $one_string[1];
                            switch ($eurocode[4])
                            {
                                case "A":
                                    $type = 3464;
                                break;
                                case "B":
                                    $type = 3465;
                                break;
                            }
                            if (isset ($type))
                            {
                                $sql = "
                                INSERT INTO  `ls_values_select` (
                                `id_item` ,
                                `id_cardparam` ,
                                `value`
                                )
                                VALUES (
                                '".$id_item."',
                                '8' ,
                                '".$type."'
                                );
                                ";
                                mysql_query($sql);
                            }
                            unset ($type);
                        }
                    }
                }
            }
        }
    }
}
?>