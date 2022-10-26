<?php
if (isset ($_POST['name_payment']))
{
    $sql = "
    INSERT INTO  `ls_payment` (
    `id`
    )
    VALUES (
    NULL
    );
    ";
    if (mysql_query($sql))
    {
        $id_payment = mysql_insert_id();
        $sql = "INSERT INTO  `ls_translate` (
        `type` ,
        `id_lang` ,
        `id_elements` ,
        `text`
        )
        VALUES (
        'pay_name',
        '".$id_online_lang."' ,
        '".$id_payment."' ,
        '".$_POST['name_payment']."'
        );";
        mysql_query($sql);
        $sql = "INSERT INTO  `ls_translate` (
        `type` ,
        `id_lang` ,
        `id_elements` ,
        `text`
        )
        VALUES (
        'pay_template',
        '".$id_online_lang."' ,
        '".$id_payment."' ,
        '".$_POST['template']."'
        );";
        mysql_query($sql);
        $body_admin .= '<h2 style="color:green;">'.$lang[472].'</h2>';
    }
} else {
    $info_for_my_lang_category = mysql_fetch_array(mysql_query("select * from `ls_lang` where `id` = '".$id_online_lang."';"), MYSQL_ASSOC);
    $template = file_get_contents('engine/payment/action/offline/template_'.$id_online_lang.'.tpl');
    $body_admin .= '<h1>'.$lang[466].'</h1>
    <script type="text/javascript" src="../tiny_mce/tiny_mce.js"></script>
        <script type="text/javascript"> 
                tinyMCE.init({
                        // General options
                        mode : "specific_textareas",
                        editor_selector : "mceEditor",
                        theme : "advanced",
                        skin : "o2k7",
                        skin_variant : "silver",
                        language : "uk",
                        width : "800",
                        height : "400", 
                        plugins : "safari,pagebreak,style,layer,table,save,images,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
         
                        // Theme options
                        theme_advanced_buttons3 : "images,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,fullscreen",
                
                        extended_valid_elements : "iframe[title|width|height|src|frameborder|allowfullscreen], object[width|height|param|embed],param[name|value],embed[src|type|width|height]" ,
                        // Example content CSS (should be your site CSS)
                        content_css : "css/content.css",
         
                        // Drop lists for link/image/media/template dialogs
                        template_external_list_url : "lists/template_list.js",
                        external_link_list_url : "lists/link_list.js",
                        external_image_list_url : "lists/image_list.js",
                        media_external_list_url : "lists/media_list.js",
         
                        // Replace values for the template plugin
                        template_replace_values : {
                                username : "Some User",
                                staffid : "991234"
                        }
                });
        </script> 
    <form action="" method="POST">
    <table border="0">
        <tr>
            <td>'.$lang[467].'</td>
            <td><b>'.$info_for_my_lang_category['name'].'</b></td>
        </tr>
        <tr>
            <td>'.$lang[469].':</td>
            <td><input type="text" name="name_payment" class="form_text"><br>
            <span style="color:red; font-size:10px;">
            '.$lang[470].'
            </span>
            </td>
        </tr>
        <tr>
            <td>'.$lang[471].':</td>
            <td><textarea name="template" class="mceEditor">'.$template.'</textarea></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" name="submit" value="'.$lang[468].'" class="form_text"></td>
        </tr>
    </table>
    </form>
    ';
}
?>