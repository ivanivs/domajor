<?php
require ('../../../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
require ('../../../include/functions.php');
if (check_user())
{
    $lang_file_array = return_my_language ();
    foreach ($lang_file_array as $v)
    {
            require_once($v);
    }
    print '
    <div align="center">
    <h2 class="title">'.$lang[350].'</h2>
    <div id="after_save"></div>
    <table border="0" align="center">
        <tr>
            <td>'.$lang[348].'</td>
            <td><input type="text" id="name_template" style="border:1px solid black;"></td>
        </tr>
        <tr>
            <td align="center" colspan="2"><a href="#" onclick="save_new_template()">'.$lang[45].'</a></td>
        </tr>
    </table>
    </div>
    ';
}
?>