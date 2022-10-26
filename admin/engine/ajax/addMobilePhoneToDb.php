<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/14/15
 * Time: 5:40 PM
 * To change this template use File | Settings | File Templates.
 */
if (mysql_query("INSERT INTO  `ls_markModel` (
`idItem` ,
`mark` ,
`model`
)
VALUES (
'".intval($_POST['id'])."' ,
'".intval($_POST['mark'])."' ,
'".intval($_POST['model'])."'
);")){
    echo '
    <div class="alert alert-success">
        Успешно!
    </div>
    ';
} else {
    echo '
    <div class="alert alert-error">
        Ошибка!
    </div>
    ';
}