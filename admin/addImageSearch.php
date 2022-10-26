<?php
require_once ('./../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'UTF8'");
require_once ('./../include/functions.php');
require ('engine/products/functions.php');
require ('engine/params/functions.php');
if (isset ($_GET['lang']))
{
    setcookie ("lang", $_GET['lang'], time() + $config['time_life_cookie']);
}
$lang_file_array = return_my_language ();
foreach ($lang_file_array as $v)
{
    require_once($v);
}
require_once ('./../include/cookie.php');
if (isset ($_COOKIE['lang']) or isset ($_GET['lang']))
{
    if (isset ($_COOKIE['lang']))
    {
        $alt_name_online_lang = $_COOKIE['lang'];
    } else {
        $alt_name_online_lang = $_GET['lang'];
    }
    $info_for_my_lang = return_info_for_lang_for_alt_name($alt_name_online_lang);
    $id_online_lang = $info_for_my_lang['id'];
} else {
    $info_for_my_lang = return_info_for_lang_for_alt_name($config ['default_language']);
    $id_online_lang = $info_for_my_lang['id'];
}
if (isset ($_COOKIE['id_user_online']))
{
    $body = '<title>'.$lang[182].'</title> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<link rel="stylesheet" href="'.$config ['site_url'].'css/dragula.min.css">
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="'.$config ['site_url'].'js/tooltipjs.js"></script> 
<script type="text/javascript" src="'.$config ['site_url'].'js/jquery.tablesorter.min.js"></script> 
<script type="text/javascript" src="'.$config ['site_url'].'js/jquery.MultiFile.js"></script> 
<script type="text/javascript" src="'.$config ['site_url'].'js/jquery.form.js"></script> 
<script type="text/javascript" src="'.$config ['site_url'].'js/jquery.blockUI.js"></script> 
<script type="text/javascript" src="'.$config ['site_url'].'js/dragula.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
	';
    $body .= '
		<script>
		var countUpload = 0;
		var countToUpload = 0;
		function sendFileAddItem(file, id) {
            data = new FormData();
            data.append("fileToUpload", file);
            url = "engine/search/files/doajaxfileupload.php?id='.intval($_GET['id']).'&type='.htmlspecialchars($_GET['type']).'";
            $.ajax({
                data: data,
                type: "POST",
                url: url,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    countUpload++;
                    if (countToUpload==countUpload)
                        location.reload();
                },
            });
        }
            $( document ).ready(function() {
                    $(\'#inputPhoto\').on(\'change\', function(){
                        var inputFile = $("#inputPhoto").prop("files");
                        var countFile = inputFile.length;
                        countToUpload = countFile;
                        for (var i=0; i<countFile; i++){
                            $("#loading").show();
                            sendFileAddItem(inputFile[i], i);
                        }
                    });
            });
        </script>
                        <div align="center">
						<h2>'.$lang[190].'</h2>
                            <form id="uploadForm" action="" method="post" enctype="multipart/form-data">
                                <input name="fileToUpload[]" id="inputPhoto" class="MultiFile" type="file" multiple/>
                            </form>
                            <img id="loading" src="'.$config ['site_url'].'images/admin/loading.gif" style="display:none;"/>   
                            <div id="uploadOutput"></div>
                        </div>
        ';
    $sql = "SELECT * FROM `ls_searchSystemImages` WHERE `searchId` = '".intval($_GET['id'])."' AND `type` = '".mysql_real_escape_string($_GET['type'])."'";
    if ($arrayImage = getArray($sql)){
        $body .= '<div class="row">';
        foreach ($arrayImage as $v){
            $body .= '
                <div class="col-lg-3" style="margin-top:20px; text-align: center;" id="image_'.$v['id'].'">
                    <img src="'.$config ['site_url'].'resize_image.php?filename='.urlencode($v['url']).'&path='.urlencode('upload/searchSystem/').'&const=128&width=262&height=262&r=255&g=255&b=255" class="drag-handle-class img-fluid img-thumbnail">
                </div>
                ';
        }
        $body .= '</div>';
    }
    print $body;
}
?>