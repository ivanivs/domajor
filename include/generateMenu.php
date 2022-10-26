<?php
preg_match_all('|{select_(.*)}|isU', $html, $arraySelectTmp);
foreach ($arraySelectTmp[1] as $oneSelectOption){
    $tmp = explode('_', $oneSelectOption);
    $selectId = $tmp[0];
    $selectValue = $tmp[1];
    $dataSelect = getValuesSelectParamWithParent($selectId, $selectValue);
    $value = getOneValueText($selectValue);
    $link = '';
    foreach ($dataSelect as $oneValue){
        $link .= '<li><a href="https://bobas.ua/ua/shop/'.translit($value).'/?p='.$oneValue['id'].','.$selectValue.'">'.$oneValue['text'].'</a></li>';
    }
    $html = str_replace('{select_'.$selectId.'_'.$selectValue.'}', $link, $html);
}
//$html = str_replace('{select_3_6}', '', $html);