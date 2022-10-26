<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 11/17/17
 * Time: 7:13 PM
 * To change this template use File | Settings | File Templates.
 */
$bodyMail = '
<div><strong>Телефон:</strong> '.htmlspecialchars($_POST['phone']).'</div>
<a href="'.htmlspecialchars($_POST['link']).'">'.htmlspecialchars($_POST['link']).'</a>
';
send_message_for_email($config['user_params_22'], 'Нашли дешевле?', $bodyMail, 'minCost');