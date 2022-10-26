<?php
/*if (!isset ($_COOKIE['uniq_id']))
{
	$uniq_id = md5($_SERVER['REMOTE_ADDR'].time());
	setcookie ("uniq_id", $uniq_id, time() + $config['time_life_cookie']);
}*/
if (isset ($_GET['logout']))
{
    setcookie ("login", '', time() - 3600, '/');
    setcookie ("password", '', time() - 3600, '/');
    setcookie ("id_user_online", '', time() - 3600, '/');
    header('Location: '.$config ['site_url']);
}
if (isset ($_POST['login']) and isset ($_POST['password']))
{
	$info_for_user = get_user_info_by_login($_POST['login']);
	if (count ($info_for_user)>1)
	{
		if ($info_for_user['accesslevel']==100)
		{
			if ($_POST['login']==$info_for_user['login'] and md5($_POST['password'])==$info_for_user['password'])
			{
				setcookie ("login", $info_for_user['login'], time() + $config['time_life_cookie'], '/');
				setcookie ("password", $info_for_user['password'], time() + $config['time_life_cookie'], '/');
				setcookie ("id_user_online", $info_for_user['id'], time() + $config['time_life_cookie'], '/');
                setcookie ("accessLevel", $info_for_user['accesslevel'], time() + $config['time_life_cookie'], '/');
				mysql_query ("update ls_users set time_login='".time()."';");
				if (!isset ($_POST['inSite']))
					header('Location: '.$config ['site_url'].'admin/index.php');
			} else {
				$error_message = $lang[7];
			}
		} else {
			$error_message = $lang[5];
		}
	} else {
		$error_message = $lang[6];
	}
}
?>
