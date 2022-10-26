<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivashka
 * Date: 6/15/15
 * Time: 4:13 PM
 * To change this template use File | Settings | File Templates.
 */
$categoryList = '
<script>
    $(function() {
        $(".categoryLink").hover(function(){
            $(this).children(".original").hide();
            $(this).children(".color").show();
        },
        function(){
            $(this).children(".original").show();
            $(this).children(".color").hide();
        }
        );
    });
</script>
';
if ($array = getValuesSelectParam(3)){
    foreach ($array as $v){
        $categoryList .= '
        <div class="category col-lg-2 col-md-2 col-sm-4 col-xs-6">
                <a href="'.$config['site_url'].'ru/producer/'.translit($v['text']).'/'.$v['id'].'" class="categoryLink">
                    <img src="'.$v['info'].'" alt="чехлы '.$v['text'].'" style="height: 100px;" class="original">
                    <img src="'.str_replace('logo/', 'logo/color/', $v['info']).'" alt="чехлы '.$v['text'].'" style="height: 100px; display: none;" class="color">
                    <p>'.$v['text'].'</p>
                </a>
            </div>
        ';
    }
}