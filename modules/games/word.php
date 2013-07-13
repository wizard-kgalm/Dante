<?php

$args = $argsF;

$args = ( $args > 50 ) ? ( 50 ) : ( $args );

if( $args == false || is_numeric( $args ) == false ) { 
	$args = mt_rand( 4, 10 ); 
}

if( !is_array( $vowels ) ) $vowels = array( 'a', 'e', 'i', 'o', 'u' );
if( !is_array( $cons ) ) $cons = array('b', 'c', 'd', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'u', 'v', 'w', 'tr',
'cr', 'br', 'fr', 'th', 'dr', 'ch', 'ph', 'wr', 'st', 'sp', 'sw', 'pr', 'sl', 'cl');

$num_vowels = count($vowels);
$num_cons 	= count($cons);

$res = '';
for ( $i = 0; $i < $args; $i++ ) {
	$res .= $cons[rand( 0, $num_cons - 1 )] . $vowels[rand( 0, $num_vowels - 1 )];
}
$word = substr( $res, 0, $args );
$dAmn->say( "The word generator has created your new word: <b>$word</b>", $c );
?>