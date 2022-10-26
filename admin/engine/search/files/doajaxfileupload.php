<?php
require_once ('./../../../../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
require_once ('./../../../../include/functions.php');
$error = "";
$msg = "";
$fileElementName = 'fileToUpload';
$i = 0;
$files_count = sizeof($_FILES[$fileElementName]["name"]);
if(!empty($_FILES[$fileElementName]['error']))
{
    switch($_FILES[$fileElementName]['error'])
    {

        case '1':
            $error = $info[0];
            break;
        case '2':
            $error = $info[1];
            break;
        case '3':
            $error = $info[2];
            break;
        case '4':
            $error = $info[3];
            break;
        case '6':
            $error = $info[4];
            break;
        case '7':
            $error = $info[5];
            break;
        case '8':
            $error = $info[6];
            break;
        case '999':
        default:
            $error = $info[7];
    }
}elseif(empty($_FILES[$fileElementName]['tmp_name'][$i]) || $_FILES[$fileElementName]['tmp_name'][$i] == 'none')
{
    $error = 'No file was uploaded..';
}else
{
    $msg .= " File Name: " . $_FILES[$fileElementName]['name'] . "<br/>";
    //$msg .= " File Temp Name: " . $_FILES['fileToUpload']['tmp_name'] . "<br/>";
    $msg .= " File Type: " . $_FILES[$fileElementName]['type'] . "<br/>";
    $msg .= " File Size: " . (@filesize($_FILES[$fileElementName]['tmp_name'])/ 1024)."Kb";
    $file_name = 'year_'.time().rand(0,100).'_'.$_FILES[$fileElementName]['name'];
    if (move_uploaded_file($_FILES[$fileElementName]['tmp_name'], "./../../../../upload/searchSystem/" . $file_name))
    {
        mysql_query("
        INSERT INTO `ls_searchSystemImages` (`searchId`, `url`,`type`) VALUES (
        '".$_GET['id']."', 
        '".$file_name."',
        '".mysql_real_escape_string($_GET['type'])."'
        );
        ");
        $id_new_param = mysql_insert_id();
        print 'good';
    } else {
        print "Ne GOOD";
    }
    //for security reason, we force to remove all uploaded file
    @unlink($_FILES[$fileElementName][$i]);
}
//	echo "{";
//	echo				"error: '" . $error . "',\n";
//	echo				"msg: '" . $msg . "'\n";
//	echo "}";