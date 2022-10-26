<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 5/15/15
 * Time: 1:05 PM
 * To change this template use File | Settings | File Templates.
 */
require ('config.php');
function send_message_for_email ($mail_to, $subject, $body_mail)
{
    require_once ('lib/smtp.php');
    require_once ('lib/sasl.php');
    global $config;
    $smtp=new smtp_class;
    $from=$config['email_admin'];  /* адрес отправителя */  $sender_line=__LINE__;
    $to=$mail_to;  /* адрес получателя */ $recipient_line=__LINE__;
    $smtp->host_name=$config['smtp_host'];
    /* smtp сервер через который будет отправляться почта */
    $smtp->host_port=$config['smtp_port'];
    /* порт smtp сервера */
    $smtp->ssl=$config['ssl'];
    /* большая часть опций была описана выше */
    $smtp->start_tls=0;
    $smtp->localhost="localhost";
    $smtp->timeout=60;
    $smtp->data_timeout=0;
    $smtp->debug=0;
    /* полезно для отладки - лог ответов smtp сервера */
    $smtp->html_debug=1;
    /* форматировать ответ в HTML */
    $smtp->pop3_auth_host="";
    /* некоторые почтовые службы требуют вместо аутентификации на smtp сервере
    залогиниться на pop3 */

    $smtp->user=$config['email_admin'];
    /* логин пользователя */
    $smtp->realm="";
    /* домен почтового сервера */
    $smtp->password=$config['email_password'];
    /* пароль */
    $smtp->workstation="";
    /* используется только для NTLM аутентификации */
    $smtp->authentication_mechanism="";
    /* укажите метод SASL аутентификации
    (LOGIN, PLAIN, CRAM-MD5, NTLM и т.д.) ..
     если не знаете - оставьте пустым */

    if($smtp->SendMessage(
        $from,
        array(
            $to
        ),
        array(
            "From: $from",
            "To: $to",
            "Content-type:text/html;charset=UTF8",
            "Subject: ".$subject,
            "Date: ".strftime("%a, %d %b %Y %H:%M:%S %Z")
        ), $body_mail))
    {
        $smtp->error."\n";
        return (1);
    } else {
        $smtp->error."\n";
        return(0);
    }
}
//function go_sms ($to, $message)
//{
////    exit();
//    global $alphasms_login, $alphasms_password, $alphasms_user_send;
//    $request = 'http://alphasms.com.ua/api/http.php?version=http&login='.$alphasms_login.'&password='.$alphasms_password.'&command=send&from='.$alphasms_user_send.'&to='.$to.'&message='.str_replace (' ', '%20', $message);
//    file_get_contents ($request);
//}
//if (strlen($_POST['phone'])==13){
//    go_sms ('+380932280708', 'CALLBACK: '.htmlspecialchars($_POST['phone']));
//    echo '
//    <div class="alert alert-success" style="padding: 6px;">
//        <strong>Дякуємо!</strong> З Вами зв\'яжеться наш менеджер!
//    </div>
//    ';
//}
$bodyMail = '
    Клієнт: '.htmlspecialchars($_POST['name']).'<br>
    E-Mail: '.htmlspecialchars($_POST['email']).'<br>
    <strong>Повідомлення</strong><br>
    '.htmlspecialchars($_POST['message']).'
    ';
send_message_for_email ('info@picase.com.ua', 'Заявка з сайту', $bodyMail);
echo '
<div class="alert alert-success" style="padding: 6px;">
    <strong>Спасибо!</strong> Ваше сообщение успешно отправлено!
</div>
';
