<?php
if (isset ($_POST['name'])){
    if (mysql_query("INSERT INTO `ls_searchSystem` (`name`, `status`) VALUES ('".mysql_real_escape_string($_POST['name'])."', '0');")){
        $success = '<div class="alert alert-success">Успішно збережено!</div>';
    } else {
        $success = '<div class="alert alert-danger">Помилка збереження!</div>';
    }
}
$body_admin .= '
<h2>Створення нового унікалізованого пошуку</h2>
'.$success.'
<form action="" method="POST">
    <table class="table table-bordered table-striped">
        <tr>
            <td>Назва пошуку:</td>
            <td><input type="text" class="form-control" name="name"></td>    
        </tr>
        <tr>
            <td colspan="2"><input type="submit" class="btn btn-success" value="Зберегти"></td>    
        </tr>
    </table>
</form>
';