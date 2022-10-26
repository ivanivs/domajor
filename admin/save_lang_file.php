<?php
require_once ('./../config.php');
if (isset ($_COOKIE['id_user_online']))
{
    if (isset ($_POST['text']))
    {
        $filename = "languages/".$_POST['file'];
        if (is_writeable($filename))
        {
            $fh = fopen($filename, "w+");
            $success = fwrite($fh, stripcslashes($_POST['text']));
            fclose($fh);
            print '<span style="color:red;">Зміни успішно збережені</span>';
        } else {
            print '<span style="color:red;">Мовний файл не доступний для запису - '.$filename.'</span>';
        }
    } else {
        print "<span style=\"color:red;\">Параметри мають бути передані</span>";
    }
} else {
    print '<center><img src="'.$config ['site_url'].'images/admin/Supermassive_Black_Hole.jpg"></center>';
}
?>