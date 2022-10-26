<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 3/6/13
 * Time: 7:58 PM
 * To change this template use File | Settings | File Templates.
 */
$body_admin = '
<a href="index.php?do=users&action=add"><img src="'.$config ['site_url'].'images/admin/add_16.png"> Добавить пользователя</a>
';
if (isset ($_GET['action']))
{
    switch ($_GET['action'])
    {
        case "add":
            require ('engine/users/actions/add.php');
            break;
        case "edit":
            require ('engine/users/actions/add.php');
            break;
    }
} else {
    if (isset ($_GET['noaction']) and $_GET['noaction'] == 'del')
    {
        if ($_GET['id']!=1 and $_GET['id']!=2)
        {
            mysql_query("DELETE FROM `ls_users` where `id` = '".$_GET['id']."';");
        }
    }
    $sql = "SELECT * FROM ls_users order by `id` DESC;";
    $results = mysql_query($sql);
    $number = @mysql_num_rows ($results);
    if ($number>0)
    {
        for ($i=0; $i<$number; $i++)
        {
            $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);
        }
        $body_admin .= '
        <table cellspacing="1" class="tablesorter" width="100%">
		 <thead>
		    <tr>
		        <th width="25">ID</th>
		        <th>Логин</th>
		        <th width="150">Дата регистрации</th>
		        <th width="25">Доступ</th>
		        <th width="25"></th>
		        <th width="25"></th>
		    </tr>
		    </thead>
		  <tbody>
        ';
        foreach ($array as $v)
        {
            $body_admin .= '
            <tr>
                <td>'.$v['id'].'</td>
                <td>'.$v['login'].'</td>
                <td>'.$v['time_reg'].'</td>
                <td>'.$v['accesslevel'].'</td>
                <td style="text-align:center;"><a href="index.php?do=users&action=edit&id='.$v['id'].'"><img src="'.$config ['site_url'].'images/admin/edit.png"></a></td>
                <td style="text-align:center;"><a href="index.php?do=users&noaction=del&id='.$v['id'].'" onclick="return confirm(\'Подтвердите удаление?\');"><img src="'.$config ['site_url'].'images/admin/remove_16.png"></a></td>
            </tr>
            ';
        }
        $body_admin .= '</tbody></table>';
    }
    $body_admin .= '';
}
$body_admin = '
<div id="info_admin_page">
<img src="'.$config ['site_url'].'images/admin/rightarrow.png">Управление пользователями'.$other_way.'
</div>
'.$body_admin;