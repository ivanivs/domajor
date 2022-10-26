<?php
$info = file ('engine/import/userfiles/settings/'.$_GET['settings']);
if ($info[3]==1)
{
    $body_admin .= '
    <h3>Обновление базы с использованием сохраненных настроек и обработчика</h3>
    ';
    $body_admin .= '<span style="color:green;">Подключаем обработчик, и передаем ему управление обработкой</span>';
    require ('engine/import/userfiles/update/'.trim($info[4]));
} else {
    $body_admin .= '
    <h3>Импорт товара в базу использованием сохраненных настроек и обработчика</h3>
    ';
    $body_admin .= '<span style="color:green;">Подключаем обработчик, и передаем ему управление обработкой</span>';
    require ('engine/import/userfiles/import/'.trim($info[4]));
}
?>