<?php
switch($args[1]){
	case "on": 
		$config['DJ']['on'] = TRUE;
		save_config("DJ");
		$dAmn->say("$from: xD response activated.",$c);
		break;
	case "off":
		$config['DJ']['on'] = FALSE;
		save_config("DJ");
		$dAmn->say("$from: xD response deactivated.",$c);
		break;
}