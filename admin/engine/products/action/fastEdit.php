<?php
$body_admin .= '
<h3>Швидке редагування</h3>
<form class="form-search" action="index.php?do=products&action=fastEdit&id_card='.intval($_GET['id_card']).'&limit=0" method="GET">
  <input type="text" class="input-medium search-query" name="searchField" value="'.htmlspecialchars($_GET['searchField']).'">
  <input type="hidden" name="action" value="fastEdit">
  <input type="hidden" name="do" value="products">
  <input type="hidden" name="id_card" value="'.intval($_GET['id_card']).'">
  <input type="hidden" name="limit" value="'.intval($_GET['limit']).'">
  <button type="submit" class="btn">Пошук</button>
</form>
';
if (isset ($_POST['ids'])){
    $ids = $_POST['ids'];
    foreach ($ids as $key => $oneItemId){
        $select_6 = $_POST['select_6'][$key];
        $select_9 = $_POST['select_9'][$key];
        $select_10 = $_POST['select_10'][$key];
        $text_7 = $_POST['text_7'][$key];
        $price_2 = $_POST['price_2'][$key];
        $price_1 = $_POST['price_1'][$key];
        $text_9 = $_POST['text_9'][$key];
        mysql_query("UPDATE `ls_items` SET 
                        `select_6` = '".mysql_real_escape_string($select_6)."',
                        `select_9` = '".mysql_real_escape_string($select_9)."',
                        `select_10` = '".mysql_real_escape_string($select_10)."',
                        `text_7` = '".mysql_real_escape_string($text_7)."',
                        `price_2` = '".mysql_real_escape_string($price_2)."',
                        `price_1` = '".mysql_real_escape_string($price_1)."',
                        `text_9` = '".mysql_real_escape_string($text_9)."'
                    WHERE `id` = '".$oneItemId."'
        ");
    }
}
if (!isset ($_GET['limit']))
{
    $array_items = return_items_by_card ($_GET['id_card']);
} else {
    if (!isset ($_GET['searchField']))
        $_GET['searchField'] = '';
    $array_items = return_items_by_card_and_limit ($_GET['id_card'], $_GET['limit'], $_GET['searchField']);
}
$info_cardparam_name = return_cardparam_text_one_position($_GET['id_card']);
if ($array_items)
{
    if (!isset ($info_reference['name']))
        $info_reference['name'] = '';
    $body_admin .= '
	<style>
	.translate_form { 
	 font-size: 10px; 
	 background-color: #C2ECFF; 
	 border: 1px solid #666666; 
	}
	</style>
	<form action="" method="POST">
	<div align="center">
	<table class="table table-bordered table-striped">
	<thead>
	<tr>
	<th>ID</th>
	<th>Назва</th>
	<th>MINI SOFT</th>
	<th>Ціна</th>
	<th>Ціна зі знижкою</th>
	<th>Кількість</th>
	<th>Відправка</th>
	<th>Акція</th>
	<th>Merchant</th>
	<th>Лінк</th>
	</tr> 
	</thead> 
	<tbody>
	';
    foreach ($array_items as $v)
    {
        if (!$v['status'])
        {
            $status = 'style="background-color:#FFE8E8;"';
        } else {
            $status = '';
        }
        if ($arrayMobile = getArray("SELECT * FROM `ls_markModel` WHERE `idItem` = '".$v['id']."';")){
            $listMobile = '<ul>';
            foreach ($arrayMobile as $oneMobile){
                $mark = getOneValue($oneMobile['mark']);
                $model = getOneValue($oneMobile['model']);
                $listMobile .= '<li id="markModel_'.$oneMobile['id'].'">'.$mark['text'].' '.$model['text'].' <i class="icon-remove" style="cursor: pointer; color: red;" onclick="removeMarkModel('.$oneMobile['id'].');"></i></li>';
            }
            $listMobile .= '</ul>';
        }

        $imgUrl = $config ['site_url'].'resize_image.php?filename='.urlencode(getMainImage($v['id'])).'&const=12&width=100&height=100&r=255&g=255&b=255';
        $optionSelect6 = '';
        if ($arraySelect6 = getValuesSelectParam(6)){
            foreach ($arraySelect6 as $oneValue6){
                $optionSelect6 .= '<option value="'.$oneValue6['id'].'" '.(($oneValue6['id']==$v['select_6']) ? 'selected' : '').'>'.$oneValue6['text'].'</option>';
            }
        }
        $optionSelect9 = '';
        if ($arraySelect9 = getValuesSelectParam(9)){
            foreach ($arraySelect9 as $oneValue9){
                $optionSelect9 .= '<option value="'.$oneValue9['id'].'" '.(($oneValue9['id']==$v['select_9']) ? 'selected' : '').'>'.$oneValue9['text'].'</option>';
            }
        }
        $optionSelect10 = '';
        if ($arraySelect10 = getValuesSelectParam(10)){
            foreach ($arraySelect10 as $oneValue10){
                $optionSelect10 .= '<option value="'.$oneValue10['id'].'" '.(($oneValue10['id']==$v['select_10']) ? 'selected' : '').'>'.$oneValue10['text'].'</option>';
            }
        }
        $body_admin .= '
		<tr>
            <td '.$status.'>'.$v['id'].'<input type="hidden" value="'.$v['id'].'" name="ids[]"></td>
            <td '.$status.'>'.$v['text_1'].'<br>'.getOneValueText($v['select_1']).' '.$v['text_4'].'</td>
            <td '.$status.'><input type="text" class="form-control" name="text_9[]" value="'.$v['text_9'].'"></td>
            <td align="center"><input type="text" class="form-control" name="price_1[]" value="'.$v['price_1'].'"></td>
            <td align="center"><input type="text" class="form-control" name="price_2[]" value="'.$v['price_2'].'"></td>
            <td align="center"><input type="text" class="form-control" name="text_7[]" value="'.$v['text_7'].'"></td>
            <td align="center">
                <select class="form-control" name="select_6[]">
                    '.$optionSelect6.'            
                </select>
            </td>
            <td align="center">
                <select class="form-control" name="select_9[]">
                    '.$optionSelect9.'            
                </select>
            </td>
            <td align="center">
                <select class="form-control" name="select_10[]">
                    '.$optionSelect10.'            
                </select>
            </td>
            <td align="center"><a href="'.$config['site_url'].'ru/mode/item-'.$v['id'].'.html" target="_blank"><i class="icon-globe"></i></a></td>
		</tr>
		';
        unset ($listMobile);
    }
    $body_admin .= '
    <tr>
        <td colspan=107" style="text-align: center;"><input type="submit" value="Зберегти" class="btn btn-large btn-success"></td>    
    </tr>
    <tr><td colspan=10">';
    if (!isset ($_GET['limit']) or $_GET['limit']==0)
    {
        $body_admin .= '<div style="float:right;"><a href="index.php?do=products&action=fastEdit&id_card='.$_GET['id_card'].'&limit='.($_GET['limit']+50).'"><img src="'.$config ['site_url'].'images/admin/1rightarrow_32.png"></a></div>';
    } else {
        $body_admin .= '<div style="float:right;"><a href="index.php?do=products&action=fastEdit&id_card='.$_GET['id_card'].'&limit='.($_GET['limit']+50).'"><img src="'.$config ['site_url'].'images/admin/1rightarrow_32.png"></a></div>';
        $body_admin .= '<div style="float:left;"><a href="index.php?do=products&action=fastEdit&id_card='.$_GET['id_card'].'&limit='.($_GET['limit']-50).'"><img src="'.$config ['site_url'].'images/admin/1leftarrow_32.png"></a></div>';
    }
    $body_admin .= '
	</td></tr>
		</tbody>
            </table>
            </form>
            <div id="modalHtml"></div>
		';
} else {
    $body_admin .= '
	<h2 align="center">'.$lang[192].'</h2>    
	';
}