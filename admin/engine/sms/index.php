<?php
$body_admin .= '
<h3>СМС с номером декларации</h3>
<div id="successSendNumberDeclaration"></div>
<div class="row">
    <div class="span3"><input type="text" id="declarationPhone" placeholder="Номер телефона"></div>
    <div class="span2"><input type="text" id="declarationShipDate" placeholder="Дата получения" class="input-small"></div>
    <div class="span3"><input type="text" id="declarationNumber" placeholder="Номер декларации"></div>
    <div class="span3"><button class="btn btn-success" onclick="sendSmsNumberDeclaration();">Отправить смс</button></div>
</div>
<h3>СМС розсилка</h3>
<div class="row">
    <div class="span5">
        Включить в рассылку смс зарегестрированых пользователей:
    </div>
    <div class="span4">
        <select id="registerUser">
            <option value="1">Да</option>
            <option value="0">Нет</option>
        </select>
    </div>
</div>
<div class="row">
    <div class="span5">
        Включить в рассылку смс пользователей сделавших заказ:
    </div>
    <div class="span4">
        <select id="orderUser">
            <option value="1">Да</option>
            <option value="0">Нет</option>
        </select>
    </div>
</div>
<div class="row">
    <div class="span5">
        <div style="font-weight: bold;">Текст смс</div>
        <textarea id="bodySms" style="width: 400px; height: 50px;"></textarea>
        Количество символов: <div id="colSymb" style="display: inline-block; color: red; font-weight: bold;">0</div>
    </div>
</div>

<button class="btn btn-success" onclick="getNumbers();">Получить номера</button>
<div id="resultBlock"></div>
<script>
    function sendSmsNumberDeclaration(){
        if ($("#declarationPhone").val().length>0){
            if ($("#declarationShipDate").val().length>0){
                if ($("#declarationNumber").val().length>0){
                    $.ajax({
                    type: "POST",
                    url: "'.$config ['site_url'].'admin/sendSmsNumberDeclaration.php",
                    data: {
                        declarationPhone: $("#declarationPhone").val() ,
                        declarationShipDate: $("#declarationShipDate").val() ,
                        declarationNumber: $("#declarationNumber").val() ,
                    },
                    dataType: \'html\',
                    success: function(result) {
                        $("#successSendNumberDeclaration").html(result);
                    },
                });
                } else {
                    alert("Номер декларации не может быть пустой");
                }
            } else {
                alert("Дата получения не может быть пустая");
            }
        } else {
            alert("Номер получателя не может быть пустым");
        }
    }
    $(function() {
      $("#bodySms").keyup(function(){
        $("#colSymb").html($("#bodySms").val().length);
      });
    });
    function sendSms(){
        if ($("#bodySms").val().length>0){
            if ($("#bodySms").val().length<70){
                sendSmsAjax(0);
            } else {
                if (confirm(\'СМС должна быть латиницей, или отправиться несколько смс каждому клиенту!\')){
                    sendSmsAjax(0);
                } else {
                    alert ("Вы отменили рассылку!");
                }
            }
        } else {
            alert ("Пустая смс!")
        }
    }
    function sendSmsAjax(i){
        if($("#number_" + i).length) {
            $.ajax({
                    type: "POST",
                    url: "'.$config ['site_url'].'admin/sendSmsAjax.php",
                    data: {
                        number: $("#number_" + i).html() ,
                        message: $("#bodySms").val(),
                    },
                    dataType: \'html\',
                    success: function(result) {
                        $("#sendSms_" + i).html(result);
                        $("#sendSmsCount").html(parseInt($("#sendSmsCount").html())+1);
                        $("#tr_number_" + i).addClass("success");
                        if (i<$("#maxId").val()){
                            i++;
                            sendSmsAjax(i);
                        }
                    },
                });
        } else {
            if (i<$("#maxId").val()){
                i++;
                sendSmsAjax(i);
            }
        }
    }
    function getPrice(){
        getPriceAjax(0);
    }
    function getPriceAjax(i){
        $.ajax({
                type: "POST",
                url: "'.$config ['site_url'].'admin/getPrice.php",
                data: {
                    number: $("#number_" + i).html() ,
                },
                dataType: \'html\',
                success: function(result) {
//                    $("html, body").animate({
//                        scrollTop: $("#number_" + i).offset().top
//                    }, 200);
                    $("#price_" + i).html(result);
                    if (result==0){
                        $("#tr_number_" + i).addClass("error");
                        $("#del_number_" + i).html($("#number_" + i).html());
                        $("#number_" + i).remove();
                        $("#notValid").html(parseInt($("#notValid").html())+1);
                    } else {
                        if (parseFloat(result)<parseFloat($("#minimalPrice").html()) || parseFloat($("#minimalPrice").html())==0){
                            $("#minimalPrice").html(parseFloat(result).toFixed(2))
                        }
                        if (parseFloat(result)>parseFloat($("#maximalPrice").html())){
                            $("#maximalPrice").html(parseFloat(result).toFixed(2))
                        }
                        $("#priceNumberCount").html(parseInt($("#priceNumberCount").html())+1);
                    }
                    $("#allPrice").html(parseFloat(Number($("#allPrice").html())+parseFloat(result)).toFixed(2));
                    if (i<$("#maxId").val()){
                        i++;
                        getPriceAjax(i);
                    }
                },
            });
    }
    function getNumbers(){
        $("#resultBlock").html("<img src=\"'.$config ['site_url'].'/img/ajax-loader.gif\">");
        $.ajax({
                type: "POST",
                url: "'.$config ['site_url'].'admin/getNumbers.php",
                data: {
                    registerUser: $("#registerUser").val() ,
                    orderUser: $("#orderUser").val() ,
                },
                dataType: \'html\',
                success: function(result) {
                    $("#resultBlock").html(result);
                },
            });
    }
</script>
';