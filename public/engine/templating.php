<?

class Templating
{

public static $masterpage;
public static $dblink;

public static function SetMasterPage($file)
{
	ob_start();
	self::$masterpage = $file;
}

// в переменной $vars передаются переменные из основного пространства имен
public static function Render($dblink, $vars)
{

	$PageContent = ob_get_contents();
	ob_end_clean();
	
	$title = "";

	if(isset($vars['title']))
		$title = $vars['title'];
	
	if(isset($vars['alias']))
		$alias = $vars['alias'];

	if($title!="")
		$title=" - ".$title;

	include(self::$masterpage);	
}

public static function my_array_diff($a, $b) {
    $map = $out = array();
    foreach($a as $val) 
		{
		echo "<li>".$val."</li>";
		$map[$val] = 1;
		}
		
    foreach($b as $val) 
		if(isset($map[$val])) 
			$map[$val] = 0;
			
    foreach($map as $val => $ok) 
		if($ok) 
			$out[] = $val;
			
    return $out;
}

}

?>