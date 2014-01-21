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
	<div class="row">
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
		<div class="col-sm-2 link-original"><a class="link-original-active" id="link-original" onclick="showOriginal();">Оригинал</a></div>
		<div class="col-sm-2 link-leanch" ><a class="link-leanch-unactive" id="link-leanch" onclick="showLeanch();">LeanЧевать!</a></div>
	</div>
	<div class="leanchpic">
<div class="leanchcommentbubble" style="display: none;">
			<table align=right>
				<tr><td valign=top align=left>
				<img src="<?=$leanch['Avatar']?>">
				<br/>
				<strong>Рецензировал:</strong><br/>
				<?=$leanch['ReviewerName']?>
				</td></tr>
			</table>
			<div style="clear: both;"></div>			
			<div style="width: 100%; margin-top: 10px;">
			<p><?=nl2br($leanch['Review'])?></p>
			</div>
	</div>	
		<div class="row text-left">		
			<img src="<?=$leanch['Picture']?>" id="leanchpic">			
		</div>
	</div>
 </div>
 <div class="col-md-2">
	<table width=150 class="navigate" align=right>
	<?
		for($i=$navindex; $i<$navindex + 5; $i++)
			{
			echo "
				<tr class=\"navigate-preview\"><td width=150 align=right><a href=\"/".$nav[$i]["Date"]."/\"><img src=\"".$nav[$i]["SmallPicture"]."\" border=0></a></td></tr>
	<tr class=\"navigate-preview-caption\"><td width=150 align=left><a href=\"/".$nav[$i]["Date"]."/\">".$nav[$i]["ProjectName"]."</a></td></tr>
			";
			}
	?>
	</table>
 </div>
 </div>
</div>
<?

Templating::Render($dblink,array_diff(get_defined_vars(), array(array())));

?>