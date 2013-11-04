<?

$rcode = md5((mt_rand() / mt_getrandmax())."rlkz;kjlkjn-");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>StartupLeanch</title>
	<!-- Bootstrap -->
	<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="/style.css" rel="stylesheet" type="text/css" />
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/js/jquery-1.9.1.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/js/bootstrap.min.js"></script>
	<script src="/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script src="/js/jquery.ocupload-1.1.2.js"></script>
	<script src="/js/jquery.validate_ru.js"></script>
</head>
<body>
<div class="header">
	<div class="container">
		<div class="row text-center">
			<a href="/"><img src="/img2/logo.png" border=0></a>
		</div>
		<div class="row text-center" style="margin-top: 20px;">
			<div class="col-sm-8 col-sm-offset-2">
				<h1>Заявка на рецензию</h1>
				<p>Лучше всего задать нам конкретный вопрос. Например: «Мой начальник считает, что логотип слева лучше, кто прав?». Или: «Хорошо ли стоят баннеры на сайте?» Мы готовы делиться соображениями и давать советы. Присланная работа принимается на рецензию исключительно по нашей доброй воле. Мы не даем никаких обязательств относительно сроков публикации..</p>
			</div>
		</div>
	</div>
</div>
<div class="leanch">
	<div class="container">
		<div class="row" style="margin-top: 20px;">
			<div class="col-sm-8 col-sm-offset-2">
				<form action="add_save.php"  enctype="multipart/form-data" method=post id="reqform">
				<input type=hidden name=code value="<?=$rcode?>" />
					<table width=100%><tr><td width=50%>
					<div class="form-group">
					<label>Ваше имя:</label>
					<input type=text name="name" class="form-control" required>
					</div></td><td width=50%></td></tr>
					<tr><td>
					<div class="form-group">
					<label>E-mail:</label>
					<input type=email name="email" class="form-control" required>
					</div>
					</td><td></td></tr>
					<tr><td valign=top height=80>
					<div class="imgupload"><span style="font-weight: bold; margin-right: 20px;">Загрузка изображения</span></div>
					<div class="imgupload"><a class="btn btn-pink" id="uploadlogo">Выберите файл</a></div>
					<div class="imgupload"><img src="/img2/ok.png" id="uploadok"></div>
					<script>
			
				$(function() {

				$('#uploadlogo').upload({action: '/ajax_upload_leanch.php?code=<?=$rcode?>',  onComplete: function(data) {
					$('#uploadok').show();
				}
				
				});		
				
				function checkForm()
					{
						if($('#reqform').find('input[name="name"]').val()=='')
							{
							$('#reqform').find('input[name="name"]').addClass('error');
							return false;
							}
							
						if($('#reqform').find('input[name="email"]').val()=='')
							{
							$('#reqform').find('input[name="email"]').addClass('error');
							return false;
							}
							
						return true;
					}
				
					});
				
					</script>
					</td><td valign=top class="requesthint">Изображение с презентацией или сайтом проекта, ко которому вы хотите получить отзыв. Максимальный размер 800х1000 пикселей.
					</td></tr>
					<tr><td>					
					<label>Описание проекта</label>
					<textarea rows=5 class="form-control" name=description></textarea>
					</td><td valign=top class="requesthint">Опишите как можно подробнее ваш проект (описание будет опубликовано).</td></tr>
					<tr><td style="padding-top: 20px;"><input type=submit onclick="checkForm();" value="Отправить заявку" class="btn btn-green" style="width: 100%;"></td><td></td></tr></table>
				</form>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-sm-4" style="padding-bottom: 30px; padding-top: 15px;">
				&copy; 2013 Startup Leanch
			</div>
		</div>
	</div>
</div>
</body>
</html>