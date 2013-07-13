<?php 
global $Timer, $dAmn;
switch ($args[0]){
	//me and npmsg commands here for use in the input window, so that you can go /me. /npmsg is the same as holding shift.
	case "me":
		if($args[1][0] == "#"){ 
			$dAmn->me($argsE[2],$args[1]);
		}else
			$dAmn->me($argsF,$c);
		break;
	case "npmsg":
		if($args[1][0] == "#"){
			$dAmn->npmsg($argsE[2],$args[1]);
		}else
			$dAmn->npmsg($argsF,$c);
		break;
	case "input":
		switch($args[1]){
			case "on":
					$config['input']['status'] = TRUE;
					save_config("input");
					$dAmn->say("$from: Input has been switched on!",$c);
					$inp = $config['input']['status'];
					$data['time'] = 5;
					$data['name'] = "check";
					$data['room'] = $c;
				if($inp){
					$config['input']['check'] = $Timer->registerRecurring(5, $data, 0, "modules/input/input.php", true);
				}else
					$Timer->cancel($config['input']['check']);
				break;
			case "off":
				if($config['input']['status'] == TRUE){
					unset($config['input']['status']);
					save_config('input');
					$dAmn->say("$from: Input has been switched off!",$c);
				}else
					return $dAmn->say("$from: Input hasn't been turned on.",$c);
				break;
			case "help":
				$dAmn->say("$from: Here's how the input works.<sup><br> When you type {$tr}input on, it turns input on. In the input window, which is a command prompt with just : in the window, you'll be able to control the bot. To use the commands, you just type the command. You don't need the trigger. To start, just type 'say #roomname' and after that, you won't need to specify a room unless you're changing what room you're talking or controlling the bot in. That about covers it!<br>This is really important. DO NOT USE '|', '<', '>' or '&'. Use <b>'%v'</b> for |, <b>'%a'</b> for &, <b>'lt;'</b> for <, and <b>'gt;'</b> for >. Otherwise, <b>IT WILL KILL THE INPUT WINDOW</b>!!",$c);
				break;
			default: 
				$dAmn->say("$from: Usage: {$tr}input on/off/help.",$c);
			break;
		}
		break;
}
				