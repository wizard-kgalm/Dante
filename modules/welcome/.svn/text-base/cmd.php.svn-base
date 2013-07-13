<?php

$wfrom = strtolower( $from );
if( substr( $args[1], 0, 1 ) == '#' ) {
	$wroom = rChatName( $args[1] );
	$r = TRUE;
	$wargs = explode( " ", $argsE[2] );
	foreach( $argsE as $n => $arg ) {
		if ( ( $n !=1 ) && ( $n !=0 ) ) $wargsE[] = $arg;
	}
	$place = $wroom;
} else {
	$wroom = rChatName( $c );
	$r = FALSE;
	$wargs = explode( " ", $argsF );
	foreach( $argsE as $n => $arg ) {
		if ( $n != 0 ) $wargsE[] = $arg;
	}
	$place = $wroom;
}
global $welcome;
switch( $args[0] ) {
	case "welcome":
		$msg = $welcome->getIndv( $wfrom, $c );
		if ( empty( $args[1] ) ) {
			if ( $msg !== FALSE ) {
				$dAmn->say( $f . " your current welcome message is: <br> $msg <br>" .
				'Use "welcome clear" to remove it or "welcome [message]" to change it.', $c );
			} else {
				$dAmn->say( $f . " you don't have welcome message set.<br>" .
				'Use "welcome [message]" to make one.', $c );
			}
		} else {
			$msg = $argsE[1];
			if ( strtolower( trim( $msg ) == "clear" ) ) {
				$welcome->clearIndv( $wfrom, $c );
				$dAmn->say( $f . " your welcome message has been cleared.", $c );
			} else {
				$welcome->setIndv( $wfrom, $c, $msg );
				$dAmn->say( $f . " your welcome message has been set to: <br>" . $msg, $c );
			}
		}
		break;
	case "wtglobal":
		$place = "all rooms";
		$wroom = FALSE;
	case "wtroom":
		switch ( $wargs[0] ) {
			case "order":
				if ( $wargs[1] == '' || $wargs[1] == '?' ) {
					$dAmn->say( $args[0] . '" (#room) order [indv,pc,all]" Expects that [indv,pc,all] looks like "1,2,3"' . "<br>"
					. 'This command set the order in which welcome messages are parsed. The default is "1,2,3".'."<br>"
					. 'If you set any of them to 0 that type of message will be ignored. **This settings is for all rooms.**', $c );
				} else {
					$result = $welcome->setOrder( $wroom, $wargsE[1] );
					if ( $result === FALSE ) {
						$dAmn->say( 'Invalid order, expecting[( 0-3 ),( 0-3 ),( 0-3 )]', $c );
						break;
					} else {
						$dAmn->say( "Order for {$place} is now Indv={$result['indv']}, PC={$result['pc']}, All={$result['all']}", $c );
					}
				}
				break;
			case "combine":
				if ( $wargs[1] == '' || $wargs[1] == '?' ) {
					$dAmn->say( '"combine [yes/no/default]" allow multipule welcome messages?' . "<br>" .
				'This would allow a user that has an individual welcome to receive that along with a room message' . "<br>" .
				'This could easily allow alot of spam if not handled carefully. Default is off.', $c );
				} elseif ( strtolower( $wargs[1] ) == 'yes' || strtolower( $wargs[1] ) == 'on' ) {
					$dAmn->say( "Combine welcomes for {$place} is on.", $c );
					$welcome->setCombineOn( $wroom );
				} elseif ( strtolower( $wargs[1] ) == 'no' || strtolower( $wargs[1] ) == 'off' || strtolower( $wargs[1] ) == 'default' ) {
					$dAmn->say( "Combine welcomes for {$place} is off.", $c );
					$welcome->setCombineOff( $wroom );
				} else {
					$dAmn->say( '"combine (#room) [yes/no/default]"', $c );
				}
				break;
			case "first":
				if ( $wargs[1] == '' || $wargs[1] == '?' ) {
					$dAmn->say( '"first [global/room/default]" Do the all rooms messages ("global" option) come first or individual room messages ("room" option)?'."<br>".'Default is "room"', $c );
				} elseif ( strtolower( $wargs[1] ) == 'global' ) {
					$dAmn->say( "Global welcomes for {$place} will be checked first.", $c );
					$welcome->setGlobalsFirst( $wrooms );
				} elseif ( strtolower( $wargs[1] ) == 'room' || strtolower( $wargs[1] ) == 'default' ) {
					$dAmn->say( "Individual room welcomes for {$place} will be checked first.", $c );
					$welcome->setGlobalsLast( $wrooms );
				} else {
					$dAmn->say( '"first [room/global/default]"', $c );
				}
				break;
			case "all":
				$msg = $wargsE[1];
				$result = $welcome->getAll( $wroom );
				if ( empty( $msg ) ) { //Empty prints the message and "clear" clears it ( Need to do )
					$dAmn->say( "Welcome for all users for $place is currently set to: <br> {$result}", $c );
				} elseif ( strtolower( $msg ) == 'clear' ) {
					$welcome->clearAll( $wroom );
					$dAmn->say( "Welcome for all users for $place has been cleared", $c );
				} else {
					$welcome->setAll( $wroom, $msg );
					$dAmn->say( "Welcome for all users for $place has been set to: <br> $msg", $c );
				}
				break;
			case "priv":
				$msg = $wargsE[2];
				$pc = $wargs[1];
				$result = $welcome->getPriv( $pc, $wroom );
				if ( empty( $msg ) ) { //Empty prints the message and "clear" clears it ( Need to do )
					//$wargsE[2]
					$dAmn->say( "Welcome for privclass $pc in $place is currently set to:<br>$result", $c );
				} elseif ( strtolower( $msg )=='clear' ) {
					$welcome->clearPriv( $pc, $wroom );
					$dAmn->say( "Welcome for privclass $pc in $place has been cleared", $c );
				} else {
					$welcome->setPriv( $pc, $wroom, $msg );
					$dAmn->say( "Welcome for privclass $pc in $place has been set to:/n $msg", $c );
				}
				break;
			case "status":
				if( $wroom === FALSE ) $r = "All Rooms"; else $r = $wroom;
				$stats = array(
					"combine"	=>	$welcome->getCombine( $wroom )		?	"On"	:	"Off",
					"first"		=>	$welcome->getWhichFirst( $wroom )	?	"Global":	"Room",
					"order"		=>	$welcome->getOrder( $wroom ),
					"switch"	=>	$welcome->getSwitch( $wroom )		?	"On"	:	"Off",
				);
				$say = "<u><b>Welcome Settings for $r</b></u><br/>".
					"Welcome Status: <b>{$stats['switch']}</b><br/>";
				if( $wroom ) {
					$say .= "Multipule Welcomes: <b>{$stats['combine']}</b><br/>".
						"Order: <b>{$stats['first']} messages first</b><br/>";
				}
				$dAmn->say( $say .
					"Message Order: All=<b>{$stats['order']['all']}</b>, PC=<b>{$stats['order']['pc']}</b>, Indv=<b>{$stats['order']['indv']}</b>"
				, $c );
				unset( $stats,$say );
				break;
			case "off":
				if( $wroom !== FALSE ) {
					if( !$welcome->getSwitch( $wroom ) ) {
						$dAmn->say( "Welcomes for $wroom are already off.", $c );
					} else {
						$welcome->setSwitchOff( $wroom );
						$dAmn->say( "Welcomes for $wroom have been switched off.", $c );
					}
				} else $dAmn->say( "You can't turn the entire system on or off. Use the module command to switch the module on or off.", $c );
				break;
			case "on":
				if( $wroom !== FALSE ) {
					if ( $welcome->getSwitch( $wroom ) ) {
						$dAmn->say( "Welcomes for $wroom are already on.", $c );
					} else {
						$welcome->setSwitchOn( $wroom );
						$dAmn->say( "Welcomes for $wroom have been switched on.", $c );
					}
				} else $dAmn->say( "You can't turn the entire system on or off. Use the module command to switch the module on or off.", $c );
				break;
			default:
				$dAmn->say( $args[0] . '" (#room) [order/combine/first/all/priv/status]"' .
					"\n" . $args[0] . '" [#room] [on/off]"', $c );
				break;
		}
		break;

	case "wt":
		switch( strtolower( $args[1] ) ) {
			case 'all':
				$msg = $argsE[2];
				if ( strtolower( $msg ) == '' ) {
					// Show the message for all users
					$msg = $welcome->getAll( $c );
					if( $msg !== FALSE ) $dAmn->say( 'Welcome message is currently set to:' . "<br>" . $msg, $c );
					else $dAmn->say( 'Welcomes are currently off', $c );
				} elseif ( strtolower( $msg ) == 'clear' ) {
					// Clear the message for all users
					$dAmn->say( 'Welcome message erased.', $c );
					$welcome->clearAll( $c );
				} else {
					// Set the message for all user
					$welcome->setGlobalsLast( $c );
					$welcome->setCombineOff( $c );
					$welcome->setSwitchOn( $c );
					$welcome->setOrder( $c, "0,0,1" );
					$welcome->setAll( $c, $msg );
					$dAmn->say( 'Everyone that joins will now recieve the message:' . "<br>" . $msg, $c );
				}
				break;
			case 'priv':
				$msg = $argsE[3];
				if ( strtolower( $msg ) == '' ) {
					// Show the message for the privclass
					$msg = $welcome->getPriv( $args[2], $c );
					if ( $msg !== false ) $dAmn->say( 'Welcome message for privclass "' . $args[2] . '" is currently set to:' . "<br>" . $msg, $c );
					else $dAmn->say( 'There is no welcome for privclass "' . $args[2] . '"', $c );
				} elseif ( strtolower( $msg ) == 'clear' ) {
					//clear the message for privclass
					$dAmn->say( 'Welcome for privclass "' . $args[2] . '" erased', $c );
					$welcome->clearPriv( $args[2], $c );
				} else {
					$welcome->setGlobalsLast( $c );
					$welcome->setCombineOff( $c );
					$welcome->setSwitchOn( $c );
					$welcome->setOrder( $c, "0,1,0" );
					$dAmn->say( 'Welcome for privclass "' . $args[2] . '" is now set to:' . "<br>" .
						$msg, $c );
					$welcome->setPriv( $args[2], $c, $msg );
				}
				break;
			case 'indv':
				$welcome->setGlobalsLast( $c );
				$welcome->setCombineOff( $c );
				$welcome->setSwitchOn( $c );
				$welcome->setOrder( $c, "1,0,0" );
				$dAmn->say( "Each user will receive their own custom welcome which can be set with the {$config['bot']['trigger']}welcome command.", $c );
				break;
			case 'status':
				global $config;
				$x = $welcome->getOrder( $c );
				if( !$welcome->getSwitch( $c ) ) $say = "Welcome messages are turned off.";
				elseif( $x['combine'] !== FALSE || $x['globalFirst'] !== FALSE ) $say = "Custom welcome setup, use the command: " . $config['bot']['trigger'] . "wtadmin " . rChatName( $c ) . " status";
				elseif( $x['indv'] == 0 && $x['priv'] == 0 && $x['all'] == 1 ) $say = "There is one welcome for all users.";
				elseif( $x['indv'] == 0 && $x['priv'] == 1 && $x['all'] == 0 ) $say = "Each privclass has it's own welcome.";
				elseif( $x['indv'] == 1 && $x['priv'] == 0 && $x['all'] == 0 ) $say = "Users recieved their own custom welcome messages set by the {$config['bot']['trigger']}welcome command.";
				elseif( $x['indv'] == 0 && $x['priv'] == 0 && $x['all'] == 0 ) $say = "Welcome messages are turned off.";
				else $say = "Error or custom welcome setup, use the command: " . $config['bot']['trigger'] . "wtadmin " . rChatName( $c ) . " status.";
				$dAmn->say( $say,$c );
				break;
			case 'on':
				$welcome->setSwitchOn( $c );
				$dAmn->say( 'Welcome messages have been turned on.', $c );
				break;
			case 'off':
				$welcome->setSwitchOff( $c );
				$dAmn->say( 'Welcome messages have been turned off.', $c );
				break;
			default:
				$dAmn->say( '"all (message)" Set an all users welcome message for all rooms.' . "<br>" .
					'"priv [privclass name] (message)" Set welcome message for all users of [priv] privclass' . "<br>" .
					'"indv" Set welcomes to use personal welcome messages set with the "welcome" command.' . "<br>" .
					'When used without (message) it prints the currently set message.' . "<br>" .
					'When the message is "clear" the current message is removed.', $c );
				break;
		}
		break;
}
save_config( 'welcome' ); //Save welcome before ending
?>