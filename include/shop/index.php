<?php
/**
 * Created by PhpStorm.
 * User: ivashka
 * Date: 6/5/15
 * Time: 4:27 PM
 */
if ($_GET['category']!=0){
    $infoCategory = getOneValue(intval($_GET['category']));
    $title = $infoCategory['text'];
} else {
    $title = 'Bobas.ua';
}
if (isset ($_GET['category'])){
    $infoParent = getOneString("SELECT COUNT(*) FROM `ls_params_select_values` WHERE `parent_param_id` = '".intval($_GET['category'])."'");
    if ($infoParent['COUNT(*)']>0){
        $sql = "
        SELECT
            `ls_params_select_values`.*,
            `ls_translate`.`text`
        FROM
            `ls_params_select_values`
        JOIN
            `ls_translate`
        ON
          `ls_params_select_values`.`id` = `ls_translate`.`id_elements`
       where
          `ls_translate`.`type` = 'select_value'
       and
        `ls_params_select_values`.`id_params` = 1
       and
        `ls_params_select_values`.`parent_param_id` = '".intval($_GET['category'])."'
        ORDER by `ls_params_select_values`.`position` DESC
        ";
    } else {
        if ($infoCategory['parent_param_id']!=0){
            $sql = "
            SELECT
                `ls_params_select_values`.*,
                `ls_translate`.`text`
            FROM
                `ls_params_select_values`
            JOIN
                `ls_translate`
            ON
              `ls_params_select_values`.`id` = `ls_translate`.`id_elements`
           where
              `ls_translate`.`type` = 'select_value'
           and
            `ls_params_select_values`.`id_params` = 1
           and
            `ls_params_select_values`.`parent_param_id` = '".$infoCategory['parent_param_id']."'
            ORDER by `ls_params_select_values`.`position` DESC
            ";
        } else {
            $sql = "
            SELECT
                `ls_params_select_values`.*,
                `ls_translate`.`text`
            FROM
                `ls_params_select_values`
            JOIN
                `ls_translate`
            ON
              `ls_params_select_values`.`id` = `ls_translate`.`id_elements`
           where
              `ls_translate`.`type` = 'select_value'
           and
            `ls_params_select_values`.`id_params` = 1
           and
            `ls_params_select_values`.`parent_param_id` = 0
            ORDER by `ls_params_select_values`.`position` DESC
            ";
        }
    }
    if ($arrayLink = getArray($sql)){
        $menu = '';
        if (!isset ($_GET['category']))
        {
            $allActive = ' active';
        }
        if (!isset ($_GET['category']) and isset ($_GET['producer'])){
            $mark = getOneValue(intval($_GET['producer']));
            $all = '<li class="'.$allActive.'"><a href="'.$config['site_url'].'ru/producer/'.translit($mark['text']).'/'.$mark['id'].'">Всі</a></li>';
        } else {
            $all = '<li class="'.$allActive.'"><a href="'.$config['site_url'].'ru/shop/'.translit($infoCategory['text']).'/'.intval($_GET['category']).'">Всі</a></li>';
        }
        foreach ($arrayLink as $v){
            if (isset ($_GET['producer'])){
                if (!isset ($_GET['model'])){
                    $link = $config['site_url'].'ru/pc/'.intval($v['id']).'/'.intval($_GET['producer']);
                } else {
                    $link = $config['site_url'].'ru/pc/'.intval($v['id']).'/'.intval($_GET['producer']).'/'.intval($_GET['model']);
                    $infoCountItemInCategory = getOneString("SELECT COUNT(*) FROM `ls_items` JOIN `ls_markModel` ON `ls_markModel`.`idItem` = `ls_items`.`id` where `ls_markModel`.`mark` = '".intval($_GET['producer'])."' and `ls_markModel`.`model` = '".intval($_GET['model'])."' and `ls_items`.`select_1` = '".$v['id']."';");
                }
            } else {
                $infoParent = getOneString("SELECT COUNT(*) FROM `ls_params_select_values` WHERE `parent_param_id` = '".$v['id']."'");
                if ($infoParent['COUNT(*)']==0){
                    $infoCountItemInCategory = getOneString("SELECT COUNT(*) FROM `ls_items` where `select_1` = '".$v['id']."';");
                } else {
                    $arrayChildId = array();
                    if ($arrayChild = getArray("SELECT `id` FROM `ls_params_select_values` WHERE `parent_param_id` = '".$v['id']."'")){
                        foreach ($arrayChild as $oneChild){
                            $arrayChildId[] = $oneChild['id'];
                        }
                    }
                    $sql = "SELECT
                                COUNT(*)
                            FROM
                                `ls_items`
                            where
                                `ls_items`.`select_1` IN (".implode(',', $arrayChildId).");";
                    $infoCountItemInCategory = getOneString($sql);
                }
                $link = $config['site_url'].'ru/shop/'.translit($v['text']).'/'.intval($v['id']);
            }
            if ($infoCountItemInCategory['COUNT(*)']>0)
                if ($v['id']==$infoCategory['id']){
                    $menu .= '<li class="active"><a href="'.$link.'">'.$v['text'].' ['.$infoCountItemInCategory['COUNT(*)'].']</a></li>';
                } else {
                    $menu .= '<li><a href="'.$link.'">'.$v['text'].' ['.$infoCountItemInCategory['COUNT(*)'].']</a></li>';
                }
        }
    } else {
        if ($arrayLink = getArray("SELECT `ls_params_select_values`.*, `ls_translate`.`text` FROM `ls_params_select_values` JOIN `ls_translate` ON `ls_params_select_values`.`id` = `ls_translate`.`id_elements`  where `ls_params_select_values`.`parent_param_id` = '".$infoCategory['parent_param_id']."' and `ls_translate`.`type` = 'select_value' and `ls_params_select_values`.`id_params` = 2")){
            $menu = '';
            foreach ($arrayLink as $v){
                if (isset ($_GET['producer'])){
                    if (!isset ($_GET['model'])){
                        $link = $config['site_url'].'ru/pc/'.intval($v['id']).'/'.intval($_GET['producer']);
                        $infoCountItemInCategory = getOneString("SELECT COUNT(*) FROM `ls_items` JOIN `ls_markModel` ON `ls_markModel`.`idItem` = `ls_items`.`id` where `ls_markModel`.`mark` = '".intval($_GET['producer'])."' and `ls_items`.`select_1` = '".$v['id']."';");
                    } else {
                        $link = $config['site_url'].'ru/pc/'.intval($v['id']).'/'.intval($_GET['producer']).'/'.intval($_GET['model']);
                        $infoCountItemInCategory = getOneString("SELECT COUNT(*) FROM `ls_items` JOIN `ls_markModel` ON `ls_markModel`.`idItem` = `ls_items`.`id` where `ls_markModel`.`mark` = '".intval($_GET['producer'])."' and `ls_markModel`.`model` = '".intval($_GET['model'])."' and `ls_items`.`select_1` = '".$v['id']."';");
                    }
                } else {
                    $infoCountItemInCategory = getOneString("SELECT COUNT(*) FROM `ls_items` where `select_1` = '".$v['id']."';");
                    $link = $config['site_url'].'ru/shop/'.translit($v['text']).'/'.intval($v['id']);
                }
                if ($infoCountItemInCategory['COUNT(*)']>0){
                    if ($v['id']==$infoCategory['id']){
                        $menu .= '<li class="active"><a href="'.$link.'">'.$v['text'].' ['.$infoCountItemInCategory['COUNT(*)'].']</a></li>';
                    } else {
                        $menu .= '<li><a href="'.$link.'">'.$v['text'].' ['.$infoCountItemInCategory['COUNT(*)'].']</a></li>';
                    }
                }
            }

        } else {

        }
    }
}
if ($breadcrumbArray = getBreadcrumbFromCategory(intval($_GET['category']))){
    $breadcrumb = '<ol class="breadcrumb" style="text-align: left;">';
    foreach ($breadcrumbArray as $v){
        $breadcrumb .= $v;
    }
    if (isset ($_GET['producer'])){
        $mark = getOneValue(intval($_GET['producer']));
        if (isset ($_GET['category']) and $_GET['category']!=0){
            $link = $config['site_url'].'ru/pc/'.intval($_GET['category']).'/'.intval($_GET['producer']);
        } else {
            $link = $config['site_url'].'ru/producer/'.translit($mark['text']).'/'.intval($_GET['producer']);
        }
        $breadcrumb .= '<li><a href="'.$link.'">'.$mark['text'].'</a></li>';
    }
    if (isset ($_GET['model'])){
        $model = getOneValue(intval($_GET['model']));
        if (isset ($_GET['category']) and $_GET['category']!=0){
            $link = $config['site_url'].'ru/pc/'.intval($_GET['category']).'/'.intval($_GET['producer']).'/'.intval($_GET['model']);
        } else {
            $link = $config['site_url'].'ru/producer/'.translit($mark['text'].' '.$model['text']).'/'.intval($_GET['producer']).'/'.intval($_GET['model']);
        }
        $breadcrumb .= '<li><a href="'.$link.'">'.$model['text'].'</a></li>';
    }
    $breadcrumb .= '</ol>';
}
if (isset ($_GET['category']) and $_GET['category']!=0){
    $h1 = '<h1>'.$infoCategory['text'].'</h1>';
} else {
    $h1 = '<h1>Каталог товарів</h1>';
}
$items = '';
$time1 = microtime(true);
$childCategory = array();
if(isset ($_GET['category']) and $_GET['category']!=0)
    $childCategory = getChildCategory($infoCategory['id']);
$time2 = microtime(true);
if (count($childCategory)>0){
    $dopSql = "WHERE `ls_items`.`select_1` IN (".implode(',', $childCategory).")";
} elseif(isset ($_GET['category']) and $_GET['category']!=0) {
    $dopSql = "WHERE `ls_items`.`select_1` = '".intval($_GET['category'])."'";
} else {
    $dopSql = '';
}
$paginator = '';
if (isset ($_GET['page'])){
    $page = intval($_GET['page']);
    $limitStart = $page*20-20;
} else {
    $page = 1;
    $limitStart = 0;
}
$toPaginator = '';
if ((isset ($_GET['minPrice']) and strlen($_GET['minPrice'])>0) or (isset ($_GET['maxPrice']) and strlen($_GET['maxPrice'])>0)){
    if (strlen($dopSql)==0){
        $dopSql .= ' WHERE ';
    } else {
        $dopSql .= ' AND ';
    }
    if (isset ($_GET['minPrice']) and strlen($_GET['minPrice'])>0){
        $dopSql .= ' `price_1` >= '.getPrice(intval($_GET['minPrice']), 1);
    }
    if (isset ($_GET['maxPrice']) and strlen($_GET['maxPrice'])>0 and $_GET['maxPrice']!=0){
        $dopSql .= ' AND `price_1` <= '.getPrice(intval($_GET['maxPrice']), 1);
    }
    $toPaginator = 'minPrice='.intval($_GET['minPrice']).'&maxPrice='.intval($_GET['maxPrice']).'&';
}
if (isset ($dopSql) and strlen($dopSql)>0){
    $dopSql .= ' AND `ls_items`.`status` = 1 AND `text_14` = 1 ';
} else {
    $dopSql = ' WHERE `ls_items`.`status` = 1 AND `text_14` = 1  ';
}
if (isset ($_GET['kod'])){
    $dopSql = " WHERE `text_1` LIKE '%".mysql_real_escape_string($_GET['kod'])."%' or `text_3` LIKE '%".mysql_real_escape_string($_GET['kod'])."%' or `text_4` LIKE '%".mysql_real_escape_string($_GET['kod'])."%' or `text_5` LIKE '%".mysql_real_escape_string($_GET['kod'])."%'";
    $toPaginator = 'kod='.htmlspecialchars($_GET['kod']).'&';
}
if (isset ($_GET['producer'])){
    $producerSqlJoin = " JOIN `ls_markModel` ON `ls_markModel`.`idItem` = `ls_items`.`id` ";
    if (isset ($_GET['model'])){
        $dopSqlModel = " AND `ls_markModel`.`model` = '".intval($_GET['model'])."'";
    }
    if (isset ($dopSql) and strlen($dopSql)>0){
        $dopSql .= ' AND `ls_markModel`.`mark` = '.intval($_GET['producer']).' '.$dopSqlModel;
    } else {
        $dopSql = ' WHERE `ls_markModel`.`mark` = '.intval($_GET['producer']).' '.$dopSqlModel;
    }
    $group = ' GROUP by `ls_items`.`id`';
}
if (!isset($_GET['model']) and isset ($_GET['producer'])){

    if ($arrayModels = getValuesSelectParamWithParent(4, intval($_GET['producer']))){
        $mark = getOneValue(intval($_GET['producer']));
        foreach ($arrayModels as $v){
            $link = $config['site_url'].'ru/producer/'.translit($mark['text'].' '.$v['text']).'/'.intval($_GET['producer']).'/'.intval($v['id']);
//            $infoCountItem = getOneString("SELECT COUNT(*) FROM `ls_items` where ``")
            if (isset ($_GET['category']) and $_GET['category']!=0){
                $infoCountItemInCategory = getOneString("SELECT COUNT(*) FROM `ls_items` JOIN `ls_markModel` ON `ls_markModel`.`idItem` = `ls_items`.`id` where `ls_markModel`.`mark` = '".intval($_GET['producer'])."' and `ls_markModel`.`model` = '".$v['id']."' and `ls_items`.`select_1` = '".intval($_GET['category'])."';");
            } else {
                $infoCountItemInCategory = getOneString("SELECT COUNT(*) FROM `ls_items` JOIN `ls_markModel` ON `ls_markModel`.`idItem` = `ls_items`.`id` where `ls_markModel`.`mark` = '".intval($_GET['producer'])."' and `ls_markModel`.`model` = '".$v['id']."';");
            }
            if ($infoCountItemInCategory['COUNT(*)']>0){
                $items .= '
                <div class="col-lg-3 col-md-4 col-xs-12">
                    <a href="'.$link.'" class="thumbnail" style="text-align: center;">
                        <img src="http://picase.com.ua/resize_image.php?filename=upload%2Freviews%2F'.str_replace('http://picase.com.ua/upload/reviews/', '', $v['valueText']).'&const=128&width=200&height=200&r=255&g=255&b=255">
                        <span style="font-size: 1.5em;">'.$v['text'].'</span>
                    </a>
                </div>
                ';
            }
        }
    }
} else {
    $sql = "SELECT `ls_items`.* FROM `ls_items`".$producerSqlJoin." JOIN `ls_values_image` ON `ls_values_image`.`id_item` = `ls_items`.`id` ".$dopSql." GROUP by `ls_items`.`id` ORDER by `ls_items`.`price_1` ASC LIMIT ".$limitStart.", 21;";
    if ($arrayItems = getArray($sql)){
        $sql = "SELECT COUNT(*) FROM `ls_items` ".$producerSqlJoin." ".$dopSql.$group;
        if (isset ($_GET['producer'])){
            $sql = 'SELECT COUNT(*) FROM ('.$sql.') as tmp;';
        }
        $infoCount = getOneString($sql);
        $countPage = ceil($infoCount['COUNT(*)']/21);
        if ($page!=1){
            $prev = '
        <li><a href="?'.$toPaginator.'page='.($page-1).'" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a></li>
        ';
        } else {
            $prev = '
        <li class="disabled"><a href="#" aria-label="Previous" onclick="return false;">
            <span aria-hidden="true">&laquo;</span>
          </a></li>
        ';
        }
        $paginator = '<nav>
      <ul class="pagination">

          '.$prev.'

        ';
        for ($i=1; $i<=$countPage; $i++){
            if (($i-5)<=$page and ($i+5)>=$page){
                if ($i==$page){
                    $paginator .= '<li class="active"><a href="#" onclick="return false;">'.$i.'</a></li>';
                } else {
                    $paginator .= '<li><a href="?'.$toPaginator.'page='.$i.'">'.$i.'</a></li>';
                }
            }
        }
        if ($page!=$countPage){
            $next = '<li>
          <a href="?'.$toPaginator.'page='.($page+1).'" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>';
        } else {
            $next = '
        <li class="disabled">
          <a href="#" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
        ';
        }
        $paginator .= $next.'

      </ul>
    </nav>
    ';
        $i = 0;
        $htmlTemplate = file_get_contents($config ['site_url'].'templates/'.$config ['default_template'].'/mainOneItemShop.html');
        foreach ($arrayItems as $key => $v){
            switch ($v['select_2']){
                case "9625":
                    $inStock = '<span style="color: green;"><i class="fa fa-smile-o"></i> в наявності</span>';
                    $shipping = '<img src="'.$config['site_url'].'templates/sgp/img/dostavkaSm.png"> Швидка доставка';
                    break;
                case "9626":
                    $inStock = '<span style="color: red;"><i class="fa fa-meh-o"></i> відсутній</span>';
                    break;
            }
            if (strlen(getPrice($v['price_1'])))
                $priceOld = '<div class="priceOld">'.getPrice($v['price_1']).' <span>грн.</span></div>';
            if ($i==0)
                $items .= '<div class="row">';
            /*$items2 .= '
            <div class="row">
                <div class="col-lg-3">
                    <a href="http://vikoteh.com.ua/ru/mode/item-'.translit($v['text_1']).'-'.$v['id'].'.html" class="thumbnail">
                        <img src="http://vikoteh.com.ua/resize_image.php?filename='.getMainImage ($v['id']).'&const=12&width=150&height=150&r=255&g=255&b=255" alt="'.$v['text_3'].' '.$v['text_1'].'" title="'.$v['text_3'].' '.$v['text_1'].'" width="150" height="150">
                    </a>
                </div>
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-9" style="margin-left: -15px;">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="col-lg-12"><a href="http://vikoteh.com.ua/ru/mode/item-'.translit($v['text_1']).'-'.$v['id'].'.html"><h2 class="shopH2">'.$v['text_1'].'</h2></a></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="shortInfo">
                                        <p>
                                            Код: <strong>'.$v['text_3'].'</strong>
                                        </p>
                                        <p>
                                            '.$v['text_4'].'
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3" style="text-align: right;">
                            <div class="priceShop"><strong>'.getPrice($v['price_1'], 1).'</strong> <span>грн.</span></div>
                            '.$priceOld.'
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 infoShop">'.$shipping.'</div>
                        <div class="col-lg-4 infoShop"><img src="http://vikoteh.com.ua/templates/sgp/img/varantySm.png"> Гарантія</div>
                        <div class="col-lg-4" style="text-align: right;">
                            <div><strong>'.$inStock.'</strong></div>
                            <a href="http://vikoteh.com.ua/ru/mode/item-'.translit($v['text_1']).'-'.$v['id'].'.html" class="btn btn-orange">детальніше</a>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            ';*/
            switch ($v['select_1']){
                case 1:
                    $stock = 'В наявності';
                    $dopStyleStock = 'style="background: green;"';
                    break;
                case 2:
                    $stock = 'Зараз відсутній';
                    $dopStyleStock = 'style="background: red;"';
                    break;
            }
            $oneItem = $htmlTemplate;
            $oneItem = str_replace('{id}', $v['id'], $oneItem);
            $oneItem = str_replace('{name}', $v['text_1'], $oneItem);
            $oneItem = str_replace('{price}', getPrice($v['price_1']), $oneItem);
            if ($v['price_2']!=0 and $v['price_1']!=$v['price_2'])
            {
                $oneItem = str_replace('{strike}', '<span style="text-decoration: line-through; color:#7c7c7c; font-size: 16px;">', $oneItem);
                $oneItem = str_replace('{/strike}', '</span>', $oneItem);
                $oneItem = str_replace('{price_discount}', '<span>'.$v['price_2'].'</span>', $oneItem);
            } else {
                $oneItem = str_replace('{strike}', '', $oneItem);
                $oneItem = str_replace('{/strike}', '', $oneItem);
                $oneItem = str_replace('{price_discount}', '', $oneItem);
            }
            $oneItem = str_replace('{link}', getItemLink($v), $oneItem);
            $size = json_decode($v['select_1'], true);
            if (is_array($size)){
                foreach ($size as $oneSize){
                    $sql = "SELECT `ls_params_select_values`.`id`, `ls_translate`.`text`, `ls_params_select_values`.`position` FROM `ls_params_select_values` JOIN `ls_translate` ON `ls_translate`.`id` = `ls_params_select_values`.`id_translate` WHERE `ls_params_select_values`.`id` = ".$oneSize." order by `ls_params_select_values`.`position` DESC;";
                    $infoSize = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
                    $sizeHtml .= '<span class="oneSizeInShop badge badge-important">'.$infoSize['text'].'</span>';
                }
            } else {
                $sql = "SELECT `ls_params_select_values`.`id`, `ls_translate`.`text`, `ls_params_select_values`.`position` FROM `ls_params_select_values` JOIN `ls_translate` ON `ls_translate`.`id` = `ls_params_select_values`.`id_translate` WHERE `ls_params_select_values`.`id` = ".$v['select_1']." order by `ls_params_select_values`.`position` DESC;";
                $infoSize = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
                $sizeHtml .= '<span class="oneSizeInShop badge badge-important">'.$infoSize['text'].'</span>';
            }
            if (time()-$v['time']<2678400){
                $oneItem = str_replace('{new}', '<div class="new"><img src="'.$config['site_url'].'images/new.png"></div>', $oneItem);
            } else {
                $oneItem = str_replace('{new}', '', $oneItem);
            }
            $oneItem = str_replace('{size}', '<div class="sizeItemShop">'.$sizeHtml.'</div>', $oneItem);
            $producer = getOneValue($v['select_2']);
            $oneItem = str_replace('{producer}', $producer['text'], $oneItem);
            $oneItem = str_replace('{image}', $config ['site_url'].'resize_image.php?filename='.urlencode('upload/userparams/'.getMainImage ($v['id'])).'&const=128&width=400&height=250&r=255&g=255&b=255', $oneItem);
            $items .= $oneItem;
            $i++;
            if ($i==3 or !isset ($arrayItems[$key+1])){
                $items .= '</div>';
                $i = 0;
            }
            unset ($shipping);
        }
    } else {
        $items = '
    <div class="alert alert-danger">
        <strong>Вибачте!</strong> Але у цьому розділі товару поки шо немає
    </div>
    ';
    }
}
if (isset ($_GET['category']) and ($_GET['category']==21 or $_GET['category']==28)){
    if (!isset ($_GET['producer'])){
        if ($arrayMark = getValuesSelectParam(3)){
            foreach ($arrayMark as $v){
                if (isset ($_GET['category']) and $_GET['category']!=0){
                    $link = $config['site_url'].'ru/pc/'.intval($_GET['category']).'/'.$v['id'];
                } else {
                    $link = $config['site_url'].'ru/producer/'.translit($v['text']).'/'.$v['id'];
                }
                if (isset ($_GET['producer']) and $v['id']==intval($_GET['producer'])){
                    $menuMark .= '<li class="active"><a href="'.$link.'">'.$v['text'].'</a></li>';
                } else {
                    $menuMark .= '<li><a href="'.$link.'">'.$v['text'].'</a></li>';
                }
            }
            $markList = '
                <div class="row">
                    <div class="col-lg-12">
                        <div class="leftMenu" style="min-height: inherit;">
                            <div class="panel panel-danger">
                              <div class="panel-heading" style="background-color: #2f343b;">
                                <h3 class="panel-title" id="panel-title" style="color: #FFF; font-weight: bold; font-size: 16px;">Марки телефонов<a class="anchorjs-link" href="#panel-title"><span class="anchorjs-icon"></span></a></h3>
                              </div>
                              <div class="panel-body" style="padding: 0px;">
                                <ul class="category">
                                    '.$menuMark.'
                                </ul>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
                ';
        }
    } else {
        if ($arrayMark = getValuesSelectParamWithParent(4, intval($_GET['producer']))){
            $mark = getOneValue(intval($_GET['producer']));
            foreach ($arrayMark as $v){
                if (isset ($_GET['category']) and $_GET['category']!=0){
                    $link = $config['site_url'].'ru/pc/'.intval($_GET['category']).'/'.intval($_GET['producer']).'/'.$v['id'];
                } else {
                    $link = $config['site_url'].'ru/producer/'.translit($mark['text'].' '.$v['text']).'/'.intval($_GET['producer']).'/'.$v['id'];
                }
                if (isset ($_GET['model']) and $v['id']==intval($_GET['model'])){
                    $menuMark .= '<li class="active"><a href="'.$link.'">'.$v['text'].'</a></li>';
                } else {
                    $menuMark .= '<li><a href="'.$link.'">'.$v['text'].'</a></li>';
                }
            }
            $markList = '
                <div class="row">
                    <div class="col-lg-12">
                        <div class="leftMenu" style="min-height: inherit;">
                            <div class="panel panel-danger">
                              <div class="panel-heading" style="background-color: #2f343b;">
                                <h3 class="panel-title" id="panel-title" style="color: #FFF; font-weight: bold; font-size: 16px;">Модели '.$mark['text'].'<a class="anchorjs-link" href="#panel-title"><span class="anchorjs-icon"></span></a></h3>
                              </div>
                              <div class="panel-body" style="padding: 0px;">
                                <ul class="category">
                                    '.$menuMark.'
                                </ul>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
                ';
        }
    }
}
if (!isset ($_GET['category']) or $_GET['producer']==0){
    if (!isset ($_GET['producer'])){
        $text = '
        <div class="row">
            <div class="col-lg-12">
                <p><strong>Купить чехол для телефона</strong> и защитить свой телефон от повреждений при падении, царапин и т.д.</p>
                <p>У нас в магазине Вы можете <strong>купить бампер на телефон</strong> который в большинстве случаев спасает экран телефона от повреждений при падении</p>
                <p>Покупая телефон за несколько тысяч грн. не пожалейте 100-300 грн. на защиту своего телефона. Купи комплект, защитное стекло или защитную пленку для своего телефона, бампер или чехол, это сэкономит значительную суму при падении, или ношении телефона с ключами или другими жителями наших карманов</p>
            </div>
        </div>
        ';
    }
}
if (isset ($_GET['category']) and $_GET['category']!=0){
    $infoCategory = getOneValue(intval($_GET['category']));
} else {
    $infoCategory['text'] = ' - бампера, чехлы, защитные пленки и стекла';
}
if (isset ($_GET['producer']) and !isset ($_GET['model'])){
    $title = $mark['text'].' '.$infoCategory['text'];
    $keywords = $mark['text'].' купить, '.$mark['text'].' '.$model['text'].' доставка по Украине, продажа чехлов для телефонов '.$mark['text'];
    $description = 'PICASE.COM.UA - предлагает бампера, защитные стекла, защитные пленки по приятных ценах на телефоны '.$mark['text'];
    $text = '
        <div class="row">
            <div class="col-lg-12">
                <p><strong>Купить чехол для телефона '.$mark['text'].'</strong> и защити свой '.$mark['text'].' от повреждений при падении, царапин и т.д.</p>
                <p>У нас в магазине Вы можете <strong>купить бампер на '.$mark['text'].'</strong> который в большинстве случаев спасает экран телефона от повреждений при падении</p>
                <p>Покупая телефон '.$mark['text'].' за несколько тысяч грн. не пожалейте 100-300 грн. на защиту своего телефона. Купи комплект, защитное стекло или защитную пленку для своего телефона '.$mark['text'].', бампер или чехол, это сэкономит значительную суму при падении, или ношении телефона с ключами или другими жителями наших карманов</p>
            </div>
        </div>
        ';
    $h1 = '<h1>Чехлы и бампера для телефонов '.$mark['text'].'</h1>';
} elseif (isset ($_GET['model'])){
    $h1 = '<h1>Чехлы и бампера для '.$mark['text'].' '.$model['text'].'</h1>';
    $model = getOneValue(intval($_GET['model']));
    $title = $mark['text'].' '.$model['text'].' '.$infoCategory['text'];
    $keywords = $mark['text'].' '.$model['text'].' купить чехол, '.$mark['text'].' '.$model['text'].' чехлы - доставка по Украине, продажа чехлов для '.$mark['text'].' '.$model['text'];
    $description = 'PICASE.COM.UA - предлагает бампера, защитные стекла, защитные пленки по приятных ценах для '.$mark['text'].' '.$model['text'];
    $text = '
        <div class="row">
            <div class="col-lg-12">
                <p><strong>Купить чехол для телефона '.$mark['text'].' '.$model['text'].'</strong> и защити свой '.$mark['text'].' '.$model['text'].' от повреждений при падении, царапин и т.д.</p>
                <p>У нас в магазине Вы можете <strong>купить бампер на '.$mark['text'].' '.$model['text'].'</strong> который в большинстве случаев спасает экран телефона от повреждений при падении</p>
                <p>Покупая телефон '.$mark['text'].' '.$model['text'].' за несколько тысяч грн. не пожалейте 100-300 грн. на защиту своего телефона. Купи комплект, защитное стекло или защитную пленку для своего телефона '.$mark['text'].' '.$model['text'].', бампер или чехол, это сэкономит значительную суму при падении, или ношении телефона с ключами или другими жителями наших карманов</p>
                <p>
                    У нас в наличии:
                    <ul>
                        <li><strong>'.$mark['text'].' '.$model['text'].' бампер</strong></li>
                        <li><strong>'.$mark['text'].' '.$model['text'].' чехол</strong></li>
                        <li><strong>'.$mark['text'].' '.$model['text'].' защитная пленка</strong></li>
                        <li><strong>'.$mark['text'].' '.$model['text'].' защитное стекло</strong></li>
                    </ul>
                </p>

            </div>
        </div>
        ';
}
if (!isset($_GET['model']) and isset ($_GET['producer'])){
    $body .= '
    <div class="container" style="padding: 10px;">
        <input type="hidden" id="link" value="'.$config['site_url'].'ru/shop/'.translit($infoCategory['text']).'/'.$infoCategory['id'].'">
            <div class="row">
                <div class="col-lg-9">
                    '.$h1.'
                </div>
                <div class="col-lg-3" style="text-align: right; padding-top: 25px;">
                    <script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="large" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,moimir,gplus" data-yashareTheme="counter"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    '.$breadcrumb.'
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12" style="text-align: right;">
                            '.$paginator.'
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="catalog-grid">'.$items.'</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="text-align: right;">
                            '.$paginator.'
                        </div>
                    </div>
                    '.$text.'
                </div>
            </div>
        </div>
    ';
} else {
    $body .= '
    <div class="container" style="padding: 10px;">
        <input type="hidden" id="link" value="'.$config['site_url'].'ru/shop/'.translit($infoCategory['text']).'/'.$infoCategory['id'].'">
            <div class="row">
                <div class="col-lg-9">
                    '.$h1.'
                </div>
                <div class="col-lg-3" style="text-align: right; padding-top: 25px;">
                    <script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="large" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,moimir,gplus" data-yashareTheme="counter"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    '.$breadcrumb.'
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    '.$markList.'
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="leftMenu" style="min-height: inherit;">
                                <div class="panel panel-danger">
                                  <div class="panel-heading" style="background-color: #2f343b;">
                                    <h3 class="panel-title" id="panel-title" style="color: #FFF; font-weight: bold; font-size: 16px;">Категории<a class="anchorjs-link" href="#panel-title"><span class="anchorjs-icon"></span></a></h3>
                                  </div>
                                  <div class="panel-body" style="padding: 0px;">
                                    <ul class="category">
                                        '.$all.'
                                        '.$menu.'
                                    </ul>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-12" style="text-align: right;">
                            '.$paginator.'
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="catalog-grid">'.$items.'</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="text-align: right;">
                            '.$paginator.'
                        </div>
                    </div>
                    '.$text.'
                </div>
            </div>
        </div>
    ';
}
