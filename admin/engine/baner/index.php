<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 1/30/17
 * Time: 12:57 PM
 * To change this template use File | Settings | File Templates.
 */
if (isset ($_GET['del'])){
    mysql_query("DELETE FROM `ls_baner` WHERE `id` = '".intval($_GET['del'])."';");
}
if (isset ($_POST['link'])){
    $uploaddir = 'upload/baner';
    $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], '../'.$uploadfile)) {
        $body_admin .= '<div class="alert alert-success">Файл корректен и был успешно загружен.</div>';
        mysql_query("
        INSERT INTO  `ls_baner` (
        `name` ,
        `link` ,
        `file`,
        `main`
        )
        VALUES (
        '".mysql_real_escape_string($_POST['name'])."' ,
        '".mysql_real_escape_string($_POST['link'])."' ,
        '".mysql_real_escape_string($uploadfile)."' ,
        '".intval($_POST['main'])."'
        );
        ");
    } else {
        $body_admin .= '<div class="alert alert-error">Ошибка загрузки</div>';
    }
}
$body_admin .= '
<h2>Додати банер</h2>
<form enctype="multipart/form-data" action="" method="POST">
    <!-- Поле MAX_FILE_SIZE должно быть указано до поля загрузки файла -->
        <!-- Название элемента input определяет имя в массиве $_FILES -->
    <div>Посилання: <input type="text" name="link"></div>
    <div>Текст: <input type="text" name="name' .
    '"></div>
    <div>Куди? <select class="form-control" name="main"><option value="1">Лівий</option><option value="0">Правий</option></select></div>
    <div>Файл: <input name="userfile" type="file" /></div>
    <div><input type="submit" value="Завантажити" /></div>
</form>
<h2>Завантажені банери:</h2>
';
if ($array = getArray("SELECT * FROM `ls_baner`")){
    $body_admin .= '
    <table class="table table-bordered table-striped">
    <thead>
        <th>IMG</th>
        <th>Розміщення</th>
        <th>LINK</th>
        <th>DEL</th>
    </thead>
    <tbody>
    ';
    foreach ($array as $v){
        switch ($v['main']){
            case 0:
                $main = 'Правий банер';
                break;
            case 1:
                $main = 'Лівий банер';
                break;
        }
        $body_admin .= '
        <tr>
            <td><img src="'.$config['site_url'].$v['file'].'" style="width: 200px;"></td>
            <td>'.$main.'</td>
            <td><a href="'.$v['link'].'" target="_blank">'.$v['link'].'</a></td>
            <td><a href="index.php?do=baner&del='.$v['id'].'">видалити</a></td>
        </tr>
        ';
    }
    $body_admin .= '
    </tbody>
    </table>
    ';
}