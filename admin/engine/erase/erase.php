<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 2/1/13
 * Time: 12:01 PM
 * To change this template use File | Settings | File Templates.
 */

if (!isset ($_GET['eraseGo']))
{
    $body_admin = '<div style="text-align: center;"><a href="index.php?do=erase&eraseGo=1" class="buttonRed">Очистить базу полностью</a></div>';
    $styleOSMS .= "
        .buttonRed {
            background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #fc0000), color-stop(1, #610000) );
            background:-moz-linear-gradient( center top, #fc0000 5%, #610000 100% );
            filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#fc0000', endColorstr='#610000');
            background-color:#fc0000;
            -moz-border-radius:6px;
            -webkit-border-radius:6px;
            border-radius:6px;
            border:1px solid #ffffff;
            display:inline-block;
            color:#ffffff;
            font-family:arial;
            font-size:15px;
            font-weight:bold;
            padding:6px 24px;
            text-decoration:none;
        }.buttonRed:hover {
            color: #FFFFFF;
            background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #610000), color-stop(1, #fc0000) );
                background:-moz-linear-gradient( center top, #610000 5%, #fc0000 100% );
            filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#610000', endColorstr='#fc0000');
            background-color:#610000;
        }.buttonRed:active {
            position:relative;
            top:1px;
        }
    ";
} else {
    $sql = "
    TRUNCATE `ls_card`;
    TRUNCATE `ls_cardparam`;
    TRUNCATE `ls_card_param_ligament`;
    TRUNCATE `ls_cart`;
    TRUNCATE `ls_contact_card`;
    TRUNCATE `ls_css`;
    TRUNCATE `ls_filtr`;
    TRUNCATE `ls_filtr_param`;
    TRUNCATE `ls_items`;
    TRUNCATE `ls_lang`;
    TRUNCATE `ls_mail_out`;
    TRUNCATE `ls_menu`;
    TRUNCATE `ls_news`;
    TRUNCATE `ls_news_category`;
    TRUNCATE `ls_orders`;
    TRUNCATE `ls_params_boolean`;
    TRUNCATE `ls_params_image`;
    TRUNCATE `ls_params_price`;
    TRUNCATE `ls_params_select`;
    TRUNCATE `ls_params_select_values`;
    TRUNCATE `ls_params_text`;
    TRUNCATE `ls_payment`;
    TRUNCATE `ls_reference`;
    TRUNCATE `ls_reference_values`;
    TRUNCATE `ls_reference_values_translate`;
    TRUNCATE `ls_static_pages`;
    TRUNCATE `ls_tinydeal`;
    TRUNCATE `ls_translate`;
    TRUNCATE `ls_values_boolean`;
    TRUNCATE `ls_values_image`;
    TRUNCATE `ls_values_ligament_price`;
    TRUNCATE `ls_values_ligament_price_param`;
    TRUNCATE `ls_values_prices`;
    TRUNCATE `ls_values_select`;
    TRUNCATE `ls_values_text`;
    DELETE FROM `ls_template` where `id` > 18;
    ";
    $arraySql = explode ("\n", $sql);
    foreach ($arraySql as $v)
    {
        //print trim($v)."<br>";
        if (strlen(trim($v))>0)
        {
            if (mysql_query(trim($v)))
            {
                $body_admin .= '<div style="color:green; font-weight: bold;">'.$v.' - Успешно исполнено</div>';
            } else {
                $body_admin .= '<div style="color:#FF0000; font-weight: bold;">'.$v.' - Ошибка исполнения</div>';
            }
        }
    }
}
$body_admin = '
<div id="info_admin_page">
<img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[661].$other_way.'
</div>
'.$body_admin;