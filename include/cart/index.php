<?php
$title = $lang[655];
if (isset ($_GET['del']))
{
    $infoCart = getOneString("SELECT * FROM `ls_cart` WHERE `id` = '".intval($_GET['del'])."'");
    $sql = "DELETE from `ls_cart` where `id` = '".intval($_GET['del'])."';";
    mysql_query ($sql);
    if ($arrayGiftFree = getArray("SELECT * FROM `ls_giftToItem` WHERE `itemId` = '".intval($infoCart['id_item'])."'")){
        foreach ($arrayGiftFree as $oneGiftFree){
            mysql_query("DELETE FROM `ls_cart` WHERE `uniq_user` = '".$infoCart['uniq_user']."' AND `id_item` = '".$oneGiftFree['giftId']."' AND `price` = '0.01'");
        }
    }
}
if (isset ($_GET['is_i'])){
    echo $_COOKIE['PHPSESSID'];
}
if (!isset ($_GET['go']))
{
    $uniq_id_in_base = $_COOKIE['PHPSESSID'];
    if ($arrayItemInCart = getArray("SELECT * FROM `ls_cart` where `uniq_user` = '".$uniq_id_in_base."' and status <> 1;")){
//        print_r ($arrayItemInCart);
        foreach ($arrayItemInCart as $v){
            if ($v['time']<(time()-259200)){
//                echo "remove item";
                mysql_query("DELETE FROM `ls_cart` WHERE `id` = '".$v['id']."'");
            }
        }
    }
    if (isset ($_GET['erase'])){
        mysql_query("DELETE FROM `ls_cart` WHERE `uniq_user` = '".$uniq_id_in_base."' and status <> 1;");
    }
    $sql = "SELECT * FROM `ls_cart` where `uniq_user` = '".$uniq_id_in_base."' and status <> 1;";
    $results = mysql_query($sql);
    $number = mysql_num_rows ($results);
    for ($i=0; $i<$number; $i++)
    {
        $array_item_in_cart[$i] = mysql_fetch_array($results, MYSQL_ASSOC);
    }
    $price = 0;
    if ($number)
    {
        require ('admin/engine/template/functions.php');
        if (MOBILEVER==0) {
            $item_template = return_one_template($config['user_params_13']);
        } else {
            $item_template = return_one_template(32);
        }
        $item_template = $item_template['template'];
        if (MOBILEVER){
            $body .= '<div style="margin-top: 50px;"></div>';
        }
        if (!isset ($body))
            $body = '';
        $body .= $item_template;
        $allprice = 0;
        $valuta = '';
        $sql = "SELECT * FROM `ls_discounts` WHERE `code` = '".mysql_real_escape_string($_POST['promocode'])."' AND `status` = 1 AND `date` >= '".date("Y-m-d")."';";
        if ($infoCode = getOneString($sql)){
            $promocode = $infoCode['code'];
            if ($arrayCodeDb = getArray("SELECT * FROM `ls_discountsCode` WHERE `discountId` = '".$infoCode['id']."'")){
                $arrayCode = Array();
                foreach ($arrayCodeDb as $oneCode){
                    $arrayCode[] = $oneCode['itemId'];
                }
            }
        }
        if (isset ($_POST['promocode'])){
            $sql = "SELECT * FROM `ls_certificate` WHERE `code` = '".mysql_real_escape_string($_POST['promocode'])."' AND `used` = 0 AND 
                (`dateTo` = '1970-01-01' OR `dateTo` > '".date("Y-m-d")."')";
            if ($infoCertificate = getOneString($sql)){
                print_r ($infoCertificate);
                if (!$info = getOneString("SELECT * FROM `ls_cartCertificate` WHERE `codeId` = '".$infoCertificate['id']."'")) {
                    mysql_query("
                    INSERT INTO `ls_cartCertificate` (
                        `uniq_user`, 
                        `price`, 
                        `codeId`
                        ) VALUES (
                        '" . $_COOKIE['PHPSESSID'] . "',
                        '" . $infoCertificate['price'] . "',
                        '" . $infoCertificate['id'] . "');
                    ");
                }
            }
        }
        $itemIds = Array();
        foreach ($array_item_in_cart as $v){
            $itemIds[] = $v['id_item'];
        }
        foreach ($array_item_in_cart as $v)
        {
            $blockNumber = 0;
            if (MOBILEVER==0) {
                $item_template = return_one_template($config['user_params_14']);
            } else {
                $item_template = return_one_template(34);
            }
            $item_template = $item_template['template'];

            $array_template = returnSubstrings($item_template, '{template_', '}');
//			$info_item = return_item_info ($v['id_item'], $id_online_lang);
            $info_item = mysql_fetch_array(mysql_query("SELECT * FROM `ls_items` where `id` = '".$v['id_item']."';"), MYSQL_ASSOC);
            $html_item = $item_template;
            $sql = "select * from `ls_items` where `id` = '".$v['id_item']."';";
            $results = mysql_query($sql);
            $info_item_ls_item = mysql_fetch_array($results, MYSQL_ASSOC);
            foreach ($array_template as $v_2)
            {
                $a_template = explode ('_', $v_2);
                if ($a_template[1]==$info_item_ls_item['id_card'])
                {
                    $add_template = return_one_template($a_template[0]);
                    $add_template = $add_template['template'];
                    $html_item = str_replace ('{template_'.$v_2.'}', $add_template, $html_item);
                } else {
                    $html_item = str_replace ('{template_'.$v_2.'}', '', $html_item);
                }
            }
            $array_image = returnSubstrings($html_item, '{image_', '}');
            $array_text = returnSubstrings($html_item, '{text_', '}');
            $array_price = returnSubstrings($html_item, '{price_', '}');
            $array_select = returnSubstrings($html_item, '{select_', '}');
            $array_name = returnSubstrings($html_item, '{name_', '}');
            //print_r ($info_item);
            foreach ($array_image as $image_id)
            {
                $a_img = explode ('_', $image_id);
                $image = $info_item['image'.$a_img[0]];
                $image = $image[($a_img[1]-1)]['text'];
//                getImgSoroka()
                $html_item = str_replace ('{image_'.$image_id.'}', urlencode('upload/userparams/'.getMainImage ($info_item['id'])), $html_item);
            }
            foreach ($array_text as $text_id)
            {
                $a_text = explode ('_', $text_id);
                $text = $info_item['text'.$a_text[0]];
                $text = $text['text'];
                if ($info_item['select_6']==46)
                {
                    $idElements = str_replace('select|', '', $v['other_param']);
                    $infoSize = mysql_fetch_array(mysql_query("SELECT * FROM `ls_translate` where `id_elements` = '".$idElements."' and `type` = 'select_value';"), MYSQL_ASSOC);
//                    $size = ' <b>Ваш размер: '.$infoSize['text'].'</b>';
                } elseif ($info_item['select_6']==48){
                    $idElements = str_replace('select|', '', $v['other_param']);
                    $infoSize = mysql_fetch_array(mysql_query("SELECT * FROM `ls_translate` where `id_elements` = '".$idElements."' and `type` = 'select_value';"), MYSQL_ASSOC);
//                    $size = ' <b>Ваш размер: '.$infoSize['text'].'</b>';
                } elseif ($info_item['select_6']==128){
                    $idElements = str_replace('select|', '', $v['other_param']);
                    $infoSize = mysql_fetch_array(mysql_query("SELECT * FROM `ls_translate` where `id_elements` = '".$idElements."' and `type` = 'select_value';"), MYSQL_ASSOC);
//                    $size = ' <b>Ваш размер: '.$infoSize['text'].'</b>';
                }
                $idElements = str_replace('select|', '', $v['other_param']);
                $infoSize = mysql_fetch_array(mysql_query("SELECT * FROM `ls_translate` where `id_elements` = '".$idElements."' and `type` = 'select_value';"), MYSQL_ASSOC);
//                $size = ' <b>Ваш размер: '.$infoSize['text'].'</b>';
                $html_item = str_replace ('{text_'.$text_id.'}', $info_item['text_'.$text_id].'<br>'.$size, $html_item);
                unset ($size);
            }
            $html_item = str_replace('{img}', getImgSoroka($info_item), $html_item);
            foreach ($array_price as $price_id)
            {
                if (strlen($v['other_param'])==0)
                {
                    if ($info_item['price_2']!=0)
                    {
                        $info_item['price_1'] = $info_item['price_2'];
                    } else {
                        $price = $info_item['price_'.$price_id];
                    }
                    $price = $price['text'];
                    $oldPrice = $info_item['price_1'];
                    $array_price_new = explode ('|', $price);
                    if (in_array($info_item['id'], $arrayCode) or (empty($arrayCode) and isset ($infoCode) and !empty($infoCode))){
                        $oldPrice = $info_item['price_1'];
                        $info_item['price_1'] = ceil($info_item['price_1']-($info_item['price_1']/100*$infoCode['percent']));
                        if ($infoGift = getOneString("SELECT * FROM `ls_giftToItem` WHERE `giftId` = '".$info_item['id']."' AND `itemId` IN (".implode(',', $itemIds).")")){
                            if ($v['price']=='0.01') {
                                $info_item['price_1'] = '0.01';
                                $blockNumber = 1;
                            }
                        }
                        $sql = "
                        SELECT 
                            `ls_giftToItem`.* 
                        FROM 
                            `ls_giftToItem`
                        LEFT JOIN
                            `ls_cart`
                        ON 
                            `ls_cart`.`id_item` = `ls_giftToItem`.`itemId`
                        WHERE 
                            `ls_giftToItem`.`giftId` = '".$info_item['id']."' 
                        AND 
                            `ls_giftToItem`.`price` != '0.01'
                        AND
                            `dateFrom` <= '".date("Y-m-d")."'
                        AND
                            `dateTo` >= '".date("Y-m-d")."'
                            ";
//                        if ($_COOKIE['accessLevel']==100) {
                            if ($infoGift = getOneString($sql)) {
                                $info_item['price_1'] = $infoGift['price'];
                            }
//                        }
                        $html_item = str_replace ('{price_'.$price_id.'}', '<strike>'.$oldPrice.'</strike> '.$info_item['price_1'], $html_item);
                    } else {
                        if ($infoGift = getOneString("SELECT * FROM `ls_giftToItem` WHERE `giftId` = '".$info_item['id']."' AND `itemId` IN (".implode(',', $itemIds).")")){
                            if ($v['price']=='0.01') {
                                $info_item['price_1'] = '0.01';
                                $blockNumber = 1;
                            }
                        }
                        $sql = "
                        SELECT 
                            `ls_giftToItem`.* 
                        FROM 
                            `ls_giftToItem`
                        LEFT JOIN
                            `ls_cart`
                        ON 
                            `ls_cart`.`id_item` = `ls_giftToItem`.`itemId`
                        WHERE 
                            `ls_giftToItem`.`giftId` = '".$info_item['id']."' 
                        AND 
                            `ls_giftToItem`.`price` != '0.01'
                        AND
                            `dateFrom` <= '".date("Y-m-d")."'
                        AND
                            `dateTo` >= '".date("Y-m-d")."'
                        AND `ls_cart`.`uniq_user` = '".$_COOKIE['PHPSESSID']."'
                        AND `ls_cart`.`status` = 0
                            ";
//                        if ($_COOKIE['accessLevel']==100) {
                            if ($infoGift = getOneString($sql)) {
                                $info_item['price_1'] = $v['price'];
                                $blockNumber = 1;
                            }
//                        }
                        $html_item = str_replace ('{price_'.$price_id.'}', $info_item['price_1'], $html_item);
                    }
                    $html_item = str_replace ('{valuta}', $array_price_new[1], $html_item);
                    $allprice = $allprice + $info_item['price_1']*$v['number'];
                    $allpriceFull = $allpriceFull + $oldPrice*$v['number'];
                    $valuta = $array_price_new[1];
                } else {
                    if ($info_item['price_2']!=0)
                    {
                        $info_item['price_'.$price_id] = $info_item['price_2'];
                    }
                    $array_price_param = explode ('|', $v['other_param']);
                    $price_for_other_param = return_price_for_param($v['id_item'], $array_price_param);
                    $html_item = str_replace ('{price_'.$price_id.'}', $info_item['price_'.$price_id], $html_item);
                    $tr_ref_value = mysql_fetch_array(mysql_query("SELECT `value` FROM `ls_reference_values_translate` where `id_reference_value` = '".$price_for_other_param[1]."' and `id_lang` = '".$id_online_lang."';"), MYSQL_ASSOC);
                    $html_item = str_replace ('{valuta}', $tr_ref_value['value'], $html_item);
                    $allprice = $allprice + $info_item['price_'.$price_id]*$v['number'];
                    $valuta = $tr_ref_value['value'];
                }
            }
            $html_item = str_replace ('{summ}', $allprice, $html_item);
            foreach ($array_select as $select_id)
            {
                $select = $info_item['select'.$select_id];
                foreach ($select as $key => $v_3)
                {
                    $html_select .= $v_3['text'];
                    if (isset ($select[$key+1]))
                    {
                        $html_select .= ',';
                    }
                }
                $html_item = str_replace ('{select_'.$select_id.'}', getOneValueText($info_item['select_'.$select_id]), $html_item);
                unset ($html_select);
            }

            foreach ($array_name as $name_id)
            {
                $a_name = explode ('_', $name_id);
                switch ($a_name[0])
                {
                    case "select":
                        $one_name = return_one_translate ($a_name[1], $id_online_lang, 'select');
                        break;
                }
                $html_item = str_replace ('{text_'.$name_id.'}', $v['text_'.$name_id.''], $html_item);
            }
            $html_item = str_replace ('{id_item}', $v['id'], $html_item);
            $html_item = str_replace ('{link}', '{main_sait}{lang}/mode/item-'.$v['id_item'].'.html', $html_item);
            if ($blockNumber==0) {
                $html_item = str_replace('{number}', '
                                    <div class="numberItemHtml">
                                            <span class="cart-minus">-</span>
                                          <input class="cart-input" type="text" value="'.$v['number'].'" id="numberItem_' . $v['id'] . '" onchange="getNumberItem(\'numberItem_' . $v['id'] . '\');">
                                          <span class="cart-plus">+</span>
                                    </div>', $html_item);
//                if (MOBILEVER == 0) {
//                    $html_item = str_replace('{number}', '<div class="numberItemHtml">
//                                        <input type="text" id="numberItem_' . $v['id'] . '" value="' . $v['number'] . '" class="input-text qty">
//                                            <div style="vertical-align:middle; margin-left: 5px; cursor: pointer; display:inline-block;" class="numberItem_' . $v['id'] . '_image" onclick="getNumberItem(\'numberItem_' . $v['id'] . '\');">
//                                                <i class="fa fa-refresh"></i>
//                                            </div>
//                                        </div>', $html_item);
//                } else {
//                    $html_item = str_replace('{number}', '
//                    <div class="input-group mb-3">
//                        <button type="button" class="btn btn-default btn-number" ' . (($v['number'] == 1) ? ' disabled="disabled"' : '') . ' data-type="minus" data-field="quant[' . $v['id'] . ']" style="border:none;">
//                          <span class="fa fa-minus"></span>
//                      </button>
//                      <input type="text" name="quant[' . $v['id'] . ']" class="form-control input-number" id="numberItem_' . $v['id'] . '" value="' . $v['number'] . '" min="1" max="100"  onchange="getNumberItem(\'numberItem_' . $v['id'] . '\');">
//                      <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="quant[' . $v['id'] . ']" style="border:none;">
//                          <span class="fa fa-plus"></span>
//                      </button>
//                    </div>
//                    ', $html_item);
//                }
            } else {
                $html_item = str_replace('{number}', $v['number'], $html_item);
            }
            switch ($info_item['select_4']){
                case 49:
                    $dostavka = '<b>5-21 дня</b>';
                    break;
                case 42:
                    $dostavka = '<b>1-2 дня</b>';
                    break;
                case 43:
                    $dostavka = '<b>20-25 дней</b>';
                    break;
            }
            $html_item = str_replace ('{dostavka}', $dostavka, $html_item);
            $body .= $html_item;
        }
        $allsuma = $allprice+$config['user_params_16'];
        $info_name_page = return_one_translate ($config ['topland_15'], $id_online_lang, 'static_page_nam');
        $info_text = return_one_translate ($config ['topland_15'], $id_online_lang, 'static_page_tex');
        if (MOBILEVER==0) {
            $item_template = return_one_template($config['user_params_15']);
        } else {
            $item_template = return_one_template(33);
        }
        $item_template = $item_template['template'];
        $item_template = str_replace ('{allprice}', $allprice, $item_template);
        $item_template = str_replace ('{valuta}', $valuta, $item_template);
        $item_template = str_replace ('{dostavka}', $config['user_params_16'], $item_template);
        if ($config['user_params_31'])
        {
            $item_template = str_replace ('{info_dostavka}', '', $item_template);
        } else {
            $item_template = str_replace ('{info_dostavka}', '<span style="color:red;">'.$lang[525].'</span>', $item_template);
        }
        $item_template = str_replace ('{allsuma}', $allsuma, $item_template);
        if (isset ($infoUser)){
            $optionRegion = getRegion($infoUser['region']);
            $optionCity = getCity($infoUser['region'], $infoUser['city']);
            $optionWarehouse = getWarehouse($infoUser['city'],$infoUser['warehouse']);
        } else {
            $optionRegion = getRegion();
            $optionCity = '<option value="0">Сделайте выбор области</option>';
            $optionWarehouse = '<option value="0">Сделайте выбор области</option>';
        }
        $array_values_for_reference = return_all_values_for_reference(3);
        if (!isset ($select_options))
            $select_options = '';
        $j = 2;
        foreach ($array_values_for_reference as $v){
            $info_translate_value_ref = return_all_translate_for_reference_value($v['id'], $id_online_lang);
            $select_optionsOplata .= '<option value="'.$j.'">'.$info_translate_value_ref[0]['value']."</option>\r\n";
            $j--;
        }
        $array_values_for_reference = return_all_values_for_reference(2);
        if (!isset ($select_options))
            $select_options = '';
        $j = 2;
        foreach ($array_values_for_reference as $v){
            $info_translate_value_ref = return_all_translate_for_reference_value($v['id'], $id_online_lang);
            $select_optionsDostavka .= '<option value="'.$v['id'].'">'.$info_translate_value_ref[0]['value']."</option>\r\n";
            $j--;
        }
        if ($allpriceFull!=$allprice){
            $allpriceFullHtml = '<strike style="color: red;">'.$allpriceFull.'</strike> ';
        }
        $infoUserHtml = '';
//        if (!MOBILEVER){
//            $infoUserHtml = '<div class="row">
//            <div class="col-lg-12" style="text-align:center;">
//                <img src="{main_sait}images/baner_koshyk2.jpg">
//            </div>
//        </div>';
//        } else {
//            $infoUserHtml = '<div class="row">
//            <div class="col-lg-12" style="text-align:center;">
//                <img src="{main_sait}images/meest.jpg">
//            </div>
//        </div>';
//        }
        if (MOBILEVER==1) {
            $js_script .= '
            $(\'.btn-number\').click(function(e){
                e.preventDefault();
                
                fieldName = $(this).attr(\'data-field\');
                type      = $(this).attr(\'data-type\');
                var input = $("input[name=\'"+fieldName+"\']");
                var currentVal = parseInt(input.val());
                if (!isNaN(currentVal)) {
                    if(type == \'minus\') {
                        
                        if(currentVal > input.attr(\'min\')) {
                            input.val(currentVal - 1).change();
                        } 
                        if(parseInt(input.val()) == input.attr(\'min\')) {
                            $(this).attr(\'disabled\', true);
                        }
            
                    } else if(type == \'plus\') {
            
                        if(currentVal < input.attr(\'max\')) {
                            input.val(currentVal + 1).change();
                        }
                        if(parseInt(input.val()) == input.attr(\'max\')) {
                            $(this).attr(\'disabled\', true);
                        }
            
                    }
                } else {
                    input.val(0);
                }
            });
            $(\'.input-number\').focusin(function(){
               $(this).data(\'oldValue\', $(this).val());
            });
            $(\'.input-number\').change(function() {
                
                minValue =  parseInt($(this).attr(\'min\'));
                maxValue =  parseInt($(this).attr(\'max\'));
                valueCurrent = parseInt($(this).val());
                
                name = $(this).attr(\'name\');
                if(valueCurrent >= minValue) {
                    $(".btn-number[data-type=\'minus\'][data-field=\'"+name+"\']").removeAttr(\'disabled\')
                } else {
                    alert(\'Sorry, the minimum value was reached\');
                    $(this).val($(this).data(\'oldValue\'));
                }
                if(valueCurrent <= maxValue) {
                    $(".btn-number[data-type=\'plus\'][data-field=\'"+name+"\']").removeAttr(\'disabled\')
                } else {
                    alert(\'Sorry, the maximum value was reached\');
                    $(this).val($(this).data(\'oldValue\'));
                }
                
                
            });
            $(".input-number").keydown(function (e) {
                    // Allow: backspace, delete, tab, escape, enter and .
                    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                         // Allow: Ctrl+A
                        (e.keyCode == 65 && e.ctrlKey === true) || 
                         // Allow: home, end, left, right
                        (e.keyCode >= 35 && e.keyCode <= 39)) {
                             // let it happen, don\'t do anything
                             return;
                    }
                    // Ensure that it is a number and stop the keypress
                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                        e.preventDefault();
                    }
                });
        ';
        } else {
            $jsMobile = '';
        }
        $useCert = '';
        $allPriceCert = 0;
        $certPrice = '';
        if ($arrayCertUse = getArray("SELECT * FROM `ls_cartCertificate` WHERE `uniq_user` = '".$_COOKIE['PHPSESSID']."' AND `used` = 0")){
            $useCert = '<table class="table table-bordered table-striped">';
            foreach ($arrayCertUse as $oneCert){
                $useCert .= '<tr>
                    <td>'.getOneString("SELECT * FROM `ls_certificate` WHERE `id` = '".$oneCert['codeId']."';")['code'].'</td>
                    <td>-'.$oneCert['price'].' грн.</td>
                </tr>';
                $allPriceCert = $allPriceCert + $oneCert['price'];
            }
            $useCert .= '</table>';
            $certPrice = '
            <tr>
                <td>Знижка по сертифікатам:</td>
                <td><strong>'.$allPriceCert.' грн.</strong></td>
            </tr>
            ';
            $finalPrice = '
            <tr>
                <td>Остаточна ціна:</td>
                <td><strong>'.($allsuma-$allPriceCert).' грн.</strong></td>
            </tr>
            ';
        }
        $infoUserHtml .= '
        <script>
        '.$jsMobile.'
            function checkDostavkaType(){
                $("#regionSelect").show();
                $("#citySelect").show();
                if ($("#dostavka").val()==2){
                    $(".onlyNP").show();
                    $("#onlyME").hide();
                    $("#oplata option[value=\"1\"]").attr(\'disabled\', false);
                } else {
                    if ($("#dostavka").val()==7){
                        $("#onlyME").hide();
                        $("#regionSelect").hide();
                        $("#citySelect").hide();
                        $(".onlyNP").hide();
                    } else {
                        $(".onlyNP").hide();
                        $("#onlyME").show();
                        $("#oplata option[value=\"1\"]").attr(\'disabled\', true);
                    }
                }
            }
        </script>
        <div class="cart-collaterals-item">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <div class="checkbox-form">
							<h2>Дані для доставки</h2>
							<h3>Оберіть зручний для Вас спосіб доставки та оплати</h3>
							<div class="shopping_form">
								<h4>Служба доставки<span>*</span></h4>
								<select id="dostavka" onchange="checkDostavkaType();" class="validate-select" title="Служба доставки">
									'.$select_optionsDostavka.'
								</select>
								<div id="regionSelect">
                                    <h4>Область</h4>
                                    <select id="region" class="required-entry validate-select" onchange="getCity();">'.$optionRegion.'</select>
								</div>
								<div id="citySelect">
                                    <h4>Місто</h4>
                                    <select id="city" class="required-entry validate-select" onchange="getWarehouse(0); getWarehouse(1);">'.$optionCity.'</select>
								</div>
								<div class="onlyNP">
                                    <h4>Відділення "Нової пошти"</h4>
                                    <select id="warehouse" class="required-entry validate-select"  onchange="$(\'#warehousePoshtomat\').val(0);">'.$optionWarehouse.'</select>
								</div>
								<div class="onlyNP">
                                    <h4>Поштомат "Нової пошти"</h4>
                                    <select id="warehousePoshtomat" class="required-entry validate-select" onchange="$(\'#warehouse\').val(0);">'.$optionWarehouse.'</select>
								</div>
								<div id="onlyME" style="display: none;">
                                    <h4>Адреса доставки</h4>
                                    <input class="input-text validate-postcode" type="text" id="adress" value="">
								</div>
                                <h4>Варіант оплати"</h4>
                                <select id="oplata" class="required-entry validate-select">'.$select_optionsOplata.'</select>
							</div>
						</div>                    
                    </div>    
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
						<div class="shopping_details_des">
							<h2>Дані отримувача</h2>
							<h3>Введіть дані отримувача, перевірте правильність введення номера телефону</h3>
							<div class="shopping_form">
								<h4>П.І.Б.</h4>
								<input type="text" name="pib" id="pib" class="input-text" value="'.$infoUser['name'].' '.$infoUser['surName'].'">
								<h4>Номер телефону:</h4>
								<input type="text" name="tel" id="phoneOrder" value="'.$infoUser['login'].'" onkeypress="return isNumberOrPlusKey(event)"  maxlength="13" class="input-text" placeholder="+380000000000">
								<h4>E-Mail:</h4>
								<input type="text" name="email" id="email" value="'.$infoUser['email'].'">
								<h4>'.$lang[237].':</h4>
								<input type="text" name="dop_info" id="dop_info" value="">
								<label>
                                    <input type="checkbox" value="1" onclick="checkTerms();" id="confirmTerms" checked style="width: auto; height: auto;"> Погоджуюсь з <a href="'.$config['site_url'].'ua/mode/content-1.html" target="_blank">правилами доставки</a> та <a href="'.$config['site_url'].'ua/mode/content-2.html" target="_blank">правами покупця</a>
                                </label>
								<label>
                                    <input type="checkbox" value="1" id="notCallBack" style="width: auto; height: auto;">  Не звоніть мені для підтвердження замовлення
                                </label>
                                <div style="margin: 5px 0px; display: none;" id="pibError" class="alert alert-danger">
                                    <strong>Помилка!</strong> Введіть ім\'я і фамілію!
                                </div>
                                <div style="margin: 5px 0px; display: none;" id="phoneOrderError" class="alert alert-danger">
                                    <strong>Помилка!</strong> Введіть свій мобільний номер телефону!
                                </div>
                                <div style="margin: 5px 0px; display: none;" id="adressError" class="alert alert-danger">
                                    <strong>Помилка!</strong> Заповніть будь-ласка поля "Область", "Місто", "Номер відділення"!
                                </div>
							</div>
						</div>
					</div>       
					<div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
					    <div class="cart-page-total">
                             <h2>Загальна вартість</h2>
                             <ul class="mb-20">
                                <li>Разом <span>'.$allpriceFullHtml.$allsuma.'</span> грн.</span></li>
                             </ul>
                             <a href="#" onclick="checkDostavka();" class="tp-btn tp-color-btn banner-animation">Оформити замовлення</a>
                          </div>
					</div>     
                </div>
            </div>
        </div>
        ';
        $item_template = str_replace('{infoUser}', $infoUserHtml, $item_template);
        $body .= $item_template;
        $body .= '
		<script>
            function getNumberItem (id)
            {
                $.ajax({
                    type: \'POST\',
                    dataType: \'html\',
                    data: {
                        id: id,
                        value: $("#" + id).val(),
                    },
                    url: main_site + "include/changeNumberItemInCart.php",
                    success: function(result) {
                        window.location.reload();
                    },
                });
            }
            $(function() {
                $(\'.numberItem\').click(function() {
                    var id = this.id;
                    $("." + id + "_image").css(\'display\', \'inline-block\');
                });
                $(\'.numberItem\').bind(\'keypress\', function(e) {
                        if(e.keyCode==13){
                            getNumberItem(this.id);
                        }
                });
            });
        </script>
		';
        $body = '<div id="cartBody">'.$body.'</div>';
    } else {
        $body .= '
		<div class="container" style="min-height: 800px; margin-top: 50px;">
		    <div class="alert alert-danger">
		    <strong>Помилка!</strong> Нажаль корзина пуста!
		    </div>
		</div>
		';
    }
} else {
    switch ($_GET['go'])
    {
        case 1:
            $array_values_for_reference = return_all_values_for_reference($config['user_params_32']);
            if (!isset ($select_options))
                $select_options = '';
            foreach ($array_values_for_reference as $v)
            {
                $info_translate_value_ref = return_all_translate_for_reference_value($v['id'], $id_online_lang);
                $select_options .= '<option value="'.$v['id'].'">'.$info_translate_value_ref[0]['value']."</option>\r\n";
            }
            $array_values_for_reference = return_all_values_for_reference(3);
            if (!isset ($select_options))
                $select_options = '';
            $j = 2;
            foreach ($array_values_for_reference as $v)
            {
                $info_translate_value_ref = return_all_translate_for_reference_value($v['id'], $id_online_lang);
                $select_optionsOplata .= '<option value="'.$j.'">'.$info_translate_value_ref[0]['value']."</option>\r\n";
                $j--;
            }
            if (!isset ($body))
                $body = '';
            $body .= '
			<script language="javascript"> 
				function validateFormOnSubmit(theForm) {
				var reason = "";
				 
				  reason += validateAddress(form.adress);
				  reason += validateName(form.pib);
				  reason += validatePhone(form.tel);
					
				  if (reason != "") {
					alert("'.$lang[239].'\n" + reason);
					return false;
				  }
				 
				  return true;
				}
				 
				function validateAddress(fld) {
					var error = "";
				  
					if (fld.value == 0) {
					setStyleError(fld)
						error = "'.$lang[240].'\n"
					} else {
					setStyleOk(fld)
					}
					return error;   
				}
				 
				function validateFlat(fld)
				{
					var error = "";
				  
					if (fld.value.length == 0) {
						setStyleError(fld)
						error = "'.$lang[241].'\n"
					} else {
						setStyleOk(fld)
					}
					return error;   
				}
				 
				function validateName(fld)
				{
					var error = "";
				  
					if (fld.value.length == 0) {
						setStyleError(fld)
						error = "'.$lang[242].'\n"
					} else {
						setStyleOk(fld)
					}
					return error;   
				}
				 
				function validatePhone(fld)
				{
					var error = "";
				  
					if (fld.value.length != 13) {
						setStyleError(fld)
						error = "'.$lang[243].'\n"
					} else {
						setStyleOk(fld)
					}
					return error; 
				}
				 
				function setStyleError(fld)
				{
					fld.style.cssText = "border-color: #ffa500; border-width: 1px; border-style: solid;"
					return true;
				}
				 
				function setStyleOk(fld)
				{
					fld.style.cssText = "border-color: #cccccc; border-width: 1px; border-style: solid;"
					return true;
				}
				 
				function isNumberOrPlusKey(evt)
				{
					var charCode = (evt.which) ? evt.which : event.keyCode
					if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 43 && charCode != 61)
						return false;
					return true;
				}
				 
				function isNumberKey(evt)
				{
					var charCode = (evt.which) ? evt.which : event.keyCode
					if (charCode > 31 && (charCode < 48 || charCode > 57))
						return false;
					return true;
				}
				 
				</script>
<div class="container" style="min-height: 800px; margin-top: 50px;">
<div class="row">
	<div class="col-lg-6 col-lg-offset-3">
		<div class="car">
		<div class="headerBodyRotator">
				<h2>'.$lang[231].'</h2>
		</div>
							<form action="'.$config ['site_url'].$alt_name_online_lang.'/mode/cart.html?go=2" method="POST" onsubmit="return validateFormOnSubmit(this)"  name="form">
								<table class="table table-bordered table-striped">
									<tr>
										<td>'.$lang[232].'<span style="color:red;"><sup>*</sup></span></td>
										<td><input type="text" name="pib" class="form-control"></td>
									</tr>
									<tr>
										<td>'.$lang[233].'<span style="color:red;"><sup>*</sup></span></td>
										<td><input type="text" name="tel" value="+380" onkeypress="return isNumberOrPlusKey(event)"  maxlength="13" class="form-control"></td>
									</tr>
									<tr>
										<td>'.$lang[234].'</td>
										<td><input type="text" name="email" class="form-control"></td>
									</tr>
									<tr>
										<td>
											'.$lang[235].'<span style="color:red;"><sup>*</sup></span><br>
										</td>
										<td><input type="text" name="adress" class="form-control"></td>
									</tr>
									<tr>
										<td>Вариант оплаты:<span style="color:red;"><sup>*</sup></span></td>
										<td>
											<select name="oplata" class="form-control">
												'.$select_optionsOplata.'
											</select>
										</td>
									</tr>
									<tr>
										<td>'.$lang[236].'<span style="color:red;"><sup>*</sup></span></td>
										<td>
											<select name="dostavka" class="form-control">
												'.$select_options.'
											</select>
										</td>
									</tr>
									<tr>
									    <td>Номер или адрес склада:<span style="color:red;"><sup>*</sup></span></td>
										<td>
											<input type="text" name="numberSkl" class="form-control">
										</td>
									</tr>
									<tr>
										<td>'.$lang[237].'</td>
										<td><textarea name="dop_info" rows="4" cols="25" class="form-control"></textarea></td>
									</tr>
									<tr>
										<td colspan="2" align="center"><input type="submit" name="submit" value="'.$lang[238].'" class="btn btn-lg btn-success"></td>
									</tr>
								</table>
							</form>
				</div></div></div></div>
				';
            break;
        case 2:
            if (isset ($_POST['pib']))
            {
                $uniq_id_in_base = $_COOKIE['PHPSESSID'];
                $sql = "
				INSERT INTO  `ls_orders` (
				`uniq_user` ,
				`id_user` ,
				`pib` ,
				`number_phone` ,
				`email` ,
				`adress` ,
				`dostavka` ,
				`dop_info` ,
				`status` ,
				`numberSkl` ,
				`oplata` ,
				`time`
				)
				VALUES (
				'".$uniq_id_in_base."',
				'".intval($_COOKIE['id_user_online'])."',
				'".mysql_real_escape_string($_POST['pib'])."',
				'".mysql_real_escape_string($_POST['tel'])."',
				'".mysql_real_escape_string($_POST['email'])."',
				'".mysql_real_escape_string($_POST['adress'])."' ,
				'".mysql_real_escape_string($_POST['dostavka'])."' ,
				'".mysql_real_escape_string($_POST['dop_info'])."' ,
				'0' ,
				'".mysql_real_escape_string($_POST['numberSkl'])."' ,
				'".intval($_POST['oplata'])."' ,
				'".time()."'
				);
				";
                if (mysql_query ($sql))
                {
                    //$body_mail = $lang[246].$config ['site_url'].'/admin/list_item.php?uniq='.$uniq_id_in_base;
                    $body_mail = "
                    Здравствуйте.<br>
                    Ваш заказ принят в обработку.<br>
                    В ближайшее время с Вами свяжется наш менеджер.<br>
					<a href=\"".$config ['site_url']."status.php?uniq=".$uniq_id_in_base."\" style=\"color:#FF0000;\"><b>Посмотреть статус заказа</b><br>
					<b>Спасибо за заказ</b>

					";
                    if ($config['user_params_5'])
                    {
                        send_sms (0);
                    }
                    //mail ($_POST['email'], $lang[245], $body_mail);
                    send_message_for_email ($_POST['email'], $lang[245], $body_mail, 'new_order', '');
                    //send_message_for_email ('intarsio@ya.ru', 'Замовлення на сайті', 'Нове замовлення на сайті. Для перегляду: http://intarsio.com.ua/admin/', 'new_order', '');
                    //mail ('intarsio@ya.ru', 'Замовлення на сайті', 'Нове замовлення на сайті. Для перегляду: http://intarsio.com.ua/admin/');
                    $sql = "SELECT `id` FROM `ls_cart` where `uniq_user` = '".$uniq_id_in_base."';";
                    $results = mysql_query($sql);
                    $number = mysql_num_rows ($results);
                    for ($i=0; $i<$number; $i++)
                    {
                        $array_item_in_cart[$i] = mysql_fetch_array($results, MYSQL_ASSOC);
                    }
                    foreach ($array_item_in_cart as $v)
                    {
                        $sql = "update `ls_cart` set status = 1 where `id` = '".$v['id']."';";
                        mysql_query ($sql);
                    }
                    $body .= '
					<div class="container" style="margin-top: 50px; min-height: 800px;">
					       <div class="alert alert-success">
					        <strong>Спасибо!</strong> Мы успешно приняли Ваш заказ - наш менеджер свяжется с Вами для уточнения деталей!
					       </div>
					</div>
    				';
                } else {
                    $body .= '
					<div class="container" style="margin-top: 50px; min-height: 800px;">
					       <div class="alert alert-danger">
					        <strong>Ошибка!</strong> Возникла ошибка, перезвоните нам, и мы с удовольствием приймем заказ в ручном режиме!
					       </div>
					</div>
					';
                }
                session_regenerate_id();
            }
            break;
    }
}
$name_left_block = $lang[218];
require ('include/popular_items.php');
?>