<?php
require ('mysqlSwitch.php');
$config ['host']='localhost';
$config ['database']='domajor_new';
$config ['user_datebase']='domajor_new_usr';
//mysqldump -ukombat_in_ua -p8qL3sWtlZSlkgdRl kombat_in_ua > 20.09.2022.sql
$config ['password_datebase']='jgdJSSwe0j8WXJ5E';
$config ['default_language_admin']='languages/ru.php';
$config ['default_language_sait']='languages/ru.php';
$config ['default_language']='ua';
$config ['default_path'] = '/var/www/domajorcomua/data/www/domajor.com.ua/new/';
$config ['default_template'] = 'domajor';
//$config ['default_path'] = '/var/www/ivashka/data/www/dor.net/shop/';
$config ['time_life_cookie'] = 36000; //время жизни кукиксов в секундах, в том числе и для админа
$config ['default_template'] = 'domajor';
$config ['site_url'] = 'https://domajor.com.ua/';
$config['sms_send'] = 'domajor.com.ua';
$config ['newSystemSaveItems'] = 1;
$config['email_admin'] = 'info@picase.com.ua';
$config['email_password'] = 'Mb2pO0VN';
$config['smtp_host'] = '127.0.0.1';
$config['smtp_port'] = '25';
$config['ssl'] = '0';
$config['tlgrm']['token'] = '5595055585:AAFSQW29bCeCHyyBn4GjCdycnsd5kcb5zNI';
?>