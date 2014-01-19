<?

session_start();

 // Когортный анализ
  if($_COOKIE['cohort']!="")
	{
		$cohort = $_COOKIE['cohort'];
		setcookie('cohort',$cohort,time()+86400*300);
	}
	else
	{
			$cohort = "Y".date("Y")."W".date("W")."M".date("m")."D".date("z");
			setcookie('cohort',$cohort,time()+86400*300);		
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta property="og:image" content="http://startupleanch.ru/img3/logo.png">
<meta property="og:image:type" content="image/png">
<meta property="og:image:width" content="1024">
<meta property="og:image:height" content="1024">

<title>StartupLeanch <?=$title?></title>

	<!-- Bootstrap -->
	<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="/style.css" rel="stylesheet" type="text/css" />
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/js/jquery-1.10.2.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/js/bootstrap.min.js"></script>
	<script src="/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script src="/js/jquery.ocupload-1.1.2.js"></script>
	<script src="/js/jquery.validate_ru.js"></script>
	
</head>
<body>
<div class="header">
	<div class="container">
		<div class="row">
			<div class="col-sm-2">
			<a href="/"><img src="/img3/logo.png" border=0></a>
			</div>
			<div class="col-sm-7 text-center">
			<h1>Публичная порка стартап-проектов.</h1>			
			</div>
			<? if($title!=" - Заявка на рецензию") { ?>
			<div class="col-sm-3 text-right">
			<a href="/add.php" class="btn btn-green addleanch"><strong>Прием заявок!</strong></a>
			</div>			
			<? } ?>
		</div>
		<div class="row subheader">
			<div class="col-sm-6 text-left subheader-borders">
				<img src="/img3/header_line_l.gif">
			</div>
			<div class="col-sm-6 text-right subheader-borders">
				<!--<img src="/img3/header_line_r.gif">-->
			</div>
		</div>
	</div>
</div>
<?=$PageContent?>
<div class="container">	
<div class="footer">
	<div class="row">
		<div class="col-sm-6">
			&copy; <?=date("Y")?> StartupLeanch
		</div>
		<div class="col-sm-6 text-right">
			mail@startupleanch.ru
		</div>
	</div>
</div>	
</div>	
<!-- Yandex.Metrika counter -->
<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript"></script>
<script type="text/javascript">
try { var yaCounter23664067 = new Ya.Metrika({id:23664067,
          webvisor:true,
          clickmap:true,
          trackLinks:true,
          accurateTrackBounce:true});
} catch(e) { }
</script>
<noscript><div><img src="//mc.yandex.ru/watch/23664067" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<!-- Google analytics -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-47267865-1']);
  _gaq.push(['_trackPageview']);
<?	
	if($cohort!="")
			echo "_gaq.push(['_setCustomVar', 1, 'Start Date', '".$cohort."', 1]);";		
?> 
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
<!-- /Google analytics -->
</script>
</body>
</html>