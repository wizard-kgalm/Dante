<?php
/* Module Header */
// DANTE MODULE ======
// Name: Welcome
// Description: Welcome Module for Dante
// Author: SubjectX52873M
// Version: 0.9
// ====================
//Activated on startup 
$modules[$m] = new Module($m,1); 
//Deactivated on startop
//$modules[$m] = new Module($m,0); //Uncomment to use this option and then comment out the one about.
$modules[$m]->setInfo(__FILE__); //NO TOUCHY or file will not load properly
/* Commands and Events below here */
$modules[$m]->addCmd('welcome','cmd',0,1);
$modules[$m]->addHelp('welcome','This only sets stuff for the users. Admins need to use "wtadmin"'."\n".
'"welcome" shows your current welcome message'."\n".
'"welcome [message]" sets your welcome message'."\n".
'"welcome clear" erases your welcome message');

$modules[$m]->addCmd('wtroom','cmd',75,1);
$modules[$m]->addHelp('wtroom','Administrative command for the welcome module. You can set up the cuurent room welcome list here.'."\n".
'"wtroom (#room) [order/combine/first/all/priv/status]" type "wtadmin [command] ?" for help on individual commands.'."\n".
'"wtroom (#room) [on/off]" turn welcomes on or off.'."\n".
'(#room) is optional, without it the bot assumes you mean the current room. '.
'This command can not change a welcome for a single user. Use the "do" command along with the "welcome" command for that.');

$modules[$m]->addCmd('wtglobal','cmd',75,1);
$modules[$m]->addHelp('wtglobal','Administrative command for the welcome module. You can set up the "all rooms" welcome list here and other things.'."\n".
'"wtglobal [order/combine/first/all/priv/status]" type "wtadmin [command] ?" for help on individual commands.'."\n".
'This command can not change a welcome for a single user. Use the "do" command along with the "welcome" command for that.');

$modules[$m]->addCmd('wt','cmd',25,1);
$modules[$m]->addHelp('wt','Allow you to change the welcome message for the room.'."\n".
'"wt all [message]" set the welcome message for the entire room.'."\n".
'"wt priv [privclass name] [message]" set the welcome message for a priv class.'."\n".
'"wt indv" Let each user make their own welcome.'."\n".
'"wt [on/off]" Turn welcomes on and off.');

$modules[$m]->addEvt('recv-join','userjoined','d',1);
$modules[$m]->addEvt('join-success','botjoined','d',1);

require_once(f('modules/welcome/class.php'));
?>