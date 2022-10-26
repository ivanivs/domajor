<?php
$uploaddir = '../upload/files/';
$file_db = file ($uploaddir.$_GET['name'].'.csv');
foreach ($file_db as $key => $v)
{
    $one_string = explode ('|', $v);
    if (strlen($one_string[6])>0 and strlen($one_string[6])<7)
    {
        $true = 1;
    } else {
        switch ($one_string[0])
        {
            case 'СПАЛЬНІ':
                $id_category = 91;
            break;
            case 'ДИТЯЧІ МЕБЛІ':
                $id_category = 92;
            break;
            case 'ОФІСНІ МЕБЛІ':
                $id_category = 88;
            break;
            case 'ВІШАКИ':
                $id_category = 160;
            break;
            case 'БАРНІ МЕБЛІ':
                $id_category = 96;
            break;
            case 'ОСВІТЛЕННЯ':
                $id_category = 3472;
                $dop_param[] = "
                INSERT INTO  `ls_values_text` (
                `text` ,
                `id_lang` ,
                `id_item` ,
                `id_cardparam`
                )
                VALUES (
                'Количество ламп и напряжение: ".mysql_escape_string($one_string[5])."' ,
                '1' ,
                '{id_item}' ,
                '19'
                );
                ";
                $dop_param[] = "
                INSERT INTO  `ls_values_text` (
                `text` ,
                `id_lang` ,
                `id_item` ,
                `id_cardparam`
                )
                VALUES (
                'Кількість ламп та напруга: ".mysql_escape_string($one_string[5])."' ,
                '1' ,
                '{id_item}' ,
                '19'
                );
                ";
            break;
            case 'КРІСЛА':
                $id_category = 3473;
            break;
        }
    }
    if (isset ($true))
    {
        $body_admin .= $one_string[1].' '.$one_string[2].' '.$one_string[3].' '.$one_string[6].' ГРН<br>';
        mysql_query("INSERT INTO  `ls_items` (
        `id_card` ,
        `time`
        )
        VALUES (
        '2',
        '".time()."'
        );");
        $id_item = mysql_insert_id();
        $sql = "
        INSERT INTO  `ls_values_prices` (
        `value` ,
        `convert_price` ,
        `id_item` ,
        `id_cardparam`
        )
        VALUES (
        '".$one_string[6]."',
        '1',
        '".$id_item."',
        '13'
        );
        ";
        mysql_query($sql);
        $md5 = md5($one_string[1].$one_string[2].$one_string[3]);
        $sql = "
        INSERT INTO  `ls_values_text` (
        `text` ,
        `id_lang` ,
        `id_item` ,
        `id_cardparam`
        )
        VALUES (
        '".$md5."' ,
        '2' ,
        '".$id_item."' ,
        '20'
        );
        ";
        mysql_query($sql);
        if (strlen($one_string[1])>0)
        {
            $name_item = $one_string[1];
        }
        $sql = "
        INSERT INTO  `ls_values_text` (
        `text` ,
        `id_lang` ,
        `id_item` ,
        `id_cardparam`
        )
        VALUES (
        '".mysql_escape_string($name_item)."' ,
        '2' ,
        '".$id_item."' ,
        '21'
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
        '".mysql_escape_string($name_item)."' ,
        '1' ,
        '".$id_item."' ,
        '21'
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
        '".mysql_escape_string($one_string[3])."' ,
        '2' ,
        '".$id_item."' ,
        '19'
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
        '".mysql_escape_string($one_string[3])."' ,
        '1' ,
        '".$id_item."' ,
        '19'
        );
        ";
        mysql_query($sql);
        if (isset ($id_category))
        {
            $sql = "
            INSERT INTO  `ls_values_select` (
            `id_item` ,
            `id_cardparam` ,
            `value`
            )
            VALUES (
            '".$id_item."',
            '16' ,
            '".$id_category."'
            );
            ";
            mysql_query($sql);
        }
        if (isset ($dop_param))
        {
            foreach ($dop_param as $v)
            {
                $sql = str_replace ('{id_item}', $id_item, $v);
                mysql_query($sql);
            }
            unset ($dop_param);
        }
        unset($true);
    }
}
?>