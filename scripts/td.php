<?php
// DANTE SCRIPT ======
// Name: Date/Time
// Description: Displays the current date and time.
// Author: SubjectX52873M/Nol888
// Version: 1.1
// Priv: default
// Help: Displays the current date and time. (Optionally accepts a timezone as the first argument.)
// ====================
$tz = ( $args[1] ) ? ( timezone_open( $args[1] ) ) : ( timezone_open( date_default_timezone_get() ) );
$dateobj = date_create( "now", $tz );

$dAmn->say( "It is " . date_format( $dateobj, "g:i:sA" ) . " on " .
  date_format( $dateobj, "l F d, Y" ) . " (" . date_format( $dateobj, "O" ) . " GMT)", $c );
?>
