<?php
/* Module Header */
// DANTE MODULE ======
// Name: alert
// Description: wat
// Author: Wizard-Kgalm
// Version: 1.0
// ====================
$modules[$m] = new Module($m,1); 
$modules[$m]->setInfo(__FILE__); 

//!gallery
$modules[$m]->addEvt('recv-msg','alert',1);
$modules[$m]->addEvt('recv-action','alert',1);
$modules[$m]->addEvt('recv-msg','DJ',1);
$modules[$m]->addEvt('recv-action','DJ',1);
$modules[$m]->addHook('recv-msg','alert',1);
$modules[$m]->addCmd('xd','xd',99,1);
$modules[$m]->addHook('recv-action','alert',1);
$modules[$m]->addEvt('recv-join','sioraine',0,1);
$modules[$m]->addCmd('spotted','sioraine',99,1);
$modules[$m]->addCmd('times','sioraine',99,1);
$modules[$m]->addCmd('kspot','sioraine',99,1);
$modules[$m]->addCmd('ktimes','sioraine',99,1);