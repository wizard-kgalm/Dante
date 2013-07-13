<?php
if(empty($config['bzikes'])){
	$config['bzikes'] = unserialize(file_get_contents('http://www.thezikes.org/publicemotes.php?format=php&ip=12.234.156.78'));
	save_config('bzikes');
	foreach($config['bzikes'] as $test => $emotes){$config['bzikes'][$test] = $emotes['devid'];}
	save_config('bzikes');
}
if(!empty($config['bzikes']['codes'])){
	$config['bzikes'] = unserialize(file_get_contents('http://www.thezikes.org/publicemotes.php?format=php&ip=12.234.156.78'));
	save_config('bzikes');
	foreach($config['bzikes'] as $test => $emotes){$config['bzikes'][$test] = $emotes['devid'];}
	save_config('bzikes');
}

switch($args[0]){
	case "bzikes":
		$tr = $config['bot']['trigger'];
		if(!empty($args[1])){
			switch($args[1]){
				
				case "refresh":
					$config['bzikes'] = unserialize(file_get_contents('http://www.thezikes.org/publicemotes.php?format=php&ip=12.234.156.78'));
					save_config('bzikes');
					foreach($config['bzikes'] as $test => $emotes){
						$config['bzikes'][$test] = $emotes['devid'];
					}
						save_config('bzikes');
						$dAmn->say($from.": List refreshed!",$c);
					break;
				case "check":
					if(!empty($args[2])){
						$soda = FALSE;
						foreach($config['bzikes'] as $test => $emotes){
							if($test == $args[2]){
								$thumb = $emotes;
								$soda = TRUE;
								$nums = $emotes;
							}
						}
						if($soda){
							$dAmn->say("$from: $args[2] is the code for :thumb".$thumb.":.",$c);
						}else
							$dAmn->say("$from: $args[2] isn't a code stored on bZikes.",$c);
					}else
						$dAmn->say("$from: Usage: <b>".$tr."bzikes check <i>:code</i></b>. This command checks to see if an emote code is stored in bZikes and says what thumb is associated with it if it exists.",$c);
					break;
				case "on":
					if($config['bzikes2'][':on:'] != TRUE){
						$config['bzikes2'][':on:'] = TRUE;
						save_config('bzikes2');
						$dAmn->say("$from: bZikes is now on! All messages will be checked for bZikes codes!",$c);
					}else
						$dAmn->say("$from: bZikes is already on and operational. I am checking all messages for codes that exist.",$c);
					break;
				case "off":
					if($config['bzikes2'][':on:'] != FALSE){
						$config['bzikes2'][':on:'] = FALSE;
						save_config('bzikes2');
						$dAmn->say("$from: bZikes is now off.",$c);
					}else
						$dAmn->say("$from: bZikes is already off.",$c);
					break;
			}break;
		}else
			$dAmn->say("$from: Usage: <b>".$tr."bzikes on/off/add/del/check <i>[:code:] [thumbnumber]</i></b>.<sup><br><b>".$tr."bzikes add <i>:code: thumbnumber</i></b> adds a code and the thumb NUMBER of a thumb. Do not include the :thumb: part, you only need the number. For instance, to use :thumb940393804093: just say '940393804093'.<br><b>".$tr."bzikes on/off</b> toggles whether or not bZikes is on and checking input messages for codes that exist so that it may replace them with the thumb.<br><b>".$tr."bzikes del <i>:code:</i></b> deletes a :code: along with the associated thumb number off the bZikes list.",$c);
	break;
}

$bZikes = $config['bzikes2'][':on:'];
if($bZikes == TRUE){
	foreach($config['bzikes'] as $test => $emotes){
		$message = str_replace($test, ":thumb".$emotes.":", $message);
		$argsE[0] = $message;
	}
}else return;