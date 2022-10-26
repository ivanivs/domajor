<?php
$other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[164];
if (isset ($_POST['name_params']))
{
    mysql_query ("START TRANSACTION;");
    mysql_query ("
                INSERT INTO  `ls_params_boolean` (
                `id`
                )
                VALUES (
                NULL
                );
    ");
    $id_new_param = mysql_insert_id();
    mysql_query ("
                 INSERT INTO  `ls_translate` (
                `id_lang` ,
                `id_elements` ,
                `text` ,
		`type`
                )
                VALUES (
                '".$id_online_lang."' ,
                '".$id_new_param."' ,
                '".$_POST['name_params']."' ,
		'boolean'
                );
                ");
    if (mysql_query ("COMMIT;"))
    {
	$body_admin .= '<div align="center" style="font-size:16; color:green">'.$lang[161].'</div>';
    } else {
	$body_admin .= '<div align="center" style="font-size:16; color:red">'.$lang[103].'</div>';
    }
} else {
    $array_reference = return_all_reference ();
    foreach ($array_reference as $v)
    {
        $select_reference .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
    }
    $body_admin .= '
    <div align="center">
    <h2 id="title">'.$lang[164].'</h2>
    <form action="" method="POST">
    <table border="0">
        <tr>
            <td>'.$lang[141].':</td>
            <td><input type="text" name="name_params"></td>
        </tr>
        <tr>
            <td align="center" colspan="2">
                <input type="hidden" name="type_params" value="'.$_POST['type_params'].'">
                <input type="submit" name="submit" value="'.$lang[145].'">
            </td>
        </tr>
    </table>
    </form>
    </div>
    ';
}
?>