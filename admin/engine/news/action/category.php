<?php
if (isset ($_GET['add']))
{
    if (isset ($_POST['name_category']))
    {
        $sql = "
        INSERT INTO `ls_news_category`
        (`parent_id`, `sort`, `sort_type`, `template_short_news`, `template_full_news`, `only_short_news`, `number_on_page`)
        VALUES (
        '".$_POST['parent_id']."',
        '".$_POST['sort']."',
        '".$_POST['sort_type']."',
        '".$_POST['template_short_news']."',
        '".$_POST['template_full_news']."',
        '".$_POST['only_short_news']."' ,
        '".$_POST['number_on_page']."'
        );
        ";
        if (mysql_query($sql))
        {
            $id_category = mysql_insert_id();
            $sql = "INSERT INTO  `ls_translate` (
            `type` ,
            `id_lang` ,
            `id_elements` ,
            `text`
            )
            VALUES (
            'news_category_name',
            '".$id_online_lang."' ,
            '".$id_category."' ,
            '".$_POST['name_category']."'
            );";
            mysql_query($sql);
            $sql = "INSERT INTO  `ls_translate` (
            `type` ,
            `id_lang` ,
            `id_elements` ,
            `text`
            )
            VALUES (
            'news_category_title',
            '".$id_online_lang."' ,
            '".$id_category."' ,
            '".$_POST['title']."'
            );";
            mysql_query($sql);
            $sql = "INSERT INTO  `ls_translate` (
            `type` ,
            `id_lang` ,
            `id_elements` ,
            `text`
            )
            VALUES (
            'news_category_description',
            '".$id_online_lang."' ,
            '".$id_category."' ,
            '".$_POST['description_category']."'
            );";
            mysql_query($sql);
            $sql = "INSERT INTO  `ls_translate` (
            `type` ,
            `id_lang` ,
            `id_elements` ,
            `text`
            )
            VALUES (
            'news_category_keywords',
            '".$id_online_lang."' ,
            '".$id_category."' ,
            '".$_POST['keywords_category']."'
            );";
            mysql_query($sql);
            $body_admin .= '<h2 style="color:green;">'.$lang[408].'</h2>';
        }
    }
    $array_template = return_all_template();
    if ($array_template)
    {
        foreach ($array_template as $v)
        {
            $option_template .= '<option value="'.$v['id'].'">'.$v['name_template'].'</option>'."\n";
        }
    }
    $all_category = return_option_category ('0', '', '');
    $body_admin .= '
    <div align="center">
    <h1>'.$lang[388].'</h1>
    <small style="color:red;">'.$lang[389].'</small>
    <form action="" method="POST">
        <table border="0" style="width:600px;">
            <tr>
                <td width="180">'.$lang[390].':</td>
                <td><b>'.$info_for_my_lang['name'].'</b></td>
            </tr>
            <tr>
                <td>'.$lang[391].':</td>
                <td><input type="text" name="name_category" class="form_text"></td>
            </tr>
            <tr>
                <td>'.$lang[410].':</td>
                <td><input type="text" name="number_on_page" class="form_text" value="10"></td>
            </tr>
            <tr>
                <td>'.$lang[409].':</td>
                <td>
                    <select name="parent_id" class="form_text">
                    <option value="0">'.$lang[411].'</option>
                    '.$all_category.'
                    </select>
                </td>
            </tr>
            <tr>
                <td>'.$lang[392].':</td>
                <td><input type="text" name="title" class="form_text"></td>
            </tr>
            <tr>
                <td>'.$lang[393].':</td>
                <td><textarea name="description_category" class="form_text" rows="10" cols="40"></textarea></td>
            </tr>
            <tr>
                <td>'.$lang[394].':</td>
                <td><textarea name="keywords_category" class="form_text" rows="5" cols="40"></textarea></td>
            </tr>
            <tr>
                <td>'.$lang[395].':</td>
                <td>
                    <select name="sort" class="form_text">
                        <option value="date">'.$lang[396].'</option>
                        <option value="rating">'.$lang[397].'</option>
                        <option value="viewer">'.$lang[398].'</option>
                        <option value="alphabet">'.$lang[399].'</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>'.$lang[400].':</td>
                <td>
                    <select name="sort_type" class="form_text">
                        <option value="ASK">'.$lang[401].'</option>
                        <option value="DESC">'.$lang[402].'</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>'.$lang[403].':</td>
                <td>
                    <select name="template_short_news" class="form_text">
                        '.$option_template.'
                    </select>
                </td>
            </tr>
            <tr>
                <td>'.$lang[404].':</td>
                <td>
                    <select name="template_full_news" class="form_text">
                        '.$option_template.'
                    </select>
                </td>
            </tr>
            <tr>
                <td><b>'.$lang[405].'</b><span style="color:red">*</span>:</td>
                <td>
                    <input type="checkbox" name="only_short_news" value="1" class="form_text">
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="submit" name="submit" value="'.$lang[406].'" class="form_text"></td>
            </tr>
        </table>
        </form>
        <span style="color:red">*</span> '.$lang[407].'
    </div>
    ';
} else {
        if (isset ($_GET['edit']))
        {
            if (isset ($_GET['id_online_lang']))
            {
                $tmp_online_lang = $id_online_lang;
                $id_online_lang = $_GET['id_online_lang'];
            }
            if (isset ($_POST['name_category']))
            {
                if (!$_POST['only_short_news'])
                {
                    $only_short_news = 0;
                } else {
                    $only_short_news = $_POST['only_short_news'];
                }
                $sql = "
                update `ls_news_category` set
                `parent_id` = '".$_POST['parent_id']."',
                `sort` = '".$_POST['sort']."',
                `sort_type` = '".$_POST['sort_type']."',
                `template_short_news` = '".$_POST['template_short_news']."',
                `template_full_news` = '".$_POST['template_full_news']."',
                `only_short_news` = '".$only_short_news."',
                `number_on_page` = '".$_POST['number_on_page']."'
                where `id` = '".$_GET['edit']."';
                ";
                if (mysql_query($sql))
                {
                    $sql = "
                    update `ls_translate` set
                    `text` = '".$_POST['name_category']."' where `id_lang` = '".$id_online_lang."' and `id_elements` = '".$_GET['edit']."' and `type` = 'news_category_n';
                    ";
                    mysql_query($sql);
                    $sql = "
                    update `ls_translate` set
                    `text` = '".$_POST['title']."' where `id_lang` = '".$id_online_lang."' and `id_elements` = '".$_GET['edit']."' and `type` = 'news_category_t';
                    ";
                    mysql_query($sql);
                    $sql = "
                    update `ls_translate` set
                    `text` = '".$_POST['description_category']."' where `id_lang` = '".$id_online_lang."' and `id_elements` = '".$_GET['edit']."' and `type` = 'news_category_d';
                    ";
                    mysql_query($sql);
                    $sql = "
                    update `ls_translate` set
                    `text` = '".$_POST['keywords_category']."' where `id_lang` = '".$id_online_lang."' and `id_elements` = '".$_GET['edit']."' and `type` = 'news_category_k';
                    ";
                    mysql_query($sql);
                    $body_admin .= '<h2 style="color:green;">'.$lang[416].'</h2>';
                }
            }
            $info_for_my_lang_category = mysql_fetch_array(mysql_query("select * from `ls_lang` where `id` = '".$id_online_lang."';"), MYSQL_ASSOC);
            $info_category = return_info_category($_GET['edit']);
            $all_category = return_option_category ('0', '', '', $info_category['parent_id']);
            $lang_category_name = mysql_fetch_array(mysql_query("select `text` from `ls_translate` where `type` = 'news_category_n' and `id_elements` = '".$_GET['edit']."' and `id_lang` = '".$id_online_lang."';"), MYSQL_ASSOC);
            $lang_category_title = mysql_fetch_array(mysql_query("select `text` from `ls_translate` where `type` = 'news_category_t' and `id_elements` = '".$_GET['edit']."' and `id_lang` = '".$id_online_lang."';"), MYSQL_ASSOC);
            $lang_category_description = mysql_fetch_array(mysql_query("select `text` from `ls_translate` where `type` = 'news_category_d' and `id_elements` = '".$_GET['edit']."' and `id_lang` = '".$id_online_lang."';"), MYSQL_ASSOC);
            $lang_category_keyword = mysql_fetch_array(mysql_query("select `text` from `ls_translate` where `type` = 'news_category_k' and `id_elements` = '".$_GET['edit']."' and `id_lang` = '".$id_online_lang."';"), MYSQL_ASSOC);
            if ($info_category['only_short_news'])
            {
                $checked_only_short_news = 'checked';
            }
            switch ($info_category['sort'])
            {
                case "date";
                    $sort_date = 'selected';
                break;
                case "rating";
                    $sort_rating = 'selected';
                break;
                case "viewer";
                    $sort_viewer = 'selected';
                break;
                case "alphabet";
                    $sort_alphabet = 'selected';
                break;
            }
            switch ($info_category['sort_type'])
            {
                case "ASK":
                    $sort_type_ASK = 'selected';
                break;
                case "DESC":
                    $sort_type_DESC = 'selected';
                break;
            }
            $array_template = return_all_template();
            if ($array_template)
            {
                foreach ($array_template as $v)
                {
                    if ($info_category['template_short_news']==$v['id'])
                    {
                        $option_template_short .= '<option value="'.$v['id'].'" selected>'.$v['name_template'].'</option>'."\n";
                    } else {
                        $option_template_short .= '<option value="'.$v['id'].'">'.$v['name_template'].'</option>'."\n";
                    }
                }
            }
            if ($array_template)
            {
                foreach ($array_template as $v)
                {
                    if ($info_category['template_full_news']==$v['id'])
                    {
                        $option_template_full .= '<option value="'.$v['id'].'" selected>'.$v['name_template'].'</option>'."\n";
                    } else {
                        $option_template_full .= '<option value="'.$v['id'].'">'.$v['name_template'].'</option>'."\n";
                    }
                }
            }
            $body_admin .= '
            <div align="center">
            <h1>'.$lang[415].'</h1>
            <small style="color:red;">'.$lang[389].'</small>
            <form action="" method="POST">
                <table border="0" style="width:600px;">
                    <tr>
                        <td width="180">'.$lang[390].':</td>
                        <td><b>'.$info_for_my_lang_category['name'].'</b></td>
                    </tr>
                    <tr>
                        <td>'.$lang[391].':</td>
                        <td><input type="text" name="name_category" class="form_text" value="'.$lang_category_name['text'].'"></td>
                    </tr>
                    <tr>
                        <td>'.$lang[410].':</td>
                        <td><input type="text" name="number_on_page" class="form_text" value="'.$info_category['number_on_page'].'"></td>
                    </tr>
                    <tr>
                        <td>'.$lang[409].':</td>
                        <td>
                            <select name="parent_id" class="form_text">
                            <option value="0">'.$lang[411].'</option>
                            '.$all_category.'
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>'.$lang[392].':</td>
                        <td><input type="text" name="title" class="form_text" value="'.$lang_category_title['text'].'"></td>
                    </tr>
                    <tr>
                        <td>'.$lang[393].':</td>
                        <td><textarea name="description_category" class="form_text" rows="10" cols="40">'.$lang_category_description['text'].'</textarea></td>
                    </tr>
                    <tr>
                        <td>'.$lang[394].':</td>
                        <td><textarea name="keywords_category" class="form_text" rows="5" cols="40">'.$lang_category_keyword['text'].'</textarea></td>
                    </tr>
                    <tr>
                        <td>'.$lang[395].':</td>
                        <td>
                            <select name="sort" class="form_text">
                                <option value="date" '.$sort_date.'>'.$lang[396].'</option>
                                <option value="rating" '.$sort_rating.'>'.$lang[397].'</option>
                                <option value="viewer" '.$sort_viewer.'>'.$lang[398].'</option>
                                <option value="alphabet" '.$sort_alphabet.'>'.$lang[399].'</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>'.$lang[400].':</td>
                        <td>
                            <select name="sort_type" class="form_text">
                                <option value="ASK" '.$sort_type_ASK.'>'.$lang[401].'</option>
                                <option value="DESC" '.$sort_type_DESC.'>'.$lang[402].'</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>'.$lang[403].':</td>
                        <td>
                            <select name="template_short_news" class="form_text">
                                '.$option_template_short.'
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>'.$lang[404].':</td>
                        <td>
                            <select name="template_full_news" class="form_text">
                                '.$option_template_full.'
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><b>'.$lang[405].'</b><span style="color:red">*</span>:</td>
                        <td>
                            <input type="checkbox" name="only_short_news" value="1" class="form_text" '.$checked_only_short_news.'>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center"><input type="submit" name="submit" value="'.$lang[422].'" class="form_text"></td>
                    </tr>
                </table>
                </form>
                <span style="color:red">*</span> '.$lang[407].'
            </div>
            ';
    } else {
        if ($_GET['add_translate'])
        {
            if (isset ($_GET['id_online_lang']))
            {
                $tmp_online_lang = $id_online_lang;
                $id_online_lang = $_GET['id_online_lang'];
            }
            $info_for_my_lang_category = mysql_fetch_array(mysql_query("select * from `ls_lang` where `id` = '".$id_online_lang."';"), MYSQL_ASSOC);
            if (isset ($_POST['name_category']))
            {
                $id_category = $_GET['add_translate'];
                $sql = "INSERT INTO  `ls_translate` (
                `type` ,
                `id_lang` ,
                `id_elements` ,
                `text`
                )
                VALUES (
                'news_category_name',
                '".$id_online_lang."' ,
                '".$id_category."' ,
                '".$_POST['name_category']."'
                );";
                mysql_query($sql);
                $sql = "INSERT INTO  `ls_translate` (
                `type` ,
                `id_lang` ,
                `id_elements` ,
                `text`
                )
                VALUES (
                'news_category_title',
                '".$id_online_lang."' ,
                '".$id_category."' ,
                '".$_POST['title']."'
                );";
                mysql_query($sql);
                $sql = "INSERT INTO  `ls_translate` (
                `type` ,
                `id_lang` ,
                `id_elements` ,
                `text`
                )
                VALUES (
                'news_category_description',
                '".$id_online_lang."' ,
                '".$id_category."' ,
                '".$_POST['description_category']."'
                );";
                mysql_query($sql);
                $sql = "INSERT INTO  `ls_translate` (
                `type` ,
                `id_lang` ,
                `id_elements` ,
                `text`
                )
                VALUES (
                'news_category_keywords',
                '".$id_online_lang."' ,
                '".$id_category."' ,
                '".$_POST['keywords_category']."'
                );";
                mysql_query($sql);
                $body_admin .= '<h2 style="color:green;">'.$lang[420].'</h2>';
            }
            $array_template = return_all_template();
            if ($array_template)
            {
                foreach ($array_template as $v)
                {
                    $option_template .= '<option value="'.$v['id'].'">'.$v['name_template'].'</option>'."\n";
                }
            }
            $id_online_lang = $tmp_online_lang;
            $info_category = return_info_category($_GET['add_translate']);
            $all_category = return_option_category ('0', '', '', $info_category['parent_id']);
            $id_online_lang = $_GET['id_online_lang'];
            $lang_category_name = mysql_fetch_array(mysql_query("select `text` from `ls_translate` where `type` = 'news_category_n' and `id_elements` = '".$_GET['add_translate']."' and `id_lang` = '".$tmp_online_lang."';"), MYSQL_ASSOC);
            $lang_category_title = mysql_fetch_array(mysql_query("select `text` from `ls_translate` where `type` = 'news_category_t' and `id_elements` = '".$_GET['add_translate']."' and `id_lang` = '".$tmp_online_lang."';"), MYSQL_ASSOC);
            $lang_category_description = mysql_fetch_array(mysql_query("select `text` from `ls_translate` where `type` = 'news_category_d' and `id_elements` = '".$_GET['add_translate']."' and `id_lang` = '".$tmp_online_lang."';"), MYSQL_ASSOC);
            $lang_category_keyword = mysql_fetch_array(mysql_query("select `text` from `ls_translate` where `type` = 'news_category_k' and `id_elements` = '".$_GET['add_translate']."' and `id_lang` = '".$tmp_online_lang."';"), MYSQL_ASSOC);
            if ($info_category['only_short_news'])
            {
                $checked_only_short_news = 'checked';
            }
            switch ($info_category['sort'])
            {
                case "date";
                    $sort_date = 'selected';
                break;
                case "rating";
                    $sort_rating = 'selected';
                break;
                case "viewer";
                    $sort_viewer = 'selected';
                break;
                case "alphabet";
                    $sort_alphabet = 'selected';
                break;
            }
            switch ($info_category['sort_type'])
            {
                case "ASK":
                    $sort_type_ASK = 'selected';
                break;
                case "DESC":
                    $sort_type_DESC = 'selected';
                break;
            }
            $array_template = return_all_template();
            if ($array_template)
            {
                foreach ($array_template as $v)
                {
                    if ($info_category['template_short_news']==$v['id'])
                    {
                        $option_template_short .= '<option value="'.$v['id'].'" selected>'.$v['name_template'].'</option>'."\n";
                    } else {
                        $option_template_short .= '<option value="'.$v['id'].'">'.$v['name_template'].'</option>'."\n";
                    }
                }
            }
            if ($array_template)
            {
                foreach ($array_template as $v)
                {
                    if ($info_category['template_full_news']==$v['id'])
                    {
                        $option_template_full .= '<option value="'.$v['id'].'" selected>'.$v['name_template'].'</option>'."\n";
                    } else {
                        $option_template_full .= '<option value="'.$v['id'].'">'.$v['name_template'].'</option>'."\n";
                    }
                }
            }
            $body_admin .= '
            <div align="center">
            <h1>'.$lang[419].'</h1>
            <small style="color:red;">'.$lang[389].'</small>
            <form action="" method="POST">
                <table border="0" style="width:600px;">
                    <tr>
                        <td width="180">'.$lang[390].':</td>
                        <td><b>'.$info_for_my_lang_category['name'].'</b></td>
                    </tr>
                    <tr>
                        <td>'.$lang[391].':</td>
                        <td><input type="text" name="name_category" class="form_text" value="'.$lang_category_name['text'].'"></td>
                    </tr>
                    <tr>
                        <td>'.$lang[410].':</td>
                        <td><input type="text" name="number_on_page" class="form_text" value="'.$info_category['number_on_page'].'"></td>
                    </tr>
                    <tr>
                        <td>'.$lang[409].':</td>
                        <td>
                            <select name="parent_id" class="form_text">
                            <option value="0">'.$lang[411].'</option>
                            '.$all_category.'
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>'.$lang[392].':</td>
                        <td><input type="text" name="title" class="form_text" value="'.$lang_category_title['text'].'"></td>
                    </tr>
                    <tr>
                        <td>'.$lang[393].':</td>
                        <td><textarea name="description_category" class="form_text" rows="10" cols="40">'.$lang_category_description['text'].'</textarea></td>
                    </tr>
                    <tr>
                        <td>'.$lang[394].':</td>
                        <td><textarea name="keywords_category" class="form_text" rows="5" cols="40">'.$lang_category_keyword['text'].'</textarea></td>
                    </tr>
                    <tr>
                        <td>'.$lang[395].':</td>
                        <td>
                            <select name="sort" class="form_text">
                                <option value="date" '.$sort_date.'>'.$lang[396].'</option>
                                <option value="rating" '.$sort_rating.'>'.$lang[397].'</option>
                                <option value="viewer" '.$sort_viewer.'>'.$lang[398].'</option>
                                <option value="alphabet" '.$sort_alphabet.'>'.$lang[399].'</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>'.$lang[400].':</td>
                        <td>
                            <select name="sort_type" class="form_text">
                                <option value="ASK" '.$sort_type_ASK.'>'.$lang[401].'</option>
                                <option value="DESC" '.$sort_type_DESC.'>'.$lang[402].'</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>'.$lang[403].':</td>
                        <td>
                            <select name="template_short_news" class="form_text">
                                '.$option_template_short.'
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>'.$lang[404].':< /td>
                        <td>
                            <select name="template_full_news" class="form_text">
                                '.$option_template_full.'
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><b>'.$lang[405].'</b><span style="color:red">*</span>:</td>
                        <td>
                            <input type="checkbox" name="only_short_news" value="1" class="form_text" '.$checked_only_short_news.'>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center"><input type="submit" name="submit" value="'.$lang[421].'" class="form_text"></td>
                    </tr>
                </table>
                </form>
                <span style="color:red">*</span> '.$lang[407].'
            </div>
            ';
        } else {
            if (isset ($_GET['del']))
            {
                $sql = "delete from `ls_news_category` where `id` = '".$_GET['del']."';";
                if (mysql_query($sql))
                {
                    $sql = "delete from `ls_translate` where `type` = 'news_category_n' and `id_elements` = '".$_GET['del']."';";
                    mysql_query($sql);
                    $sql = "delete from `ls_translate` where `type` = 'news_category_t' and `id_elements` = '".$_GET['del']."';";
                    mysql_query($sql);
                    $sql = "delete from `ls_translate` where `type` = 'news_category_d' and `id_elements` = '".$_GET['del']."';";
                    mysql_query($sql);
                    $sql = "delete from `ls_translate` where `type` = 'news_category_k' and `id_elements` = '".$_GET['del']."';";
                    mysql_query($sql);
                    $body_admin .= '<h2 style="color:red;">'.$lang[418].'</h2>';
                }
            }
            $all_category = return_html_category ('0', '', '');
            $body_admin .= '<h1>'.$lang[423].'</h1>';
                $body_admin .= '<a href="index.php?do=news&action=category&add" class="link_add_category"><img src="'.$config ['site_url'].'images/admin/edit_add.png"> Добавить категорию</a><br>';   
            if ($all_category)
            {
                $array_lang = return_all_ok_lang();
                if ($array_lang)
                {
                    foreach ($array_lang as $one_lang)
                    {
                        $lang_html .= '<th width="50" align="center"><img src="'.$config ['site_url'].'images/languages/icon/'.$one_lang['alt_name'].'.png"></th>'."\r\n";
                    }
                }
                $body_admin .= '
                <table cellspacing="1" width="100%"> 
                             <tr>
                                <th width="15">
                                    <b>ID</b>
                                </th>
                                <th>'.$lang[412].'</th>
                                '.$lang_html.'
                                <th width="50">'.$lang[413].'</th>
                                <th width="50">'.$lang[414].'</th>
                             </tr>
                    '.$all_category.'
                </table>
                ';
                unset ($lang_html);
            } else {
                $body_admin .= '
                <h2 style="color:red;">'.$lang[502].'</h2>
                ';
            }
        }
    }
}
$id_online_lang = $tmp_online_lang;
?>