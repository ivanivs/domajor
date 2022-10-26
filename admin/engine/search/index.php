<?php
$body_admin .= '
<div style="text-align: center;">
    <a href="index.php?do=search" style="display: inline-block;"><img src="https://bobas.ua/images/admin/view.png"><br> [Просмотр]</a>
    <a href="index.php?do=search&submode=create" style="display: inline-block;"><img src="https://bobas.ua/images/admin/add.png"><br> [Створити новий]</a>
</div>
';
if (isset ($_GET['enable'])){
    mysql_query("UPDATE `ls_searchSystem` SET `status` = 1 where `id` = '".intval($_GET['enable'])."';");
    header('Location: '.$config['site_url'].'admin/index.php?do=search');
    exit;
}
if (isset ($_GET['disable'])){
    mysql_query("UPDATE `ls_searchSystem` SET `status` = 0 where `id` = '".intval($_GET['disable'])."';");
    header('Location: '.$config['site_url'].'admin/index.php?do=search');
    exit;
}
if (isset ($_FILES['userfile']['name'])){
    $uploaddir = 'upload/searchSystem';
    $uploadfile = $uploaddir . '/searchSystem_'.intval($_POST['searchId']).'_header.jpg';
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], '../'.$uploadfile)) {
        $success = '<div class="alert alert-success">Файл успішно завантажено.</div>';
        mysql_query("UPDATE `ls_searchSystem` SET `header` = '".$uploadfile."' WHERE `id` = '".intval($_POST['searchId'])."';");
    } else {
        $success = '<div class="alert alert-error">Ошибка загрузки</div>';
    }
}
if (!isset ($_GET['submode'])){
    if ($arraySearch = getArray("SELECT * FROM `ls_searchSystem`")){
        $body_admin .= '
        <h2>Ваші пошукові системи</h2>
        '.$success.'
        <table class="table table-bordered table-striped">
        <tr>
            <td>Назва</td>
            <td>Хедер</td>
            <td>Налаштування</td>
            <td>Статус</td>
        </tr>

        ';
        foreach ($arraySearch as $v){
            $body_admin .= '
            <tr '.(($v['status']==0 ? 'class="error"' : 'class="success"')).'>
                <td>'.$v['name'].'</td>
                <td>
                    '.(($v['header']!='' ? '<div><a href="'.$config['site_url'].$v['header'].'"><img src="'.$config['site_url'].$v['header'].'" style="width: 200px;"></a></div>' : '')).'
                    <a href="#" onclick="getModalLoadingHeader('.$v['id'].');">Завантажити хедер</a>
                </td>
                <td><a href="index.php?do=search&submode=config&id='.$v['id'].'">Налаштування</a></td>
                <td>'.(($v['status']==0) ? '<a href="index.php?do=search&enable='.$v['id'].'">Увімкнути</a>' : '<a href="index.php?do=search&disable='.$v['id'].'">Вимкнути</a>').'</td>
            </tr>
            ';
        }
        $body_admin .= '</table>
        <div id="modalSearch"></div>
        ';
    }
} else {
    switch ($_GET['submode']){
        case "create":
            require ('engine/search/files/create.php');
            break;
        case "config":
            require ('engine/search/files/config.php');
            break;
    }
}
