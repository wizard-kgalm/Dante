<?php

// DANTE SCRIPT ======
// Name: Roll any die
// Description: Rolls any #d#
// Author: SubjectX52873M
// Version: 1.0
// Help: "roll #d#" Roll 1d20 or just d20 for 1d20
// ====================

$cmd		=	strtolower( trim( $argsE[1] ) );
$roll		=	str_replace( " ", "", $cmd );
$roll		=	str_replace( "%", "100", $roll );
$rollone	=	ltrim( $roll, "d" );
$rollmore	=	explode( "d", $roll,2);

if($rollmore[0] > 50 or $rollmore[1] > 10000){
	$dAmn->say( "Number out of range.", $c );
}else{
	$rand = 0;
	
	if ( is_numeric( $rollone ) ) $rand = mt_rand( 1, $rollone );
	elseif ( count( $rollmore ) == 2 )
		if ( ( is_numeric( $rollmore[1] ) ) && ( is_numeric( $rollmore[0] ) ) )
			for ( $i = 1; $i <= $rollmore[0]; $i++ ) 
				$rand += mt_rand( 1, $rollmore[1] );
	if ( $rand!=0 ) $dAmn->say( "$f $rand", $c );
	else $dAmn->say( "Improper roll, try 1d20 or similar.", $c );
}
?>