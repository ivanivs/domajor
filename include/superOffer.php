<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/17/15
 * Time: 7:17 PM
 * To change this template use File | Settings | File Templates.
 */
$info = getOneString("SELECT * FROM `ls_items` where `select_5` = 351 and `text_14` = 1 ORDER by RAND();");
$superOffer = '
<a href="'.getItemLink($info).'" style="position: relative;">
    <img src="http://picase.com.ua/resize_image.php?filename=upload%2Fuserparams%2F'.getMainImage($info['id']).'&const=128&width=245&height=150&r=255&g=255&b=255" alt="Супер предложение">
    <div style="padding: 5px 10px; background-color: #2ba8db; position: absolute; color: #FFF; right: 0px; top: 50px;">ТОП аксессуар</div>
</a>
<a class="btn btn-block" href="'.getItemLink($info).'"><span>'.getPrice($info['price_1']).' грн.</span></a>
';
