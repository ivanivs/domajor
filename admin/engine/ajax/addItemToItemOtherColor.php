<?php
if (isset ($_POST['array'])){
    echo $_POST['array'];
    $arrayItems = explode("\n", $_POST['array']);
    foreach ($arrayItems as $oneItem){
        if ($infoItem = getArray("SELECT * FROM `ls_items` WHERE `id` = '".trim($oneItem)."';")){
            if (!$info = getOneString("SELECT * FROM `ls_itemToItemOtherColor` WHERE `idItem` = '".intval($_POST['id'])."' AND `idItem2` = '".intval($oneItem)."'")) {
                mysql_query("INSERT INTO `ls_itemToItemOtherColor` (`idItem`, `idItem2`) VALUES ('" . intval($_POST['id']) . "', '" . intval($oneItem) . "');");
            }
        }
    }
    echo generateItemToItemTableOtherColor(intval($_POST['id']));
}