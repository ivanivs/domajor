<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<title>Панель управления</title> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<link rel='stylesheet' href='css/admin.css' media='all'/> 
<link rel='stylesheet' href='{site_url}css/style.css' media='all'/>
<link rel='stylesheet' href='{site_url}css/calendar-blue.css' media='all'/>
<link rel='stylesheet' href='{site_url}css/summernote-bs2.css' media='all'/>
<link rel='stylesheet' href='{site_url}css/summernote.css' media='all'/>
<link href="{site_url}css/bootstrap.min.css" rel="stylesheet" />
<script type="text/javascript" src="{site_url}js/jquery-1.7.2.min.js"></script>
    <script src="{site_url}js/bootstrap.js"></script>
<script type="text/javascript" src="{site_url}js/tooltipjs.js"></script>
<script type="text/javascript" src="{site_url}js/jquery.MultiFile.js"></script>
<script type="text/javascript" src="{site_url}js/jquery.form.js"></script>
<script type="text/javascript" src="{site_url}js/OSMS_kombat.js"></script>
<script type="text/javascript" src="{site_url}js/jquery-ui-1.9.2.custom.min.js"></script>
<link href="{site_url}css/redmond/jquery-ui-1.9.2.custom.css" rel="stylesheet">
<script type="text/javascript" src="{site_url}js/jquery.blockUI.js"></script>
<script type="text/javascript" src="{site_url}js/ls_script.js"></script>
<script type="text/javascript" src="{site_url}js/scrollTo.js"></script>
<script type="text/javascript" src="{site_url}js/calendar.js"></script>
<script type="text/javascript" src="{site_url}js/summernote.js"></script>
</head>
<body> 
{errmsg}
<div id='container_admin_page'>	
<div id='sign_header_full_admin'>{menu}</div> 
<div id='sign_content'>  
	<div align="left">
		{body_admin}
	</div>			
 <div align="right">{info}</div>
</div> 
</div> 
{modal}
<style>{style}</style>
</body> 
</html> 
