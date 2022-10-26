<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/23/15
 * Time: 11:21 AM
 * To change this template use File | Settings | File Templates.
 */
$data = json_decode(base64_decode($_POST['data']), true);
mail('ivanivs@gmail.com', 'API', $data);
$dopSql = '';
if ($data['status']=='success'){
    $dopSql = ', `status` = 4';
}
$sql = "UPDATE `ls_orders` SET `result` = '".mysql_real_escape_string(base64_decode($_POST['data']))."'".$dopSql." WHERE `id` = '".$data['order_id']."';";
file_put_contents('upload/1.txt', $_POST['data']."\r\n".$data['order_id']."\r\n".$sql);
mysql_query($sql);