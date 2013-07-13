<?php
/* Module Header */
// DANTE MODULE ======
// Name: colors
// Description: Color Checker checks the dAmn colors list for the colors of a user.
// Author: Wizard-Kgalm
// Version: 1.0
// Notes:
// ====================

$modules[$m] = new Module($m,1); 
//$modules[$m] = new Module($m,0);
$modules[$m]->setInfo(__FILE__);


$modules[$m]->addCmd('colors','commands',0,1);
//$modules[$m]->addCmd('cupdate','color',0,1);
$modules[$m]->addHelp("colors","colors checks the dAmn colors list for the colors of the provided user. It can also set/change colors or create a dAmnColors account.<sup><br><b>{$tr}colors (check) <i>username</i></b> checks the colors of the provided user. The check parameter is optional.<br><b>{$tr}colors change/set <i>username password color1 color2</i></b> Changes the colors of the username given. The password is your dAmnColors Password, not your dA. If you leave the password, color1, and color2 blank, you can do the rest in the bot window. You can include the password and just do the colors from the window, and you can leave only the second color blank and put in to the window. You must provide 2 colors, and one must be different than the ones you already have.");
//$modules[$m]->addHelp('colors','Usage: <b>'.$tr.'cupdate. This command updates the colors list.');