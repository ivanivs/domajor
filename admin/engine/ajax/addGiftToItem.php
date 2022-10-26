<?php
if (isset ($_POST['id'])){
    echo '
<div class="modal hide fade" id="addGiftToItemModal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Додати товар, як подарунок</h3>
  </div>
  <div class="modal-body">
    <div id="modalSuccess">'.generateGiftToItemTable(intval($_POST['id'])).'</div>
    <hr>
    <strong>Додавання прив\'язки товарів, кожен з нового рядка</strong>
    <div class="row">
        <div class="col-lg-3"><input type="text" class="form-control" id="giftId" placeholder="ID товару"></div>    
        <div class="col-lg-3"><input type="number" class="form-control" id="giftPrice" value="0.01"></div>    
        <div class="col-lg-3"><input type="text" class="form-control" id="dateFrom" value="'.date("Y-m-d").'"></div>    
        <div class="col-lg-3"><input type="text" class="form-control" id="dateTo" value="'.date("Y-m-d", strtotime("next month")).'"></div>    
    </div>
    <button class="btn btn-success" onclick="addGiftToItemId('.intval($_POST['id']).'); return false;" style="margin-bottom:20px;">Додати</button>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal" aria-hidden="true">Закрити</a>
  </div>
</div>
';
}