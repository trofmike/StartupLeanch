<?

include("ajax_jquery.php");

$u = new updater("leanch_requests");
$u->AddParameter("Code",$_REQUEST['code']);
$u->AddParameter("Name",$_REQUEST['name']);
$u->AddParameter("Email",$_REQUEST['email']);
$u->AddParameter("Description",$_REQUEST['description']);
$u->AddDate("Date");
$u->Run();

header('Location: /add_thankyou.php');

?>