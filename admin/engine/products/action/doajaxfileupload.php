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
    $info_cardparam = mysql_fetch_array(mysql_query("select * from `ls_cardparam` where `id` = '".$_GET['id_cardparam']."';"), MYSQL_ASSOC);
    $info_param = mysql_fetch_array(mysql_query("select * from `ls_params_image` where `id` = '".$info_cardparam['id_param']."';"), MYSQL_ASSOC);
    $file_name = time().rand(0,100).'_'.$_FILES[$fileElementName]['name'];
    if (move_uploaded_file($_FILES[$fileElementName]['tmp_name'], "./../../../../upload/userparams/" . $file_name))
    {
        if ($info_param['watermark'])
        {
            $info_file = pathinfo("./../../../../upload/userparams/" . $file_name);
            $znak_hw = getimagesize("./../../../../images/watermark.png");
            $foto_hw = getimagesize("./../../../../upload/userparams/" . $file_name);
            $znak = imagecreatefrompng  ("./../../../../images/watermark.png");
            switch (strtolower($info_file['extension']))
            {
                case "jpg":
                    $foto = imagecreatefromjpeg("./../../../../upload/userparams/" . $file_name);
                    break;
                case "png":
                    $foto = imagecreatefrompng("./../../../../upload/userparams/" . $file_name);
                    //imagecolortransparent($image,ImageColorAt($image, 1, 1));
                    /*imagecolortransparent($image_p, imagecolorallocate($image, 0, 0, 0));
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                    imagealphablending($image_p, true);
                    imagesavealpha($image_p, true);
                    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                    //imagesavealpha($image,TRUE);
                    header("Content-type: image/png");*/
                    break;
                case "gif":
                    $foto = imagecreatefromgif("./../../../../upload/userparams/" . $file_name);
                    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                    header("Content-type: image/gif");
                    break;
            }
            imagecopy ($foto, $znak, $foto_hw[0] - $znak_hw[0], $foto_hw[1] - $znak_hw[1], 0, 0, $znak_hw[0], $znak_hw[1]);
            switch (strtolower($info_file['extension']))
            {
                case "jpg":
                    imagejpeg($foto, "./../../../../upload/userparams/" . $file_name,90);
                    break;
                case "png":
                    imagepng($foto, "./../../../../upload/userparams/" . $file_name);
                    imagepng($foto);
                    break;
                case "gif":
                    imagegif($foto, "./../../../../upload/userparams/" . $file_name);
                    imagegif($foto);
                    break;
            }
        }
        mysql_query ("
					INSERT INTO  `ls_values_image` (
					`id_item` ,
					`id_cardparam` ,
					`value`
					)
					VALUES (
					'".$_GET['id_item']."',
					'".$_GET['id_cardparam']."' ,
					'".$file_name."'
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