<?php
$body_admin .= '
<h1>Импорт товаров с CSV файлов</h1>
<a href="index.php?do=import&action=settings" style="color:red;">Настройки</a><br>
<a href="index.php?do=import&action=list_file" style="color:red;">Список загруженых файлов</a>

';
if (isset ($_GET['action']))
{
    $body_admin .= '
        <br>
        <a href="index.php?do=import" style="color:red;">Форма загрузки</a>
        ';
}
if (!isset ($_GET['action']))
{
    $body_admin .= '<form action="" method="post" enctype="multipart/form-data">
    Выберите файл для загрузки: <input type="file" name="filename"><br>
    <input type="hidden" name="upload" value="1">
    <input type="submit" value="Загрузить файл с товарами"><br>
    </form>';
    if (isset ($_POST['upload']))
    {
        $uploaddir = '../upload/files/';
        $uploadfile = $uploaddir.time().'_'.rand(0, 100).'.csv';
        if (move_uploaded_file($_FILES['filename']['tmp_name'], $uploadfile)) {
            $body_admin .= '<span style="color:green;">Файл успешно загружен</span>';
        } else {
            $body_admin .= '<span style="color:red;">Ошибка при загрузке файла</span>';
        }
    }
} else {
    switch ($_GET['action'])
    {
        case "settings":
            require ('engine/import/engine/settings.php');
        break;
        case "list_file":
            $uploaddir = '../upload/files/';
            $array_files = scandir ($uploaddir);
            $body_admin .= '<table border="0">
            <tr>
                <th>Название файла</th>
                <th>Время последнего обращения</th>
                <th>Время последнего изменения</th>
                <th>Размер (Mb)</th>
                <th>Обработать готовым обработчиком</th>
                <th></th>
            </tr>
            ';
            $path_settings = 'engine/import/userfiles/settings/';
            $array_settings_files = scandir ($path_settings);
            if (count($array_settings_files)>2)
            {
                foreach ($array_settings_files as $v)
                {
                    if ($v!='.' and $v!='..')
                    {
                        $info = file('engine/import/userfiles/settings/'.$v);
                        $option_settings .= '<option value="'.$v.'">'.$info[0].'</option>';
                    }
                }
            }
            foreach ($array_files as $key => $v)
            {
                if ($v!='.' and $v!='..')
                {
                    $body_admin .= '
                    <tr>
                        <td>'.$v.'</td>
                        <td>'.date('d.m.Y H:i:s', fileatime($uploaddir.$v)).'</td>
                        <td>'.date('d.m.Y H:i:s', filemtime($uploaddir.$v)).'</td>
                        <td>'.round(filesize($uploaddir.$v)/1048576, 2).'</td>
                        <td><select id="settings_'.str_replace ('.csv', '', $v).'" onchange="geterate_ling_set(\''.str_replace ('.csv', '', $v).'\');"><option>Сделайте выбор</option>'.$option_settings.'</select><div id="link_'.str_replace ('.csv', '', $v).'"></div></td>
                        <td><a href="index.php?do=import&action=one_file&name='.str_replace ('.csv', '', $v).'"><img src="'.$config ['site_url'].'images/admin/next.png"></a></td>
                    </tr>
                    ';
                    //$body_admin .=  $v."<br>";
                }
            }
            $body_admin .= '</table>';
        break;
        case "proccess_from_settings":
            require_once('engine/params/functions.php');
            require ('engine/import/engine/proccess_from_settings.php');
        break;
        case "one_file":
            $uploaddir = '../upload/files/';
            $file = $_GET['name'].'.csv';
            $array_file = file($uploaddir.$file);
            $main_col = explode (';', $array_file[0]);
            $array_card = return_all_card();
            if ($array_card)
            {
                if (!isset ($option_card))
                $option_card = '';
                foreach ($array_card as $v)
                {
                    $option_card .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                }
            }
            if (!isset ($option))
            $option = '';
            foreach ($main_col as $key => $v)
            {
                $option .= '<option value="'.$key.'">'.$v.'</option>';
            }
            if (isset ($_POST['filtr']))
            {
                $filtr_post = $_POST['filtr'];
            } else {
                $filtr_post = '';
            }
            if (!isset ($option_card))
            $option_card = '';
            $body_admin .= '
            <script type="text/javascript">
            function setChecked(obj) 
               {
               var str = document.getElementById("text").innerHTML;
               str = (str == "отметить" ? "снять" : "отметить");
               document.getElementById("text").innerHTML = str;
               
               var check = document.getElementsByName("checked[]");
               for (var i=0; i<check.length; i++) 
                  {
                  check[i].checked = obj.checked;
                  }
               }
            function all_param_from_card(){
                var cont = document.getElementById(\'all_param_from_card\');
                var id_card = document.getElementById(\'id_card\');
                cont.innerHTML = loading;  
                link = "engine/ajax/all_param_from_card.php";
                var query = "id_card=" + id_card.value;
                var http = createRequestObject();  
                if( http )   
                {  
                    http.open(\'post\', link);
                    http.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                    http.send(query);
                    http.onreadystatechange = function ()   
                    {  
                        if(http.readyState == 4)   
                        {  
                            cont.innerHTML = http.responseText;
                            all_param_from_card_2();
                        }  
                    }  
                    http.send(null);      
                }  
                else   
                {  
                    document.location = link;  
                }  
            }
            function all_param_from_card_2(){
                var cont = document.getElementById(\'all_param_from_card_2\');
                var id_card = document.getElementById(\'id_card\');
                cont.innerHTML = loading;  
                link = "engine/ajax/all_type_param_from_card.php";
                var query = "id_card=" + id_card.value + "&param=1";
                var http = createRequestObject();  
                if( http )   
                {  
                    http.open(\'post\', link);
                    http.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                    http.send(query);
                    http.onreadystatechange = function ()   
                    {  
                        if(http.readyState == 4)   
                        {  
                            cont.innerHTML = http.responseText;
                        }  
                    }  
                    http.send(null);      
                }  
                else   
                {  
                    document.location = link;  
                }  
            }
            </script>
            <div style="overflow: scroll; width:1290px;">
            <form action="" method="POST">
            Фильтровать символы: <input type="text" name="filtr" style="border:1px solid grey;" value=\''.$filtr_post.'\'><br>
            Сравнивать по колонке: <select name="main_col">'.$option.'</select><br>
            Выбор карточки товара:
                <select name="id_card" id="id_card" onchange="all_param_from_card();">
                <option value="0">Сделайте выбор</option>
                '.$option_card.'
                </select>
            <div id="all_param_from_card"></div>
            <div id="all_param_from_card_2"></div>
            Значения берем с колонок: <select name="main_col" multiple>'.$option.'</select><br>
            <p>
                <input type="checkbox" name="set" onclick="setChecked(this)" /> 
                <span id="text">отметить</span> все
             </p>
            <table border="0" id="table_import" cellpadding="0" cellspacing="0">';
            if (count($array_file))
            {
                if (isset ($_POST['checked']))
                {
                    $checked_array = $_POST['checked'];
                }
                if (isset ($_POST['id_card']))
                {
                    $info_cardparam = mysql_fetch_array(mysql_query("SELECT `id` FROM `ls_cardparam` where `id_param` = '".$_POST['id_param_main']."' and `id_card` = '".$_POST['id_card']."' and `db_type` = 'text' LIMIT 0,1;"), MYSQL_ASSOC);
                }
                foreach ($array_file as $key => $v)
                {
                    $array_v = explode ('|', $v);
                    foreach ($array_v as $key_v1 => $v_1)
                    {
                        if (isset ($_POST['filtr']))
                        {
                            $array_filtr = explode ('|', $_POST['filtr']);
                            foreach ($array_filtr as $one_filtr)
                            {
                                $v_1 = str_replace ($one_filtr, '', $v_1);
                            }
                        }
                        if (isset ($_POST['main_col']) and $_POST['main_col'] == $key_v1)
                        {
                            if (isset ($_POST['id_card']))
                            {
                                $sql = "SELECT COUNT(*) FROM `ls_values_text` where `id_cardparam` = '".$info_cardparam['id']."' and `text` = '".$v_1."';";
                                $count_item_for_price = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
                                $count_in_db = $count_item_for_price['COUNT(*)'];
                                if ($count_in_db)
                                {
                                    $count_in_db = '<span style="color:green; font-weight:bold;">'.$count_in_db.'</span><br>';
                                } else {
                                    $count_in_db = '<span style="color:red;">'.$count_in_db.'</span><br>';
                                }
                            }
                        }
                        if (!isset ($body_admin_tmp))
                        $body_admin_tmp = '';
                        $body_admin_tmp .= '<td>'.$v_1.'</td>';
                    }
                    if (!isset ($count_in_db))
                    $count_in_db = '';
                    if (isset ($_POST['checked']))
                    {
                        if (in_array($key, $checked_array))
                        {
                            $body_admin .= '<tr>
                            <td>'.$count_in_db.'<input type="checkbox" name="checked[]" value="'.$key.'" checked></td>
                            ';
                        } else {
                            $body_admin .= '<tr>
                            <td>'.$count_in_db.'<input type="checkbox" name="checked[]" value="'.$key.'"></td>
                            ';
                        }
                    } else {
                        $body_admin .= '<tr>
                        <td>'.$count_in_db.'<input type="checkbox" name="checked[]" value="'.$key.'"></td>
                        ';
                    }
                    $body_admin .= $body_admin_tmp;
                    unset ($body_admin_tmp);
                    $body_admin .= '</tr>';
                }
            }
            $body_admin .= '
                </table>
                <input type="submit" name="submit" value="Обработать">
                </form>
                </div>';
        break;
    }
}
$body_admin = '
<div id="info_admin_page">
<img src="'.$config ['site_url'].'images/admin/rightarrow.png"> Управление импортом товара'.$other_way.'
</div>
'.$body_admin;
?>