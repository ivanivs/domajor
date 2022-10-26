<?php
if (isset ($_GET['del']))
{
    mysql_query("DELETE FROM `ls_mail_out` WHERE `id` = '".intval($_GET['del'])."';");
}
if (!isset ($_GET['id_payment']))
{
    $array_mail = return_mail_out_by_type($type_view);
    if ($array_mail)
    {
        $body_admin .= '
        <script>
            function viewAllThisMail(id)
            {
                $( "#blockFullMail_" + id ).dialog({
                    autoOpen: false,
                    show: "blind",
                    hide: "explode"
                });
                $("#blockFullMail_" + id).dialog("open");
            }
        </script>
        <table width="100%">
        <tr>
            <th width="20" align="center">ID</th>
            <th width="300" align="center">Адреса одержувача</th>
            <th align="center">Тема</th>
            <th width="170" align="center">Час</th>
            <th></th>
        </tr>
        ';
        foreach ($array_mail as $v)
        {
            $body_admin .= '<tr id="mail_'.$v['id'].'" style="cursor:pointer;" onclick="viewAllThisMail(\''.$v['id'].'\');">
            <td>'.$v['id'].'
            <div style="display:none;" id="blockFullMail_'.$v['id'].'" title="'.$v['subject'].'">
                <div style="font-size:16px; font-weight:bold;">Зміст листа #'.$v['id'].'</div>
                <div style="text-align:left;">'.$v['body_mail'].'</div>
            </div></td>
            <td>'.$v['to'].'</td>
            <td>'.$v['subject'].'</td>
            <td>'.$v['time'].'</td>
            <td align="center"><a href="'.$config['site_url'].$_SERVER['REQUEST_URI'].'&del='.$v['id'].'" onclick="return confirm(\'Подтвердите удаление...\');"><img src="'.$config['site_url'].'images/admin/remove_16.png"></a></td>
            </tr>';
        }
        $body_admin .= '</table>';
    }
} else {
    
}
?>