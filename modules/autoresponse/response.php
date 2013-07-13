<?php
$ars =& $config['responses']; // Create a reference.
global $rFlood;

if(!is_array($ars)){
	$ars = array();
}
$lol = false;
if(isset($config['responses']['offrooms'][strtolower($c)])){
	$lol = TRUE;
}
$auser = false;
if(isset($config['responses']['ignore'][strtolower($from)])){
	$auser = TRUE;
}
if(!$lol){
	if(!$auser AND ( !$rFlood->check( $from ) )){
		foreach($ars as $para => $noid){
			if(stristr($message,$para)){
				$fnorder 	= 	new fnorder;
				$fnord 		= 	$fnorder->fnord();
				$answer 	= 	$ars[$para]['val'];
				$answer 	= 	str_replace( "{from}", $from, $answer );
				$answer 	= 	str_replace( "{args}", $argsE[2], $answer );
				$answer 	= 	str_replace( "{fnord}", $fnord, $answer );
				foreach( $fnorder->replace as $find => $replace ) {
					while( preg_match("/" . preg_quote($find) . "/", $answer, $matches ) ) {
						$answer = preg_replace("/" . preg_quote( $matches[0] ) . "/", $replace[array_rand( $replace ) ], $answer, 1 );
					}
				}
				$dAmn->say($answer,$c);
			}
		}
	
/*if ( ( array_key_exists( $message,$ars ) ) AND ( !$rFlood->check( $from ) ) ) {
	//Format the autoresponse
	$fnorder 	= 	new fnorder;
	$fnord 		= 	$fnorder->fnord();
	$answer 	= 	$ars[$message]['val'];
	$answer 	= 	str_replace( "{from}", $from, $answer );
	$answer 	= 	str_replace( "{args}", $argsE[2], $answer );
	$answer 	= 	str_replace( "{fnord}", $fnord, $answer );
	foreach( $fnorder->replace as $find => $replace ) {
		while( preg_match("/" . preg_quote($find) . "/", $answer, $matches ) ) {
			$answer = preg_replace("/" . preg_quote( $matches[0] ) . "/", $replace[array_rand( $replace ) ], $answer, 1 );
		}
	}
	$fail = FALSE;
	$dAmn->say( $answer, $c );
	unset( $fnorder, $fnord, $answer, $find );
}unset($ars);
*/
//Clear variables because the bot would otherwise pass variables on to the next module.
unset($ars);
}else return;
}else return;
unset($ars);