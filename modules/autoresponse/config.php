<?php
// DANTE MODULE ======
// Name: Autoresponse
// Description: Autoresponse stuff
// Author: SubjectX52873M
// Version: 0.4
// ====================
//Activated on startup
$modules[$m] = new Module($m,1);
//Deactivated on startup
//$modules[$m] = new Module($m,0); //Uncomment to use this option and then comment out the one above.

$modules[$m]->setInfo(__FILE__); //NO TOUCHY or file will not load properly

//End file header

$modules[$m]->addCmd( 'ar', 'ar', 75, 1 );
$modules[$m]->addHelp( 'ar','"ar [add/del] [command] [args]" This creates messages that can be called like commands. <br>' .
'"ar list" List available autoreponses');
$modules[$m]->addCmd( 'aroom', 'ar', 0, 1 );
$modules[$m]->addCmd( 'aignore', 'ar', 0, 1 );
$modules[$m]->addCmd( 'response', 'ar', 75, 1 );
$modules[$m]->addHelp( 'response', '"response [add/del] [command], [args]" This makes the bot react to text in said in the room.. <br>' .
'"response list" List available reponses' );

$modules[$m]->addEvt( 'recv-msg', 'response', 'd', 1 );
$modules[$m]->addEvt( 'cmd-fail', 'event', 'd', 1 );

global $rFlood;
$rFlood = new flood(true, 30, 2, 30 );
?>