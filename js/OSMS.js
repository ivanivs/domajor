var main_site = 'https://' + location.host + '/';
$(document).ready(function() {
    $("#doRegister").click(function(){
        if (checkRegisterForm())
        {
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: main_site + 'include/ajax/register.php',
                data: {
                    login: $("#phone").val(),
                    password: $("#password").val()
                },
                success: function(result) {
                    $(".register").html(result);
                },
            });
        }
    });
    $("#loginInSiteButton").click(function(){
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: main_site + 'include/ajax/loginInSite.php',
            data: {
                login: $("#loginInSite").val(),
                password: $("#passwordInSite").val()
            },
            success: function(result) {
                if (result==1)
                {
                    window.location.replace(main_site);
                } else {
                    alert ("Логин или пароль не верный!");
                }
            },
        });
    });
    $("#doChangePass").click(function(){
        checkPassword();
    });
});
function newPassAndConfirmPass(){
    if ($("#password").val()==$("#passwordConfirm").val())
    {
        $("#incorrectPass").hide(250);
        return(1);
    } else {
        $("#incorrectPass").show(250);
        return(0);
    }
}
function emptyPass(){
    if ($("#password").val().length==0)
    {
        $("#emptyPassword").show(250);
        return(0);
    } else {
        $("#emptyPassword").hide(250);
        return(1);
    }
}
function checkPassword(){
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'include/ajax/checkUserPassword.php',
        data: {
            password: $("#oldPassword").val()
        },
        success: function(result) {
            if (result!=1)
            {
                $("#incorrectPassword").show(250);
                return(0);
            } else {
                $("#incorrectPassword").hide(250);
                if (emptyPass() & newPassAndConfirmPass()){
                    $.ajax({
                        type: 'POST',
                        dataType: 'html',
                        url: main_site + 'include/ajax/changePassword.php',
                        data: {
                            password: $("#password").val()
                        },
                        success: function(result) {
                            $(".register").html(result);
                        },
                    });
                }
            }
        },
    });
}
function checkRegisterForm()
{
    var check = 0;
    if ($("#phone").val().length==0)
    {
        check = 1;
        $("#emptyLogin").show(250);
    } else {
        $("#emptyLogin").hide(250);
    }
    if ($("#password").val() != $("#passwordConfirm").val())
    {
        check = 1;
        $("#incorrectPass").show(250);
    } else {
        $("#incorrectPass").hide(250);
    }
    if ($("#password").val().length==0)
    {
        $("#emptyPassword").show(250);
        check = 1;
    } else {
        $("#emptyPassword").hide(250);
    }
    if (check!=1)
    {
        return(1);
    } else {
        return(0);
    }
}
function test(){

}
function searchWithParam()
{
    var array = [];
    var j = 0;
    $(".param").each(function(i, elem){
        if ($(this).val()==1)
        {
//            alert ($(this).val());
            array[j] = ($(this).attr("name"));
            j = j + 1;
        }
    });
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'include/ajax/getItemsCount.php',
        data: {
            array: array,
            category: $("#categoryId").val()
        },
        success: function(result) {
            if (result.length==0)
            {

            } else {
                $("#fixedBlock").html(result);
                $("#pageAjax").val(1);
            }
        },
    });
}
function closeFixedBlock()
{
    $("#fixedBlock").hide(100);
}
function viewItems()
{
    $(".blockItems").css("opacity", '0.3');
    var array = [];
    var j = 0;
    $(".param").each(function(i, elem){
        if ($(this).val()==1)
        {
            if ($(this).prop('checked')==true) {
                array[j] = ($(this).attr("name"));
                j = j + 1;
            }
        }
    });
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: main_site + 'include/ajax/getItems.php',
        data: {
            array: array,
            sort: $("#sortAjax").val(),
            page: $("#pageAjax").val(),
            typeSort: $("#askDesc").val(),
            searchField: $("#searchField").val(),
        },
        success: function(result) {
            $("#leftFiltr").html(result['filtr']);
            $(".blockItems").html(result['data']);
            $("#fixedBlock").hide(100);
            $(".blockItems").css("opacity", '1');
            $('[data-toggle="tooltip"]').tooltip();
            $(".classStyle li").click(function() {
                $(this).toggleClass("activeFiltrElement");
                if ($(this).find("input").val()==0)
                {
                    $(this).find("input").val(1);
                } else {
                    $(this).find("input").val(0);
                }
                viewItems();
                goTo("allItems");
            });
            $(".classSize div").click(function() {
                $(this).toggleClass("sizeFiltrButtonActive");
                if ($(this).find("input").val()==0)
                {
                    $(this).find("input").val(1);
                } else {
                    $(this).find("input").val(0);
                }
                viewItems();
                goTo("allItems");
            });
        },
    });
}
function sortSorokaVorona(){
    var ts = $("#sortSorokaVorona").val();
    if (ts=='dateAsk'){
        $("#sortAjax").val('date');
        changeTypeSort(0);
    } else {
        if (ts=='dateDesc'){
            $("#sortAjax").val('date');
            changeTypeSort(1);
        } else {
            if (ts=='popularAsk'){
                $("#sortAjax").val('popular');
                changeTypeSort(0);
            } else {
                if (ts=='popularDesc'){
                    $("#sortAjax").val('popular');
                    changeTypeSort(1);
                } else {
                    if (ts=='priceAsk'){
                        $("#sortAjax").val('price');
                        changeTypeSort(0);
                    } else {
                        if (ts=='priceDesc'){
                            $("#sortAjax").val('price');
                            changeTypeSort(1);
                        }
                    }
                }
            }
        }
    }
}
function changeSort(type)
{
    $("#sortAjax").val(type);
    viewItems();
}
function changeTypeSort(type){
    $("#askDesc").val(type);
    viewItems();
}
function changePage(page)
{
    $("#pageAjax").val(page);
    viewItems();
    if (main_site=='m.og-shop.in.ua'){
        goTo("allItems");
    }
}
function getCartBlock ()
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'include/ajax/cartBlock.php',
        success: function(result) {
            $(".fieldCartBlock").html(result);
        },
    });
}
function changeSortType(id)
{
    if (id==1){
        $("#changeSort_1").hide();
        $("#changeSort_0").show();
    } else {
        $("#changeSort_1").show();
        $("#changeSort_0").hide();
    }
    $("#askDesc").val(id);
    viewItems();
    if (main_site=='m.slam.city'){
        goTo("allItems");
    }
}
function calculatorResult()
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'include/ajax/calculatorResult.php',
        data: {
            price: $("#price").val(),
            weight: $("#weight").val(),
            priceUSA: $("#priceUSA").val()
        },
        success: function(result) {
            $("#priceResult").html(result);
            $(".calculatorResult").show(250);
        },
    });
}
function addMobilePhone(id)
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'admin/index.php?do=ajax&ajax=addMobilePhone',
        data: {
            id: id,
        },
        success: function(result) {
            $("#modalHtml").html(result);
            $("#addMobilePhoneModal").modal("show");
        },
    });
}
function addGiftToItem(id)
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'admin/index.php?do=ajax&ajax=addGiftToItem',
        data: {
            id: id,
        },
        success: function(result) {
            $("#modalHtml").html(result);
            $("#addGiftToItemModal").modal("show");
        },
    });
}
function addGiftToItemId(id)
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'admin/index.php?do=ajax&ajax=addGiftToItemId',
        data: {
            id: id,
            giftId: $("#giftId").val(),
            price: $("#giftPrice").val(),
            dateFrom: $("#dateFrom").val(),
            dateTo: $("#dateTo").val(),
        },
        success: function(result) {
            $("#modalSuccess").html(result);
        },
    });
}
function addOtherColor(id)
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'admin/index.php?do=ajax&ajax=addOtherColor',
        data: {
            id: id,
        },
        success: function(result) {
            $("#modalHtml").html(result);
            $("#addOtherColorModal").modal("show");
        },
    });
}
function getModelPhone(id)
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'admin/index.php?do=ajax&ajax=getModelPhone',
        data: {
            id: $("#mark").val(),
        },
        success: function(result) {
            $("#model").html(result);
        },
    });
}
function addMobilePhoneToDb(id)
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'admin/index.php?do=ajax&ajax=addMobilePhoneToDb',
        data: {
            id: id,
            mark: $("#mark").val(),
            model: $("#model").val(),
        },
        success: function(result) {
            location.reload();
        },
    });
}
function addMobilePhoneAuto(id)
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'admin/index.php?do=ajax&ajax=addMobilePhoneAuto',
        data: {
            id: id,
        },
        success: function(result) {
            $("#auto_" + id).append(result);
        },
    });
}
function removeMarkModel(id)
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'admin/index.php?do=ajax&ajax=removeMarkModel',
        data: {
            id: id,
        },
        success: function(result) {
            $("#markModel_" + id).remove();
        },
    });
}
function orderWithUSA()
{
    $(".calculatorButton").hide(250);
    $(".calculatorOrder").show(250);
}
function sendFile(file, editor, welEditable) {
    data = new FormData();
    data.append("file", file);
    url = main_site + "uploadFile.php";
    $.ajax({
        data: data,
        type: "POST",
        url: url,
        cache: false,
        contentType: false,
        processData: false,
        success: function (url) {
            editor.insertImage(welEditable, url);
        }
    });
}
function viewNews(){
    $( "#news" ).animate({
        height: "toggle"
    }, 500, function() {
        // Animation complete.
    });
}
function viewSearch(){
    $( ".searchBlock" ).animate({
        height: "toggle"
    }, 150, function() {
        $("#searchField").focus();
        $("#searchField:text:visible:first").focus();
    });
}
function getCity(){
    $.ajax({
        type: "POST",
        url: main_site + "index.php?mode=ajax&ajax=getCity",
        data: {
            region: $("#region").val(),
        },
        dataType: 'html',
        success: function(result) {
            $("#city").html(result);
        },
    });
}
function getWarehouse(st){
    $.ajax({
        type: "POST",
        url: main_site + "index.php?mode=ajax&ajax=getWarehouse",
        data: {
            city: $("#city").val(),
            type: st,
        },
        dataType: 'html',
        success: function(result) {
            if (st == 0) {
                $("#warehouse").html(result);
            } else {
                $("#warehousePoshtomat").html(result);
            }
        },
    });
}
function getCityAdmin(id){
    $.ajax({
        type: "POST",
        url: main_site + "index.php?mode=ajax&ajax=getCity",
        data: {
            region: $("#region_" + id).val(),
        },
        dataType: 'html',
        success: function(result) {
            $("#city_" + id).html(result);
        },
    });
}
function getWarehouseAdmin(id){
    $.ajax({
        type: "POST",
        url: main_site + "index.php?mode=ajax&ajax=getWarehouse",
        data: {
            city: $("#city_" + id).val(),
        },
        dataType: 'html',
        success: function(result) {
            $("#warehouse_" + id).html(result);
        },
    });
}
function registration(){
    var stop = 0;
    if ($("#phoneReg").val().length!=13){
        $("#phoneRegTr").addClass('danger');
        $("#phoneRegTr").addClass('has-error');
        $("#phoneRegError").show(300);
        stop = 1;
    } else {
        $("#phoneRegTr").removeClass('danger');
        $("#phoneRegTr").removeClass('has-error');
        $("#phoneRegError").hide(300);
    }
    if ($("#passwordReg").val() != $("#passwordConfirm").val()){
        $("#passwordRegTr").addClass('danger');
        $("#passwordRegTr").addClass('has-error');
        $("#passwordConfirmTr").addClass('danger');
        $("#passwordConfirmTr").addClass('has-error');
        $("#passwordNotConfirm").show(300);
        stop = 1;
    } else {
        $("#passwordRegTr").removeClass('danger');
        $("#passwordRegTr").removeClass('has-error');
        $("#passwordConfirmTr").removeClass('danger');
        $("#passwordConfirmTr").removeClass('has-error');
        $("#passwordNotConfirm").hide(300);
    }
    if ($("#passwordReg").val().length<6){
        $("#passwordRegTr").addClass('danger');
        $("#passwordRegTr").addClass('has-error');
        $("#passwordRegTrErrorShort").show(300);
        stop = 1;
    } else {
        $("#passwordRegTr").removeClass('danger');
        $("#passwordRegTr").removeClass('has-error');
        $("#passwordRegTrErrorShort").hide(300);
    }
    if ($("#nameReg").val().length==0){
        $("#nameRegTr").addClass('danger');
        $("#nameRegTr").addClass('has-error');
        $("#nameRegError").show(300);
        stop = 1;
    } else {
        $("#nameRegTr").removeClass('danger');
        $("#nameRegTr").removeClass('has-error');
        $("#nameRegError").hide(300);
    }
    if ($("#surNameReg").val().length==0){
        $("#surNameRegTr").addClass('danger');
        $("#surNameRegTr").addClass('has-error');
        $("#surNameRegError").show(300);
        stop = 1;
    } else {
        $("#surNameRegTr").removeClass('danger');
        $("#surNameRegTr").removeClass('has-error');
        $("#surNameRegError").hide(300);
    }
    if ($("#region").val()==0){
        $("#regionRegTr").addClass('danger');
        $("#regionRegTr").addClass('has-error');
        $("#regionRegError").show(300);
        stop = 1;
    } else {
        $("#regionRegTr").removeClass('danger');
        $("#regionRegTr").removeClass('has-error');
        $("#regionRegError").hide(300);
    }
    if ($("#city").val()==0){
        $("#cityRegTr").addClass('danger');
        $("#cityRegTr").addClass('has-error');
        $("#cityRegError").show(300);
        stop = 1;
    } else {
        $("#cityRegTr").removeClass('danger');
        $("#cityRegTr").removeClass('has-error');
        $("#cityRegError").hide(300);
    }
    if (stop==0){
        $.ajax({
            type: "POST",
            url: main_site + "index.php?mode=ajax&ajax=register",
            data: {
                uid: $("#uid").val(),
                birthday: $("#birthday").val(),
                image: $("#image").val(),
                phone: $("#phoneReg").val(),
                password: $("#passwordReg").val(),
                name: $("#nameReg").val(),
                emailReg: $("#emailReg").val(),
                surNameReg: $("#surNameReg").val(),
                regionReg: $("#region").val(),
                city: $("#city").val(),
                warehouse: $("#warehouse").val(),
            },
            dataType: 'html',
            success: function(result) {
                if (result==1){
                    $("#register").hide(300);
                    $("#successRegister").show(300);
                } else {
                    if (result==0){
                        $("#errorUserExist").show(300);
                    } else {
                        $("#errorRegister").show(300);
                    }
                }
            },
        });
    }
}
function loginInSite2(){
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'index.php?mode=ajax&ajax=loginInSite',
        data: {
            login: $("#loginInSite").val(),
            password: $("#passwordInSite").val(),
            inSite: 1,
        },
        success: function(result) {
            if (result==1){
                location.reload();
            } else {
                $("#errorLogin").show(300);
            }
        },
    });
}
function saveUserInfo(){
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'index.php?mode=ajax&ajax=saveUserInfo',
        data: {
            name: $("#name").val(),
            surName: $("#surName").val(),
            email: $("#email").val(),
            birthday: $("#birthday").val(),
            region: $("#region").val(),
            city: $("#city").val(),
            warehouse: $("#warehouse").val(),
        },
        success: function(result) {
            if (result==1){
                $("#successSave").show(300);
            } else {
            }
        },
    });
}
function savePassword(){
    if ($("#password").val().length>=6){
        $("#passwordTr").removeClass('danger');
        $("#passwordTr").removeClass('has-error');
        $("#confirmPasswordTr").removeClass('danger');
        $("#confirmPasswordTr").removeClass('has-error');
        $("#passwordLengthError").hide(300);
        if ($("#password").val()==$("#confirmPassword").val()){
            $("#passwordTr").removeClass('danger');
            $("#passwordTr").removeClass('has-error');
            $("#confirmPasswordTr").removeClass('danger');
            $("#confirmPasswordTr").removeClass('has-error');
            $("#passwordNotConfirmError").hide(300);
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: main_site + 'index.php?mode=ajax&ajax=savePassword',
                data: {
                    password: $("#password").val(),
                    oldPassword: $("#oldPassword").val()
                },
                success: function(result) {
                    if (result==1){
                        $("#tableChangePassword").hide();
                        $("#successSavePassword").show(300);
                    } else {
                        $("#errorOldPass").show();
                    }
                },
            });
        } else {
            $("#passwordTr").addClass('danger');
            $("#passwordTr").addClass('has-error');
            $("#confirmPasswordTr").addClass('danger');
            $("#confirmPasswordTr").addClass('has-error');
            $("#passwordNotConfirmError").show(300);
        }
    } else {
        $("#passwordTr").addClass('danger');
        $("#passwordTr").addClass('has-error');
        $("#confirmPasswordTr").addClass('danger');
        $("#confirmPasswordTr").addClass('has-error');
        $("#passwordLengthError").show(300);
    }
}
jQuery(function($) {
    getCartBlock();
});
function getCartBlock ()
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'index.php?mode=ajax&ajax=getCart',
        success: function(result) {
            $(".fieldCartBlock").html(result);
        },
    });
}
function checkDostavka(){
    if ($("#pib").val().length==0){
        $("#pibTr").addClass('danger');
        $("#pibTr").addClass('has-error');
        $("#pibError").show(300);
    } else {
        $("#pibTr").removeClass('danger');
        $("#pibTr").removeClass('has-error');
        $("#pibError").hide(300);
        if ($("#phoneOrder").val().length==0){
            $("#phoneOrderError").show(300);
            $("#phoneOrderTr").addClass('danger');
            $("#phoneOrderTr").addClass('has-error');
        } else {
            $("#phoneOrderError").hide(300);
            $("#phoneOrderTr").removeClass('danger');
            $("#phoneOrderTr").removeClass('has-error');
            var warehouse = 0;
            if ($("#warehouse").val()!=0){
                warehouse = $("#warehouse").val();
            }
            if ($("#warehousePoshtomat").val()!=0){
                warehouse = $("#warehousePoshtomat").val();
            }
            console.log(warehouse);
            console.log($("#warehouse").val());
            console.log($("#warehousePoshtomat").val());
            if ($('#notCallBack').prop('checked')==true){
                var notCallBack = 1;
            } else {
                var notCallBack = 0;
            }
            if ($("#region").val()!=0 && $("#city").val()!=0 && (warehouse!=0 || $("#adress").val().length!=0) || $("#dostavka").val()==7){
                $("#submitOrderForm").prop("disabled",true);
                $.ajax({
                    type: "POST",
                    url: main_site + "index.php?mode=ajax&ajax=saveOrder",
                    data: {
                        pib: $("#pib").val(),
                        phoneOrder: $("#phoneOrder").val(),
                        email: $("#email").val(),
                        region: $("#region").val(),
                        city: $("#city").val(),
                        warehouse: warehouse,
                        oplata: $("#oplata").val(),
                        dop_info: $("#dop_info").val(),
                        promocode: $("#promocode").val(),
                        dostavka: $("#dostavka").val(),
                        adress: $("#adress").val(),
                        notCallBack: notCallBack,
                    },
                    dataType: 'html',
                    success: function(result) {
                        $("#cartBody").html(result);
                    },
                });
            } else {
                $("#adressError").show(300);
            }
        }
    }
}
function checkTerms(){
    if ($("#confirmTerms").prop( "checked" )){
        $("#submitOrderForm").attr("disabled", false);
    } else {
        $("#submitOrderForm").attr("disabled", true);
    }
}
function goTo(id){
    // Scroll
//    $('html,body').animate({
//            scrollTop: $("#"+id).offset().top},
//        "slow");
    $('html,body').animate({scrollTop: $("#"+id).offset().top - 50}, '500', 'swing', function() {
    });
    generateUrl();
}
function showHideFiltr(cls){
    $("." + cls).toggle(200);
    $("#icon_" + cls).toggleClass("fa-plus-square-o");
    $("#icon_" + cls).toggleClass("fa-minus-square-o");
}
var searchOpen = 0;
function searchLine(){
    if (menu==1){
        $(".topMenuChild").animate({
            height: 0
        }, 500, function() {
            $(".topMenuLi").removeClass("activeTopMenuLi");
            menu = 0;
        });
    }
    if (searchOpen==0){
        $(".select2-search__field").focus();
        $(".select2-search__field:text:visible:first").focus();
        $(".searchLine").animate({
            height: "54px"
        }, 500, function() {
            searchOpen = 1;
        });
    } else {
        $(".searchLine").animate({
            height: "0px"
        }, 500, function() {
            searchOpen = 0;
        });
    }
}
function goSearch(){
    location.replace("https://sorokavorona.eu/ru/shop/Search/?search=" + $("#searchVar").val());
}
function getNewPassword(){
    $.ajax({
        type: "POST",
        url: main_site + "index.php?mode=ajax&ajax=getNewPassword",
        data: {
            phone: $("#phoneNumber").val(),
        },
        dataType: 'html',
        success: function(result) {
            $("#successGetNewPass").html(result);
        },
    });
}
function callMe(){
    $("#buttonCallMe").hide();
    $("#callMe").show();
}
function callMeSend(){
    if ($("#phoneCallMe").val().length<10){
        alert("Введіть номер телефону");
    } else {
        $.ajax({
            type: "POST",
            url: main_site + "index.php?mode=ajax&ajax=phoneCallMe",
            data: {
                phone: $("#phoneCallMe").val(),
            },
            dataType: 'html',
            success: function(result) {
                $("#successSendCallMe").html(result);
            },
        });
    }
}
function sendReview(id){
    var stop = 0;
    if ($("#fieldReview").val().length==0){
        $("#reviewEmpty").show(200);
        stop = 1;
    } else {
        $("#reviewEmpty").hide(300);
    }
    if ($("#reviewName").val().length==0){
        $("#nameEmpty").show(300);
        $("#reviewName").css('border', '1px solid red');
        stop = 1;
    } else {
        $("#nameEmpty").hide(300);
        $("#reviewName").css('border', '1px solid #ccc');
    }
    if ($("input[name=rating]:checked").val()==undefined){
        $("#ratingEmpty").show(300);
    } else {
        $("#ratingEmpty").hide(300);
    }
    if (stop==0){
        $(".errorReview").remove();
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: main_site + 'index.php?mode=ajax&ajax=sendReview',
            data: {
                id: id,
                name: $("#reviewName").val(),
                body: $("#fieldReview").val(),
                rating: $("input[name=rating]:checked").val(),
            },
            success: function(result) {
                $("#reviewName").val("");
                $("#fieldReview").val("");
                $("#bodyReviews").prepend(result);
            },
        });
    }
}
function sendAnswer(idQuestion, id){
    var stop = 0;
    if ($("#answer_" + idQuestion).val().length==0){
        $("#answer_" + idQuestion).css('border', '1px solid red');
        stop = 1;
    }
    if (stop==0){
        $(".errorReview").remove();
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: main_site + 'index.php?mode=ajax&ajax=sendReview',
            data: {
                id: id,
                idQuestion: idQuestion,
                body: $("#answer_" + idQuestion).val(),
                answer: 1,
            },
            success: function(result) {
                location.reload();
            },
        });
    }
}
function removeReview(id){
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'index.php?mode=ajax&ajax=removeReview',
        data: {
            id: id,
        },
        success: function(result) {
            $("#review_" + id).remove();
        },
    });
}
function getModalWithShortLink(url){
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'index.php?mode=ajax&ajax=getModalWithShortLink',
        data: {
            url: url,
        },
        success: function(result) {
            $("#modalShort").html(result);
            $("#modalShortLink").modal("show");
        },
    });
}
function insertParam(key, value) {
    key = escape(key); value = escape(value);

    var kvp = document.location.href.substr(1).split('?');
    setLocation('?' + key + '=' + value);
    console.log('?' + key + '=' + value);
    return('?' + key + '=' + value);
    if (kvp == '') {
//        history.pushState(null, null,  '?' + key + '=' + value);
//        document.location.href = '?' + key + '=' + value;
        setLocation('?' + key + '=' + value);
        console.log('?' + key + '=' + value);
        return('?' + key + '=' + value);
    }
    else {

        var i = kvp.length; var x; while (i--) {
            x = kvp[i].split('=');

            if (x[0] == key) {
                x[1] = value;
                kvp[i] = x.join('=');
                break;
            }
        }

        if (i < 0) { kvp[kvp.length] = [key, value].join('='); }

        //this will reload the page, it's likely better to store this until finished
//        document.location.href = kvp.join('&');
        var newUrl = 'h' + kvp.join('&');
        newUrl = newUrl.replace('%5B', '[');
        newUrl = newUrl.replace('%5D', ']');
        setLocation('h' + kvp.join('&'));
        console.log('h' + kvp.join('&'));
        return('h' + kvp.join('&'));
    }
}
//function ChangeUrl(page, url) {
//    if (typeof (history.pushState) != "undefined") {
//        var obj = {Page: page, Url: url};
//        history.pushState(obj, obj.Page, obj.Url);
//    } else {
//        window.location.href = "homePage";
//        // alert("Browser does not support HTML5.");
//    }
//}

function setLocation(curLoc){
    try {
        history.pushState(null, null, urldecode(curLoc));
        return;
    } catch(e) {}
    location.hash = '#' + curLoc;
}
function urldecode(str) {
    return decodeURIComponent((str+'').replace(/\+/g, '%20'));
}
function generateUrl(){
    var array = [];
    var arrayValue = [];
    var param;
    var j = 0;
    var kvp = document.location.href.substr(1).split('?');
    setLocation('h' + kvp[0]);
    $(".param").each(function(i, elem){
        if ($(this).prop("checked")==true)
        {
//            alert ($(this).val());
            array[j] = ($(this).attr("name"));
            param = array[j].split("|");
            arrayValue[j] = param[2];
            // insertParam("select[" + param[1] + "][]", param[2]);
            j = j + 1;
        }
    });
    insertParam("p", arrayValue.join(','));
}
function addRemoveItemToCodeDiscount(id, itemId, st)
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'admin/index.php?do=ajax&ajax=addRemoveItemToCodeDiscount',
        data: {
            id: id,
            itemId: itemId,
            st: st,
        },
        success: function(result) {
            if (st==1){
                $("#add_" + itemId).hide();
                $("#remove_" + itemId).show();
            } else {
                $("#add_" + itemId).show();
                $("#remove_" + itemId).hide();
            }
        },
    });
}
function addItemToItem(id)
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'admin/index.php?do=ajax&ajax=addItemToItem',
        data: {
            id: id,
            array: $("#bodyAddItemToItem").val(),
        },
        success: function(result) {
            $("#modalSuccess").html(result);
        },
    });
}
function addItemToCart(id)
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'include/ajax/addItemToCart.php',
        data: {
            idItem: id
        },
        success: function(result) {
            $("#addToCartModalBody").html(result);
            $("#addToCartModal").modal("show");
            getCartBlock();
        },
    });
}
function addItemToItemOtherColor(id)
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'admin/index.php?do=ajax&ajax=addItemToItemOtherColor',
        data: {
            id: id,
            array: $("#bodyAddItemToItem").val(),
        },
        success: function(result) {
            $("#modalSuccess").html(result);
        },
    });
}
function removeItemToItem(id)
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'admin/index.php?do=ajax&ajax=removeItemToItem',
        data: {
            id: id,
        },
        success: function(result) {
            $("#iti_" + id).hide(200);
        },
    });
}
function removeGiftToItem(id)
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'admin/index.php?do=ajax&ajax=removeGiftToItem',
        data: {
            id: id,
        },
        success: function(result) {
            $("#iti_" + id).hide(200);
        },
    });
}
function removeItemToItemOtherColor(id)
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'admin/index.php?do=ajax&ajax=removeItemToItemOtherColor',
        data: {
            id: id,
        },
        success: function(result) {
            $("#iti_" + id).hide(200);
        },
    });
}
function getModalLoadingHeader(id)
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'admin/index.php?do=ajax&ajax=getModalLoadingHeader',
        data: {
            id: id,
        },
        success: function(result) {
            $("#modalSearch").html(result);
            $("#modalSearhBody").modal('show');
        },
    });
}
function getColors(id)
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'index.php?mode=ajax&ajax=getColors',
        data: {
            id: id,
            size: $("#size").val(),
        },
        success: function(result) {
            $("#color").html(result);
        },
    });
}
function addItemToCartWithVerification(id)
{
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: main_site + 'index.php?mode=ajax&ajax=addItemToCartWithVerification',
        data: {
            id: id,
            size: $("#size").val(),
            color: $("#color").val(),
            number: $("#number").val(),
        },
        success: function(result) {
            $("#addToItemHtml").html(result);
            $("#addToCartModalNew").modal("show");
            getCartBlock ();
        },
    });
}
function getItemByColor(id)
{
    // if ($("#color").val()!=$("#thisColor").val()) {
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: main_site + 'index.php?mode=ajax&ajax=getItemByColor',
            data: {
                id: id,
                size: $("#size").val(),
                color: $("#color").val(),
            },
            success: function (result) {
                location.href = result;
            },
        });
    // }
}
function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $temp.remove();
}
function clickItem(productObj) {
    dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
    dataLayer.push({
        'event': 'productClick',
        'ecommerce': {
            'click': {
                'actionField': {'list': 'Search Results'},      // Optional list property.
                'products': [{
                    'name': productObj.name,                      // Name or ID is required.
                    'id': productObj.id,
                    'price': productObj.price,
                    'brand': productObj.brand,
                    'category': productObj.category,
                }]
            }
        },
        'eventCallback': function() {
            document.location = productObj.url
        }
    });
}
function addToCartGA(productObj){
    dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
    dataLayer.push({
        event: "add_to_cart",
        ecommerce: {
            items: [{
                item_name: productObj.name, // Name or ID is required.
                item_id: productObj.id,
                price: productObj.price,
                item_brand: productObj.brand,
                item_category: productObj.category,
                index: 1,
                quantity: productObj.quantity
            }]
        }
    });
}