<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/13/15
 * Time: 9:41 PM
 * To change this template use File | Settings | File Templates.
 */
//if ($arrayMark = getArray("SELECT `ls_params_select_values`.`id`, `ls_translate`.`text` FROM `ls_params_select_values` JOIN `ls_translate` ON `ls_translate`.`id_elements` = `ls_params_select_values`.`id` where `ls_params_select_values`.`id_params` = 3 and `ls_translate`.`type` = 'select_value' ORDER by `ls_translate`.`text`;")){
//    $optionMark = '';
//    foreach ($arrayMark as $v){
//        $optionMark .= '<option value="'.$v['id'].'">'.$v['text'].'</option>';
//    }
//}
echo '
<div class="modal hide fade" id="addMobilePhoneModal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Додати з цим товаром також купують</h3>
  </div>
  <div class="modal-body">
    <div id="modalSuccess">'.generateItemToItemTable(intval($_POST['id'])).'</div>
    <hr>
    <strong>Додавання прив\'язки товарів, кожен з нового рядка</strong>
    <textarea style="width: 97%; height: 100px;" id="bodyAddItemToItem"></textarea>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal" aria-hidden="true">Закрити</a>
    <a href="#" class="btn btn-primary" onclick="addItemToItem('.intval($_POST['id']).'); return false;">Додати</a>
  </div>
</div>
';