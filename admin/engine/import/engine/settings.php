<?php
$body_admin .= '
<h2>Сохраненные настройки</h2>
<a href="index.php?do=import&action=settings&method=add">Добавить новую настройку</a>';
if (!isset ($_GET['method']))
{
    if (isset ($_GET['no_method']))
    {
        switch ($_GET['no_method'])
        {
            case "del":
                if (unlink('engine/import/userfiles/settings/'.$_GET['settings'].'.ini'))
                {
                    $body_admin .= '<br><span style="color:green;">Файл настроек '.$_GET['settings'].' - успешно удален</span>';
                } else {
                    $body_admin .= '<br><span style="color:red;">Файл настроек '.$_GET['settings'].' - удалить не удалось</span>';
                }
            break;
        }
    }
    $path_settings = 'engine/import/userfiles/settings/';
    $array_settings_files = scandir ($path_settings);
    if (count($array_settings_files)>2)
    {
        $body_admin .= '
        <div align="center">
        <table border="0">
        <tr>
            <th>Название настроек</th>
            <th>Тип настроек</th>
            <th>Дата создания</th>
            <th>Дата последнего изменения</th>
            <th><img src="'.$config ['site_url'].'images/admin/edit.png"></th>
            <th><img src="'.$config ['site_url'].'images/admin/remove_16.png"></th>
        </tr>
        ';
        foreach ($array_settings_files as $v)
        {
            if ($v!='.' and $v!='..')
            {
                $info = file('engine/import/userfiles/settings/'.$v);
                if ($info[3]==1)
                {
                    $type_settings = 'Обновление';
                } else {
                    $type_settings = 'Импорт';
                }
                $body_admin .= '
                <tr>
                    <td>'.$info[0].'</td>
                    <td>'.$type_settings.'</td>
                    <td>'.date('d.m.Y H:i:s', str_replace ('.ini', '', $v)).'</td>
                    <td>'.date('d.m.Y H:i:s', $info[5]).'</td>
                    <td><a href="index.php?do=import&action=settings&method=edit&settings='.str_replace ('.ini', '', $v).'"><img src="'.$config ['site_url'].'images/admin/edit.png"></a></td>
                    <td><a href="index.php?do=import&action=settings&no_method=del&settings='.str_replace ('.ini', '', $v).'""  onclick="return confirm (\'Подтвердить удаление?\');"><img src="'.$config ['site_url'].'images/admin/remove_16.png"></a></td>
                </tr>
                ';
            }
        }
        $body_admin .= '</table></div>';
    } else {
        $body_admin .= '<br><span style="color:red;">Настроек еще нет</span>';
    }
} else {
    switch ($_GET['method'])
    {
        case "edit":
            if (isset ($_POST['name_settings']))
            {
                if ($_POST['type'])
                {
                    $file = $_POST['file_update'];
                } else {
                    $file = $_POST['file_import'];
                }
                $body_file .= $_POST['name_settings']."\n".$_POST['razd']."\n".$_POST['filtr']."\n".$_POST['type']."\n".$file."\n".time();
                $filename = 'engine/import/userfiles/settings/'.$_GET['settings'].'.ini';
                $fh = fopen($filename, "w+");
                $success = fwrite($fh, $body_file);
                fclose($fh);
                if (is_file($filename))
                {
                    $body_admin .= '<div align="center"><span style="color:green">Настройки успешно сохранены, <a href="index.php?do=import&action=settings">просмотр настроек</a></span></div>';
                } else {
                    $body_admin .= '<div align="center"><span style="color:red">Ошибка при сохранении настроек</span></div>'; 
                }
            } else {
                $info = file('engine/import/userfiles/settings/'.$_GET['settings'].'.ini');
                $path_update = 'engine/import/userfiles/update/';
                $array_update_files = scandir ($path_update);
                foreach ($array_update_files as $v)
                {
                    if ($v!='.' and $v!='..')
                    {
                        if ($info[4]==$v)
                        {
                            $option_update .= '<option value="'.$v.'" selected>'.str_replace ('.php', '', $v).'</option>'."\n";
                        } else {
                            $option_update .= '<option value="'.$v.'">'.str_replace ('.php', '', $v).'</option>'."\n";
                        }
                    }
                }
                $path_update = 'engine/import/userfiles/import/';
                $array_update_files = scandir ($path_update);
                foreach ($array_update_files as $v)
                {
                    if ($v!='.' and $v!='..')
                    {
                        if ($info[4]==$v)
                        {
                            $option_import .= '<option value="'.$v.'" selected>'.str_replace ('.php', '', $v).'</option>'."\n";
                        } else {
                            $option_import .= '<option value="'.$v.'">'.str_replace ('.php', '', $v).'</option>'."\n";
                        }
                    }
                }
                if (is_writable('engine/import/userfiles/settings'))
                {
                    $is_writeble = '<br><span style="color:green;">Директория <i>engine/import/engine/settings/</i>, где сохраняются настройки - доступна для записи</span>';
                } else {
                    $is_writeble = '<br><span style="color:red;">Директория <i>engine/import/engine/settings/</i>, где сохраняются настройки - не доступна для записи</span>';
                }
                if ($info[3]==1)
                {
                    $update_selected = 'selected';
                    $update_display = 'block';
                    $import_display = 'none';
                } else {
                    $import_selected = 'selected';
                    $update_display = 'none';
                    $import_display = 'block';
                }
                $body_admin .= '
                <div align="center">
                <b>Создание новой настройки</b>
                <form action="" method="POST">
                    '.$is_writeble.'
                        <table border="0" style="border:1px solid grey; width:500px;">
                            <tr>
                                <td width="200">Название настройки:</td>
                                <td><input type="text" name="name_settings" value="'.$info[0].'"></td>
                            </tr>
                            <tr>
                                <td>Разделитель для парсинга CSV:</td>
                                <td><input type="text" name="razd" value="'.$info[1].'"></td>
                            </tr>
                            <tr>
                                <td>Фильтровать символы:</td>
                                <td><input type="text" name="filtr" value="'.$info[2].'"></td>
                            </tr>
                            <tr>
                                <td>Тип действия:</td>
                                <td>
                                    <select name="type" id="type" onchange="visible_dop_settings();">
                                        <option value="">Сделайте выбор</option>
                                        <option value="0" '.$import_selected.'>Импорт</option>
                                        <option value="1" '.$update_selected.'>Обновление</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <div style="display:'.$import_display.';" id="import">
                            <table border="0" style="border:1px solid grey; width:500px; margin-top:10px;">
                                <tr>
                                    <td colspan="2"><b>Дополнительные настройки для импорта</b></td>
                                </tr>
                                <tr>
                                    <td width="200">Файл обработчик:</td>
                                    <td>
                                        <select name="file_import">
                                            '.$option_import.'
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center"><input type="submit" name="submit" value="Сохранить настройки"></td>
                                </tr>
                            </table>
                        </div>
                        <div style="display:'.$update_display.';" id="update">
                            <table border="0" style="border:1px solid grey; width:500px; margin-top:10px;">
                                <tr>
                                    <td colspan="2"><b>Дополнительные настройки для обновления</b></td>
                                </tr>
                                <tr>
                                    <td>Файл обработчик:</td>
                                    <td>
                                        <select name="file_update">
                                            '.$option_update.'
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center"><input type="submit" name="submit" value="Сохранить настройки"></td>
                                </tr>
                            </table>
                        </div>
                    </form>
                </div>
                ';
            }
        break;
        case "add":
            if (isset ($_POST['name_settings']))
            {
                if ($_POST['type'])
                {
                    $file = $_POST['file_update'];
                } else {
                    $file = $_POST['file_import'];
                }
                $body_file .= $_POST['name_settings']."\n".$_POST['razd']."\n".$_POST['filtr']."\n".$_POST['type']."\n".$file."\n".time();
                $filename = 'engine/import/userfiles/settings/'.time().'.ini';
                $fh = fopen($filename, "w+");
                $success = fwrite($fh, $body_file);
                fclose($fh);
                if (is_file($filename))
                {
                    $body_admin .= '<div align="center"><span style="color:green">Настройки успешно сохранены, <a href="index.php?do=import&action=settings">просмотр настроек</a></span></div>';
                } else {
                    $body_admin .= '<div align="center"><span style="color:red">Ошибка при сохранении настроек</span></div>'; 
                }
            } else {
                $path_update = 'engine/import/userfiles/update/';
                $array_update_files = scandir ($path_update);
                foreach ($array_update_files as $v)
                {
                    if ($v!='.' and $v!='..')
                    {
                        $option_update .= '<option value="'.$v.'">'.str_replace ('.php', '', $v).'</option>'."\n";
                    }
                }
                $path_update = 'engine/import/userfiles/import/';
                $array_update_files = scandir ($path_update);
                foreach ($array_update_files as $v)
                {
                    if ($v!='.' and $v!='..')
                    {
                        $option_import .= '<option value="'.$v.'">'.str_replace ('.php', '', $v).'</option>'."\n";
                    }
                }
                if (is_writable('engine/import/userfiles/settings'))
                {
                    $is_writeble = '<br><span style="color:green;">Директория <i>engine/import/engine/settings/</i>, где сохраняются настройки - доступна для записи</span>';
                } else {
                    $is_writeble = '<br><span style="color:red;">Директория <i>engine/import/engine/settings/</i>, где сохраняются настройки - не доступна для записи</span>';
                }
                $body_admin .= '
                <div align="center">
                <b>Создание новой настройки</b>
                <form action="" method="POST">
                    '.$is_writeble.'
                        <table border="0" style="border:1px solid grey; width:500px;">
                            <tr>
                                <td width="200">Название настройки:</td>
                                <td><input type="text" name="name_settings"></td>
                            </tr>
                            <tr>
                                <td>Разделитель для парсинга CSV:</td>
                                <td><input type="text" name="razd" value=";"></td>
                            </tr>
                            <tr>
                                <td>Фильтровать символы:</td>
                                <td><input type="text" name="filtr"></td>
                            </tr>
                            <tr>
                                <td>Тип действия:</td>
                                <td>
                                    <select name="type" id="type" onchange="visible_dop_settings();">
                                        <option value="">Сделайте выбор</option>
                                        <option value="0">Импорт</option>
                                        <option value="1">Обновление</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <div style="display:none;" id="import">
                            <table border="0" style="border:1px solid grey; width:500px; margin-top:10px;">
                                <tr>
                                    <td colspan="2"><b>Дополнительные настройки для импорта</b></td>
                                </tr>
                                <tr>
                                    <td width="200">Файл обработчик:</td>
                                    <td>
                                        <select name="file_import">
                                            '.$option_import.'
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center"><input type="submit" name="submit" value="Сохранить настройки"></td>
                                </tr>
                            </table>
                        </div>
                        <div style="display:none;" id="update">
                            <table border="0" style="border:1px solid grey; width:500px; margin-top:10px;">
                                <tr>
                                    <td colspan="2"><b>Дополнительные настройки для обновления</b></td>
                                </tr>
                                <tr>
                                    <td>Файл обработчик:</td>
                                    <td>
                                        <select name="file_update">
                                            '.$option_update.'
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center"><input type="submit" name="submit" value="Сохранить настройки"></td>
                                </tr>
                            </table>
                        </div>
                    </form>
                </div>
                ';
            }
        break;
    }
}
?>