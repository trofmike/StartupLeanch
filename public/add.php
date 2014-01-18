<?

session_start();

require_once("config.php");
require_once("engine/engine.php");

Templating::SetMasterPage("templates/main.php");


$rcode = md5((mt_rand() / mt_getrandmax())."rlkz;kjlkjn-");

$title = "Заявка на рецензию";

?>
<div class="container">
		<div class="row text-left" style="margin-top: 20px;">
			<div class="col-sm-8 col-sm-offset-2">
				<h1 class="text-center">Заявка на рецензию</h1>
				<p>Для того, чтобы наши эксперты отрецензировали вашу заявку, пришлите нам скриншот вашей лендинг-страницы и ссылку на проект. 
				В тексте лучше всего задать нам конкретный вопрос. Например: “Как вы бы оценили наш Value proposition на лендинг странице?”. 
				Или: “Как вы бы порекомендовали увеличить конверсию в активацию?”.</p>
				<p>	Приложите цифры для того, чтобы нам было на что опираться.</p>
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

	</div>
</div>
<?

Templating::Render($dblink,array_diff(get_defined_vars(), array(array())));

?>