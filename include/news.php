<?php
require ('admin/engine/template/functions.php');
if (!isset ($_GET['id_category']))
{
    $title = $lang[652];
    $keywords = '';
    $description = '';
    if (!isset ($_GET['page']))
    {
        $start = 0;
    } else {
        $start = $_GET['page']*$config['user_params_33']-1;
    }
    $results = mysql_query("SELECT * FROM `ls_news` order by id DESC LIMIT ".$start.", ".$config['user_params_33'].";");
    $info_count_news = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `ls_news`;"), MYSQL_ASSOC);
    $number = mysql_num_rows ($results);
    for ($i=0; $i<$number; $i++)
    {
            $array[$i] = mysql_fetch_array($results, MYSQL_ASSOC);
    }
    $template_tmp = return_one_template(17);
    $body .= str_replace ('{count_news}', $info_count_news['COUNT(*)'], $template_tmp['template']);
    $template_short_news = return_one_template(15);
    if ($number)
    {
        foreach ($array as $v)
        {
            $one_news = $template_short_news['template'];
            $sql = "SELECT `text` FROM `ls_translate` WHERE `type` = 'news_name' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".$v['id']."';";
            $info_name_news = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
            $sql = "SELECT `text` FROM `ls_translate` WHERE `type` = 'news_short' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".$v['id']."';";
            $info_short_news = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
            $sql = "SELECT `text` FROM `ls_translate` WHERE `type` = 'news_category_n' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".$v['id_category']."';";
            $info_category_news = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
            $one_news = str_replace('{name_news}', $info_name_news['text'], $one_news);
            $one_news = str_replace('{short_news}', $info_short_news['text'], $one_news);
            $one_news = str_replace('{date}', date('d.m.Y H:i', $v['time']), $one_news);
            $full_news_link = '{main_sait}{lang}/mode/news/'.translit($info_category_news['text']).'_'.$v['id_category'].'/'.translit($info_name_news['text']).'_'.$v['id'].'.html';
            $one_news = str_replace('{full_news_link}', $full_news_link, $one_news);
            $body .= $one_news;
        }
    }
    $number_page = ceil($info_count_news['COUNT(*)']/$config['user_params_33']);
    $a = '{main_sait}{lang}/mode/news';
    /*
     * <nav aria-label="Page navigation">
  <ul class="pagination">
    <li>
      <a href="#" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <li><a href="#">1</a></li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">4</a></li>
    <li><a href="#">5</a></li>
    <li>
      <a href="#" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>
     */
    $body .= '<div id="clear"></div>
    <div style="text-align: center;"><nav aria-label="Page navigation">
  <ul class="pagination">';
    for ($i=1; $i<=$number_page; $i++)
    {
        if (!isset ($_GET['page']) and $i==1)
        {
                $body .= '<li class="active"><a href="#">'.$i.'</a></li>';
        } else {
            if ($_GET['page']==$i)
            {
                    $body .= '<li class="active"><a href="#">>'.$i.'</a></li>';
            } else {
                    $body .= '<li><a href="'.$a.'?page='.$i.'">'.$i.'</a></li>';
            }
            if ($i==23)
            {
                    $body .= '<br><br>';
            }
        }
    }
    $body .= '</ul>';
    $body .= '</nav></div>';
    $template_tmp = return_one_template(18);
    $body .= $template_tmp['template'];
} else {
    if (!isset ($_GET['id_news']))
    {

    } else {
        $info_news = mysql_fetch_array(mysql_query("SELECT * FROM `ls_news` WHERE `id` = '".mysql_escape_string($_GET['id_news'])."';"), MYSQL_ASSOC);
        $template_tmp = return_one_template(16);
        $sql = "SELECT `text` FROM `ls_translate` WHERE `type` = 'news_name' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".mysql_real_escape_string($_GET['id_news'])."';";
        $info_name_news = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
        $sql = "SELECT `text` FROM `ls_translate` WHERE `type` = 'news_key' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".mysql_real_escape_string($_GET['id_news'])."';";
        $info_key_news = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
        $sql = "SELECT `text` FROM `ls_translate` WHERE `type` = 'news_descriptio' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".mysql_real_escape_string($_GET['id_news'])."';";
        $info_descriptio_news = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
        $sql = "SELECT `text` FROM `ls_translate` WHERE `type` = 'news_full' and `id_lang` = '".$id_online_lang."' and `id_elements` = '".mysql_real_escape_string($_GET['id_news'])."';";
        $info_full_news = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
        $infoShort = mysql_fetch_array(mysql_query("SELECT * FROM `ls_translate` where `id_elements` = '".mysql_real_escape_string($_GET['id_news'])."' and `type` = 'news_short';"), MYSQL_ASSOC);
        if (strlen($info_full_news['text'])>11) {
            $template_tmp['template'] = str_replace('{full_news}', $info_full_news['text'], $template_tmp['template']);
        } else {
            $template_tmp['template'] = str_replace('{full_news}', $infoShort['text'], $template_tmp['template']);
        }
        $template_tmp['template'] = str_replace ('{name_news}', $info_name_news['text'], $template_tmp['template']);
        $template_tmp['template'] = str_replace ('{date}', date('d.m.Y H:i:s', $info_news['time']), $template_tmp['template']);
        $title = $info_name_news['text'];
        $keywords = $info_key_news['text'];
        $description = $info_descriptio_news['text'];
        $body .= $template_tmp['template'];
    }
}
?>