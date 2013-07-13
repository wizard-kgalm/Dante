<?php
if ( $args[1] == '' ) {
	$dAmn->say( '"gcalc [expression]" Gets the Google Calc answer for [expression]', $c );
} else {
	$page = file_get_contents( "http://www.google.com/search?q=" . urlencode( $argsF ) );
	$array = explode( "<font size=+1><b>", $page );
	if( $array[1] ) {
		$array = explode( "</b></h2></td></tr>", $array[1] );
		$array = strip_tags( $array[0] );
		$array = preg_replace( "/([0-9]) ([0-9])/", "\\1,\\2", $array );
		$dAmn->say( "$from: $array", $c ); 
	} else {
		$dAmn->say( "<b>:dev$from:</b> Google Calc returned nothing for <b>$argsF</b>.", $c );
	}
}
?>