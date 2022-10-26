<?php
if (isset ($_POST['name_news']))
{
    if (isset ($_POST['no_translate']) and $_POST['no_translate']==1)
    {
        //select `text` from `ls_translate` where `type` = 'news_name' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".$_GET['id']."';"
        $sql = "
        update `ls_translate` set `text` = '".$_POST['name_news']."' where `id_elements` = '".$_GET['id']."' and `id_lang` = '".$_GET['id_online_lang']."' and `type` = 'news_name';
        ";
        mysql_query($sql);
        $sql = "
        update `ls_translate` set `text` = '".$_POST['keyword_news']."' where `id_elements` = '".$_GET['id']."' and `id_lang` = '".$_GET['id_online_lang']."' and `type` = 'news_key';
        ";
        mysql_query($sql);
        $sql = "
        update `ls_translate` set `text` = '".$_POST['description']."' where `id_elements` = '".$_GET['id']."' and `id_lang` = '".$_GET['id_online_lang']."' and `type` = 'news_descriptio';
        ";
        mysql_query($sql);
        $sql = "
        update `ls_translate` set `text` = '".$_POST['short_news']."' where `id_elements` = '".$_GET['id']."' and `id_lang` = '".$_GET['id_online_lang']."' and `type` = 'news_short';
        ";
        mysql_query($sql);
        $sql = "
        update `ls_translate` set `text` = '".$_POST['full_news']."' where `id_elements` = '".$_GET['id']."' and `id_lang` = '".$_GET['id_online_lang']."' and `type` = 'news_full';
        ";
        mysql_query($sql);
        $sql = "
        update `ls_news` set `id_category` = '".$_POST['category_id']."' where `id` = '".$_GET['id']."';
        ";
        mysql_query($sql);
        $body_admin .= '<h2 style="color:green;">'.$lang[448].'</h2>';
    } else {
        $sql = "
        update `ls_news` set `id_category` = '".$_POST['category_id']."' where `id` = '".$_GET['id']."';
        ";
        if (mysql_query($sql))
        {
            $id_news = $_GET['id'];
            $tmp_lang = $id_online_lang;
            $id_online_lang = $_GET['id_online_lang'];
            $sql = "INSERT INTO  `ls_translate` (
            `type` ,
            `id_lang` ,
            `id_elements` ,
            `text`
            )
            VALUES (
            'news_name',
            '".$id_online_lang."' ,
            '".$id_news."' ,
            '".$_POST['name_news']."'
            );";
            mysql_query($sql);
            $sql = "INSERT INTO  `ls_translate` (
            `type` ,
            `id_lang` ,
            `id_elements` ,
            `text`
            )
            VALUES (
            'news_key',
            '".$id_online_lang."' ,
            '".$id_news."' ,
            '".$_POST['keyword_news']."'
            );";
            mysql_query($sql);
            $sql = "INSERT INTO  `ls_translate` (
            `type` ,
            `id_lang` ,
            `id_elements` ,
            `text`
            )
            VALUES (
            'news_description',
            '".$id_online_lang."' ,
            '".$id_news."' ,
            '".$_POST['description']."'
            );";
            mysql_query($sql);
            $sql = "INSERT INTO  `ls_translate` (
            `type` ,
            `id_lang` ,
            `id_elements` ,
            `text`
            )
            VALUES (
            'news_short',
            '".$id_online_lang."' ,
            '".$id_news."' ,
            '".$_POST['short_news']."'
            );";
            mysql_query($sql);
            $sql = "INSERT INTO  `ls_translate` (
            `type` ,
            `id_lang` ,
            `id_elements` ,
            `text`
            )
            VALUES (
            'news_full',
            '".$id_online_lang."' ,
            '".$id_news."' ,
            '".$_POST['full_news']."'
            );";
            mysql_query($sql);
            $body_admin .= '<h2 style="color:green;">'.$lang[448].'</h2>';
        }  else {
            $body_admin .= '<h2 style="color:red;">'.$lang[449].'</h2>';
        }
        $id_online_lang = $tmp_lang;
    }
} else {
    $tmp_lang = $id_online_lang;
    $id_online_lang = $_GET['id_online_lang'];
    $info_news = mysql_fetch_array(mysql_query("select * from `ls_news` where `id` = '".$_GET['id']."';"), MYSQL_ASSOC);
    $info_for_my_lang_category = mysql_fetch_array(mysql_query("select * from `ls_lang` where `id` = '".$id_online_lang."';"), MYSQL_ASSOC);
    $all_category = return_option_category ('0', '', '', $info_news['id_category']);
    $results = mysql_query("select `text` from `ls_translate` where `type` = 'news_name' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".$_GET['id']."';");
    $name_news = mysql_fetch_array($results, MYSQL_ASSOC);
    $number_news = mysql_num_rows ($results);
    if ($number_news)
    {
        $no_translate = '<input type="hidden" name="no_translate" value="1">';
    }
    $keyword_news = mysql_fetch_array(mysql_query("select `text` from `ls_translate` where `type` = 'news_key' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".$_GET['id']."';"), MYSQL_ASSOC);
    $news_descriptio = mysql_fetch_array(mysql_query("select `text` from `ls_translate` where `type` = 'news_descriptio' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".$_GET['id']."';"), MYSQL_ASSOC);
    $short_news = mysql_fetch_array(mysql_query("select `text` from `ls_translate` where `type` = 'news_short' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".$_GET['id']."';"), MYSQL_ASSOC);
    $news_full = mysql_fetch_array(mysql_query("select `text` from `ls_translate` where `type` = 'news_full' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".$_GET['id']."';"), MYSQL_ASSOC);
    $body_admin .= '
    <script>
        $(document).ready(function() {
          $(\'#short_news\').summernote({
                  height: 600,
                  focus: true ,
                  onImageUpload: function(files, editor, welEditable) {
                    sendFile(files[0],editor,welEditable);
                   }
                });
          $(\'#full_news\').summernote({
                  height: 600,
                  focus: true ,
                  onImageUpload: function(files, editor, welEditable) {
                    sendFile(files[0],editor,welEditable);
                   }
                });
        });
    </script>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" />
    <h1 align="center">'.$lang[446].'</h1>
    <div align="center">
        <form action="" method="POST">
        '.$no_translate.'
            <table border="0">
                <tr>
                    <td>'.$lang[438].':</td>
                    <td><b>'.$info_for_my_lang_category['name'].'</b></td>
                </tr>
                <tr>
                    <td>'.$lang[439].':</td>
                    <td><input type="text" name="name_news" value="'.$name_news['text'].'" class="form_text"></td>
                </tr>
                <tr>
                    <td>'.$lang[440].':</td>
                    <td><input type="text" name="keyword_news" value="'.$keyword_news['text'].'" class="form_text"></td>
                </tr>
                <tr>
                    <td>'.$lang[441].':</td>
                    <td><textarea name="description" rows="3" cols="50" class="form_text">'.$news_descriptio['text'].'</textarea></td>
                </tr>
                <tr>
                    <td>'.$lang[442].':</td>
                    <td><select name="category_id" class="form_text">
                        <option value="0">'.$lang[411].'</option>
                        '.$all_category.'
                        </select></td>
                </tr>
                <tr>
                    <td>'.$lang[443].':</td>
                    <td>
                        <textarea name="short_news" id="short_news" class="mceEditor">'.$short_news['text'].'</textarea>
                    </td>
                </tr>
                <tr>
                    <td>'.$lang[444].':</td>
                    <td>
                        <textarea name="full_news" id="full_news" class="mceEditor">'.$news_full['text'].'</textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="submit" name="submit" value="'.$lang[445].'" class="form_text" style="width:200px"></td>
                </tr>
            </table>
        </form>
    </div>
    ';
    $id_online_lang = $tmp_lang;
}
?>