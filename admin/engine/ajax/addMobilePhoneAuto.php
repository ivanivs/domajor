<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/14/15
 * Time: 6:04 PM
 * To change this template use File | Settings | File Templates.
 */
$infoItem = getOneString("SELECT * FROM `ls_items` where `id` = '".intval($_POST['id'])."';");
$idMark = 0;
if ($arrayMark = getArray("SELECT `ls_params_select_values`.`id`, `ls_translate`.`text` FROM `ls_params_select_values` JOIN `ls_translate` ON `ls_translate`.`id_elements` = `ls_params_select_values`.`id` where `ls_params_select_values`.`id_params` = 3 and `ls_translate`.`type` = 'select_value' ORDER by `ls_translate`.`text`;")){
    $optionMark = '';
    foreach ($arrayMark as $v){
        if (substr_count($infoItem['text_1'], $v['text'])){
            $infoCount = getOneString("SELECT COUNT(*) FROM `ls_markModel` WHERE `idItem` = '".intval($_POST['id'])."' and `mark` = '".$v['id']."'");
            if ($infoCount['COUNT(*)']==0)
                $idMark = $v['id'];
        }
    }
}
$idModel = 0;
if ($idMark!=0){
    if ($array = getArray("SELECT `ls_params_select_values`.`id`, `ls_translate`.`text` FROM `ls_params_select_values` JOIN `ls_translate` ON `ls_params_select_values`.`id` = `ls_translate`.`id_elements` WHERE `ls_params_select_values`.`parent_param_id` AND `ls_translate`.`type` = 'select_value' AND `ls_params_select_values`.`parent_param_id` = '".$idMark."' ORDER BY `ls_translate`.`text` ASC")){
        foreach ($array as $v){
            if (substr_count(strtoupper(str_replace('(', '', str_replace(')', '', $infoItem['text_1']))), strtoupper($v['text'])) or substr_count(strtoupper(str_replace('+', ' Plus', $infoItem['text_1'])), strtoupper($v['text'])) or substr_count(strtoupper(str_replace('III', '3', $infoItem['text_1'])), strtoupper($v['text'])) or substr_count(strtoupper(str_replace('-', '', $infoItem['text_1'])), strtoupper($v['text'])) or substr_count(strtoupper(str_replace('III', '3', str_replace(' ', '', $infoItem['text_1']))), strtoupper($v['text']))){
                $infoCount = getOneString("SELECT COUNT(*) FROM `ls_markModel` WHERE `idItem` = '".intval($_POST['id'])."' and `model` = '".$v['id']."'");
                if ($infoCount['COUNT(*)']==0)
                    $idModel = $v['id'];
            }
        }
    }
} else {
    if ($array = getArray("SELECT `ls_params_select_values`.`id`, `ls_translate`.`text` FROM `ls_params_select_values` JOIN `ls_translate` ON `ls_params_select_values`.`id` = `ls_translate`.`id_elements` WHERE `ls_params_select_values`.`parent_param_id` AND `ls_translate`.`type` = 'select_value' AND `ls_params_select_values`.`id_params` = '4' ORDER BY `ls_translate`.`text` ASC")){
        foreach ($array as $v){
            if (substr_count(strtoupper(str_replace('(', '', str_replace(')', '', $infoItem['text_1']))), strtoupper($v['text'])) or substr_count(strtoupper(str_replace('+', ' Plus', $infoItem['text_1'])), strtoupper($v['text'])) or substr_count(strtoupper(str_replace('III', '3', $infoItem['text_1'])), strtoupper($v['text'])) or substr_count(strtoupper(str_replace('-', '', $infoItem['text_1'])), strtoupper($v['text'])) or substr_count(strtoupper(str_replace('III', '3', str_replace(' ', '', $infoItem['text_1']))), strtoupper($v['text']))){
                $infoCount = getOneString("SELECT COUNT(*) FROM `ls_markModel` WHERE `idItem` = '".intval($_POST['id'])."' and `model` = '".$v['id']."'");
                if ($infoCount['COUNT(*)']==0)
                    $idModel = $v['id'];
            }
        }
    }
}
if ($idModel!=0){
    if ($idMark==0){
        $infoModel = getOneString("SELECT * FROM `ls_params_select_values` WHERE `id` = '".$idModel."';");
        if ($infoMark = getOneString("SELECT * FROM `ls_params_select_values` WHERE `id` = '".$infoModel['parent_param_id']."';")){
            $idMark = $infoMark['id'];
        }
    }
    if ($idMark!=0){
        $mark = getOneValue($idMark);
        $model = getOneValue($idModel);
        mysql_query("INSERT INTO  `ls_markModel` (
        `idItem` ,
        `mark` ,
        `model`
        )
        VALUES (
        '".intval($_POST['id'])."' ,
        '".$idMark."' ,
        '".$idModel."'
        );");
        $idMarkModel = mysql_insert_id();
        echo '<div id="markModel_'.$idMarkModel.'"><strong>'.$mark['text'].' '.$model['text'].'</strong> <i class="icon-remove" style="cursor: pointer; color: red;" onclick="removeMarkModel('.$idMarkModel.');"></i></div>';
    } else {
        echo '<strong style="color: red;">Нет совпадений</strong>';
    }
} else {
    echo '<strong style="color: red;">Нет совпадений</strong>';
}