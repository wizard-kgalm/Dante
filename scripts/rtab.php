<?php
// DANTE SCRIPT ======
// Name: Random Tab
// Description: Randomly tab someone with the message you want.
// Author: Wizard-Kgalm 
// Version: 1.0
// Priv: default
// Help: rtab message :3
// ====================
$rtablist = $dAmn->room[strtolower( rChatName( $c ) )]->getHierarchy( TRUE );
foreach( $rtablist as $privnum => $rtabbing ){
  $listing .=  implode( ' ', array_keys( $rtabbing ) )." ";
}
$listing2 = explode( " ",$listing );
$rtab = $listing2[array_rand( $listing2 )];
$dAmn->say( ( !empty( $argsF ) ) ? "{$rtab}: $argsF" : "{$rtab}: RANDOM TABBING :eager:!", $c );
?>
