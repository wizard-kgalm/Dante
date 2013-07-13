<?php
/* Module Header */
// DANTE MODULE ======
// Name: echochan
// Description: tharglet's version of echo
// Author: tharglet
// Version: 1.0
// ====================
//Activated on startup 
//$modules[$m] = new Module($m,1); 
//Deactivated on startop
$modules[$m] = new Module($m,1); 
$modules[$m]->setInfo(__FILE__); //NO TOUCHY or file will not load properly
/* Commands and Events below here */

/*echo */
$modules[$m]->addCmd('echochan','techo',99,1); 
$modules[$m]->addHelp('echochan',"Echoes what\'s said in one channel to another - if only one channel is specified, it is assumed the current channel is the destination channel (ie the channel where the echo is displayed)<br>echochan on [channel1] [channel2] - repeat what people say from channel1 to channel2<br>echochan off [channel1] [channel2] - turns off the echo from channel1 to channel2<br>sechochan del [channel] [channel] - deletes the specified echo<br>echochan list - lists all active echoes<br>echochan clearall - clears all echoes");

$modules[$m]->addHook('recv-msg','echoparse',-1,1);
$modules[$m]->addHook('recv-action','echoparse',-1,1);
$modules[$m]->addHook('recv-join','echoparse',-1,1);
$modules[$m]->addHook('recv-part','echoparse',-1,1);
$modules[$m]->addHook('recv-kicked','echoparse',-1,1);
$modules[$m]->addHook('recv-privchg','echoparse',-1,1);

if(!isset($config['techo'])) {
	$config['techo'] = array();
}

?>