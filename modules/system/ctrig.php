<?php
$NewTrig = $args[1];
if ( $NewTrig ) {
	if( strtolower( $args[2] ) == 'confirm' ) {
		$config->bot['trigger'] = $NewTrig;
		$config->save_info( "./config/bot.df", $config->bot );
		$dAmn->say( "$f Trigger has been temporarily set to $NewTrig", $c );
	} else {
		$dAmn->say( "$f Action not confirmed, trigger has not been changed.", $c );
	}

} else {
	$dAmn->say( "You didn't give me a new trigger.", $c );
}
?>