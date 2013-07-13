<?php
/* Module Header */
// DANTE MODULE ======
// Name: Searches
// Description: Futurism search module ported to Dante
// Author: SubjectX52873M, Nol888
// Version: 1.0
// Notes: Ported by Subjetx52873M, Expanded by Nol888
// ====================
$modules[$m] = new Module( $m, 1 );
$modules[$m]->setInfo( __FILE__ );

//Tarbot
$modules[$m]->addCmd( "/^g(o+)gle$/", "google", 'd', 1 );
$modules[$m]->addHelp( "/^g(o+)gle$/", "Search google, the more ooo's, the more results. Also with *nix style commands (--option)" );

$modules[$m]->addCmd( "gcalc", "gcalc", 0, 1 );
$modules[$m]->addHelp( "gcalc", "Google Calc search." );

$modules[$m]->addCmd( "irpg", "irpg", 0, 1 );
$modules[$m]->addHelp( "irpg", "Get a user's iRPG scores." );

// Misc function for parsing webpages.
if( !function_exists( "removeli" ) ) {
	function removeli( $s ) {
		return str_ireplace( "</li>", '', $s );
	}
}
if( !function_exists( "getname" ) ) {
	function getname( $s ) {
		$s = eregi_replace( "<a[^>]*>", '', $s );
		$s = eregi_replace( "</a>.*", '', $s );
		return trim( $s );
	}
}
?>