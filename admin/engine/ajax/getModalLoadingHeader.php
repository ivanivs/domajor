<?php
echo '
<div class="modal hide fade" id="modalSearhBody">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Завантажте хедер</h3>
  </div>
  <div class="modal-body">
    <form enctype="multipart/form-data" action="" method="POST">
        <!-- Поле MAX_FILE_SIZE должно быть указано до поля загрузки файла -->
            <!-- Название элемента input определяет имя в массиве $_FILES -->
        <input type="hidden" name="searchId" value="'.intval($_POST['id']).'">
        <div>Отправить этот файл: <input name="userfile" type="file" /></div>
        <div><input type="submit" value="Загрузить" class="btn btn-success" /></div>
    </form>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal" >Закрити</a>
  </div>
</div>
';