<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?

require_once("config.php");
require_once("engine/engine.php");

$rdate = date_parse($_REQUEST['date']);



$date = "";

if($rdate["error_count"]==0 && intval($rdate["year"])>0 && intval($rdate["month"])>0 && intval($rdate["day"])>0)
	$date = sprintf("%d-%02d-%02d",$rdate["year"],$rdate["month"],$rdate["day"]);

if(strtotime($date)>time() && !isset($_REQUEST['superdate']))
	{
	$date="";
	}
	
if($date=="")
	{
	$date = date("Y-m-d");	
	}

$res = mysql_query("SELECT *, UNIX_TIMESTAMP(Date) AS uDate FROM leanches 
	INNER JOIN reviewers ON leanches.ReviewerID = reviewers.ReviewerID
	WHERE Date <= '".$date."' ORDER BY LeanchID DESC LIMIT 1");
$leanch = mysql_fetch_array($res);

$date = date("Y-m-d",$leanch['uDate']);

$months = array(-1 => 'n', '', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
$smonths = array(-1 => 'n', '', 'янв', 'фев', 'мар', 'апр', 'мая', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноя', 'дек');



$d = date("d",$leanch['uDate']);
$m = $months[date("n",$leanch['uDate'])];

$id = $leanch['LeanchID'];

/// NEED TO REVIEW
//// дальше идёт длинный код для заполнения правой таблицы навигации предыдущими и последующими записями
//// + здесь же высчитываются даты предыдущего и последующего линча

$res1 = mysql_query("SELECT *, UNIX_TIMESTAMP(Date) AS uDate FROM leanches 
	INNER JOIN reviewers ON leanches.ReviewerID = reviewers.ReviewerID
	WHERE Date <='".$date."' AND Date <= DATE(NOW()) ORDER BY Date DESC LIMIT 5");
	
$res2 = mysql_query("SELECT *, UNIX_TIMESTAMP(Date) AS uDate FROM leanches 
	INNER JOIN reviewers ON leanches.ReviewerID = reviewers.ReviewerID
	WHERE Date >'".$date."' AND Date <= DATE(NOW()) ORDER BY Date ASC LIMIT 5");

$n1 = mysql_num_rows($res1);
$n2 = mysql_num_rows($res2);

$nav = array();
$prevdate = "";
$nextdate = "";

$showdown=0;
if($n1>3)
	$showdown=1;

if($n2 > 1)
	{
	if($n1 > 2)
		{
		$n1 = 3;
		}
	}
	else
		$n1 = 5-$n2;

for($i=$n1;$i>0;$i--)
	{
		$row = mysql_fetch_array($res1);
		$nav[5-$i]=$row;
	}
	
for($i=0;$i<5-$n1;$i++)
	{
		$row = mysql_fetch_array($res2);
		$nav[4-$i-$n1]=$row;
	}
	
switch($n1)
	{
		case 1:
				$nextdate = $nav[3]['Date'];	
			break;
		case 2:
				$prevdate = $nav[4]['Date'];
				$nextdate = $nav[2]['Date'];		
			break;
		default:
				switch($n2)
					{
						case 0:
							$prevdate = $nav[1]['Date'];
							break;
						case 1:
							$prevdate = $nav[2]['Date'];
							$nextdate = $nav[0]['Date'];		
							break;
						default:
							$prevdate = $nav[$n1]['Date'];				
							$nextdate = $nav[$n1-2]['Date'];			
							break;
					}
			break;
	}

$title = "Линч стартапов от экспертов по lean-технологиям";

Templating::SetMasterPage("templates/main.php");

?>
<div class="container">
 <div class="row">
 <div class="col-md-10">
	<div class="row leanchtitle">
		<div class="col-sm-2">			
				<table class="dateselector"><tr><td width=19><? if($prevdate!="") { ?><a href="/<?=$prevdate?>/"><img src="/img2/dselector_left.png" border=0></a><? } else { ?><img src="/img2/dselector_left_d.png" border=0><? } ?></td>
				<td class="datetext" valign=middle align=left><?=$d?></td><td width=19><? if($nextdate!="") { ?><a href="/<?=$nextdate?>/"><img src="/img2/dselector_right.png" border=0></a><? } else { ?><img src="/img2/dselector_right_d.png" border=0><? } ?></td></tr>
				<tr><td></td>
				<td class="leanchmonth" align=left colspan=2><?=$m?></td></tr>
				</table>			
		</div>
		<div class="col-sm-8">
		<h2><?=$leanch['ProjectName']?></h2>
		</div>
	</div>
	<div class="leanchinfo" style="" >
	<? if($leanch['Author']!="") { ?>
	  <div class="row leanchinfoitem">
		<div class="col-sm-2">
		Имя:
		</div>
		<div class="col-sm-10 leanchinfovalue">
		<?=$leanch['Author']?>
		</div>	
	 </div>
	 <? } 
	 
	 if($leanch['Link']!="") {
	 ?>
	 <div class="row leanchinfoitem">
		<div class="col-sm-2">
		Ссылка:
		</div>
		<div class="col-sm-10 leanchinfovalue">
		<a href="http://<?=$leanch['Link']?>/" target=_blank><?=$leanch['Link']?></a>
		</div>
	 </div>
	 <? } ?>
	 <div class="row leanchinfoitem">
		<div class="col-sm-2">
		Описание:
		</div>
		<div class="col-sm-10">
		<?=nl2br($leanch['Description'])?>
		</div>		
	 </div>
	</div>	
	<div class="row">
	<div class="col-sm-12">
		<table width=100%>
		<tr><td rowspan=3 width=160>
			<img src="<?=$leanch['Avatar']?>">
		</td>
		<td class="reviewer-title">Рецензировал:</td>
		</tr>
		<tr><td class="reviewer-name"><?=$leanch['ReviewerName']?></td></tr>
		<tr><td valign=bottom>
		<script language=JavaScript>
			function showOriginal()
				{
					$('#link-original').removeClass('link-original-unactive').addClass('link-original-active');
					$('#link-leanch').removeClass('link-leanch-active').addClass('link-leanch-unactive');
					$('.leanchcommentbubble').hide();
				}
				
			function showLeanch()
				{
					$('#link-original').removeClass('link-original-active').addClass('link-original-unactive');
					$('#link-leanch').removeClass('link-leanch-unactive').addClass('link-leanch-active');				
					$('.leanchcommentbubble').show();
				}
		</script>
		<a class="link-original-active" id="link-original" onclick="showOriginal();">оригинал</a>
		<a class="link-leanch-unactive" id="link-leanch" onclick="showLeanch();">показать рецензию</a>
		</td></tr></table>
	</div>
	</div>
	<div class="leanchpic">
<div class="leanchcommentbubble" style="display: none;">			
			<div style="width: 100%;">
			<p><?=nl2br($leanch['Review'])?></p>
			</div>
	</div>	
		<div class="row text-left">		
			<img src="<?=$leanch['Picture']?>" id="leanchpic">			
		</div>
	</div>
 </div>
 <div class="col-md-2">
	<script language=JavaScript>
		function navUp()
			{
				if(!$('.navup').hasClass('nav-active'))
					return;
					
				var d = $('.navigate A').first().attr('href');
				$.post('/ajax_get_preview.php', {date: d, currentdate: '<?=$date?>', order: 'up'}, function(data) {
						if(data)
							{
								var row = JSON.parse(data);
								$('.navigate .navigate-preview').last().remove();
								//$('.navigate .navigate-preview-caption').last().remove();
								$('.navigate TR:first').after(row['code']);
								//$('.navigate TR:first').after('<tr class=\"navigate-preview\"><td width=150 align=right><a href=\"/'+row['Date']+'/\"><img src=\"'+row['SmallPicture']+'\" border=0></a></td></tr><tr class=\"navigate-preview-caption\"><td width=150 align=left><a href=\"/'+row['Date']+'/\">'+row['ProjectName']+'</a></td></tr>');
								if(row['n']!=2)
									$('.navup').removeClass('nav-active').addClass('nav-unactive').attr('src','/img3/nav_up_inactive.gif');;
								if(!$('.navdown').hasClass('nav-active'))
									$('.navdown').removeClass('nav-unactive').addClass('nav-active').attr('src','/img3/nav_down_active.gif');;
									
								//debugger;
							}
					});
			}
			
		function navDown()
			{
				if(!$('.navdown').hasClass('nav-active'))
					return;
					
				var d = $('.navigate A').last().attr('href');
				$.post('/ajax_get_preview.php', {date: d, currentdate: '<?=$date?>', order: 'down'}, function(data) {
						if(data)
							{
								var row = JSON.parse(data);
								$('.navigate .navigate-preview').first().remove();
								//$('.navigate .navigate-preview-caption').first().remove();
								
								$('.navigate TR:last').before(row['code']);
								//$('.navigate TR:last').before('<tr class=\"navigate-preview\"><td width=150 align=right><a href=\"/'+row['Date']+'/\"><img src=\"'+row['SmallPicture']+'\" border=0></a></td></tr><tr class=\"navigate-preview-caption\"><td width=150 align=left><a href=\"/'+row['Date']+'/\">'+row['ProjectName']+'</a></td></tr>');
								if(row['n']!=2)
									$('.navdown').removeClass('nav-active').addClass('nav-unactive').attr('src','/img3/nav_down_inactive.gif');
								if(!$('.navup').hasClass('nav-active'))
									$('.navup').removeClass('nav-unactive').addClass('nav-active').attr('src','/img3/nav_up_active.gif');
								//debugger;
							}
					});
			}			
	</script>
	<table width=162 class="navigate" align=right cellpadding=0>
	<tr><td width=2></td><td colspan=2 class="navup-td"><img src="/img3/nav_up_<?=$n2>2?"":"in"?>active.gif" onclick="navUp();" class="navup <?=$n2>2?"nav-active":"nav-unactive"?>"></td></tr>
	<?
		//for($i=$navindex; $i<$navindex + 5; $i++)
		for($i=0; $i<5; $i++)
			{
			echo "<tr class=\"navigate-preview ".($nav[$i]["Date"]==$date?"navigate-current":"")."\">
				<td width=2 valign=top class=\"navigate-pre\">".($nav[$i]["Date"]==$date?"<img src=\"/img3/date_pre2.gif\">":"")."</td>
				<td valign=top width=40 class=\"navigate-date\"><span class=\"navigate-date-num\">".date("d",$nav[$i]["uDate"])."</span><br><span class=\"navigate-date-mon\">".$smonths[date("n",$nav[$i]["uDate"])]."</span></td>
				<td width=130 align=left><a href=\"/".$nav[$i]["Date"]."/\"><img width=108 src=\"".$nav[$i]["SmallPicture"]."\" border=0></a>
				<div class=\"navigate-preview-caption\"><a href=\"/".$nav[$i]["Date"]."/\">".$nav[$i]["ProjectName"]."</a></div>
				</td></tr>
			";
			}
	?>
	<tr><td width=2></td><td colspan=2 class="navdown-td"><img src="/img3/nav_down_<?=$showdown==1?"":"in"?>active.gif" onclick="navDown();" class="navdown <?=$showdown==1?"nav-active":"nav-unactive"?>"></td></tr>
	</table>
 </div>
 </div>
</div>
<?

Templating::Render($dblink,array_diff(get_defined_vars(), array(array())));

?>