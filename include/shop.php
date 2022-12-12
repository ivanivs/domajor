<?php
require ('admin/engine/template/functions.php');
//unset ($arrayItems);
//if ($arrayItems = getArray("SELECT * FROM `ls_items` where `price_2` = 0")){
//    foreach ($arrayItems as $v){
//        mysql_query("UPDATE `ls_items` set `price_2` = '".$v['price_1']."' where `id` = '".$v['id']."';");
//    }
//}
$main_template = return_one_template($config['user_params_29']);
$main_template_array = explode ('<--page-->', $main_template['template']);
if (count($main_template_array)>1)
{
    $main_template = stripslashes($main_template_array[1]);
} else {
    $main_template = $main_template['template'];
    unset ($main_template_array);
}
$array_template = returnSubstrings($main_template, '{template_', '}');
$array_image = returnSubstrings($main_template, '{image_', '}');
$array_text = returnSubstrings($main_template, '{text_', '}');
$array_price = returnSubstrings($main_template, '{price_', '}');
$array_select = returnSubstrings($main_template, '{select_', '}');
if (isset ($_GET['param']) and isset ($_GET['select'])){
    $arrayTmp = Array();
    foreach ($_GET['select'] as $s){
        foreach ($s as $p){
            $arrayTmp[] = $p;
        }
    }
    if (isset ($_GET['page']))
        $page = '&page='.intval($_GET['page']);
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: ".$config ['site_url']."ua/shop/".htmlspecialchars($_GET['trash'])."/?p=".implode(',', $arrayTmp).$page);
    exit();
}
if (isset ($_GET['p']) AND !empty($_GET['p'])){
    $arr = explode(',', $_GET['p']);
    foreach ($arr as $v){
        $oneValue = getOneValue($v);
        $_GET['select'][$oneValue['id_params']][] = $v;
    }
    $_GET['param'] = '';
} else {
    $_GET['param'] = '';
}
if (isset ($_GET['param']) AND !isset($_GET['search']))
{
    if (!isset ($_GET['page']))
    {
        $limit = 0;
    } else {
        $limit = $_GET['page']*32-32;
    }
    if (isset ($_GET['sort']))
    {
        $sortSql = $_GET['sort'];
    } else {
        $sortSql = '';
    }
    $array_itemsData = getAllItemsWithParam($_GET, $limit, $sortSql, 0);
    $array_items = $array_itemsData['data'];
    $countItem = getNumberAllItemsWithParam($_GET);
    $numberPage = ceil($countItem/33);
} else {
    if (!isset ($_GET['page']))
    {
        $limit = 0;
    } else {
        $limit = $_GET['page']*33-33;
    }
    if (isset ($_GET['sort']))
    {
        $sortSql = $_GET['sort'];
    } else {
        $sortSql = '';
    }
    if (isset ($_GET['search']))
    {
        $sql = "SELECT * FROM `ls_searchSystemKeywords` WHERE `keyword` = '".mysql_real_escape_string($_GET['search'])."'";
        if ($info = getOneString($sql)){
            $sql = "SELECT * FROM `ls_searchSystem` WHERE `id` = '".$info['searchId']."' AND `status` = 1";
            if ($infoSearch = getOneString($sql)){

                header("HTTP/1.1 301 Moved Permanently");
                header("Location: ".$config['site_url']."ua/search/".htmlspecialchars($info['keyword']).'/');
                exit();
            }
        }
        $array_items = getAllItemsWithParamSEARCH($_GET['search'], $limit, $sortSql, 1);
        $countItem = getNumberAllItemsWithParamSEARCH($_GET['search']);
        $numberPage = ceil($countItem/33);
        $title = 'Kombat - '.ucfirst(htmlspecialchars($_GET['search']));
    }
}
$brand = '';
if (isset ($_GET['select'][1][0])){
    $brand = getOneValueText(intval($_GET['select'][1][0])).' ';
}
if (isset ($_GET['select'][2][0])){
    if (!isset ($_GET['select'][3][0])) {
        $title = getOneValueText(intval($_GET['select'][2][0])).' '.$brand;
        $value = getOneValue(intval($_GET['select'][2][0]));
        $bodyText = $value['bodyText'];
    } else {
        $title = getOneValueText(intval($_GET['select'][3][0])).' '.$brand.'- '.getOneValueText(intval($_GET['select'][2][0])).' на Kombat.in.ua';
        $value = getOneValue(intval($_GET['select'][3][0]));
        $bodyText = $value['bodyText'];
    }
} else {
    if (isset ($_GET['select'][1][0])){
        $title = getOneValueText(intval($_GET['select'][1][0])).' -  на Kombat.in.ua';
        $value = getOneValue(intval($_GET['select'][1][0]));
        $bodyText = $value['bodyText'];
    }
}
if (!empty($bodyText))
    $infoCategory = $bodyText;
if (isset ($_COOKIE['login']) and $_COOKIE['login']=='admin')
{
    $body .= '<div style="color:white;">Знайдено '.$count_items.' товарів</div>';
}
if (!isset ($_GET['select'][7]))
{
    $idCategory = $_GET['select'][6][0];
} else {
    $idCategory = $_GET['select'][7][0];
}
if (isset ($array_itemsData['sql'])){
//    echo str_replace("*", "COUNT(*)", $array_itemsData['sql']);
    $results = mysql_query($array_itemsData['countSql']);
    $number = @mysql_num_rows ($results);
    $countItem = $number;
    $numberPage = ceil($countItem/33);
}
if ($array_items)
{
    $htmlTemplate = file_get_contents('templates/'.$config ['default_template'].'/oneItemShop.html');
    $i = 0;
    if (MOBILEVER==0){
        $keyMobile = 3;
    } else {
        $keyMobile = 1;
    }
    foreach ($array_items as $key => $v)
    {
        $oneItem = $htmlTemplate;
//        if ($i==0)
//            $oneItem = '<div class="row'.$active.'" style="margin-top: 25px;">'.$oneItem;
        $oneItem = getOneItem($v, $oneItem);
        $i++;
        if (!isset ($array_items[$key+1]) or $i==$keyMobile) {
//            $oneItem .= '</div><hr>';
            $i = 0;
        }
        $items .= $oneItem;
    }
} else {
    $items .= '
    <div class="alert alert-info">
        Нажаль поки немає торару у цій категорії
    </div>
    ';
}
if (isset ($_GET['search']))
{
    $searchLink = 'search='.$_GET['search'].'&amp;';
} else {
    $searchLink = 'param&amp;';
}
$link = $config['site_url'].$_GET['lang'].'/'.$_GET['mode'].'/'.$_GET['trash'].'/?'.$searchLink;
foreach ($_GET['select'] as $key => $v)
{
    foreach ($v as $one){
        $linkArray[] = 'select['.$key.'][]='.$one;
    }
}
$link .= implode ('&amp;', $linkArray);
if ($numberPage>1)
{
    for ($i=1; $i<=$numberPage; $i++)
    {
        if (($i>($_GET['page']-5) and $i<($_GET['page']+5)) or $i == 1 or $i==$numberPage) {
            if ($i == $_GET['page'] or ($i == 1 and !isset ($_GET['page']))) {
                $paginator .= '
                <li class="active"><a href="' . $link . '&amp;page=' . $i . '&sort=' . $_GET['sort'] . '">' . $i . '</a></li>
                ';
            } else {
                $paginator .= '
                <li><a href="' . $link . '&amp;page=' . $i . '&sort=' . $_GET['sort'] . '">' . $i . '</a></li>
                ';
            }
        }
        if ($i==2 and isset ($_GET['page']) and $_GET['page']>6){
            $paginator .= '<li class="disabled"><a href="#">...</a></li>';
        }
        if ($i==$numberPage-1 and $_GET['page']<$numberPage-5 and $numberPage>5){
            $paginator .= '<li class="disabled"><a href="#">...</a></li>';
        }
    }
}
if ($numberPage>1) {
    if ($numberPage == $_GET['page']) {
        $nextPassive = ' class="disabled"';
        $nextLink = '#';
    } else {
        $nextLink = $link . '&amp;page=' . (intval($_GET['page']) + 1) . '&sort=' . $_GET['sort'];
    }
    if (!isset ($_GET['page']) or $_GET['page'] == 1) {
        $prevPassive = ' class="disabled"';
    } else {
        $prevLink = $link . '&amp;page=' . (intval($_GET['page']) - 1) . '&sort=' . $_GET['sort'];
    }
//    $paginator = '
//        <nav>
//          <ul class="pagination">
//            <li' . $prevPassive . '>
//              <a href="'.$prevLink.'" aria-label="Previous">
//                <span aria-hidden="true">&laquo;</span>
//              </a>
//            </li>
//            ' . $paginator . '
//            <li' . $nextPassive . '>
//              <a href="' . $nextLink . '" aria-label="Next">
//                <span aria-hidden="true">&raquo;</span>
//              </a>
//            </li>
//          </ul>
//        </nav>
//        ';
}
if (isset ($_GET['page']))
{
    $thisPage = '&amp;page='.$_GET['page'];
    $thisPageInt = $_GET['page'];
} else {
    $thisPageInt = 1;
}
$addFiltr = '';
if (isset ($_GET['select'][34])){
//    $addFiltr .= '<input type="hidden" class="param" name="select|34|'.intval($_GET['select'][34][0]).'" value="1">';
}
if (isset ($_GET['select'][32])){
//    $addFiltr .= '<input type="hidden" class="param" name="select|32|'.intval($_GET['select'][32][0]).'" value="1">';
}
//if (isset ($_GET['select'][2]))
//    $select2 = '<input type="hidden" class="param" name="select|2|'.intval($_GET['select'][2][0]).'" value="1">';


    $arrayValues = Array();
    foreach ($_GET['select'] as $oneGet){
        foreach ($oneGet as $oneGetValue){
            $arrayValues[] = $oneGetValue;
        }
    }
    if (!isset ($_GET['select'][32]) and !isset ($_GET['search'])){
        $filtr = '';
        if ($arraySelect = getArray("SELECT * FROM `ls_params_select` WHERE `id` <> 5 AND  `id` <> 4  ORDER by `position`")){
            foreach ($arraySelect as $v){
                $filtr .= generateFiltrFromSelectParams($array_itemsData['sql'], $v['id'], $arrayValues, $v['typeSort']);
            }
        }
        $leftBlock = '
            <div class="leftBlock">
                <div id="leftFiltr">'.$filtr.'</div>
                '.$select2.'
                <div id="fixedBlock" class="findBlock" style="z-index:10;"></div>
            </div>
        ';
        $colRightBlock = '  col-lg-9';
    } else {
        $colRightBlock = '  col-lg-12';
    }
    $addH1 = '';
    if (isset ($_GET['search'])){
        $addH1 = '<h1>Пошук: '.htmlspecialchars($_GET['search']).'</h1>';
    }
    if (isset ($_GET['select'][2][0])){
//        $infoCategorySql = getOneString("SELECT * FROM `ls_params_select_values` WHERE `id` = '".$_GET['select'][2][0]."';");
//        $infoCategory = $infoCategorySql['bodyText'];
    }
    if (isset ($_GET['select'][2])){
        $valueBreadcrumb = getOneValueText($_GET['select'][2][0]);
        $title = $valueBreadcrumb.' на Domajor.com.ua';
    } elseif (isset ($_GET['select'][5])) {
//        $valueBreadcrumb = getOneValueText($_GET['select'][5][0]);
//        $title = $valueBreadcrumb.' на Domajor.com.ua';
    }
    $body = '
'.((isset ($_GET['p']) AND $_GET['p']==76) ? '<input type="hidden" class="param" name="select|5|76" value="1">' : '').'
'.((isset ($_GET['p']) AND $_GET['p']==77) ? '<input type="hidden" class="param" name="select|5|77" value="1">' : '').'
<section class="breadcrumb__area pt-60 pb-60 tp-breadcrumb__bg" data-background="" style="">
 <div class="container">
    <div class="row align-items-center">
       <div class="col-xl-7 col-lg-12 col-md-12 col-12">
          <div class="tp-breadcrumb">
             <div class="tp-breadcrumb__link mb-10">
                <span class="breadcrumb-item-active"><a href="'.$config['site_url'].'">Головна</a></span>
                '.$valueBreadcrumb.'
             </div>
             <h2 class="tp-breadcrumb__title">'.$valueBreadcrumb.'</h2>
          </div>
       </div>
    </div>
 </div>
</section>
<div class="container">
    <div class="row" style="margin-top: 30px;">
        <div class="col-lg-10 col-md-12" style="position: relative;">
            <div class="product-sidebar__product-item">
                <input type="hidden" id="searchField" value="'.htmlspecialchars($_GET['search']).'">
                <div class="product-filter-content mb-40">
                    <div class="row align-items-center">
                       <div class="col-sm-6">
                          <div class="product-item-count">
                             <span><b>32</b> з 2</span>
                          </div>
                       </div>
                       <div class="col-sm-6">
                          <div class="product-navtabs d-flex justify-content-end align-items-center">
                             <div class="tp-shop-selector">
                                <select onchange="sortSorokaVorona();" id="sortSorokaVorona" class="form-control">
                                    <option value="dateAsk" selected>по даті ↓</option>                    
                                    <option value="dateDesc">по даті ↑</option>                    
                                    <option value="priceAsk">по ціні ↓</option>                    
                                    <option value="priceDesc">по ціні ↑</option>       
                                </select>
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>
                <div id="allItems" class="blockItems">
                    <div style="clear:both;" id="itemsScroll"></div>
                    <div class="row row-cols-xxl-4 row-cols-xl-4 row-cols-lg-3 row-cols-md-3 row-cols-sm-2 row-cols-1 tpproduct">
                        '.$items.'
                    </div>
                    <div style="clear:both;"></div>
                    <div class="row" style="margin-top:1em;">
                        <div class="col-12">
                            <ul class="pagination_list">'.$paginator.'<ul/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="color: grey;">
                            '.$infoCategory.'
                        </div>
                    </div>
                </div>
                <div style="clear:both;"></div>
                <input type="hidden" id="pageAjax" value="'.$thisPageInt.'">
                <input type="hidden" id="sortAjax" value="'.$sortAjax.'">
                <input type="hidden" id="askDesc" value="1">
                <input type="hidden" id="categoryId" value="'.$idCategory.'">
            </div>
        </div>
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <div class="catagory_price_color">
                '.$leftBlock.'
                '.$addFiltr.'
            </div>
        </div>
    </div>
</div>

    ';
    $js_script .= '
    $(document).ready(function() {
            $(".classStyle li").click(function() {
                $(this).toggleClass("activeFiltrElement");
                if ($(this).find("input").val()==0)
                {
                    $(this).find("input").val(1);
                } else {
                    $(this).find("input").val(0);
                }
                var param = $(this).find("input").attr("name").split("|");
                viewItems();
                goTo("allItems");
            });
            $(".classSize div").click(function() {
                $(this).toggleClass("sizeFiltrButtonActive");
                if ($(this).find("input").val()==0)
                {
                    $(this).find("input").val(1);
                } else {
                    $(this).find("input").val(0);
                }
                 viewItems();
                 goTo("allItems");
            });
        });
    ';

?>