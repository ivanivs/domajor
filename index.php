<?php$timeStart = microtime(true);session_start();require ('config.php');$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);mysql_select_db($config ['database']);mysql_query ("SET NAMES 'UTF8';");$client_id = '4963064'; // ID приложения$client_secret = '3Zitfqhp7j0fCLDF8SF1'; // Защищённый ключ$redirect_uri = 'http://og-shop.in.ua/ru/register.html'; // Адрес сайтаrequire ('include/functions.php');require ('admin/engine/products/functions.php');require ('admin/engine/params/functions.php');require ('admin/engine/pages/functions.php');require ('admin/engine/reference/functions.php');define('IDLANG', 2);if (isset ($_GET['lang'])){    setcookie ("lang", $_GET['lang'], time() + $config['time_life_cookie']);}if ($infoOldSession = getOneString("SELECT * FROM `ls_cart` WHERE `uniq_user` = '".$_COOKIE['PHPSESSID']."' AND `status` = 1")){    session_regenerate_id();}//if (isset ($_GET['lang']))//{//    setcookie ("lang", $_GET['lang'], time() + $config['time_life_cookie']);//    switch ($_GET['lang']){//        case "ru"://            define('LANG', 2);//            break;//        case "ua"://            define('LANG', 2);//            break;//    }////    define('LANG', $_GET['lang']);//} else {//    define('LANG', 2);//}$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");$berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");$mobile = strpos($_SERVER['HTTP_USER_AGENT'],"Mobile");$symb = strpos($_SERVER['HTTP_USER_AGENT'],"Symbian");$operam = strpos($_SERVER['HTTP_USER_AGENT'],"Opera M");$htc = strpos($_SERVER['HTTP_USER_AGENT'],"HTC_");$fennec = strpos($_SERVER['HTTP_USER_AGENT'],"Fennec/");$winphone = strpos($_SERVER['HTTP_USER_AGENT'],"WindowsPhone");$wp7 = strpos($_SERVER['HTTP_USER_AGENT'],"WP7");$wp8 = strpos($_SERVER['HTTP_USER_AGENT'],"WP8");if (isset ($_GET['full'])){    setcookie ("full", 1, time() + 86400, '/');}////print_r ($_SERVER);$lang_file_array = return_my_language ();if (isset ($_GET['test']))    mail ('ivanivs@gmail.com', "TEST mail", "body test mail");foreach ($lang_file_array as $v){    require_once($v);}require_once ('include/cookie.php');if ($_COOKIE['accessLevel']==100){//    print_r ($_SERVER);}if (isset ($_COOKIE['id_user_online'])){    $infoUser = getOneString("SELECT * FROM `ls_users` where `id` = '".intval($_COOKIE['id_user_online'])."';");}if (isset ($_GET['admin'])){    $_SESSION['admin'] = 1;}$menu_language = return_select_lang ('index.php', '90px', '#F5FAF8', '');if (isset ($_COOKIE['lang']) or isset ($_GET['lang'])){    if (isset ($_GET['lang']))    {        $alt_name_online_lang = $_GET['lang'];    } else {        $alt_name_online_lang = $_COOKIE['lang'];    }    $info_for_my_lang = return_info_for_lang_for_alt_name($alt_name_online_lang);    $id_online_lang = $info_for_my_lang['id'];} else {    $info_for_my_lang = return_info_for_lang_for_alt_name($config ['default_language']);    $id_online_lang = $info_for_my_lang['id'];    $alt_name_online_lang = $info_for_my_lang['alt_name'];}$id_online_lang = 2;$sql = "SELECT `id`,`value` FROM ls_settings;";$results = mysql_query($sql);$number = mysql_num_rows ($results);for ($i=0; $i<$number; $i++){    $array_config_param[] = mysql_fetch_array($results, MYSQL_ASSOC);}foreach ($array_config_param as $key => $v){    $config['user_params_'.$v['id']] = $v['value'];}//print_r ($_SERVER);require ('include/cart_add_item.php');//require ('include/cart_left_menu.php');if ($_SERVER['HTTP_X_REAL_IP']=='31.41.66.85'){    $html = file_get_contents ('templates/'.$config ['default_template'].'/index.html');//    $html = file_get_contents ('templates/'.$config ['default_template'].'/index.html');} else {    $html = file_get_contents ('templates/'.$config ['default_template'].'/index.html');}if ($ipad || $iphone || $android || $palmpre || $ipod || $berry || $mobile || $symb || $operam || $htc || $fennec || $winphone || $wp7 || $wp8 === true) {    $html = str_replace('{header}', file_get_contents('templates/'.$config ['default_template'].'/headerMobile.html'), $html);    define('MOBILEVER', 1);} else {    $html = str_replace('{header}', file_get_contents('templates/'.$config ['default_template'].'/header.html'), $html);    define('MOBILEVER', 0);}if (!isset ($_COOKIE['id_user_online'])){    $loginInSite = file_get_contents('templates/'.$config ['default_template'].'/loginInSite.html');    $html = str_replace('{loginInSite}', $loginInSite, $html);} else {//    $loginInSite = file_get_contents('templates/'.$config ['default_template'].'/loginInSiteUser.html');    if (strlen($infoUser['image'])>0){        $img = '<img src="'.$infoUser['image'].'" class="img-circle" style="width: 30px; display: inline-block;">';    } else {        $img = '<i class="fa fa-user"></i>';    }    $infoInBonus = getOneString("SELECT SUM(bonus) FROM `ls_bonus` where `idUser` = '".$infoUser['id']."' and `type` = 'in'");    $infoOutBonus = getOneString("SELECT SUM(bonus) FROM `ls_bonus` where `idUser` = '".$infoUser['id']."' and `type` = 'out'");    $bonus = $infoInBonus['SUM(bonus)']-$infoOutBonus['SUM(bonus)'];    $loginInSite = '<a href="{main_sait}ua/orders.html"><i class="fal fa-user"></i></a>    <a href="{main_sait}{lang}/index.html?logout" style="color: #ff2e49;"><i class="fa fa-sign-out"></i></a>        ';    $html = str_replace('{loginInSite}', $loginInSite, $html);}require ('include/superOffer.php');$menuClass = '';if (!isset ($_GET['mode'])){    $menuClass = 'show';    $onlyMainPage = file_get_contents('templates/' . $config ['default_template'] . '/onlyMainPage.html');    require ('include/main/categoryList.php');    require ('include/main/lastProduct.php');    require('include/main/slider.php');//    $onlyMainPage = str_replace('{lastProduct}', $lastProduct, $onlyMainPage);//    $onlyMainPage = str_replace('{categoryList}', $categoryList, $onlyMainPage);//    require ('include/main/moduls/lastAdd.php');//    require ('include/main/moduls/popular.php');//    require ('include/main/brandsCarousel.php');//    $onlyMainPage = str_replace('{brandsCarousel}', $brandsCarousel, $onlyMainPage);//    require ('include/main/moduls/actions.php');//    if ($arrayNews = getArray("SELECT * FROM `ls_news` WHERE `id_category` = 1 order by `id` DESC LIMIT 0, 18")){//        $news = '';//        $i = 0;//        foreach ($arrayNews as $key => $v){//            $infoName = mysql_fetch_array(mysql_query("SELECT * FROM `ls_translate` where `id_elements` = '".$v['id']."' and `type` = 'news_name';"), MYSQL_ASSOC);//            $infoShort = mysql_fetch_array(mysql_query("SELECT * FROM `ls_translate` where `id_elements` = '".$v['id']."' and `type` = 'news_short';"), MYSQL_ASSOC);//            $info_category_news = mysql_fetch_array(mysql_query("SELECT `text` FROM `ls_translate` WHERE `type` = 'news_category_n' and `id_lang` = '".$id_online_lang."' and `id_elements` = '1';"), MYSQL_ASSOC);////            if ($_COOKIE['accessLevel']!=100){////                $news .= '////                <div class="span4">////                    <div class="well well-small">////                        <strong>'.$infoName['text'].'</strong>////                        <p>////                            '.$infoShort['text'].'////                        </p>////                        <div style="text-align: right;"><small>'.date('d.m.Y H:i', $v['time']).'</small></div>////                    </div>////                </div>////                ';////            } else {//            $active = '';//            if ($key==0)//                $active = ' active ';//            if ($i==0)//                $news .= '<div class="item'.$active.'">';//            $news .= '//                <div class="col-lg-4 col-xs-12 col-md-12">//                    <div class="mainPageNameCategory"><h2>'.$infoName['text'].'</h2></div>//                    <p>//                        '.$infoShort['text'].'//                    </p>//                    <div style="clear: both;"></div>//                    <div style="text-align: center; margin-top: 10px;"><a href="{main_sait}{lang}/mode/news/'.translit($info_category_news['text']).'_1/'.translit($infoName['text']).'_'.$v['id'].'.html" class="btn btn-xs btn-default">читать полностью...</a></div>//                </div>//                ';//            $i++;//            if (MOBILEVER==0){//                if (!isset ($arrayNews[$key+1]) or $i==3) {//                    $news .= '</div>';//                    $i = 0;//                }//            } else {//                if (!isset ($arrayNews[$key+1]) or $i==1) {//                    $news .= '</div>';//                    $i = 0;//                }//            }//        }////    }    $actionText = '    <div class="row">        <div id="col-lg-12">            <div id="newsRotate" class="carousel slide" data-ride="carousel">                <!-- Indicators -->                <ol class="carousel-indicators">                    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>                    <li data-target="#carousel-example-generic" data-slide-to="1"></li>                    <li data-target="#carousel-example-generic" data-slide-to="2"></li>                </ol>                <!-- Wrapper for slides -->                <div class="carousel-inner" role="listbox">                    '.$news.'                </div>                <!-- Controls -->                <a class="left carousel-control" href="#newsRotate" role="button" data-slide="prev">                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>                    <span class="sr-only">Previous</span>                </a>                <a class="right carousel-control" href="#newsRotate" role="button" data-slide="next">                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>                    <span class="sr-only">Next</span>                </a>            </div>        </div>    </div>    ';    $leftBanner = '';    if ($array = getArray("SELECT * FROM `ls_baner` WHERE `main` = 0 LIMIT 0,5")){        foreach ($array as $key => $v){            $active = '';            if ($key == 0)                $active = ' active';            $leftBanner .= '            <div class="row" style="margin: 5px 0px; margin-left: -15px;">                <div class="col-lg-12">                    <a href="'.$v['link'].'"><img src="'.$config['site_url'].$v['file'].'" alt="..."></a>                </div>            </div>            ';        }    }    $onlyMainPage = str_replace('{actionText}', $actionText, $onlyMainPage);    $onlyMainPage = str_replace('{leftBaner}', $leftBanner, $onlyMainPage);    $html = str_replace('{onlyMainPage}', $onlyMainPage, $html);    $carousel = '';    if ($array = getArray("SELECT * FROM `ls_baner` WHERE `main` = 1 LIMIT 0,5")){        foreach ($array as $key => $v){            $active = '';            if ($key == 0)                $active = ' active';            $carousel .= '        <div class="item'.$active.'">             <a href="'.$v['link'].'"><img src="'.$config['site_url'].$v['file'].'" alt="..."></a>        </div>        ';        }    }//    $title = "Спортивная одежда и кроссовки. Широкий ассортимент. Nike, Jordan, Under Armour, Adidas, McDavid. Все для баскетбола.";//    $keywords = "Баскетбольные кроссовки, оригинальная обувь, обувь в наличии, баскетбольная обувь, баскетбольная экипировка";//    $description = "Интернет магазин OG-SHOP предлагает оригинальную обувь, баскетбольные кроссовки и экипировка. Одежда по приятным ценам.";    $title = 'Domajor.com.ua - Музичні інструменти';    $dopLinkCatItem = '';    $dopStyleUlCatItem = '';    $dopStyleRightMenu = '';} else {    $html = str_replace('{bg}', '', $html);    $html = str_replace('{onlyMainPage}', '', $html);    $html = str_replace('{popularBlock}', '', $html);    $html = str_replace('{lastAddBlock}', '', $html);    $html = str_replace('{actionsBlock}', '', $html);    $dopLinkCatItem = " onclick=\"$('#leftMenuAbs').toggle(200);\" style=\"cursor: pointer;\" ";    $dopStyleUlCatItem = ' style="display: none;" ';    $dopStyleRightMenu = ' border-left: 1px solid #e31e24; border-bottom: 1px solid #ed3126; border-right: 1px solid #ed3126; ';    switch ($_GET['mode'])    {        case "ss":            require ('include/searchSystem.php');            break;        case "shortLink":            if ($info = getOneString("SELECT * FROM `ls_shortLink` WHERE `id` = '".intval($_GET['id'])."'")){                header("HTTP/1.1 301 Moved Permanently");                header("Location: ".mb_substr($config['site_url'], 0, -1).$info['url']);                exit();            } else {                $body .= '                <div class="alert alert-danger" style="margin-top: 20px;">                    Нажаль нічього не знайдено                </div>                ';            }            break;        case "successPay":            $body .= '            <div class="row" style="margin-top: 150px; margin-bottom: 200px;">                <div style="text-align: center;"><h1>ДЯКУЄМО ЗА ПОКУПКУ!</h1></div>                <div class="col-lg-6 col-lg-offset-3">                    <div class="alert alert-info" style="text-align: center;">                        <div>Очікуйте на дзвінок менеджера нашого інтернет-магазину.</div>                    </div>                </div>            </div>            ';            break;        case "test":            go_sms('+380949091035', 'TEST');            break;        case "recovery":            require ('include/recovery.php');            break;        case "resultPage":            require ('include/resultPage.php');            break;        case "favorite":            require ('include/cabinet.php');            break;        case "changePassword":            require ('include/cabinet.php');            break;        case "bonus":            require ('include/cabinet.php');            break;        case "cabinet":            require ('include/cabinet.php');            break;        case "reviews":            require ('include/reviews.php');            break;        case "shop":            require ('include/shop.php');//            require ('include/shop/index.php');            break;        case "item":            require ('include/item/index.php');            break;        case "cart"://            require ('include/cart.php');            require ('include/cart/index.php');            break;        case "content":            require ('include/content.php');            break;        case "news":            require ('include/news.php');            break;        case "contact":            require ('include/contact.php');            break;        case "register":            require ('include/register.php');            break;        case "userInfo":            require ('include/cabinet.php');            break;        case "calculator":            require ('include/calculator.php');            break;        case "orders":            require ('include/cabinet.php');            break;        case "vkAuth":            require ('include/vkAuth.php');            break;        case "ajax":            switch ($_GET['ajax']){                case "getItemByColor":                    $infoItem = getOneString("SELECT * FROM `ls_items` WHERE `id` = '".intval($_POST['id'])."'");                    $sql = "SELECT * FROM `ls_items` WHERE `text_1` = '".$infoItem['text_1']."' AND `select_2` = '".intval($_POST['color'])."' AND `select_3` = '".intval($_POST['size'])."'";                    if ($info = getOneString($sql)){                        echo getItemLink($info);                    }                    break;                case "addItemToCartWithVerification":                    if (isset ($_COOKIE['id_user_online'])){                        $userID = $_COOKIE['id_user_online'];                    }                    $infoItem = getOneString("SELECT * FROM `ls_items` WHERE `id` = '".intval($_POST['id'])."'");                    $priceArray = getPriceArray($infoItem);                        if ($infoCount = getOneString("SELECT * FROM `ls_cart` WHERE `id_item` = '".intval($_POST['id'])."' and `uniq_user` = '".$_COOKIE['PHPSESSID']."' AND `price` = '".$priceArray['price']."' AND `idUser` = '".$userID."' AND `status` = 0;")){                            $sql = "UPDATE `ls_cart` SET `number` = `number` + 1 where `id` = '".$infoCount['id']."';";                        } else {                            if (!isset ($_POST['number']) or $_POST['number']==0)                                $_POST['number'] = 1;                                $sql = "                                    INSERT INTO `ls_cart`                                    (                                    `id_item`,                                    `uniq_user`,                                    `other_param`,                                    `idUser`,                                    `time` ,                                    `number`,                                    `price`                                    ) VALUES (                                    '".$_POST['id']."',                                    '".$_COOKIE['PHPSESSID']."',                                    '".$_POST['array']."',                                    '".$userID."',                                    '".time()."' ,                                    '".$_POST['number']."',                                    '".mysql_real_escape_string($priceArray['price'])."'                                    );                                    ";                        }                        if (mysql_query($sql))                        {                            print '                            <div class="modal" tabindex="-1" id="addToCartModalNew">                              <div class="modal-dialog">                                <div class="modal-content">                                  <div class="modal-header">                                    <h5 class="modal-title">Товар додано до корзини</h5>                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>                                  </div>                                  <div class="modal-body">                                        <div class="alert alert-success">                                        <strong>Вітаємо!</strong> Товар успішно додано до кошика                                    </div>                                  </div>                                  <div class="modal-footer">                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>                                    <a href="'.$config['site_url'].$alt_name_online_lang.'/mode/cart.html" class="btn btn-success">Оформити замовлення</a>                                  </div>                                </div>                              </div>                            </div>                            ';                        } else {                            print '<div style="color:red; font-weight:bold;" class="alert alert-danger">Помилка додавання в корзину</div>';                        }                    break;                case "getColors":                    $color = '';                    $infoItem = getOneString("SELECT * FROM `ls_items` WHERE `id` = '".intval($_POST['id'])."'");                    if ($arrayColors = getArray("SELECT * FROM `ls_items` WHERE `text_1` = '".mysql_real_escape_string($infoItem['text_1'])."' AND `select_3` = '".intval($_POST['size'])."'")){                        foreach ($arrayColors as $oneColor){                            $color .= '<option value="'.$oneColor['select_2'].'">'.getOneValueText($oneColor['select_2']).'</option>';                        }                    }                    echo $color;                    break;                case "getModalWithShortLink":                    require ('include/ajax/getModalWithShortLink.php');                    break;                case "checkoutLiqpay":                    require ('include/ajax/checkoutLiqpay.php');                    break;                case "sendMailFoundCheaper":                    require ('include/ajax/sendMailFoundCheaper.php');                    break;                case "minCostMail":                    require ('include/ajax/minCostMail.php');                    break;                case "sendReview":                    require ('include/ajax/sendReview.php');                    break;                case "phoneCallMe":                    if (mysql_query("INSERT INTO  `ls_orders` (                        `uniq_user`,                        `site`,                        `number_phone` ,                        `dop_info` ,                        `time` ,                        `mobile`                        )                        VALUES (                        '".$_COOKIE['PHPSESSID']."',                        'Slam.city',                        '".mysql_real_escape_string($_POST['phone'])."',                        '".mysql_real_escape_string('<div class="alert alert-success">Перезвоните мне!!!</div>')."' ,                        '".time()."' ,                        '".MOBILEVER."'                        );")){                        mysql_query("                        INSERT INTO  `ls_cart` (                            `id_item` ,                            `uniq_user` ,                            `other_param` ,                            `time` ,                            `idUser` ,                            `status` ,                            `number`                            )                            VALUES (                            '1',                            '".$_COOKIE['PHPSESSID']."', NULL ,  '',  '',  '1',  '1'                            );                        ");                        echo '<div style="font-family: \'Open Sans Condensed\', sans-serif; color: #FFF; font-size: 16px;">Спасибо, ждите звонка!</div>';                    }                    break;                case "getNewPassword":                    require ('include/ajax/getNewPassword.php');                    break;                case "search":                    require ('include/ajax/search.php');                    break;                case "serverUrl":                    require ('include/ajax/serverUrl.php');                    break;                case "saveOrder":                    require ('include/ajax/saveOrder.php');                    break;                case "rmFavorite":                    mysql_query("DELETE FROM `ls_favorite` where `id` = '".intval($_POST['idсф'])."';");                    break;                case "addToFavorite":                    require ('include/ajax/addToFavorite.php');                    break;                case "removeItemFromCart":                    $infoCart = getOneString("SELECT * FROM `ls_cart` WHERE `id` = '".intval($_POST['id'])."';");                    if (($infoCart['uniq_user']==$_COOKIE['PHPSESSID'] or $infoCart['idUser']==$infoUser['id']) and $infoCart['status']==0){                        mysql_query("DELETE FROM `ls_cart` WHERE `id` = '".$infoCart['id']."';");                        echo 1;                    } else {                        echo 0;                    }                    break;                case "getCart":                    require ('include/ajax/getCart.php');                    break;                case "saveUserInfo":                    require ('include/ajax/saveUserInfo.php');                    break;                case "savePassword":                    require ('include/ajax/savePassword.php');                    break;                case "loginInSite":                    require ('include/ajax/loginInSite.php');                    break;                case "register":                    require ('include/ajax/register.php');                    break;                case "checkDostavka":                    require ('include/checkDostavka.php');                    break;                case "getWarehouse":                    if (isset ($_GET['type']) AND intval($_GET['type'])==0){                        $type = Array("'9a68df70-0267-42a8-bb5c-37f427e36ee4'", "'841339c7-591a-42e2-8233-7a0a00f0ed6f'");                    } else {                        $type = Array("'95dc212d-479c-4ffb-a8ab-8c1b9073d0bc'", "'f9316480-5f2d-425d-bc2c-ac7cd29decf0'", "'6f8c7162-4b72-4b0a-88e5-906948c6a92f'");                    }                    echo getWarehouse(intval($_GET['city']),0,$type, $_GET['search']);                    break;                case "getCity":                    echo getCity(intval($_POST['region']));                    break;                case "getReviewPhone":                    if ($_COOKIE['accessLevel']==100){                        echo 1;                    } else {                        if (count($_POST['phone'])==0){                            echo 0;                        } else {                            $infoCount = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `ls_orders` WHERE `number_phone` = '".mysql_real_escape_string($_POST['phone'])."' or `number_phone` = '".str_replace("+38", "", mysql_real_escape_string($_POST['phone']))."'"), MYSQL_ASSOC);                            if ($infoCount['COUNT(*)']==0){                                echo 0;                            } else {                                echo 1;                            }                        }                    }                    break;                case "saveReview":                    $infoCount = mysql_fetch_array(mysql_query("SELECT * FROM `ls_orders` WHERE `number_phone` = '".mysql_real_escape_string($_POST['phone'])."' or `number_phone` = '".str_replace("+38", "", mysql_real_escape_string($_POST['phone']))."'"), MYSQL_ASSOC);                    $sql = "                    INSERT INTO  `ls_reviews` (                        `name` ,                        `phone` ,                        `idOrder` ,                        `body` ,                        `time`                        )                        VALUES (                        '".mysql_real_escape_string($_POST['name'])."',                        '".mysql_real_escape_string($_POST['phone'])."',                        '".$infoCount['id']."' ,                        '".mysql_real_escape_string($_POST['body'])."' ,                        '".time()."'                        );                    ";                    if (mysql_query($sql)){                        $review['id'] = mysql_insert_id();                        $review['name'] = $_POST['name'];                        $review['phone'] = $_POST['phone'];                        $review['body'] = $_POST['body'];                        $review['time'] = time();                        echo getOneReview($review);                    }                    break;                case "removeReview":                    if ($_COOKIE['accessLevel']==100){                        mysql_query("DELETE FROM `ls_reviews` where `id` = '".intval($_POST['id'])."';");                    }                    break;            }            exit();            break;    }}require ('include/generate_menu.php');if ($arraySelectValues = getValuesSelectParamNotEmpty(1)){    $countAll = count($arraySelectValues);    $inList = ceil($countAll/3);    $menu = '';    $menu2 = '';    $menu3 = '';//    foreach ($arraySelectValues as $key => $v){//        if ($key<=$inList) {//            $menu .= '<a href="'.$config['site_url'].'ua/shop/'.translit($v['text']).'/?p='.$v['id'].'"><i class="fa fa-angle-right"></i> ' . $v['text'] . '</a>';//        } elseif ($key<=$inList*2){//            $menu2 .= '<a href="'.$config['site_url'].'ua/shop/'.translit($v['text']).'/?p='.$v['id'].'"><i class="fa fa-angle-right"></i> ' . $v['text'] . '</a>';//        } else {//            $menu3 .= '<a href="'.$config['site_url'].'ua/shop/'.translit($v['text']).'/?p='.$v['id'].'"><i class="fa fa-angle-right"></i> ' . $v['text'] . '</a>';//        }//    }    if ($arrayMenu1 = getValuesSelectParamWithParent(6, 210)){        foreach ($arrayMenu1 as $v){            $menu .= '<a href="'.$config['site_url'].'ua/shop/'.translit($v['text']).'/?p='.$v['id'].'"><i class="fa fa-angle-right"></i> ' . $v['text'] . '</a>';        }    }    if ($arrayMenu1 = getValuesSelectParamWithParent(6, 211)){        foreach ($arrayMenu1 as $v){            $menu2 .= '<a href="'.$config['site_url'].'ua/shop/'.translit($v['text']).'/?p='.$v['id'].'"><i class="fa fa-angle-right"></i> ' . $v['text'] . '</a>';        }    }    if ($arrayMenu1 = getValuesSelectParamWithParent(6, 212)){        foreach ($arrayMenu1 as $v){            $menu3 .= '<a href="'.$config['site_url'].'ua/shop/'.translit($v['text']).'/?p='.$v['id'].'"><i class="fa fa-angle-right"></i> ' . $v['text'] . '</a>';        }    }    $menu  = '<div class="single_megamenu">                    <div class="items_list">                        <a href="https://kombat.in.ua/ua/shop/Odyag/?p=210" style="font-weight:bold; font-size:1.1em;">Одяг</a>                        '.$menu.'                    </div>                </div><div class="single_megamenu">                    <div class="items_list">                        <a href="https://kombat.in.ua/ua/shop/Takt/?p=211" style="font-weight:bold; font-size:1.1em;">Тактичне спорядження</a>                        '.$menu2.'                    </div>                </div><div class="single_megamenu">                    <div class="items_list">                        <a href="https://kombat.in.ua/ua/shop/Vzyttya/?p=211" style="font-weight:bold; font-size:1.1em;">Взуття</a>                        '.$menu3.'                    </div>                </div>                ';}if (isset ($js_script)){    $js_script = '<script>'.$js_script.'</script>';} else {    $js_script = '';}if (!isset ($body))    $body = '';if (!isset ($title))    $title = '';if (!isset ($keywords))    $keywords = '';if (!isset ($description))    $description = '';if (!isset ($cart_left_menu))    $cart_left_menu = '';if (!isset ($name_left_block))    $name_left_block = '';if (!isset ($left_block))    $left_block = '';$include = 1;require ('include/ajax/cartBlock.php');$url = 'http://oauth.vk.com/authorize';$params = array(    'client_id'     => $client_id,    'redirect_uri'  => $redirect_uri,    'response_type' => 'code');if (!isset ($origin))    $origin = '<meta property="og:image" content="http://bobas.ua/templates/bobas/images/logo.png">';$link = '<p><a href="' . $url . '?' . urldecode(http_build_query($params)) . '"><img src="http://picase.com.ua/images/vk.png"></a></p>';require ('include/size.php');require ('include/mainPage_blog.php');require ('include/last_comment.php');if (MOBILEVER==0){    $html = str_replace ('{footer}', file_get_contents('templates/'.$config ['default_template'].'/footer.html'), $html);} else {    $html = str_replace ('{footer}', file_get_contents('templates/'.$config ['default_template'].'/footerMobile.html'), $html);}require ('include/generateMenu.php');$html = str_replace ('{baner}', $carousel, $html);$html = str_replace ('{dopLinkCatItem}', $dopLinkCatItem, $html);$html = str_replace ('{dopStyleUlCatItem}', $dopStyleUlCatItem, $html);$html = str_replace ('{dopStyleRightMenu}', $dopStyleRightMenu, $html);$html = str_replace ('{size_man}', $sizeMan, $html);$html = str_replace ('{last_comment}', $last_comment, $html);$html = str_replace ('{mainPage_blog}', $mainPage_blog, $html);$html = str_replace ('{size_woman}', $sizeWoman, $html);$html = str_replace ('{size_child}', $sizeChild, $html);$html = str_replace ('{superOffer}', $superOffer, $html);$html = str_replace ('{linkVk}', $link, $html);$html = str_replace ('{origin}', $origin, $html);$html = str_replace ('{body}', $body, $html);$html = str_replace ('{year}', date('Y', time()), $html);$html = str_replace ('{template}', $config['site_url'].'templates/'.$config ['default_template'].'/', $html);$html = str_replace ('{title}', $title, $html);$html = str_replace ('{main_sait}', $config['site_url'], $html);$html = str_replace ('{keywords}', $keywords, $html);$html = str_replace ('{description}', $description, $html);$html = str_replace ('{cart_left_menu}', $cart_left_menu, $html);$html = str_replace ('{name_left_block}', $name_left_block, $html);$html = str_replace ('{left_block}', $left_block, $html);$html = str_replace ('{cart_block}', $cartBlock, $html);$html = str_replace ('{menu}', $menu, $html);$html = str_replace ('{menuClass}', $menuClass, $html);$html = str_replace ('{summ_war}', $config['user_params_41'], $html);$html = str_replace ('width: 580px;', 'width: 100%', $html);if (!isset ($buttonBuy))    $buttonBuy = '';$html = str_replace ('{buttonBuy}', $buttonBuy, $html);$html = str_replace ('{script}', $js_script, $html);$shortLink = '';if ($_COOKIE['accessLevel']==100){    $shortLink = '<a href="#" class="btn btn-default btn-sm" onclick="getModalWithShortLink(\''.$_SERVER['REQUEST_URI'].'\');">Short link</a>';}$html = str_replace ('{shortLink}', $shortLink, $html);if (MOBILEVER==1){    $html = str_replace ('https://sorokavorona.eu/', 'https://m.sorokavorona.eu/', $html);}$html = str_replace ('http://', 'https://', $html);$html = str_replace ('{lang}', $alt_name_online_lang, $html);if (substr_count($_SERVER['REQUEST_URI'], '&')){    $t = '&';} else {    $t = '?';}//$html = str_replace ('{fullVersion}', 'http://slam.city'.$_SERVER['REQUEST_URI'].$t.'full', $html);if ($config['user_params_30'] and $_GET['mode']=='shop'){    $html = str_replace ('{filtr_start}', 'change_filtr(); ', $html);} else {    $html = str_replace ('{filtr_start}', '', $html);}$html = str_replace ('/ru/', '/ua/', $html);$html = language ($html, '');print $html;?>