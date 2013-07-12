<?php
// DANTE SCRIPT ======
// Name: Everyone
// Description: Just like the command everyone hates.
// Author: Wizard-Kgalm 
// Version: 1.0
// Priv: default
// Help: ??everyone (#room) message" The #room is optional.
// ====================
( $args[1] == rChatName( $args[1] ) ) ? $room = $args[1] : $room = $c;
( $args[1] == rChatName( $args[1] ) ) ? $msg = $argsE[2] : $msg = $argsE[1];
if( is_object( $dAmn->room[strtolower( $room )] ) ) {
	$listing = $dAmn->room[strtolower( rChatName( $room ) )]->getHierarchy( TRUE );
	$say = "";
	foreach( $listing as $rooms => $zilla ){
		$say .= implode(" ", array_keys( $zilla ) ) . " ";
	}
	$dAmn->say( "<abbr title=' {$say} '></abbr> {$msg} <abbr title='away'></abbr>", $room );
}else
	return $dAmn->say( "Not in $args[1], cannot continue.", $c );
?>
