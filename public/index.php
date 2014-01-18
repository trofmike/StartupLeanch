<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?

require_once("config.php");
require_once("engine/engine.php");

$rdate = date_parse($_REQUEST['date']);

$date = "";

if($rdate["error_count"]==0 && intval($rdate["year"])>0 && intval($rdate["month"])>0 && intval($rdate["day"])>0)
	$date = $rdate["year"]."-".$rdate["month"]."-".$rdate["day"];

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


$months = array(-1 => 'n', '', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');

$d = date("d",$leanch['uDate']);
$m = $months[date("n",$leanch['uDate'])];

$id = $leanch['LeanchID'];

$previd = $id - 2;
$nextid = $id + 5;

$res = mysql_query("SELECT *, UNIX_TIMESTAMP(Date) AS uDate FROM leanches 
	INNER JOIN reviewers ON leanches.ReviewerID = reviewers.ReviewerID
	WHERE LeanchID <=".$nextid." AND Date <= DATE(NOW()) ORDER BY LeanchID DESC LIMIT 10");
	
$nav = array();
$prevdate = "";
$nextdate = "";

$navindex = 0;
$i=0;

while($row = mysql_fetch_array($res))
	{
		if($row['LeanchID']<$id && $prevdate == "")
			$prevdate = $row['Date'];
		if( $row['LeanchID']>$id)
			$nextdate = $row['Date'];
		if( $row['LeanchID']==$id && $i > 2)
			$navindex = $i - 2;
		$nav[] = $row;
		$i++;
	}

	if($i - $navindex < 6)
		$navindex = $i - 5;

$title = "Публичная порка стартап-проектов";

Templating::SetMasterPage("templates/main.php");

?>
<div class="container">
 <div class="row">
 <div class="col-md-10">
	<div class="row leanchtitle">
		<div class="col-sm-2">			
				<table class="dateselector"><tr><td width=19><? if($prevdate!="") { ?><a href="/<?=$prevdate?>/"><img src="/img2/dselector_left.png" border=0></a><? } ?></td>
				<td class="datetext" valign=middle align=center><?=$d?></td><td width=19><? if($nextdate!="") { ?><a href="/<?=$nextdate?>/"><img src="/img2/dselector_right.png" border=0></a><? } ?></td></tr>
				<tr><td colspan=3><?=$m?></td></tr>
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
	<div class="leanchpic">
		<div class="row text-center">
				<!--<div style="width: 100%; padding-top: 30px; padding-bottom: 30px; font-size: 16pt;">
					<a class="pointer dashlink" onclick="$('#spoiler').hide(); $('#review').css('visibility','');"><strong>LeanЧевать!</strong></a>
				</div>-->
		
			<img src="<?=$leanch['Picture']?>" id="leanchpic" style=" box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.05) inset, 0px 0px 8px rgba(180, 180, 180, 0.6);">
			
		</div>
	</div>
	<div class="leanchcomment">
		<div class="row">
			<div class="col-sm-2" style="padding-top: 15px;">
				<img src="<?=$leanch['Avatar']?>">
			</div>
			<div class="col-sm-8">
				<div class="row leanchcommentline">
					<div class="col-sm-3">
					Рецензировал:
					</div>
					<div class="col-sm-7">
					<strong><?=$leanch['ReviewerName']?></strong>
					</div>
				</div>
				<? if($leanch['Verdict']!="") { ?>
				<div class="row leanchcommentline">
					<div class="col-sm-3">
					Вердикт:
					</div>
					<div class="col-sm-7">
					<?=$leanch['Verdict']?>
					</div>
				</div>				
				<? } ?>
				<div class="row leanchcommentline">
				<div class="col-sm-12 text-center" id="spoiler" style="position: absolute;">
					<a class="pointer dashlink" onclick="$('#spoiler').hide(); $('#review').css('visibility','');"><strong>Посмотреть комментарий</strong></a>
				</div>
					<div class="col-sm-12" id="review" style="visibility: hidden;">
				<p><?=nl2br($leanch['Review'])?></p>
					</div>
				</div>	
				<div class="row leanchcommentline">				
				</div>
			</div>
		</div>
	</div>
 </div>
 <div class="col-md-2">
	<table width=100% class="navigate">
	<!--<tr><td><img src="/img3/up.gif"></td></tr>-->
	<?
		for($i=$navindex; $i<$navindex + 5; $i++)
			{
			echo "
				<tr class=\"navigate-preview\"><td><a href=\"/".$nav[$i]["Date"]."/\"><img src=\"".$nav[$i]["SmallPicture"]."\" border=0></a></td></tr>
	<tr class=\"navigate-preview-caption\"><td><a href=\"/".$nav[$i]["Date"]."/\">".$nav[$i]["ProjectName"]."</a></td></tr>
			";
			}
	?>
	<!--
	<tr class="navigate-preview"><td><img src="/leanches/1_t.jpg"></td></tr>
	<tr class="navigate-preview-caption"><td>Vokzal.com</td></tr>
	<tr class="navigate-preview"><td><img src="/leanches/2_t.jpg"></td></tr>
	<tr class="navigate-preview-caption"><td>Fivecards</td></tr>
	<tr class="navigate-preview"><td><img src="/leanches/3_t.jpg"></td></tr>
	<tr class="navigate-preview-caption"><td>Bustourpro</td></tr>
	<tr class="navigate-preview"><td><img src="/leanches/4_t.jpg"></td></tr>
	<tr class="navigate-preview-caption"><td>HRValue</td></tr>
	<tr class="navigate-preview"><td><img src="/leanches/5_t.jpg"></td></tr>
	<tr class="navigate-preview"><td>RoadAR</td></tr>
	
	<tr><td style="padding-top: 10px;"><img src="/img3/down.gif"></td></tr>-->
	</table>
 </div>
 </div>
</div>
<?

Templating::Render($dblink,array_diff(get_defined_vars(), array(array())));

?>