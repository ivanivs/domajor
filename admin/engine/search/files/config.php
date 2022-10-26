<?php
if (isset ($_POST['linkActions'])){
    $sql = "UPDATE `ls_searchSystem` SET 
        `title` = '".mysql_real_escape_string($_POST['title'])."',
        `descriptions` = '".mysql_real_escape_string($_POST['descriptions'])."',
        `keywords` = '".mysql_real_escape_string($_POST['keywordsPage'])."',
        `linkNews` = '".mysql_real_escape_string($_POST['linkNews'])."',
        `linkActions` = '".mysql_real_escape_string($_POST['linkActions'])."',
        `linkAllItems` = '".mysql_real_escape_string($_POST['linkAllItems'])."',
        `sqlNews` = '".mysql_real_escape_string($_POST['sqlNews'])."',
        `sqlActions` = '".mysql_real_escape_string($_POST['sqlActions'])."',
        `sqlAllItems` = '".mysql_real_escape_string($_POST['sqlAllItems'])."',
        `textNews` = '".mysql_real_escape_string($_POST['textNews'])."',
        `textActions` = '".mysql_real_escape_string($_POST['textActions'])."',
        `textAll` = '".mysql_real_escape_string($_POST['textAll'])."'
    WHERE `id` = '".intval($_GET['id'])."';
    ";
    mysql_query($sql);
}
$infoSearchEngine = getOneString("SELECT * FROM `ls_searchSystem` WHERE `id` = '".intval($_GET['id'])."';");
if (isset ($_POST['keywords'])){
    $arrayKeywords = explode("\r\n", $_POST['keywords']);
    mysql_query("DELETE FROM `ls_searchSystemKeywords` WHERE `searchId` = '".$infoSearchEngine['id']."'");
    foreach ($arrayKeywords as $v){
        if (!empty($v)) {
            mysql_query("INSERT INTO `ls_searchSystemKeywords` (
            `searchId`, `keyword`) VALUES ('" . $infoSearchEngine['id'] . "', '" . mysql_real_escape_string($v) . "');");
        }
    }
}
$keywords = '';
if ($arrayKeywords = getArray("SELECT * FROM `ls_searchSystemKeywords` WHERE `searchId` = '".$infoSearchEngine['id']."'")){
    foreach ($arrayKeywords as $v){
        $keywords .= $v['keyword']."\r\n";
    }
}
if (isset ($_POST['ids'])){
    $ids = $_POST['ids'];
    $links = $_POST['links'];
    $name = $_POST['name'];
    $positions = $_POST['positions'];
    foreach ($ids as $key => $id){
        mysql_query("UPDATE `ls_searchSystemImages` set 
            `link` = '".mysql_real_escape_string($links[$key])."',
            `name` = '".mysql_real_escape_string($name[$key])."',
            `position` = '".mysql_real_escape_string($positions[$key])."'
        WHERE `id` = '".$id."';");
    } 
}
if (isset ($_GET['rm'])){
    mysql_query("DELETE FROM `ls_searchSystemImages` WHERE `id` = '".intval($_GET['rm'])."'");
}
if ($arrayImages = getArray("SELECT * FROM `ls_searchSystemImages` WHERE `searchId` = '".$infoSearchEngine['id']."' AND `type` = 'top' ORDER by `position`")){
    $bodyTop = '
    <form action="" method="POST">
    <table class="table table-striped table-bordered">
    <tr>
        <td>Зображення</td>
        <td>Назва</td>
        <td>Посилання</td>
        <td>Позиція</td>
        <td></td>
    </tr>
    ';
    foreach ($arrayImages as $v){
        $bodyTop .= '
        <tr>
            <td>
                <img src="'.$config ['site_url'].'resize_image.php?filename='.urlencode($v['url']).'&path='.urlencode('upload/searchSystem/').'&const=128&width=262&height=262&r=255&g=255&b=255">
                <input type="hidden" name="ids[]" value="'.$v['id'].'">
            </td>
            <td><input type="text" class="form-control" name="name[]" value="'.htmlspecialchars($v['name']).'"></td>
            <td><input type="text" class="form-control" name="links[]" value="'.htmlspecialchars($v['link']).'"></td>
            <td><input type="number" class="form-control" name="positions[]" value="'.$v['position'].'"></td>
            <td><a href="index.php?do=search&submode=config&id='.intval($_GET['id']).'&rm='.$v['id'].'">Видалити</a></td>
        </tr>
        ';
    }
    $bodyTop .= '</table>
    <input type="submit" class="btn btn-success" value="Зберегти">
    </form>
';
}
if (isset ($_POST['idsBottom'])){
    $ids = $_POST['idsBottom'];
    $links = $_POST['linksBottom'];
    $name = $_POST['nameBottom'];
    $positions = $_POST['positionsBottom'];
    foreach ($ids as $key => $id){
        mysql_query("UPDATE `ls_searchSystemImages` set 
            `link` = '".mysql_real_escape_string($links[$key])."',
            `name` = '".mysql_real_escape_string($name[$key])."',
            `position` = '".mysql_real_escape_string($positions[$key])."'
        WHERE `id` = '".$id."';");
    }
}
if ($arrayImages = getArray("SELECT * FROM `ls_searchSystemImages` WHERE `searchId` = '".$infoSearchEngine['id']."' AND `type` = 'bottom' ORDER by `position`")){
    $bodyBottom = '
    <form action="" method="POST">
    <table class="table table-striped table-bordered">
    <tr>
        <td>Зображення</td>
        <td>Назва</td>
        <td>Посилання</td>
        <td>Позиція</td>
        <td></td>
    </tr>
    ';
    foreach ($arrayImages as $v){
        $bodyBottom .= '
        <tr>
            <td>
                <img src="'.$config ['site_url'].'resize_image.php?filename='.urlencode($v['url']).'&path='.urlencode('upload/searchSystem/').'&const=128&width=262&height=262&r=255&g=255&b=255">
                <input type="hidden" name="idsBottom[]" value="'.$v['id'].'">
            </td>
            <td><input type="text" class="form-control" name="nameBottom[]" value="'.htmlspecialchars($v['name']).'"></td>
            <td><input type="text" class="form-control" name="linksBottom[]" value="'.htmlspecialchars($v['link']).'"></td>
            <td><input type="number" class="form-control" name="positionsBottom[]" value="'.$v['position'].'"></td>
            <td><a href="index.php?do=search&submode=config&id='.intval($_GET['id']).'&rm='.$v['id'].'">Видалити</a></td>
        </tr>
        ';
    }
    $bodyBottom .= '</table>
    <input type="submit" class="btn btn-success" value="Зберегти">
    </form>
';
}
if (isset ($_POST['idsBaner'])){
    $ids = $_POST['idsBaner'];
    $links = $_POST['linksBaner'];
    $name = $_POST['nameBaner'];
    $positions = $_POST['positionsBaner'];
    foreach ($ids as $key => $id){
        mysql_query("UPDATE `ls_searchSystemImages` set 
            `link` = '".mysql_real_escape_string($links[$key])."',
            `name` = '".mysql_real_escape_string($name[$key])."',
            `position` = '".mysql_real_escape_string($positions[$key])."'
        WHERE `id` = '".$id."';");
    }
}
if ($arrayImages = getArray("SELECT * FROM `ls_searchSystemImages` WHERE `searchId` = '".$infoSearchEngine['id']."' AND `type` = 'baner' ORDER by `position`")){
    $bodyBanner = '
    <form action="" method="POST">
    <table class="table table-striped table-bordered">
    <tr>
        <td>Зображення</td>
        <td>Назва</td>
        <td>Посилання</td>
        <td>Позиція</td>
        <td></td>
    </tr>
    ';
    foreach ($arrayImages as $v){
        $bodyBanner .= '
        <tr>
            <td>
                <img src="'.$config ['site_url'].'resize_image.php?filename='.urlencode($v['url']).'&path='.urlencode('upload/searchSystem/').'&const=128&width=262&height=262&r=255&g=255&b=255">
                <input type="hidden" name="idsBaner[]" value="'.$v['id'].'">
            </td>
            <td><input type="text" class="form-control" name="nameBaner[]" value="'.htmlspecialchars($v['name']).'"></td>
            <td><input type="text" class="form-control" name="linksBaner[]" value="'.htmlspecialchars($v['link']).'"></td>
            <td><input type="number" class="form-control" name="positionsBaner[]" value="'.$v['position'].'"></td>
            <td><a href="index.php?do=search&submode=config&id='.intval($_GET['id']).'&rm='.$v['id'].'">Видалити</a></td>
        </tr>
        ';
    }
    $bodyBanner .= '</table>
    <input type="submit" class="btn btn-success" value="Зберегти">
    </form>
';
}
$body_admin .= '
<h2>Налаштування: '.$infoSearchEngine['name'].'</h2>
<hr>
<h4>Загальні налаштування</h4>
<form action="" method="POST">
<table class="table table-bordered table-striped">
    <tr>
        <td>Title сторінки:</td>
        <td>
            <textarea class="form-control" name="title" style="width:100%;">'.$infoSearchEngine['title'].'</textarea>        
        </td>    
    </tr>
    <tr>
        <td>Descriptions сторінки:</td>
        <td>
            <textarea class="form-control" name="descriptions" style="width:100%;">'.$infoSearchEngine['descriptions'].'</textarea>        
        </td>    
    </tr>
    <tr>
        <td>Keywords сторінки:</td>
        <td>
            <textarea class="form-control" name="keywordsPage" style="width:100%;">'.$infoSearchEngine['keywords'].'</textarea>        
        </td>    
    </tr>
    <tr>
        <td>Посилання на всі новинки:</td>
        <td><input type="text" class="form-control" name="linkNews" value="'.htmlspecialchars($infoSearchEngine['linkNews']).'"></td>    
    </tr>
    <tr>
        <td>Посилання на всі акційні:</td>
        <td><input type="text" class="form-control" name="linkActions" value="'.htmlspecialchars($infoSearchEngine['linkActions']).'"></td>    
    </tr>
    <tr>
        <td>Посилання на всі товари:</td>
        <td><input type="text" class="form-control" name="linkAllItems" value="'.htmlspecialchars($infoSearchEngine['linkAllItems']).'"></td>    
    </tr>
    <tr>
        <td>Текст новинки:</td>
        <td><input type="text" class="form-control" name="textNews" value="'.htmlspecialchars($infoSearchEngine['textNews']).'"></td>    
    </tr>
    <tr>
        <td>Текст акційні:</td>
        <td><input type="text" class="form-control" name="textActions" value="'.htmlspecialchars($infoSearchEngine['textActions']).'"></td>    
    </tr>
    <tr>
        <td>Текст Всі:</td>
        <td><input type="text" class="form-control" name="textAll" value="'.htmlspecialchars($infoSearchEngine['textAll']).'"></td>    
    </tr>
    <tr>
        <td>SQL запит на новинки:</td>
        <td>
            <textarea class="form-control" name="sqlNews" style="width:100%;">'.$infoSearchEngine['sqlNews'].'</textarea>        
        </td>    
    </tr>
    <tr>
        <td>SQL запит на акційні:</td>
        <td>
            <textarea class="form-control" name="sqlActions" style="width:100%;">'.$infoSearchEngine['sqlActions'].'</textarea>        
        </td>    
    </tr>
    <tr>
        <td>SQL запит на всі товари:</td>
        <td>
            <textarea class="form-control" name="sqlAllItems" style="width:100%;">'.$infoSearchEngine['sqlAllItems'].'</textarea>        
        </td>    
    </tr>
</table>
<input type="submit" class="btn btn-success" value="Зберегти">
</form>
<hr>
<h4>Налаштування ключових слів</h4>
<form action="" method="POST">
<textarea class="form-control" name="keywords" style="height:200px;">'.$keywords.'</textarea>
<div><input type="submit" class="btn btn-success" name="submit" value="Зберегти"></div>
</form>
<hr>
<h4>Налаштування верхнього блоку (Вік)</h4>
<a href="#" onclick="window.open(\'addImageSearch.php?type=top&id='.$infoSearchEngine['id'].'\', \'_blank\', \'Toolbar=0, Scrollbars=1, Resizable=0, Width=640, resize=no, Height=480\'); return false;">Додати зображення</a>
'.$bodyTop.'
<hr>
<h4>Налаштування нижнього блоку</h4>
<a href="#" onclick="window.open(\'addImageSearch.php?type=bottom&id='.$infoSearchEngine['id'].'\', \'_blank\', \'Toolbar=0, Scrollbars=1, Resizable=0, Width=640, resize=no, Height=480\'); return false;">Додати зображення</a>
'.$bodyBottom.'
<hr>
<h4>Налаштування каруселі банерів</h4>
<a href="#" onclick="window.open(\'addImageSearch.php?type=baner&id='.$infoSearchEngine['id'].'\', \'_blank\', \'Toolbar=0, Scrollbars=1, Resizable=0, Width=640, resize=no, Height=480\'); return false;">Додати зображення</a>
'.$bodyBanner.'
';