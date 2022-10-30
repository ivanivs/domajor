<?php
error_reporting(E_ALL ^ E_NOTICE);
$url_img = urldecode($_GET['filename']);
if (!isset ($_GET['path'])){
    $url_img = '/var/www/domajorcomua/data/www/domajor.com.ua/new/upload/userparams/'.$url_img;
} else {
    $url_img = '/var/www/domajorcomua/data/www/domajor.com.ua/new/'.urldecode($_GET['path']).str_replace(' ', '+',$url_img);
}
if (!file_exists($url_img) or !is_file($url_img)){
    $url_img = '/var/www/kombat_in_ua_usr74/data/www/kombat.in.ua/images/no-image.jpg';
}
$true_name = md5($url_img.$_GET['width'].$_GET['height'].$_GET['const']);
if (!file_exists('img_small/'.$_GET['const'].'_'.$true_name.'.jpg'))
{
    $info_image = getimagesize($url_img);
    $ex = substr($info_image['mime'], strpos($info_image['mime'], '/') + 1);
    list($width, $height) = getimagesize($url_img);
    if (isset ($_GET['width']))
    {
        $new_width = $_GET['width'];
        $new_height = $new_width*$height/$width;
    }
    if (isset ($_GET['width']) and isset ($_GET['height']))
    {
        $new_width_static = $_GET['width'];
        $new_height_static = $_GET['height'];
        if ($new_height_static>=$new_height)
        {
            $create_height = $new_height_static;
            $padding_vertical = ($new_height_static-$new_height)/2;
            $create_widht = $new_width;
        } else {
            $new_height = $new_height_static;
            $create_height = $new_height_static;
            $new_width = $new_height*$width/$height;
            $padding_horizontal = ($new_width_static-$new_width)/2;
        }
    } else {
        if (isset ($_GET['width']))
        {
            $new_width_static = $_GET['width'];
            $new_height_static = $new_width*$height/$width;
            $new_width = $new_width_static;
            $new_height = $new_height_static;
        }
        if (isset ($_GET['height']))
        {
            $new_height_static = $_GET['height'];
            $new_width_static = $width*$new_height/$height;
            $new_width = $new_width_static;
            $new_height = $new_height_static;
        }
    }
    $info_file = pathinfo($url_img);
    //$image_p = imagecreatetruecolor($new_width, $new_height);
    $image_p = imagecreatetruecolor($new_width_static, $new_height_static);
    if (isset ($_GET['r']) and isset ($_GET['g']) and isset ($_GET['b']))
    {
        imagefill($image_p,0,0,imagecolorallocatealpha($image_p,$_GET['r'],$_GET['g'],$_GET['b'],0));
    } else {
        imagefill($image_p,0,0,imagecolorallocatealpha($image_p,255,255,255,0));
    }
    switch ($ex)
    {
        case "jpeg":
            $image = imagecreatefromjpeg($url_img);
            imagecopyresampled($image_p, $image, $padding_horizontal, $padding_vertical, 0, 0, $new_width, $new_height, $width, $height);
            header("Content-type: image/jpeg");
            break;
        case "png":
            $image = imagecreatefrompng($url_img);
            //imagecolortransparent($image,ImageColorAt($image, 1, 1));
            imagecolortransparent($image_p, imagecolorallocate($image, 0, 0, 0));
            imagealphablending($image, true);
            imagesavealpha($image, true);
            imagealphablending($image_p, true);
            imagesavealpha($image_p, true);
            imagecopyresampled($image_p, $image, $padding_horizontal, $padding_vertical, 0, 0, $new_width, $new_height, $width, $height);
            //imagesavealpha($image,TRUE);
            header("Content-type: image/png");
            break;
        case "gif":
            $image = imagecreatefromgif($url_img);
            imagecopyresampled($image_p, $image, $padding_horizontal, $padding_vertical, 0, 0, $new_width, $new_height, $width, $height);
            header("Content-type: image/gif");
            break;
    }
    switch (strtolower($info_file['extension']))
    {
        case "jpg":
            imagejpeg($image_p, 'img_small/'.$_GET['const'].'_'.$true_name.'.jpg',90);
            imagejpeg($image_p, null,90);
            break;
        case "png":
            imagepng($image_p, 'img_small/'.$_GET['const'].'_'.$true_name.'.jpg');
            imagepng($image_p);
            break;
        case "gif":
            imagegif($image_p, 'img_small/'.$_GET['const'].'_'.$true_name.'.jpg');
            imagegif($image_p);
            break;
    }
} else {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: ".'img_small/'.$_GET['const'].'_'.$true_name.'.jpg');
    exit();
    header("Content-type: image/jpeg");
    $image = imagecreatefromjpeg('img_small/'.$_GET['const'].'_'.$true_name.'.jpg');
    imagejpeg($image, null,90);
}
?>
