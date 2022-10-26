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
        <b>'.$lang[636].'</b>
        '.$error_message.'
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <form class="contact-form-style" id="contact-form" action="" method="post">
                <div class="row">
                    <div class="col-lg-12">
                        <input name="name" value="'.$_POST['name'].'"  placeholder="Ім\'я" type="text">
                    </div>
                    <div class="col-lg-12">
                        <input name="email" value="'.$_POST['email'].'" placeholder="Email*" type="email">
                    </div>
                    <div class="col-lg-12">
                        <input name="phone" value="'.$_POST['phone'].'" placeholder="Телефон" type="text">
                    </div>
                    <div class="col-lg-12">
                        <textarea name="body" placeholder="'.$lang[640].'"></textarea>
                        <button class="btn btn-primary" type="submit">Надіслати</button>
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