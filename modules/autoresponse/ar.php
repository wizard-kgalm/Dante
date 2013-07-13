<?php
switch( $args[0] ) {
	case "ar":
		switch( $args[1] ) {
			case "add":
				if ( !empty( $args[2] ) && !empty( $argsE[3] ) ) {
					$config['autoresponses'][$args[2]]['val'] = $argsE[3];
					$config['autoresponses'][$args[2]]['by'] = $from;
					$config['autoresponses'][$args[2]]['ts'] = time();
					$dAmn->say( $f . "Added!", $c );
				} else {
					$dAmn->say( $f . "You need to type a name and a value.", $c );
				}
				save_config( 'autoresponses' );
				break;
			case "del":
				if ( !empty( $args[2] ) ) {
					unset( $config['autoresponses'][$args[2]] );
					$dAmn->say( $f . "Removed!", $c );
				} else {
					$dAmn->say( $f . "You need to type a name.", $c );
				}
				save_config( 'autoresponses' );
				break;
			case "list":
				if ( count( $config['autoresponses'] ) <= 0 ) {
					$dAmn->say( $f . "No autoresponses created yet.", $c );
				} else {
					$msg = "<strong>:postit: Autoresponses:</strong><ul>";
					foreach( $config['autoresponses'] as $arN => $arV ) {
						$msg .= "<li><strong>" . $tr . $arN . "</strong><sub>, set by :dev" . $arV['by'] . ": at " . date( "r", $arV['ts'] ) . ".</sub></li>";
					}
					$dAmn->say( $msg, $c );
				}
				break;
			default:
				$dAmn->say( $f . "Type <em>" . $tr . $commandname . " add</em> to add a new one. There's also <em>remove</em> and <em>list</em> commands.", $c );
				break;
		}
		break;
	case "aignore":
		switch ($args[1]){
			case "add":
				if(!empty($args[2])){
					$toignore = strtolower($args[2]);
					if(isset($config['responses']['ignore'][0])){
						foreach($config['responses']['ignore'] as $num => $person){
							$config['responses']['ignore'][strtolower($person)] = date('d-m-y', time());
							unset($config['responses']['ignore'][$num]);
						}
						save_config('responses');
						$dAmn->say("Ignore list updated.",$c);
					}
					if(!isset($config['responses']['ignore'][$toignore])){
						$config['responses']['ignore'][$toignore] = date('d-m-y', time());
						save_config('responses');
						$dAmn->say("$from: $args[2]'s autoresponse triggers will now be ignored.",$c);
							
					}else
						return $dAmn->say("$from: $args[2]'s triggers are already ignored.",$c);
				}else
					return $dAmn->say("$from: Usage: {$tr}aignore add [username].",$c);
				break;
			case "del":
			case "delete":
			case "remove":
				if(!empty($args[2])){
					$toignore = strtolower($args[2]);
					if(isset($config['responses']['ignore'][0])){
						foreach($config['responses']['ignore'] as $num => $person){
							$config['responses']['ignore'][strtolower($person)] = date('d-m-y', time());
							unset($config['responses']['ignore'][$num]);
						}
						save_config('responses');
						$dAmn->say("Ignore list updated.",$c);
					}
					if(isset($config['responses']['ignore'][$toignore])){
						unset($config['responses']['ignore'][$toignore]);
						save_config('responses');
						$dAmn->say("$from: $args[2]'s responses are no longer ignored.",$c);
					}else
						$dAmn->say("$from: $args[2] was not found on the ignore list.",$c);
				}else
					$dAmn->say("$from: Usage: {$tr}aignore del [username].",$c);
				break;
			case "list":
				$say = "";
				if(!empty($config['responses']['ignore'])){
					$say .="These are the people who are <b>ignored</b> by autoresponse:<br><sup>";
					foreach($config['responses']['ignore'] as $person => $date){
						$say .="[".$person."] ";
					}
				}else{
					$say .="$from: The ignore list does not exist.";
				}
				$dAmn->say($say,$c);
				break;
		}break;
	case "aroom":
		switch ($args[1]){
			case "add":
				if(!empty($args[2])){
					$offroom = strtolower(rChatName($args[2]));
					if(isset($config['responses']['offrooms'][0])){
						foreach($config['responses']['offrooms'] as $num => $room){
							$config['responses']['offrooms'][strtolower($room)] = date('d-m-y', time());
							unset($config['responses']['offrooms'][$num]);
						}
						save_config('responses');
						$dAmn->say("No-response rooms list updated.",$c);
					}
					if(!isset($config['responses']['offrooms'][$offroom])){
						$config['responses']['offrooms'][$offroom] = date('d-m-y', time());
						save_config('responses');
						$dAmn->say("$from: ".rChatName($args[2])." is now on the no-response list.",$c);
					}else 
						return $dAmn->say("$from: $args[2] is already a no-response room.",$c);
				}else
					return $dAmn->say("$from: Usage: {$tr}aroom add [#room]. This will block autoresponse from being triggered in the room provided.",$c);
				break;
			case "del":
			case "delete":
			case "remove":
				if(!empty($args[2])){
					$offroom = strtolower(rChatName($args[2]));
					if(isset($config['responses']['offrooms'][0])){
						foreach($config['responses']['offrooms'] as $num => $room){
							$config['responses']['offrooms'][strtolower($room)] = date('d-m-y', time());
							unset($config['responses']['offrooms'][$num]);
						}
						save_config('responses');
						$dAmn->say("No-response rooms list updated.",$c);
					}
					if(isset($config['responses']['offrooms'][$offroom])){
						unset($config['responses']['offrooms'][$offroom]);
						save_config('responses');
						$dAmn->say("$from: ".rChatName($args[2])." has been removed from the no-response list.",$c);
					}else
						$dAmn->say("$from: $args[2] isn't a no-response list.",$c);
				}else 
					$dAmn->say("$from: Usage: {$tr}aroom del [#room].",$c);
				break;
			case "list":
				$say = "";
				if(!empty($config['responses']['offrooms'])){
					$say .= "These rooms are on the <b>no-response</b> list:<br><sup>";
					foreach($config['responses']['offrooms'] as $room => $date){
						$say .= "[".$room."] ";
					}
				}else{
					$say .= "$from: The no-response list doesn't exist.";
				}
				$dAmn->say($say,$c);
				break;
		}break;
		
	case "response":
		switch( $args[1] ) {
			case "add":
				if ( !empty( $args[2] ) && !empty( $argsE[3] ) ) {
					if ( !strstr( $argsF, "," ) ) {
						$dAmn->say( $f . "This command is different from the Noodlebot version. You need a comma to seperate the triggering words and the response. ", $c );
					} else {
						$r = explode( ", ", $argsE[2] );
						$config['responses'][$r[0]]['val'] = $r[1];
						$config['responses'][$r[0]]['by'] = $from;
						$config['responses'][$r[0]]['ts'] = time();
						$dAmn->say( $f . "Added!", $c );
					}
				} else {
					$dAmn->say( $f . "You need to type a name and a value.", $c );
				}
				save_config( 'responses' );
				break;
			case "del":
				if ( !empty( $argsE[2] ) ) {
					if ( array_key_exists( $argsE[2], $config['responses'] ) ) {
						unset( $config['responses'][$argsE[2]] );
						$dAmn->say( $f . "Removed!", $c );
					} else $dAmn->say( $f . "Response not in list.", $c );
				} else {
					$dAmn->say( $f . "You need to type a name.", $c );
				}
				save_config( 'responses' );
				break;
			case "list":
				if ( count( $config['responses'] ) <= 0 ) {
					$dAmn->say( $f . "There haven't been posted any responses yet.", $c );
				} else {
					$msg = "<strong>:postit: Responses:</strong><ul>";
					foreach( $config['responses'] as $arN => $arV ) {
						$msg .= "<li><strong>" . $arN . "</strong><sub>, set by :dev" . $arV['by'] . ": at " . date("r", $arV['ts']) . ".</sub></li>";
					}
					$dAmn->say( $msg, $c );
				}
				break;
			default:
				$dAmn->say( $f . "Type <em>" . $tr . $commandname . " add</em> to add a new one. There's also <em>remove</em> and <em>list</em> commands.", $c );
				break;
		}
		break;
}
?>