<?php
//Adapted from Noodlebot command

$t = $args[1];
$joinChat = rChatName( $args[2] );
$formatChat = rChatName( $joinChat );

switch( $t ) {
  case 'list':
		$txt = "<sup><u><strong>Autojoined Rooms</strong></u> <br/>";
		foreach($config['bot']['rooms'] as $chan) {
			$txt .= strtolower( rChatName($chan))."\n";
		}
		$txt = rtrim( $txt, "\n" );
		$txt .= '</sup>';
		$dAmn->say( $txt, $c );
		break;
	case 'add':
		if( !in_array( strtolower( rChatname( $joinChat ) ), $config['bot']['rooms'] ) ) {
			$config['bot']['rooms'][] = rChatName( strtolower( $joinChat ) );
			save_config( 'bot' );
			$say = "$f Added $formatChat.";
		} else $say = "$f $formatChat is already in the autojoin list.";
		$dAmn->say( $say, $c );
		break;
	case 'del':
		$d = FALSE;
		foreach( $config['bot']['rooms'] as $i => $chan ) {
			//Really must match all types of rooms
			if( strtolower( $joinChat ) != strtolower( rChatName( $chan ) ) ) {
				$config['bot']['rooms'][$i] = strtolower( rChatName( $chan ) );
				$chan = strtolower( $chan );
			} else {
				$d = TRUE;
				$config['bot']['rooms'][$i] = NULL;
				unset( $config['bot']['rooms'][$i] );
			}
		}
		if ( $d ) {
			sort( $config['bot']['rooms'] );
			save_config( 'bot' );
			$dAmn->say( "$f Removed $formatChat from auto-join list", $c );
		}
		else $dAmn->say( "$f $formatChat not in auto-join list.", $c );
		break;
	default:
		$dAmn->say( "Usage: [list/add/del] [#room]", $c );
		break;
}
?>
