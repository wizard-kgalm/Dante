<?php
// DANTE SCRIPT ======
// Name: Everyone
// Description: Just like the command everyone hates.
// Author: Wizard-Kgalm 
// Version: 1.0
// Priv: default
// Help: ??everyone (#room) message" The #room is optional.
// ====================
if( $args[1][0] == "#" ){
  if( is_object( $dAmn->room[strtolower( $args[1] )] ) ) {
		$listing = $dAmn->room[strtolower( rChatName( $args[1] ) )]->getHierarchy( TRUE );
		$say = "";
		foreach( $listing as $rooms => $zilla ){
			$say .= implode(" ", array_keys( $zilla ) ) . " ";
		}
		$dAmn->say( "<abbr title=' {$say} '></abbr> {$argsE[2]} <abbr title='away'></abbr>", $args[1] );
	}else
		return $dAmn->say( "Not in $args[1], cannot continue.", $c );
}else if( $args[1][0] != "#" ){
	$listing = $dAmn->room[strtolower( rChatName( $c ) )]->getHierarchy( TRUE );
	$say = "";
	foreach( $listing as $rooms => $zilla ){
		$say .= implode(" ", array_keys( $zilla ) ) . " ";
	}
	$dAmn->say( "<abbr title=' {$say} '></abbr> {$argsE[1]} <abbr title='away'></abbr>", $c );
}
?>
