<?php
$body .= '
<style>
    @font-face {
    font-family: \'Cera Pro\';
    src: url(\''.$config['site_url'].'font/CeraPro-Italic.eot\');
    src: local(\'Cera Pro Italic\'), local(\'CeraPro-Italic\'),
        url(\''.$config['site_url'].'font/CeraPro-Italic.eot?#iefix\') format(\'embedded-opentype\'),
        url(\''.$config['site_url'].'font/CeraPro-Italic.woff\') format(\'woff\'),
        url(\''.$config['site_url'].'font/CeraPro-Italic.ttf\') format(\'truetype\');
    font-weight: normal;
    font-style: italic;
}

@font-face {
    font-family: \'Cera Pro\';
    src: url(\''.$config['site_url'].'font/CeraPro-Regular.eot\');
    src: local(\'Cera Pro Regular\'), local(\'CeraPro-Regular\'),
        url(\''.$config['site_url'].'font/CeraPro-Regular.eot?#iefix\') format(\'embedded-opentype\'),
        url(\''.$config['site_url'].'font/CeraPro-Regular.woff\') format(\'woff\'),
        url(\''.$config['site_url'].'font/CeraPro-Regular.ttf\') format(\'truetype\');
    font-weight: normal;
    font-style: normal;
}

@font-face {
    font-family: \'Cera Pro\';
    src: url(\''.$config['site_url'].'font/CeraPro-BlackItalic.eot\');
    src: local(\'Cera Pro Black Italic\'), local(\'CeraPro-BlackItalic\'),
        url(\''.$config['site_url'].'font/CeraPro-BlackItalic.eot?#iefix\') format(\'embedded-opentype\'),
        url(\''.$config['site_url'].'font/CeraPro-BlackItalic.woff\') format(\'woff\'),
        url(\''.$config['site_url'].'font/CeraPro-BlackItalic.ttf\') format(\'truetype\');
    font-weight: 900;
    font-style: italic;
}

@font-face {
    font-family: \'Cera Pro\';
    src: url(\''.$config['site_url'].'font/CeraPro-Light.eot\');
    src: local(\'Cera Pro Light\'), local(\'CeraPro-Light\'),
        url(\''.$config['site_url'].'font/CeraPro-Light.eot?#iefix\') format(\'embedded-opentype\'),
        url(\''.$config['site_url'].'font/CeraPro-Light.woff\') format(\'woff\'),
        url(\''.$config['site_url'].'font/CeraPro-Light.ttf\') format(\'truetype\');
    font-weight: 300;
    font-style: normal;
}

@font-face {
    font-family: \'Cera Pro\';
    src: url(\''.$config['site_url'].'font/CeraPro-BoldItalic.eot\');
    src: local(\'Cera Pro Bold Italic\'), local(\'CeraPro-BoldItalic\'),
        url(\''.$config['site_url'].'font/CeraPro-BoldItalic.eot?#iefix\') format(\'embedded-opentype\'),
        url(\''.$config['site_url'].'font/CeraPro-BoldItalic.woff\') format(\'woff\'),
        url(\''.$config['site_url'].'font/CeraPro-BoldItalic.ttf\') format(\'truetype\');
    font-weight: bold;
    font-style: italic;
}

@font-face {
    font-family: \'Cera Pro\';
    src: url(\''.$config['site_url'].'font/CeraPro-Black.eot\');
    src: local(\'Cera Pro Black\'), local(\'CeraPro-Black\'),
        url(\''.$config['site_url'].'font/CeraPro-Black.eot?#iefix\') format(\'embedded-opentype\'),
        url(\''.$config['site_url'].'font/CeraPro-Black.woff\') format(\'woff\'),
        url(\''.$config['site_url'].'font/CeraPro-Black.ttf\') format(\'truetype\');
    font-weight: 900;
    font-style: normal;
}

@font-face {
    font-family: \'Cera Pro\';
    src: url(\''.$config['site_url'].'font/CeraPro-Medium.eot\');
    src: local(\'Cera Pro Medium\'), local(\'CeraPro-Medium\'),
        url(\''.$config['site_url'].'font/CeraPro-Medium.eot?#iefix\') format(\'embedded-opentype\'),
        url(\''.$config['site_url'].'font/CeraPro-Medium.woff\') format(\'woff\'),
        url(\''.$config['site_url'].'font/CeraPro-Medium.ttf\') format(\'truetype\');
    font-weight: 500;
    font-style: normal;
}

@font-face {
    font-family: \'Cera Pro\';
    src: url(\''.$config['site_url'].'font/CeraPro-Bold.eot\');
    src: local(\'Cera Pro Bold\'), local(\'CeraPro-Bold\'),
        url(\''.$config['site_url'].'font/CeraPro-Bold.eot?#iefix\') format(\'embedded-opentype\'),
        url(\''.$config['site_url'].'font/CeraPro-Bold.woff\') format(\'woff\'),
        url(\''.$config['site_url'].'font/CeraPro-Bold.ttf\') format(\'truetype\');
    font-weight: bold;
    font-style: normal;
}

@font-face {
    font-family: \'Cera Pro\';
    src: url(\''.$config['site_url'].'font/CeraPro-Thin.eot\');
    src: local(\'Cera Pro Thin\'), local(\'CeraPro-Thin\'),
        url(\''.$config['site_url'].'font/CeraPro-Thin.eot?#iefix\') format(\'embedded-opentype\'),
        url(\''.$config['site_url'].'font/CeraPro-Thin.woff\') format(\'woff\'),
        url(\''.$config['site_url'].'font/CeraPro-Thin.ttf\') format(\'truetype\');
    font-weight: 100;
    font-style: normal;
}

@font-face {
    font-family: \'Cera Pro\';
    src: url(\''.$config['site_url'].'font/CeraPro-MediumItalic.eot\');
    src: local(\'Cera Pro Medium Italic\'), local(\'CeraPro-MediumItalic\'),
        url(\''.$config['site_url'].'font/CeraPro-MediumItalic.eot?#iefix\') format(\'embedded-opentype\'),
        url(\''.$config['site_url'].'font/CeraPro-MediumItalic.woff\') format(\'woff\'),
        url(\''.$config['site_url'].'font/CeraPro-MediumItalic.ttf\') format(\'truetype\');
    font-weight: 500;
    font-style: italic;
}

@font-face {
    font-family: \'Cera Pro\';
    src: url(\''.$config['site_url'].'font/CeraPro-LightItalic.eot\');
    src: local(\'Cera Pro Light Italic\'), local(\'CeraPro-LightItalic\'),
        url(\''.$config['site_url'].'font/CeraPro-LightItalic.eot?#iefix\') format(\'embedded-opentype\'),
        url(\''.$config['site_url'].'font/CeraPro-LightItalic.woff\') format(\'woff\'),
        url(\''.$config['site_url'].'font/CeraPro-LightItalic.ttf\') format(\'truetype\');
    font-weight: 300;
    font-style: italic;
}

@font-face {
    font-family: \'Cera Pro\';
    src: url(\''.$config['site_url'].'font/CeraPro-ThinItalic.eot\');
    src: local(\'Cera Pro Thin Italic\'), local(\'CeraPro-ThinItalic\'),
        url(\''.$config['site_url'].'font/CeraPro-ThinItalic.eot?#iefix\') format(\'embedded-opentype\'),
        url(\''.$config['site_url'].'font/CeraPro-ThinItalic.woff\') format(\'woff\'),
        url(\''.$config['site_url'].'font/CeraPro-ThinItalic.ttf\') format(\'truetype\');
    font-weight: 100;
    font-style: italic;
}

</style>
';
$body .= '<div style="font-family: \'Cera Pro\'; font-weight: normal;">';
if ($infoKey = getOneString("SELECT * FROM `ls_searchSystemKeywords` WHERE `keyword` = '".mysql_real_escape_string($_GET['key'])."'")){
    $infoSearchSystem = getOneString("SELECT * FROM `ls_searchSystem` WHERE `id` = '".$infoKey['searchId']."'");
    $title = $infoSearchSystem['title'];
    $keywords = $infoSearchSystem['keywords'];
    $description = $infoSearchSystem['description'];
    $body .= '
    <div class="row">
        <div class="col-lg-12">
            <img src="'.$config['site_url'].$infoSearchSystem['header'].'" class="img-thumbnail" style="border:0;">            
        </div>       
    </div>
    ';
    if ($arrayTop = getArray("SELECT * FROM `ls_searchSystemImages` WHERE `searchId` = '".$infoSearchSystem['id']."' AND `type` = 'top' order by `position`")){
        $body .= '
        <div class="row">
        ';
        foreach ($arrayTop as $v){
            $body .= '
            <div class="col-lg-2 text-center">
                <a href="'.$v['link'].'"><img class="img-thumbnail" style="border: 0;" src="'.$config['site_url'].'resize_image.php?filename='.urlencode($v['url']).'&path=upload%2FsearchSystem%2F&const=128&width=262&height=262&r=255&g=255&b=255"></a>
            </div>
            ';
        }
        $body .= '</div>';
    }
    if ($arrayBottom = getArray("SELECT * FROM `ls_searchSystemImages` WHERE `searchId` = '".$infoSearchSystem['id']."' AND `type` = 'bottom' order by `position`")){
        $body .= '<div class="row">
        ';
        foreach ($arrayBottom as $v){
            $body .= '
                <div class="col-lg-5ths text-center" style="margin-top: 20px;">
                    <a href="'.$v['link'].'">
                        <img class="img-thumbnail" style="border: 0;" src="'.$config['site_url'].'resize_image.php?filename='.urlencode($v['url']).'&path=upload%2FsearchSystem%2F&const=129&width=204&height=323&r=255&g=255&b=255">
                        <div>'.$v['name'].'</div>
                    </a>
                    
                </div>
            ';
        }
        $body .= '</div>';
    }
    if ($arrayBottom = getArray("SELECT * FROM `ls_searchSystemImages` WHERE `searchId` = '".$infoSearchSystem['id']."' AND `type` = 'baner' order by `position`")){
        $body .= '<div class="row" style="margin-top: 3em;
    margin-bottom: 3em;">
        <div class="col-lg-12">
        ';
        $items = '';
        foreach ($arrayBottom as $key => $v){
            $items .= '
            <div class="item'.(($key==0) ? ' active' : '').'">
              <a href="'.$v['link'].'"><img src="'.$config['site_url'].'resize_image.php?filename='.urlencode($v['url']).'&path=upload%2FsearchSystem%2F&const=129&width=1170&height=600&r=255&g=255&b=255" alt="'.$v['name'].'"></a>
              <div class="carousel-caption">
                '.$v['name'].'
              </div>
            </div>
            ';
        }
        $body .= '
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
          <!-- Wrapper for slides -->
          <div class="carousel-inner" role="listbox">
            '.$items.'

          </div>
        
          <!-- Controls -->
          <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
        </div>
        </div>';
    }
    if (!empty($infoSearchSystem['sqlNews']) AND $array_items = getArray($infoSearchSystem['sqlNews'])){
        $body .= '
    <div class="row" style="margin-bottom: 2em; margin-top: 2em;">
        <div class="col-lg-12">
            <h2 class="text-center">
                <a style="color:#e6000b;  display: inline-block; padding: 10px 3em;font-size: 0.8em;border: 5px solid black; font-weight:900;font-family: \'Cera Pro\';" href="'.$infoSearchSystem['linkNews'].'">'.$infoSearchSystem['textNews'].'</a>
            </h2>
        </div>    
    </div>
        <div class="row">
        ';
        $htmlTemplate = file_get_contents('templates/'.$config ['default_template'].'/mainOneItem.html');
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
            $body .= $oneItem;
        }
        $body .= '</div>';
    }
    if (!empty($infoSearchSystem['sqlActions']) AND $array_items = getArray($infoSearchSystem['sqlActions'])){
        $body .= '
            <div class="row" style="margin-bottom: 2em; margin-top: 5em;">
                <div class="col-lg-12">
                    <h2 class="text-center"><a style="color:#e6000b;  display: inline-block; font-size: 0.8em;  padding: 10px 3em;border: 5px solid black;font-weight:900;font-family: \'Cera Pro\';" href="'.$infoSearchSystem['linkActions'].'">'.$infoSearchSystem['textActions'].'</a></h2>
                </div>
            </div>   
        <div class="row">
        ';
        $htmlTemplate = file_get_contents('templates/'.$config ['default_template'].'/mainOneItem.html');
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
            $body .= $oneItem;
        }
        $body .= '</div>';
    }
    $body .= '
    <div class="row" style="margin-bottom: 2em; margin-top: 5em;;">
        <div class="col-lg-12">
            <h2 class="text-center"><a style="color:#e6000b;  display: inline-block; font-size: 0.8em;  padding: 10px 3em;border: 5px solid black;font-weight:900;font-family: \'Cera Pro\';" href="'.$infoSearchSystem['linkAllItems'].'">'.$infoSearchSystem['textAll'].'</a></h2>
        </div>
    </div>
    ';
    if (!empty($infoSearchSystem['sqlAllItems']) AND $array_items = getArray($infoSearchSystem['sqlAllItems'])){
        $body .= '
        <div class="row">
        ';
        $htmlTemplate = file_get_contents('templates/'.$config ['default_template'].'/mainOneItem.html');
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
            $body .= $oneItem;
        }
        $body .= '</div>
        <div class="text-center" style="margin-top: 30px;"><a href="'.$infoSearchSystem['linkAllItems'].'" class="btn btn-success btn-lg">дивитись більше</a></div>
        ';
    }
    $body .= '</div>';
} else {
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
}