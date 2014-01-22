<?

include("ajax_jquery.php");

$date = str_replace("/","",$_REQUEST['date']);
$order = $_REQUEST['order'];

$rdate = date_parse($date);

if($rdate["error_count"]==0 && intval($rdate["year"])>0 && intval($rdate["month"])>0 && intval($rdate["day"])>0)
	$date = $rdate["year"]."-".$rdate["month"]."-".$rdate["day"];
else
	return;
	
	// exit if date in future
if(strtotime($date)>time())
	return;

if($order=="up")
	$query = "SELECT * FROM leanches WHERE Date > '".$date."' AND Date <= DATE(NOW()) ORDER BY Date LIMIT 2";
if($order=="down")
	$query = "SELECT * FROM leanches WHERE Date < '".$date."' ORDER BY Date DESC LIMIT 2";

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

echo json_encode($result);

?>