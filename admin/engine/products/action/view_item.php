<?php
$other_way .= ' <img src="'.$config ['site_url'].'images/admin/rightarrow.png">'.$lang[185];
if (isset ($_GET['method']))
{
    switch ($_GET['method'])
    {
        case "del":
            $sql = "delete from ls_values_boolean where id_item='".$_GET['id_item']."';";
            mysql_query ($sql);
            $sql = "delete from ls_values_image where id_item='".$_GET['id_item']."';";
            mysql_query ($sql);
            $sql = "delete from ls_values_prices where id_item='".$_GET['id_item']."';";
            mysql_query ($sql);
            $sql = "delete from ls_values_select where id_item='".$_GET['id_item']."';";
            mysql_query ($sql);
            $sql = "delete from ls_values_text where id_item='".$_GET['id_item']."';";
            mysql_query ($sql);
            $sql = "delete from ls_items where id='".$_GET['id_item']."';";
            mysql_query ($sql);
            $sql = "delete from `ls_values_ligament_price_param` where `id_item`='".$_GET['id_item']."';";
            mysql_query ($sql);
            $sql = "delete from `ls_values_ligament_price` where `id_item`='".$_GET['id_item']."';";
            mysql_query ($sql);
            break;
        case "del_all":
            $sql = "delete from ls_values_boolean;";
            mysql_query ($sql);
            $sql = "delete from ls_values_image;";
            mysql_query ($sql);
            $sql = "delete from ls_values_prices;";
            mysql_query ($sql);
            $sql = "delete from ls_values_select;";
            mysql_query ($sql);
            $sql = "delete from ls_values_text;";
            mysql_query ($sql);
            $sql = "delete from ls_items;";
            mysql_query ($sql);
            $sql = "delete from `ls_values_ligament_price_param`;";
            mysql_query ($sql);
            $sql = "delete from `ls_values_ligament_price`;";
            mysql_query ($sql);
            break;
        case "copy":
            $infoItem = mysql_fetch_array(mysql_query("SELECT * FROM `ls_items` where `id` = '".$_GET['id_item']."';"), MYSQL_ASSOC);
            unset($infoItem['id']);
            foreach ($infoItem as $key => $v)
            {
                $name[] = '`'.$key.'`';
                $value[] = "'".mysql_real_escape_string($v)."'";
            }
            mysql_query('INSERT INTO `ls_items` ('.implode(',',$name).') VALUES ('.implode(',',$value).');');
            //INSERT INTO `ls_items` (`id`, `idCopy`, `id_card`, `time`, `visit`, `status`, `price_2`, `select_12`, `select_10`, `select_9`, `select_11`, `select_8`, `select_7`, `select_6`, `select_5`, `text_4`, `text_3`, `text_2`, `text_1`, `select_4`, `select_3`, `select_2`, `select_1`, `price_1`) VALUES
//(1, 0, 1, '1369514784', 0, 1, 174, '', '', NULL, '', 17, '["14","15"]', 10, NULL, '', '', '', 'Кварцовые наручные часы #1', 5, 19, 0x31, '', 87),
            break;

    }
}
if (!isset ($_GET['limit']))
{
    $array_items = return_items_by_card ($_GET['id_card']);
} else {
    $searchField = '';
    if (isset ($_GET['searchField']))
        $searchField = $_GET['searchField'];
    $array_items = return_items_by_card_and_limit ($_GET['id_card'], $_GET['limit'], $searchField);
}
if (!empty($searchField))
    $searchSql = " AND (`searchFied` LIKE '%".mysql_real_escape_string($searchField)."%' OR `text_4` LIKE '%".mysql_real_escape_string($searchField)."%' OR `text_1` LIKE '%".mysql_real_escape_string($searchField)."%' OR `text_9` LIKE '%".mysql_real_escape_string($searchField)."%' OR `text_11` LIKE '%".mysql_real_escape_string($searchField)."%')";
$sql = "SELECT COUNT(*) FROM ls_items where id_card='".intval($_GET['id_card'])."' ".$searchSql." order by id DESC";
$count = getOneString($sql);
$countPage = ceil($count['COUNT(*)']/50);
$paginator = '';
for ($i=1; $i<=$countPage; $i++){
    $paginator .= '<li '.(($i*50-50==intval($_GET['limit'])) ? 'class="active"' : '').'><a style="margin-bottom: 10px;" href="index.php?do=products&action=view_item&id_card='.intval($_GET['id_card']).'&limit='.($i*50-50).((isset ($_GET['searchField']) ? '&searchField='.urlencode($_GET['searchField']) : '')).'">'.$i.'</a></li>';
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
	<h2 id="title" align="center">'.$lang[194].'</h2>
	<div align="left" style="font-size:16px; color:#227399">'.$info_reference['name'].'</div>
	<div style="margin-top:20px; margin-bottom: 20px;">
	    <a href="index.php?do=products&action=view_item&id_card='.intval($_GET['id_card']).'&limit=0" class="btn" style="margin-bottom: 20px;">всі товари</a> 
        <form class="navbar-form" method="GET" action="index.php">
          <input type="hidden" name="do" value="products">
          <input type="hidden" name="limit" value="0">
          <input type="hidden" name="action" value="view_item">
          <input type="hidden" name="id_card" value="'.intval($_GET['id_card']).'">
          <input type="text" class="span2" name="searchField" style="margin-top: 0;" value="'.htmlspecialchars($_GET['searchField']).'">
          <button type="submit" class="btn">Пошук</button>
        </form>
    </div>
	<form action="" method="POST">
	<div align="center">
	<table class="table table-bordered table-striped">
	<thead>
	<tr>
	<th>ID</th>
	<th></th>
	<th>'.$lang[186].'</th>
    <th>Сумістність</th>
    <th>Подарунок</th>
    <th>Інші кольори</th>
	<th>Ціна</th>
	<th>'.$lang[189].'</th>
	<th>Лінк</th>
	<th>'.$lang[187].'</th>
	<th>'.$lang[188].'</th>
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
        $body_admin .= '
		<tr>
		<td '.$status.'><a href="'.$config ['site_url'].'ua/mode/item-'.$v['id'].'.html">'.$v['id'].'</a><br>MS: '.$v['text_9'].'</td>
		<td><a href="'.$config ['site_url'].'upload/userparams/'.getMainImage ($v['id']).'" target="_blank"><img src="'.$imgUrl.'"></a></td>
		<td '.$status.'>'.$v['text_1'].'<br>'.getOneValueText($v['select_2']).' '.getOneValueText($v['select_3']).' '.((!empty(getOneValueText($v['select_6'])) ? '<div style="color:red;">'.getOneValueText($v['select_6']).'</div>' : '')).'<div>'.getOneValueText($v['select_5']).'</div></td>
		<td>
		    '.$listMobile.'
		    <div id="auto_'.$v['id'].'"></div>
		<button class="btn btn-success btn-mini" onclick="addMobilePhone('.$v['id'].'); return false;">додати ['.getOneString("SELECT COUNT(*) FROM `ls_itemToItem` WHERE `idItem` = '".$v['id']."'")['COUNT(*)'].']</button><br>
		</td>
		<td>
		    <button class="btn btn-success btn-mini" onclick="addGiftToItem('.$v['id'].'); return false;">додати ['.getOneString("SELECT COUNT(*) FROM `ls_giftToItem` WHERE `itemId` = '".$v['id']."'")['COUNT(*)'].']</button><br>
        </td>
		<td><button class="btn btn-success btn-mini" onclick="addOtherColor('.$v['id'].'); return false;">додати ['.getOneString("SELECT COUNT(*) FROM `ls_itemToItemOtherColor` WHERE `idItem` = '".$v['id']."'")['COUNT(*)'].']</button><br></td>
		<td align="center">'.$v['price_1'].' грн.</td>
		<td align="center" '.$status.'><a href="#" onClick="window.open(\'add_image.php?id='.$_GET['id_card'].'&id_item='.$v['id'].'\', \'_blank\', \'Toolbar=0, Scrollbars=1, Resizable=0, Width=640, resize=no, Height=480\');"><img src="'.$config ['site_url'].'images/admin/folder_images.png" border="0"></a></td>
		<td align="center"><a href="'.getItemLink($v).'" target="_blank"><i class="icon-globe"></i></a></td>
		<td align="center" '.$status.'><a href="index.php?do=products&action=edit_item&id='.$v['id'].'"><img src="'.$config ['site_url'].'images/admin/edit.png" border="0"></a></td>
		<td align="center" '.$status.'><a href="index.php?do=products&action=view_item&id_card='.$_GET['id_card'].'&method=del&id_item='.$v['id'].'&limit='.$_GET['limit'].'" onclick="return confirm (\''.$lang[69].'\')"><img src="'.$config ['site_url'].'images/admin/remove_16.png" border="0"></td>
		</tr>
		';
        unset ($listMobile);
    }
    $body_admin .= '<tr><td colspan="11">';
    if (!isset ($_GET['limit']) or $_GET['limit']==0)
    {
        $body_admin .= '<div style="float:right;"><a href="index.php?do=products&action=view_item&id_card='.$_GET['id_card'].'&limit='.($_GET['limit']+50).((isset ($_GET['searchField']) ? '&searchField='.urlencode($_GET['searchField']) : '')).'"><img src="'.$config ['site_url'].'images/admin/1rightarrow_32.png"></a></div>';
    } else {
        $body_admin .= '<div style="float:right;"><a href="index.php?do=products&action=view_item&id_card='.$_GET['id_card'].'&limit='.($_GET['limit']+50).((isset ($_GET['searchField']) ? '&searchField='.urlencode($_GET['searchField']) : '')).'"><img src="'.$config ['site_url'].'images/admin/1rightarrow_32.png"></a></div>';
        $body_admin .= '<div style="float:left;"><a href="index.php?do=products&action=view_item&id_card='.$_GET['id_card'].'&limit='.($_GET['limit']-50).((isset ($_GET['searchField']) ? '&searchField='.urlencode($_GET['searchField']) : '')).'"><img src="'.$config ['site_url'].'images/admin/1leftarrow_32.png"></a></div>';
    }
    $body_admin .= '
	</td></tr>
	<tr>
        <td colspan="11">
            <div class="pagination"><ul>'.$paginator.'</ul></div></td>	
    </tr>
		</tbody>
            </table>
            <div id="modalHtml"></div>
		';
} else {
    $body_admin .= '
	<h2 align="center">'.$lang[192].'</h2>    
	';
}
?>