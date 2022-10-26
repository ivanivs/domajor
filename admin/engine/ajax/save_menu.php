<?php
require ('../../../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
require ('../params/functions.php');
require ('../../../include/functions.php');
if (check_user())
{
    print '<div style="border:1px solid black;">';
     $lang_file_array = return_my_language ();
        foreach ($lang_file_array as $v)
        {
                require_once($v);
        }
    if (!isset ($_POST['id']))
    {
        $sql = "INSERT INTO  `ls_menu` (
        `id_select_params` ,
        `name_menu` ,
        `use_parent` ,
        `main_menu` ,
        `class_parent_blok` ,
        `class_parent_link` ,
        `class_blok_link` ,
        `class_link`
        )
        VALUES (
        '".$_POST['id_select_params']."' ,
        '".$_POST['name_menu']."' ,
        '".$_POST['use_parent']."' ,
        '".$_POST['main_menu']."' ,
        '".$_POST['class_parent_blok']."' ,
        '".$_POST['class_parent_link']."' ,
        '".$_POST['class_blok_link']."' ,
        '".$_POST['class_link']."'
        );";
        if (mysql_query($sql))
        {
            print '<span style="color:green">'.$lang[309].'</span>';
        } else {
            print '<span style="color:red">'.$lang[310].'</span>';
        }
    } else {
        $sql = "
         update `ls_menu` set
         `id_select_params` = '".mysql_escape_string($_POST['id_select_params'])."' ,
         `name_menu`  = '".mysql_escape_string($_POST['name_menu'])."' ,
         `use_parent` =  '".mysql_escape_string($_POST['use_parent'])."' ,
         `main_menu` =  '".mysql_escape_string($_POST['main_menu'])."' ,
         `class_parent_blok` = '".mysql_escape_string($_POST['class_parent_blok'])."' ,
         `class_parent_link` = '".mysql_escape_string($_POST['class_parent_link'])."' ,
         `class_blok_link` = '".mysql_escape_string($_POST['class_blok_link'])."' ,
         `class_link` = '".mysql_escape_string($_POST['class_link'])."' where `id` = '".$_POST['id']."';
        ";
        if (mysql_query($sql))
        {
            print '<span style="color:green">'.$lang[326].'</span>';
        } else {
            print '<span style="color:red">'.$lang[327].'</span>';
        }
    }
    print "</div>";
}
?>