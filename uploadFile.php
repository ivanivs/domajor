<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 3/12/14
 * Time: 8:02 PM
 * To change this template use File | Settings | File Templates.
 */
require ('config.php');
$uploaddir = 'upload/reviews/';
//$uploaddir = '/var/www/ogshop/data/www/og-shop.in.ua/upload/reviews/';
$uploadfile = $uploaddir . basename($_FILES['file']['name']);
if (file_exists($uploadfile)){
    $uploadfile = $uploaddir . time().'_'.basename($_FILES['file']['name']);
}
if (move_uploaded_file($_FILES['file']['tmp_name'], $config ['default_path'].$uploadfile)) {
    echo $config ['site_url'].$uploadfile;
}