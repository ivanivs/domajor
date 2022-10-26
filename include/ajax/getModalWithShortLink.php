<?php
if ($info = getOneString("SELECT * FROM `ls_shortLink` WHERE `url` = '".mysql_real_escape_string($_POST['url'])."';")){
    $url = $info['short'];
} else {
    mysql_query("INSERT INTO `ls_shortLink` (`url`, `short`) VALUES ('".mysql_real_escape_string($_POST['url'])."', '');");
    $id = mysql_insert_id();
    $url = $config['site_url'].'l/'.$id.'.html';
    mysql_query("UPDATE `ls_shortLink` SET `short` = '".mysql_real_escape_string($url)."' WHERE `id` = '".$id."';");
}
echo '
<div class="modal fade" id="modalShortLink" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Генерація короткого посилання</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="text" class="form-control" value="'.$url.'"> 
        <!--<div><button class="btn btn-success" onclick="copyToClipboard(\'#textShortLink\')">Копіювати</button></div>-->       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрити</button>
      </div>
    </div>
  </div>
</div>
';