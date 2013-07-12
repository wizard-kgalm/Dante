<?php
// DANTE SCRIPT ======
// Name: Current Time
// Description: Displays the current time plus the time zone
// Author: SubjectX52873M/Nol888
// Version: 0.5
// Help: Optionally accepts a timezone as the first argument.
// ====================
$tz = ( $args[1] ) ? ( timezone_open( $args[1] ) ) : ( timezone_open( date_default_timezone_get() ) );
$dateobj = date_create( "now", $tz );
$timestamp = date_format( $dateobj, "H:i:s (e)" );
$dAmn->say( "$f The time is " . $timestamp, $c );
?>
