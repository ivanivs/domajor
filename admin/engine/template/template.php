<?php
require ('engine/params/functions.php');
if (isset ($_POST['id_template']))
{
    $sql = "
    update ls_template set name_template = '".$_POST['name_template']."', template = '".$_POST['template']."' where id = '".$_POST['id_template']."';
    ";
    if (mysql_query($sql))
    {
        $status_sql =  '<span style="color:green">'.$lang[355].'</span>';
    } else {
        $status_sql =  '<span style="color:red">'.$lang[356].'</span>';
    }
}
$body_admin .= '
<div id="main_template">'.$status_sql.'<br>';
    $array_template = return_all_template();
    if ($array_template)
    {
        $body_admin .= '
        <table cellspacing="1" class="table table-bordered table-striped" width="100%">
        <thead>
        <tr> 
               <th width="25" align="center">'.$lang[27].'</th> 
               <th>'.$lang[348].'</th>
               <th width="100">'.$lang[349].'</th> 
         </tr> 
        </thead> 
        <tbody>';
        foreach ($array_template as $v)
        {
            $body_admin .= '<tr>
            <td>'.$v['id'].'</td>
            <td>'.$v['name_template'].'</td>
            <td align="center"><img src="'.$config ['site_url'].'images/admin/edit.png" onclick="edit_template(\''.$v['id'].'\')"></td>
            </tr>';
        }
        $body_admin .= '</tbody></table>';
    } else {
        $body_admin .= '<h2 class="title">'.$lang[347].'</h2>';
    }
$body_admin = '
<div id="info_admin_page">
<img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[346].$other_way.'
</div>
'.$body_admin.'<br><br><a href="#" onclick="add_template();">'.$lang[350].'</a>';
?>