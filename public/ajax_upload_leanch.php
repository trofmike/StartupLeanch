<?

include("ajax_jquery.php");

$code = preg_replace("/[^0-9a-z]+/", "", $_REQUEST['code']);


if(is_uploaded_file ( $_FILES['file']['tmp_name'] ))
	{
	 $uploadfile = "leanchfiles/" . $code ."." . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
	 
	if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {

									$u = new updater("leanch_uploads");
									$u->AddParameter("Code",$code);
									$u->AddParameter("FileName","/".$uploadfile);
									$u->AddParameter("OriginalFile",$_FILES['file']['name']);
									$u->Run();
								}
							else
							{
								echo "upload failed";
							}
	}	
	else
	{
		echo "file was not uploaded";
	}

?>