<?php

// DANTE MODULE ======
// Name: Bot Notes
// Description: This allows a user to leave a note 
// Author: SubjectX52873M
// Version: 0.1
// Note: This is the first Dante module ever written. :)
// ====================

//Activated on startup 
$modules[$m] = new Module( $m, 1 ); 
//Deactivated on startip
//$modules[$m] = new Module($m,0); //Uncomment to use this option and then comment out the one about.
$modules[$m]->setInfo( __FILE__ ); //NO TOUCHY or file will not load properly
/* Commands and Events below here */

$modules[$m]->addCmd( 'note', 'commands', 0, 1 );
$modules[$m]->addHelp( 'note', '"note [user] [message]" leave a note for user. ' );

$modules[$m]->addEvt( 'recv-msg', 'msg', 0, 1 );
$modules[$m]->addEvt( 'recv-join', 'msg', 0, 1 );
?>