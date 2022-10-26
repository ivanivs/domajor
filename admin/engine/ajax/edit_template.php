<?php
require ('../../../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8'");
require ('../template/functions.php');
require ('../../../include/functions.php');
if (check_user())
{
    $lang_file_array = return_my_language ();
    foreach ($lang_file_array as $v)
    {
            require_once($v);
    }
    $info_template = return_one_template($_POST['id']);
    print '
    <form action="" method="POST">
    <input type="hidden" name="id_template" value="'.$_POST['id'].'">
    <div align="center">
    <h2 class="title">'.$lang[353].'</h2>
    <div id="after_save"></div>
    <div align="left"><b>'.$lang[357].'</b></div>
    <table border="0">
        <tr>
            <td width="200">'.$lang[348].'</td>
            <td><input type="text" id="name_template" name="name_template" value="'.$info_template['name_template'].'" style="border:1px solid black; width:300px"></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
            '.$lang[354].'
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center"><textarea id="template" name="template" style="width:800px;" rows="30" cols="100">'.str_replace ("'", "\'", $info_template['template']).'</textarea></td>
        </tr>
        <tr>
            <td align="center" colspan="2"><input type="submit" name="submit" value="'.$lang[45].'"></td>
        </tr>
    </table></div></form>
    ';
}
?>