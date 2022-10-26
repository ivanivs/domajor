<?php
function return_all_news ()
{
    $results = mysql_query("SELECT * FROM ls_news");
    $number = mysql_num_rows ($results);
    for ($i=0; $i<$number; $i++)
    {	
            $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
    }	
    return ($array);    
}
function return_all_category_news ($id)
{
    $results = mysql_query("SELECT * FROM ls_news_category where parent_id = '".$id."';");
    $number = mysql_num_rows ($results);
    for ($i=0; $i<$number; $i++)
    {	
            $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);	
    }	
    return ($array);
}
function return_all_parent_category_news ($id)
{
    $results = mysql_query("SELECT * FROM ls_news_category where parent_id = '".$id."';");
    $number = mysql_num_rows ($results);
    return ($number);
}
function return_number_all_news_for_category($id)
{
    $results = mysql_query("SELECT * FROM `ls_news` where `id_category` = '".$id."';");
    $number = mysql_num_rows ($results);
    return ($number);
}
function return_option_category ($id, $metka, $option, $my_id = '')
{
    global $config;
    global $lang;
    global $id_online_lang;
    $all_category = return_all_category_news($id);
    foreach ($all_category as $v)
    {
        if ($v['parent_id']==0)
        {
            $metka = '';
        } else {
            $metka .= ' -';
        }
        $lang_category = mysql_fetch_array(mysql_query("select `text` from `ls_translate` where `type` = 'news_category_n' and `id_elements` = '".$v['id']."' and `id_lang` = '".$id_online_lang."';"), MYSQL_ASSOC);
        if ($my_id==$v['id'])
        {
            //print $my_id.'  '.$v['id'].'<br>';
            $option .= '<option value="'.$v['id'].'" selected>'.$metka.' '.$lang_category['text'].'</option>';
        } else {
            //print $my_id.'  '.$v['id'].'<br>';
            $option .= '<option value="'.$v['id'].'">'.$metka.' '.$lang_category['text'].'</option>';
        }
        if (return_all_parent_category_news ($v['id']))
        {
            $option = return_option_category ($v['id'], $metka, $option, $my_id);
        }
        $nubm_symb = strlen($metka);
        unset ($metka);
        for ($i=0; $i<$nubm_symb-2; $i++)
        {
            $metka .= ' -';
        }
    }
    return ($option);
}
function return_html_category ($id, $metka, $option)
{
    global $config;
    global $lang;
    global $id_online_lang;
    $all_category = return_all_category_news($id);
    if ($all_category)
    {
        foreach ($all_category as $v)
        {
            if ($v['parent_id']==0)
            {
                $metka = '';
            } else {
                $metka .= ' -';
            }
            $lang_category = mysql_fetch_array(mysql_query("select `text` from `ls_translate` where `type` = 'news_category_n' and `id_elements` = '".$v['id']."' and `id_lang` = '".$id_online_lang."';"), MYSQL_ASSOC);
            $tmp = return_all_parent_category_news ($v['id']);
            $tmp_news = return_number_all_news_for_category($v['id']);
            if (!$tmp and !$tmp_news)
            {
                $tmp_html = '<td align="center" style="border-bottom:1px solid grey;"><a href="index.php?do=news&action=category&del='.$v['id'].'" onclick="return confirm (\''.$lang['69'].'\')" border="0"><img src="'.$config ['site_url'].'images/admin/remove_16.png"></a></td>';
            } else {
                $tmp_html = '<td align="center" style="border-bottom:1px solid grey;">'.$lang[417].'</td>';
            }
            $array_lang = return_all_ok_lang();
            if ($array_lang)
            {
                foreach ($array_lang as $one_lang)
                {
                    $lang_ok = mysql_num_rows (mysql_query("select `text` from `ls_translate` where `type` = 'news_category_n' and `id_elements` = '".$v['id']."' and `id_lang` = '".$one_lang['id']."';"));
                    if ($lang_ok)
                    {
                        $lang_html .= '
                        <td width="50" align="center" style="border-bottom:1px solid grey;">
                                <a href="index.php?do=news&action=category&edit='.$v['id'].'&id_online_lang='.$one_lang['id'].'"><img src="'.$config ['site_url'].'images/admin/edit.png"></a>
                        </td>';
                    } else {
                        $lang_html .= '<td width="50" align="center" style="border-bottom:1px solid grey;">
                            <a href="index.php?do=news&action=category&add_translate='.$v['id'].'&id_online_lang='.$one_lang['id'].'"><img src="'.$config ['site_url'].'images/admin/red.png"></a>
                        </td>';
                    }
                }
            }
            $option .= '
            <tr>
                <td align="center" style="border-bottom:1px solid grey;">
                    <b>'.$v['id'].'</b>
                </td>
                <td style="border-bottom:1px solid grey;">'.$metka.' '.$lang_category['text'].'</td>
                '.$lang_html.'
                <td align="center" style="border-bottom:1px solid grey;"><a href="index.php?do=news&action=category&edit='.$v['id'].'" border="0"><img src="'.$config ['site_url'].'images/admin/edit.png"></a></td>
                '.$tmp_html.'
            </tr>
            ';
            unset ($lang_html);
            if ($tmp)
            {
                $option = return_html_category ($v['id'], $metka, $option);
            }
            $nubm_symb = strlen($metka);
            unset ($metka);
            for ($i=0; $i<$nubm_symb-2; $i++)
            {
                $metka .= ' -';
            }
        }
        return ($option);
    }
}
function return_info_category($id)
{
    $results = mysql_query("SELECT * FROM ls_news_category where id = '".$id."';");
    $number = mysql_num_rows ($results);
    if ($number)
    {
        $array = mysql_fetch_array($results, MYSQL_ASSOC);
        return ($array);
    } else {
        return (0);
    }
}
?>