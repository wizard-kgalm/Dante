<?php
/* Module Header */
// DANTE MODULE ======
// Name: AI
// Description: Contains the AI that was coded into the bot before version 0.5
// Author: SubjectX52873M
// Version: 1.0
// ====================
// Activated on startup
$modules[$m] = new Module($m,0);
// Deactivated on startup
// $modules[$m] = new Module($m,0); //Uncomment to use this option and then comment out the one about.

$modules[$m]->setInfo(__FILE__); //NO TOUCHY or file will not load properly

/* Commands and Events below here */

$modules[$m]->addEvt( 'recv-msg', 'ai', 'd', 1 );
?>