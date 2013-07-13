<?php
//Uses Tarbot AI system.
$namePart = substr( $message, 0, strlen( $config['bot']['username'] ) + 1 );
if ( $namePart == $config['bot']['username'] . ":" || $namePart == $config['bot']['username'] . "," ) {
	$aiMessage = trim( substr( $message, strlen( $config['bot']['username'] ) + 1 ) );
	$awayStr = "I am currently away. Reason:";
	if ( substr( $aiMessage, 0, strlen( $awayStr ) ) == $awayStr ){ return false; } //Preventing answer to a dAmn.extend away message.
	if ( $aiMessage !== "trigcheck" ) {
		//The actual AI is on a server.
		$response = file_get_contents("http://kato.botdom.com/respond/" . $from . "/" . base64_encode( $aiMessage ) );

		$dAmn->say( $f  .": ". $response, $c );
	}
}
//Clear variables because the bot would otherwise pass variables on to the next module.
unset( $aiMessage, $awayStr, $namePart );
?>