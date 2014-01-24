<?

include("ajax_jquery.php");

$date = str_replace("/","",$_REQUEST['date']);
$order = $_REQUEST['order'];

$rdate = date_parse($date);

$currentdate = $_REQUEST['currentdate'];

if($rdate["error_count"]==0 && intval($rdate["year"])>0 && intval($rdate["month"])>0 && intval($rdate["day"])>0)
	$date = $rdate["year"]."-".$rdate["month"]."-".$rdate["day"];
else
	return;
	
	// exit if date in future
if(strtotime($date)>time())
	return;

if($order=="up")
	$query = "SELECT *, UNIX_TIMESTAMP(Date) AS uDate  FROM leanches WHERE Date > '".$date."' AND Date <= DATE(NOW()) ORDER BY Date LIMIT 2";
if($order=="down")
	$query = "SELECT *, UNIX_TIMESTAMP(Date) AS uDate  FROM leanches WHERE Date < '".$date."' ORDER BY Date DESC LIMIT 2";

if($query=="")
	return;

$result = array();
	
$res = mysql_query($query);
$n = mysql_num_rows($res);
if($n>0)
	{
		$result = mysql_fetch_array($res);
	}
$result["n"] = $n;

// Ed: не думаю, что это лучшее решение выдавать тут в качестве результата html-code

$smonths = array(-1 => 'n', '', 'янв', 'фев', 'мар', 'апр', 'мая', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноя', 'дек');
$resultstr = "<tr class=\"navigate-preview ".($result["Date"]==$currentdate?"navigate-current":"")."\">
				<td width=2 valign=top class=\"navigate-pre\">".($result["Date"]==date("Y-m-d")?"<img src=\"/img3/date_pre2.gif\">":"")."</td>
				<td valign=top width=40 class=\"navigate-date\"><span class=\"navigate-date-num\">".date("d",$result["uDate"])."</span><br><span class=\"navigate-date-mon\">".$smonths[date("n",$result["uDate"])]."</span></td>
				<td width=130 align=left><a href=\"/".$result["Date"]."/\"><img width=108 src=\"".$result["SmallPicture"]."\" border=0></a></td></tr>
	<tr class=\"navigate-preview-caption ".($result["Date"]==$currentdate?"navigate-current":"")."\"><td width=2 class=\"navigate-pre\"></td><td></td>
	<td width=130 align=left valign=top><a href=\"/".$result["Date"]."/\">".$result["ProjectName"]."</a></td></tr>";
$result['code']=$resultstr;
//echo $resultstr;
echo json_encode($result,  JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

?>