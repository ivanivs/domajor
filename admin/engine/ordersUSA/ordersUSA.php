<?php
$body_admin .= '<div align="center">
		<div id="temporary"></div>
		<h2 id="title">Заказ товаров из США</h2>
		<form>
		<table cellspacing="1" class="tablesorter" width="100%">
		 <thead>
		<tr> 
			<th width="25">ID</th> 
			<th>Телефон</th> 
			<th>Сума USD</th>
			<th>Доставка по США</th>
			<th>Цена в Украине</th> 
			<th>Комметарий администратора</th>
                        <th>Дата</th>
                        <th><img src="'.$config['site_url'].'images/admin/stock_list_enum-off.png"></th>
                        <th><img src="'.$config['site_url'].'images/admin/remove_16.png"></th>
		</tr> 
		 </thead> 
		  <tbody> ';
$results = mysql_query("SELECT * FROM `ls_calculator`");
$number = @mysql_num_rows ($results);
if ($number>0)
{
    for ($i=0; $i<$number; $i++)
    {	
            $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
    }
    foreach ($array as $v)
    {
        $body_admin .= '
            <tr id="orderUSA_'.$v['id'].'">
                <td align="center">'.$v['id'].'</td>
                <td align="center">'.$v['phone'].'</td>
                <td align="center">'.$v['price'].'</td>
                <td align="center">'.$v['priceUSA'].'</td>
                <td align="center"><div style="display:inline-block;" id="priceInUaRow_'.$v['id'].'">'.$v['priceInUa'].'</div> <img src="'.$config['site_url'].'images/admin/edit.png" style="cursor:pointer;" onclick="changePriceUa('.$v['id'].');"></td>
                <td align="center" style="width: 250px;"><div id="adminComment_'.$v['id'].'">'.$v['adminComment'].'</div> <img src="'.$config['site_url'].'images/admin/edit.png" style="cursor:pointer;" onclick="EditAdminComment('.$v['id'].');"></td>
                <td align="center">'.date('d.m.Y H:i', $v['time']).'</td>
                <td align="center"><img src="'.$config['site_url'].'images/admin/stock_list_enum-off.png" style="cursor:pointer;" onclick="getAllInfoOrderUSA('.$v['id'].');"></td>
                <td align="center"><img src="'.$config['site_url'].'images/admin/remove_16.png" onclick="if (confirm(\'Подтвердите удаление?\')) { removeOrderUSA('.$v['id'].'); }" style="cursor:pointer;"></td>
            </tr>
        ';
    }
}
$body_admin .= '
</tbody>
</table>
</form>
</div>
<div style="display:none;" title="Детальная информация" id="infoDetail"></div>
<div style="display:none;" title="Ввод цены в Украине" id="infoPriceUa">
    <input type="hidden" id="priceInUaId">
    Введите цену в Украине по выбраному заказу: <input type="text" id="priceInUaValue">
    <input type="submit" name="submit" value="Сохранить" onclick="savePriceInUa();">
</div>
<div style="display:none;" title="Ввод цены в Украине" id="adminCommentBlock">
    <input type="hidden" id="adminCommentId">
    <div><b>Введите Ваш комментарий</b></div>
    <div><textarea style="width: 500px; height: 150px;" id="bodyAdminComment"></textarea></div>
    <input type="submit" name="submit" value="Сохранить" onclick="saveAdminComment();">
</div>
<script>
$(function() {
    $( "#infoDetail" ).dialog({
      width: 600,
      autoOpen: false,
      modal: true
    });
    $( "#infoPriceUa" ).dialog({
      width: 600,
      autoOpen: false,
      modal: true
    });
    $( "#adminCommentBlock" ).dialog({
      width: 600,
      autoOpen: false,
      modal: true
    });
  });
  function getAdminComment (id)
  {
    $.ajax({
            type: \'POST\',
            dataType: \'html\',
            url: main_site + \'include/ajax/getAdminComment.php\',
            data: {
                id: id,
            },
            success: function(result) {
                $("#bodyAdminComment").val(result);
            },
        });
  }
  function EditAdminComment(id)
  {
        $("#adminCommentId").val(id);
        getAdminComment(id);
        $("#adminCommentBlock").dialog("open");
  }
  function changePriceUa(id)
  {
        $("#priceInUaId").val(id);
        $("#infoPriceUa").dialog("open");
  }
  function getAllInfoOrderUSA(id)
  {
        $.ajax({
            type: \'POST\',
            dataType: \'html\',
            url: main_site + \'include/ajax/getAllInfoOrderUSA.php\',
            data: {
                id: id
            },
            success: function(result) {
                $("#infoDetail").html(result);
                $("#infoDetail").dialog("open");
            },
        });
  }
  function removeOrderUSA(id)
  {
        $.ajax({
                type: \'POST\',
                dataType: \'html\',
                url: main_site + \'include/ajax/removeOrderUSA.php\',
                data: {
                    id: id,
                },
                success: function(result) {
                    $("#orderUSA_" + id).toggle(250, function() {$("#orderUSA_" + id).remove()});
                },
            });
  }
  function savePriceInUa()
  {
        $.ajax({
            type: \'POST\',
            dataType: \'html\',
            url: main_site + \'include/ajax/savePriceInUa.php\',
            data: {
                id: $("#priceInUaId").val(),
                price: $("#priceInUaValue").val(),
            },
            success: function(result) {
                $("#infoPriceUa").dialog("close");
                $("#priceInUaRow_" + $("#priceInUaId").val()).html(result);
            },
        });
  }
  function saveAdminComment()
  {
        $.ajax({
            type: \'POST\',
            dataType: \'html\',
            url: main_site + \'include/ajax/saveAdminComment.php\',
            data: {
                id: $("#adminCommentId").val(),
                adminComment: $("#bodyAdminComment").val(),
            },
            success: function(result) {
                $("#adminCommentBlock").dialog("close");
                $("#adminComment_" + $("#adminCommentId").val()).html(result);
            },
        });
  }
</script>
';
?>