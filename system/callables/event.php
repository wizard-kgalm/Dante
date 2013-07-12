<?php
/**
 *Dante Core File
 *
 * Event file, this calls the modules that have registered events
 *
 *"If it's open source it's not stealing, it's sharing" - Olyrion
 *@copyright SubjectX52873M (2006)
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@package Dante
 *@license http://www.opensource.org/licenses/gpl-license.php GNU General Public License
 *@version 0.6
 */
global $events, $user, $config, $dAmn;
$f = $from;
$evtby = FALSE;
if( isset( $c ) ) {
  $cc = strtolower( rChatName( $c ) );
}

if( !isset( $events[$event] ) ) { internalMessage("Trigger \"" . $event . "\" has not been registered."); return false; }

foreach( $events[$event] as $evt ) {
	if( $evtby && isset( $evt['priv'] ) && !$user->has( $evtby, $evt['priv'], TRUE ) ) {
		debug( "Incoming event \"" . $event . "\" was ignored as user " . $evtby . " does not have the access level.", 3 );
	} elseif( !$evt['status'] ) {
		debug( "Incoming event \"" . $event . "\" is switched off and was ignored.", 3 );
	} else {
		//For hook events allow all data to pass.
		$EVENT_FILE_LOAD = TRUE;
		if ( $evt['hook'] === FALSE) {
			if ( $event == "recv-msg" || $event == "recv-action" ) {
				if( strtolower( $from ) == strtolower( $config->bot['username'] ) ) $EVENT_FILE_LOAD = FALSE;
			}

			//Skip event for users in the ignore list.
			if ( $user->isIgnored( $from ) && $from != $config->bot['owner'] ) {
				debug( "User: $from is in the ignore list, removing event." );
				$EVENT_FILE_LOAD = FALSE;
			}
		}
		/**
			* Load the module :)
			*/
		if( $EVENT_FILE_LOAD ) {
			include ( f( "modules/" . $evt['file'] ) );
		}
	}
}
?>
