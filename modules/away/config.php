<?php
/* Module Header */
// DANTE MODULE ======
// Name: Away
// Description: Away messages for users that will be away from the keyboard for awhile.
// Author: SubjectX52873M
// Version: 0.1
// Note: This is the first Dante module ever written. :)
// ====================
//Activated on startup 
$modules[$m] = new Module($m,1); 
//Deactivated on startop
//$modules[$m] = new Module($m,0); //Uncomment to use this option and then comment out the one about.
$modules[$m]->setInfo(__FILE__); //NO TOUCHY or file will not load properly
/* Commands and Events below here */

$modules[$m]->addCmd('setaway','commands',0,1);
$modules[$m]->addHelp('setaway','"away (reason)" Sets you as away on the bot. Use "back" to set yourself back.');

$modules[$m]->addCmd('away','commands',0,1);
$modules[$m]->addHelp('away','"away (reason)" Sets you as away on the bot. Use "back" to set yourself back.');

$modules[$m]->addCmd('setback','commands',0,1);
$modules[$m]->addHelp('setback','"setback" Sets you back from away.');

$modules[$m]->addCmd('back','commands',0,1);
$modules[$m]->addHelp('back','"back" Sets you back from away.');

$modules[$m]->addEvt('recv-msg','msg',0,1);
?>