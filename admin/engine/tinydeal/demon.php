<?php
require_once ('./../../../config.php');
$link = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
require_once ('./../../../include/functions.php');
require ('../products/functions.php');
require ('functions.php');
require ('./../../../admin/languages/ru.php');
mysql_close($link);
while(1)
{
    $link = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
    mysql_select_db($config ['database']);
    mysql_query ("SET NAMES 'utf-8'");
    $array_work = return_one_work();
    print_r ($array_work);
    if ($array_work)
    {
        $page = file_get_contents_my($array_work['url']);
        $number_item = returnSubstrings($page, '</b> (Всего <b>', '</b>');
        $page = file_get_contents_my($array_work['url'].'?pagesize='.$number_item[0]);
        //$page = file_get_contents_my($array_work['url'].'?pagesize=10');
        $page_only_item_category = returnSubstrings($page, '<span class="fl">', '<span class="fl">');
        $full_link_page = returnSubstrings($page_only_item_category[0], '<a class="p_box_img" href="', '"');
        print count ($full_link_page)."\n";
        foreach ($full_link_page as $v)
        {
            mysql_close($link);
            $pid = pcntl_fork();
            $id_works = $array_work['id'];
            print "ID_WORK-FATHER: ".$id_works."\n";
            $array_pid[] = $pid;
            $link_full = $v;
            if ($pid == -1) {
                 die('could not fork');
            } else if (!$pid) {
                include_once ('one_process.php');
                exit();
            }
            sleep(2);
            $link = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
            mysql_select_db($config ['database']);
            mysql_query ("SET NAMES 'utf-8'");
        }
        mysql_query ("UPDATE `ls_tinydeal` set `processed` = 0 WHERE `id` = '".$array_work['id']."';");
        //exit();
    }
    mysql_close($link);
    print "sleep(900)\n";
    sleep(60);
}
?>