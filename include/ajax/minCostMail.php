<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 9/29/17
 * Time: 3:37 PM
 * To change this template use File | Settings | File Templates.
 */
if (isset ($_POST['phone'])){
    send_message_for_email ($config['user_params_22'], 'Нашли дешевле?', 'Телефон: '.htmlspecialchars($_POST['phone']).'<br>'.'Лінк: '.htmlspecialchars($_POST['link']), 'minCost', '');
}