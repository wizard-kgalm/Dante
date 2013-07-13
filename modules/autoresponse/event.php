<?php
$ars =& $config['autoresponses']; // Create a reference

if ( !empty( $ars[$commandname] ) ) {
	//Format the autoresponse
	$fnorder 	= 	new fnorder;
	$fnord 		= 	$fnorder->fnord();
	$answer 	= 	$ars[$commandname]['val'];
	$answer 	= 	str_replace( "{from}", $from, $answer );
	$answer 	= 	str_replace( "{args}", $argsF, $answer );
	$answer 	= 	( empty( $argsF ) ) ? ( $answer = str_replace( "{args/from}", $from, $answer ) ) : ( $answer = str_replace( "{args/from}", $argsF, $answer ) );
	$answer 	= 	str_replace( "{fnord}", $fnord, $answer );
	foreach( $fnorder->replace as $find => $replace ) {
		while( preg_match( "/" . preg_quote($find) . "/", $answer, $matches ) ) {
			$answer = preg_replace("/" . preg_quote( $matches[0] ) . "/", $replace[array_rand( $replace )], $answer, 1 );
		}
	}
	$fail = FALSE;
	$dAmn->say( $answer, $c );
} else {
	debug('Failed to match AR');
}

//Clear variables because the bot would otherwise pass variables on to the next module.
unset( $fnorder, $ars, $fnord, $answer, $find );

?>