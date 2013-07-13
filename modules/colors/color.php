<?php
if(!isset($config['colors'])){
	$b = file_get_contents("http://damncolors.nol888.com/ColorList.php");
	$pattern = "/\"([0-9a-zA-Z\-]+)\":\['(#[0-9a-zA-Z]+)','(#[0-9a-zA-Z]+)'\],/";
	$matches = array();
	preg_match_all($pattern, $b, $matches);
	$config['colors']['users'] = $matches[1];
	$config['colors']['firstcolor'] = $matches[2];
	$config['colors']['secondcolor'] = $matches[3];
	save_config('colors');
}
switch($args[0]){
	case "colors":
		if(!empty($args[1])){
			$checking = FALSE;
			foreach($config['colors']['users'] as $num => $cuser){
				if(strtolower($cuser) == strtolower($args[1])){
					$checking = TRUE;
					$dAmn->say("$from: $args[1]'s colors are ".$config['colors']['firstcolor'][$num]." and ".$config['colors']['secondcolor'][$num].".",$c);
				}
			}
			if(!$checking){
				$dAmn->say("$from: $args[1] doesn't have dAmn colors.",$c);
			}
		}else
			$dAmn->say("$from: Usage: ".$tr."colors <i>username</i>. This command displays the colors of a specified user if they exist. You may want to update the color list first though, using ".$tr."cupdate.",$c);
		break;
	case "cupdate":
		if(isset($config['colors'])){
			unlink(f('config/colors.vudf'));
			$b = file_get_contents("http://damncolors.nol888.com/ColorList.php");
			$pattern = "/\"([0-9a-zA-Z\-]+)\":\['(#[0-9a-zA-Z]+)','(#[0-9a-zA-Z]+)'\],/";
			$matches = array();
			preg_match_all($pattern, $b, $matches);
			$config['colors']['users'] = $matches[1];
			$config['colors']['firstcolor'] = $matches[2];
			$config['colors']['secondcolor'] = $matches[3];
			save_config('colors');
			$dAmn->say("$from: Color list successfully updated.",$c);
		}
		break;
}