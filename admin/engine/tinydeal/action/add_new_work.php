<?php
$array_card = return_all_card ();
if ($array_card)
{
    foreach ($array_card as $v)
    {
        $option_card .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
    }
}
$body_admin .= '
    <h2>'.$lang[543].'</h2>
    <form action="" method="POST">
        <table border="0">
        <tr>
            <td><b>'.$lang[551].':</b></td>
            <td>
                <select name="id_card" id="id_card" onchange="all_select_param_from_card();">
                    <option></option>
                    '.$option_card.'
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="all_select_param_from_card"></div>
            </td>
        </tr>
        <tr>
            <td><b>'.$lang[547].':</b></td>
            <td><input type="text" name="name"></td>
        <tr>
        <tr>
            <td><b>'.$lang[548].':</b></td>
            <td><input type="text" name="url"></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" name="submit" value="'.$lang[45].'"></td>
        <tr>
        </table>
    </form>
';
if (isset ($_POST['url']))
{
    $select_block_array = $_POST['select'];
    foreach ($select_block_array as $key => $v)
    {
        $select_block .= $v;
        if (isset ($select_block_array[$key+1]))
        {
            $select_block .= '&';
        }
    }
    $sql = "INSERT INTO  `ls_tinydeal` (
    `id_card` ,
    `select_block` ,
    `name` ,
    `url` ,
    `status`
    )
    VALUES (
    '".$_POST['id_card']."',
    '".$select_block."' ,
    '".$_POST['name']."' ,
    '".$_POST['url']."' ,
    '1'
    );";
    if (mysql_query($sql))
    {
        $body_admin .= '<span style="color:green;">'.$lang[544].'</span>';
    } else {
        $body_admin .= '<span style="color:red;">'.$lang[545].'</span>'; 
    }
}
?>