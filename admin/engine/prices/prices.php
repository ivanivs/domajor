<?php
if (isset ($_POST['card']))
{
    $body_admin .= '
    <h2>'.$lang[558].'</h2>
    ';
    $sql = "SELECT * FROM `ls_items` where `id_card` = '".$_POST['card']."' and `select_4` = '".intval($_POST['stock'])."';";
    $results = mysql_query($sql); 
    $number = @mysql_num_rows ($results);
    for ($i=0; $i<$number; $i++)
    {	
        $array_items[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
    }
    $i=0;
    $j=0;
    if ($number)
    {
        $body_admin .= 'Найдено '.$number.' товаров<br>';
        $koef = ($_POST['nacenka']/100)+1;
        foreach ($array_items as $v)
        {
            $newPrice = ceil($v['price_'.intval($_POST['one_price'])]*$koef);
            mysql_query("UPDATE `ls_items` SET `price_".intval($_POST['one_price'])."` = '".$newPrice."' WHERE `id` = '".$v['id']."';");
//            echo $v['id'].' - '.$v['price_'.intval($_POST['one_price'])].' - '.ceil($newPrice).'<br>';
//            exit();
        }
        $body_admin .= 'Оновлено цен:'.count($array_items);
    }
} else {
    $body_admin .= '
    <h2>Произвести наценку</h2>
    <div style="color:red;">Внимание!!! Модуль не поддерживает карточки товаров с зависимостю цены от параметров товара.</div>
    ';
    $sql = "SELECT * FROM `ls_card`;";
    $results = mysql_query($sql);
    $number = @mysql_num_rows ($results);
    for ($i=0; $i<$number; $i++)
    {	
        $array_card[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
    }
    /*$sql = "SELECT `ls_cardparam`.*, `ls_card`.`name` as `cardName` FROM `ls_cardparam` JOIN `ls_card` ON `ls_card`.`id` = `ls_cardparam`.`id_card` where `ls_cardparam`.`db_type` = 'price';";
    $results = mysql_query($sql);
    $number_price = @mysql_num_rows ($results);
    for ($i=0; $i<$number_price; $i++)
    {	
        $array_price[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
    }
    if ($number_price)
    {
        foreach ($array_price as $v)
        {
            $tr_price = mysql_fetch_array(mysql_query("SELECT `text` FROM `ls_translate` WHERE `type` = 'price' and `id_elements` = '".$v['id_param']."' and `id_lang` = '".$id_online_lang."';"), MYSQL_ASSOC);
            $option_price .= '<option value="'.$v['id'].'">'.$tr_price['text'].' - '.$v['cardName'].'</option>';
        }
    }*/
    if ($arrayPrice = getArray("SELECT `ls_params_price`.`id`, `ls_translate`.`text` FROM `ls_params_price` JOIN `ls_translate` ON `ls_translate`.`id_elements` = `ls_params_price`.`id` WHERE `ls_translate`.`type` = 'price';")){
        foreach ($arrayPrice as $v){
            $option_price .= '<option value="'.$v['id'].'">'.$v['text'].'</option>';
        }
    }
    $sql = "SELECT * FROM `ls_cardparam` where `db_type` = 'text';";
    $results = mysql_query($sql);
    $number_text = @mysql_num_rows ($results);
    for ($i=0; $i<$number_text; $i++)
    {	
        $array_text[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
    }
    if ($number_text)
    {
        foreach ($array_text as $v)
        {
            $tr_text = mysql_fetch_array(mysql_query("SELECT `text` FROM `ls_translate` WHERE `type` = 'text' and `id_elements` = '".$v['id_param']."' and `id_lang` = '".$id_online_lang."';"), MYSQL_ASSOC);
            $option_text .= '<option value="'.$v['id'].'">'.$tr_text['text'].'</option>';   
        }
    }
    if ($arrayStock = getValuesSelectParam(4)){
        foreach ($arrayStock as $v){
            $optionStock .= '<option value="'.$v['id'].'">'.$v['text'].'</option>';
        }
    }
    if ($number)
    {
        foreach ($array_card as $v)
        {
            $option_card .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
        }
        $body_admin .= '
        <form action="" method="POST">
            <table border="0">
                <tr>
                    <td>Товары по карточке:</td>
                    <td><select name="card">'.$option_card.'</select></td>
                </tr>
                <tr>
                    <td>Наличие:</td>
                    <td><select name="stock">'.$optionStock.'</select></td>
                </tr>
                <tr>
                    <td>Цена:</td>
                    <td><select name="one_price">'.$option_price.'</select></td>
                </tr>
                <tr>
                    <td>Наценка в процентах:</td>
                    <td><input type="text" name="nacenka" style="border:1px solid black;" placeholder="10 - для наценки 10 процентов"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="submit" name="submit" value="Произвести переоценку"></td>
                </tr>
            </table>
        </form>
        ';
    }
}
$body_admin = '
<div id="info_admin_page">
<img src="'.$config ['site_url'].'images/admin/rightarrow.png"> '.$lang[557].$other_way.'
</div>
'.$body_admin;
?>