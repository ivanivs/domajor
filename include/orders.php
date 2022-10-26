<?php
if (isset ($_COOKIE['id_user_online']))
{
    $sql = "SELECT * FROM `ls_orders` where `id_user` = '".intval($_COOKIE['id_user_online'])."' order by `id` DESC;";
    $results = mysql_query($sql);
    $number = @mysql_num_rows ($results);
    if ($number>0)
    {
        for ($i=0; $i<$number; $i++)
        {
            $arrayOrders[$i] = mysql_fetch_array($results, MYSQL_ASSOC);
        }
        $body .= '
        <div class="headerBodyRotator">
            <h2>Ваши заказы</h2>
        </div>
        <table border="0" width="100%">
        <tr>
            <td align="center" style="width:25px; background-color:#F4F4F4; font-weight:bold;">ID</td>
            <td align="center" style="background-color:#F4F4F4; font-weight:bold;">Номер телефона</td>
            <td align="center" style="background-color:#F4F4F4; font-weight:bold;">Ф.И.О.</td>
            <td align="center" style="background-color:#F4F4F4; font-weight:bold;">Стоимость</td>
            <td align="center" style="background-color:#F4F4F4; font-weight:bold;">Товары</td>
            <td align="center" style="background-color:#F4F4F4; font-weight:bold;">Статус</td>
            <td align="center" style="background-color:#F4F4F4; font-weight:bold;">Дата заказа</td>
        </tr>
        ';
        foreach ($arrayOrders as $v)
        {
            $sql = "SELECT * FROM `ls_cart` where `uniq_user` = '".$v['uniq_user']."';";
            $results = mysql_query($sql);
            $number = @mysql_num_rows ($results);
            $allPrice = 0;
            if ($number>0)
            {
                for ($i=0; $i<$number; $i++)
                {
                    $arrayItems[$i] = mysql_fetch_array($results, MYSQL_ASSOC);
                }
                foreach ($arrayItems as $oneItem)
                {
                    $infoItem = mysql_fetch_array (mysql_query("SELECT * FROM `ls_items` where `id` = '".$oneItem['id_item']."';"), MYSQL_ASSOC);
                    if ($infoItem['price_2']!=0){
                        $allPrice += $infoItem['price_2']*$oneItem['number'];
                    } else {
                        $allPrice += $infoItem['price_1']*$oneItem['number'];
                    }
                }
            }
            switch ($v['status'])
            {
                case 0:
                        $status = '<span style="color:green;">Новый заказ</span>';
                    break;
                case 1:
                        $status = '<span style="color:red;">Подтвержден</span>';
                    break;
                case 2:
                        $status = '<span style="color:green;"><div>Отослан</div><div>Номер декларации: '.$v['number_declaration'].'</div></span>';
                    break;
                case 3:
                        $status = '<span style="color:green; font-weight: bold;">Исполнен</span>';
                    break;
            }
            $body .= '
            <tr>
                <td align="center" style="width:25px;">'.$v['id'].'</td>
                <td align="center">'.$v['number_phone'].'</td>
                <td align="center">'.$v['pib'].'</td>
                <td align="center">'.$allPrice.' грн.</td>
                <td align="center"><a href="'.$config ['site_url'].'status.php?uniq='.$v['uniq_user'].'" target="_blank">смотреть</a></td>
                <td align="center">'.$status.'</td>
                <td align="center">'.date('d.m.Y H:i', $v['time']).'</td>
            </tr>
            ';
            unset ($allPrice, $arrayItems);
        }
        $body .= '</table>
        ';
    } else {
        $body .= '
        <div class="headerBodyRotator">
            <h2>У Вас еще нет заказов</h2>
        </div>
        ';
    }
} else {
    $body .= '<h2>Ошибка доступа</h2>';
}
?>