<?php
if (isset ($_COOKIE['id_user_online']))
{
    $admin_html = '
    <script>
        function addImageToCategory()
        {
            $( "#dialogUpload" ).dialog({
                    autoOpen: false,
                    show: "blind",
                    width: "500",
                    hide: "explode"
                });
            $("#dialogUpload").dialog("open");
        }
        $(document).ready(function() {

			var button = $("#uploadButtonCategory"), interval;

			$.ajax_upload(button, {
						action : \'http://intarsio.com.ua/include/upload.php\',
						name : \'myfile\',
						onSubmit : function(file, ext) {
							// показываем картинку загрузки файла
							$("img#load").attr("src", "http://intarsio.com.ua/images/load.gif");
							$("#uploadButtonCategory font").text(\'Загрузка\');

							/*
							 * Выключаем кнопку на время загрузки файла
							 */
							this.disable();

						},
						onComplete : function(file, response) {
							// убираем картинку загрузки файла
							$("img#load").attr("src", "http://intarsio.com.ua/images/loadstop.gif");
							$("#uploadButtonCategory font").text(\'Загрузить\');

							// снова включаем кнопку
							this.enable();

							// показываем что файл загружен
							$(response).appendTo("#files");

						}
					});
		});
    </script>
    <style>
       #uploadButtonCategory font
        {
                display:block;
        }
    </style>
    <div id="dialogUpload" title="Завантаження зображень до категорії" style="display:none;">
        <b>Виберіть одне, або декілька зображень для завантаження:</b>
            <div id="uploadButtonCategory" class="button">
                <font>
                    Завантажити
                </font>
                <img id="load" src="http://intarsio.com.ua/images/loadstop.gif"/>
            </div>
            <ol id="files">
                Загруженные файлы :
            </ol>
    </div>
    <script type="text/javascript" src="http://intarsio.com.ua/js/ajaxupload.js"></script>
    <a href="#" onclick="addImageToCategory();" style="color:white;">Добавить изображения</a>
    ';
    $body .= '
    <div id="top_filtr_body">
        <div style="font-weight:bold;">Загальний вигляд комплекту</div>
        '.$admin_html.'
    </div>
    ';
}
?>