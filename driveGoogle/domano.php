<?php
if ($_COOKIE['accessLevel']==100) {
    if (isset ($_POST['upload'])) {
        ini_set('memory_limit', '2048M');
        require('../config.php');
        $mysql_connect_id = mysql_connect($config ['host'], $config ['user_datebase'], $config ['password_datebase']);
        mysql_select_db($config ['database']);
        mysql_query("SET NAMES 'UTF8';");
        require('../include/functions.php');
        require('PHPExcel.php');
        $uploadfile = basename($_FILES['userfile']['name']);
        if (move_uploaded_file($_FILES['userfile']['tmp_name'], 'domano.xlsx')) {
            echo '<div style="color: green">File upload successfully</div>';
            $filename = "domano.xlsx";
            $type = PHPExcel_IOFactory::identify($filename);
            $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
            $cacheSettings = array(' memoryCacheSize ' => '8MB');
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
            $objReader = PHPExcel_IOFactory::createReader($type);
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($filename);
            $arrayResult = array();
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $oneCell = $worksheet->toArray(NULL, false, true, true);
                $worksheets[$worksheet->getTitle()] = $oneCell;
                $arrayResult[] = $oneCell;
            }
            $arrayStock = array();
            $arraySumm = array();
            $arrayIds = array();
            mysql_query("UPDATE `ls_items` SET `text_12` = 0 WHERE `select_14` = 281");
            mysql_query("UPDATE `ls_items` SET `select_10` = 205 WHERE `select_14` = 281 AND `text_7` < 1;");
            foreach ($arrayResult[0] as $key => $oneItem) {
                if (!empty($oneItem['F']) AND $oneItem['F']!='#NULL!') {
                    $arrayStock[$oneItem['B']] = 1;
                    $arraySumm[$oneItem['B']] = format_summ($oneItem['F']);
                    if ($info = getOneString("SELECT * FROM `ls_items` WHERE `text_2` = '" . $oneItem['A'] . "' AND `select_14` = 281")) {
                        echo '<div style="color: green;">' . $info['searchField'] . " - " . $oneItem['C'] . " - " . format_summ($oneItem['F']) . " - Код по сайту: " . $info['id'] . " Артикул: " . $oneItem['A'] . "</div>";
                        mysql_query("UPDATE `ls_items` SET `text_12` = 1, `price_1` = '" . format_summ($oneItem['F']) . "',`select_10` = 204 WHERE `id` = '" . $info['id'] . "'");
                        $arrayIds[] = $info['id'];
                    } else {
                        echo '<div style="color: red;">' . $oneItem['B'] . ' - ' . $oneItem['C'] . ' - NOT FOUND' . " Артикул: " . $oneItem['A'] . "</div>";
                    }
                }
            }
        } else {
            echo '<div style="color: red;">Error loading file</div>';
        }
    } else {
        echo '
    <form enctype="multipart/form-data" action="" method="POST">
        <input type="hidden" name="upload" value="1">
        Отправить этот файл: <input name="userfile" type="file" />
        <input type="submit" value="Upload" />
    </form>
    ';
    }
} else {
    echo '<div style="color:red;">Access denied</div>';
}