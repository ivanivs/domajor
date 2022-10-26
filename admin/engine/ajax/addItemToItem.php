<?php
if (isset ($_POST['array'])){
    echo $_POST['array'];
    $arrayItems = explode("\n", $_POST['array']);
    foreach ($arrayItems as $oneItem){
        if ($infoItem = getArray("SELECT * FROM `ls_items` WHERE `id` = '".trim($oneItem)."';")){
            if (!$info = getOneString("SELECT * FROM `ls_itemToItem` WHERE `idItem` = '".intval($_POST['id'])."' AND `idItem2` = '".intval($oneItem)."'")) {
                mysql_query("INSERT INTO `ls_itemToItem` (`idItem`, `idItem2`) VALUES ('" . intval($_POST['id']) . "', '" . intval($oneItem) . "');");
            }
        }
    }
    echo generateItemToItemTable(intval($_POST['id']));
}