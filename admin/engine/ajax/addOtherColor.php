<?php
echo '
<div class="modal hide fade" id="addOtherColorModal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Додати з цим товаром також купують</h3>
  </div>
  <div class="modal-body">
    <div id="modalSuccess">'.generateItemToItemTableOtherColor(intval($_POST['id'])).'</div>
    <hr>
    <strong>Додавання прив\'язки товарів, кожен з нового рядка</strong>
    <textarea style="width: 97%; height: 100px;" id="bodyAddItemToItem"></textarea>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal" aria-hidden="true">Закрити</a>
    <a href="#" class="btn btn-primary" onclick="addItemToItemOtherColor('.intval($_POST['id']).'); return false;">Додати</a>
  </div>
</div>
';