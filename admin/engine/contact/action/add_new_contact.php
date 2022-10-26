<?php
$other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[643];
if (isset ($_POST['name_cart']) and strlen($_POST['name_cart']))
{
    $sql = "INSERT INTO `ls_contact_card` (`feedback`, `name_card`) VALUES
            ('".$_POST['feedback']."', '".mysql_escape_string($_POST['name_cart'])."');";
    if (mysql_query($sql))
    {
        $id = mysql_insert_id();
        $sql = "
            INSERT INTO `ls_translate` (`type`, `id_lang`, `id_elements`, `text`) VALUES
            ('contact_card_te', ".$id_online_lang.", ".$id.", '".mysql_escape_string($_POST['block_contact'])."');
        ";
        if (mysql_query($sql))
        {
            $body_admin .= '<div style="font-size:18px; color:green;">'.$lang[644].'</div>';
        } else {
            $body_admin .= '<div style="font-size:18px; color:green;">'.$lang[609].'</div>';
        }
    }
} else {
    $body_admin .= '
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
                width: "100%",
                height: "400px",
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
    <h2>'.$lang[610].'</h2>
    <form action="" method="POST">
        <table border="0" class="new_table" style="width:100%">
            <tr>
                <td align="left" style="width:250px;">'.$lang[611].':</td>
                <td align="left"><input type="text" name="name_cart"></td>
            </tr>
            <tr>
                <td align="left" style="width:250px;">'.$lang[612].'</td>
                <td align="left">
                    <select name="feedback">
                        <option value="1">'.$lang[613].'</option>
                        <option value="0">'.$lang[614].'</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><b style="font-size:18px;">'.$lang[615].'</b></td>
            </tr>
            <tr>
                <td align="center" colspan="2"><textarea name="block_contact" class="mceEditor"></textarea><td>
            </tr>
            <tr>
                <td colspan="2" align="center" style="border-bottom:0px;"><input type="submit" name="submit" value="'.$lang[616].'"></td>
            </tr>
        </table>
    </form>
    ';
}
?>