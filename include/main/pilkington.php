<?php
require ('admin/engine/template/functions.php');
$main_template = return_one_template($config['user_params_9']);
$body .= ' <div id="content_block_2">
				 <div id="content_block_2_zagolovok">
				 <h1>Пошук автоскла</h1>
				 </div>
				 <div id="content_block_2_posuk">
				 <form action="" method="POST">
				   <div id="content_block_2_posuk_b">
				   <span class="text_u_poshuku">Пошук за єврокодом</span>
				     <table width="270" border="0">
                     <tr>
                     <td align="left" width="90">Єврокод</td>
                     <td  align="right" width="170"><input type="text" style="border:1px solid gray;" name="eurocode"/></td>
                     </tr>
                     </table>
                   </div>
				   <div id="clear"></div>
				   <span class="right"><input  type="image"  src="{template}img/submit.png"/></span>
				   </form>
				 </div>
				</div>
                                ';
$info_name_page = return_one_translate (9, $id_online_lang, 'static_page_nam');
$info_key_words = return_one_translate (9, $id_online_lang, 'static_page_key');
$info_description = return_one_translate (9, $id_online_lang, 'static_page_des');
$info_title = return_one_translate (9, $id_online_lang, 'static_page_tit');
$info_text = return_one_translate (9, $id_online_lang, 'static_page_tex');
if (isset ($_POST['eurocode']))
{
    $sql = "select `id_item` from `ls_values_text` where `text` LIKE '%".$_POST['eurocode']."%' and id_cardparam = '3';";
    //$id_item = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
    $results = mysql_query($sql);
    $number = mysql_num_rows ($results);
    for ($i=0; $i<$number; $i++)
    {	
	    $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
    }
    if ($number)
    {
	foreach ($array as $v)
	{
	    $info_item = mysql_fetch_array(mysql_query("SELECT * FROM `ls_items` WHERE `id` = '".$v['id_item']."';"), MYSQL_ASSOC);
	    $array_items[] = $info_item;
	}
    }
    $main_template = return_one_template($config['user_params_29']);
    $main_template_array = explode ('<--page-->', $main_template['template']);
    $main_template = $main_template_array[1];
    $body .= $main_template_array[0];
    $array_image = returnSubstrings($main_template, '{image_', '}');
    $array_text = returnSubstrings($main_template, '{text_', '}');
    $array_price = returnSubstrings($main_template, '{price_', '}');
    $array_select = returnSubstrings($main_template, '{select_', '}');
    if ($array_items)
    {
            foreach ($array_items as $key => $v)
            {
                    $html_item = $main_template;
                    $info_item = return_item_info ($v['id'], $id_online_lang);
                    foreach ($array_image as $image_id)
                    {
                            $a_img = explode ('_', $image_id);
                            $image = $info_item['image'.$a_img[0]];
                            $image = $image[($a_img[1]-1)]['text'];
                            $html_item = str_replace ('{image_'.$image_id.'}', urlencode('upload/userparams/'.$image), $html_item);
                    }
                    foreach ($array_text as $text_id)
                    {
                            $a_text = explode ('_', $text_id);
                            $text = $info_item['text'.$a_text[0]];
                            $text = $text['text'];
                            if ($a_text[1])
                            {
                                    if (strlen($text)>$a_text[1])
                                    {
                                            mb_internal_encoding("UTF-8");
                                            $text = mb_substr ($text, 0, $a_text[1]).'...';
                                            mb_internal_encoding("windows-1251");
                                    }
                            }
                            $html_item = str_replace ('{text_'.$text_id.'}', $text, $html_item);
                    }
                    foreach ($array_select as $select_id)
                    {
                            $select = $info_item['select'.$select_id];
                            foreach ($select as $key => $v1)
                            {
                                $html_select .= $v1['text'];
                                if (isset ($select[$key+1]))
                                {
                                    $html_select .= ',';
                                }
                            }
                            $html_item = str_replace ('{select_'.$select_id.'}', $html_select, $html_item);
                            unset ($html_select);
                    }
                    foreach ($array_price as $price_id)
                    {
                            $results_many_price = mysql_query("SELECT * FROM `ls_values_ligament_price` where `id_item` = '".$v['id']."' order by `price` LIMIT 0,1;");
                            $maybe_many_price = mysql_num_rows($results_many_price);
                            if (!$maybe_many_price)
                            {
                                    $price = $info_item['price'.$price_id];
                                    $price = $price['text'];
                                    $array_price_new = explode ('|', $price);
                                    $html_item = str_replace ('{price_'.$price_id.'}', $array_price_new[0], $html_item);
                                    $html_item = str_replace ('{valuta}', $array_price_new[1], $html_item);
                            } else {
                                    $info_price_many = mysql_fetch_array($results_many_price, MYSQL_ASSOC);
                                    $html_item = str_replace ('{price_'.$price_id.'}', $info_price_many['price'], $html_item);
                                    $info_valuta_many_price = mysql_fetch_array(mysql_query("SELECT `value` FROM `ls_reference_values_translate` where `id_reference_value` = '".$info_price_many['id_reference_value']."' and `id_lang` = '".$id_online_lang."';"), MYSQL_ASSOC);
                                    $html_item = str_replace ('{valuta}', $info_valuta_many_price['value'], $html_item);
                            }
                    }
                    $html_item = str_replace ('{link}', '{main_sait}{lang}/mode/item-'.$v['id'].'.html', $html_item);
                    $body_tmp .= $html_item;
                    if ($array_price_new[0]>$max_price or !isset ($max_price))
                    {
                            $max_price = $array_price_new[0];
                    }
                    if ($array_price_new[0]<$min_price or !isset ($min_price))
                    {
                            $min_price = $array_price_new[0];
                    }
                    $body_tmp = str_replace ('{id_item}', $v['id'], $body_tmp);
            }
            $top_filtr_template = return_one_template($config['user_params_21']);
            $top_filtr_template = $top_filtr_template['template'];
            require_once('admin/engine/filtr/functions.php');
            $sql = "SELECT `id` FROM ls_filtr where id_select_values='".$array['select'][0]."';";
            $results = mysql_query($sql);
            $number = @mysql_num_rows ($results);
            if ($number>0)
            {
                    $info_filtr = mysql_fetch_array($results, MYSQL_ASSOC);
                    $array_filtr_param = return_param_for_filtr ($info_filtr['id']);
                    $sql = "select `id` from `ls_filtr_param` where id_filtr='".$info_filtr['id']."' and visible=0";
                    $results = mysql_query($sql);
                    $number = @mysql_num_rows ($results);
                    if($number)
                    {
                            if ($array_filtr_param)
                            {
                                    $link_more_param = '<div id="link_more_param"><a href="#">'.$lang[377].'</a></div>';
                            }
                    }
                    $id_block = 0;
                    foreach ($array_filtr_param as $key => $v)
                    {
                            //print $array_filtr_param[$key]['id_param']."<br>";
                            //print $array_filtr_param[$key-1]['id_param']."<br>";
                            if ($array_filtr_param[$key]['id_param']!=$array_filtr_param[$key-1]['id_param'])
                            {
                                    $tr_param = return_one_translate ($v['id_param'], $id_online_lang, 'select');
                                    $filtr .= '<div style="float:left; text-align:left; margin:30px;" id="block_'.$id_block.'"><span style="font-size:14px;font-family:Verdana, Arial, Helvetica, sans-serif; font-weight:bold;">'.$tr_param['text'].'</span>';
                                    $id_block++;
                            }
                            if ($v['visible'])
                            {
                                    $tr_param = return_one_translate ($v['id_value_param'], $id_online_lang, 'select_value');
                                    $filtr .= '<div id="param_'.$v['id'].'"><input type="checkbox" id="checkbox_'.$v['id'].'" onClick="change_filtr_param(\''.$id_block.'_'.$v['id'].'\', \''.$v['id'].'\');" value="'.$v['id'].'"> '.$tr_param['text'].'</div>';
                            } else {
                                    $array_id_hidden[] = $v['id'];
                                    $other_param = '<div id="other_param_'.$id_block.'" style="padding-left:15px; font-size:12px; text-decoration:underline;" onclick="visible_param(Array('.implode(',', $array_id_hidden).'), \''.$id_block.'\')">'.$lang[378].'</div>';
                                    $tr_param = return_one_translate ($v['id_value_param'], $id_online_lang, 'select_value');
                                    $filtr .= '<div id="param_'.$v['id'].'" style="display: none;"><input type="checkbox" id="checkbox_'.$v['id'].'" onClick="change_filtr_param(\''.$id_block.'_'.$v['id'].'\', \''.$v['id'].'\');" value="'.$v['id'].'"> '.$tr_param['text'].'</div>';
                                    
                            }
                            if ($array_filtr_param[$key]['id_param']!=$array_filtr_param[$key+1]['id_param'])
                            {
                                    $filtr .= $other_param.'</div>';
                                    unset ($other_param);
                                    unset ($array_id_hidden);
                            }
                            $array_js_param .= $v['id'];
                            if (isset ($array_filtr_param[$key+1]))
                            {
                                    $array_js_param .= ', ';
                            }
                    }
            }
            if (count($array['select'])>1)
            {
                    $link_erase_search = '<a href="?a[select][]='.$array['select'][0].'">'.$lang[382].'</a>';
            }
            $top_filtr = '
            <div id="top_filtr">
            <div id="filtr_price">'.$lang[376].'</div>
            <script type="text/javascript">
                    var array_param = [ '.$array_js_param.' ];
                    var array = \'\';
                    var change_param_m = \''.$lang[383].'\';
                    var change_param_b = \''.$lang[378].'\';
                    var bolshe_parametrov = 0;
                    $(function(){
                            // Slider
                            $(\'#slider\').slider({
                                    range: true,
                                    min: '.($min_price*0.9).',
                                    max: '.($max_price*1.1).',
                                    values: ['.($min_price*0.9).', '.($max_price*1.1).'],
                                    animate: true,
                                    slide:  function(event, ui) {
                                                    var min = document.getElementById(\'min\');
                                                    var values = $( "#slider" ).slider( "option", "values" );
                                                    min.innerHTML = values[0] + " '.$array_price_new[1].'";
                                                    var max = document.getElementById(\'max\');
                                                    max.innerHTML = values[1] + " '.$array_price_new[1].'";
                                            } ,
                                    change: function ()
                                            {
                                                    change_filtr();
                                                    var min = document.getElementById(\'min\');
                                                    var values = $( "#slider" ).slider( "option", "values" );
                                                    min.innerHTML = values[0] + " '.$array_price_new[1].'";
                                                    var max = document.getElementById(\'max\');
                                                    max.innerHTML = values[1] + " '.$array_price_new[1].'";
                                            }

                            });
                            
                            //hover states on the static widgets
                            
                    });
                    function change_filtr_param1(param, id)
                    {
                            checkB=document.getElementById("checkbox_" + id);
                            if (checkB.checked)
                            {
                                    var new_array = array;
                                    array = new_array + "a[select][]=" + param + "&";
                                    change_filtr();
                            }
                    }
                    function change_filtr_param(param, id)
                    {
                            array = \'\';
                            for (var key in array_param) {
                                    var val = array_param [key];
                                    checkB=document.getElementById("checkbox_" + val);
                                    tmp=document.getElementById("apply_2");
                                    var new_array = array;
                                    if (checkB.checked)
                                    {
                                            array = new_array + "a[select][]=" + checkB.value + "&";
                                    }
                            }
                            change_filtr();
                    }
                    function change_filtr()
                    {
                            var cont = document.getElementById(\'apply\');
                            var values_price = $( "#slider" ).slider("option", "values");
                            cont.innerHTML = loading;
                            var query = "a[select][]='.$array['select'][0].'&" + array + "id_param_price='.$price_id.'&min_price=" + values_price[0] + "&max_price=" + values_price[1];
                            link = main_sait_url + "include/check_number_item.php?" + query;
                            query = \'\';
                            var http = createRequestObject();  
                            if( http )   
                            {  
                                http.open(\'get\', link);
                                http.onreadystatechange = function ()   
                                {  
                                    if(http.readyState == 4)   
                                    {
                                        cont.style.display = \'block\';
                                        cont.innerHTML = http.responseText;  
                                    }  
                                }  
                                http.send(null);      
                            }
                    }
                    function visible_param(array, id_block)
                    {
                            var block_param = document.getElementById(\'other_param_\' + id_block);
                            for (var key in array) {
                            var val = array [key];
                            var cont = document.getElementById(\'param_\' + val);
                            toggle(cont);
                            block_param.innerHTML = change_param_m;
                            }
                            
                    }
                    function toggle(el) {
                    el.style.display = (el.style.display == \'none\') ? \'block\' : \'none\';
                    }

                    function param_4()
                    {
                            var cont = document.getElementById(\'param_4\');
                            cont.style.display = \'block\';
                    }
            </script>
            <div id="top_filtr_slider">
                    <div id="slider"></div>
            </div>
            <div style="float:left;" id="min">'.($min_price*0.9).' '.$array_price_new[1].'</div>
            <div style="float:right;" id="max">'.($max_price*1.1).' '.$array_price_new[1].'</div>
            <div id="clear"></div>
            <span id="hr_filtr"></span>
            <div id="clear"></div>
            <div id="filtrs">
            '.$filtr.'
            </div>
            <div id="clear"></div>
            <div id="apply2"></div>
            <div id="clear"></div>
            <div id="apply"></div>
            <div id="link_erase_search">'.$link_erase_search.'</div>
            </div>
            ';
            if ($config['user_params_30'])
            {
                    $body .= str_replace ('{content}', $top_filtr, $top_filtr_template);
            }
            $body .= $body_tmp;
            $body .= $main_template_array[2];
            $number_page = ceil($count_items/$limit_count);
            $array = $_GET['a'];
            $all_params = '?a[select][]='.$array['select'][0];
            if (isset ($_GET['param']) and $_GET['param']=='sale')
            {
                    $a = $config ['site_url'].$alt_name_online_lang.'/'.$_GET['mode'].'/'.$_GET['trash'].'/'.$all_params;
            } else {
                    $a = $config ['site_url'].$alt_name_online_lang.'/'.$_GET['mode'].'/'.$_GET['trash'].'/'.$all_params;
            }
            $body .= '</div><div id="clear"></div><div id="paginator" align="center">';
            for ($i=1; $i<=$number_page; $i++)
            {
                    if (!isset ($_GET['page']) and $i==1)
                    {
                            $body .= '<span class="page">'.$i.'</span>';
                    } else {
                            if ($_GET['page']==$i)
                            {
                                    $body .= '<span class="page">'.$i.'</span>';
                            } else {
                                    $body .= '<a href="'.$a.'&page='.$i.'">'.$i.'</a>';
                            }
                            if ($i==23)
                            {
                                    $body .= '<br><br>';
                            }
                    }
            }
            $body .= $main_template_array[3];
    }
} else {
$body .= '
<div id="content_block_3">
<div style="font-weight:bold;">'.$info_name_page['text'].'</div>
    '.$info_text['text'].'
    </div></div>
';
}
$name_left_block = $lang[218];
?>