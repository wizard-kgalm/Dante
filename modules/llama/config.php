<?php
/* Module Header */
// DANTE MODULE ======
// Name: llama
// Description: Send llamas to either a specified user or a random user.
// Author: Wizard-Kgalm
// Version: 1.5
// ====================
$modules[$m] = new Module($m,1); 
$modules[$m]->setInfo(__FILE__); 

//!gallery
$modules[$m]->addCmd('rllama','llama','d',1);
$modules[$m]->addCmd('llama','llama','d',1);
$modules[$m]->addCmd('rspama','llama', 99,1);
$modules[$m]->addCmd('fullama','llama',99,1);
$modules[$m]->addCmd('cookieclear','llama',99,1);
$modules[$m]->addHelp('cookieclear', "{$tr}cookieclear. Clears the cookies of all the stored logins for llama purposes.");
$modules[$m]->addHelp('fullama',"{$tr}fullama [username] sends a llama to the specified username with ALL the accounts on the logins list. It will take a while.");
$modules[$m]->addHelp('rspama',"{$tr}rspama # {user} Sends llamas to either a specified number of random deviants or 50. If you provide a number and {user} it'll try and send those random people llamas from that account.");
$modules[$m]->addHelp('rllama',"{$tr}rllama {username} Sends a llama to a random user. If you include a username there and it's on your logins list, it'll use that dA account instead. ");
$modules[$m]->addHelp('llama',"{$tr}llama [username] {user2} where username is a specified dA username. This sends the specified username a llama. If you include another name for user2, it'll use that dA account to send the llama.");