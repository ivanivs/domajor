<?php
if (isset ($_POST['name_news']))
{
    $sql = "
    INSERT INTO  `ls_news` (
    `id_category` ,
    `time`
    )
    VALUES (
    '".mysql_real_escape_string($_POST['category_id'])."' ,
    '".time()."'
    );
    ";
    if (mysql_query($sql))
    {
        $id_news = mysql_insert_id();
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
        '".mysql_real_escape_string($_POST['name_news'])."'
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
        '".mysql_real_escape_string($_POST['keyword_news'])."'
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
        '".mysql_real_escape_string($_POST['description'])."'
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
        '".mysql_real_escape_string($_POST['short_news'])."'
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
        '".mysql_real_escape_string($_POST['full_news'])."'
        );";
        mysql_query($sql);
        $body_admin .= '<h2 style="color:green;">'.$lang[435].'</h2><br>
        <a href="index.php?do=news&action=add_news">'.$lang[436].'</a>
        ';
    } else {
        $body_admin .= '<h2 style="color:red;">'.$lang[437].'</h2>';
    }
} else {
    $info_for_my_lang_category = mysql_fetch_array(mysql_query("select * from `ls_lang` where `id` = '".$id_online_lang."';"), MYSQL_ASSOC);
    $all_category = return_option_category ('0', '', '');
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
    <h1 align="center">'.$lang[424].'</h1>
    <div align="center">
        <form action="" method="POST">
            <table border="0">
                <tr>
                    <td>'.$lang[438].':</td>
                    <td><b>'.$info_for_my_lang_category['name'].'</b></td>
                </tr>
                <tr>
                    <td>'.$lang[439].':</td>
                    <td><input type="text" name="name_news" class="form_text"></td>
                </tr>
                <tr>
                    <td>'.$lang[440].':</td>
                    <td><input type="text" name="keyword_news" class="form_text"></td>
                </tr>
                <tr>
                    <td>'.$lang[441].':</td>
                    <td><textarea name="description" rows="3" cols="50" class="form_text"></textarea></td>
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
                        <textarea name="short_news" id="short_news" class="mceEditor"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>'.$lang[444].':</td>
                    <td>
                        <textarea name="full_news" id="full_news" class="mceEditor"></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="submit" name="submit" value="'.$lang[445].'" class="form_text" style="width:200px"></td>
                </tr>
            </table>
        </form>
    </div>
    ';
}
?>