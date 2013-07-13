<?php
global $Timer;
$time = $args[1];
$data = array();
if( !is_numeric( $time ) ) {
	$dAmn->say( 'Usage: [seconds] [message]', $c );
	return;
}
$dAmn->say("Setting timer for $time seconds", $c );
$data['room'] = $c;
$data['say'] = $argsE[2];
$Timer->register( $time, $data, "modules/games/timer.php", null );
?>