<?php
// DANTE SCRIPT ======
// Name: Forge
// Description: Forge a "quoted" message.
// Author: Wizard-Kgalm
// Version: 1.2
// Priv: 0
// Help: {tr}forge [action/username] [message]. If action, {tr}forge [action] [username] [message]. 
// ====================
if( empty( $args[1] ) ){
	return $dAmn->say( "$from: You must provide someone to forge messages from.", $c );
}
$dateobj = date_create( "now", timezone_open( date_default_timezone_get() ) );
$say = date_format( $dateobj, "g:i:sA" )." ";
if(  $args[1] == "action" ){
	($args[2] == rChatName( $args[2] ) ) ? $todochat = $args[2]  : $todochat = $c;
	($args[2] == rChatName( $args[2] ) ) ? $msg      = $argsE[4] : $msg      = $argsE[3];
	($args[2] == rChatName( $args[2] ) ) ? $un       = $args[3]  : $un       = $args[2];
} else {
	($args[1] == rChatName( $args[1] ) ) ? $todochat = $args[1]  : $todochat = $c;
	($args[1] == rChatName( $args[1] ) ) ? $msg      = $argsE[3] : $msg      = $argsE[2];
	($args[1] == rChatName( $args[1] ) ) ? $un       = $args[2]  : $un       = $args[1];
}
( strtolower( $args[1] ) == "action" ) ? $say .= "<i><b>* {$un}</b> $msg </i>" : $say .= "<b><{$un}></b> $msg";
$dAmn->say( $say, $todochat );
?>
