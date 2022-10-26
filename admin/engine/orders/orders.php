<?php
require_once ('engine/template/functions.php');
require_once ('engine/params/functions.php');
require_once ('engine/products/functions.php');
require_once ('engine/reference/functions.php');
if (isset ($_POST['date_from']))
{
	$parce_start_date = explode (' ',  $_POST['date_from']);
	$parce_start_date_ymd = explode ('-', $parce_start_date[0]);
	$parce_start_date_chs = explode (':', $parce_start_date[1]);
	$start_time = mktime($parce_start_date_chs[0], $parce_start_date_chs[1], 0, $parce_start_date_ymd[1], $parce_start_date_ymd[2], $parce_start_date_ymd[0]); 
	if (strlen($_POST['date_to'])>0)
	{
		$parce_start_date = explode (' ',  $_POST['date_to']);
		$parce_start_date_ymd = explode ('-', $parce_start_date[0]);
		$parce_start_date_chs = explode (':', $parce_start_date[1]);
		$end_time = mktime($parce_start_date_chs[0], $parce_start_date_chs[1], 0, $parce_start_date_ymd[1], $parce_start_date_ymd[2], $parce_start_date_ymd[0]); 
	} else {
		$end_time = time();
	}
	$array_orders = return_all_orders('1', $start_time, $end_time);
    $orderCount = return_all_orders_count(1, $start_time, $end_time);
} else {
    if (!isset ($_GET['id'])){
        if (isset ($_GET['all_orders']))
        {
            $array_orders = return_all_orders(0);
            $orderCount = return_all_orders_count(0);
        } else {
            if (isset ($_GET['only_del']))
            {
                $array_orders = return_all_orders(2);
                $orderCount = return_all_orders_count(2);
            } else {
                if (!isset ($_GET['page'])){
                    $thisPage = 1;
                    $start = 0;
                } else {
                    $thisPage = $_GET['page'];
                    $start = $_GET['page']*$config['user_params_35']-$config['user_params_35'];
                }
                $array_orders = return_all_orders(0, '', '', $start);
                $orderCount = return_all_orders_count(0, '', '');
            }
        }
    } else {

        $array_orders = getArray("SELECT * FROM `ls_orders` where `id` = '".intval($_GET['id'])."';");
    }
}
if (!isset ($_GET['id'])){
    $orderCount = $orderCount['COUNT(*)'];
    $page = ceil ($orderCount/$config['user_params_35']);
    $paginator = '
    <div class="pagination">
      <ul>
    ';
    if ($thisPage==1){
        $paginator .= '<li class="disabled"><a href="#"><i class="icon-backward"></i> назад</a></li>';
    } else {
        $paginator .= '<li><a href="index.php?do=orders&page='.($thisPage-1).'"><i class="icon-backward"></i> назад</a></li>';
    }
    for ($i = 1; $i<=$page; $i++){
        if ($i==$thisPage){
            $paginator .= '
                <li class="active"><a href="#">'.$i.'</a></li>
            ';
        } else {
            $paginator .= '
                <li><a href="index.php?do=orders&page='.$i.'">'.$i.'</a></li>
            ';
        }
    }
    if ($thisPage==$page){
        $paginator .= '<li class="disabled"><a href="#">вперед <i class="icon-forward"></i></a></li>';
    } else {
        $paginator .= '<li><a href="index.php?do=orders&page='.($thisPage+1).'">вперед <i class="icon-forward"></i></a></li>';
    }
    $paginator .= '  </ul>
    </div>';
} else {
    $paginator .= '<a href="'.$config['site_url'].'admin/index.php?do=orders" class="btn btn-info">отобразить все заказы</a>';
}
if ($array_orders)
{
	if (isset ($_GET['send_payment_info']))
	{
		$info_order_send = mysql_fetch_array(mysql_query("SELECT * FROM `ls_orders` where `id` = '".$_GET['send_payment_info']."';"), MYSQL_ASSOC);
		$due = generate_due($_GET['send_payment_info']);
		if ($due)
		{
			$body_admin .= '<div style="color:red;">'.$lang[486].'</div>';
		} else {
			$body_admin .= '<div style="color:red;">'.$lang[487].'</div>';
		}
		$status_mail = send_message_for_email ($info_order_send['email'], $config['user_params_28'].' - '.$lang[492], $due, 'payment', $_GET['send_payment_info']);
		switch ($status_mail)
		{
			case 0:
				$body_admin .= '<div style="color:red;">'.$lang[488].'</div>';
			break;
			case 1:
				$body_admin .= '<div style="color:green;">'.$lang[489].'</div>';
			break;
			case 2:
				$body_admin .= '<div style="color:red;">'.$lang[490].'</div>';
			break;
		}
	}
	$body_admin .= '
		<div align="center">
		<div id="temporary"></div>
		<h2 id="title">'.$lang[250].'</h2>
		'.$paginator.'
		<div style="float: right;">
		    <div id="idSuccess"></div>
		    <div class="input-append">
              <span class="add-on">Отправить номер кредитки <small>('.$config ['user_params_36'].')</small>:</span><input type="text" class="form-control" id="summaSms" style="width: 50px;" placeholder="сума"><input type="text" id="phoneClient" placeholder="номер телефона"><button class="btn" type="button" onclick="if (confirm(\'Вы уверенны что хотите отправить номер Вашей карточки?\')) { sendSmsWidthCreditCard2(\'phoneClient\', \'idSuccess\');}">Отправить</button>
            </div>
		</div>
		<div style="text-align:left;">
		    <div class="input-append">
              <span class="add-on">Перейти к заказу под №:</span><input type="text" id="idOrder" class="input-mini"><button class="btn" type="button" onclick="searchOneOrder();">перейти</button>
            </div>
		</div>
		<form>
		<table cellspacing="1" class="table table-bordered  table-striped" width="100%">
		 <thead>
		<tr> 
			<th width="25">ID</th> 
			<th>'.$lang[232].'</th> 
			<th>'.$lang[233].'</th>
			<th>'.$lang[234].'</th>
			<th>'.$lang[235].'</th> 
			<th>'.$lang[236].'</th>
			<th>'.$lang[237].'</th>
			<th>'.$lang[78].'</th>
			<th>'.$lang[251].'</th>
			<!--<th>'.$lang[451].'</th>-->
			<th>'.$lang[256].'</th>
			<th>'.$lang[105].'</th>
		</tr> 
		 </thead> 
		  <tbody> 
	';
	foreach ($array_orders as $v)
	{
		$results = mysql_query("SELECT `value` FROM `ls_reference_values_translate` where `id_reference_value` = '".$v['dostavka']."' and `id_lang` = '".$id_online_lang."' limit 0,1;");
		$info_dostavka = mysql_fetch_array($results, MYSQL_ASSOC);	
		switch($v['status'])
		{
			case 0:
				$value_0 = 'selected';
			break;
			case 1:
				$value_1 = 'selected';
			break;
			case 2:
				$value_2 = 'selected';
			break;
			case 3:
				$value_3 = 'selected';
			break;
            case 4:
                $value_4 = 'selected';
                break;
            case 5:
                $value_5 = 'selected';
                break;
            case 6:
                $value_6 = 'selected';
                break;
            case 7:
                $value_7 = 'selected';
                break;
            case 8:
                $value_8 = 'selected';
                break;
		}
		$status = '
		<option value="0" '.$value_0.'>'.$lang[252].'</option>
		<option value="1" '.$value_1.'>'.$lang[253].'</option>
		<option value="2" '.$value_2.'>'.$lang[254].'</option>
		<option value="3" '.$value_3.'>'.$lang[255].'</option>
		<option value="4" '.$value_4.'>Оплачен</option>
		<option value="5" '.$value_5.'>Ожидает оплаты</option>
		<option value="6" '.$value_6.'>Еще думают</option>
		<option value="7" '.$value_7.'>Отменен</option>
		<option value="8" '.$value_8.'>ПКП</option>
		';
		if (!$v['delete'])
		{
			$delete = '<div id="delete_'.$v['id'].'"><img src="'.$config ['site_url'].'images/admin/remove_16.png" onclick="if (confirm (\''.$lang[69].'\')) { del_order (\''.$v['id'].'\');}"></div>';
		} else {
			$delete = '<span style="color:red; font-size:9px;">'.$lang[279].'</span>';
		}
		$sql = "SELECT `id` from `ls_mail_out` where `type_mail` = 'payment' and `id_other` = '".$v['id']."';";
		$number_mail = @mysql_num_rows (mysql_query($sql));
		if ($number_mail)
		{
			$out_success = '<br><a href="index.php?do=mail&action=view_mail_for_payment&id_payment='.$v['id'].'">'.$lang['493'].' '.$number_mail.' '.$lang['494'].'</a>';
		}
        if ($v['buyOneClick']){
            $classBuyOneClick = 'info';
            if (strlen($v['dop_info'])==0){
                $v['dop_info'] = '
                <div class="alert alert-error"><b>Через форму: купить в один клик</b></div>
                ';
            }
        }
        if ($v['status']==0)
            $newOrder = ' error';
		$infoRegion = getOneString("SELECT * FROM `ls_region` WHERE `id` = '".$v['region']."';");
		$infoCity = getOneString("SELECT * FROM `ls_cities` WHERE `id` = '".$v['city']."';");
		$infoWarehouse = getOneString("SELECT * FROM `ls_warehouses` WHERE `id` = '".$v['warehouse']."';");
        switch ($v['mobile']){
            case 0:
                $mobile = '';
                break;
            case 1:
                $mobile = '<div class="alert alert-error"><strong>Заказ с мобильной версии</strong></div>';
                break;
        }
        $onlinePayData = '';
        if ($v['status']==4){
            $classBuyOneClick = 'warning';
            if ($infoLastStatusLiqPay = getOneString("SELECT * FROM `ls_liqpayStatus` WHERE `orderId` = '".$v['id']."' order by `time` DESC;")){
                $onlinePayData = '<div class="alert alert-info">'.liqpayStatus($infoLastStatusLiqPay['status']).'</div>';
            }
        }
        $notCallBack = '';
        if ($v['notCallBack']==1){
            $notCallBack = '<div class="alert alert-info"><strong>Просили не перезванивать</strong></div>';
        }
		$body_admin .= '
			<tr class="'.$classBuyOneClick.' oneTrOrder '.$newOrder.'" id="trBody_'.$v['id'].'">
			<td align="center">'.$v['id'].'</td>
			<td align="center">'.$v['pib'].'</td>
			<td align="center">'.$v['number_phone'].'</td>
			<td align="center">'.$v['email'].'</td>
			<td align="center">'.$v['adress'].$infoRegion['name'].'<br>'.$infoCity['name'].'<br>'.$infoWarehouse['name'].'</td>
			<td align="center">'.$info_dostavka['value'].'</td>
			<td align="center" style="max-width: 400px; overflow: auto;">'.$notCallBack.$mobile.$v['dop_info'].''.$onlinePayData.'</td>
			<td align="center">'.date("d.m.Y H:i:s", $v['time']).'</td>
			<td align="center"><select name="status" id="status_bar_'.$v['id'].'" onchange="save(\''.$v['id'].'\');" style="border:1px solid black;">'.$status.'</status></td>
<!--			<td align="center"><a href="index.php?do=orders&send_payment_info='.$v['id'].'">'.$lang[451].'</a>'.$out_success.'</td>-->
			<td align="center"><img src="'.$config ['site_url'].'images/admin/stock_list_enum-off.png" onclick="list_item(\''.$v['uniq_user'].'\', '.$v['id'].');"></td>
			<td align="center">'.$delete.'</td>
			</tr>
			<tr id="tr_'.$v['id'].'" style="display: none;">
		        <td colspan="11" id="td_'.$v['id'].'"></td>
		    </tr>
			';
		unset ($out_success,$value_0,$value_1,$value_2,$value_3,$value_4,$value_5,$value_6,$value_7,$value_8,$classBuyOneClick, $newOrder);
	}
	$body_admin .= '
		</tbody>
		</table>
		</form>
		'.$paginator.'
		</div>
			<script type="text/javascript">
			$( document ).ready(function() {
              $(".oneTrOrder").click(function(){
                $(".oneTrOrder").removeClass("success");
                $(this).addClass("success");
              });
            });
		function createRequestObject_ls()   
    {  
        try { return new XMLHttpRequest() }  
        catch(e)   
        {  
            try { return new ActiveXObject(\'Msxml2.XMLHTTP\') }  
            catch(e)   
            {  
                try { return new ActiveXObject(\'Microsoft.XMLHTTP\') }  
                catch(e) { return null; }  
            }  
        }  
    }
    function searchOneOrder (){
        window.location.href = "'.$config ['site_url'].'admin/index.php?do=orders&id=" + $("#idOrder").val();
    }
function save(id){
		var temporary = document.getElementById(\'temporary\');  
		var id_select = \'status_bar_\' + id;
		var status_bar = document.getElementById(id_select).value;
		var link = \''.$config ['site_url'].'admin/orders_save_change.php?id=\' + id + \'&status=\' + status_bar;
		var http = createRequestObject_ls();
		if( http )   
		{  
			http.open(\'get\', link);  
			http.onreadystatechange = function ()
			{  
				if(http.readyState == 4)   
				{  
					temporary.innerHTML = http.responseText; 
					setTimeout(clear, 3000);					
				}  
			}  
			http.send(null);      
		}
}
function sendSmsWidthCreditCard2(idField, idSuccess){
    $.ajax({
            type: "POST",
            url: "'.$config ['site_url'].'admin/sendSmsWidthCreditCard.php",
            data: {
                phone: $("#" + idField).val() ,
                suma: $("#summaSms").val(),
                onlyCard: 1,
            },
            dataType: \'html\',
            success: function(result) {
                $("#" + idSuccess).html(result);
            },
        });
}
function sendSmsWidthCreditCard(idField, idSuccess, idOrder){
    $.ajax({
            type: "POST",
            url: "'.$config ['site_url'].'admin/sendSmsWidthCreditCard.php",
            data: {
                phone: $("#" + idField).val() ,
                idOrder: idOrder,
                suma: $("#suma_" + idOrder).val(),
            },
            dataType: \'html\',
            success: function(result) {
                $("#" + idSuccess).html(result);
            },
        });
}
function saveChangeOrder(id){
    var idOrder = id;
    $.ajax({
            type: "POST",
            url: "'.$config ['site_url'].'admin/saveChangeOrder.php",
            data: {
                id: id ,
                ship_date: $("#ship_date_" + id).val(),
                pib: $("#pib_" + id).val(),
                number_phone: $("#number_phone_" + id).val(),
                adress: $("#adress_" + id).val(),
                email: $("#email_" + id).val(),
                numberSkl: $("#numbSkl_" + id).val(),
                admin_note: $("#admin_note_" + id).val(),
                number_declaration: $("#number_declaration_" + id).val(),
                discount: $("#discount_" + id).val(),
                region: $("#region_" + id).val(),
                city: $("#city_" + id).val(),
                warehouse: $("#warehouse_" + id).val(),
            },
            dataType: \'html\',
            success: function(result) {
                $("#successSaveChangeOrder_" + idOrder).html(result);
            },
        });
}
function saveDeclarationAndSendSms(id){
    $.ajax({
            type: "POST",
            url: "'.$config ['site_url'].'admin/saveDeclarationAndSendSms.php",
            data: {
                id: id ,
                ship_date: $("#ship_date_" + id).val(),
                number_declaration: $("#number_declaration_" + id).val(),
            },
            dataType: \'html\',
            success: function(result) {
                $("#successSaveDeclarationAndSendSms_" + id).html(result);
            },
        });
}
function sendInfoSms(id){
    $.ajax({
            type: "POST",
            url: "'.$config ['site_url'].'admin/sendInfoSms.php",
            data: {
                id: id ,
                smsInfo: $("#smsInfo_" + id).val(),
            },
            dataType: \'html\',
            success: function(result) {
                $("#successSendSmsInfo_" + id).html(result);
            },
        });
}
function closeTrOrder(id){
    $("#tr_" + id).hide(250);
    $(\'html, body\').animate({
        scrollTop: $("#trBody_" + id).offset().top
    }, 750);
    return false;
}
function list_item(id, idOrder){
$("#tr_" + idOrder).show(250);
    $("#td_" + idOrder).html("<img src=\"'.$config ['site_url'].'/img/ajax-loader.gif\">");
    $.ajax({
            type: "GET",
            url: "'.$config ['site_url'].'admin/list_item.php",
            data: {
                uniq: id ,
                id: idOrder,
            },
            dataType: \'html\',
            success: function(result) {
                $("#td_" + idOrder).html(result + "<div><a class=\"btn btn-danger\" onclick=\"closeTrOrder(" + idOrder + ");\">ЗАКРЫТЬ ЗАКАЗ</a></div>");
            },
        });
//		var temporary = document.getElementById(\'temporary\');
//		var link = \''.$config ['site_url'].'admin/list_item.php?uniq=\' + id;
//		var http = createRequestObject_ls();
//		if( http )
//		{
//			http.open(\'get\', link);
//			http.onreadystatechange = function ()
//			{
//				if(http.readyState == 4)
//				{
//					temporary.innerHTML = http.responseText;
//				}
//			}
//			http.send(null);
//		}
}
function save_discount(id){
		var tmp = document.getElementById(\'tmp\');  
		var discount = document.getElementById(\'discount\').value;
		var link = \''.$config ['site_url'].'admin/save_discount.php?discount=\' + discount + \'&id=\' + id;
		var http = createRequestObject_ls();
		if( http )   
		{  
			http.open(\'get\', link);  
			http.onreadystatechange = function ()
			{  
				if(http.readyState == 4)   
				{  
					tmp.innerHTML = http.responseText;				
				}  
			}  
			http.send(null);      
		}

		}
function save_ship_date(id){
		var tmp_ship_date = document.getElementById(\'tmp_ship_date\');  
		var ship_date = document.getElementById(\'ship_date\').value;
		var link = \''.$config ['site_url'].'admin/save_ship_date.php?ship_date=\' + ship_date + \'&id=\' + id;
		var http = createRequestObject_ls();
		if( http )   
		{  
			http.open(\'get\', link);  
			http.onreadystatechange = function ()
			{  
				if(http.readyState == 4)   
				{  
					tmp_ship_date.innerHTML = http.responseText;				
				}  
			}  
			http.send(null);      
		}

		}
function save_number_declaration(id){
		var tmp_number_declaration = document.getElementById(\'tmp_number_declaration\');  
		var number_declaration = document.getElementById(\'number_declaration\').value;
		var link = \''.$config ['site_url'].'admin/save_number_declaration.php?number_declaration=\' + number_declaration + \'&id=\' + id;
		var http = createRequestObject_ls();
		if( http )   
		{  
			http.open(\'get\', link);  
			http.onreadystatechange = function ()
			{  
				if(http.readyState == 4)   
				{  
					tmp_number_declaration.innerHTML = http.responseText;				
				}  
			}  
			http.send(null);      
		}

}
function del_element(id, uniq_user){
		var link = \''.$config ['site_url'].'admin/del_item.php?id=\' + id;
		var http = createRequestObject_ls();
		if( http )   
		{  
			http.open(\'get\', link);  
			http.onreadystatechange = function ()
			{  
				if(http.readyState == 4)   
				{  
					list_item(uniq_user);		
				}  
			}  
			http.send(null);      
		}

}
function del_order(id){
		var tmp_number_declaration = document.getElementById(\'delete_\' + id);  
		var link = \''.$config ['site_url'].'admin/del_order.php?id=\' + id;
		var http = createRequestObject_ls();
		if( http )   
		{  
			http.open(\'get\', link);  
			http.onreadystatechange = function ()
			{  
				if(http.readyState == 4)   
				{  
					tmp_number_declaration.innerHTML = http.responseText;
				}  
			}  
			http.send(null);      
		}

}
function user_info_save(id, type){
		var save_successful = document.getElementById(\'save_successful\');  
		var value = document.getElementById(type).value;
		var link = \''.$config ['site_url'].'admin/save_user_info.php?type=\' + type + \'&id=\' + id + \'&value=\' + value;
		var http = createRequestObject_ls();
		if( http )   
		{  
			http.open(\'get\', link);  
			http.onreadystatechange = function ()
			{  
				if(http.readyState == 4)   
				{  
					save_successful.innerHTML = http.responseText;
					setTimeout(clear_info_save, 5000);
				}  
			}  
			http.send(null);      
		}
	}
function clear ()
{
	temporary.innerHTML = \'\';
}
function clear_info_save ()
{
	save_successful.innerHTML = \'\';
}
</script>	
	';
} else {
	$body_admin .= '<div align="center"><h2 id="title">'.$lang[249].'</h2></div>';
}
$body_admin .= '
<b>'.$lang[268].'</b>
		<form action="" method="POST">
		<input type="text" name="date_from" id="date_from" style="border:1px solid black;" size="12">
		<img src="'.$config ['site_url'].'images/admin/1day.png" align="absmiddle" id="date_from_button" style="cursor: pointer; border: 0">
		<input type="text" name="date_to" id="date_to" style="border:1px solid black;" size="12">
		<img src="'.$config ['site_url'].'images/admin/1day.png" align="absmiddle" id="date_to_button" style="cursor: pointer; border: 0">
		<input type="submit" name="submit" value="Ok">
		</form><br>
		<a href="'.$config ['site_url'].'admin/index.php?do=orders"><b>'.$lang[269].'</b></a><br><br>
		<a href="'.$config ['site_url'].'admin/index.php?do=orders&all_orders"><b>'.$lang[270].'</b></a><br><br>
		<a href="'.$config ['site_url'].'admin/index.php?do=orders&only_del"><b>'.$lang[280].'</b></a><br><br>
<script>
    Calendar.setup({
        inputField     :    "date_from",     // id of the input field
        ifFormat       :    "%Y-%m-%d %H:%M",      // format of the input field
        button         :    "date_from_button",  // trigger for the calendar (button ID)
        align          :    "Br",           // alignment
		timeFormat     :    "24",
		showsTime      :    true,
        singleClick    :    true
    });
	 Calendar.setup({
        inputField     :    "date_to",     // id of the input field
        ifFormat       :    "%Y-%m-%d %H:%M",      // format of the input field
        button         :    "date_to_button",  // trigger for the calendar (button ID)
        align          :    "Br",           // alignment
		timeFormat     :    "24",
		showsTime      :    true,
        singleClick    :    true
    });
</script>
</script>
';
?>