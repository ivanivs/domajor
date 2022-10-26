<?php
function return_all_work()
{
    $sql = "SELECT * FROM `ls_tinydeal` order by `id` DESC;;";
    $results = mysql_query($sql);
    $number = @mysql_num_rows ($results);
    if ($number>0)
    {
            for ($i=0; $i<$number; $i++)
            {	
                    $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
            }	
            return ($array);
    } else {
            return(0);
    }
}
function return_one_work()
{
    print $sql = "SELECT * FROM `ls_tinydeal` FORCE index(time_parc) WHERE `status` = 1 and `processed` = 0 ORDER by `time_parc` LIMIT 0,1;";
    $result = mysql_query($sql);
    $array_work = mysql_fetch_array($result, MYSQL_ASSOC);
    if (!mysql_num_rows($result))
    {
        mysql_query ("UPDATE `ls_tinydeal` set `processed` = 0;");
        $array_work = mysql_fetch_array(mysql_query("SELECT * FROM `ls_tinydeal` FORCE index(time_parc) WHERE `status` = 1 and `processed` = 0 ORDER by `time_parc` LIMIT 0,1;"), MYSQL_ASSOC);
        mysql_query ("UPDATE `ls_tinydeal` set `processed` = 1, `time_parc` = '".time()."' WHERE `id` = '".$array_work['id']."';");
    } else {
        mysql_query ("UPDATE `ls_tinydeal` set `processed` = 1, `time_parc` = '".time()."' WHERE `id` = '".$array_work['id']."';");
    }
    return($array_work);
}
function file_get_contents_my($url)
{
	//sleep(rand(2, 15));
	$timeout = 1;
	$proxy = "127.0.0.1:9050";
	//$url = "http://whatismyip.org/";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8');  
	curl_setopt($ch, CURLOPT_HEADER, 0);  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 500);	
	curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
	curl_setopt($ch, CURLOPT_PROXY , $proxy);
	$page = curl_exec($ch);
	return ($page);
}
function get_one_item_tinydeal($link)
{
    $page = file_get_contents_my($link);
    $articul = returnSubstrings($page, '<li ><span >Артикул: </span><strong>', '</strong>');
    //$body = 'Артикул:'.$articul[0].'<br>';
    $name_item = returnSubstrings($page, '<span class="blue_style">', '</span>');
    $name_item = str_replace ($articul[0], '', $name_item[0]);
    $name_item = trim($name_item);
    //$body .= 'Название товара:'.$name_item.'<br>';
    $price = returnSubstrings($page, '<span class="productSpecialPrice fl">$', '</span>');
    $price = $price[0];
    //$body .= 'Цена товара:'.$price.'<br>';
    $vaga = returnSubstrings($page, '<li><span >Вес доставки: ', 'кг');
    $vaga[0] = str_replace('</span>', '', $vaga[0]);
    //$body .= 'Вага:'.$vaga[0].'<br>';
    $opus = returnSubstrings($page, '<ul class="features_ul">', '</ul>');
    $opus = '<ul>'.$opus[0].'</ul>';
    //$body .= $opus.'<br>';
    $array_image = returnSubstrings($page, 'imgb="', '"');
    /*foreach ($array_image as $v)
    {
        $body .= '<img src="'.$v.'"><br>';
    }*/
    $array[] = $name_item;
    $array[] = $articul[0];
    $array[] = $price;
    $array[] = $vaga[0];
    $array[] = $opus;
    $array[] = $array_image;
    return($array);
}
?>