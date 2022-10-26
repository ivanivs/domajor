<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/14/15
 * Time: 5:26 PM
 * To change this template use File | Settings | File Templates.
 */
if ($array = getArray("SELECT `ls_params_select_values`.`id`, `ls_translate`.`text` FROM `ls_params_select_values` JOIN `ls_translate` ON `ls_params_select_values`.`id` = `ls_translate`.`id_elements` WHERE `ls_params_select_values`.`parent_param_id` AND `ls_translate`.`type` = 'select_value' AND `ls_params_select_values`.`parent_param_id` = '".intval($_POST['id'])."' ORDER BY `ls_translate`.`text`")){
    foreach ($array as $v){
        echo '<option value="'.$v['id'].'">'.$v['text'].'</option>';
    }
}