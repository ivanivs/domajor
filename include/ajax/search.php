<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 4/6/16
 * Time: 10:41 AM
 * To change this template use File | Settings | File Templates.
 */
if (isset ($_GET['q'])){
    $arrayOut['incomplete_results'] = 'false';
    $sql ="
        SELECT
            COUNT(*)
        FROM
            `ls_items`
        WHERE
        (
            `text_1` LIKE '%".mysql_real_escape_string(strtolower($_GET['q']))."%'
        or
            `text_2` LIKE '%".mysql_real_escape_string(strtolower($_GET['q']))."%'
        or
            `text_4` LIKE '%".mysql_real_escape_string(strtolower($_GET['q']))."%'
        or
            `searchField` LIKE '%".mysql_real_escape_string(strtolower($_GET['q']))."%'
        )
        AND (`text_7` != 0 or `text_12` != 0)
        ;
        ";
    $infoItemCount = getOneString($sql);
    $arrayOut['total_count'] = $infoItemCount['COUNT(*)'];
    if (!isset ($_GET['page'])){
        $limitStart = 0;
    } else {
        $limitStart = intval($_GET['page'])*30-30;
    }
    $sql = "
        SELECT * FROM
            `ls_items`
        WHERE
        (
            `text_1` LIKE '%".mysql_real_escape_string(strtolower($_GET['q']))."%'
        or
            `text_2` LIKE '%".mysql_real_escape_string(strtolower($_GET['q']))."%'
        or
            `text_4` LIKE '%".mysql_real_escape_string(strtolower($_GET['q']))."%'
        or
            `searchField` LIKE '%".mysql_real_escape_string(strtolower($_GET['q']))."%')
            AND  (`text_7` != 0 or `text_12` != 0)
         ORDER by `visit` DESC
         LIMIT ".$limitStart.", 30;";
    if ($arrayItems = getArray($sql)){
        foreach ($arrayItems as $key => $v){
            $items[$key]['id'] = $v['id'];
            $items[$key]['link'] = generateItemLink ($v['id']);
            $items[$key]['avatar_url'] = getImgSoroka($v);
            $items[$key]['name'] = $v['text_1'].' <div>'.getOneValueText($v['select_1']).' '.$v['text_4'].'</div>';
            if (isset($sizeHtml) and count($sizeHtml)>0 and strlen($sizeHtml[0])>0) {
//                $items[$key]['desc'] = 'Доступные размеры: <div style="font-weight: bold;">Доступные размеры</div>' . implode(' | ', $sizeHtml);
            } else {
                $items[$key]['desc'] = '';
            }
            $items[$key]['full_name'] = $v['text_1'].'<div>'.getOneValueText($v['select_1']).' '.$v['text_4'].'</div>';
            if ($v['price_2']!=0 and $v['price_2']!=$v['price_1'])
            {
                $items[$key]['price'] = '<div class="priceItem"><span style="text-decoration: line-through; color:#7c7c7c; font-size: 16px;"><span>'.$v['price_1'].'</span></span> <span class="priceNow"><strong>'.$v['price_2'].' грн.</strong></span></div>';
            } else {
                $items[$key]['price'] = '<div class="priceItem">'.$v['price_1'].' грн.</div>';
            }
            unset ($size, $sizeHtml);
        }
    }
    $arrayOut['items'] = $items;
    header('Content-Type: application/json');
    echo json_encode($arrayOut);
}