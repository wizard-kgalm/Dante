<?php
/* Module Header */
// DANTE MODULE ======
// Name: input
// Description: Input for Dante. Now with Zikes implemented!
// Author: Wizard-Kgalm
// Version: 2.0
// Notes:
// ====================
//Activated on startup 
$modules[$m] = new Module($m,1); 
//Deactivated on startop
//$modules[$m] = new Module($m,0); //Uncomment to use this option and then comment out the one about.
$modules[$m]->setInfo(__FILE__); //NO TOUCHY or file will not load properly
/* Commands and Events below here */
$tr = $config['bot']['trigger'];
$modules[$m]->addCmd('input','commands',99,1);
$modules[$m]->addHelp('input',"Usage: {$tr}input on/off/help. Turns on and off the input. To hear how input works, just type {$tr}input help. This is really important. DO NOT USE '|', '<', '>' or '&'. Use '%v' for |, '%a' for &, 'lt;' for <, and 'gt;' for >. Otherwise, <b>IT WILL KILL THE INPUT WINDOW</b>!!"); 
$modules[$m]->addCmd('npmsg','commands',99,1);
$modules[$m]->addCmd('me','commands',99,1);
$modules[$m]->addEvt('loop','input',0,1);
$modules[$m]->addHook('loop','input',0,1);