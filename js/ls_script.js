var loading = "<center><img src=\"/images/admin/ajax-loader.gif\"></center>";
function load_child_param(id_card_param, id_child, id_father, id_online_lang)
{
    var cont = document.getElementById('select_' + id_child);
    var id_value_parent_id = document.getElementById('select_' + id_father).value;
    cont.innerHTML = loading;  
    link = "engine/ajax/load_child_param.php";
    var query = "id_card_param=" + id_card_param + "&id_child=" + id_child + "&id_father=" + id_father + "&id_value_parent_id=" + id_value_parent_id + "&id_online_lang=" + id_online_lang;
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
            }  
        }  
        http.send(null);      
    }  
    else   
    {  
        document.location = link;  
    }
}
function save_change_template(id)
{
    var cont = document.getElementById('after_save');
    var name_template = document.getElementById('name_template').value;
    var template = document.getElementById('template').value;
    cont.innerHTML = loading;  
    link = "engine/ajax/save_change_template.php";
    var query = "id=" + id + "&name_template=" + name_template + "&template=" + htmlspecialchars(template);
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
            }  
        }  
        http.send(null);      
    }  
    else   
    {  
        document.location = link;  
    }
}
function htmlspecialchars(text)
{
   var chars = Array("&", "<", ">", '"', "'");
   var replacements = Array("&amp;", "&lt;", "&gt;", "&quot;", "'");
   for (var i=0; i<chars.length; i++)
   {
       var re = new RegExp(chars[i], "gi");
       if(re.test(text))
       {
           text = text.replace(re, replacements[i]);
       }
   }
   return text;
}
function edit_template(id)
{
    var cont = document.getElementById('main_template');
    cont.innerHTML = loading;  
    link = "engine/ajax/edit_template.php";
    var query = "id=" + id;
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
            }  
        }  
        http.send(null);      
    }  
    else   
    {  
        document.location = link;  
    }
}
function save_new_template()
{
    var cont = document.getElementById('after_save');
    var value = document.getElementById('name_template').value;
    cont.innerHTML = loading;  
    link = "engine/ajax/save_new_template.php";
    var query = "value=" + value;
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
            }  
        }  
        http.send(null);      
    }  
    else   
    {  
        document.location = link;  
    }
}
function add_template()
{
    var cont = document.getElementById('main_template');
    cont.innerHTML = loading;  
    link = "engine/ajax/add_template.php";
    var query = "";
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
            }  
        }  
        http.send(null);      
    }  
    else   
    {  
        document.location = link;  
    }
}
function delete_filtr_param(id)
{
    var cont = document.getElementById('filtr_param_' + id);
    cont.innerHTML = loading;  
    link = "engine/ajax/delete_filtr_param.php";
    var query = "id=" + id;
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
            }  
        }  
        http.send(null);      
    }  
    else   
    {  
        document.location = link;  
    }
}
function fitr_control(id)
{
    var cont = document.getElementById('main_filtr');
    cont.innerHTML = loading;  
    link = "engine/ajax/fitr_control.php";
    var query = "id=" + id;
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
            }  
        }  
        http.send(null);      
    }  
    else   
    {  
        document.location = link;  
    }
}
function add_new_filtr()
{
    var cont = document.getElementById('main_filtr');
    cont.innerHTML = loading;  
    link = "engine/ajax/add_new_filtr.php";
    var query = "";
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
            }  
        }  
        http.send(null);      
    }  
    else   
    {  
        document.location = link;  
    }  
}
function save_for_edit_menu(id)
{
    var cont = document.getElementById('preview_menu');
    var name_menu = document.getElementById('name_menu').value;
    var id_select_params = document.getElementById('id_select_params').value;
    var use_parent = document.getElementById('use_parent').value;
    var class_parent_blok = document.getElementById('class_parent_blok').value;
    var class_parent_link = document.getElementById('class_parent_link').value;
    var class_blok_link = document.getElementById('class_blok_link').value;
    var class_link = document.getElementById('class_link').value;
    var main_menu = document.getElementById('main_menu_class').value;
   
    cont.innerHTML = loading;  
    link = "engine/ajax/save_menu.php";
    var query = "id=" + id + "&main_menu=" + main_menu + "&name_menu=" + name_menu + "&id_select_params=" + id_select_params + "&use_parent=" + use_parent + "&class_parent_blok=" + class_parent_blok + "&class_parent_link=" + class_parent_link + "&class_blok_link=" + class_blok_link + "&class_link=" + class_link;
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
            }  
        }  
        http.send(null);      
    }  
    else   
    {  
        document.location = link;  
    }
}
function edit_menu (id)
{
    var cont = document.getElementById('menu_main');
    cont.innerHTML = loading;  
    link = "engine/ajax/edit_menu.php";
    var query = "id=" + id;
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
            }  
        }  
        http.send(null);      
    }  
    else   
    {  
        document.location = link;  
    }  
}
function save_new_menu()
{
    var cont = document.getElementById('preview_menu');
    var name_menu = document.getElementById('name_menu').value;
    var id_select_params = document.getElementById('id_select_params').value;
    var use_parent = document.getElementById('use_parent').value;
    var class_parent_blok = document.getElementById('class_parent_blok').value;
    var class_parent_link = document.getElementById('class_parent_link').value;
    var class_blok_link = document.getElementById('class_blok_link').value;
    var class_link = document.getElementById('class_link').value;
    var main_menu = document.getElementById('main_menu_class').value;
   
    cont.innerHTML = loading;  
    link = "engine/ajax/save_menu.php";
    var query = "main_menu=" + main_menu + "&name_menu=" + name_menu + "&id_select_params=" + id_select_params + "&use_parent=" + use_parent + "&class_parent_blok=" + class_parent_blok + "&class_parent_link=" + class_parent_link + "&class_blok_link=" + class_blok_link + "&class_link=" + class_link;
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
            }  
        }  
        http.send(null);      
    }  
    else   
    {  
        document.location = link;  
    }
}
function preview_menu()
{
    var cont = document.getElementById('preview_menu');
    var name_menu = document.getElementById('name_menu').value;
    var id_select_params = document.getElementById('id_select_params').value;
    var use_parent = document.getElementById('use_parent').value;
    var class_parent_blok = document.getElementById('class_parent_blok').value;
    var class_parent_link = document.getElementById('class_parent_link').value;
    var class_blok_link = document.getElementById('class_blok_link').value;
    var class_link = document.getElementById('class_link').value;
    var main_menu = document.getElementById('main_menu_class').value;
   
    cont.innerHTML = loading;  
    link = "engine/ajax/preview_menu.php";
    var query = "main_menu=" + main_menu + "&name_menu=" + name_menu + "&id_select_params=" + id_select_params + "&use_parent=" + use_parent + "&class_parent_blok=" + class_parent_blok + "&class_parent_link=" + class_parent_link + "&class_blok_link=" + class_blok_link + "&class_link=" + class_link;
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
            }  
        }  
        http.send(null);      
    }  
    else   
    {  
        document.location = link;  
    }
}
function add_new_menu ()
{
    var cont = document.getElementById('menu_main');
    cont.innerHTML = loading;  
    link = "engine/ajax/new_menu.php";
    var query = "";
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
            }  
        }  
        http.send(null);      
    }  
    else   
    {  
        document.location = link;  
    }  
}
function save_values(id, parent_values_id)
{
    var cont = document.getElementById('status_save');
    var value = document.getElementById('textarea_param_' +id).value;
    cont.innerHTML = loading;  
    link = "engine/ajax/save_new_param.php";
    var query = "id=" + id + "&value=" + value + "&parent_values_id=" + parent_values_id;
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
            }  
        }  
        http.send(null);      
    }  
    else   
    {  
        document.location = link;  
    }  
}
function go_params(id)
{
    var cont = document.getElementById('textarea_param');
    var value = document.getElementById('parent_param_id').value;
    cont.innerHTML = loading;  
    link = "engine/ajax/textarea_param.php?id=" + id + "&parent_param_id=" + value;
    var http = createRequestObject();  
    if( http )   
    {  
        http.open('get', link);  
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
function cancel_parent(id)
{
    var cont = document.getElementById('parent_div_' + id);

    cont.innerHTML = loading;  
    link = "engine/ajax/save_parent_id.php?id=" + id + "&canceled=1";
    var http = createRequestObject();  
    if( http )   
    {  
        http.open('get', link);  
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
function save_parent(id)
{
    var cont = document.getElementById('parent_div_' + id);
    var value = document.getElementById('parent_id_' + id).value;  

    cont.innerHTML = loading;  
    link = "engine/ajax/save_parent_id.php?id=" + id + "&value=" + value;
    var http = createRequestObject();  
    if( http )   
    {  
        http.open('get', link);  
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
function change_parent(id){
    var cont = document.getElementById('parent_div_' + id);  

    cont.innerHTML = loading;  
    link = "engine/ajax/change_parent_id.php?id=" + id;;
    var http = createRequestObject();  
    if( http )   
    {  
        http.open('get', link);  
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
function all_select_param_from_card(){
    var cont = document.getElementById('all_select_param_from_card');  
    var id_card = document.getElementById('id_card');
    cont.innerHTML = loading;  
    link = "engine/ajax/all_select_param_from_card.php";
    var query = "id_card=" + id_card.value;
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
    try { return new XMLHttpRequest() }  
    catch(e)   
    {  
        try { return new ActiveXObject('Msxml2.XMLHTTP') }  
        catch(e)   
        {  
            try { return new ActiveXObject('Microsoft.XMLHTTP') }  
            catch(e) { return null; }  
        }  
    }  
}
function visible_dop_settings()
{
    var cont = document.getElementById('type').value;
    var import_html = document.getElementById('import');
    var update = document.getElementById('update');
    if (cont.length>0)
    {
        if (cont==1)
        {
            import_html.style.display = 'none';
            update.style.display = 'block';
        } else {
            update.style.display = 'none';
            import_html.style.display = 'block';
        }
    } else {
        update.style.display = 'none';
        import_html.style.display = 'none';
    }
}
function geterate_ling_set(file_csv)
{
    var cont = document.getElementById('link_' + file_csv);
    var settings = document.getElementById('settings_' + file_csv).value;
    var href = '<a href="index.php?do=import&action=proccess_from_settings&name=' + file_csv + '&settings=' + settings + '">Обработать</a>';
    cont.innerHTML = href;
}