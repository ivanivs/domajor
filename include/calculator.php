<?php
if (isset ($_POST['price']) and strlen($_POST['price'])!=0)
{
    mysql_query("SET NAMES `UTF8`;");
    $sql = "
    INSERT INTO  `ls_calculator` (
    `phone` ,
    `price` ,
    `priceUSA` ,
    `weight` ,
    `links` ,
    `comment` ,
    `time`
    )
    VALUES (
    '".mysql_real_escape_string($_POST['phone'])."' ,
    '".mysql_real_escape_string($_POST['price'])."' ,
    '".mysql_real_escape_string($_POST['priceUSA'])."' ,
    '".mysql_real_escape_string($_POST['weight'])."' ,
    '".mysql_real_escape_string(json_encode($_POST['link']))."' ,
    '".mysql_real_escape_string(json_encode($_POST['comment']))."' ,
    '".time()."'
    );

    ";
    //print_r (json_decode(stripslashes(mysql_real_escape_string(json_encode($_POST['comment'])))));
    if (mysql_query($sql))
    {
        $body .= '<div class="good">Заявка успешно отправлена, менеджер свяжется с Вами в ближайшее время</div>';
//        go_sms ($config['user_params_4'], 'Новая заявка на доставку из США');
    } else {
        $body .= '<div class="bad">Ошибка добавления заявки.</div>';
    }
} else {
    $body .= '
    <div class="calculatorPage">
        <h1>Калькулятор доставки из США</h1>
        <div>
            <p>В этом разделе вы можете посчитать стоимость доставки кроссовок или
            любого другого товара из США.</p>
            
            <p>Стоимость доставки включает в себя 15% от стоимости товара
            в США(вместе с доставкой по США) + 15 доларов за 1 килограм веса.
            Минимальный вес заказа будет считатся 0,5 килограма.</p>
            
            <p>Например, если в США кроссовки стоят 100 доларов и доставка по
            США бесплатная(в большинстве случаев), то в Украине они Вам выйдут:
            100$+15%=115$ + 1,5(средний вес 1 пары кроссовок в коробке)*15$=22,5$. В
            сумме 115+22,5=137,5 долара.</p>
            
            <p><strong>Для заказа нужно перечислить нам всего 50% полной стоимости
            товара в Украине</strong>, вторую половину вы сможете оплатить, например,
            наложенным платежем в отделении любой курьерской службы(Новая Почта
            и т.д).</p>
            
            <p><strong>Если Вы скидываете полную предоплату, вы получаете скидку 5%
            от полной стоимости товара в США!</strong></p>
            
            <p>Для оформления заказа, нажмите <strong>“Сделать заказ”</strong>, заполните все
            поля и дальше – <strong>“Отправить менеджеру”</strong>. В ближайшее время мы с Вами
            свяжемся и уточним все детали.</p>
            
            <p>По поводу гарантий, Вы можете прочитать в разделе «О нас»</p>
        </div>
        <form action="" method="POST">
        <table class="calculator">
            <tr>
                <td>Стоимость товара (ов):</td>
                <td><input type="text" id="price" name="price"> USD</td>
            </tr>
            <tr>
                <td>Общий вес:</td>
                <td><input type="text" id="weight" name="weight"> Kg.</td>
            </tr>
            <tr>
                <td>Общая стоимость доставки по США:</td>
                <td><input type="text" id="priceUSA" name="priceUSA"> USD</td>
            </tr>
            <tr class="calculatorResult">
                <td><span style="font-size: 16px;">В Украине:</span></td>
                <td><div id="priceResult">0 USD</div></td>
            </tr>
            <tr class="calculatorButton">
                <td><div class="addToCart btn btn-danger" onclick="calculatorResult();">Подсчитать</div></td>
                <td></td>
            </tr>
            <tr class="calculatorOrder">
                <td>Список ссылок на товары:</td>
                <td>
                    <div class="linkCalc">1. <input type="text" style="width: 250px;" placeholder="ссылка на товар" class="commentInput" name="link[]"> <input type="text" style="width: 250px;" placeholder="ваш комментарий к этому товару" class="commentInput" name="comment[]"> <img src="'.$config['site_url'].'images/admin/remove_16.png" class="removeLink"></div>
                    <div class="linkCalc">2. <input type="text" style="width: 250px;" placeholder="ссылка на товар" class="commentInput" name="link[]"> <input type="text" style="width: 250px;" placeholder="ваш комментарий к этому товару" class="commentInput" name="comment[]"> <img src="'.$config['site_url'].'images/admin/remove_16.png" class="removeLink"></div>
                    <div class="linkCalc">3. <input type="text" style="width: 250px;" placeholder="ссылка на товар" class="commentInput" name="link[]"> <input type="text" style="width: 250px;" placeholder="ваш комментарий к этому товару" class="commentInput" name="comment[]"> <img src="'.$config['site_url'].'images/admin/remove_16.png" class="removeLink"></div>
                    <div class="moreLink"></div>
                    <div class="addLinkCalc">добавить еще...</div>
                </td>
            </tr>
            <tr class="calculatorOrder">
                <td>Ваш телефон:</td>
                <td><input type="text" id="phone" name="phone" value="+38"></td>
            </tr>
            <tr class="calculatorOrder">
                <td colspan="2"><input type="submit" name="submit" class="addToCart btn btn-success" value="Отправить менеджеру"></td>
            </tr>
        </table>
        </form>
    </div>
    <script>
    var numbLink = 4;
    $(document).ready(function() {
        $(".addLinkCalc").click(function(){
            $(".moreLink").append(\'<div class="linkCalc">\' + numbLink + \'. <input type="text" style="width: 250px;" placeholder="ссылка на товар" class="commentInput" name="link[]"> <input type="text" style="width: 250px;" placeholder="ваш комментарий к этому товару" class="commentInput" name="comment[]"> <img src="'.$config['site_url'].'images/admin/remove_16.png" class="removeLink"></div>\');
            numbLink++;
                $(".removeLink").click(function(){
                $(this).parent(".linkCalc").remove();
            });
        });
    });
    </script>
    ';
}
$title = "Доставка из США, заказ с Ebay, покупка в США";
$keywords = 'заказ товаров из США, доставка из США, покупка на ebay, покупка в американских интернет магазинах';
$description = 'Заказ товаров из США, низкие цены, через нас Вы можете совершать покупки в интернет магазинах США, аукционах и т.д. Доставка по всей територии Украины.';
?>