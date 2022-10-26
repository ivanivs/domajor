<?php
$config ['host']='localhost';
$config ['database']='intarsio';
$config ['user_datebase']='root';
$config ['password_datebase']='ifgfkm59171';
$link = MySql_connect ($config ['host'], $config ['user_datebase'], $config ['password_datebase']) or die ("Не могу подключится к базе данных");
mysql_select_db($config ['database']);
print "ID_WORK-CHILD: ".$id_works."\n";
$sql = "SELECT * FROM `ls_tinydeal` where `id` = ".$id_works.";";
print $sql."\n";
print $result = mysql_query($sql);
$info_work = mysql_fetch_array($result, MYSQL_ASSOC);
$array = get_one_item_tinydeal($link_full);
$info_count_item = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `ls_values_text` WHERE `text` = '".$array[1]."';"), MYSQL_ASSOC);
if (!$info_count_item['COUNT(*)'])
{
    $sql = "
    INSERT INTO  `ls_items` (
    `id_card` ,
    `time`
    )
    VALUES (
    '".$info_work['id_card']."',
    '".time()."'
    );
    ";
    $result = mysql_query($sql);
    $id_item = mysql_insert_id();
    $array_cardparam_to_value = explode ('&', $info_work['select_block']);
    foreach ($array_cardparam_to_value as $v)
    {
        $info_select_block = explode ('|', $v);
        $sql = "
        INSERT INTO  `ls_values_select` (
        `id_item` ,
        `id_cardparam`,
        `value`
        )
        VALUES (
        '".$id_item."',
        '".$info_select_block[1]."',
        '".$info_select_block[0]."'
        );
        ";
        mysql_query($sql);
    }
    $sql = "
    INSERT INTO  `ls_values_text` (
    `text`,
    `id_lang`,
    `id_item`,
    `id_cardparam`
    )
    VALUES (
    '".mysql_escape_string($array[0])."',
    '4',
    '".$id_item."',
    '97'
    );
    ";
    mysql_query($sql);
    $sql = "
    INSERT INTO  `ls_values_text` (
    `text`,
    `id_lang`,
    `id_item`,
    `id_cardparam`
    )
    VALUES (
    '".mysql_escape_string($array[0])."',
    '2',
    '".$id_item."',
    '97'
    );
    ";
    mysql_query($sql);
    $sql = "
    INSERT INTO  `ls_values_text` (
    `text`,
    `id_lang`,
    `id_item`,
    `id_cardparam`
    )
    VALUES (
    '".mysql_escape_string($array[3])."',
    '4',
    '".$id_item."',
    '100'
    );
    ";
    mysql_query($sql);
    $sql = "
    INSERT INTO  `ls_values_text` (
    `text`,
    `id_lang`,
    `id_item`,
    `id_cardparam`
    )
    VALUES (
    '".mysql_escape_string($array[1])."',
    '4',
    '".$id_item."',
    '98'
    );
    ";
    mysql_query($sql);
    $sql = "
    INSERT INTO  `ls_values_text` (
    `text`,
    `id_lang`,
    `id_item`,
    `id_cardparam`
    )
    VALUES (
    '".mysql_escape_string($array[4])."',
    '4',
    '".$id_item."',
    '99'
    );
    ";
    mysql_query($sql);
    $sql = "
    INSERT INTO  `ls_values_text` (
    `text`,
    `id_lang`,
    `id_item`,
    `id_cardparam`
    )
    VALUES (
    '".mysql_escape_string($array[4])."',
    '2',
    '".$id_item."',
    '99'
    );
    ";
    mysql_query($sql);
    $price = $array[2];
    $sql = "
    INSERT INTO  `ls_values_prices` (
    `value`,
    `convert_price`,
    `id_item`,
    `id_cardparam`
    )
    VALUES (
    '".mysql_escape_string($price)."',
    '1',
    '".$id_item."',
    '96'
    );
    ";
    mysql_query($sql);
    $array_image = array_reverse($array[5]);
    foreach ($array_image as $key => $v)
    {
        $new_name = time().'_'.rand(0,1000).'.jpg';
        copy ($v, './../../../upload/userparams/'.$new_name);
        $sql = "
        INSERT INTO  `ls_values_image` (
        `id_item`,
        `id_cardparam`,
        `value`,
        `position`
        )
        VALUES (
        '".$id_item."',
        '95',
        '".$new_name."',
        '".$key."'
        );
        ";
        mysql_query($sql);
    }
} else {
    print "IN BASE"."\n";
}
mysql_close($link);
exit();
?>