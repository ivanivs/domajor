<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 3/16/13
 * Time: 5:08 PM
 * To change this template use File | Settings | File Templates.
 */
require ('../../config.php');
require ('../../include/functions.php');
require ('../../admin/engine/params/functions.php');
$mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
mysql_select_db($config ['database']);
mysql_query("SET NAMES `UTF8`;");
if (isset ($_GET['lang']))
{
    setcookie ("lang", $_GET['lang'], time() + $config['time_life_cookie']);
    switch ($_GET['lang']){
        case "ru":
            define('LANG', 2);
            break;
        case "ua":
            define('LANG', 2);
            break;
    }
//    define('LANG', $_GET['lang']);
} else {
    define('LANG', 2);
}
if (isset ($_COOKIE['lang']) or isset ($_GET['lang']))
{
    if (isset ($_GET['lang']))
    {
        $alt_name_online_lang = $_GET['lang'];
    } else {
        $alt_name_online_lang = $_COOKIE['lang'];
    }
    $info_for_my_lang = return_info_for_lang_for_alt_name($alt_name_online_lang);
    $id_online_lang = $info_for_my_lang['id'];
} else {
    $info_for_my_lang = return_info_for_lang_for_alt_name($config ['default_language']);
    $id_online_lang = $info_for_my_lang['id'];
    $alt_name_online_lang = $info_for_my_lang['alt_name'];
}
$array = $_POST['array'];
foreach ($array as $v)
{
    $arr = explode ('|', $v);
    $arraySelect[$arr[1]][] = $arr[2];
}
$arrayParam['select'] = $arraySelect;
if (!isset ($_POST['page']))
{
    $limit = 0;
} else {
    $limit = $_POST['page']*32-32;
}
if (isset ($_POST['sort']))
{
    $sortSql = $_POST['sort'];
} else {
    $sortSql = '';
}
if (isset ($_POST['searchField']) and !empty($_POST['searchField'])){
    $arrayItems = getAllItemsWithParamSEARCH(htmlspecialchars($_POST['searchField']), $limit, $sortSql, $_POST['typeSort']);
    $array_itemsData = $arrayItems;
    $arrayItems['data'] = $arrayItems;
    $countItem = getNumberAllItemsWithParamSEARCH(htmlspecialchars($_POST['searchField']));
} else {
//    echo "SORT: ".$sortSql." TYPE: ".$_POST['typeSort'];
    $arrayItems = getAllItemsWithParam($arrayParam, $limit, $sortSql, $_POST['typeSort']);
    $array_itemsData = $arrayItems;
    $countItem = getNumberAllItemsWithParam($arrayParam);
}
$numberPage = ceil($countItem/32);
$htmlTemplate = file_get_contents($config['site_url'].'templates/'.$config ['default_template'].'/mainOneItem.html');
$i = 0;
if (MOBILEVER==0){
    $keyMobile = 3;
} else {
    $keyMobile = 1;
}
if (isset ($arrayItems['sql'])){
//    echo str_replace("*", "COUNT(*)", $array_itemsData['sql']);
    $results = mysql_query($arrayItems['countSql']);
    $number = @mysql_num_rows ($results);
    $countItem = $number;
    $numberPage = ceil($countItem/33);
}
$arrayItems = $arrayItems['data'];
foreach ($arrayItems as $key => $v)
{
    $oneItem = $htmlTemplate;
//    if ($i==0)
//        $oneItem = '<div class="row'.$active.'" style="margin-top: 10px;">'.$oneItem;
    $oneItem = getOneItem($v, $oneItem);
    $i++;
    if (!isset ($arrayItems[$key+1]) or $i==$keyMobile) {
//        $oneItem .= '</div><hr>';
        $i = 0;
    }
    $items .= $oneItem;
}
$items = str_replace ('{main_sait}', $config['site_url'], $items);
$items = str_replace ('{lang}', $alt_name_online_lang, $items);
$btnXs = '';
if (MOBILEVER==1){
    $btnXs = ' btn-xs';
}
if (!isset ($_POST['sort']))
{
    $sort = '
    <div class="btn-group" role="group" aria-label="...">
        <a href="#" onclick="changeSort(\'price\');" class="btn btn-primary'.$btnXs.'">По цене</a>
        <a href="#" onclick="changeSort(\'popular\');" class="btn btn-default'.$btnXs.'">По популярности</a>
        <a href="#" onclick="changeSort(\'name\');" class="btn btn-default'.$btnXs.'">По имени</a>
        <a href="#" onclick="changeSort(\'date\');" class="btn btn-default'.$btnXs.'">По дате</a>
        <a href="#" onclick="changeSortType(\'1\'); return false;"class="btn btn-default'.$btnXs.'" style="color: red;"><i class="fa fa-long-arrow-up" aria-hidden="true"></i></a>
        <a href="#" onclick="changeSortType(\'0\'); return false;"class="btn btn-default'.$btnXs.'" style="color: red;"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a>
    </div>
    ';
    $sortAjax = 'price';
} else {
    switch ($_POST['sort'])
    {
        case "price":
            $sort = '
            <div class="btn-group" role="group" aria-label="...">
                <a href="#" onclick="changeSort(\'price\');" class="btn btn-primary'.$btnXs.'">По цене</a>
                <a href="#" onclick="changeSort(\'popular\');" class="btn btn-default'.$btnXs.'">По популярности</a>
                <a href="#" onclick="changeSort(\'name\');" class="btn btn-default'.$btnXs.'">По имени</a>
                <a href="#" onclick="changeSort(\'date\');" class="btn btn-default'.$btnXs.'">По дате</a>
                <a href="#" onclick="changeSortType(\'1\'); return false;"class="btn btn-default'.$btnXs.'" style="color: red;"><i class="fa fa-long-arrow-up" aria-hidden="true"></i></a>
                <a href="#" onclick="changeSortType(\'0\'); return false;"class="btn btn-default'.$btnXs.'" style="color: red;"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a>
            </div>
            ';
            $sortAjax = 'price';
            break;
        case "popular":
            $sort = '
            <div class="btn-group" role="group" aria-label="...">
                <a href="#" onclick="changeSort(\'price\');" class="btn btn-default'.$btnXs.'">По цене</a>
                <a href="#" onclick="changeSort(\'popular\');" class="btn btn-primary'.$btnXs.'">По популярности</a>
                <a href="#" onclick="changeSort(\'name\');" class="btn btn-default'.$btnXs.'">По имени</a>
                <a href="#" onclick="changeSort(\'date\');" class="btn btn-default'.$btnXs.'">По дате</a>
                <a href="#" onclick="changeSortType(\'1\'); return false;"class="btn btn-default'.$btnXs.'" style="color: red;"><i class="fa fa-long-arrow-up" aria-hidden="true"></i></a>
                <a href="#" onclick="changeSortType(\'0\'); return false;"class="btn btn-default'.$btnXs.'" style="color: red;"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a>
            </div>
            ';
            $sortAjax = 'popular';
            break;
        case "name":
            $sort = '
            <div class="btn-group" role="group" aria-label="...">
                <a href="#" onclick="changeSort(\'price\');" class="btn btn-default'.$btnXs.'">По цене</a>
                <a href="#" onclick="changeSort(\'popular\');" class="btn btn-default'.$btnXs.'">По популярности</a>
                <a href="#" onclick="changeSort(\'name\');" class="btn btn-primary'.$btnXs.'">По имени</a>
                <a href="#" onclick="changeSort(\'date\');" class="btn btn-default'.$btnXs.'">По дате</a>
                <a href="#" onclick="changeSortType(\'1\'); return false;"class="btn btn-default'.$btnXs.'" style="color: red;"><i class="fa fa-long-arrow-up" aria-hidden="true"></i></a>
                <a href="#" onclick="changeSortType(\'0\'); return false;"class="btn btn-default'.$btnXs.'" style="color: red;"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a>
            </div>
            ';
            $sortAjax = 'name';
            break;
        case "date":
            $sort = '
            <div class="btn-group" role="group" aria-label="...">
                <a href="#" onclick="changeSort(\'price\');" class="btn btn-default'.$btnXs.'">По цене</a>
                <a href="#" onclick="changeSort(\'popular\');" class="btn btn-default'.$btnXs.'">По популярности</a>
                <a href="#" onclick="changeSort(\'name\');" class="btn btn-default'.$btnXs.'">По имени</a>
                <a href="#" onclick="changeSort(\'date\');" class="btn btn-primary'.$btnXs.'">По дате</a>
                <a href="#" onclick="changeSortType(\'1\'); return false;"class="btn btn-default'.$btnXs.'" style="color: red;"><i class="fa fa-long-arrow-up" aria-hidden="true"></i></a>
                <a href="#" onclick="changeSortType(\'0\'); return false;"class="btn btn-default'.$btnXs.'" style="color: red;"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a>
            </div>
            ';
            $sortAjax = 'date';
            break;
    }
}
if ($numberPage>1)
{
    for ($i=1; $i<=$numberPage; $i++)
    {
        if (($i>($_POST['page']-5) and $i<($_POST['page']+5)) or $i == 1 or $i==$numberPage) {
            if ($i == $_POST['page'] or ($i == 1 and !isset ($_POST['page']))) {
                $paginator .= '
                <li class="active"><a href="#" onclick="return false;">' . $i . '</a></li>
                ';
            } else {
                $paginator .= '
                <li><a href="#" onclick="changePage('.$i.'); return false;">' . $i . '</a></li>
                ';
            }
        }
        if ($i==2 and $_POST['page']>6){
            $paginator .= '<li class="disabled"><a href="#" onclick="return false;">...</a></li>';
        }
        if ($i==$numberPage-1 and $_POST['page']<$numberPage-5){
            $paginator .= '<li class="disabled"><a href="#" onclick="return false;">...</a></li>';
        }
    }
}
if ($numberPage>1) {
    if ($numberPage == $_POST['page']) {
        $nextPassive = ' class="disabled"';
        $nextLink = '#';
    } else {
        $nextLink = ' onclick="changePage('.(intval($_POST['page'])+1).'); return false;"';
    }
    if (!isset ($_POST['page']) or $_POST['page'] == 1) {
        $prevPassive = ' class="disabled"';
    } else {
        $prevLink = ' onclick="changePage('.(intval($_POST['page'])-1).'); return false;"';
    }
//    $paginator = '
//        <nav>
//          <ul class="pagination">
//            <li' . $prevPassive . '>
//              <a href="#" aria-label="Previous"'.$prevLink.'>
//                <span aria-hidden="true">&laquo;</span>
//              </a>
//            </li>
//            ' . $paginator . '
//            <li' . $nextPassive . '>
//              <a href="#" aria-label="Next"'.$nextLink.'>
//                <span aria-hidden="true">&raquo;</span>
//              </a>
//            </li>
//          </ul>
//        </nav>
//        ';
}
if (strlen($items)==0)
    $items = '<div class="alert alert-danger">К сожалению по Вашим параметрам товаров не найдено!</div>';
$filtr = '';
if ($arraySelect = getArray("SELECT * FROM `ls_params_select` WHERE `id` <> 5 AND  `id` <> 4 ORDER by `position`")){
    foreach ($arraySelect as $v){
        $filtr .= generateFiltrFromSelectParams($array_itemsData['sql'], $v['id'], $arrayParam['select'][$v['id']]);
    }
}
$arrayOut['filtr'] = $filtr;
switch ($_POST['sort']){
    case "date":
        if ($_POST['typeSort']==0){
            $dateAsk = ' selected';
        } else {
            $dateDesc = ' selected';
        }
        break;
    case "popular":
        if ($_POST['typeSort']==0){
            $popularAsk = ' selected';
        } else {
            $popularDesc = ' selected';
        }
        break;
    case "price":
        if ($_POST['typeSort']==0){
            $priceAsk = ' selected';
        } else {
            $priceDesc = ' selected';
        }
        break;
}
$arrayOut['data'] = '
<div class="row">
    <div class="col-lg-6 col-12 text-center">
        <ul class="pagination_list">'.$paginator.'</ul>
    </div>
    <div class="col-lg-5 col-12">
        <div class="sort-by" style="position: relative;top:0;">
            <label>Сортувати за</label>
            <select onchange="sortSorokaVorona();" id="sortSorokaVorona">
                <option value="dateAsk" '.$dateAsk.'>по даті ↓</option>
                <option value="dateDesc" '.$dateDesc.'>по даті ↑</option>
                <option value="popularAsk" '.$popularAsk.'>по популярності ↓</option>
                <option value="popularDesc" '.$popularDesc.'>по популярності ↑</option>
                <option value="priceAsk" '.$priceAsk.'>по ціні ↓</option>
                <option value="priceDesc" '.$priceDesc.'>по ціні ↑</option>
            </select>
        </div>                
    </div>
</div>
<hr>
<div style="clear:both;" id="itemsScroll"></div>
<div class="row">'.$items.'</div>
<div style="clear:both;"></div>
<div class="row">
     <div class="col-12">
        <ul class="pagination_list">'.$paginator.'<ul/>
    </div>
</div>
<hr>
';
$arrayOut['data'] = str_replace ('YOYO 2', 'YOYO<sup>2</sup>', $arrayOut['data']);
$arrayOut['data'] = str_replace ('Yoyo 2', 'YOYO<sup>2</sup>', $arrayOut['data']);
$arrayOut['data'] = str_replace ('Yoyo', 'YOYO', $arrayOut['data']);
$arrayOut['data'] = str_replace ('Babyzen', 'BABYZEN', $arrayOut['data']);
echo json_encode($arrayOut);
/*<div class="sortFiltr" style="text-align: right;">
    '.$sort.'
</div>*/