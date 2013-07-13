<?php
/* Module Header */
// DANTE MODULE ======
// Name: bZikes
// Description: Bot Zikes. This modules brings Zikes to Dante! Some assistance provided by megajosh2 and Chaos21 ;D It goes mostly with input.
// Author: Wizard-Kgalm 
// Version: 1.0a
// Notes: 
// ====================
$modules[$m] = new Module($m,1); 
//$modules[$m] = new Module($m,0);
$modules[$m]->setInfo(__FILE__);
$tt = $config['bot']['trigger'];

$modules[$m]->addCmd('bzikes','bzikes',0,1);
$modules[$m]->addevt('recv-msg','bzikes',-1,1);
$modules[$m]->addevt('recv-action','bzikes',-1,1);
$modules[$m]->addHelp('bzikes','Bot Zikes. Usage: <b>'.$tt.'bzikes on/off/add/del/check <i>[:code:] [thumbnumber]</i></b>.<sup><br><b>'.$tt.'bzikes add <i>:code: thumbnumber</i></b> adds a code and the thumb NUMBER of a thumb. Do not include the :thumb: part, you only need the number. For instance, to use :thumb940393804093: just say "940393804093".<br><b>'.$tt.'bzikes on/off</b> toggles whether or not bZikes is on and checking input messages for codes that exist so that it replace them with the thumb.<br><b>'.$tt.'bzikes del <i>:code:</i></b> deletes a :code: along with the associated thumb number off the bZikes list.');