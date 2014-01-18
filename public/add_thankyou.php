<?

session_start();

require_once("config.php");
require_once("engine/engine.php");

Templating::SetMasterPage("templates/main.php");


$rcode = md5((mt_rand() / mt_getrandmax())."rlkz;kjlkjn-");

$title = "Заявка на рецензию";

?>
<div class="container">
		<div class="row text-center" style="margin-top: 20px;">
			<div class="col-sm-8 col-sm-offset-2">
				<div><strong>Спасибо! Ваша заявка принята!</strong></div>
				<div style="margin-top: 40px;"><a href="/">Вернуться на главную</a></div>
				<div style="margin-top: 300px;"></div>
			</div>
		</div>
</div>
<?

Templating::Render($dblink,array_diff(get_defined_vars(), array(array())));

?>