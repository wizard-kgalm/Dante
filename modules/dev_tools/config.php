<?php
// DANTE MODULE ======
// Name: Development Tools
// Description: Commands to help developers
// Author: SubjectX52873M
// Version: 0.3
// Notes: will be deactivited by default in Public Releases
// ====================

//Activated on startup 
$modules[$m] = new Module($m,1); 
//Deactivated on startop
//$modules[$m] = new Module($m,0); //Uncomment to use this option and then comment out the one about.
$modules[$m]->setInfo(__FILE__); //NO TOUCHY or file will not load properly
/* Commands and Events below here */

$modules[$m]->addCmd( 'debug', 'commands', 99, 1 );
$modules[$m]->addHelp( 'debug', '"debug (0-5)" check or set the debug level' );

$modules[$m]->addCmd( 'execute', 'commands', 99, 1 );
$modules[$m]->addHelp( 'execute', '"execute [php code]" Execute raw php code. Only the bot\'s owner can use this.' );

$modules[$m]->addCmd( 'vardump', 'commands', 99, 1 );
$modules[$m]->addHelp( 'vardump', '"vardump [global variable]" Do a var_dump() on a global variable.' );

$modules[$m]->addCmd( 'reconfig', 'commands', 99, 1 );
$modules[$m]->addHelp( 'reconfig', '"reconfig" Save the bot\'s configuration. This is intended for debugging.' );

$modules[$m]->addCmd( 'saveconfig', 'commands', 99, 1 );
$modules[$m]->addHelp( 'saveconfig', '"saveconfig" Save the bot\'s configuration. This is intended for debugging.' );


?>