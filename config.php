<?php
require ('mysqlSwitch.php');
$config ['host']='localhost';
$config ['database']='kombat_in_ua';
$config ['user_datebase']='kombat_in_ua';
//mysqldump -ukombat_in_ua -p8qL3sWtlZSlkgdRl kombat_in_ua > 20.09.2022.sql
$config ['password_datebase']='8qL3sWtlZSlkgdRl';
$config ['default_language_admin']='languages/ru.php';
$config ['default_language_sait']='languages/ru.php';
$config ['default_language']='ua';
$config ['default_path'] = '/var/www/kombat_in_ua_usr74/data/www/kombat.in.ua/';
$config ['default_template'] = 'kombat';
//$config ['default_path'] = '/var/www/ivashka/data/www/dor.net/shop/';
$config ['time_life_cookie'] = 36000; //время жизни кукиксов в секундах, в том числе и для админа
if ($_SERVER['HTTP_HOST']=='kombat.in.ua'){
    $config ['default_template'] = 'kombat';
    $config ['site_url'] = 'https://kombat.in.ua/';
//    define('MOBILEVER', 0);
} else {
    $config ['default_template'] = 'mbobas';
    $config ['site_url'] = 'https://m.skombat.in.ua/';
//    define('MOBILEVER', 1);
}
$config['sms_send'] = 'kombat.in.ua';
$config ['newSystemSaveItems'] = 1;
$config['email_admin'] = 'info@picase.com.ua';
$config['email_password'] = 'Mb2pO0VN';
$config['smtp_host'] = '127.0.0.1';
$config['smtp_port'] = '25';
$config['ssl'] = '0';
$config['tlgrm']['token'] = '5595055585:AAFSQW29bCeCHyyBn4GjCdycnsd5kcb5zNI';
?>