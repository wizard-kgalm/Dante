<?php
// DANTE SCRIPT ======
// Name: Cgen
// Description: Random hex generator.
// Author: Wizard-Kgalm
// Version: 1.1
// Priv: 0
// Help: "{tr}Cgen" jut generates 6 hexidecimals to use.. For the old dAmnColors script.
// ====================
$d = array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 0, 'a', 'b', 'c', 'd', 'e', 'f', );
for( $i =0; $i<6; $i++ ){
  $a .= $d[array_rand( $d )];
}
 $dAmn->say( $a, $c );
?>
