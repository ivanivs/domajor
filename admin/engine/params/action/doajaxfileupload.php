<?php
require_once ('./../../../../config.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query ("SET NAMES 'utf-8'");
require_once ('./../../../../include/functions.php');
if (isset ($_COOKIE['lang']))
{
	require ('language/'.$_COOKIE['lang'].'.php');
}
$error = "";
$msg = "";
$fileElementName = 'fileToUpload';
$i = 0;
$files_count = sizeof($_FILES[$fileElementName]["name"]);

for ($i = 0; $i < $files_count-1; $i++) {	
	if(!empty($_FILES[$fileElementName]['error'][$i]))
	{
		switch($_FILES[$fileElementName]['error'][$i])
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
			if (file_exists($config ['default_path']."upload/select_params/" . $_FILES[$fileElementName]['name'][$i])){
      			$error =$_FILES[$fileElementName]['name'][$i] . " ".$info[8]." ";
      		}
    		else{
    			$msg .= " File Name: " . $_FILES[$fileElementName]['name'][$i] . "<br/>";
    			//$msg .= " File Temp Name: " . $_FILES['fileToUpload']['tmp_name'] . "<br/>";
    			 $msg .= " File Type: " . $_FILES[$fileElementName]['type'][$i] . "<br/>";
				$msg .= " File Size: " . (@filesize($_FILES[$fileElementName]['tmp_name'][$i])/ 1024)."Kb<br>";
				$msg .= $_FILES[$fileElementName]['tmp_name'][$i].'';
				if (move_uploaded_file($_FILES[$fileElementName]['tmp_name'][$i], $config ['default_path']."upload/select_params/" . $_FILES[$fileElementName]['name'][$i]))
				{
					mysql_query ("START TRANSACTION;");
					mysql_query ("INSERT INTO  `ls_params_select_values` (
					`id_params` ,
					`type_value` ,
					`img`
					)
					VALUES (
					'".$_POST['id_select']."' ,
					'0' ,
					'".$_FILES[$fileElementName]['name'][$i]."'
					);");
					$id_new_param = mysql_insert_id();
				} else {
					print "Ne GOOD";
				}
			}
			//for security reason, we force to remove all uploaded file
			@unlink($_FILES[$fileElementName][$i]);		
	}		                      
	/*echo "{";
	echo				"error: '" . $error . "',\n";
	echo				"msg: '" . $msg . "'\n";
	echo "}";*/
}
?>