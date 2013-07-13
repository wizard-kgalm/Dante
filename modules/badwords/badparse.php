<?php
global $dAmn, $config, $Timer;
if(strtolower($from)===strtolower($config['bot']['username'])) return;
$compstr = $config['bot']['trigger']."badwords";
if(strpos($message, $compstr)===0) return;
if(isset($config['badwordsettings']['delimiters'])) {
	$items = $config['badwordsettings']['delimiters'];
} else {
	$items = array('?',',','.','!','"',"'");
	$config['badwordsettings']['delimiters'] = $items;
}
$message = str_replace($items, ' ',$message); 
$args = explode(" ", $message);
foreach($args as $k => $v)
	$argslower[$k] = strtolower($v);

$swearupperg = array();
$swearlowerg = array();
$swearupperc = array();
$swearlowerc = array();
$badlist = true;
if(isset($config['badwordsettings']['listonword']))
	$badlist = $config['badwordsettings']['listonword'];
if(isset($config['badwords']['@all'])) {
	if(isset($config['badwords']['@all']['upper']))
		$swearupperg = array_intersect($args, $config['badwords']['@all']['upper']);
	if(isset($config['badwords']['@all']['lower']))
		$swearlowerg = array_intersect($argslower, $config['badwords']['@all']['lower']);
}

if(isset($config['badwords'][strtolower(substr($c,1))])) {
	if(isset($config['badwords'][strtolower(substr($c,1))]['upper']))
		$swearupperc = array_intersect($args, $config['badwords'][strtolower(substr($c,1))]['upper']);
	if(isset($config['badwords'][strtolower(substr($c,1))]['lower']))
		$swearlowerc = array_intersect($argslower, $config['badwords'][strtolower(substr($c,1))]['lower']);
}
$swears = "";
if($badlist) {
	if(sizeof($swearupperg) > 0) {
		foreach($swearupperg as $v) {
			$swears.="$v, ";
		}
	}
	if(sizeof($swearlowerg) > 0) {
		foreach($swearlowerg as $v) {
			$swears.="$v, ";
		}
	}
	if(sizeof($swearupperc) > 0) {
		foreach($swearupperc as $v) {
			$swears.="$v, ";
		}
	}
	if(sizeof($swearlowerc) > 0) {
		foreach($swearlowerc as $v) {
			$swears.="$v, ";
		}
	}
	if(strlen($swears) > 0)
		$swears = substr($swears,0,-2);
} else {
	if(sizeof($swearupperg) > 0) {
		$swears = $swearupperg[0];
	}
	if(sizeof($swearlowerg) > 0 && strlen($swears)==0) {
		$swears = $swearlowerg[0];
	}
	if(sizeof($swearlowerc) > 0 && strlen($swears)==0) {
		$swears = $swearlowerc[0];
	}
	if(sizeof($swearupperc) > 0 && strlen($swears)==0) {
		$swears = $swearlowerc[0];
	}
}
if(strlen($swears) > 0) {
	if(is_numeric($config['badwordsettings']['ban'])) {
		$bandata = array($from,$dAmn->room[strtolower(rChatName($c))]->getUserData($from,'pc'),$c);
		$Timer->register($config['badwordsettings']['ban'],$bandata,"modules/badwords/unban.php",null);
	}
	$kickmsg='';
	if(isset($config['badwordsettings']['kick']))
		$kickmsg = str_replace("{word}",$swears,$config['badwordsettings']['kick']);
	$dAmn->kick($from, $c, $kickmsg);
	if($config['badwordsettings']['ban']===true || is_numeric($config['badwordsettings']['ban'])) {
		$dAmn->ban($from, $c);
	}
}
?>