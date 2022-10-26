var main_sait_url = 'https://sorokavorona.eu/';
var loading = "<center><img src=\"" + main_sait_url + "images/admin/loading_ref.gif\"></center>";
function change_main_photo(to_change)
{
    var main_image = document.getElementById('main_image');
    main_image.src = to_change;
}
function view_vart()
{
    var cont = document.getElementById('korzina');
    cont.innerHTML = loading;  
    link = main_sait_url + "include/cart_left_menu.php";
    var query = "";
    var http = createRequestObject();
    if( http )   
    {  
        http.open('GET', link);
        http.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

        http.onreadystatechange = function ()   
        {  
            if(http.readyState == 4)   
            {
                cont.innerHTML = http.responseText;
            }  
        }  
        http.send(null);      
    }  
    else   
    {  
        document.location = link;  
    }
}
view_vart();
function addcart_with_other_param(id)
{
    var cont = document.getElementById('add_to_cart_' + id);
    var param_0 = document.getElementById('param_0').value;
    var param_1 = document.getElementById('param_1').value;
    cont.innerHTML = loading;  
    link = main_sait_url + "include/cart_add_item.php";
    var query = "id_item=" + id + "&param[]=" + param_0 + "&param[]=" + param_1;
    var http = createRequestObject();  
    if( http )   
    {  
        http.open('post', link);
        http.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        http.send(query);
        http.onreadystatechange = function ()   
        {  
            if(http.readyState == 4)   
            {  
                cont.innerHTML = http.responseText;
                view_vart();
            }  
        }  
        http.send(null);      
    }  
    else   
    {  
        document.location = link;  
    }
}
function checking_search_form()
{
    searchitem = document.getElementById('search_string');
    if (searchitem.value.length<3)
    {
        alert ('Поисковый запрос должен быть длинее 3 символов.');
        return false;
    } else {
        return true;
    }
}
function addcart(id)
{
    var cont = document.getElementById('add_to_cart_' + id);
    cont.innerHTML = loading;
    link = main_sait_url + "include/cart_add_item.php";
    var query = "id_item=" + id;
    var http = createRequestObject();  
    if( http )   
    {  
        http.open('post', link);
        http.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        http.send(query);
        http.onreadystatechange = function ()   
        {  
            if(http.readyState == 4)   
            {
                view_vart();
                setTimeout(view_vart, 500)
                cont.innerHTML = http.responseText;
            }  
        }  
        http.send(null);      
    }  
    else   
    {  
        document.location = link;  
    }
}
function createRequestObject()
{
if (XMLHttpRequest == undefined) {
XMLHttpRequest = function() {
try { return new ActiveXObject("Msxml2.XMLHTTP.6.0"); }
catch(e) {}
try { return new ActiveXObject("Msxml2.XMLHTTP.3.0"); }
catch(e) {}
try { return new ActiveXObject("Msxml2.XMLHTTP"); }
catch(e) {}
try { return new ActiveXObject("Microsoft.XMLHTTP"); }
catch(e) {}
throw new Error("This browser does not support XMLHttpRequest.");
};
}
return null;
}