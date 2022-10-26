<?php
$other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[143];
if (isset ($_POST['name_params']))
{
    mysql_query ("START TRANSACTION;");
    mysql_query ("
               INSERT INTO  `ls_params_price` (
                `reference_id` ,
                `convert_price`
                )
                VALUES (
                '".$_POST['reference_id']."',
                '".$_POST['convert_price']."'
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
		'price'
                );
                ");
    if (mysql_query ("COMMIT;"))
    {
	$body_admin .= '<div align="center" style="font-size:16; color:green">'.$lang[147].'</div>';
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
    <h2 id="title">'.$lang[143].'</h2>
    <form action="" method="POST">
    <table border="0">
        <tr>
            <td>'.$lang[141].':</td>
            <td><input type="text" name="name_params"></td>
        </tr>
        <tr>
            <td>'.$lang[142].':</td>
            <td>
                <select name="reference_id">
                <option value="">'.$lang[144].'</option>
                '.$select_reference.'
                </select>
            </td>
        </tr>
        <tr>
            <td>'.$lang[146].'</td>
            <td><input type="checkbox" name="convert_price" value="1"></td>
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