<?php
if (!isset ($body))
$body = '';
if (isset ($_POST['name']))
{
    if (strlen($_POST['name'])>0 and strlen($_POST['email'])>0)
    {
        $body_mail = $lang[629].': '.$_POST['name'].'<br>E-mail: '.$_POST['email'].'<br>';
        if (strlen($_POST['phone'])>0)
        {
            $body_mail .= $lang[630].': '.$_POST['phone'].'<br>';
        }
        $body_mail .= $lang[631].':<br>'.$_POST['body'];
        $sql = "
        INSERT INTO `ls_mail_out` (`subject`, `to`, `body_mail`, `type_mail`, `id_other`) VALUES
        ('Сайт - форма обратной связи', '".mysql_real_escape_string($_POST['email'])."', '".mysql_real_escape_string($body_mail)."', 'feedback', 0);
        ";
        mysql_query($sql);
        send_message_for_email ($config['user_params_22'], $lang[632], $body_mail, 'contact', '');
        $error_message = '<div style="color:green; font-weight:bold; font-size:16px;">'.$lang[633].'</div>';
        unset ($_POST);
    } else {
        $error_message = '<div style="color:red; font-weight:bold;">'.$lang[634].'</div>';
    }
}
$title = $lang[635];
$info_contact = mysql_fetch_array(mysql_query("SELECT * FROM `ls_contact_card` where `id` = '".intval($_GET['id'])."';"), MYSQL_ASSOC);
if (!isset ($error_message))
$error_message = '';
if (!isset ($_POST['name']))
$_POST['name'] = '';
if (!isset ($_POST['email']))
$_POST['email'] = '';
if (!isset ($_POST['phone']))
$_POST['phone'] = '';
if (!isset ($_POST['body']))
$_POST['body'] = '';
if ($info_contact['feedback'])
{
    $contactForm = '
        <h3>'.$lang[636].'</h3>
        '.$error_message.'
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 mt-3">
            <form class="contact-form-style" id="contact-form" action="" method="post">
                <div class="row">
                    <div class="col-lg-4 mt-2 mb-2">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" id="name"  name="name" placeholder="Ім\'я" value="'.$_POST['name'].'">
                          <label for="name">Ім\'я</label>
                        </div>                    
                    </div>
                    <div class="col-lg-4 mt-2 mb-2">
                        <div class="form-floating mb-3">
                          <input type="email" class="form-control" id="email"  name="email" placeholder="Email" value="'.$_POST['email'].'">
                          <label for="email">Email</label>
                        </div>                    
                    </div>
                    <div class="col-lg-4 mt-2 mb-2">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" id="phone"  name="phone" placeholder="Телефон" value="'.$_POST['phone'].'">
                          <label for="phone">Телефон</label>
                        </div>                    
                    </div>
                    <div class="col-12 mt-2 mb-2">
                        <div class="form-floating">
                          <textarea class="form-control" placeholder="'.$lang[640].'" id="body" name="body" style="height: 100px"></textarea>
                          <label for="body">Повідомлення</label>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <button class="btn btn-primary" type="submit" class="btn btn-success">Надіслати</button>                    
                    </div>
                </div>
            </form>
            <p class="form-messege"></p>
        </div>
    ';
}
$info_translate_contact = mysql_fetch_array(mysql_query("SELECT `text` FROM `ls_translate` where `id_lang` = '".$id_online_lang."' and `type` = 'contact_card_te' and `id_elements` = '1';"), MYSQL_ASSOC);
if (strlen($info_translate_contact['text'])>0)
{
    $contact = '
    <h3 style="margin:0; padding:0;">'.$lang[642].'</h3>
    '.$info_translate_contact['text'].'
    ';
}
$body .= '
<div class="container" style="margin-top: 50px; min-height: 600px;">
    <div class="row">
        '.$contactForm.'
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
            '.$contact.'
        </div>
    </div>
</div>
';
?>