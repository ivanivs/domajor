<?php
$body_admin .= '
<div id="loading_new_content" style="display: none">  
    <center><img src="'.$config ['site_url'].'images/admin/ajax-loader.gif"></center> 
</div> 
<script>
    function save_lang_file(file) {
        var cont = document.getElementById(\'result_saves\' + file);
        var loading_new_content = document.getElementById(\'loading_new_content\');
        var text = document.getElementById(\'langsfile_\' + file).value;
        cont.innerHTML = loading_new_content.innerHTML;  
        var link = \'save_lang_file.php\';
        var query = \'file=\' + file + \'&text=\' + text;
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
function createRequestObject()   
    {  
        try { return new XMLHttpRequest() }  
        catch(e)   
        {  
            try { return new ActiveXObject(\'Msxml2.XMLHTTP\') }  
            catch(e)   
            {  
                try { return new ActiveXObject(\'Microsoft.XMLHTTP\') }  
                catch(e) { return null; }  
            }  
        }  
    }  
</script>';
if (!isset ($_GET['action']))
{
    $array_lang_file = scandir('languages');
    $body_admin .= '<h2 id="title">'.$lang[300].'</h2>';
    foreach ($array_lang_file as $v)
    {
        if ($v!='.' and $v!='..')
        {
            $body_admin .= '<a href="index.php?do=languages_file&action=show_file">'.str_replace ('.php', '', $v)."</a><br>";
        }
    }
} else {
    switch ($_GET['action'])
    {
        case "show_file":
            $array_lang_file = scandir('languages');
            $body_admin .= '
            <table border="0"><tr>';
            $number_file = count($array_lang_file);
            $cols = (150/($number_file-2));
            foreach ($array_lang_file as $key => $v)
            {
                if ($v!='.' and $v!='..')
                {
                    $file = file_get_contents('languages/'.$v);
                    if (!isset ($rows))
                    {
                        $rows = substr_count($file, '$lang');
                    }
                    $body_admin .= '
                    <td valign="top">
                        <div id="result_saves'.$v.'"></div>
                        <img src="'.$config ['site_url'].'images/admin/filesaveas.png" onclick="save_lang_file(\''.$v.'\')">
                        <br>
                        <textarea name="langsfile_'.$v.'" id="langsfile_'.$v.'" rows="'.$rows.'" cols="'.$cols.'">'.$file.'</textarea>
                        <br>
                        <img src="'.$config ['site_url'].'images/admin/filesaveas.png" onclick="save_lang_file(\''.$v.'\')">
                    </td>';
                }
            }
            $body_admin .= '</tr></table>';
        break;
    }
}
$body_admin = '
<div id="info_admin_page">
<img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[299].$other_way.'
</div>
'.$body_admin;
?>