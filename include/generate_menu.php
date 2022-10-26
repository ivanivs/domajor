<?php
if (substr_count($html, '{dynamic_menu_'))
{
    //$array_id_menu = returnSubstrings($html, '{dynamic_menu_', '}');
    $titre = preg_match_all("|{dynamic_menu_(.*)}|isU",$html,$regs);
    $array_id_menu = $regs[1];
    foreach ($array_id_menu as $v)
    {
        $one_menu = generate_menu($v);
        $html = str_replace ('{dynamic_menu_'.$v.'}', $one_menu, $html);
    }
}
?>